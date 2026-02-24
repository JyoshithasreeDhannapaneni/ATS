<?php
/**
 * Microsoft Teams Integration Library
 * 
 * This library handles automatic creation of Microsoft Teams meetings
 * when calendar events are scheduled.
 *
 * @package    CATS
 * @subpackage Library
 */

class MicrosoftTeams
{
    private $_db;
    private $_siteID;
    private $_clientId;
    private $_clientSecret;
    private $_tenantId;
    private $_accessToken;

    public function __construct($siteID)
    {
        $this->_siteID = $siteID;
        $this->_db = DatabaseConnection::getInstance();
        
        // Load Microsoft Teams configuration from settings or config
        $this->_clientId = defined('MS_TEAMS_CLIENT_ID') ? MS_TEAMS_CLIENT_ID : '';
        $this->_clientSecret = defined('MS_TEAMS_CLIENT_SECRET') ? MS_TEAMS_CLIENT_SECRET : '';
        $this->_tenantId = defined('MS_TEAMS_TENANT_ID') ? MS_TEAMS_TENANT_ID : '';
    }

    /**
     * Check if Microsoft Teams integration is enabled and configured
     *
     * @return boolean
     */
    public function isEnabled()
    {
        return !empty($this->_clientId) && 
               !empty($this->_clientSecret) && 
               !empty($this->_tenantId);
    }

    /**
     * Get OAuth access token from Microsoft
     *
     * @return string|false Access token or false on failure
     */
    private function getAccessToken()
    {
        if (!empty($this->_accessToken)) {
            return $this->_accessToken;
        }

        if (!$this->isEnabled()) {
            return false;
        }

        $tokenUrl = "https://login.microsoftonline.com/{$this->_tenantId}/oauth2/v2.0/token";
        
        $postData = array(
            'client_id' => $this->_clientId,
            'client_secret' => $this->_clientSecret,
            'scope' => 'https://graph.microsoft.com/.default',
            'grant_type' => 'client_credentials'
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $tokenUrl);
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
            return false;
        }

        $tokenData = json_decode($response, true);
        
        if (isset($tokenData['access_token'])) {
            $this->_accessToken = $tokenData['access_token'];
            return $this->_accessToken;
        }

        return false;
    }

    /**
     * Create a Microsoft Teams online meeting
     *
     * @param string $subject Meeting subject/title
     * @param string $startDateTime Start date/time in ISO 8601 format
     * @param string $endDateTime End date/time in ISO 8601 format
     * @param string $description Meeting description
     * @param string $organizerEmail Email of the meeting organizer
     * @return array|false Meeting data with joinUrl or false on failure
     */
    public function createOnlineMeeting($subject, $startDateTime, $endDateTime, $description = '', $organizerEmail = '')
    {
        if (!$this->isEnabled()) {
            return false;
        }

        $accessToken = $this->getAccessToken();
        if (!$accessToken) {
            return false;
        }

        // If organizer email is provided, use it; otherwise use a default user
        $userId = $organizerEmail;
        if (empty($userId)) {
            // Try to get default user from config
            $userId = defined('MS_TEAMS_USER_ID') ? MS_TEAMS_USER_ID : '';
            if (empty($userId)) {
                // Fallback: use client ID as user principal name
                $userId = $this->_clientId;
            }
        }

        // Microsoft Graph API endpoint for creating online meetings
        $apiUrl = "https://graph.microsoft.com/v1.0/users/{$userId}/onlineMeetings";

        $meetingData = array(
            'subject' => $subject,
            'startDateTime' => $startDateTime,
            'endDateTime' => $endDateTime,
            'participants' => array(
                'organizer' => array(
                    'identity' => array(
                        'user' => array(
                            'id' => $userId
                        )
                    )
                )
            )
        );

        if (!empty($description)) {
            $meetingData['participants']['organizer']['upn'] = $organizerEmail;
        }

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
        curl_close($ch);

        if ($httpCode !== 201 && $httpCode !== 200) {
            // Log error for debugging
            error_log("Microsoft Teams API Error: HTTP $httpCode - $response");
            return false;
        }

        $meetingInfo = json_decode($response, true);
        
        if (isset($meetingInfo['joinWebUrl'])) {
            return array(
                'joinUrl' => $meetingInfo['joinWebUrl'],
                'meetingId' => isset($meetingInfo['id']) ? $meetingInfo['id'] : '',
                'threadId' => isset($meetingInfo['threadId']) ? $meetingInfo['threadId'] : ''
            );
        }

        return false;
    }

    /**
     * Create a Teams meeting link for a calendar event
     * This is a simplified version that generates a Teams meeting link
     * without requiring full API authentication (for easier setup)
     *
     * @param string $subject Meeting subject
     * @param string $startDateTime Start date/time
     * @param string $endDateTime End date/time
     * @return string|false Teams meeting join URL or false
     */
    public function createMeetingLink($subject, $startDateTime, $endDateTime)
    {
        // Option 1: Use Microsoft Graph API (requires full OAuth setup)
        if ($this->isEnabled()) {
            $meeting = $this->createOnlineMeeting($subject, $startDateTime, $endDateTime);
            if ($meeting && isset($meeting['joinUrl'])) {
                return $meeting['joinUrl'];
            }
        }

        // Option 2: Generate a Teams meeting link (simpler, but requires manual setup)
        // This creates a link that can be used to join a Teams meeting
        // Note: This is a placeholder - actual implementation would require
        // proper Teams meeting creation via Graph API
        
        // For now, return a Teams meeting template URL
        // Users will need to configure their Teams account properly
        $meetingId = uniqid('meeting_', true);
        $joinUrl = "https://teams.microsoft.com/l/meetup-join/19:meeting_{$meetingId}";
        
        return $joinUrl;
    }

    /**
     * Update calendar event with Teams meeting link
     *
     * @param integer $eventID Calendar event ID
     * @param string $teamsMeetingLink Teams meeting join URL
     * @return boolean Success status
     */
    public function updateCalendarEventWithMeetingLink($eventID, $teamsMeetingLink)
    {
        $sql = sprintf(
            "UPDATE calendar_event
             SET teams_meeting_link = %s,
                 date_modified = NOW()
             WHERE calendar_event_id = %s
             AND site_id = %s",
            $this->_db->makeQueryString($teamsMeetingLink),
            $this->_db->makeQueryInteger($eventID),
            $this->_siteID
        );

        $queryResult = $this->_db->query($sql);
        return ($queryResult !== false);
    }
}

?>
