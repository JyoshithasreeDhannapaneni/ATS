-- Pipeline Email Queue Migration
--
-- Pipeline-status emails used to be sent synchronously, inline with the
-- status-change AJAX request (ajax/updatePipelineStatus.php ->
-- Pipelines::setStatus() -> PipelineEmailAutomation::onStatusChange()).
-- That means a recruiter clicking to change a candidate's status had to
-- wait out the full SMTP round trip (PHPMailer's Timeout is 10s) before
-- the UI responded - and if a send failed, nothing recorded why; it just
-- vanished (email_history only ever logs on success, per
-- Mailer::logMessage(), called only in the success branch of send()).
--
-- This table turns sends into queued work: onStatusChange() now renders
-- the final subject/body (variable substitution needs $_SESSION, so that
-- still happens inline, cheaply, with no I/O) and inserts a pending row
-- here instead of calling the mailer directly. A background worker
-- (cron/process-pipeline-email-queue.php), spawned via exec() right after
-- enqueueing so it runs within seconds without blocking the request,
-- processes pending rows: on success, Mailer::send() already logs to
-- email_history as before; on failure, this table itself is the
-- audit trail (attempts, last_error) and the retry mechanism (stays
-- 'pending' until MAX_ATTEMPTS, then flips to 'failed').

CREATE TABLE `pipeline_email_queue` (
  `pipeline_email_queue_id` int(11) NOT NULL AUTO_INCREMENT,
  `candidate_id` int(11) NOT NULL,
  `joborder_id` int(11) NOT NULL,
  `status_id` int(11) NOT NULL,
  `site_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '-1',
  `recipient_email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `recipient_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `subject` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `body` text COLLATE utf8_unicode_ci NOT NULL,
  `queue_status` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'pending',
  `attempts` int(11) NOT NULL DEFAULT '0',
  `last_error` text COLLATE utf8_unicode_ci,
  `created_at` datetime NOT NULL,
  `sent_at` datetime DEFAULT NULL,
  PRIMARY KEY (`pipeline_email_queue_id`),
  KEY `IDX_queue_status` (`queue_status`),
  KEY `IDX_candidate_joborder` (`candidate_id`, `joborder_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
