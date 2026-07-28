<?php
/**
 * Neutara ATS
 * Pipeline Email Queue Worker
 *
 * Processes pending rows in pipeline_email_queue (see
 * db/upgrade-pipeline-email-queue.sql). Normally spawned as a detached
 * background process by PipelineEmailAutomation::_dispatchQueueWorker()
 * right after a row is enqueued, so sends happen within seconds without
 * blocking the status-change request that created them. Safe to also run
 * on a schedule (e.g. cron every few minutes) as a catch-all in case a
 * spawn silently failed to fire - claiming a row is atomic (an UPDATE
 * guarded by queue_status = 'pending'), so concurrent runs can't double
 * -send the same row.
 *
 * CLI only - deliberately does not touch $_SESSION (there isn't one).
 * PipelineEmailAutomation::onStatusChange() already rendered the final
 * subject/body before enqueueing for exactly this reason.
 */

if (php_sapi_name() !== 'cli')
{
    die("This script is CLI-only.\n");
}

define('LEGACY_ROOT', dirname(__DIR__));

include_once(LEGACY_ROOT . '/config.php');
include_once(LEGACY_ROOT . '/lib/DatabaseConnection.php');
include_once(LEGACY_ROOT . '/lib/PipelineEmailAutomation.php');

const MAX_ATTEMPTS = 3;
const BATCH_SIZE = 20;

$db = DatabaseConnection::getInstance();

$pendingRS = $db->getAllAssoc(sprintf(
    "SELECT pipeline_email_queue_id FROM pipeline_email_queue
     WHERE queue_status = 'pending'
     ORDER BY created_at ASC
     LIMIT %d",
    BATCH_SIZE
));

$sent = 0;
$failed = 0;
$skipped = 0;

foreach ($pendingRS as $row)
{
    $queueID = intval($row['pipeline_email_queue_id']);

    // Atomically claim this row - if another worker process (or a
    // concurrent spawn) already claimed it, rowCount() is 0 and we move on.
    $claimStmt = $db->query(sprintf(
        "UPDATE pipeline_email_queue SET queue_status = 'processing'
         WHERE pipeline_email_queue_id = %d AND queue_status = 'pending'",
        $queueID
    ), true);

    if (!$claimStmt || $claimStmt->rowCount() === 0)
    {
        $skipped++;
        continue;
    }

    $rowRS = $db->getAllAssoc(sprintf(
        "SELECT * FROM pipeline_email_queue WHERE pipeline_email_queue_id = %d",
        $queueID
    ));

    if (empty($rowRS))
    {
        continue;
    }

    $queued = $rowRS[0];
    $automation = new PipelineEmailAutomation(intval($queued['site_id']));

    $success = $automation->sendQueuedEmailNow(
        $queued['recipient_email'],
        $queued['recipient_name'],
        $queued['subject'],
        $queued['body'],
        intval($queued['user_id'])
    );

    if ($success)
    {
        $db->query(sprintf(
            "UPDATE pipeline_email_queue
             SET queue_status = 'sent', sent_at = NOW()
             WHERE pipeline_email_queue_id = %d",
            $queueID
        ), true);
        $sent++;
    }
    else
    {
        $attempts = intval($queued['attempts']) + 1;
        $error = $automation->getLastSendError();
        $nextStatus = ($attempts >= MAX_ATTEMPTS) ? 'failed' : 'pending';

        $db->query(sprintf(
            "UPDATE pipeline_email_queue
             SET queue_status = %s, attempts = %d, last_error = %s
             WHERE pipeline_email_queue_id = %d",
            $db->makeQueryString($nextStatus),
            $attempts,
            $db->makeQueryString($error),
            $queueID
        ), true);
        $failed++;
    }
}

echo "Pipeline email queue: sent={$sent} failed={$failed} skipped={$skipped}\n";
