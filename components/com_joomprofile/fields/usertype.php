<?php

/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JFormHelper::loadFieldClass('list');
class JoomprofileFormFieldUsertype extends JFormFieldList
{	
	protected $type = 'Usertype';
	protected function getOptions()
	{ 
		$options = array();
		
		$options = parent::getOptions();
		
		include_once JPATH_SITE.'/components/com_joomprofile/includes/autoload.php';

		$config_app  = JoomprofileExtension::get('config');
		$config      = $config_app->getConfig('profile');

		$allUsergroups 			= JoomprofileHelperJoomla::getUsergroups();
		$allowedUsergroups 		= $config['registration_allowed_jusergroups'];

		if(empty($allowedUsergroups)){
			return $options;
		}

		foreach ($allowedUsergroups as $id)
		{ 
			// Create a new option object based on the <option /> element.
			$options[] = JHtml::_(
				'select.option', (string) $id,
				trim(ucfirst((string) $allUsergroups[$id]->title)), 'value', 'text');
		}

		reset($options);

		return $options;
	}

} 
