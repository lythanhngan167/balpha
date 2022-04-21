<?php
/**
 * @version		1.3.1
 * @package		Joomla
 * @subpackage	EShop
 * @author  	Giang Dinh Truong
 * @copyright	Copyright (C) 2011 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die;

abstract class modEshopCurrencyHelper
{
	/**
	 * 
	 * Function to get Currencies
	 * @return currencies list
	 */
	public static function getCurrencies()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')
			->from('#__eshop_currencies')
			->where('published = 1');
		$db->setQuery($query);
		return $db->loadObjectList();
	}
}