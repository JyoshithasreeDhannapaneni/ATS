<?php
/**
 * CATS
 * Mail Transfer Library
 *
 *
 * The contents of this file are subject to the CATS Public License
 * Version 1.1a (the "License"); you may not use this file except in
 * compliance with the License. You may obtain a copy of the License at
 * http://www.catsone.com/.
 *
 * Software distributed under the License is distributed on an "AS IS"
 * basis, WITHOUT WARRANTY OF ANY KIND, either express or implied. See the
 * License for the specific language governing rights and limitations
 * under the License.
 *
 * The Original Code is "CATS Standard Edition".
 *
 * The Initial Developer of the Original Code is Cognizo Technologies, Inc.
 * Portions created by the Initial Developer are Copyright (C) 2005 - 2007
 * (or from the year in which this file was created to the year 2007) by
 * Cognizo Technologies, Inc. All Rights Reserved.
 *
 *
 * @package    CATS
 * @subpackage Library
 * @copyright Copyright (C) 2005 - 2007 Cognizo Technologies, Inc.
 * @version    $Id: Mailer.php 3587 2007-11-13 03:55:57Z will $
 */

/**
 *	E-Mail Abstraction Layer
 *	@package    CATS
 *	@subpackage Library
 */

 // Import PHPMailer classes into the global namespace
 // These must be at the top of your script, not inside a function
 use PHPMailer\PHPMailer\PHPMailer;
 use PHPMailer\PHPMailer\Exception;

 // Load Composer's autoloader
 require './vendor/autoload.php';

// FIXME: Remove this dependency! Bad bad bad!
include_once(LEGACY_ROOT . '/lib/Pipelines.php');

define('MAILER_MODE_DISABLED', 0);
define('MAILER_MODE_PHP',      1);
define('MAILER_MODE_SENDMAIL', 2);
define('MAILER_MODE_SMTP',     3);

$errorReporting = error_reporting();
error_reporting($errorReporting & ~ E_STRICT);

/**
 *	E-Mail Abstraction Layer
 *	@package    CATS
 *	@subpackage Library
 */
class Mailer
{
    private $_mailer;
    private $_errorMessage = '';
    private $_settings;
    private $_siteID;
    private $_userID;
    private $_db;


    public function __construct($siteID, $userID = -1)
    {
        $this->_siteID = $siteID;

        // Instantiation and passing `true` enables exceptions
        $this->_mailer = new PHPMailer(true);

        /* Load mailer configuration settings. */
        $settings = new MailerSettings($this->_siteID);
        $this->_settings = $settings->getAll();

        /* Configure PHPMailer based on CATS configuration settings. */
        $this->refreshSettings();

        $this->_mailer->SetLanguage('en', './lib/phpmailer/language/');

        /* Stuff for E-Mail logging. */
        // FIXME: Do this in the UserInterface. Session dependencied in
        //        libraries are bad.
        if ($userID != -1)
        {
            $this->_userID = $userID;
        }
        else
        {
            $this->_userID = $_SESSION['CATS']->getUserID();
        }

        $this->_db = DatabaseConnection::getInstance();
    }


    /**
     * Sends an e-mail message from the CATS system to one recipient. The
     * recipient's address is specified as a one-dimensional array of
     * "Recipient Name", "recipient@email.address". Lines will be wrapped at
     * 78 characters by default, but you may specify your own limit. If any
     * messages fail to send, false will be returned. You can use the
     * getError() method to retrieve the error message if false is returned.
     *
     * This method is a proxy to Mailer::send().
     *
     * @param array Recipient address array (0 => address, 1 => name).
     * @param string Message subject.
     * @param string Message body.
     * @param boolean Is this an HTML e-mail?
     * @param boolean Log message in the message log?
     * @param array Reply-to address (0 => address, 1 => name).
     * @param integer Wrap lines at X characters.
     * @return boolean Was the message successfully sent to all recipients?
     */
    public function sendToOne($recipient, $subject, $body, $isHTML = false,
        $logMessage = true, $replyTo = array(), $wrapLinesAt = 78)
    {
        return $this->send(
            array($this->_settings['fromAddress'], ''),
            array($recipient),
            $subject,
            $body,
            $isHTML,
            $logMessage,
            $replyTo,
            $wrapLinesAt,
            true
        );
    }

