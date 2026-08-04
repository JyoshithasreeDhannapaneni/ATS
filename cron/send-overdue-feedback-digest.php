<?php
/**
 * Neutara ATS
 * Overdue Interview Feedback Digest
 *
 * Finds interview_feedback rows still 'pending' more than N days after
 * their interview date (InterviewFeedback::getOverdue()) and sends each
 * affected interviewer a single digest email listing everything they still
 * owe feedback on. There is no in-app notification system in this codebase
 * (only outbound email), so this is the whole mechanism -- reuses
 * PipelineEmailAutomation::sendQueuedEmailNow(), the same generic send path
 * cron/process-pipeline-email-queue.php uses, rather than standing up a new
 * mail-sending path.
 *
 * CLI only - deliberately does not touch $_SESSION (there isn't one).
 * Intended to be invoked on a schedule (e.g. a daily crontab entry) -- see
 * the plan / CHANGELOG for the exact crontab line, since this repo's
 * deploy.yml does not run scheduled jobs on its own.
 */

if (php_sapi_name() !== 'cli')
{
    die("This script is CLI-only.\n");
}

define('LEGACY_ROOT', dirname(__DIR__));

include_once(LEGACY_ROOT . '/config.php');
include_once(LEGACY_ROOT . '/constants.php');
include_once(LEGACY_ROOT . '/lib/DatabaseConnection.php');
include_once(LEGACY_ROOT . '/lib/InterviewFeedback.php');
include_once(LEGACY_ROOT . '/lib/PipelineEmailAutomation.php');

const DAYS_THRESHOLD = 3;

$db = DatabaseConnection::getInstance();

$siteRS = $db->getAllAssoc("SELECT site_id FROM site");

$digestsSent = 0;
$digestsFailed = 0;
$itemsCovered = 0;

foreach ($siteRS as $siteRow)
{
    $siteID = intval($siteRow['site_id']);

    $feedback = new InterviewFeedback($siteID);
    $overdueRS = $feedback->getOverdue(DAYS_THRESHOLD);

    if (empty($overdueRS))
    {
        continue;
    }

    // Group by interviewer -- one digest email per interviewer, not one
    // email per overdue item.
    $byInterviewer = array();
    foreach ($overdueRS as $row)
    {
        $interviewerUserID = intval($row['interviewerUserID']);
        if (empty($row['interviewerEmail']))
        {
            // Interviewer account was deleted or has no email on file --
            // nothing to send to, skip.
            continue;
        }

        if (!isset($byInterviewer[$interviewerUserID]))
        {
            $byInterviewer[$interviewerUserID] = array(
                'name' => trim($row['interviewerFirstName'] . ' ' . $row['interviewerLastName']),
                'email' => $row['interviewerEmail'],
                'items' => array()
            );
        }

        $byInterviewer[$interviewerUserID]['items'][] = $row;
    }

    $automation = new PipelineEmailAutomation($siteID);

    foreach ($byInterviewer as $interviewerUserID => $interviewer)
    {
        $items = $interviewer['items'];
        $count = count($items);

        $subject = sprintf(
            'You have %d overdue interview feedback form%s',
            $count,
            $count === 1 ? '' : 's'
        );

        $lines = array();
        $lines[] = sprintf('Hi %s,', $interviewer['name']);
        $lines[] = '';
        $lines[] = sprintf(
            'You have %d interview feedback form%s still pending, more than %d days after the interview:',
            $count,
            $count === 1 ? '' : 's',
            DAYS_THRESHOLD
        );
        $lines[] = '';

        foreach ($items as $item)
        {
            $candidateName = trim($item['candidateFirstName'] . ' ' . $item['candidateLastName']);
            $lines[] = sprintf(
                '- %s (%s) - interviewed %s - %d day%s overdue',
                $candidateName,
                $item['jobTitle'] ?: 'Unknown Job',
                $item['eventDate'],
                intval($item['daysOverdue']),
                intval($item['daysOverdue']) === 1 ? '' : 's'
            );
        }

        $lines[] = '';
        $lines[] = 'Please submit your feedback as soon as possible.';
        $lines[] = '';
        $lines[] = 'Neutara ATS';

        $body = implode("\n", $lines);

        $success = $automation->sendQueuedEmailNow(
            $interviewer['email'],
            $interviewer['name'],
            $subject,
            $body,
            $interviewerUserID
        );

        if ($success)
        {
            $digestsSent++;
            $itemsCovered += $count;
        }
        else
        {
            $digestsFailed++;
            error_log(
                'send-overdue-feedback-digest: failed to send digest to '
                . $interviewer['email'] . ' - ' . $automation->getLastSendError()
            );
        }
    }
}

echo "Overdue feedback digest: digestsSent={$digestsSent} digestsFailed={$digestsFailed} itemsCovered={$itemsCovered}\n";
