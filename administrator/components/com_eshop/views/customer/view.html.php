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
class EshopViewCustomer extends EShopViewForm
{
	function _buildListArray(&$lists, $item)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('a.id AS value, b.customergroup_name AS text')
			->from('#__eshop_customergroups AS a')
			->innerJoin('#__eshop_customergroupdetails AS b ON (a.id = b.customergroup_id)')
			->where('a.published = 1')
			->where('b.language = "' . JComponentHelper::getParams('com_languages')->get('site', 'en-GB') . '"')
			->order('b.customergroup_name');
		$db->setQuery($query);
		$lists['customergroup_id'] = JHtml::_('select.genericlist', $db->loadObjectList(), 'customergroup_id',
			array(
				'option.text.toHtml' => false,
				'option.text' => 'text',
				'option.value' => 'value',
				'list.attr' => ' class="inputbox chosen" ',
				'list.select' => $item->customergroup_id));
		// Prepare addresses data
		$query->clear();
		$query->select('*')
			->from('#__eshop_addresses')
			->where('customer_id = ' . intval($item->customer_id));
		$db->setQuery($query);
		$addresses = $db->loadObjectList();
		for ($i = 0; $n = count($addresses), $i < $n; $i++)
		{
			$lists['country_id_' . $addresses[$i]->id] = JHtml::_('select.genericlist', $this->_getCountryOptions(), 'address_country_id_' . ($i + 1), ' class="inputbox" onchange="country(this, \'' . ($i + 1) . '\', \'' . $addresses[$i]->zone_id . '\')" ', 'id', 'name', $addresses[$i]->country_id);
			$lists['zone_id_' . $addresses[$i]->id] = JHtml::_('select.genericlist', $this->_getZoneList($addresses[$i]->country_id), 'address_zone_id_' . ($i + 1), ' class="inputbox" ', 'id', 'zone_name', $addresses[$i]->zone_id);
		}
		$lists['country_id'] = JHtml::_('select.genericlist', $this->_getCountryOptions(), 'country_id[]', ' class="inputbox" style="display: none;" ', 'id', 'name', 0);
		$lists['zone_id'] = JHtml::_('select.genericlist', $this->_getZoneList(), 'zone_id[]', ' class="inputbox" style="display: none;" ', 'id', 'zone_name', 0);
		$this->addresses = $addresses;
		return true;
	}
	
	/**
	 *
	 * Private method to get Country Options
	 * @param array $lists
	 */
	function _getCountryOptions()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('id, country_name AS name')
			->from('#__eshop_countries')
			->where('published = 1')
			->order('country_name');
		$db->setQuery($query);
		$options = array();
		$options[] = JHtml::_('select.option', 0, JText::_('ESHOP_PLEASE_SELECT'), 'id', 'name');
		$options = array_merge($options, $db->loadObjectList());
		return $options;
	}
	
	/**
	 *
	 * Private method to get Zone Options
	 * @param array $lists
	 */
	function _getZoneList($countryId = '')
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('id, zone_name')
			->from('#__eshop_zones')
			->where('country_id = ' . (int) $countryId)
			->where('published = 1');
		$db->setQuery($query);
		$options = array();
		$options[] = JHtml::_('select.option', 0, JText::_('ESHOP_PLEASE_SELECT'), 'id', 'zone_name');
		$options = array_merge($options, $db->loadObjectList());
		return $options;
	}
}