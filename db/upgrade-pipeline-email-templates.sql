-- Pipeline Email Templates Migration
--
-- Adds candidate-facing email templates for the custom pipeline statuses
-- added in db/upgrade-custom-statuses.sql (Screen Select, Screen Reject,
-- L1/L2/L3 Select, Offer Release, Onboarded, No Show). Those statuses
-- currently have triggers_email = 0 and no matching email_template row,
-- so today moving a candidate into any of them sends no email at all.
--
-- PipelineEmailAutomation::_getTemplate() looks up a row in email_template
-- by tag = 'PIPELINE_STATUS_<statusID>' (and site_id, disabled = 0); this
-- migration inserts exactly those rows, then flips triggers_email on for
-- each status so the automation actually fires.
--
-- Uses %TOKEN% placeholders substituted by
-- PipelineEmailAutomation::_substituteVars(): %CANDNAME%, %CANDFIRSTNAME%,
-- %CANDLASTNAME%, %CANDEMAIL%, %JOBTITLE%, %COMPANY%, %JOBLOCATION%,
-- %RECRUITER%, %RECRUITEREMAIL%, %SITENAME%, %DATETIME%. These are a
-- separate, simpler token set from the legacy %CANDFULLNAME%/%JBODTITLE%
-- style used by EMAIL_TEMPLATE_STATUSCHANGE etc. - don't mix the two.
--
-- email_template_id is AUTO_INCREMENT with no UNIQUE constraint on `tag`,
-- so a plain INSERT IGNORE here would NOT be idempotent on a second run
-- (unlike upgrade-custom-statuses.sql, which specifies an explicit PK).
-- Each INSERT below is instead guarded by a WHERE NOT EXISTS on the tag,
-- so re-running this file is a no-op past the first time.

-- Screen Select (1000)
INSERT INTO `email_template`
    (`text`, `allow_substitution`, `site_id`, `tag`, `title`, `possible_variables`, `disabled`, `subject`)
SELECT
    'Dear %CANDFIRSTNAME%,\r\n\r\nGreat news! You''ve successfully cleared the initial screening for the %JOBTITLE% position at %COMPANY%.\r\n\r\nWe were impressed with your background and would like to move you forward to the next round. %RECRUITER% will be in touch shortly with details on what to expect next.\r\n\r\nThank you for your patience, and congratulations on reaching this stage!\r\n\r\nBest regards,\r\n%RECRUITER%\r\n%SITENAME%',
    1, 1, 'PIPELINE_STATUS_1000', 'Screen Select (Sent to Candidate)',
    '%CANDNAME%%CANDFIRSTNAME%%CANDLASTNAME%%CANDEMAIL%%JOBTITLE%%COMPANY%%JOBLOCATION%%RECRUITER%%RECRUITEREMAIL%%SITENAME%%DATETIME%',
    0, 'You''re Moving Forward - %JOBTITLE% at %COMPANY%'
WHERE NOT EXISTS (SELECT 1 FROM `email_template` WHERE `tag` = 'PIPELINE_STATUS_1000' AND `site_id` = 1);

-- Screen Reject (1010) - reused as the one general-purpose respectful
-- decline template regardless of which round the rejection happens at;
-- see the "Gap" note discussed with the user before writing this.
INSERT INTO `email_template`
    (`text`, `allow_substitution`, `site_id`, `tag`, `title`, `possible_variables`, `disabled`, `subject`)
SELECT
    'Dear %CANDFIRSTNAME%,\r\n\r\nThank you for taking the time to apply for the %JOBTITLE% position at %COMPANY%, and for the effort you put into the process so far.\r\n\r\nAfter careful consideration, we''ve decided to move forward with other candidates whose experience more closely matches what we need for this particular role. This was not an easy decision, and it does not reflect on your skills or potential.\r\n\r\nWe''d like to keep your profile on file and would welcome your interest in future opportunities that may be a better fit.\r\n\r\nThank you again for your time and interest in %COMPANY%.\r\n\r\nBest regards,\r\n%RECRUITER%\r\n%SITENAME%',
    1, 1, 'PIPELINE_STATUS_1010', 'Screen Reject (Sent to Candidate)',
    '%CANDNAME%%CANDFIRSTNAME%%CANDLASTNAME%%CANDEMAIL%%JOBTITLE%%COMPANY%%JOBLOCATION%%RECRUITER%%RECRUITEREMAIL%%SITENAME%%DATETIME%',
    0, 'Update on Your Application - %JOBTITLE% at %COMPANY%'
