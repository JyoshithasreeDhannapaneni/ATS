<?php
/**
 * Microsoft SSO (Single Sign-On) Library
 * 
 * Handles Microsoft Azure AD OAuth 2.0 authentication for login.
 * Restricts access to specific email domains.
 * Includes PKCE (Proof Key for Code Exchange) support.
 *
 * @package    CATS
 * @subpackage Library
 */

class MicrosoftSSO
{
    private $_clientId;
    private $_clientSecret;
    private $_tenantId;
    private $_redirectUri;
    private $_allowedDomains;
    
    const GRAPH_URL = 'https://graph.microsoft.com/v1.0/me';
    
    public function __construct()
    {
        // Azure AD Configuration - loaded from config.php (which is gitignored)
        // This prevents secrets from being committed to version control
        $this->_clientId = defined('MICROSOFT_SSO_CLIENT_ID') ? MICROSOFT_SSO_CLIENT_ID : '';
        $this->_clientSecret = defined('MICROSOFT_SSO_CLIENT_SECRET') ? MICROSOFT_SSO_CLIENT_SECRET : '';
        $this->_tenantId = defined('MICROSOFT_SSO_TENANT_ID') ? MICROSOFT_SSO_TENANT_ID : 'common';
        $this->_redirectUri = defined('MICROSOFT_SSO_REDIRECT_URI') ? MICROSOFT_SSO_REDIRECT_URI : 'http://localhost:8000/oauth_callback.php';
        
        // Allowed email domains for login
        $this->_allowedDomains = array(
            'cloudfuze.com',
            'exinent.com'
        );
    }
    
    /**
     * Get the authorization URL
     */
    private function getAuthorizeUrl()
    {
        return 'https://login.microsoftonline.com/' . $this->_tenantId . '/oauth2/v2.0/authorize';
    }
    
    /**
     * Get the token URL
     */
    private function getTokenUrl()
    {
        return 'https://login.microsoftonline.com/' . $this->_tenantId . '/oauth2/v2.0/token';
    }
    
    /**
     * Generate PKCE code verifier
     * 
     * @return string Random code verifier
     */
    public function generateCodeVerifier()
    {
        $randomBytes = random_bytes(32);
        return rtrim(strtr(base64_encode($randomBytes), '+/', '-_'), '=');
    }
    
    /**
     * Generate PKCE code challenge from verifier
     * 
     * @param string $codeVerifier The code verifier
     * @return string The code challenge
     */
    public function generateCodeChallenge($codeVerifier)
    {
        $hash = hash('sha256', $codeVerifier, true);
        return rtrim(strtr(base64_encode($hash), '+/', '-_'), '=');
    }
    
    /**
     * Get the Microsoft OAuth authorization URL with PKCE
     * 
     * @param string $state Random state for CSRF protection
     * @param string $codeChallenge PKCE code challenge
     * @return string The authorization URL
     */
    public function getAuthorizationUrl($state = '')
    {
        if (empty($state)) {
            $state = bin2hex(random_bytes(16));
        }
        
        $nonce = bin2hex(random_bytes(16));
        
        // Use id_token - implicit flow, no client secret needed
        $params = array(
            'client_id' => $this->_clientId,
            'response_type' => 'id_token',
            'redirect_uri' => $this->_redirectUri,
            'response_mode' => 'fragment',
            'scope' => 'openid profile email',
            'state' => $state,
            'nonce' => $nonce,
            'prompt' => 'select_account'  // Always show account selection screen
        );
        
        return $this->getAuthorizeUrl() . '?' . http_build_query($params);
    }
    
    /**
     * Decode and validate ID token (JWT)
     */
    public function decodeIdToken($idToken)
    {
        $parts = explode('.', $idToken);
        if (count($parts) !== 3) {
            return false;
        }
        
        $payload = $parts[1];
        $payload = str_replace(['-', '_'], ['+', '/'], $payload);
        $payload = base64_decode($payload);
        
        return json_decode($payload, true);
    }
    
    /**
     * Exchange authorization code for access token with PKCE
     * 
     * @param string $code The authorization code from Microsoft
     * @param string $codeVerifier PKCE code verifier (optional)
     * @return array|false Token data or false on failure
     */
    public function getAccessToken($code, $codeVerifier = '')
    {
        $result = $this->getAccessTokenWithDebug($code, $codeVerifier);
        
        if (isset($result['access_token'])) {
            return $result;
        }
        
        return false;
    }
    
