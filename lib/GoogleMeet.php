<?php
/**
 * Google Meet Integration Library
 * 
 * This library handles automatic creation of Google Meet meetings
 * via Google Calendar API when calendar events are scheduled.
 * 
 * Uses OAuth 2.0 for authentication with refresh token flow.
 * Supports both config.php and database-based credential storage.
 *
 * @package    CATS
 * @subpackage Library
 */

include_once(LEGACY_ROOT . '/lib/MeetingCredentials.php');

class GoogleMeet
{
    private $_db;
    private $_siteID;
    private $_clientId;
    private $_clientSecret;
    private $_redirectUri;
    private $_calendarId;
    private $_accessToken;
    private $_refreshToken;
    private $_tokenExpiry;

    const API_BASE_URL = 'https://www.googleapis.com/calendar/v3';
    const AUTH_URL = 'https://accounts.google.com/o/oauth2/v2/auth';
    const TOKEN_URL = 'https://oauth2.googleapis.com/token';
    const SCOPES = 'https://www.googleapis.com/auth/calendar https://www.googleapis.com/auth/calendar.events';

    public function __construct($siteID)
    {
        $this->_siteID = $siteID;
        $this->_db = DatabaseConnection::getInstance();
        
        // Load Google configuration from database or config
        $this->loadCredentials();
        
        // Load refresh token from database
        $this->loadRefreshToken();
    }

    /**
     * Load credentials from database or config.php
     */
    private function loadCredentials()
    {
        $credentials = new MeetingCredentials($this->_siteID);
        
        $this->_clientId = $credentials->getCredential('google_meet', 'client_id');
        $this->_clientSecret = $credentials->getCredential('google_meet', 'client_secret');
        $this->_calendarId = $credentials->getCredential('google_meet', 'calendar_id');
        
        // Ensure we have strings, not null
        $this->_clientId = $this->_clientId ?: '';
        $this->_clientSecret = $this->_clientSecret ?: '';
        $this->_calendarId = $this->_calendarId ?: 'primary';
        
        // Build redirect URI
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
        
        $customRedirectUri = $credentials->getCredential('google_meet', 'redirect_uri');
        $this->_redirectUri = !empty($customRedirectUri) 
            ? $customRedirectUri 
            : $protocol . '://' . $host . '/index.php?m=settings&a=googleMeetCallback';
    }

    /**
     * Check if Google Meet integration is enabled and configured
     *
     * @return boolean
     */
    public function isEnabled()
    {
        return !empty($this->_clientId) && 
               !empty($this->_clientSecret) &&
               !empty($this->_refreshToken);
    }

    /**
     * Check if Google Meet is configured (credentials exist)
     *
     * @return boolean
     */
    public function isConfigured()
    {
        return !empty($this->_clientId) && !empty($this->_clientSecret);
    }

    /**
     * Check if Google Meet is authorized (has refresh token)
     *
     * @return boolean
     */
    public function isAuthorized()
    {
        return !empty($this->_refreshToken);
    }

    /**
     * Load refresh token from database
     */
    private function loadRefreshToken()
    {
        $sql = sprintf(
            "SELECT value FROM settings 
             WHERE setting = 'google_meet_refresh_token' 
             AND site_id = %s",
            $this->_siteID
        );
        
        $result = @$this->_db->query($sql);
        if ($result && @mysqli_num_rows($result) > 0) {
            $row = $this->_db->getAssoc();
            $this->_refreshToken = $row['value'];
        }
    }

