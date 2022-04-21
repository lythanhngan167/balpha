<?php
/**
 * @version		1.3.3
 * @package		Joomla
 * @subpackage	EShop
 * @author  	Giang Dinh Truong
 * @copyright	Copyright (C) 2012 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
error_reporting(E_ALL);
// no direct access
defined( '_JEXEC' ) or die();
require_once (dirname(__FILE__).'/helper.php');
require_once JPATH_ROOT . '/administrator/components/com_eshop/libraries/defines.php';
require_once JPATH_ROOT . '/administrator/components/com_eshop/libraries/inflector.php';
require_once JPATH_ROOT . '/administrator/components/com_eshop/libraries/autoload.php';
$categories = modEshopAdvancedSearchHelper::getCategories($params->get('child_categories_level', 9999));
$manufacturers = modEshopAdvancedSearchHelper::getManufacturers();
$attributeGroups = modEshopAdvancedSearchHelper::getAttributeGroups();
$options = modEshopAdvancedSearchHelper::getOptions();
$template = JFactory::getApplication()->getTemplate();

//Get currency symbol
$currency = new EshopCurrency();
$currencyCode = $currency->getCurrencyCode();
$db = JFactory::getDbo();
$query = $db->getQuery(true);
$query->select('left_symbol, right_symbol')
	->from('#__eshop_currencies')
	->where('currency_code = ' . $db->quote($currencyCode));
$db->setQuery($query);
$row = $db->loadObject();
($row->left_symbol) ? $symbol = $row->left_symbol : $symbol = $row->right_symbol;

//Get weight unit
$weight = new EshopWeight();
$weightId = EshopHelper::getConfigValue('weight_id');
$weightUnit = $weight->getUnit($weightId);

//Get length unit
$length = new EshopLength();
$lengthId = EshopHelper::getConfigValue('length_id');
$lengthUnit = $length->getUnit($lengthId);

//Get submitted values
$minPrice = str_replace($symbol, '', htmlspecialchars(JRequest::getVar('min_price'), ENT_COMPAT, 'UTF-8'));
$maxPrice = str_replace($symbol, '', htmlspecialchars(JRequest::getVar('max_price'), ENT_COMPAT, 'UTF-8'));
$minWeight = str_replace($weightUnit, '', htmlspecialchars(JRequest::getVar('min_weight'), ENT_COMPAT, 'UTF-8'));
$maxWeight = str_replace($weightUnit, '', htmlspecialchars(JRequest::getVar('max_weight'), ENT_COMPAT, 'UTF-8'));
$minLength = str_replace($lengthUnit, '', htmlspecialchars(JRequest::getVar('min_length'), ENT_COMPAT, 'UTF-8'));
$maxLength = str_replace($lengthUnit, '', htmlspecialchars(JRequest::getVar('max_length'), ENT_COMPAT, 'UTF-8'));
$minWidth = str_replace($lengthUnit, '', htmlspecialchars(JRequest::getVar('min_width'), ENT_COMPAT, 'UTF-8'));
$maxWidth = str_replace($lengthUnit, '', htmlspecialchars(JRequest::getVar('max_width'), ENT_COMPAT, 'UTF-8'));
$minHeight = str_replace($lengthUnit, '', htmlspecialchars(JRequest::getVar('min_height'), ENT_COMPAT, 'UTF-8'));
$maxHeight = str_replace($lengthUnit, '', htmlspecialchars(JRequest::getVar('max_height'), ENT_COMPAT, 'UTF-8'));
$productInStock = JRequest::getVar('product_in_stock');
$categoryIds = JRequest::getVar('category_ids');
if (!$categoryIds)
{
	$categoryIds = array();
}
else 
{
	$categoryIds = explode(',', $categoryIds);
}
$manufacturerIds = JRequest::getVar('manufacturer_ids');
if (!$manufacturerIds)
{
	$manufacturerIds = array();
}
else 
{
	$manufacturerIds = explode(',', $manufacturerIds);
}
$attributeIds = JRequest::getVar('attribute_ids');
if (!$attributeIds)
{
	$attributeIds = array();
}
else 
{
	$attributeIds = explode(',', $attributeIds);
}
$optionValueIds = JRequest::getVar('optionvalue_ids');
if (!$optionValueIds)
{
	$optionValueIds = array();
}
else 
{
	$optionValueIds = explode(',', $optionValueIds);
}
$keyword = JRequest::getString('keyword');
if (!empty($keyword))
{
	$keyword = htmlspecialchars($keyword, ENT_COMPAT, 'UTF-8');
}
$itemId = $params->get('item_id');
if (!$itemId)
{
	$itemId = EshopRoute::getDefaultItemId();
}
// Load Bootstrap CSS and JS
if (EshopHelper::getConfigValue('load_bootstrap_css'))
{
	EshopHelper::loadBootstrapCss();
}
if (EshopHelper::getConfigValue('load_bootstrap_js'))
{
	EshopHelper::loadBootstrapJs();
}
$document = JFactory::getDocument();
$document->addScript(EshopHelper::getSiteUrl().'components/com_eshop/assets/js/noconflict.js');
if (JFile::exists(JPATH_ROOT.'/templates/'. $template .  '/css/'  . $module->module . '.css'))
{
	$document->addStyleSheet(JURI::base().'templates/' . $template . '/css/' . $module->module . '.css');
}
else 
{
	$document->addStyleSheet(JURI::base().'modules/' . $module->module . '/assets/css/style.css');
}
$document->addStyleSheet(EshopHelper::getSiteUrl().'modules/mod_eshop_advanced_search/assets/css/jquery.nouislider.css');
$document->addScript(EshopHelper::getSiteUrl().'modules/mod_eshop_advanced_search/assets/js/jquery.nouislider.min.js');

require(JModuleHelper::getLayoutPath('mod_eshop_advanced_search'));
