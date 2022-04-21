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
class EShopViewAttribute extends EShopViewForm
{

	function _buildListArray(&$lists, $item)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('a.id AS value, b.attributegroup_name  AS text')
			->from('#__eshop_attributegroups AS a')
			->innerJoin('#__eshop_attributegroupdetails AS b ON (a.id = b.attributegroup_id)')
			->where('a.published = 1')
			->where('b.language = "' . JComponentHelper::getParams('com_languages')->get('site', 'en-GB') . '"');
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		$options = array();
		$options[] = JHtml::_('select.option', 0, JText::_('ESHOP_NONE'), 'value', 'text');
		if (count($rows))
		{
			$options = array_merge($options, $rows);
		}
		$lists['attributegroups'] = JHtml::_('select.genericlist', $options, 'attributegroup_id', 
			array(
				'option.text.toHtml' => false, 
				'option.text' => 'text', 
				'option.value' => 'value', 
				'list.attr' => ' class="inputbox" ', 
				'list.select' => $item->attributegroup_id));
	}
}