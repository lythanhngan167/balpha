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
class JoomprofileFormFieldArticle extends JFormFieldList
{	
	protected $type = 'Article';
	protected function getOptions()
	{
		$options = array();
		
		$options = parent::getOptions();
		
		$articles	= self::getList();		
		
		foreach ($articles as $id => $article)
		{
			// Create a new option object based on the <option /> element.
			$options[] = JHtml::_(
				'select.option', (string) $id,
				trim(ucfirst((string) $article->title)), 'value', 'text');
		}

		reset($options);

		return $options;
	}

	private function getList()
	{
		$db 	= JFactory::getDBO();
		$query 	= $db->getQuery();
		$sql 	= 'SELECT id, title FROM `#__content` WHERE `state` = 1 ORDER BY `title`';
		$db->setQuery($sql);
		return $db->loadObjectList('id');
	}
} 