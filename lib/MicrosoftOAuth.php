<?php
/**
 * Microsoft OAuth Authentication Library
 * Handles Microsoft SSO login for Neutara ATS
 */

include_once(LEGACY_ROOT . '/lib/DatabaseConnection.php');

class MicrosoftOAuth
{
    private $_clientId;
    private $_clientSecret;
    private $_tenantId;
    private $_redirectUri;
    private $_db;
    
    const AUTHORIZE_URL = 'https://login.microsoftonline.com/%s/oauth2/v2.0/authorize';
    const TOKEN_URL = 'https://login.microsoftonline.com/%s/oauth2/v2.0/token';
    const GRAPH_URL = 'https://graph.microsoft.com/v1.0/me';
    
    public function __construct()
    {
        $this->_db = DatabaseConnection::getInstance();
        $this->loadCredentials();
    }
    
    /**
     * Load OAuth credentials from database or config
     */
    private function loadCredentials()
    {
        // Try to load from MeetingCredentials if available
        if (file_exists(LEGACY_ROOT . '/lib/MeetingCredentials.php'))
        {
            include_once(LEGACY_ROOT . '/lib/MeetingCredentials.php');
            $creds = new MeetingCredentials();
            $teamsCreds = $creds->getPlatformCredentials('teams');
            
            if (!empty($teamsCreds['client_id']))
            {
                $this->_clientId = $teamsCreds['client_id'];
                $this->_clientSecret = $teamsCreds['client_secret'];
                $this->_tenantId = $teamsCreds['tenant_id'];
            }
        }
        
        // Fallback to config defines
        if (empty($this->_clientId) && defined('MICROSOFT_CLIENT_ID'))
        {
            $this->_clientId = MICROSOFT_CLIENT_ID;
        }
        if (empty($this->_clientSecret) && defined('MICROSOFT_CLIENT_SECRET'))
        {
            $this->_clientSecret = MICROSOFT_CLIENT_SECRET;
        }
        if (empty($this->_tenantId) && defined('MICROSOFT_TENANT_ID'))
        {
            $this->_tenantId = MICROSOFT_TENANT_ID;
        }
        
        // Use 'common' tenant if not specified (allows any Microsoft account)
        if (empty($this->_tenantId))
        {
            $this->_tenantId = 'common';
        }
        
        // Set redirect URI
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $this->_redirectUri = $protocol . '://' . $host . '/' . CATSUtility::getIndexName() . '?m=login&a=microsoftCallback';
    }
    
    /**
     * Check if Microsoft OAuth is configured
     */
    public function isConfigured()
    {
        return !empty($this->_clientId) && !empty($this->_clientSecret);
    }
    
    /**
     * Get the authorization URL for Microsoft login
     */
    public function getAuthorizationUrl($state = null)
    {
        if (!$this->isConfigured())
        {
            return false;
        }
        
        if ($state === null)
        {
            $state = bin2hex(random_bytes(16));
        }
        
        // Store state in session for verification
        $_SESSION['microsoft_oauth_state'] = $state;
        
        $params = array(
            'client_id' => $this->_clientId,
            'response_type' => 'code',
            'redirect_uri' => $this->_redirectUri,
            'response_mode' => 'query',
            'scope' => 'openid profile email User.Read',
            'state' => $state
        );
        
        $url = sprintf(self::AUTHORIZE_URL, $this->_tenantId);
        return $url . '?' . http_build_query($params);
    }
    
    /**
     * Exchange authorization code for access token
     */
    public function exchangeCodeForToken($code)
    {
        if (!$this->isConfigured())
        {
            return array('error' => 'Microsoft OAuth not configured');
        }
        
        $url = sprintf(self::TOKEN_URL, $this->_tenantId);
        
        $params = array(
            'client_id' => $this->_clientId,
            'client_secret' => $this->_clientSecret,
            'code' => $code,
            'redirect_uri' => $this->_redirectUri,
            'grant_type' => 'authorization_code',
            'scope' => 'openid profile email User.Read'
        );
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($error)
        {
            return array('error' => 'cURL error: ' . $error);
        }
        
        $data = json_decode($response, true);
        
        if ($httpCode !== 200 || isset($data['error']))
        {
            return array(
                'error' => $data['error_description'] ?? $data['error'] ?? 'Token exchange failed'
            );
        }
        
        return $data;
    }
    
