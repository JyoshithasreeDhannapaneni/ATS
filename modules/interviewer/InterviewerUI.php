<?php
/**
 * Interviewer Portal Module
 * Provides a simplified view for interviewers to see their scheduled interviews
 */

include_once(LEGACY_ROOT . '/lib/UserRoles.php');

class InterviewerUI extends UserInterface
{
    public function __construct()
    {
        parent::__construct();
        
        $this->_authenticationRequired = true;
        $this->_moduleName = 'interviewer';
        $this->_moduleDirectory = 'interviewer';
    }
    
    public function getModuleTabText()
    {
        return '';
    }
    
    public function handleRequest()
    {
        $action = $this->getAction();
        
        switch ($action)
        {
            case 'viewCandidate':
                $this->viewCandidate();
                break;
                
            case 'submitFeedback':
                if ($this->isPostBack())
                {
                    $this->onSubmitFeedback();
                }
                else
                {
                    $this->showFeedbackForm();
                }
                break;
                
            case 'dashboard':
            default:
                $this->showDashboard();
                break;
        }
    }
    
    /**
     * Show the interviewer dashboard with upcoming interviews
     */
    private function showDashboard()
    {
        $userID = $_SESSION['CATS']->getUserID();
        $siteID = $_SESSION['CATS']->getSiteID();
        
        $userRoles = new UserRoles($siteID);
        $role = UserRoles::getUserRole($userID);
        $interviewerType = UserRoles::getInterviewerType($userID);
        
        // Get upcoming interviews
        $upcomingInterviews = $this->getUpcomingInterviews($userID);
        $pastInterviews = $this->getPastInterviewsNeedingFeedback($userID);
        
        $this->_template->assign('userRole', $role);
        $this->_template->assign('interviewerType', $interviewerType);
        $this->_template->assign('upcomingInterviews', $upcomingInterviews);
        $this->_template->assign('pastInterviews', $pastInterviews);
        $this->_template->assign('userName', $_SESSION['CATS']->getFullName());
        $this->_template->assign('active', $this);
        
        $this->_template->display('./modules/interviewer/Dashboard.tpl');
    }
    
    /**
     * Get upcoming interviews for the user
     */
    private function getUpcomingInterviews($userID)
    {
        $db = DatabaseConnection::getInstance();
        $siteID = $_SESSION['CATS']->getSiteID();
        
        // Get user email
        $userSql = sprintf("SELECT email FROM user WHERE user_id = %d", $userID);
        $userRs = $db->query($userSql);
        $userEmail = '';
        if ($userRs && mysqli_num_rows($userRs) > 0) {
            $row = mysqli_fetch_assoc($userRs);
            $userEmail = $row['email'];
        }
        
        $sql = sprintf(
            "SELECT 
                ce.calendar_event_id,
                ce.date,
                ce.title,
                ce.description,
                ce.all_day,
                cet.short_description as event_type,
                ce.entered_by,
                ce.data_item_id,
                ce.data_item_type,
                CASE 
                    WHEN ce.data_item_type = 100 THEN (SELECT CONCAT(first_name, ' ', last_name) FROM candidate WHERE candidate_id = ce.data_item_id)
                    ELSE ''
                END as candidate_name,
                CASE 
                    WHEN ce.data_item_type = 100 THEN (SELECT candidate_id FROM candidate WHERE candidate_id = ce.data_item_id)
                    ELSE NULL
                END as candidate_id
             FROM calendar_event ce
             LEFT JOIN calendar_event_type cet ON ce.event_type = cet.calendar_event_type_id
             WHERE ce.site_id = %d 
             AND ce.date >= CURDATE()
             AND (
                 ce.entered_by = %d 
                 OR ce.description LIKE %s
                 OR ce.title LIKE %s
             )
             ORDER BY ce.date ASC
             LIMIT 20",
            $siteID,
            $userID,
            $db->makeQueryString('%' . $userEmail . '%'),
            $db->makeQueryString('%' . $userEmail . '%')
        );
        
        $rs = $db->query($sql);
        $interviews = array();
        
        if ($rs) {
            while ($row = mysqli_fetch_assoc($rs)) {
                // Format date
                $row['formatted_date'] = date('D, M j, Y', strtotime($row['date']));
                $row['formatted_time'] = $row['all_day'] ? 'All Day' : date('g:i A', strtotime($row['date']));
                $row['is_today'] = (date('Y-m-d', strtotime($row['date'])) == date('Y-m-d'));
                $interviews[] = $row;
            }
        }
        
        return $interviews;
    }
    