    /**
     * Sends an e-mail message from the CATS system to one or more recipients.
     * The recipient addresses are specified as a multi-dimensional array of
     * "Recipient Name", "recipient@email.address". Lines will be wrapped at
     * 78 characters by default, but you may specify your own limit. If any
     * messages fail to send, false will be returned. You can use the
     * getError() method to retrieve the error message if false is returned.
     *
     * This method is a proxy to Mailer::send().
     *
     * @param array Recipient address array (each element: 0 => address, 1 => name).
     * @param string Message subject.
     * @param string Message body.
     * @param boolean Is this an HTML e-mail?
     * @param boolean Log message in the message log?
     * @param array Reply-to address (0 => address, 1 => name).
     * @param integer Wrap lines at X characters.
     * @return boolean Was the message successfully sent to all recipients?
     */
    public function sendToMany($recipients, $subject, $body, $isHTML = false,
        $logMessage = true, $replyTo = array(), $wrapLinesAt = 78)
    {
        return $this->send(
            array($this->_settings['fromAddress'], ''),
            $recipients,
            $subject,
            $body,
            $isHTML,
            $logMessage,
            $replyTo,
            $wrapLinesAt,
            true
        );
    }

    /**
     * Sends an e-mail message to one or more recipients. The from address is
     * specified as a one-dimensional array of 0 => "from@email.address", 1 =>
     * "From Name". Recipient addresses are specified as a multi-dimensional
     * array of "Recipient Name", "recipient@email.address". Lines will be
     * wrapped at 78 characters by default, but you may specify your own limit.
     * If any messages fail to send, false will be returned. You can use the
     * getError() method to retrieve the error message if false is returned.
     *
     * @param array From address (0 => address, 1 => name).
     * @param array Recipient address array (each element: 0 => address, 1 => name).
     * @param string Message subject.
     * @param string Message body.
     * @param boolean Is this an HTML e-mail?
     * @param boolean Log message in the message log?
     * @param array Reply-to address (0 => address, 1 => name).
     * @param integer Wrap lines at X characters.
     * @param boolean Include CATS e-mail signature?
     * @return boolean Was the message successfully sent to all recipients?
     */
    public function send($from, $recipients, $subject, $body, $isHTML = false,
        $logMessage = true, $replyTo = array(), $wrapLinesAt = 78,
        $signature = false)
    {

        $this->_mailer->From     = $from[0];
        $this->_mailer->FromName = $from[1];

        $this->_mailer->WordWrap = $wrapLinesAt;

        $this->_mailer->Subject = $subject;

        if ($isHTML)
        {
            $this->_mailer->isHTML(true);

            if ($signature)
            {
                $body .= '\n<br />\n<br /><span style=\"font-size: 10pt;\">Powered by <a href=\"http://www.opencats.org" alt=\"Neutara ATS Tool\">Neutara ATS Tool</a> (Free ATS)</span>';
            }

            $this->_mailer->Body = '<div style="font: normal normal 12px Arial, Tahoma, sans-serif">'
                . str_replace('<br>', "<br />\n", str_replace('<br />', '<br>', str_replace("\n", "<br>", $body))) . '</div>';

            $this->_mailer->AltBody = strip_tags($body);
        }
        else
        {
            if ($signature)
            {
                $body .= "\n\nPowered by Neutara ATS Tool (http://www.opencats.org) Free ATS";
            }

            $this->_mailer->isHTML(false);
            $this->_mailer->Body = $body;
        }

        $failedRecipients = array();
        foreach ($recipients as $key => $value)
        {
            $this->_mailer->AddAddress($recipients[$key][0], $recipients[$key][1]);

            if (!empty($replyTo))
            {
                $this->_mailer->AddReplyTo($replyTo[0], $replyTo[1]);
            }
            $this->_mailer->CharSet = 'UTF-8';
            try
            {
                if (!$this->_mailer->Send())
                {
                    $failedRecipients[] = array(
                        'recipient'    => $recipients[$key],
                        'errorMessage' => $this->_mailer->ErrorInfo
                    );
                }
                else if ($logMessage)
                {
                    // FIXME: Log all recipients in one log entry?
                    // FIXME: Make sure all callers are passing an array of e-mails and not just a CSV string...
                    $this->logMessage($from[0], $recipients[$key][0], $subject, $body);
                }
            }
            catch (\PHPMailer\PHPMailer\Exception $e)
            {
                // Handle PHPMailer exceptions gracefully (e.g., SMTP connection failures)
                // Log the error but don't crash the application
                $failedRecipients[] = array(
                    'recipient'    => $recipients[$key],
                    'errorMessage' => 'Email sending failed: ' . $e->getMessage()
                );
            }

            $this->_mailer->ClearAddresses();
            $this->_mailer->ClearAttachments();
        }

        /* Return false if we had any failures. getError() will return the
         * specific error message.
         */
        if (!empty($failedRecipients))
        {
            $this->_errorMessage = "Errors occurred while attempting to send mail to one or more provided addresses:\n\n";

            foreach ($failedRecipients as $key => $value)
            {
                $this->_errorMessage .= sprintf(
                    "%s (%s): %s\n",
                    $failedRecipients[$key]['recipient'][0],
                    $failedRecipients[$key]['recipient'][1],
                    $failedRecipients[$key]['errorMessage']
                );
            }

            return false;
        }

        $this->_errorMessage = '';
        return true;
    }

