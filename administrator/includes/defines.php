<?php
/**
 * @package    Joomla.Administrator
 *
 * @copyright  Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Global definitions
$parts = explode(DIRECTORY_SEPARATOR, JPATH_BASE);
array_pop($parts);

// Defines
define('JPATH_ROOT',          implode(DIRECTORY_SEPARATOR, $parts));
define('JPATH_SITE',          JPATH_ROOT);
define('JPATH_CONFIGURATION', JPATH_ROOT);
define('JPATH_ADMINISTRATOR', JPATH_ROOT . DIRECTORY_SEPARATOR . 'administrator');
define('JPATH_LIBRARIES',     JPATH_ROOT . DIRECTORY_SEPARATOR . 'libraries');
define('JPATH_PLUGINS',       JPATH_ROOT . DIRECTORY_SEPARATOR . 'plugins');
define('JPATH_INSTALLATION',  JPATH_ROOT . DIRECTORY_SEPARATOR . 'installation');
define('JPATH_THEMES',        JPATH_BASE . DIRECTORY_SEPARATOR . 'templates');
define('JPATH_CACHE',         JPATH_BASE . DIRECTORY_SEPARATOR . 'cache');
define('JPATH_MANIFESTS',     JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'manifests');


define('BIZ_XU','BizXu');

define('REST_API_KEY','MzUzYmIxYzUtZGFhNy00NzczLWEwOWQtZDFmYzA4ZjAxMDc4');
define('APP_ID','e9060a2d-50ee-4195-927d-313fdff94973');

define('NOTI_ALL_GROUP', 172);
define('NOTI_CUSTOMER_GROUP', 173);
define('NOTI_AGENT_GROUP', 174);
define('NOTI_TESTER_GROUP', 175);

define('EXPIRED_DATA_CUSTOMER', 12); // 12 months
define('EXPIRED_DATA_LANDINGPAGE', 2); // 2 months


define('PROJECT_LANDINGPAGE', 22);

// User logs
define('TRANSFER_AGENT', 176);
define('LEVEL_UPDATE', 177);

define('AT_PROJECT', 32);
