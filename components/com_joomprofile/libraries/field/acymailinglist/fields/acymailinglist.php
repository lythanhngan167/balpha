<?php

/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
if(!include_once(rtrim(JPATH_ADMINISTRATOR,DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_acymailing'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'helper.php')){
    echo 'This module can not work without the AcyMailing Component';
}

JFormHelper::loadFieldClass('list');
class JoomprofileFormFieldAcymailinglist extends JFormFieldList
{	
	protected $type = 'acymailinglist';
	protected function getOptions()
	{
		$options = array();
		$options = parent::getOptions();
		
		$listType = acymailing_get('type.lists');
		$lists = $listType->getValues();
		
		foreach($lists as $list)
		{
			// Create a new option object based on the <option /> element.
			$options[] = JHtml::_(
				'select.option', (string) $list->value,
				trim((string) $list->text), 'value', 'text');
		}

		reset($options);

		return $options;
	}
} 