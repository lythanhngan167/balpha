CREATE TABLE IF NOT EXISTS `#__orders` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`asset_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',

`category_id` INT NOT NULL ,
`quantity` INT NOT NULL ,
`price` INT NOT NULL ,
`total` INT NOT NULL ,
`project_id` INT NOT NULL ,
`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL ,
`created_by` INT(11)  NOT NULL ,
`modified_by` INT(11)  NOT NULL ,
`create_date` DATETIME NOT NULL ,
`modified_date` DATETIME NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