WHERE NOT EXISTS (SELECT 1 FROM `email_template` WHERE `tag` = 'PIPELINE_STATUS_1010' AND `site_id` = 1);

-- L1 Select (1020)
INSERT INTO `email_template`
    (`text`, `allow_substitution`, `site_id`, `tag`, `title`, `possible_variables`, `disabled`, `subject`)
SELECT
    'Dear %CANDFIRSTNAME%,\r\n\r\nCongratulations! You''ve successfully cleared the first round of interviews for the %JOBTITLE% position at %COMPANY%.\r\n\r\nWe were glad to learn more about your experience and are moving you forward to the next round. %RECRUITER% will reach out shortly to coordinate the details.\r\n\r\nThank you for your continued interest, and well done on reaching this stage!\r\n\r\nBest regards,\r\n%RECRUITER%\r\n%SITENAME%',
    1, 1, 'PIPELINE_STATUS_1020', 'L1 Select (Sent to Candidate)',
    '%CANDNAME%%CANDFIRSTNAME%%CANDLASTNAME%%CANDEMAIL%%JOBTITLE%%COMPANY%%JOBLOCATION%%RECRUITER%%RECRUITEREMAIL%%SITENAME%%DATETIME%',
    0, 'You''ve Cleared Round 1 - %JOBTITLE% at %COMPANY%'
WHERE NOT EXISTS (SELECT 1 FROM `email_template` WHERE `tag` = 'PIPELINE_STATUS_1020' AND `site_id` = 1);

-- L2 Select (1030)
INSERT INTO `email_template`
    (`text`, `allow_substitution`, `site_id`, `tag`, `title`, `possible_variables`, `disabled`, `subject`)
SELECT
    'Dear %CANDFIRSTNAME%,\r\n\r\nGreat news - you''ve cleared the second round of interviews for the %JOBTITLE% position at %COMPANY%!\r\n\r\nYou''re now moving on to the final round. %RECRUITER% will follow up shortly with scheduling details.\r\n\r\nYou''re doing great - congratulations on making it this far!\r\n\r\nBest regards,\r\n%RECRUITER%\r\n%SITENAME%',
    1, 1, 'PIPELINE_STATUS_1030', 'L2 Select (Sent to Candidate)',
    '%CANDNAME%%CANDFIRSTNAME%%CANDLASTNAME%%CANDEMAIL%%JOBTITLE%%COMPANY%%JOBLOCATION%%RECRUITER%%RECRUITEREMAIL%%SITENAME%%DATETIME%',
    0, 'You''ve Cleared Round 2 - Final Round Next! - %JOBTITLE% at %COMPANY%'
WHERE NOT EXISTS (SELECT 1 FROM `email_template` WHERE `tag` = 'PIPELINE_STATUS_1030' AND `site_id` = 1);

-- L3 Select (1040)
INSERT INTO `email_template`
    (`text`, `allow_substitution`, `site_id`, `tag`, `title`, `possible_variables`, `disabled`, `subject`)
SELECT
    'Dear %CANDFIRSTNAME%,\r\n\r\nCongratulations! You''ve successfully cleared the final round of interviews for the %JOBTITLE% position at %COMPANY%.\r\n\r\nThis is a fantastic milestone, and we''re excited about the possibility of you joining the team. %RECRUITER% will be in touch very soon with next steps.\r\n\r\nThank you for your patience and effort throughout this process.\r\n\r\nBest regards,\r\n%RECRUITER%\r\n%SITENAME%',
    1, 1, 'PIPELINE_STATUS_1040', 'L3 Select (Sent to Candidate)',
    '%CANDNAME%%CANDFIRSTNAME%%CANDLASTNAME%%CANDEMAIL%%JOBTITLE%%COMPANY%%JOBLOCATION%%RECRUITER%%RECRUITEREMAIL%%SITENAME%%DATETIME%',
    0, 'You''ve Cleared the Final Round! - %JOBTITLE% at %COMPANY%'
WHERE NOT EXISTS (SELECT 1 FROM `email_template` WHERE `tag` = 'PIPELINE_STATUS_1040' AND `site_id` = 1);

