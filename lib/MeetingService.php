<?php
/**
 * Unified Meeting Service
 * 
 * Factory class that handles meeting creation across multiple platforms:
 * - Microsoft Teams
 * - Zoom
 * - Google Meet
 * 
 * Supports database-based credential storage for live server configuration.
 *
 * @package    CATS
 * @subpackage Library
 */

include_once(LEGACY_ROOT . '/lib/MeetingCredentials.php');
include_once(LEGACY_ROOT . '/lib/MicrosoftTeams.php');
include_once(LEGACY_ROOT . '/lib/ZoomMeeting.php');
include_once(LEGACY_ROOT . '/lib/GoogleMeet.php');
include_once(LEGACY_ROOT . '/lib/JitsiMeet.php');

class MeetingService
{
    private $_db;
    private $_siteID;
    
    const PLATFORM_NONE = 'none';
    const PLATFORM_TEAMS = 'teams';
    const PLATFORM_ZOOM = 'zoom';
    const PLATFORM_GOOGLE_MEET = 'google_meet';
    const PLATFORM_JITSI = 'jitsi';

    public function __construct($siteID)
    {
        $this->_siteID = $siteID;
        $this->_db = DatabaseConnection::getInstance();
    }

    /**
     * Get the default meeting platform from settings
     *
     * @return string Platform identifier
     */
    public function getDefaultPlatform()
    {
        $sql = sprintf(
            "SELECT value FROM settings 
             WHERE setting = 'meeting_platform' 
             AND site_id = %s",
            $this->_siteID
        );
        
        $result = @$this->_db->query($sql);
        if ($result && @mysqli_num_rows($result) > 0) {
            $row = $this->_db->getAssoc();
            return $row['value'];
        }
        
        return self::PLATFORM_NONE;
    }

    /**
     * Create a meeting on the specified platform
     *
     * @param string $subject Meeting subject/title
     * @param string $startDateTime Start date/time (ISO 8601 or Y-m-d H:i:s)
     * @param string $endDateTime End date/time
     * @param string $description Meeting description
     * @param string $platform Platform to use (null = default)
     * @param array $attendees Optional list of attendee emails
     * @return array|false Meeting data with joinUrl, platform, meetingId or false on failure
     */
    public function createMeeting($subject, $startDateTime, $endDateTime, $description = '', $platform = null, $attendees = array())
    {
        if ($platform === null) {
            $platform = $this->getDefaultPlatform();
        }
        
        if ($platform === self::PLATFORM_NONE) {
            return false;
        }

        $result = false;
        
        switch ($platform) {
            case self::PLATFORM_TEAMS:
                $result = $this->createTeamsMeeting($subject, $startDateTime, $endDateTime, $description, $attendees);
                break;
                
            case self::PLATFORM_ZOOM:
                $result = $this->createZoomMeeting($subject, $startDateTime, $endDateTime, $description, $attendees);
                break;
                
            case self::PLATFORM_GOOGLE_MEET:
                $result = $this->createGoogleMeeting($subject, $startDateTime, $endDateTime, $description, $attendees);
                break;
                
            case self::PLATFORM_JITSI:
                $result = $this->createJitsiMeeting($subject, $startDateTime, $endDateTime, $description);
                break;
        }
        
        if ($result) {
            $result['platform'] = $platform;
        }
        
        return $result;
    }

    /**
     * Create Microsoft Teams meeting
     */
    private function createTeamsMeeting($subject, $startDateTime, $endDateTime, $description, $attendees)
    {
        error_log("MeetingService: Creating Teams meeting - Subject: $subject");
        
        $teams = new MicrosoftTeams($this->_siteID);
        
        if (!$teams->isEnabled()) {
            error_log("MeetingService: Microsoft Teams is not enabled or configured");
            return false;
        }
        
        error_log("MeetingService: Teams is enabled, calling createOnlineMeeting");
        $meeting = $teams->createOnlineMeeting($subject, $startDateTime, $endDateTime, $description);
        
        error_log("MeetingService: Teams meeting result: " . print_r($meeting, true));
        
        if ($meeting && isset($meeting['joinUrl'])) {
            return array(
                'joinUrl' => $meeting['joinUrl'],
                'meetingId' => isset($meeting['meetingId']) ? $meeting['meetingId'] : '',
                'platformName' => 'Microsoft Teams'
            );
        }
        
        error_log("MeetingService: No joinUrl in Teams meeting result");
        return false;
    }

