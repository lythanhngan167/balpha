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
class EshopViewTaxrate extends EShopViewForm
{
	function _buildListArray(&$lists, $item)
	{
		$db = JFactory::getDbo();
		EshopHelper::chosen();
		$options = array();
		$options[] = JHtml::_('select.option', 'P', JText::_('ESHOP_PERCENTAGE'));
		$options[] = JHtml::_('select.option', 'F', JText::_('ESHOP_FIXED_AMOUNT'));
		$lists['tax_type'] = JHtml::_('select.genericlist', $options, 'tax_type', ' class="inputbox" ', 'value', 'text', $item->tax_type);
		//get list geozone
		$query = $db->getQuery(true);
		$query->select('id, geozone_name')
			->from('#__eshop_geozones')
			->where('published = 1')
			->order('geozone_name');
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		$lists['geozone_id'] = JHtml::_('select.genericlist', $rows, 'geozone_id', ' class="inputbox" ', 'id', 'geozone_name', $item->geozone_id);
		//get list customer group
		$query->clear();
		$query->select('a.id AS value, b.customergroup_name AS text')
			->from('#__eshop_customergroups AS a')
			->innerJoin('#__eshop_customergroupdetails AS b ON (a.id = b.customergroup_id)')
			->where('a.published = 1')
			->where('b.language = "' . JComponentHelper::getParams('com_languages')->get('site', 'en-GB') . '"')
			->order('b.customergroup_name');
		$db->setQuery($query);
		$options = $db->loadObjectList();
		if ($item->id)
		{
			$query->clear();
			$query->select('customergroup_id')
				->from('#__eshop_taxcustomergroups')
				->where('tax_id = ' . intval($item->id));
			$db->setQuery($query);
			$selectedItems = $db->loadColumn();
		}
		else
		{
			$selectedItems = array();
		}
		$lists['customergroup_id'] = JHtml::_('select.genericlist', $options, 'customergroup_id[]',
			array(
				'option.text.toHtml' => false, 
				'option.text' => 'text', 
				'option.value' => 'value', 
				'list.attr' => ' class="inputbox chosen" multiple ', 
				'list.select' => $selectedItems));
		
		return true;
	}
}