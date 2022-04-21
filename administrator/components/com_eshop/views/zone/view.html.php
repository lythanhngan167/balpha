<?php
/**
 * @version		3.1.0
 * @package		Joomla
 * @subpackage	EShop
 * @author  	Giang Dinh Truong
 * @copyright	Copyright (C) 2012 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die();

/**
 * HTML View class for EShop component
 *
 * @static
 * @package		Joomla
 * @subpackage	EShop
 * @since 1.5
 */
class EshopViewZone extends EShopViewForm
{

	function _buildListArray(&$lists, $item)
	{
		$db = JFactory::getDbo();
		//Build countries list
		$query = $db->getQuery(true);
		$query->select('id AS value, country_name AS text')
			->from('#__eshop_countries')
			->where('published = 1')
			->order('country_name');
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		$options = array();
		$options[] = JHtml::_('select.option', '', JText::_('ESHOP_NONE'), 'value', 'text');
		if (count($rows))
		{
			$options = array_merge($options, $rows);
		}
		$lists['countries'] = JHtml::_('select.genericlist', $options, 'country_id', 
			array(
				'option.text.toHtml' => false, 
				'option.value' => 'value', 
				'option.text' => 'text', 
				'list.attr' => ' class="inputbox" ', 
				'list.select' => $item->country_id));
		return true;
	}
}