    /**
     * Returns the last error message generated by the send() method.
     *
     * @return string Error message, or '' if no errors have occurred.
     */
    public function getError()
    {
        return $this->_errorMessage;
    }

    /**
     * Overrides a MailerSettings setting for this instance. This is useful for
     * letting a user test settings, etc.
     *
     * @param string Setting name.
     * @param string Setting value.
     * @return void
     */
    public function overrideSetting($setting, $value)
    {
        $this->_settings[$setting] = $value;
    }

    /**
     * (Re)configures PHPMailer settings based on CATS settings (from the
     * config file and any other sources).
     *
     * @return void
     */
    public function refreshSettings()
    {
        switch (MAIL_MAILER)
        {
            case MAILER_MODE_DISABLED:
                break;

            case MAILER_MODE_SENDMAIL:
                $this->_mailer->isSendmail();
                $this->_mailer->Sendmail = MAIL_SENDMAIL_PATH;
                break;

            case MAILER_MODE_SMTP:
                $this->_mailer->isSMTP();
                $this->_mailer->Host   = MAIL_SMTP_HOST;
                $this->_mailer->Port   = MAIL_SMTP_PORT;
                $this->_mailer->SMTPSecure  = MAIL_SMTP_SECURE;
                if (!MAIL_SMTP_SECURE)
                {
                    $this->_mailer->SMTPAutoTLS = false;
                }
                if (MAIL_SMTP_AUTH == true)
                {
                    $this->_mailer->SMTPAuth = MAIL_SMTP_AUTH;
                    $this->_mailer->Username = MAIL_SMTP_USER;
                    $this->_mailer->Password = MAIL_SMTP_PASS;
                }
                else
                {
                    $this->_mailer->SMTPAuth = false;
                }

                $this->_mailer->Timeout = 10;
                break;

            case MAILER_MODE_PHP:
            default:
                $this->_mailer->isMail();
                break;
        }
    }

    /**
     * Sends a meeting invitation email to an attendee.
     * This is used when scheduling interviews, calls, or meetings from the calendar.
     *
     * @param string $recipientEmail Attendee's email address
     * @param string $recipientName Attendee's name (optional)
     * @param string $meetingTitle Meeting/Interview title
     * @param mixed $dateTime Meeting date and time (DateTime object or string)
     * @param integer $duration Meeting duration in minutes
     * @param string $meetingLink Video meeting URL (Teams/Zoom/Google Meet)
     * @param string $platformName Platform display name
     * @param string $description Meeting description (optional)
     * @param string $organizerName Name of the person scheduling the meeting
     * @return boolean Was the email sent successfully?
     */
    public function sendMeetingInvite($recipientEmail, $recipientName, $meetingTitle, 
        $dateTime, $duration, $meetingLink, $platformName, $description = '', $organizerName = '')
    {
        if (empty($recipientEmail)) {
            return false;
        }
        
        // Handle DateTime object or string
        if ($dateTime instanceof DateTime) {
            $dateTimeObj = $dateTime;
        } else {
            $dateTimeObj = new DateTime($dateTime);
        }
        
        // Format the date/time nicely
        $formattedDate = $dateTimeObj->format('l, F j, Y');
        $formattedTime = $dateTimeObj->format('g:i A');
        
        // Build the HTML email body
        $body = $this->buildMeetingInviteHtml(
            $meetingTitle, $formattedDate, $formattedTime, $duration, 
            $platformName, $meetingLink, $description, $organizerName
        );

        // Build recipient array
        $recipient = array($recipientEmail, $recipientName);
        
        // Send the email
        return $this->sendToOne(
            $recipient,
            'Meeting Invitation: ' . $meetingTitle,
            $body,
            true,   // Is HTML
            true    // Log message
        );
    }
    
