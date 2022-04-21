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

require_once (dirname(__FILE__).'/helper.php');
require_once JPATH_ROOT . '/administrator/components/com_eshop/libraries/defines.php';
require_once JPATH_ROOT . '/administrator/components/com_eshop/libraries/inflector.php';
require_once JPATH_ROOT . '/administrator/components/com_eshop/libraries/autoload.php';
require_once JPATH_ADMINISTRATOR . '/components/com_eshop/libraries/rad/bootstrap.php';

//Load com_eshop language file
$language = JFactory::getLanguage();
$template = JFactory::getApplication()->getTemplate();

$tag = $language->getTag();

if (!$tag)
	$tag = 'en-GB';

$language->load('com_eshop', JPATH_ROOT, $tag);

//Load css module eshop quote
$document = JFactory::getDocument();

// add extra css for selected type

if (is_file(JPATH_SITE .  '/templates/'. $template .  '/css/'  . $module->module . '.css')) {
	$document->addStyleSheet(JURI::base().'templates/' . $template . '/css/' . $module->module . '.css');
} else {
	$document->addStyleSheet(JURI::base().'modules/' . $module->module . '/asset/css/style.css');
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
//Get quote data
$quote = new EshopQuote();
$items = $quote->getQuoteData();
$countProducts = $quote->countProducts();
$view = JRequest::getVar('view');
require JModuleHelper::getLayoutPath('mod_eshop_quote', $params->get('layout', 'default'));