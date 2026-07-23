-- Add a manual "Contact Mobile Number" text field to joborder, alongside the
-- existing contact_id link to a saved Contact record. This lets a recruiter
-- record a phone number for the job order's contact without requiring a
-- full Contact record to already exist.

ALTER TABLE `joborder`
ADD COLUMN `contact_phone` VARCHAR(40) NULL DEFAULT NULL
AFTER `contact_id`;
