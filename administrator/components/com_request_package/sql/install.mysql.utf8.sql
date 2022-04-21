CREATE TABLE IF NOT EXISTS `#__request_package` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`asset_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',

`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL DEFAULT 1,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT "0000-00-00 00:00:00",
`created_by` INT(11)  NOT NULL ,
`modified_by` INT(11)  NOT NULL ,
`name` VARCHAR(255)  NOT NULL ,
`email` VARCHAR(255)  NOT NULL ,
`phone` VARCHAR(255)  NOT NULL ,
`job` VARCHAR(255)  NOT NULL ,
`address` VARCHAR(255)  NOT NULL ,
`note` TEXT NOT NULL ,
`province` VARCHAR(255)  NOT NULL ,
`status` VARCHAR(255)  NOT NULL ,
`company` VARCHAR(255)  NOT NULL ,
`services` VARCHAR(255)  NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;


INSERT INTO `#__content_types` (`type_title`, `type_alias`, `table`, `content_history_options`)
SELECT * FROM ( SELECT 'Request Package','com_request_package.requestpackage','{"special":{"dbtable":"#__request_package","key":"id","type":"Requestpackage","prefix":"Request_packageTable"}}', '{"formFile":"administrator\/components\/com_request_package\/models\/forms\/requestpackage.xml", "hideFields":["checked_out","checked_out_time","params","language" ,"note"], "ignoreChanges":["modified_by", "modified", "checked_out", "checked_out_time"], "convertToInt":["publish_up", "publish_down"], "displayLookup":[{"sourceColumn":"catid","targetTable":"#__categories","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"group_id","targetTable":"#__usergroups","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"created_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"},{"sourceColumn":"access","targetTable":"#__viewlevels","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"modified_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"}]}') AS tmp
WHERE NOT EXISTS (
	SELECT type_alias FROM `#__content_types` WHERE (`type_alias` = 'com_request_package.requestpackage')
) LIMIT 1;
