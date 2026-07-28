-- Pipeline Email Templates Migration - Hired / Withdrawn
--
-- Two more custom pipeline statuses (1080 "Hired", 1090 "Withdrawn") exist
-- on production but aren't defined in any checked-in schema/upgrade file -
-- they were evidently added directly against the database at some point,
-- outside source control. This migration does NOT (re-)create those status
-- rows (candidate_joborder_status is left untouched here beyond the
-- triggers_email flip below) - it only adds their email templates, mirroring
-- db/upgrade-pipeline-email-templates.sql for the other 8 custom statuses.
--
-- Same token set as that file (PipelineEmailAutomation::_substituteVars):
-- %CANDNAME%, %CANDFIRSTNAME%, %CANDLASTNAME%, %CANDEMAIL%, %JOBTITLE%,
-- %COMPANY%, %JOBLOCATION%, %RECRUITER%, %RECRUITEREMAIL%, %SITENAME%,
-- %DATETIME%. Idempotent via WHERE NOT EXISTS on tag - see that file's
-- header comment for why a plain INSERT IGNORE wouldn't be safe here.

-- Hired (1080)
INSERT INTO `email_template`
    (`text`, `allow_substitution`, `site_id`, `tag`, `title`, `possible_variables`, `disabled`, `subject`)
SELECT
    'Dear %CANDFIRSTNAME%,\r\n\r\nCongratulations, and welcome to %COMPANY%!\r\n\r\nWe''re delighted to confirm your hiring for the %JOBTITLE% position. It''s been a pleasure getting to know you throughout this process, and we''re excited to have you join the team.\r\n\r\n%RECRUITER% will be in touch shortly with next steps and everything you''ll need before your start date.\r\n\r\nCongratulations again - we''re looking forward to working with you!\r\n\r\nBest regards,\r\n%RECRUITER%\r\n%SITENAME%',
    1, 1, 'PIPELINE_STATUS_1080', 'Hired (Sent to Candidate)',
    '%CANDNAME%%CANDFIRSTNAME%%CANDLASTNAME%%CANDEMAIL%%JOBTITLE%%COMPANY%%JOBLOCATION%%RECRUITER%%RECRUITEREMAIL%%SITENAME%%DATETIME%',
    0, 'Welcome to the Team! - %JOBTITLE% at %COMPANY%'
WHERE NOT EXISTS (SELECT 1 FROM `email_template` WHERE `tag` = 'PIPELINE_STATUS_1080' AND `site_id` = 1);

-- Withdrawn (1090) - candidate-initiated, so tone is an acknowledgment
-- rather than a decision announcement.
INSERT INTO `email_template`
    (`text`, `allow_substitution`, `site_id`, `tag`, `title`, `possible_variables`, `disabled`, `subject`)
SELECT
    'Dear %CANDFIRSTNAME%,\r\n\r\nThank you for letting us know that you''d like to withdraw your application for the %JOBTITLE% position at %COMPANY%. We''ve updated your status accordingly, and no further action is needed on your end.\r\n\r\nWe appreciate the time you invested in this process and wish you the very best in your search. Should your circumstances change, or a future opportunity feel like the right fit, we''d be glad to hear from you again.\r\n\r\nThank you again for your interest in %COMPANY%.\r\n\r\nBest regards,\r\n%RECRUITER%\r\n%SITENAME%',
    1, 1, 'PIPELINE_STATUS_1090', 'Withdrawn (Sent to Candidate)',
    '%CANDNAME%%CANDFIRSTNAME%%CANDLASTNAME%%CANDEMAIL%%JOBTITLE%%COMPANY%%JOBLOCATION%%RECRUITER%%RECRUITEREMAIL%%SITENAME%%DATETIME%',
    0, 'Confirming Your Withdrawal - %JOBTITLE% at %COMPANY%'
WHERE NOT EXISTS (SELECT 1 FROM `email_template` WHERE `tag` = 'PIPELINE_STATUS_1090' AND `site_id` = 1);

-- Turn on automated sending now that each has a template.
UPDATE `candidate_joborder_status`
SET `triggers_email` = 1
WHERE `candidate_joborder_status_id` IN (1080, 1090);
