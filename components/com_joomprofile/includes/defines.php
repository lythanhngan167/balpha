<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

define('JOOMPROFILE_STATE_UNPUBLISHED', 0);
define('JOOMPROFILE_STATE_PUBLISHED', 	1);
define('JOOMPROFILE_STATE_ADMINONLY', 	2);
define('JOOMPROFILE_STATE_TRASHED',    -2);

define('JOOMPROFILE_PATH_MEDIA', 	'images/com_joomprofile');
define('JOOMPROFILE_PATH_MEDIA_TMP', JOOMPROFILE_PATH_MEDIA.'/tmp');
define('JOOMPROFILE_PATH_MEDIA_USER', JOOMPROFILE_PATH_MEDIA.'/user');

define('JOOMPROFILE_PATH_SITE', dirname(__DIR__));
define('JOOMPROFILE_PATH_LIBRARIES', JOOMPROFILE_PATH_SITE.'/libraries');