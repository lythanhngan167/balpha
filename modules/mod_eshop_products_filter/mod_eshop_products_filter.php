<?php
/**
 * @version        2.5.0
 * @package        Joomla
 * @subpackage     EShop
 * @author         Giang Dinh Truong
 * @copyright      Copyright (C) 2012 - 2016 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */

// no direct access
defined('_JEXEC') or die;

$input = JFactory::getApplication()->input;

$option = $input->getCmd('option');
$view   = $input->getCmd('view');

if ($option != 'com_eshop')
{
	return;
}

require_once JPATH_ROOT . '/administrator/components/com_eshop/libraries/defines.php';
require_once JPATH_ROOT . '/administrator/components/com_eshop/libraries/inflector.php';
require_once JPATH_ROOT . '/administrator/components/com_eshop/libraries/autoload.php';
require_once JPATH_ROOT . '/components/com_eshop/helpers/filter.php';

$keyword = $input->getString('keyword');

if (!empty($keyword))
{
	$keyword = htmlspecialchars($keyword, ENT_COMPAT, 'UTF-8');
}

$filterData = EshopFilter::getFilterData();

$categories = EshopFilter::getCategories($filterData);

if ($params->get('filter_by_manufacturers', 1))
{
	$manufacturers = EshopFilter::getManufacturers($filterData);
}

if ($params->get('filter_by_attributes', 1))
{
	$attributes = EshopFilter::getAttributes($filterData, true);
}

if ($params->get('filter_by_options', 1))
{
	$options = EshopFilter::getOptions($filterData, true);
}

//Get currency symbol
$currency     = new EshopCurrency();
$currencyCode = $currency->getCurrencyCode();
$db           = JFactory::getDbo();
$query        = $db->getQuery(true);
$query->select('left_symbol, right_symbol')
	->from('#__eshop_currencies')
	->where('currency_code = ' . $db->quote($currencyCode));
$db->setQuery($query);
$row = $db->loadObject();
($row->left_symbol) ? $symbol = $row->left_symbol : $symbol = $row->right_symbol;

if (!empty($filterData['category_id']))
{
	$query->clear()
		->select(' a.id, a.category_parent_id, b.category_name')
		->from('#__eshop_categories AS a')
		->innerJoin('#__eshop_categorydetails AS b ON a.id = b.category_id')
		->where('a.id = ' . $filterData['category_id']);

	if (JLanguageMultilang::isEnabled())
	{
		$query->where('b.language = ' . $db->quote(JFactory::getLanguage()->getTag()));
	}

	$db->setQuery($query);
	$category = $db->loadObject();
}

//Get weight unit
$weight     = new EshopWeight();
$weightId   = EshopHelper::getConfigValue('weight_id');
$weightUnit = $weight->getUnit($weightId);

//Get length unit
$length     = new EshopLength();
$lengthId   = EshopHelper::getConfigValue('length_id');
$lengthUnit = $length->getUnit($lengthId);

$itemId = $params->get('item_id');

if ($params->get('filter_by_price', 1) && $params->get('max_price') > 0)
{
	$filterByPrice = true;
}
else 
{
	$filterByPrice = false;
}

if ($params->get('filter_by_weight', 1) && $params->get('max_weight') > 0)
{
	$filterByWeight = true;
}
else
{
	$filterByWeight = false;
}

if ($params->get('filter_by_weight', 1) && $params->get('max_weight') > 0)
{
	$filterByWeight = true;
}
else
{
	$filterByWeight = false;
}

if ($params->get('filter_by_length', 1) && $params->get('max_length') > 0)
{
	$filterByLength = true;
}
else
{
	$filterByLength = false;
}

if ($params->get('filter_by_width', 1) && $params->get('max_width') > 0)
{
	$filterByWidth = true;
}
else
{
	$filterByWidth = false;
}

if ($params->get('filter_by_height', 1) && $params->get('max_height') > 0)
{
	$filterByHeight = true;
}
else
{
	$filterByHeight = false;
}

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
$template = JFactory::getApplication()->getTemplate();
$baseUri  = JUri::base(true);


$document->addScript(EshopHelper::getSiteUrl() . 'components/com_eshop/assets/js/noconflict.js');

if (JFile::exists(JPATH_ROOT . '/templates/' . $template . '/css/' . $module->module . '.css'))
{
	$document->addStyleSheet($baseUri . '/templates/' . $template . '/css/' . $module->module . '.css');
}
else
{
	$document->addStyleSheet($baseUri . '/modules/' . $module->module . '/assets/css/style.css');
}

$document->addStyleSheet($baseUri . '/modules/mod_eshop_products_filter/assets/css/jquery.nouislider.css');
$document->addScript($baseUri . '/modules/mod_eshop_products_filter/assets/js/jquery.nouislider.min.js');

require JModuleHelper::getLayoutPath('mod_eshop_products_filter');
