-- Upgrade script to add meeting-related fields to calendar_event table
-- Run this script to enable meeting link storage and attendee email tracking

-- Add attendee_email column to store the email of the meeting attendee
ALTER TABLE `calendar_event` 
ADD COLUMN `attendee_email` VARCHAR(255) NULL DEFAULT NULL 
AFTER `description`;

-- Add meeting_link column to store the meeting URL (Teams/Zoom/Google Meet)
ALTER TABLE `calendar_event` 
ADD COLUMN `meeting_link` VARCHAR(500) NULL DEFAULT NULL 
AFTER `attendee_email`;

-- Add meeting_platform column to store which platform was used
ALTER TABLE `calendar_event` 
ADD COLUMN `meeting_platform` VARCHAR(50) NULL DEFAULT NULL 
AFTER `meeting_link`;

-- Add index on attendee_email for faster lookups
ALTER TABLE `calendar_event` 
ADD INDEX `idx_attendee_email` (`attendee_email`);
