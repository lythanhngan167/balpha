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
class EShopViewLabel extends EShopViewForm
{
	function _buildListArray(&$lists, $item)
	{
		$document = JFactory::getDocument();
		$document->addScript(JURI::base() . 'components/com_eshop/assets/colorpicker/jscolor.js');
		$db = JFactory::getDbo();
		//Label style
		$options = array();
		$options[] = JHtml::_('select.option', 'rotated', JText::_('ESHOP_LABEL_STYLE_ROTATED'));
		$options[] = JHtml::_('select.option', 'round', JText::_('ESHOP_LABEL_STYLE_ROUND'));
		$options[] = JHtml::_('select.option', 'horizontal', JText::_('ESHOP_LABEL_STYLE_HORIZONTAL'));
		$lists['label_style'] = JHtml::_('select.genericlist', $options, 'label_style', ' class="inputbox" ', 'value', 'text', $item->label_style);
		//Label position
		$options = array();
		$options[] = JHtml::_('select.option', 'top_left', JText::_('ESHOP_LABEL_POSITION_TOP_LEFT'));
		$options[] = JHtml::_('select.option', 'top_right', JText::_('ESHOP_LABEL_POSITION_TOP_RIGHT'));
		$options[] = JHtml::_('select.option', 'bottom_left', JText::_('ESHOP_LABEL_POSITION_BOTTOM_LEFT'));
		$options[] = JHtml::_('select.option', 'bottom_right', JText::_('ESHOP_LABEL_POSITION_BOTTOM_RIGHT'));
		$lists['label_position'] = JHtml::_('select.genericlist', $options, 'label_position', ' class="inputbox" ', 'value', 'text', $item->label_position);
		//Label bold
		$lists['label_bold']	= EshopHtmlHelper::getBooleanInput('label_bold', $item->label_bold);
		//Label opacity
		$options = array();
		$options[] = JHtml::_('select.option', '1', '100%');
		$options[] = JHtml::_('select.option', '0.9', '90%');
		$options[] = JHtml::_('select.option', '0.8', '80%');
		$options[] = JHtml::_('select.option', '0.7', '70%');
		$options[] = JHtml::_('select.option', '0.6', '60%');
		$options[] = JHtml::_('select.option', '0.5', '50%');
		$options[] = JHtml::_('select.option', '0.4', '40%');
		$options[] = JHtml::_('select.option', '0.3', '30%');
		$options[] = JHtml::_('select.option', '0.2', '20%');
		$options[] = JHtml::_('select.option', '0.1', '10%');
		$lists['label_opacity'] = JHtml::_('select.genericlist', $options, 'label_opacity', ' class="inputbox" ', 'value', 'text', $item->label_opacity);
		$lists['enable_image']	= EshopHtmlHelper::getBooleanInput('enable_image', $item->enable_image);
		//Get multiple products
		$query = $db->getQuery(true);
		$query->select('a.id AS value, b.product_name AS text')
			->from('#__eshop_products AS a')
			->innerJoin('#__eshop_productdetails AS b ON (a.id = b.product_id)')
			->where('a.published = 1')
			->where('b.language = "' . JComponentHelper::getParams('com_languages')->get('site', 'en-GB') . '"')
			->order('b.product_name');
		$db->setQuery($query);
		$products = $db->loadObjectList();
		$query->clear();
		$query->select('element_id')
			->from('#__eshop_labelelements')
			->where('label_id = ' . intval($item->id))
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
		$query->clear();
		$query->select('element_id')
			->from('#__eshop_labelelements')
			->where('label_id = ' . intval($item->id))
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
		$query->clear();
		$query->select('element_id')
			->from('#__eshop_labelelements')
			->where('label_id = ' . intval($item->id))
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