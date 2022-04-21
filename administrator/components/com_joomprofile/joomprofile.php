<?php
/**
 * @package     Joomla.Admin
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$app = JFactory::getApplication();
$option 	= $app->input->get('option', 	'com_joomprofile');
$view 	= $app->input->get('view', 		'profile');
$task 		= $app->input->get('task', 		'field.grid');

include_once JPATH_SITE.'/components/'.$option.'/includes/autoload.php';

JoomprofileExtension::$paths[] = JPATH_SITE.'/components/'.$option.'/extensions';	
$extension = JoomprofileExtension::get($view, array());

// load header
include_once __DIR__.'/tmpl/header.php';

$content = $extension->execute($task);

$menus = array();
$args = array(&$menus);

JoomprofileExtension::trigger('onJoomprofileAdminMenuRender', $args);
include_once __DIR__.'/tmpl/template.php';