    /**
     * Builds the HTML content for a meeting invitation email.
     *
     * @param string $meetingTitle Meeting title
     * @param string $formattedDate Formatted date string
     * @param string $formattedTime Formatted time string
     * @param integer $duration Duration in minutes
     * @param string $platformName Platform name
     * @param string $meetingLink Meeting URL
     * @param string $description Description
     * @param string $organizerName Organizer name
     * @return string HTML email body
     */
    private function buildMeetingInviteHtml($meetingTitle, $formattedDate, $formattedTime, 
        $duration, $platformName, $meetingLink, $description, $organizerName)
    {
        $organizerHtml = '';
        if (!empty($organizerName)) {
            $organizerHtml = '
                <tr>
                    <td style="padding: 8px 0;">
                        <span style="color: #6b7280; font-size: 13px; display: inline-block; width: 80px;">Organizer:</span>
                        <span style="color: #1f2937; font-size: 14px; font-weight: 500;">' . htmlspecialchars($organizerName) . '</span>
                    </td>
                </tr>';
        }
        
        $meetingLinkHtml = '';
        if (!empty($meetingLink)) {
            $meetingLinkHtml = '
            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 25px;">
                <tr>
                    <td align="center">
                        <a href="' . htmlspecialchars($meetingLink) . '" 
                           style="display: inline-block; background-color: #16a34a; color: #ffffff; 
                                  padding: 14px 32px; text-decoration: none; border-radius: 6px; 
                                  font-size: 16px; font-weight: 600;">
                            Join Meeting
                        </a>
                    </td>
                </tr>
            </table>
            <p style="color: #6b7280; font-size: 13px; margin: 0 0 5px 0;">Meeting Link:</p>
            <p style="margin: 0 0 25px 0;">
                <a href="' . htmlspecialchars($meetingLink) . '" 
                   style="color: #2563eb; font-size: 13px; word-break: break-all;">' . htmlspecialchars($meetingLink) . '</a>
            </p>';
        }
        
        $descriptionHtml = '';
        if (!empty($description)) {
            $descriptionHtml = '
            <div style="border-top: 1px solid #e5e7eb; padding-top: 20px; margin-top: 10px;">
                <p style="color: #6b7280; font-size: 13px; margin: 0 0 8px 0;">Description:</p>
                <p style="color: #374151; font-size: 14px; margin: 0; line-height: 1.6;">' . nl2br(htmlspecialchars($description)) . '</p>
            </div>';
        }
        
        return '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin: 0; padding: 0; font-family: Arial, Helvetica, sans-serif; background-color: #f5f5f5;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f5f5f5; padding: 20px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <tr>
                        <td style="background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%); padding: 30px; border-radius: 8px 8px 0 0;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 24px; font-weight: 600;">Meeting Invitation</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 30px;">
                            <h2 style="color: #1f2937; margin: 0 0 20px 0; font-size: 20px;">' . htmlspecialchars($meetingTitle) . '</h2>
                            <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f9fafb; border-radius: 8px; margin-bottom: 25px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <table width="100%" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td style="padding: 8px 0;">
                                                    <span style="color: #6b7280; font-size: 13px; display: inline-block; width: 80px;">Date:</span>
                                                    <span style="color: #1f2937; font-size: 14px; font-weight: 500;">' . $formattedDate . '</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 8px 0;">
                                                    <span style="color: #6b7280; font-size: 13px; display: inline-block; width: 80px;">Time:</span>
                                                    <span style="color: #1f2937; font-size: 14px; font-weight: 500;">' . $formattedTime . '</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 8px 0;">
                                                    <span style="color: #6b7280; font-size: 13px; display: inline-block; width: 80px;">Duration:</span>
                                                    <span style="color: #1f2937; font-size: 14px; font-weight: 500;">' . $duration . ' minutes</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 8px 0;">
                                                    <span style="color: #6b7280; font-size: 13px; display: inline-block; width: 80px;">Platform:</span>
                                                    <span style="color: #1f2937; font-size: 14px; font-weight: 500;">' . htmlspecialchars($platformName) . '</span>
                                                </td>
                                            </tr>
                                            ' . $organizerHtml . '
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            ' . $meetingLinkHtml . '
                            ' . $descriptionHtml . '
                        </td>
                    </tr>
                    <tr>
                        <td style="background-color: #f9fafb; padding: 20px 30px; border-radius: 0 0 8px 8px; border-top: 1px solid #e5e7eb;">
                            <p style="color: #9ca3af; font-size: 12px; margin: 0; text-align: center;">
                                This invitation was sent from Neutara ATS
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>';
    }

