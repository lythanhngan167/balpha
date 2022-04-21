<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JLoader::register('JoomprofileController', __DIR__.'/controller.php');
JLoader::register('JoomprofileExtension', __DIR__.'/extension.php');
JLoader::register('JoomprofileViewHtml', __DIR__.'/viewhtml.php');
JLoader::register('JoomprofileViewJson', __DIR__.'/viewjson.php');
JLoader::register('JoomprofileTemplate', __DIR__.'/template.php');
JLoader::register('JoomprofileModel', __DIR__.'/model.php');
JLoader::register('JoomprofileTable', __DIR__.'/table.php');
JLoader::register('JoomprofileModelform', __DIR__.'/modelform.php');
JLoader::register('JoomprofileLib', __DIR__.'/lib.php');