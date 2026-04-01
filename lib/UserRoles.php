<?php
/**
 * User Roles Helper Class
 * Manages role-based access for Admin, Recruiter, and Interviewer views
 */

class UserRoles
{
    const ROLE_ADMIN = 'admin';
    const ROLE_RECRUITER = 'recruiter';
    const ROLE_INTERVIEWER = 'interviewer';
    
    const INTERVIEWER_L1 = 'L1';
    const INTERVIEWER_L2 = 'L2';
    const INTERVIEWER_L3 = 'L3';
    const INTERVIEWER_HR = 'HR';
    
    private $_db;
    private $_siteID;
    
    public function __construct($siteID)
    {
        $this->_db = DatabaseConnection::getInstance();
        $this->_siteID = $siteID;
    }
    
    /**
     * Get user's role
     */
    public static function getUserRole($userID)
    {
        $db = DatabaseConnection::getInstance();
        
        // Check if role column exists
        if (!self::roleColumnExists()) {
            // Infer role from access level
            $sql = sprintf("SELECT access_level FROM user WHERE user_id = %d LIMIT 1", $userID);
            $rs = $db->query($sql);
            if ($rs && mysqli_num_rows($rs) > 0) {
                $row = mysqli_fetch_assoc($rs);
                return ($row['access_level'] >= 400) ? self::ROLE_ADMIN : self::ROLE_RECRUITER;
            }
            return self::ROLE_RECRUITER;
        }
        
        $sql = sprintf(
            "SELECT role, access_level FROM user WHERE user_id = %d LIMIT 1",
            $userID
        );
        $rs = $db->query($sql);
        if ($rs && mysqli_num_rows($rs) > 0) {
            $row = mysqli_fetch_assoc($rs);
            if (!empty($row['role'])) {
                return $row['role'];
            }
            // Fallback to access level
            return ($row['access_level'] >= 400) ? self::ROLE_ADMIN : self::ROLE_RECRUITER;
        }
        return self::ROLE_RECRUITER;
    }
    
    /**
     * Get interviewer type (L1, L2, L3, HR)
     */
    public static function getInterviewerType($userID)
    {
        $db = DatabaseConnection::getInstance();
        $sql = sprintf(
            "SELECT interviewer_type FROM user WHERE user_id = %d LIMIT 1",
            $userID
        );
        $rs = $db->query($sql);
        if ($rs && mysqli_num_rows($rs) > 0) {
            $row = mysqli_fetch_assoc($rs);
            return $row['interviewer_type'];
        }
        return null;
    }
    
    /**
     * Check if user is admin
     */
    public static function isAdmin($userID)
    {
        return self::getUserRole($userID) === self::ROLE_ADMIN;
    }
    
    /**
     * Check if user is recruiter
     */
    public static function isRecruiter($userID)
    {
        return self::getUserRole($userID) === self::ROLE_RECRUITER;
    }
    
    /**
     * Check if user is interviewer
     */
    public static function isInterviewer($userID)
    {
        return self::getUserRole($userID) === self::ROLE_INTERVIEWER;
    }
    
    /**
     * Set user role
     */
    public function setUserRole($userID, $role, $interviewerType = null)
    {
        $sql = sprintf(
            "UPDATE user SET role = %s, interviewer_type = %s WHERE user_id = %d AND site_id = %d",
            $this->_db->makeQueryString($role),
            $interviewerType ? $this->_db->makeQueryString($interviewerType) : 'NULL',
            $userID,
            $this->_siteID
        );
        return $this->_db->query($sql);
    }
    
    /**
     * Get all available roles
     */
    public static function getAllRoles()
    {
        return array(
            self::ROLE_ADMIN => 'Administrator',
            self::ROLE_RECRUITER => 'Recruiter',
            self::ROLE_INTERVIEWER => 'Interviewer'
        );
    }
    
    /**
     * Get all interviewer types
     */
    public static function getInterviewerTypes()
    {
        return array(
            self::INTERVIEWER_L1 => 'L1 Interviewer',
            self::INTERVIEWER_L2 => 'L2 Interviewer',
            self::INTERVIEWER_L3 => 'L3 Interviewer',
            self::INTERVIEWER_HR => 'HR Interviewer'
        );
    }
    
    /**
     * Get role display name
     */
    public static function getRoleDisplayName($role, $interviewerType = null)
    {
        $roles = self::getAllRoles();
        if ($role === self::ROLE_INTERVIEWER && $interviewerType) {
            $types = self::getInterviewerTypes();
            return isset($types[$interviewerType]) ? $types[$interviewerType] : 'Interviewer';
        }
        return isset($roles[$role]) ? $roles[$role] : 'Unknown';
    }
    
    /**
     * Get interviews assigned to an interviewer
     */
    public function getInterviewerSchedule($userID, $interviewerType = null)
    {
        $db = DatabaseConnection::getInstance();
        
        // Get user's email to match with calendar events
        $userSql = sprintf("SELECT email, first_name, last_name FROM user WHERE user_id = %d", $userID);
        $userRs = $db->query($userSql);
        if (!$userRs || mysqli_num_rows($userRs) == 0) {
            return array();
        }
        $userData = mysqli_fetch_assoc($userRs);
        $userEmail = $userData['email'];
        
        // Get calendar events where this user is the entered_by or attendee
        $sql = sprintf(
            "SELECT ce.*, cet.short_description as event_type_name
             FROM calendar_event ce
             LEFT JOIN calendar_event_type cet ON ce.event_type = cet.calendar_event_type_id
             WHERE ce.site_id = %d 
             AND (ce.entered_by = %d OR ce.description LIKE %s)
             AND ce.date >= CURDATE()
             ORDER BY ce.date ASC, ce.all_day DESC",
            $this->_siteID,
            $userID,
            $db->makeQueryString('%' . $userEmail . '%')
        );
        
        $rs = $db->query($sql);
        $events = array();
        
        if ($rs) {
            while ($row = mysqli_fetch_assoc($rs)) {
                $events[] = $row;
            }
        }
        
        return $events;
    }
    
    /**
     * Check if role column exists in user table
     */
    public static function roleColumnExists()
    {
        $db = DatabaseConnection::getInstance();
        $sql = "SHOW COLUMNS FROM user LIKE 'role'";
        $rs = $db->query($sql);
        return ($rs && mysqli_num_rows($rs) > 0);
    }
}
?>
