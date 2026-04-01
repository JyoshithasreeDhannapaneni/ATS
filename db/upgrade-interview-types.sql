-- Upgrade script to add L1, L2, L3, HR Interview types
-- Run this script to add the new calendar event types

-- Update existing Interview type to L1 Interview
UPDATE `calendar_event_type` 
SET `short_description` = 'L1 Interview' 
WHERE `calendar_event_type_id` = 400;

-- Add L2 Interview type
INSERT INTO `calendar_event_type` (`calendar_event_type_id`, `short_description`, `icon_image`) 
VALUES (410, 'L2 Interview', 'images/interview.gif')
ON DUPLICATE KEY UPDATE `short_description` = 'L2 Interview';

-- Add L3 Interview type
INSERT INTO `calendar_event_type` (`calendar_event_type_id`, `short_description`, `icon_image`) 
VALUES (420, 'L3 Interview', 'images/interview.gif')
ON DUPLICATE KEY UPDATE `short_description` = 'L3 Interview';

-- Add HR Interview type
INSERT INTO `calendar_event_type` (`calendar_event_type_id`, `short_description`, `icon_image`) 
VALUES (430, 'HR Interview', 'images/interview.gif')
ON DUPLICATE KEY UPDATE `short_description` = 'HR Interview';
