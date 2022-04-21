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
class EshopViewTaxrates extends EShopViewList
{
	function _buildListArray(&$lists, $state)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('id, geozone_name')
			->from('#__eshop_geozones')
			->where('published=1')
			->order('geozone_name');
		$db->setQuery($query);
		$options = array();
		$options[] = JHtml::_('select.option', 0, JText::_('ESHOP_GEOZONE'), 'id', 'geozone_name');
		$options = array_merge($options, $db->loadObjectList());
		$lists['geozone_id'] = JHtml::_('select.genericlist', $options, 'geozone_id', ' class="inputbox" onchange="submit();" ', 'id', 'geozone_name', 
			$state->geozone_id);
		return true;
	}
}