    /**
     * Get past interviews that need feedback
     */
    private function getPastInterviewsNeedingFeedback($userID)
    {
        $db = DatabaseConnection::getInstance();
        $siteID = $_SESSION['CATS']->getSiteID();
        
        $userSql = sprintf("SELECT email FROM user WHERE user_id = %d", $userID);
        $userRs = $db->query($userSql);
        $userEmail = '';
        if ($userRs && mysqli_num_rows($userRs) > 0) {
            $row = mysqli_fetch_assoc($userRs);
            $userEmail = $row['email'];
        }
        
        $sql = sprintf(
            "SELECT 
                ce.calendar_event_id,
                ce.date,
                ce.title,
                ce.description,
                cet.short_description as event_type,
                ce.data_item_id,
                ce.data_item_type,
                CASE 
                    WHEN ce.data_item_type = 100 THEN (SELECT CONCAT(first_name, ' ', last_name) FROM candidate WHERE candidate_id = ce.data_item_id)
                    ELSE ''
                END as candidate_name
             FROM calendar_event ce
             LEFT JOIN calendar_event_type cet ON ce.event_type = cet.calendar_event_type_id
             WHERE ce.site_id = %d 
             AND ce.date < CURDATE()
             AND ce.date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
             AND (
                 ce.entered_by = %d 
                 OR ce.description LIKE %s
             )
             ORDER BY ce.date DESC
             LIMIT 10",
            $siteID,
            $userID,
            $db->makeQueryString('%' . $userEmail . '%')
        );
        
        $rs = $db->query($sql);
        $interviews = array();
        
        if ($rs) {
            while ($row = mysqli_fetch_assoc($rs)) {
                $row['formatted_date'] = date('M j, Y', strtotime($row['date']));
                $interviews[] = $row;
            }
        }
        
        return $interviews;
    }
    
    /**
     * View candidate details (limited view for interviewers)
     */
    private function viewCandidate()
    {
        if (!isset($_GET['candidateID'])) {
            CommonErrors::fatal(COMMONERROR_BADINDEX, $this, 'Invalid candidate ID.');
        }
        
        $candidateID = intval($_GET['candidateID']);
        $db = DatabaseConnection::getInstance();
        $siteID = $_SESSION['CATS']->getSiteID();
        
        $sql = sprintf(
            "SELECT 
                c.candidate_id,
                c.first_name,
                c.last_name,
                c.email1,
                c.phone_home,
                c.phone_cell,
                c.address,
                c.city,
                c.state,
                c.current_employer,
                c.current_pay,
                c.desired_pay,
                c.notes,
                c.key_skills
             FROM candidate c
             WHERE c.candidate_id = %d AND c.site_id = %d",
            $candidateID,
            $siteID
        );
        
        $rs = $db->query($sql);
        
        if (!$rs || mysqli_num_rows($rs) == 0) {
            CommonErrors::fatal(COMMONERROR_BADINDEX, $this, 'Candidate not found.');
        }
        
        $candidate = mysqli_fetch_assoc($rs);
        
        $this->_template->assign('candidate', $candidate);
        $this->_template->assign('active', $this);
        
        $this->_template->display('./modules/interviewer/ViewCandidate.tpl');
    }
    
    /**
     * Show feedback form
     */
    private function showFeedbackForm()
    {
        if (!isset($_GET['eventID'])) {
            CommonErrors::fatal(COMMONERROR_BADINDEX, $this, 'Invalid event ID.');
        }
        
        $eventID = intval($_GET['eventID']);
        
        $this->_template->assign('eventID', $eventID);
        $this->_template->assign('active', $this);
        
        $this->_template->display('./modules/interviewer/FeedbackForm.tpl');
    }
    
    /**
     * Submit interview feedback
     */
    private function onSubmitFeedback()
    {
        // Handle feedback submission
        // This would save the feedback to the database
        
        CATSUtility::transferRelativeURI('m=interviewer&a=dashboard');
    }
}
?>
