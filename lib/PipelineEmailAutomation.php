<?php
/**
 * Neutara ATS
 * Pipeline Email Automation
 *
 * Sends automated emails when candidates move through pipeline stages.
 * Configurable per-status email templates with variable substitution.
 */

include_once(LEGACY_ROOT . '/lib/Mailer.php');
include_once(LEGACY_ROOT . '/lib/EmailTemplates.php');

class PipelineEmailAutomation
{
    private $_db;
    private $_siteID;
    private $_lastSendError = '';

    /* Default email templates for each pipeline status change */
    private static $_defaultTemplates = array(
        200 => array( // Contacted
            'subject' => 'Application Update - %CANDNAME%',
            'body'    => "Dear %CANDNAME%,\n\nThank you for your interest in the %JOBTITLE% position at %COMPANY%.\n\nWe have received your application and a recruiter will be in touch with you shortly.\n\nBest regards,\n%RECRUITER%\n%SITENAME%"
        ),
        300 => array( // Qualifying
            'subject' => 'Your Application is Being Reviewed - %JOBTITLE%',
            'body'    => "Dear %CANDNAME%,\n\nWe are currently reviewing your application for the %JOBTITLE% position at %COMPANY%.\n\nYour qualifications look promising and we will be in touch with next steps.\n\nBest regards,\n%RECRUITER%\n%SITENAME%"
        ),
        400 => array( // Submitted
            'subject' => 'Application Submitted to Hiring Manager - %JOBTITLE%',
            'body'    => "Dear %CANDNAME%,\n\nGreat news! Your profile has been submitted to the hiring manager for the %JOBTITLE% position at %COMPANY%.\n\nWe will keep you updated on the next steps.\n\nBest regards,\n%RECRUITER%\n%SITENAME%"
        ),
        500 => array( // Interviewing
            'subject' => 'Interview Scheduled - %JOBTITLE%',
            'body'    => "Dear %CANDNAME%,\n\nCongratulations! You have been selected for an interview for the %JOBTITLE% position at %COMPANY%.\n\nYour recruiter will reach out with specific scheduling details.\n\nBest of luck!\n%RECRUITER%\n%SITENAME%"
        ),
        600 => array( // Offered
            'subject' => 'Offer Extended - %JOBTITLE%',
            'body'    => "Dear %CANDNAME%,\n\nWe are pleased to inform you that an offer has been extended for the %JOBTITLE% position at %COMPANY%.\n\nYour recruiter will be in touch with the offer details.\n\nCongratulations!\n%RECRUITER%\n%SITENAME%"
        ),
        650 => array( // Not in Consideration
            'subject' => 'Application Update - %JOBTITLE%',
            'body'    => "Dear %CANDNAME%,\n\nThank you for your interest in the %JOBTITLE% position at %COMPANY%.\n\nAfter careful review, we have decided to move forward with other candidates at this time. We encourage you to apply for future opportunities.\n\nBest regards,\n%RECRUITER%\n%SITENAME%"
        ),
        700 => array( // Client Declined
            'subject' => 'Application Update - %JOBTITLE%',
            'body'    => "Dear %CANDNAME%,\n\nThank you for your interest in the %JOBTITLE% position at %COMPANY%.\n\nUnfortunately, the hiring team has decided to proceed with other candidates. We appreciate your time and will keep your profile on file for future opportunities.\n\nBest regards,\n%RECRUITER%\n%SITENAME%"
        ),
        800 => array( // Placed
            'subject' => 'Welcome Aboard! - %JOBTITLE%',
            'body'    => "Dear %CANDNAME%,\n\nCongratulations! We are thrilled to confirm your placement for the %JOBTITLE% position at %COMPANY%.\n\nWelcome to the team! Your recruiter will provide onboarding details shortly.\n\nBest regards,\n%RECRUITER%\n%SITENAME%"
        )
    );


    public function __construct($siteID)
    {
        $this->_siteID = $siteID;
        $this->_db = DatabaseConnection::getInstance();
    }

