DROP TABLE IF EXISTS `#__notifications_user`;

DELETE FROM `#__content_types` WHERE (type_alias LIKE 'com_notifications_user.%');