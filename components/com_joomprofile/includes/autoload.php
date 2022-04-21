<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

if(defined('JOOMPROFILE_LOADED')){
	return true;
}

define('JOOMPROFILE_LOADED', true);
require_once __DIR__.'/defines.php';
require_once __DIR__.'/includes.php';
require_once __DIR__.'/global.php';

include_once dirname(__DIR__).'/base/autoload.php';
include_once dirname(__DIR__).'/helpers/autoload.php';
