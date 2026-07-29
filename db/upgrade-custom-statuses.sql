-- Custom Pipeline Status Migration
-- Adds custom recruitment workflow statuses

-- Screen Select (ID: 1000)
INSERT IGNORE INTO `candidate_joborder_status` 
(`candidate_joborder_status_id`, `short_description`, `can_be_scheduled`, `triggers_email`, `is_enabled`) 
VALUES (1000, 'Screen Select', 0, 0, 1);

-- Screen Reject (ID: 1010)
INSERT IGNORE INTO `candidate_joborder_status` 
(`candidate_joborder_status_id`, `short_description`, `can_be_scheduled`, `triggers_email`, `is_enabled`) 
VALUES (1010, 'Screen Reject', 0, 0, 1);

-- L1 Select (ID: 1020)
INSERT IGNORE INTO `candidate_joborder_status` 
(`candidate_joborder_status_id`, `short_description`, `can_be_scheduled`, `triggers_email`, `is_enabled`) 
VALUES (1020, 'L1 Select', 0, 0, 1);

-- L2 Select (ID: 1030)
INSERT IGNORE INTO `candidate_joborder_status` 
(`candidate_joborder_status_id`, `short_description`, `can_be_scheduled`, `triggers_email`, `is_enabled`) 
VALUES (1030, 'L2 Select', 0, 0, 1);

-- L3 Select (ID: 1040)
INSERT IGNORE INTO `candidate_joborder_status` 
(`candidate_joborder_status_id`, `short_description`, `can_be_scheduled`, `triggers_email`, `is_enabled`) 
VALUES (1040, 'L3 Select', 0, 0, 1);

-- Offer Release (ID: 1050)
INSERT IGNORE INTO `candidate_joborder_status` 
(`candidate_joborder_status_id`, `short_description`, `can_be_scheduled`, `triggers_email`, `is_enabled`) 
VALUES (1050, 'Offer Release', 0, 0, 1);

-- Onboarded (ID: 1060)
INSERT IGNORE INTO `candidate_joborder_status` 
(`candidate_joborder_status_id`, `short_description`, `can_be_scheduled`, `triggers_email`, `is_enabled`) 
VALUES (1060, 'Onboarded', 0, 0, 1);

-- No Show (ID: 1070)
INSERT IGNORE INTO `candidate_joborder_status`
(`candidate_joborder_status_id`, `short_description`, `can_be_scheduled`, `triggers_email`, `is_enabled`)
VALUES (1070, 'No Show', 0, 0, 1);

-- Hired (ID: 1080) and Withdrawn (ID: 1090) were added directly against the
-- production database outside source control - db/upgrade-pipeline-email-
-- templates-hired-withdrawn.sql adds their email templates and flips
-- triggers_email on, but never created these two rows themselves. Backfilling
-- them here (with triggers_email already on) so a fresh install/schema
-- rebuild matches production instead of being missing these two statuses.

-- Hired (ID: 1080)
INSERT IGNORE INTO `candidate_joborder_status`
(`candidate_joborder_status_id`, `short_description`, `can_be_scheduled`, `triggers_email`, `is_enabled`)
VALUES (1080, 'Hired', 0, 1, 1);

-- Withdrawn (ID: 1090)
INSERT IGNORE INTO `candidate_joborder_status`
(`candidate_joborder_status_id`, `short_description`, `can_be_scheduled`, `triggers_email`, `is_enabled`)
VALUES (1090, 'Withdrawn', 0, 1, 1);
