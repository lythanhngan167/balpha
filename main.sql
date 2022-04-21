ALTER TABLE `wmspj_users` CHANGE `approved` `approved_user` TINYINT(4) NOT NULL COMMENT '0: chua approve, 1: da cap nhat thong tin, 9: duoc phe duyet';
mysql -u bcav_bcavietnam_user -p bcav_bcavietnam < /home/bcavietnam.com/public_html/demo2.bcavietnam.com/bigdump/backup-bcavietnam-30-03-2021-10-22.sql



mysql -u bcav_bcavietnam_user -p bcav_bcavietnam < /home/bcavietnam.com/public_html/bigdump/bcav_social_network1.sql


mysql -u bcav_bcavietnam_user -p bcav_bcavietnam < /home/bcavietnam.com/public_html/bigdump/bcav_social_network2.sql


ALTER TABLE `wmspj_k2_comments` ADD `phone_number` VARCHAR(20) NULL AFTER `published`;

ALTER TABLE `wmspj_k2_items` ADD `top_item` BOOLEAN NULL DEFAULT FALSE AFTER `language`, ADD `top_item_date` DATETIME NULL AFTER `top_item`;

ALTER TABLE `wmspj_k2_items` ADD `published_by` INT NOT NULL DEFAULT '0' AFTER `created_by`;

ALTER TABLE `wmspj_k2_items` ADD `status_notication` TINYINT NOT NULL DEFAULT '0' AFTER `top_item_date`, ADD `user_send_notification` INT NULL AFTER `status_notication`, ADD `date_send_notification` DATETIME NULL AFTER `user_send_notification`;