<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

include_once __DIR__.'/defines.php';

JLoader::register('JoomprofileProfile', __DIR__.'/profile.php');
JLoader::register('JoomprofileProfileHelper', __DIR__.'/helper.php');

joomprofile_importlib('field');

JLoader::register('JoomprofileProfileControllerField', __DIR__.'/controllers/field.php');
JLoader::register('JoomprofileProfileViewHtmlField', __DIR__.'/views/field/html.php');
JLoader::register('JoomprofileProfileViewJsonField', __DIR__.'/views/field/json.php');
JLoader::register('JoomprofileProfileModelField', __DIR__.'/models/field.php');
JLoader::register('JoomprofileProfileTableField', __DIR__.'/tables/field.php');
JLoader::register('JoomprofileProfileLibField', __DIR__.'/libs/field.php');

JLoader::register('JoomprofileProfileControllerFieldgroup', __DIR__.'/controllers/fieldgroup.php');
JLoader::register('JoomprofileProfileViewHtmlFieldgroup', __DIR__.'/views/fieldgroup/html.php');
JLoader::register('JoomprofileProfileViewJsonFieldgroup', __DIR__.'/views/fieldgroup/json.php');
JLoader::register('JoomprofileProfileModelFieldgroup', __DIR__.'/models/fieldgroup.php');
JLoader::register('JoomprofileProfileTableFieldgroup', __DIR__.'/tables/fieldgroup.php');
JLoader::register('JoomprofileProfileLibFieldgroup', __DIR__.'/libs/fieldgroup.php');

JLoader::register('JoomprofileProfileModelFieldgroup_usergroups', __DIR__.'/models/fieldgroup_usergroups.php');
JLoader::register('JoomprofileProfileModelField_fieldgroups', __DIR__.'/models/field_fieldgroups.php');
JLoader::register('JoomprofileProfileTableField_fieldgroups', __DIR__.'/tables/field_fieldgroups.php');

JLoader::register('JoomprofileProfileModelField_values', __DIR__.'/models/field_values.php');
JLoader::register('JoomprofileProfileTableField_values', __DIR__.'/tables/field_values.php');

// user
JLoader::register('JoomprofileProfileControllerUser', __DIR__.'/controllers/user.php');
JLoader::register('JoomprofileProfileViewHtmlUser', __DIR__.'/views/user/html.php');
JLoader::register('JoomprofileProfileViewJsonUser', __DIR__.'/views/user/json.php');
JLoader::register('JoomprofileProfileModelUser', __DIR__.'/models/user.php');
JLoader::register('JoomprofileProfileTableUser', __DIR__.'/tables/user.php');
JLoader::register('JoomprofileProfileLibUser', __DIR__.'/libs/user.php');

// search
JLoader::register('JoomprofileProfileControllerSearch', __DIR__.'/controllers/search.php');
JLoader::register('JoomprofileProfileViewHtmlSearch', __DIR__.'/views/search/html.php');
JLoader::register('JoomprofileProfileViewJsonSearch', __DIR__.'/views/search/json.php');

// registration
JLoader::register('JoomprofileProfileModelRegistration', __DIR__.'/models/registration.php');
JLoader::register('JoomprofileProfileTableRegistration', __DIR__.'/tables/registration.php');

// helper
JLoader::register('JoomprofileProfileHelperRegistration', __DIR__.'/helpers/registration.php');
JLoader::register('JoomprofileProfileHelperField', __DIR__.'/helpers/field.php');

// event
JLoader::register('JoomprofileProfileEvent', __DIR__.'/event.php');

// Uergroup
JLoader::register('JoomprofileProfileControllerUsergroup', __DIR__.'/controllers/usergroup.php');
JLoader::register('JoomprofileProfileViewHtmlUsergroup', __DIR__.'/views/usergroup/html.php');
JLoader::register('JoomprofileProfileViewJsonUsergroup', __DIR__.'/views/usergroup/json.php');
JLoader::register('JoomprofileProfileModelUsergroup', __DIR__.'/models/usergroup.php');
JLoader::register('JoomprofileProfileTableUsergroup', __DIR__.'/tables/usergroup.php');
JLoader::register('JoomprofileProfileLibUsergroup', __DIR__.'/libs/usergroup.php');

// search 
JLoader::register('JoomprofileProfileModelUsergroup_searchfields', __DIR__.'/models/usergroup_searchfields.php');
JLoader::register('JoomprofileProfileTableUsergroup_searchfields', __DIR__.'/tables/usergroup_searchfields.php');

// register the event
$dispatcher = JDispatcher::getInstance();
$dispatcher->register('onUserAfterSave', 'JoomprofileProfileEvent');