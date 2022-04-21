<?php
/**
 * @version		1.3.1
 * @package		Joomla
 * @subpackage	EShop
 * @author  	Giang Dinh Truong
 * @copyright	Copyright (C) 2011 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die;

require_once (dirname(__FILE__).'/helper.php');
require_once JPATH_ROOT . '/administrator/components/com_eshop/libraries/defines.php';
require_once JPATH_ROOT . '/administrator/components/com_eshop/libraries/inflector.php';
require_once JPATH_ROOT . '/administrator/components/com_eshop/libraries/autoload.php';
$thumbnailWidth 	= $params->get('image_width', 140);
$thumbnailHeight	= $params->get('image_height', 90);
$manufacturersTotal = $params->get('manufacturers_total', 8);
$manufacturersShow 	= $params->get('number_manufacturer_show', 6);
$slideWidth = $params->get('slide_width', 680);
$items = modEshopManufacturerHelper::getItems($params);
$document = JFactory::getDocument();
$template = JFactory::getApplication()->getTemplate();

//Resize manufacturer images
for ($i = 0; $n = count($items), $i < $n; $i++)
{
	$item = $items[$i];
	// Image
	if ($item->manufacturer_image && JFile::exists(JPATH_ROOT.'/media/com_eshop/manufacturers/' . $item->manufacturer_image))
	{
		$image = EshopHelper::resizeImage($item->manufacturer_image, JPATH_ROOT . '/media/com_eshop/manufacturers/', $thumbnailWidth, $thumbnailHeight);
	}
	else
	{
		$image = EshopHelper::resizeImage('no-image.png', JPATH_ROOT . '/media/com_eshop/manufacturers/', $thumbnailWidth, $thumbnailHeight);
	}
	$items[$i]->image = JURI::base() . 'media/com_eshop/manufacturers/resized/' . $image;
}

if (JFile::exists(JPATH_ROOT.'/templates/'. $template .  '/css/'  . $module->module . '.css'))
{
	$document->addStyleSheet(JURI::base().'templates/' . $template . '/css/' . $module->module . '.css');
}
else 
{
	$document->addStyleSheet(JURI::base().'modules/' . $module->module . '/admin/style.css');
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
JHtml::_('script', EshopHelper::getSiteUrl(). 'components/com_eshop/assets/js/noconflict.js', false, false);
JHtml::_('script', EshopHelper::getSiteUrl(). 'components/com_eshop/assets/js/eshop.js', false, false);
JHtml::_('script', EshopHelper::getSiteUrl(). 'components/com_eshop/assets/js/slick.js', false, false);
JHtml::_('script', EshopHelper::getSiteUrl(). 'modules/mod_eshop_manufacturer/admin/owl.carousel.js', false, false);
require JModuleHelper::getLayoutPath('mod_eshop_manufacturer', $params->get('layout', 'default'));
?>