    /**
     * Get user profile from Microsoft Graph API
     */
    public function getUserProfile($accessToken)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::GRAPH_URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/json'
        ));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($error)
        {
            return array('error' => 'cURL error: ' . $error);
        }
        
        $data = json_decode($response, true);
        
        if ($httpCode !== 200 || isset($data['error']))
        {
            return array(
                'error' => $data['error']['message'] ?? 'Failed to get user profile'
            );
        }
        
        return array(
            'id' => $data['id'] ?? '',
            'email' => $data['mail'] ?? $data['userPrincipalName'] ?? '',
            'firstName' => $data['givenName'] ?? '',
            'lastName' => $data['surname'] ?? '',
            'displayName' => $data['displayName'] ?? ''
        );
    }
    
    /**
     * Find or create user by email
     */
    public function findOrCreateUser($profile, $siteID = 1)
    {
        $email = strtolower(trim($profile['email']));
        
        if (empty($email))
        {
            return array('error' => 'No email address found in Microsoft profile');
        }
        
        // First, try to find user by email
        $sql = sprintf(
            "SELECT user_id, user_name, email, access_level, site_id 
             FROM user 
             WHERE LOWER(email) = %s OR LOWER(user_name) = %s",
            $this->_db->makeQueryString($email),
            $this->_db->makeQueryString($email)
        );
        
        $rs = $this->_db->getAssoc($sql);
        
        if ($rs && !empty($rs['user_id']))
        {
            // User exists, return their info
            return array(
                'user_id' => $rs['user_id'],
                'username' => $rs['user_name'],
                'email' => $rs['email'],
                'access_level' => $rs['access_level'],
                'site_id' => $rs['site_id'],
                'exists' => true
            );
        }
        
        // User doesn't exist - return error (admin must create user first)
        return array(
            'error' => 'No account found for ' . $email . '. Please contact your administrator to create an account.',
            'email' => $email
        );
    }
    
    /**
     * Process Microsoft OAuth login
     */
    public function processOAuthLogin($userInfo)
    {
        if (isset($userInfo['error']))
        {
            return $userInfo;
        }
        
        // Get user details from database
        $sql = sprintf(
            "SELECT
                user.user_id AS userID,
                user.user_name AS username,
                user.password AS password,
                user.first_name AS firstName,
                user.last_name AS lastName,
                user.access_level AS accessLevel,
                user.site_id AS userSiteID,
                user.is_demo AS isDemoUser,
                user.email AS email,
                user.categories AS categories,
                user.pipeline_entries_per_page AS pipelineEntriesPerPage,
                user.column_preferences as columnPreferences,
                user.can_see_eeo_info as canSeeEEOInfo,
                site.name AS siteName,
                site.unix_name AS unixName,
                site.user_licenses AS userLicenses,
                site.company_id AS companyID,
                site.is_demo AS isDemo,
                site.account_active AS accountActive,
                site.account_deleted AS accountDeleted,
                site.time_zone AS timeZone,
                site.default_phone_country_code AS defaultPhoneCountryCode,
                site.date_format_ddmmyy AS dateFormatDMY,
                site.is_free AS isFree,
                site.is_hr_mode AS isHrMode,
                site.first_time_setup as isFirstTimeSetup,
                site.localization_configured as isLocalizationConfigured,
                site.agreed_to_license as isAgreedToLicense
            FROM
                user
            LEFT JOIN site
                ON site.site_id = user.site_id
            WHERE
                user.user_id = %s",
            $this->_db->makeQueryInteger($userInfo['user_id'])
        );
        
        $rs = $this->_db->getAssoc($sql);
        
        if (!$rs)
        {
            return array('error' => 'Failed to load user data');
        }
        
        // Check if account is disabled
        if ($rs['accessLevel'] == ACCESS_LEVEL_DISABLED)
        {
            return array('error' => 'Your account is disabled or pending approval.');
        }
        
        // Check if account is active
        if (!$rs['accountActive'])
        {
            return array('error' => 'This site account is not active.');
        }
        
        return array(
            'success' => true,
            'userData' => $rs
        );
    }
    
    /**
     * Get redirect URI for configuration
     */
    public function getRedirectUri()
    {
        return $this->_redirectUri;
    }
    
    /**
     * Get client ID
     */
    public function getClientId()
    {
        return $this->_clientId;
    }
}
?>