    /**
     * Exchange authorization code for access token with PKCE - with debug info
     * 
     * @param string $code The authorization code from Microsoft
     * @param string $codeVerifier PKCE code verifier (optional)
     * @return array Token data with debug info
     */
    public function getAccessTokenWithDebug($code, $codeVerifier = '')
    {
        $url = $this->getTokenUrl();
        
        // For confidential client (Web app), send client_secret
        // Build POST data manually to ensure proper encoding
        $postFields = 'client_id=' . urlencode($this->_clientId)
            . '&client_secret=' . urlencode($this->_clientSecret)
            . '&code=' . urlencode($code)
            . '&redirect_uri=' . urlencode($this->_redirectUri)
            . '&grant_type=authorization_code'
            . '&scope=' . urlencode('openid profile email User.Read');
        
        // Add PKCE code verifier if provided
        if (!empty($codeVerifier)) {
            $postFields .= '&code_verifier=' . urlencode($codeVerifier);
        }
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/x-www-form-urlencoded'
        ));
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);
        
        $tokenData = json_decode($response, true);
        
        if (!is_array($tokenData)) {
            $tokenData = array();
        }
        
        // Add debug info
        $tokenData['debug_info'] = array(
            'http_code' => $httpCode,
            'response' => $response,
            'curl_error' => $curlError,
            'token_url' => $url,
            'redirect_uri' => $this->_redirectUri
        );
        
        return $tokenData;
    }
    
    /**
     * Get user profile from Microsoft Graph API
     * 
     * @param string $accessToken The access token
     * @return array|false User data or false on failure
     */
    public function getUserProfile($accessToken)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::GRAPH_URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/json'
        ));
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($httpCode !== 200) {
            error_log("Microsoft SSO Graph Error: HTTP $httpCode - $response - cURL: $error");
            return false;
        }
        
        $userData = json_decode($response, true);
        
        if (isset($userData['mail']) || isset($userData['userPrincipalName'])) {
            return $userData;
        }
        
        error_log("Microsoft SSO: No email in user profile");
        return false;
    }
    
    /**
     * Validate if email domain is allowed
     * 
     * @param string $email User's email address
     * @return boolean True if domain is allowed
     */
    public function isEmailDomainAllowed($email)
    {
        $email = strtolower(trim($email));
        $domain = substr($email, strpos($email, '@') + 1);
        
        return in_array($domain, $this->_allowedDomains);
    }
    
    /**
     * Get allowed domains list
     * 
     * @return array List of allowed domains
     */
    public function getAllowedDomains()
    {
        return $this->_allowedDomains;
    }
    
    /**
     * Extract email from user profile
     * 
     * @param array $userProfile User profile from Microsoft
     * @return string|null Email address or null
     */
    public function extractEmail($userProfile)
    {
        // Try mail first, then userPrincipalName
        if (!empty($userProfile['mail'])) {
            return strtolower($userProfile['mail']);
        }
        
        if (!empty($userProfile['userPrincipalName'])) {
            return strtolower($userProfile['userPrincipalName']);
        }
        
        return null;
    }
    
    /**
     * Extract display name from user profile
     * 
     * @param array $userProfile User profile from Microsoft
     * @return array Array with firstName and lastName
     */
    public function extractName($userProfile)
    {
        $firstName = '';
        $lastName = '';
        
        if (!empty($userProfile['givenName'])) {
            $firstName = $userProfile['givenName'];
        }
        
        if (!empty($userProfile['surname'])) {
            $lastName = $userProfile['surname'];
        }
        
        // Fallback to displayName if no first/last name
        if (empty($firstName) && empty($lastName) && !empty($userProfile['displayName'])) {
            $parts = explode(' ', $userProfile['displayName'], 2);
            $firstName = $parts[0];
            $lastName = isset($parts[1]) ? $parts[1] : '';
        }
        
        return array(
            'firstName' => $firstName,
            'lastName' => $lastName
        );
    }
    
    /**
     * Generate a random state for CSRF protection
     * 
     * @return string Random state string
     */
    public function generateState()
    {
        return bin2hex(random_bytes(16));
    }
    
    /**
     * Validate state to prevent CSRF attacks
     * 
     * @param string $returnedState State returned from Microsoft
     * @param string $savedState State saved in session
     * @return boolean True if states match
     */
    public function validateState($returnedState, $savedState)
    {
        return hash_equals($savedState, $returnedState);
    }
}

?>
