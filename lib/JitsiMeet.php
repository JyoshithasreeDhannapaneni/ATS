<?php
/**
 * Jitsi Meet Integration Library
 * 
 * Generates free video meeting links using Jitsi Meet.
 * No API keys or accounts required - works instantly!
 * 
 * Jitsi Meet is a free, open-source video conferencing platform.
 * 
 * @package    CATS
 * @subpackage Library
 */

class JitsiMeet
{
    private $_siteID;
    private $_serverUrl;
    
    // Public Jitsi servers (free to use)
    const DEFAULT_SERVER = 'https://meet.jit.si';
    const ALTERNATE_SERVERS = array(
        'https://meet.jit.si',
        'https://jitsi.riot.im',
        'https://meet.ffmuc.net'
    );

    public function __construct($siteID)
    {
        $this->_siteID = $siteID;
        $this->_serverUrl = self::DEFAULT_SERVER;
    }

    /**
     * Set custom Jitsi server URL (for self-hosted instances)
     *
     * @param string $serverUrl The Jitsi server URL
     */
    public function setServerUrl($serverUrl)
    {
        $this->_serverUrl = rtrim($serverUrl, '/');
    }

    /**
     * Check if Jitsi Meet is enabled (always true - no config needed)
     *
     * @return boolean
     */
    public function isEnabled()
    {
        return true;
    }

    /**
     * Generate a unique meeting room name
     *
     * @param string $subject Meeting subject/title
     * @param integer $eventID Optional event ID for uniqueness
     * @return string Unique room name
     */
    private function generateRoomName($subject, $eventID = null)
    {
        // Clean the subject - remove special characters, keep alphanumeric and spaces
        $cleanSubject = preg_replace('/[^a-zA-Z0-9\s]/', '', $subject);
        
        // Convert to CamelCase and limit length
        $words = explode(' ', $cleanSubject);
        $roomName = '';
        foreach ($words as $word) {
            if (!empty($word)) {
                $roomName .= ucfirst(strtolower($word));
            }
        }
        
        // Limit to 30 characters
        $roomName = substr($roomName, 0, 30);
        
        // Add unique identifier
        if ($eventID) {
            $uniqueId = $eventID . date('His');
        } else {
            $uniqueId = uniqid();
        }
        
        // Final room name: SubjectName-UniqueID
        $finalRoomName = $roomName . '-' . $uniqueId;
        
        return $finalRoomName;
    }

    /**
     * Create a Jitsi Meet meeting link
     *
     * @param string $subject Meeting subject/title
     * @param string $startDateTime Start date/time (not used by Jitsi, but kept for consistency)
     * @param string $endDateTime End date/time (not used by Jitsi)
     * @param string $description Meeting description (optional)
     * @param integer $eventID Optional event ID for uniqueness
     * @return array Meeting data with joinUrl
     */
    public function createMeeting($subject, $startDateTime = '', $endDateTime = '', $description = '', $eventID = null)
    {
        $roomName = $this->generateRoomName($subject, $eventID);
        
        // Generate the meeting URL
        $joinUrl = $this->_serverUrl . '/' . $roomName;
        
        return array(
            'joinUrl' => $joinUrl,
            'meetingId' => $roomName,
            'roomName' => $roomName,
            'serverUrl' => $this->_serverUrl,
            'platformName' => 'Jitsi Meet'
        );
    }

    /**
     * Create meeting link with additional options
     *
     * @param string $subject Meeting subject
     * @param array $options Additional options (config, interfaceConfig, etc.)
     * @return array Meeting data
     */
    public function createMeetingWithOptions($subject, $options = array())
    {
        $meeting = $this->createMeeting($subject);
        
        // Add URL parameters for customization if provided
        $params = array();
        
        // Set display name for host
        if (isset($options['displayName'])) {
            $params['userInfo.displayName'] = $options['displayName'];
        }
        
        // Set email
        if (isset($options['email'])) {
            $params['userInfo.email'] = $options['email'];
        }
        
        // Start with audio muted
        if (isset($options['startWithAudioMuted'])) {
            $params['config.startWithAudioMuted'] = $options['startWithAudioMuted'] ? 'true' : 'false';
        }
        
        // Start with video muted
        if (isset($options['startWithVideoMuted'])) {
            $params['config.startWithVideoMuted'] = $options['startWithVideoMuted'] ? 'true' : 'false';
        }
        
        // Add parameters to URL if any
        if (!empty($params)) {
            $meeting['joinUrl'] .= '#' . http_build_query($params);
        }
        
        return $meeting;
    }

    /**
     * Get platform display name
     *
     * @return string
     */
    public function getPlatformName()
    {
        return 'Jitsi Meet';
    }

    /**
     * Test connection (always succeeds for Jitsi)
     *
     * @return array
     */
    public function testConnection()
    {
        return array(
            'success' => true,
            'message' => 'Jitsi Meet is ready to use. No configuration required!'
        );
    }
}

?>
