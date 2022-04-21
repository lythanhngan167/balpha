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
class JoomprofileFormFieldFieldgroups extends JFormFieldList
{	
	protected $type = 'Fieldgroups';
	protected function getOptions()
	{
		$options = array();
		$options = parent::getOptions();
		$app 	 =  JoomprofileExtension::get('profile');
		$model 	 = $app->getModel('fieldgroup');
		$query 	 = $model->getQuery();
		$query->where('`published` = 1');
		$groups  = $model->getList($query);
			
		foreach ($groups as $group)
		{
			// Create a new option object based on the <option /> element.
			$options[] = JHtml::_(
				'select.option', (string) $group->id,
				trim((string) $group->title), 'value', 'text');
		}

		reset($options);

		return $options;
	}
} 