    /**
     * Trigger email automation when a pipeline status changes.
     * Called after Pipelines::setStatus().
     *
     * Queues the send rather than blocking this request on the full SMTP
     * round trip (a slow/unreachable mail server would otherwise stall
     * every status-change click), then kicks a background worker so the
     * queued row is picked up within seconds rather than waiting on a
     * polling interval.
     *
     * @param int $candidateID
     * @param int $jobOrderID
     * @param int $newStatusID The new pipeline status ID
     * @param int $userID The user who triggered the change
     * @return bool Whether the email was queued successfully
     */
    public function onStatusChange($candidateID, $jobOrderID, $newStatusID, $userID = -1)
    {
        // Check if this status triggers emails
        if (!$this->_statusTriggersEmail($newStatusID))
        {
            return false;
        }

        // Get candidate data
        $candidate = $this->_getCandidateData($candidateID);
        if (!$candidate || empty($candidate['email1']))
        {
            return false;
        }

        // Get job order data
        $jobOrder = $this->_getJobOrderData($jobOrderID);
        if (!$jobOrder)
        {
            return false;
        }

        // Get recruiter/user data
        $recruiter = $this->_getUserData($jobOrder['recruiter'] ?? 0);

        // Get template for this status
        $template = $this->_getTemplate($newStatusID);
        if (!$template)
        {
            return false;
        }

        // Perform variable substitution now, while $_SESSION (used for
        // %SITENAME%) is still available - the background worker that
        // actually sends this runs as a CLI process with no session.
        $subject = $this->_substituteVars($template['subject'], $candidate, $jobOrder, $recruiter);
        $body    = $this->_substituteVars($template['body'], $candidate, $jobOrder, $recruiter);

        $recipientName = trim($candidate['firstName'] . ' ' . $candidate['lastName']);

        // Resolve the -1 "use the current session user" sentinel now,
        // while there still is a session - the queue worker that later
        // reads this row back runs as a CLI process with none.
        if ($userID == -1 && isset($_SESSION['CATS']))
        {
            $userID = $_SESSION['CATS']->getUserID();
        }

        $queued = $this->_enqueueEmail(
            $candidateID, $jobOrderID, $newStatusID,
            $candidate['email1'], $recipientName,
            $subject, $body, $userID
        );

        if (!$queued)
        {
            return false;
        }

        $this->_dispatchQueueWorker();

        return true;
    }

    /**
     * Check if a status change should trigger an email.
     */
    private function _statusTriggersEmail($statusID)
    {
        $sql = sprintf(
            "SELECT triggers_email FROM candidate_joborder_status
             WHERE candidate_joborder_status_id = %s",
            intval($statusID)
        );

        $rs = $this->_db->getAllAssoc($sql);
        return (!empty($rs) && $rs[0]['triggers_email'] == 1);
    }

    /**
     * Get the email template for a given status.
     * First checks database for custom templates, then falls back to defaults.
     */
    private function _getTemplate($statusID)
    {
        // Check for custom template in email_template table
        $tag = 'PIPELINE_STATUS_' . $statusID;
        $sql = sprintf(
            "SELECT text AS body, subject FROM email_template
             WHERE tag = %s AND site_id = %s AND disabled = 0",
            $this->_db->makeQueryString($tag),
            $this->_siteID
        );

        $rs = $this->_db->getAllAssoc($sql);
        if (!empty($rs) && !empty($rs[0]['subject']))
        {
            return array(
                'subject' => $rs[0]['subject'],
                'body'    => $rs[0]['body']
            );
        }

        // Fall back to default templates
        if (isset(self::$_defaultTemplates[$statusID]))
        {
            return self::$_defaultTemplates[$statusID];
        }

        return null;
    }

    /**
     * Replace template variables with actual values.
     */
    private function _substituteVars($text, $candidate, $jobOrder, $recruiter)
    {
        $siteName = $_SESSION['CATS']->getSiteName() ?? 'Neutara ATS';

        $vars = array(
            '%CANDNAME%'      => trim($candidate['firstName'] . ' ' . $candidate['lastName']),
            '%CANDFIRSTNAME%' => $candidate['firstName'],
            '%CANDLASTNAME%'  => $candidate['lastName'],
            '%CANDEMAIL%'     => $candidate['email1'],
            '%JOBTITLE%'      => $jobOrder['title'] ?? '',
            '%COMPANY%'       => $jobOrder['companyName'] ?? '',
            '%JOBLOCATION%'   => trim(($jobOrder['city'] ?? '') . ', ' . ($jobOrder['state'] ?? ''), ', '),
            '%RECRUITER%'     => $recruiter ? trim($recruiter['firstName'] . ' ' . $recruiter['lastName']) : '',
            '%RECRUITEREMAIL%'=> $recruiter ? ($recruiter['email'] ?? '') : '',
            '%SITENAME%'      => $siteName,
            '%DATETIME%'      => date('F j, Y g:i A')
        );

        return str_replace(array_keys($vars), array_values($vars), $text);
    }

    /**
     * Insert a pending row for the background worker to pick up.
     *
     * @return int|false the new queue row's ID, or false on failure
     */
    private function _enqueueEmail($candidateID, $jobOrderID, $statusID, $recipientEmail,
        $recipientName, $subject, $body, $userID)
    {
        $sql = sprintf(
            "INSERT INTO pipeline_email_queue
                (candidate_id, joborder_id, status_id, site_id, user_id,
                 recipient_email, recipient_name, subject, body,
                 queue_status, attempts, created_at)
             VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, 'pending', 0, NOW())",
            intval($candidateID),
            intval($jobOrderID),
            intval($statusID),
            intval($this->_siteID),
            intval($userID),
            $this->_db->makeQueryString($recipientEmail),
            $this->_db->makeQueryString($recipientName),
            $this->_db->makeQueryString($subject),
            $this->_db->makeQueryString($body)
        );

