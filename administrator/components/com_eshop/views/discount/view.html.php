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
class EShopViewDiscount extends EShopViewForm
{
	function _buildListArray(&$lists, $item)
	{
		$options = array();
		$options[] = JHtml::_('select.option', 'P', JText::_('ESHOP_PERCENTAGE'));
		$options[] = JHtml::_('select.option', 'F', JText::_('ESHOP_FIXED_AMOUNT'));
		$lists['discount_type'] = JHtml::_('select.genericlist', $options, 'discount_type', ' class="inputbox" ', 'value', 'text', $item->discount_type);
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		//Build customer groups list
		$query->clear();
		$query->select('a.id AS value, b.customergroup_name AS text')
			->from('#__eshop_customergroups AS a')
			->innerJoin('#__eshop_customergroupdetails AS b ON (a.id = b.customergroup_id)')
			->where('a.published = 1')
			->where('b.language = "' . JComponentHelper::getParams('com_languages')->get('site', 'en-GB') . '"')
			->order('b.customergroup_name');
		$db->setQuery($query);
		$options = $db->loadObjectList();
		if ($item->discount_customergroups != '')
		{
			$selectedItems = explode(',', $item->discount_customergroups);
		}
		else
		{
			$selectedItems = array();
		}
		$lists['discount_customergroups'] = JHtml::_('select.genericlist', $options, 'discount_customergroups[]',
			array(
				'option.text.toHtml' => false,
				'option.text' => 'text',
				'option.value' => 'value',
				'list.attr' => ' class="inputbox chosen" multiple ',
				'list.select' => $selectedItems));
		//Get multiple products
		$query->clear();
		$query->select('a.id AS value, b.product_name AS text')
			->from('#__eshop_products AS a')
			->innerJoin('#__eshop_productdetails AS b ON (a.id = b.product_id)')
			->where('a.published = 1')
			->where('b.language = "' . JComponentHelper::getParams('com_languages')->get('site', 'en-GB') . '"')
			->order('b.product_name');
		$db->setQuery($query);
		$products = $db->loadObjectList();
		$options = array();
		$options[] = JHtml::_('select.option', 0, JText::_('ESHOP_ALL_PRODUCTS'), 'value', 'text');
		$products = array_merge($options, $products);
		$query->clear();
		$query->select('element_id')
			->from('#__eshop_discountelements')
			->where('discount_id = ' . intval($item->id))
			->where('element_type = "product"');
		$db->setQuery($query);
		$productIds = $db->loadObjectList();
		$productArr = array();
		for ($i = 0; $i < count($productIds); $i++)
		{
			$productArr[] = $productIds[$i]->element_id;
		}
		$lists['products'] = JHtml::_('select.genericlist', $products, 'product_id[]', ' class="inputbox chosen" multiple ', 'value', 'text', $productArr);
		//Get multiple manufacturers
		$query = $db->getQuery(true);
		$query->select('a.id AS value, b.manufacturer_name AS text')
			->from('#__eshop_manufacturers AS a')
			->innerJoin('#__eshop_manufacturerdetails AS b ON (a.id = b.manufacturer_id)')
			->where('a.published = 1')
			->where('b.language = "' . JComponentHelper::getParams('com_languages')->get('site', 'en-GB') . '"')
			->order('b.manufacturer_name');
		$db->setQuery($query);
		$manufacturers = $db->loadObjectList();
		$options = array();
		$options[] = JHtml::_('select.option', 0, JText::_('ESHOP_ALL_MANUFACTURERS'), 'value', 'text');
		$manufacturers = array_merge($options, $manufacturers);
		$query->clear();
		$query->select('element_id')
			->from('#__eshop_discountelements')
			->where('discount_id = ' . intval($item->id))
			->where('element_type = "manufacturer"');
		$db->setQuery($query);
		$manufacturerIds = $db->loadObjectList();
		$manufacturerArr = array();
		for ($i = 0; $i < count($manufacturerIds); $i++)
		{
			$manufacturerArr[] = $manufacturerIds[$i]->element_id;
		}
		$lists['manufacturers'] = JHtml::_('select.genericlist', $manufacturers, 'manufacturer_id[]', ' class="inputbox chosen" multiple ', 'value', 'text', $manufacturerArr);
		//Get multiple categories
		//Build categories list
		$query->clear();
		$query->select('a.id, b.category_name AS title, a.category_parent_id AS parent_id')
			->from('#__eshop_categories AS a')
			->innerJoin('#__eshop_categorydetails AS b ON (a.id = b.category_id)')
			->where('a.published = 1')
			->where('b.language = "' . JComponentHelper::getParams('com_languages')->get('site', 'en-GB') . '"');
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		$children = array();
		if ($rows)
		{
			// first pass - collect children
			foreach ($rows as $v)
			{
				$pt = $v->parent_id;
				$list = @$children[$pt] ? $children[$pt] : array();
				array_push($list, $v);
				$children[$pt] = $list;
			}
		}
		$list = JHtml::_('menu.treerecurse', 0, '', array(), $children, 9999, 0, 0);
		$options = array();
		foreach ($list as $listItem)
		{
			$options[] = JHtml::_('select.option', $listItem->id, $listItem->treename);
		}
		$options[] = JHtml::_('select.option', 0, JText::_('ESHOP_ALL_CATEGORIES'), 'value', 'text');
		$query->clear();
		$query->select('element_id')
			->from('#__eshop_discountelements')
			->where('discount_id = ' . intval($item->id))
			->where('element_type = "category"');
		$db->setQuery($query);
		$categoryIds = $db->loadObjectList();
		$categoryArr = array();
		for ($i = 0; $i < count($categoryIds); $i++)
		{
			$categoryArr[] = $categoryIds[$i]->element_id;
		}
		$lists['categories'] = JHtml::_('select.genericlist', $options, 'category_id[]',
			array(
				'option.text.toHtml' => false,
				'option.text' => 'text',
				'option.value' => 'value',
				'list.attr' => ' class="inputbox chosen" multiple ',
				'list.select' => $categoryArr));
		$nullDate = $db->getNullDate();
		$this->nullDate = $nullDate;
	}
}