    /**
     * Logs a message to the e-mail history table.
     *
     * @param string E-mail from address.
     * @param string E-mail recipient(s).
     * @param string E-mail subject.
     * @param string E-mail body.
     * @return void
     */
    private function logMessage($from, $to, $subject, $body)
    {
        $messageText = sprintf("Subject: %s\n\nMessage:\n%s", $subject, $body);

        $sql = sprintf(
            "INSERT INTO email_history (
                from_address,
                recipients,
                text,
                user_id,
                site_id,
                date
            )
            VALUES (
                %s,
                %s,
                %s,
                %s,
                %s,
                NOW()
            )",
            $this->_db->makeQueryString($from),
            $this->_db->makeQueryString($to),
            $this->_db->makeQueryString($messageText),
            $this->_userID,
            $this->_siteID
         );

         $this->_db->query($sql);
    }
}

/**
 *	Mailer / E-Mail Settings Library
 *	@package    CATS
 *	@subpackage Library
 */
class MailerSettings
{
    private $_db;
    private $_siteID;


    public function __construct($siteID)
    {
        $this->_siteID = $siteID;
        $this->_db = DatabaseConnection::getInstance();
    }

    /**
     * Returns all mailer / e-mail settings for a site.
     *
     * @return array E-mail settings (setting => value).
     */
    public function getAll()
    {
        // FIXME: This is violating just about every OO design principal I can come up with :)

        /* Default values. */
        $pipelines = new Pipelines($this->_siteID);
        $statuses = $pipelines->getStatuses();

        $candidateJoborderStatusSendsMessage = array();
        foreach ($statuses as $status)
        {
            $candidateJoborderStatusSendsMessage[$status['statusID']] = $status['triggersEmail'];
        }

        $settings = array(
            'fromAddress'       => 'noreply@yourdomain.com',
            'configured'        => '0',
            'modeConfigurable'  => '1',
            'candidateJoborderStatusSendsMessage' => serialize($candidateJoborderStatusSendsMessage)
        );

        $sql = sprintf(
            "SELECT
                settings.setting AS setting,
                settings.value AS value,
                settings.site_id AS siteID
            FROM
                settings
            WHERE
                settings.site_id = %s
            AND
                settings.settings_type = %s",
            $this->_siteID,
            SETTINGS_MAILER
        );
        $rs = $this->_db->getAllAssoc($sql);

        /* Override default settings with settings from the database. */
        foreach ($rs as $rowIndex => $row)
        {
            foreach ($settings as $setting => $value)
            {
                if ($row['setting'] == $setting)
                {
                    $settings[$setting] = $row['value'];
                }
            }
        }

        return $settings;
    }

    /**
     * Sets a mailer setting for a site.
     *
     * @param string Setting name.
     * @param string Setting value.
     * @return void
     */
    public function set($setting, $value)
    {
        /* Delete old setting. */
        $sql = sprintf(
            "DELETE FROM
                settings
            WHERE
                settings.setting = %s
            AND
                site_id = %s
            AND
                settings_type",
            $this->_db->makeQueryStringOrNULL($setting),
            $this->_siteID,
            SETTINGS_MAILER
        );
        $this->_db->query($sql);

        /* Add new setting. */
        $sql = sprintf(
            "INSERT INTO settings (
                setting,
                value,
                site_id,
                settings_type
            )
            VALUES (
                %s,
                %s,
                %s,
                %s
            )",
            $this->_db->makeQueryStringOrNULL($setting),
            $this->_db->makeQueryStringOrNULL($value),
            $this->_siteID,
            SETTINGS_MAILER
         );
         $this->_db->query($sql);
    }
}

?>
