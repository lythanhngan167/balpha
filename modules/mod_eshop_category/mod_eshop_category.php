<?php
/**
 * @version		1.3.1
 * @package		Joomla
 * @subpackage	EShop
 * @author  	Giang Dinh Truong
 * @copyright	Copyright (C) 2012 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined( '_JEXEC' ) or die();

// Include the helper functions only once
require_once JPATH_ROOT.'/components/com_eshop/helpers/helper.php';
require_once dirname(__FILE__).'/helper.php';
require_once JPATH_ROOT . '/administrator/components/com_eshop/libraries/autoload.php';
$categories = modEshopCategoryHelper::getCategories();
$document = JFactory::getDocument();
$template = JFactory::getApplication()->getTemplate();

if (is_file(JPATH_SITE .  '/templates/'. $template .  '/css/'  . $module->module . '.css')) {
	$document->addStyleSheet(JURI::base().'templates/' . $template . '/css/' . $module->module . '.css');
} else {
	$document->addStyleSheet(JURI::base().'modules/' . $module->module . '/css/style.css');
}
$showChildren = $params->get('show_children');
$showNumberProducts = $params->get('show_number_products') && EshopHelper::getConfigValue('product_count');
if (JRequest::getVar('view') == 'category')
{
	$categoryId = JRequest::getVar('id');
}
else 
{
	$categoryId = 0;
}
if ($categoryId == 0)
{
	$parentCategoryId = 0;
	$childCategoryId = 0;
}
else
{
	$parentCategoryId = modEshopCategoryHelper::getParentCategoryId($categoryId);
	if ($parentCategoryId == $categoryId)
	{
		$childCategoryId = 0;
	}
	else 
	{
		$childCategoryId = $categoryId;
	}
}
require JModuleHelper::getLayoutPath('mod_eshop_category', $params->get('layout', 'default'));
