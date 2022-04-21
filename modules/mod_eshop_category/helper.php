<?php
/**
 * @version		1.3.1
 * @package		Joomla
 * @subpackage	EShop
 * @author  	Giang Dinh Truong
 * @copyright	Copyright (C) 2012 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined( '_JEXEC' ) or die();

abstract class modEshopCategoryHelper
{
	/**
	 * 
	 * Function to get Categories
	 * @return categories list
	 */
	public static function getCategories()
	{
		$categories = EshopHelper::getCategories(0, JFactory::getLanguage()->getTag(), true);
		for ($i = 0; $n = count($categories), $i < $n; $i++)
		{
			$categories[$i]->childCategories = EshopHelper::getCategories($categories[$i]->id, JFactory::getLanguage()->getTag(), true);
		}
		return $categories;
	}
	
	/**
	 * 
	 * Function to get id of parent category
	 * @param int $categoryId
	 * @return int id of parent category
	 */
	public static function getParentCategoryId($categoryId)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query = $query->select('category_parent_id')
			->from('#__eshop_categories')
			->where('id = ' . $categoryId);
		$db->setQuery($query);
		return $db->loadResult() ? $db->loadResult() : $categoryId; 
	}
}
