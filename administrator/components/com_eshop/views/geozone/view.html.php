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
class EShopViewGeozone extends EShopViewForm
{
	function _buildListArray(&$lists, $item)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('id, country_name AS name')
			->from('#__eshop_countries')
			->where('published=1')
			->order('country_name');
		$db->setQuery($query);
		$countryOptions = $db->loadObjectList();
		$query->clear();
		$query->select('zone_id,country_id')
			->from('#__eshop_geozonezones')
			->where('geozone_id = ' . (int) $item->id)
			->where('zone_id != 0');
		$db->setQuery($query);
		$zoneToGeozones = $db->loadObjectList();
		$config = EshopHelper::getConfig();
		JFactory::getDocument()->addScript(JURI::root() . 'administrator/components/com_eshop/assets/js/eshop.js')
			->addScriptDeclaration(EshopHtmlHelper::getZonesArrayJs())
			->addScriptDeclaration(EshopHtmlHelper::getCountriesOptionsJs());
		$this->countryId = $config->country_id;
		$this->countryOptions = $countryOptions;
		$this->zoneToGeozones = $zoneToGeozones;
		
		$lists['include_countries']	= EshopHtmlHelper::getBooleanInput('include_countries', 1);
		$query->clear();
		$query = $db->getQuery(true);
		$query->select('a.id AS value, a.country_name AS text')
				->from('#__eshop_countries AS a')
				->where('published = 1')
				->order('a.country_name');
		$db->setQuery($query);
		$options = array();
		$options[] = JHTML::_( 'select.option', '-1', '-- ' . JText::_('ESHOP_ALL_COUNTRIES') . ' --');
		$options = array_merge($options, $db->loadObjectList());
		$query->clear();
		$query->select('DISTINCT a.country_id')
				->from('#__eshop_geozonezones AS a')
				->where('zone_id = 0 and geozone_id = '. (int) $item->id);
		$db->setQuery($query);
		$selectedItems = $db->loadColumn();
		$lists['countries_list'] = JHtml::_('select.genericlist', $options, 'countries_list[]',
			array(
				'option.text.toHtml' => false,
				'option.text' => 'text',
				'option.value' => 'value',
				'list.attr' => ' class="inputbox chosen" multiple ',
				'list.select' => $selectedItems));
		JFactory::getDocument()->addScriptDeclaration(EshopHtmlHelper::getTaxrateOptionsJs())->addScriptDeclaration(
				EshopHtmlHelper::getBaseonOptionsJs());
		EshopHelper::chosen();
		
		//Geozone postcodes
		$query->clear()
			->select('*')
			->from('#__eshop_geozonepostcodes')
			->where('geozone_id = ' . intval($item->id));
		$db->setQuery($query);
		$this->geozonePostcodes = $db->loadObjectList();
	}
}