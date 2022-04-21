<?php
/**
 * @version		1.3.3
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

if (is_file(JPATH_SITE .  '/templates/'. $template .  '/css/'  . $module->module . '.css'))
{
	$document->addStyleSheet(JURI::base().'templates/' . $template . '/css/' . $module->module . '.css');
}
else
{
	$document->addStyleSheet(JURI::base().'modules/' . $module->module . '/assets/css/style.css');
}
require JModuleHelper::getLayoutPath('mod_eshop_search', $params->get('layout', 'default'));