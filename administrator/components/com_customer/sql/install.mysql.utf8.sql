CREATE TABLE IF NOT EXISTS `#__customers` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`asset_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',

`name` VARCHAR(255)  NOT NULL ,
`phone` VARCHAR(255)  NOT NULL ,
`email` VARCHAR(255)  NOT NULL ,
`place` VARCHAR(255)  NOT NULL ,
`sale_id` INT NOT NULL ,
`category_id` INT(11)  NOT NULL ,
`project_id` TEXT NOT NULL ,
`status_id` VARCHAR(255)  NOT NULL ,
`total_revenue` INT NOT NULL ,
`checked_out_time` DATETIME NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
`ordering` INT(11)  NOT NULL ,
`checked_out` INT(11)  NOT NULL ,
`modified_date` DATETIME NOT NULL ,
`created_by` INT(11)  NOT NULL ,
`modified_by` INT(11)  NOT NULL ,
`create_date` DATETIME NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

