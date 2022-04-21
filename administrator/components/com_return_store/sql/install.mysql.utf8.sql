CREATE TABLE IF NOT EXISTS `#__return_store` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`asset_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',

`user_id` INT NOT NULL ,
`customer_id` INT NOT NULL ,
`project_id` INT NOT NULL ,
`status_id` VARCHAR(255)  NOT NULL ,
`buy_date` DATETIME NOT NULL ,
`created_date` DATETIME NOT NULL ,
`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL ,
`created_by` INT(11)  NOT NULL ,
`modified_by` INT(11)  NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

