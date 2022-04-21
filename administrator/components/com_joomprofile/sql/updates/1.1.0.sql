CREATE TABLE IF NOT EXISTS `#__joomprofile_config` (
  `id` varchar(255) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__joomprofile_field`
--

CREATE TABLE IF NOT EXISTS `#__joomprofile_field` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(100) NOT NULL,
  `title` varchar(255) NOT NULL,
  `tooltip` varchar(255) DEFAULT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '1',
  `css_class` varchar(255) DEFAULT NULL,
  `params` text,
  PRIMARY KEY (`id`),
  INDEX `idx_type` (`type`),
  INDEX `idx_published` (`published`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__joomprofile_fieldgroup`
--

CREATE TABLE IF NOT EXISTS `#__joomprofile_fieldgroup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `published` tinyint(1) NOT NULL DEFAULT '1',
  `ordering` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `params` text,
  PRIMARY KEY (`id`),
  INDEX `idx_published` (`published`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__joomprofile_fieldgroup_usergroups`
--

CREATE TABLE IF NOT EXISTS `#__joomprofile_fieldgroup_usergroups` (
  `fieldgroup_id` int(11) NOT NULL,
  `usergroup_id` int(11) NOT NULL,
  INDEX `idx_fieldgroup_id` (`fieldgroup_id`),
  INDEX `idx_usergroup_id` (`usergroup_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__joomprofile_fieldoption`
--

CREATE TABLE IF NOT EXISTS `#__joomprofile_fieldoption` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `field_id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `idx_field_id` (`field_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__joomprofile_field_fieldgroups`
--

CREATE TABLE IF NOT EXISTS `#__joomprofile_field_fieldgroups` (
  `field_fieldgroup_id` int(11) NOT NULL AUTO_INCREMENT,
  `field_id` int(11) NOT NULL,
  `fieldgroup_id` int(11) NOT NULL,
  `ordering` int(11) NOT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT '1',
  `editable` tinyint(1) NOT NULL DEFAULT '1',
  `required` tinyint(1) NOT NULL DEFAULT '0',
  `registration` tinyint(1) NOT NULL DEFAULT '1',
  `params` text,
  PRIMARY KEY (`field_fieldgroup_id`),
  INDEX `idx_field_id` (`field_id`),
  INDEX `idx_fieldgroup_id` (`fieldgroup_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `#__joomprofile_registration`
--

CREATE TABLE IF NOT EXISTS `#__joomprofile_registration` (
  `id` varchar(255) NOT NULL,
  `usergroups` varchar(255) NOT NULL,
  `values` text NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `#__joomprofile_field_values` (
  `field_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `value` varchar(255) NOT NULL,
  `privacy` tinyint(4) NOT NULL DEFAULT '0',
  INDEX `idx_field_id` (`field_id`),
  INDEX `idx_user_id` (`user_id`),
  FULLTEXT KEY `fullx_value` (`value`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `#__joomprofile_usergroup_searchfields` (
  `usergroup_searchfield_id` int(11) NOT NULL AUTO_INCREMENT,
  `usergroup_id` int(11) NOT NULL,
  `field_id` int(11) NOT NULL,
  `ordering` int(11) NOT NULL,
  PRIMARY KEY (`usergroup_searchfield_id`),
  INDEX `idx_usergroup_id` (`usergroup_id`),
  INDEX `idx_field_id` (`field_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
