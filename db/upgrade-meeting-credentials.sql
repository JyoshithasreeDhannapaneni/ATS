-- Meeting Credentials Database Migration
-- Allows storing meeting platform credentials in database (configurable via Admin UI)
-- Run this script to enable database-based credential management

-- Create meeting_credentials table for storing platform API credentials
CREATE TABLE IF NOT EXISTS `meeting_credentials` (
    `credential_id` INT(11) NOT NULL AUTO_INCREMENT,
    `site_id` INT(11) NOT NULL DEFAULT 1,
    `platform` VARCHAR(50) NOT NULL COMMENT 'Platform: teams, zoom, google_meet',
    `credential_key` VARCHAR(100) NOT NULL COMMENT 'Credential name: client_id, client_secret, etc.',
    `credential_value` TEXT COMMENT 'Encrypted credential value',
    `is_encrypted` TINYINT(1) NOT NULL DEFAULT 1,
    `date_created` DATETIME NOT NULL,
    `date_modified` DATETIME NOT NULL,
    PRIMARY KEY (`credential_id`),
    UNIQUE KEY `idx_platform_key` (`site_id`, `platform`, `credential_key`),
    KEY `idx_site_platform` (`site_id`, `platform`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Add setting for credential storage mode (config_file or database)
INSERT INTO `settings` (`setting_key`, `setting_value`, `site_id`)
SELECT 'meeting_credentials_source', 'database', site_id
FROM site
WHERE NOT EXISTS (
    SELECT 1 FROM settings 
    WHERE setting_key = 'meeting_credentials_source' 
    AND settings.site_id = site.site_id
);