    /**
     * Save refresh token to database
     *
     * @param string $refreshToken
     * @return boolean
     */
    public function saveRefreshToken($refreshToken)
    {
        $this->_refreshToken = $refreshToken;
        
        $sql = sprintf(
            "SELECT settings_id FROM settings 
             WHERE setting = 'google_meet_refresh_token' 
             AND site_id = %s",
            $this->_siteID
        );
        
        $result = @$this->_db->query($sql);
        
        if ($result && @mysqli_num_rows($result) > 0) {
            $sql = sprintf(
                "UPDATE settings SET value = %s 
                 WHERE setting = 'google_meet_refresh_token' 
                 AND site_id = %s",
                $this->_db->makeQueryString($refreshToken),
                $this->_siteID
            );
        } else {
            $sql = sprintf(
                "INSERT INTO settings (setting, value, site_id) 
                 VALUES ('google_meet_refresh_token', %s, %s)",
                $this->_db->makeQueryString($refreshToken),
                $this->_siteID
            );
        }
        
        return $this->_db->query($sql) !== false;
    }

    /**
     * Get OAuth authorization URL
     *
     * @return string Authorization URL
     */
    public function getAuthorizationUrl()
    {
        $params = array(
            'client_id' => $this->_clientId,
            'redirect_uri' => $this->_redirectUri,
            'response_type' => 'code',
            'scope' => self::SCOPES,
            'access_type' => 'offline',
            'prompt' => 'consent'
        );
        
        return self::AUTH_URL . '?' . http_build_query($params);
    }

