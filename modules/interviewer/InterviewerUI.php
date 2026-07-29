<?php
/**
 * Interviewer Portal Module
 * Provides a simplified view for interviewers to see their scheduled interviews
 */

include_once(LEGACY_ROOT . '/lib/UserRoles.php');
include_once(LEGACY_ROOT . '/lib/InterviewFeedback.php');

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
        $userRows = $db->getAllAssoc($userSql);
        $userEmail = !empty($userRows) ? $userRows[0]['email'] : '';

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
             LEFT JOIN calendar_event_type cet ON ce.type = cet.calendar_event_type_id
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

        $rows = $db->getAllAssoc($sql);
        $interviews = array();

        foreach ($rows as $row) {
            // Format date
            $row['formatted_date'] = date('D, M j, Y', strtotime($row['date']));
            $row['formatted_time'] = $row['all_day'] ? 'All Day' : date('g:i A', strtotime($row['date']));
            $row['is_today'] = (date('Y-m-d', strtotime($row['date'])) == date('Y-m-d'));
            $interviews[] = $row;
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
        $userRows = $db->getAllAssoc($userSql);
        $userEmail = !empty($userRows) ? $userRows[0]['email'] : '';

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
             LEFT JOIN calendar_event_type cet ON ce.type = cet.calendar_event_type_id
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

        $rows = $db->getAllAssoc($sql);
        $interviews = array();

        foreach ($rows as $row) {
            $row['formatted_date'] = date('M j, Y', strtotime($row['date']));
            $interviews[] = $row;
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
        
        $rows = $db->getAllAssoc($sql);

        if (empty($rows)) {
            CommonErrors::fatal(COMMONERROR_BADINDEX, $this, 'Candidate not found.');
        }

        $candidate = $rows[0];

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
        $userID = $_SESSION['CATS']->getUserID();
        $siteID = $_SESSION['CATS']->getSiteID();
        $db = DatabaseConnection::getInstance();

        $eventRows = $db->getAllAssoc(sprintf(
            "SELECT calendar_event_id, date, title, joborder_id, data_item_id, data_item_type
             FROM calendar_event
             WHERE calendar_event_id = %d AND site_id = %d",
            $eventID,
            $siteID
        ));

        if (empty($eventRows)) {
            CommonErrors::fatal(COMMONERROR_BADINDEX, $this, 'Interview event not found.');
        }

        $event = $eventRows[0];
        $candidateID = ($event['data_item_type'] == DATA_ITEM_CANDIDATE) ? intval($event['data_item_id']) : 0;

        if ($candidateID <= 0) {
            CommonErrors::fatal(COMMONERROR_BADINDEX, $this, 'This event is not linked to a candidate.');
        }

        $candidateRows = $db->getAllAssoc(sprintf(
            "SELECT candidate_id, first_name, last_name FROM candidate WHERE candidate_id = %d AND site_id = %d",
            $candidateID,
            $siteID
        ));

        if (empty($candidateRows)) {
            CommonErrors::fatal(COMMONERROR_BADINDEX, $this, 'Candidate not found.');
        }

        $feedback = new InterviewFeedback($siteID);

        /* Reuse this interviewer's existing feedback row for this event if
         * one was already started, instead of creating a new one every time
         * the form is opened. */
        $existingRows = $db->getAllAssoc(sprintf(
            "SELECT feedback_id FROM interview_feedback
             WHERE calendar_event_id = %d AND interviewer_user_id = %d AND site_id = %d
             LIMIT 1",
            $eventID,
            $userID,
            $siteID
        ));

        if (!empty($existingRows)) {
            $feedbackID = intval($existingRows[0]['feedback_id']);
        } else {
            $stage = UserRoles::getInterviewerType($userID);
            $feedbackID = $feedback->create(
                $eventID,
                $candidateID,
                intval($event['joborder_id']),
                $userID,
                $stage ?: InterviewFeedback::STAGE_L1
            );
        }

        $this->_template->assign('feedbackID', $feedbackID);
        $this->_template->assign('event', $event);
        $this->_template->assign('candidate', $candidateRows[0]);
        $this->_template->assign('active', $this);

        $this->_template->display('./modules/interviewer/FeedbackForm.tpl');
    }

    /**
     * Submit interview feedback
     */
    private function onSubmitFeedback()
    {
        $userID = $_SESSION['CATS']->getUserID();
        $siteID = $_SESSION['CATS']->getSiteID();

        if (!isset($_POST['feedbackID']))
        {
            CommonErrors::fatal(COMMONERROR_BADINDEX, $this, 'Invalid feedback ID.');
        }

        $feedbackID = intval($_POST['feedbackID']);

        $feedback = new InterviewFeedback($siteID);
        $existing = $feedback->get($feedbackID);

        /* Only the interviewer who owns this feedback row may submit it -
         * otherwise a guessed/incremented feedbackID could overwrite
         * someone else's interview feedback. */
        if (!$existing || intval($existing['interviewer_user_id']) !== intval($userID))
        {
            CommonErrors::fatal(COMMONERROR_BADINDEX, $this, 'Invalid feedback ID.');
        }

        $data = array(
            'overallRating'        => isset($_POST['overallRating']) ? intval($_POST['overallRating']) : null,
            'technicalRating'      => isset($_POST['technicalRating']) ? intval($_POST['technicalRating']) : null,
            'communicationRating'  => isset($_POST['communicationRating']) ? intval($_POST['communicationRating']) : null,
            'culturalFitRating'    => isset($_POST['culturalFitRating']) ? intval($_POST['culturalFitRating']) : null,
            'problemSolvingRating' => isset($_POST['problemSolvingRating']) ? intval($_POST['problemSolvingRating']) : null,
            'strengths'            => isset($_POST['strengths']) ? trim($_POST['strengths']) : '',
            'weaknesses'           => isset($_POST['weaknesses']) ? trim($_POST['weaknesses']) : '',
            'notes'                => isset($_POST['notes']) ? trim($_POST['notes']) : '',
            'recommendation'       => isset($_POST['recommendation']) ? trim($_POST['recommendation']) : ''
        );

        $feedback->submitFeedback($feedbackID, $data);

        CATSUtility::transferRelativeURI('m=interviewer&a=dashboard');
    }
}
?>