-- Offer Release (1050)
INSERT INTO `email_template`
    (`text`, `allow_substitution`, `site_id`, `tag`, `title`, `possible_variables`, `disabled`, `subject`)
SELECT
    'Dear %CANDFIRSTNAME%,\r\n\r\nWe are delighted to extend an offer for the %JOBTITLE% position at %COMPANY%!\r\n\r\nThroughout the process, you''ve consistently stood out, and we''re excited about the possibility of you joining our team. %RECRUITER% will be in touch shortly with your formal offer details, compensation, and next steps.\r\n\r\nCongratulations again - we hope you''re as excited as we are!\r\n\r\nBest regards,\r\n%RECRUITER%\r\n%SITENAME%',
    1, 1, 'PIPELINE_STATUS_1050', 'Offer Release (Sent to Candidate)',
    '%CANDNAME%%CANDFIRSTNAME%%CANDLASTNAME%%CANDEMAIL%%JOBTITLE%%COMPANY%%JOBLOCATION%%RECRUITER%%RECRUITEREMAIL%%SITENAME%%DATETIME%',
    0, 'Congratulations - Offer for %JOBTITLE% at %COMPANY%'
WHERE NOT EXISTS (SELECT 1 FROM `email_template` WHERE `tag` = 'PIPELINE_STATUS_1050' AND `site_id` = 1);

-- Onboarded (1060)
INSERT INTO `email_template`
    (`text`, `allow_substitution`, `site_id`, `tag`, `title`, `possible_variables`, `disabled`, `subject`)
SELECT
    'Dear %CANDFIRSTNAME%,\r\n\r\nWelcome aboard! We''re thrilled to confirm you''re officially on board as our new %JOBTITLE% at %COMPANY%.\r\n\r\n%RECRUITER% will be sharing your onboarding details, including your start date, required documentation, and what to expect on your first day.\r\n\r\nWe''re excited to have you join the team!\r\n\r\nBest regards,\r\n%RECRUITER%\r\n%SITENAME%',
    1, 1, 'PIPELINE_STATUS_1060', 'Onboarded (Sent to Candidate)',
    '%CANDNAME%%CANDFIRSTNAME%%CANDLASTNAME%%CANDEMAIL%%JOBTITLE%%COMPANY%%JOBLOCATION%%RECRUITER%%RECRUITEREMAIL%%SITENAME%%DATETIME%',
    0, 'Welcome to %COMPANY%! - %JOBTITLE%'
WHERE NOT EXISTS (SELECT 1 FROM `email_template` WHERE `tag` = 'PIPELINE_STATUS_1060' AND `site_id` = 1);

-- No Show (1070)
INSERT INTO `email_template`
    (`text`, `allow_substitution`, `site_id`, `tag`, `title`, `possible_variables`, `disabled`, `subject`)
SELECT
    'Dear %CANDFIRSTNAME%,\r\n\r\nWe noticed we weren''t able to connect for your scheduled interview for the %JOBTITLE% position at %COMPANY%. We understand things come up!\r\n\r\nIf you''re still interested in the opportunity, please reach out to %RECRUITER% at %RECRUITEREMAIL% so we can find a new time that works for you.\r\n\r\nWe look forward to hearing from you.\r\n\r\nBest regards,\r\n%RECRUITER%\r\n%SITENAME%',
    1, 1, 'PIPELINE_STATUS_1070', 'No Show (Sent to Candidate)',
    '%CANDNAME%%CANDFIRSTNAME%%CANDLASTNAME%%CANDEMAIL%%JOBTITLE%%COMPANY%%JOBLOCATION%%RECRUITER%%RECRUITEREMAIL%%SITENAME%%DATETIME%',
    0, 'We Missed You - %JOBTITLE% at %COMPANY%'
WHERE NOT EXISTS (SELECT 1 FROM `email_template` WHERE `tag` = 'PIPELINE_STATUS_1070' AND `site_id` = 1);

-- Turn on automated sending for all 8 custom statuses now that each has a
-- template. (Standard statuses 200/300/400/500/600/650/700/800 already
-- have triggers_email = 1 and a PHP-side default template, so they're
-- untouched here.) Plain UPDATE is naturally idempotent.
UPDATE `candidate_joborder_status`
SET `triggers_email` = 1
WHERE `candidate_joborder_status_id` IN (1000, 1010, 1020, 1030, 1040, 1050, 1060, 1070);