    /**
     * Exchange authorization code for tokens
     *
     * @param string $code Authorization code from OAuth callback
     * @return array|false Token data or false on failure
     */
    public function exchangeCodeForTokens($code)
    {
        $postData = array(
            'client_id' => $this->_clientId,
            'client_secret' => $this->_clientSecret,
            'code' => $code,
            'grant_type' => 'authorization_code',
            'redirect_uri' => $this->_redirectUri
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::TOKEN_URL);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/x-www-form-urlencoded'
        ));

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            error_log("Google OAuth Error: HTTP $httpCode - $response");
            return false;
        }

        $tokenData = json_decode($response, true);
        
        if (isset($tokenData['refresh_token'])) {
            $this->saveRefreshToken($tokenData['refresh_token']);
        }
        
        if (isset($tokenData['access_token'])) {
            $this->_accessToken = $tokenData['access_token'];
            $this->_tokenExpiry = time() + (isset($tokenData['expires_in']) ? $tokenData['expires_in'] - 60 : 3540);
        }
        
        return $tokenData;
    }

    /**
     * Get access token using refresh token
     *
     * @return string|false Access token or false on failure
     */
    private function getAccessToken()
    {
        if (!empty($this->_accessToken) && $this->_tokenExpiry > time()) {
            return $this->_accessToken;
        }

        if (empty($this->_refreshToken)) {
            return false;
        }

        $postData = array(
            'client_id' => $this->_clientId,
            'client_secret' => $this->_clientSecret,
            'refresh_token' => $this->_refreshToken,
            'grant_type' => 'refresh_token'
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::TOKEN_URL);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/x-www-form-urlencoded'
        ));

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            error_log("Google OAuth Refresh Error: HTTP $httpCode - $response");
            return false;
        }

        $tokenData = json_decode($response, true);
        
        if (isset($tokenData['access_token'])) {
            $this->_accessToken = $tokenData['access_token'];
            $this->_tokenExpiry = time() + (isset($tokenData['expires_in']) ? $tokenData['expires_in'] - 60 : 3540);
            return $this->_accessToken;
        }

        return false;
    }

    /**
     * Create a Google Calendar event with Google Meet
     *
     * @param string $subject Meeting subject/title
     * @param string $startDateTime Start date/time
     * @param string $endDateTime End date/time
     * @param string $description Meeting description
     * @param array $attendees List of attendee emails (optional)
     * @return array|false Meeting data with joinUrl or false on failure
     */
    public function createMeeting($subject, $startDateTime, $endDateTime, $description = '', $attendees = array())
    {
        if (!$this->isEnabled()) {
            return false;
        }

        $accessToken = $this->getAccessToken();
        if (!$accessToken) {
            return false;
        }

        $start = new DateTime($startDateTime);
        $end = new DateTime($endDateTime);
        $timezone = date_default_timezone_get();

        $eventData = array(
            'summary' => $subject,
            'description' => $description,
            'start' => array(
                'dateTime' => $start->format('Y-m-d\TH:i:s'),
                'timeZone' => $timezone
            ),
            'end' => array(
                'dateTime' => $end->format('Y-m-d\TH:i:s'),
                'timeZone' => $timezone
            ),
            'conferenceData' => array(
                'createRequest' => array(
                    'requestId' => uniqid('meet_', true),
                    'conferenceSolutionKey' => array(
                        'type' => 'hangoutsMeet'
                    )
                )
            )
        );

        if (!empty($attendees)) {
            $eventData['attendees'] = array_map(function($email) {
                return array('email' => $email);
            }, $attendees);
        }

        $calendarId = $this->_calendarId ?: 'primary';
        $apiUrl = self::API_BASE_URL . "/calendars/{$calendarId}/events?conferenceDataVersion=1";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($eventData));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/json'
        ));

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($httpCode !== 200 && $httpCode !== 201) {
            error_log("Google Calendar API Error: HTTP $httpCode - $response - cURL Error: $curlError");
            return false;
        }

        $eventInfo = json_decode($response, true);
        
        $meetLink = '';
        if (isset($eventInfo['conferenceData']['entryPoints'])) {
            foreach ($eventInfo['conferenceData']['entryPoints'] as $entryPoint) {
                if ($entryPoint['entryPointType'] === 'video') {
                    $meetLink = $entryPoint['uri'];
                    break;
                }
            }
        }
        
        if (empty($meetLink) && isset($eventInfo['hangoutLink'])) {
            $meetLink = $eventInfo['hangoutLink'];
        }

        if (!empty($meetLink)) {
            return array(
                'joinUrl' => $meetLink,
                'meetingId' => isset($eventInfo['conferenceData']['conferenceId']) 
                    ? $eventInfo['conferenceData']['conferenceId'] 
                    : '',
                'calendarEventId' => isset($eventInfo['id']) ? $eventInfo['id'] : ''
            );
        }

        error_log("Google Calendar API Error: No Meet link in response - " . print_r($eventInfo, true));
        return false;
    }

    /**
     * Test the Google Calendar API connection
     *
     * @return array Result with success status and message
     */
    public function testConnection()
    {
        if (!$this->isConfigured()) {
            return array(
                'success' => false,
                'message' => 'Google Meet is not configured. Please add credentials in Meeting Settings.'
            );
        }

        if (!$this->isAuthorized()) {
            return array(
                'success' => false,
                'message' => 'Google Meet is not authorized. Please click "Authorize Google Meet" to connect.',
                'needsAuth' => true,
                'authUrl' => $this->getAuthorizationUrl()
            );
        }

        $accessToken = $this->getAccessToken();
        
        if (!$accessToken) {
            return array(
                'success' => false,
                'message' => 'Failed to obtain Google access token. Please re-authorize.',
                'needsAuth' => true,
                'authUrl' => $this->getAuthorizationUrl()
            );
        }

        $apiUrl = self::API_BASE_URL . '/users/me/calendarList?maxResults=1';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/json'
        ));

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200) {
            return array(
                'success' => true,
                'message' => 'Successfully connected to Google Calendar / Google Meet'
            );
        }

        return array(
            'success' => false,
            'message' => "Failed to connect to Google API. HTTP Status: {$httpCode}"
        );
    }

    /**
     * Revoke authorization
     *
     * @return boolean Success status
     */
    public function revokeAuthorization()
    {
        if (!empty($this->_refreshToken)) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://oauth2.googleapis.com/revoke?token=' . $this->_refreshToken);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_exec($ch);
            curl_close($ch);
        }
        
        $sql = sprintf(
            "DELETE FROM settings 
             WHERE setting = 'google_meet_refresh_token' 
             AND site_id = %s",
            $this->_siteID
        );
        
        $this->_db->query($sql);
        $this->_refreshToken = '';
        $this->_accessToken = '';
        
        return true;
    }
}

?>
