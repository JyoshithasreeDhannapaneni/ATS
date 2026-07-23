-- Configurable interview/pipeline round templates, assignable per job order
-- (optionally defaulted per department). A joborder with no template keeps
-- using the original fixed global status list — this is purely additive.

CREATE TABLE IF NOT EXISTS `pipeline_template` (
  `template_id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(128) NOT NULL,
  `company_department_id` INT(11) DEFAULT NULL,
  `site_id` INT(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`template_id`),
  KEY `IDX_pt_department` (`company_department_id`),
  KEY `IDX_pt_site` (`site_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `pipeline_template_stage` (
  `stage_id` INT(11) NOT NULL AUTO_INCREMENT,
  `template_id` INT(11) NOT NULL,
  `candidate_joborder_status_id` INT(11) NOT NULL,
  `stage_order` INT(11) NOT NULL DEFAULT 0,
  `stage_name` VARCHAR(64) NOT NULL,
  `is_enabled` INT(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`stage_id`),
  KEY `IDX_pts_template` (`template_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `joborder`
ADD COLUMN `pipeline_template_id` INT(11) DEFAULT NULL
AFTER `company_department_id`;