        return $this->_db->query($sql, true) ? $this->_db->getLastInsertID() : false;
    }

    /**
     * Spawn the queue worker as a detached background process so the
     * just-enqueued row (and any other pending rows) gets sent within
     * seconds, without making the caller wait on it. Fire-and-forget by
     * design: if this fails silently (exec() disabled, php not on PATH),
     * the row is still queued and will eventually be picked up on a
     * subsequent status change's own dispatch attempt.
     */
    private function _dispatchQueueWorker()
    {
        if (!function_exists('exec'))
        {
            return;
        }

        $script = dirname(__DIR__) . '/cron/process-pipeline-email-queue.php';
        exec('php ' . escapeshellarg($script) . ' > /dev/null 2>&1 &');
    }

    /**
     * Actually send one already-rendered email. Called by the queue
     * worker (cron/process-pipeline-email-queue.php), which runs as a CLI
     * process with no $_SESSION - callers must pass a real $userID (not
     * -1) since Mailer falls back to $_SESSION['CATS'] otherwise.
     *
     * @return bool whether the send succeeded; check getLastSendError()
     *              for details on failure
     */
    public function sendQueuedEmailNow($recipientEmail, $recipientName, $subject, $body, $userID)
    {
        $this->_lastSendError = '';

        try
        {
            $mailer = new Mailer($this->_siteID, $userID);

            $mailerSettings = new MailerSettings($this->_siteID);
            $settings = $mailerSettings->getAll();

            $fromAddress = $settings['fromAddress'] ?? 'noreply@neutara.com';
            $fromName = 'Neutara ATS';

            $recipients = array(array($recipientEmail, $recipientName));

            $sent = $mailer->send(
                array($fromAddress, $fromName),
                $recipients,
                $subject,
                $body,
                false, // not HTML
                true   // log to email_history on success
            );

            if (!$sent)
            {
                $this->_lastSendError = $mailer->getError();
            }

            return $sent;
        }
        catch (\Exception $e)
        {
            $this->_lastSendError = $e->getMessage();
            error_log('PipelineEmailAutomation: Failed to send email - ' . $e->getMessage());
            return false;
        }
    }

    /**
     * @return string the error from the most recent sendQueuedEmailNow() call
     */
    public function getLastSendError()
    {
        return $this->_lastSendError;
    }

    /**
     * Get candidate data for email.
     */
    private function _getCandidateData($candidateID)
    {
        $sql = sprintf(
            "SELECT first_name AS firstName, last_name AS lastName,
                    email1, email2, phone_home AS phoneHome
             FROM candidate
             WHERE candidate_id = %s AND site_id = %s",
            intval($candidateID),
            $this->_siteID
        );
        $rs = $this->_db->getAllAssoc($sql);
        return !empty($rs) ? $rs[0] : false;
    }

    /**
     * Get job order data for email.
     */
    private function _getJobOrderData($jobOrderID)
    {
        $sql = sprintf(
            "SELECT j.title, j.city, j.state, j.recruiter,
                    c.name AS companyName
             FROM joborder j
             LEFT JOIN company c ON j.company_id = c.company_id
             WHERE j.joborder_id = %s AND j.site_id = %s",
            intval($jobOrderID),
            $this->_siteID
        );
        $rs = $this->_db->getAllAssoc($sql);
        return !empty($rs) ? $rs[0] : false;
    }

    /**
     * Get user/recruiter data.
     */
    private function _getUserData($userID)
    {
        if ($userID <= 0) return null;

        $sql = sprintf(
            "SELECT first_name AS firstName, last_name AS lastName, email
             FROM user WHERE user_id = %s",
            intval($userID)
        );
        $rs = $this->_db->getAllAssoc($sql);
        return !empty($rs) ? $rs[0] : null;
    }

    /**
     * Get all configurable templates with their current state.
     */
    public function getTemplateConfig()
    {
        $config = array();
        $statuses = $this->_getAllStatuses();

        foreach ($statuses as $status)
        {
            $statusID = $status['statusID'];
            $template = $this->_getTemplate($statusID);

            $config[] = array(
                'statusID'      => $statusID,
                'statusName'    => $status['status'],
                'triggersEmail' => $status['triggersEmail'],
                'hasTemplate'   => ($template !== null),
                'template'      => $template
            );
        }

        return $config;
    }

    private function _getAllStatuses()
    {
        $sql = "SELECT
                    candidate_joborder_status_id AS statusID,
                    short_description AS status,
                    triggers_email AS triggersEmail
                FROM candidate_joborder_status
                WHERE is_enabled = 1
                ORDER BY candidate_joborder_status_id ASC";
        return $this->_db->getAllAssoc($sql);
    }
}

?>
