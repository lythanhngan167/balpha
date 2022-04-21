CREATE TABLE IF NOT EXISTS `#__referral` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`asset_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',

`state` TINYINT(1)  NOT NULL  DEFAULT 1,
`ordering` INT(11)  NOT NULL  DEFAULT 0,
`checked_out` INT(11)  NOT NULL  DEFAULT 0,
`checked_out_time` DATETIME NOT NULL  DEFAULT "0000-00-00 00:00:00",
`created_by` INT(11)  NOT NULL  DEFAULT 0,
`modified_by` INT(11)  NOT NULL  DEFAULT 0,
`created_date` DATETIME NOT NULL  DEFAULT "0000-00-00 00:00:00",
`modified_date` DATETIME NOT NULL  DEFAULT "0000-00-00 00:00:00",
`cat_id` TEXT NOT NULL ,
`status_id` VARCHAR(255)  NOT NULL  DEFAULT "new",
`name` VARCHAR(255)  NOT NULL ,
`phone` VARCHAR(255)  NOT NULL ,
`is_exist` TINYINT NOT NULL DEFAULT 0,
`user_id` INT NOT NULL  DEFAULT 0,
`referral_code` VARCHAR(255)  NOT NULL  DEFAULT "",
`email` VARCHAR(255)  NOT NULL  DEFAULT "",
`is_exist_agent` TINYINT NOT NULL DEFAULT 0,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8mb4_unicode_ci;


INSERT INTO `#__content_types` (`type_title`, `type_alias`, `table`, `field_mappings`, `router`, `content_history_options`)
SELECT * FROM ( SELECT 'Referral Category','com_referral.category','{"special":{"dbtable":"#__categories","key":"id","type":"Category","prefix":"JTable","config":"array()"},"common":   {"dbtable":"#__ucm_content","key":"ucm_id","type":"Corecontent","prefix":"JTable","config":"array()"}}', '{"common":{"core_content_item_id":"id","core_title":"title","core_state":"published","core_alias":"alias","core_created_time":"created_time","core_modified_time":"modified_time","core_body":"description", "core_hits":"hits","core_publish_up":"null","core_publish_down":"null","core_access":"access", "core_params":"params", "core_featured":"null", "core_metadata":"metadata", "core_language":"language", "core_images":"null", "core_urls":"null", "core_version":"version", "core_ordering":"null", "core_metakey":"metakey", "core_metadesc":"metadesc", "core_catid":"parent_id", "core_xreference":"null", "asset_id":"asset_id"}, "special":{"parent_id":"parent_id","lft":"lft","rgt":"rgt","level":"level","path":"path","extension":"extension","note":"note"}}', 'ReferralRouter::getCategoryRoute', '{"formFile":"administrator\/components\/com_categories\/models\/forms\/category.xml", "hideFields":["asset_id","checked_out","checked_out_time","version","lft","rgt","level","path","extension"], "ignoreChanges":["modified_user_id", "modified_time", "checked_out", "checked_out_time", "version", "hits", "path"],"convertToInt":["publish_up", "publish_down"], "displayLookup":[{"sourceColumn":"created_user_id","targetTable":"#__users","targetColumn":"id","displayColumn":"name"},{"sourceColumn":"access","targetTable":"#__viewlevels","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"modified_user_id","targetTable":"#__users","targetColumn":"id","displayColumn":"name"},{"sourceColumn":"parent_id","targetTable":"#__categories","targetColumn":"id","displayColumn":"title"}]}') AS tmp WHERE NOT EXISTS (
	SELECT type_alias FROM `#__content_types` WHERE (`type_alias` = 'com_referral.referral')
) LIMIT 1;
