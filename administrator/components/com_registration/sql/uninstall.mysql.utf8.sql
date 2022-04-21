DROP TABLE IF EXISTS `#__registration`;

DELETE FROM `#__content_types` WHERE (type_alias LIKE 'com_registration.%');