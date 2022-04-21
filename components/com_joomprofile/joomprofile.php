<?php
/**
 * @package     Joomla.Admin
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
jimport( 'joomla.filesystem.file' );

$app = JFactory::getApplication();
$option 	= $app->input->get('option', 	'com_joomprofile');
$extension 	= $app->input->get('view', 		'cpanel');
$task 		= $app->input->get('task', 		'index');

include_once JPATH_SITE.'/components/'.$option.'/includes/autoload.php';
	
$extension = JoomprofileExtension::get($extension, array());
echo $extension->execute($task);

