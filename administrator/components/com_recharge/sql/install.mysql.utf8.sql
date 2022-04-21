CREATE TABLE IF NOT EXISTS `#__recharge` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`asset_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',

`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL ,
`created_by` INT(11)  NOT NULL ,
`modified_by` INT(11)  NOT NULL ,
`bank_name` VARCHAR(255)  NOT NULL ,
`amount` DOUBLE,
`note` TEXT NOT NULL ,
`status` VARCHAR(255)  NOT NULL ,
`image` TEXT NOT NULL ,
`created_time` DATETIME NOT NULL ,
`code` DOUBLE,
`type` VARCHAR(255)  NOT NULL ,
`updated_time` DATETIME NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

