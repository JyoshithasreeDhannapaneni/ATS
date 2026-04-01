-- Add role and interviewer_type columns to user table
-- Run this SQL to add role-based access

-- Add role column (admin, recruiter, interviewer)
ALTER TABLE `user` ADD COLUMN `role` VARCHAR(20) DEFAULT 'recruiter' AFTER `access_level`;

-- Add interviewer_type column (L1, L2, L3, HR) - only used when role is 'interviewer'
ALTER TABLE `user` ADD COLUMN `interviewer_type` VARCHAR(10) DEFAULT NULL AFTER `role`;

-- Update existing admin users (access_level 500 or 400) to have admin role
UPDATE `user` SET `role` = 'admin' WHERE `access_level` >= 400;

-- Create index for faster role-based queries
ALTER TABLE `user` ADD INDEX `IDX_role` (`role`);
