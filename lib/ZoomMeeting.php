<?php
/**
 * Zoom Meeting Integration Library
 * 
 * This library handles automatic creation of Zoom meetings
 * when calendar events are scheduled.
 * 
 * Uses Zoom Server-to-Server OAuth for authentication.
 * Supports both config.php and database-based credential storage.
 *
 * @package    CATS
 * @subpackage Library
 */

include_once(LEGACY_ROOT . '/lib/MeetingCredentials.php');

class ZoomMeeting
{
    private $_db;
    private $_siteID;
    private $_accountId;
    private $_clientId;
    private $_clientSecret;
    private $_userId;
    private $_accessToken;
    private $_tokenExpiry;

    const API_BASE_URL = 'https://api.zoom.us/v2';
    const AUTH_URL = 'https://zoom.us/oauth/token';

    public function __construct($siteID)
    {
        $this->_siteID = $siteID;
        $this->_db = DatabaseConnection::getInstance();
        
        // Load Zoom configuration from database or config
        $this->loadCredentials();
    }

    /**
     * Load credentials from database or config.php
     */
    private function loadCredentials()
    {
        $credentials = new MeetingCredentials($this->_siteID);
        
        $this->_accountId = $credentials->getCredential('zoom', 'account_id');
        $this->_clientId = $credentials->getCredential('zoom', 'client_id');
        $this->_clientSecret = $credentials->getCredential('zoom', 'client_secret');
        $this->_userId = $credentials->getCredential('zoom', 'user_id');
        
        // Ensure we have strings, not null
        $this->_accountId = $this->_accountId ?: '';
        $this->_clientId = $this->_clientId ?: '';
        $this->_clientSecret = $this->_clientSecret ?: '';
        $this->_userId = $this->_userId ?: 'me';
    }

    /**
     * Check if Zoom integration is enabled and configured
     *
     * @return boolean
     */
    public function isEnabled()
    {
        return !empty($this->_accountId) && 
               !empty($this->_clientId) && 
               !empty($this->_clientSecret);
    }

    /**
     * Get OAuth access token from Zoom (Server-to-Server OAuth)
     *
     * @return string|false Access token or false on failure
     */
    private function getAccessToken()
    {
        // Return cached token if still valid
        if (!empty($this->_accessToken) && $this->_tokenExpiry > time()) {
            return $this->_accessToken;
        }

        if (!$this->isEnabled()) {
            return false;
        }

        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, self::AUTH_URL);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array(
            'grant_type' => 'account_credentials',
            'account_id' => $this->_accountId
        )));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Basic ' . base64_encode($this->_clientId . ':' . $this->_clientSecret),
            'Content-Type: application/x-www-form-urlencoded'
        ));

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($httpCode !== 200) {
            error_log("Zoom OAuth Error: HTTP $httpCode - $response - cURL Error: $curlError");
            return false;
        }

        $tokenData = json_decode($response, true);
        
        if (isset($tokenData['access_token'])) {
            $this->_accessToken = $tokenData['access_token'];
            $this->_tokenExpiry = time() + (isset($tokenData['expires_in']) ? $tokenData['expires_in'] - 300 : 3300);
            return $this->_accessToken;
        }

        error_log("Zoom OAuth Error: No access token in response - " . print_r($tokenData, true));
        return false;
    }

    /**
     * Create a Zoom meeting
     *
     * @param string $subject Meeting subject/title
     * @param string $startDateTime Start date/time (ISO 8601 or Y-m-d H:i:s format)
     * @param string $endDateTime End date/time
     * @param string $description Meeting description/agenda
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

        // Get the user ID (email) for meeting creation
        $userId = !empty($this->_userId) ? $this->_userId : 'me';

        // Calculate duration in minutes
        $start = new DateTime($startDateTime);
        $end = new DateTime($endDateTime);
        $duration = ($end->getTimestamp() - $start->getTimestamp()) / 60;
        if ($duration < 1) {
            $duration = 30;
        }

        // Format start time for Zoom API (ISO 8601)
        $startTimeFormatted = $start->format('Y-m-d\TH:i:s');

        $meetingData = array(
            'topic' => $subject,
            'type' => 2,
            'start_time' => $startTimeFormatted,
            'duration' => (int) $duration,
            'timezone' => date_default_timezone_get(),
            'agenda' => $description,
            'settings' => array(
                'host_video' => true,
                'participant_video' => true,
                'join_before_host' => true,
                'mute_upon_entry' => false,
                'waiting_room' => false,
                'auto_recording' => 'none',
                'meeting_authentication' => false
            )
        );

        if (!empty($attendees)) {
            $meetingData['settings']['meeting_invitees'] = array_map(function($email) {
                return array('email' => $email);
            }, $attendees);
        }

        $apiUrl = self::API_BASE_URL . "/users/{$userId}/meetings";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($meetingData));
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

        if ($httpCode !== 201 && $httpCode !== 200) {
            error_log("Zoom API Error: HTTP $httpCode - $response - cURL Error: $curlError");
            return false;
        }

        $meetingInfo = json_decode($response, true);
        
        if (isset($meetingInfo['join_url'])) {
            return array(
                'joinUrl' => $meetingInfo['join_url'],
                'meetingId' => isset($meetingInfo['id']) ? (string) $meetingInfo['id'] : '',
                'password' => isset($meetingInfo['password']) ? $meetingInfo['password'] : '',
                'hostUrl' => isset($meetingInfo['start_url']) ? $meetingInfo['start_url'] : ''
            );
        }

        error_log("Zoom API Error: No join_url in response - " . print_r($meetingInfo, true));
        return false;
    }

    /**
     * Test the Zoom API connection
     *
     * @return array Result with success status and message
     */
    public function testConnection()
    {
        if (!$this->isEnabled()) {
            return array(
                'success' => false,
                'message' => 'Zoom is not configured. Please add credentials in Meeting Settings.'
            );
        }

        $accessToken = $this->getAccessToken();
        
        if (!$accessToken) {
            return array(
                'success' => false,
                'message' => 'Failed to obtain Zoom access token. Please verify your credentials.'
            );
        }

        $userId = !empty($this->_userId) ? $this->_userId : 'me';
        $apiUrl = self::API_BASE_URL . "/users/{$userId}";

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
            $userInfo = json_decode($response, true);
            $email = isset($userInfo['email']) ? $userInfo['email'] : 'Unknown';
            return array(
                'success' => true,
                'message' => "Successfully connected to Zoom as: {$email}"
            );
        }

        return array(
            'success' => false,
            'message' => "Failed to connect to Zoom API. HTTP Status: {$httpCode}"
        );
    }
}

?>
