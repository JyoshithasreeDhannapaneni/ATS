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
     * @param int $candidateID
     * @param int $jobOrderID
     * @param int $newStatusID The new pipeline status ID
     * @param int $userID The user who triggered the change
     * @return bool Whether email was sent successfully
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

        // Perform variable substitution
        $subject = $this->_substituteVars($template['subject'], $candidate, $jobOrder, $recruiter);
        $body    = $this->_substituteVars($template['body'], $candidate, $jobOrder, $recruiter);

        // Send the email
        return $this->_sendEmail($candidate, $subject, $body, $userID);
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
            "SELECT text AS body, title AS subject FROM email_template
             WHERE tag = %s AND site_id = %s AND disabled = 0",
            $this->_db->makeQueryString($tag),
            $this->_siteID
        );

        $rs = $this->_db->getAllAssoc($sql);
        if (!empty($rs))
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
     * Send the email using the Mailer class.
     */
    private function _sendEmail($candidate, $subject, $body, $userID)
    {
        try
        {
            $mailer = new Mailer($this->_siteID, $userID);

            $mailerSettings = new MailerSettings($this->_siteID);
            $settings = $mailerSettings->getAll();

            $fromAddress = $settings['fromAddress'] ?? 'noreply@neutara.com';
            $fromName = 'Neutara ATS';

            $recipients = array($candidate['firstName'] . ' ' . $candidate['lastName'], $candidate['email1']);

            return $mailer->send(
                array($fromAddress, $fromName),
                $recipients,
                $subject,
                $body,
                false, // not HTML
                true   // log
            );
        }
        catch (\Exception $e)
        {
            // Log the error but don't break the pipeline status change
            error_log('PipelineEmailAutomation: Failed to send email - ' . $e->getMessage());
            return false;
        }
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
