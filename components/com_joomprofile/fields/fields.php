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
class JoomprofileFormFieldFields extends JFormFieldList
{	
	protected $type = 'Fields';
	protected function getOptions()
	{ 
		$options = array();
		
		$options = parent::getOptions();
		
		include_once JPATH_SITE.'/components/com_joomprofile/includes/autoload.php';

		$config_app  = JoomprofileExtension::get('config');
		$config      = $config_app->getConfig('profile');

		$fields 	 = JoomprofileProfileHelper::getFields();

		$fieldtypes = $this->getAttribute('fieldtypes');
		if (!empty(fieldtypes)) {
			$fieldtypes = explode(',', $fieldtypes)	;
		} else {
			$fieldtypes = [];
		}
	
		foreach ($fields as $field) { 
			if (empty($fieldtypes) || in_array($field->type, $fieldtypes)) {
				// Create a new option object based on the <option /> element.
				$options[] = JHtml::_(
					'select.option', (string) $field->id,
					$field->title, 'value', 'text');
			}
		}

		reset($options);

		return $options;
	}

} 