    /**
     * Create Jitsi Meet meeting (Free, no API keys needed)
     */
    private function createJitsiMeeting($subject, $startDateTime, $endDateTime, $description)
    {
        error_log("MeetingService: Creating Jitsi meeting - Subject: $subject");
        
        $jitsi = new JitsiMeet($this->_siteID);
        
        $meeting = $jitsi->createMeeting($subject, $startDateTime, $endDateTime, $description);
        
        if ($meeting && isset($meeting['joinUrl'])) {
            error_log("MeetingService: Jitsi meeting created - URL: " . $meeting['joinUrl']);
            return array(
                'joinUrl' => $meeting['joinUrl'],
                'meetingId' => isset($meeting['meetingId']) ? $meeting['meetingId'] : '',
                'platformName' => 'Jitsi Meet'
            );
        }
        
        return false;
    }

    /**
     * Create Zoom meeting
     */
    private function createZoomMeeting($subject, $startDateTime, $endDateTime, $description, $attendees)
    {
        $zoom = new ZoomMeeting($this->_siteID);
        
        if (!$zoom->isEnabled()) {
            error_log("MeetingService: Zoom is not enabled or configured");
            return false;
        }
        
        $meeting = $zoom->createMeeting($subject, $startDateTime, $endDateTime, $description, $attendees);
        
        if ($meeting && isset($meeting['joinUrl'])) {
            return array(
                'joinUrl' => $meeting['joinUrl'],
                'meetingId' => isset($meeting['meetingId']) ? $meeting['meetingId'] : '',
                'password' => isset($meeting['password']) ? $meeting['password'] : '',
                'platformName' => 'Zoom'
            );
        }
        
        return false;
    }

    /**
     * Create Google Meet meeting
     */
    private function createGoogleMeeting($subject, $startDateTime, $endDateTime, $description, $attendees)
    {
        $googleMeet = new GoogleMeet($this->_siteID);
        
        if (!$googleMeet->isEnabled()) {
            error_log("MeetingService: Google Meet is not enabled or configured");
            return false;
        }
        
        $meeting = $googleMeet->createMeeting($subject, $startDateTime, $endDateTime, $description, $attendees);
        
        if ($meeting && isset($meeting['joinUrl'])) {
            return array(
                'joinUrl' => $meeting['joinUrl'],
                'meetingId' => isset($meeting['meetingId']) ? $meeting['meetingId'] : '',
                'calendarEventId' => isset($meeting['calendarEventId']) ? $meeting['calendarEventId'] : '',
                'platformName' => 'Google Meet'
            );
        }
        
        return false;
    }

    /**
     * Update calendar event with meeting link
     *
     * @param integer $eventID Calendar event ID
     * @param string $meetingLink Meeting join URL
     * @param string $platform Platform used
     * @param string $meetingId External meeting ID (optional)
     * @return boolean Success status
     */
    public function updateCalendarEventWithMeetingLink($eventID, $meetingLink, $platform, $meetingId = '')
    {
        $sql = sprintf(
            "UPDATE calendar_event
             SET meeting_link = %s,
                 meeting_platform = %s,
                 meeting_external_id = %s,
                 date_modified = NOW()
             WHERE calendar_event_id = %s
             AND site_id = %s",
            $this->_db->makeQueryString($meetingLink),
            $this->_db->makeQueryString($platform),
            $this->_db->makeQueryString($meetingId),
            $this->_db->makeQueryInteger($eventID),
            $this->_siteID
        );

        $queryResult = $this->_db->query($sql);
        return ($queryResult !== false);
    }

    /**
     * Test connection to a specific platform
     *
     * @param string $platform Platform to test
     * @return array Result with success status and message
     */
    public function testConnection($platform)
    {
        switch ($platform) {
            case self::PLATFORM_TEAMS:
                $teams = new MicrosoftTeams($this->_siteID);
                if (!$teams->isEnabled()) {
                    return array('success' => false, 'message' => 'Microsoft Teams is not configured.');
                }
                return array('success' => true, 'message' => 'Microsoft Teams is configured.');
                
            case self::PLATFORM_ZOOM:
                $zoom = new ZoomMeeting($this->_siteID);
                return $zoom->testConnection();
                
            case self::PLATFORM_GOOGLE_MEET:
                $googleMeet = new GoogleMeet($this->_siteID);
                return $googleMeet->testConnection();
                
            default:
                return array('success' => false, 'message' => 'Unknown platform');
        }
    }

    /**
     * Get platform display name
     *
     * @param string $platform Platform identifier
     * @return string Display name
     */
    public static function getPlatformName($platform)
    {
        $names = array(
            self::PLATFORM_NONE => 'None',
            self::PLATFORM_TEAMS => 'Microsoft Teams',
            self::PLATFORM_ZOOM => 'Zoom',
            self::PLATFORM_GOOGLE_MEET => 'Google Meet',
            self::PLATFORM_JITSI => 'Jitsi Meet'
        );
        
        return isset($names[$platform]) ? $names[$platform] : 'Unknown';
    }
}

?>
