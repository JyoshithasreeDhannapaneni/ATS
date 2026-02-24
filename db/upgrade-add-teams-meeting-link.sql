-- Add Microsoft Teams meeting link column to calendar_event table
-- This allows storing Teams meeting URLs for scheduled calls/meetings

ALTER TABLE `calendar_event` 
ADD COLUMN `teams_meeting_link` TEXT COLLATE utf8_unicode_ci NULL DEFAULT NULL 
AFTER `public`;

-- Update schema version (adjust version number as needed)
-- UPDATE system SET schema_version = [NEXT_VERSION];
