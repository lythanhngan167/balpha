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
defined('_JEXEC') or die;

$editorPlugin = null;

if (JPluginHelper::isEnabled('editors', 'codemirror'))
{
	$editorPlugin = 'codemirror';
}
elseif (JPluginHelper::isEnabled('editor', 'none'))
{
	$editorPlugin = 'none';
}

if ($editorPlugin)
{
	echo JHtml::_('bootstrap.addTab', 'configuration', 'custom-css', JText::_('ESHOP_CONFIG_CUSTOM_CSS', true));

	$customCss = '';

	$theme    = EshopHelper::getConfigValue('theme');
	
	if (JFile::exists(JPATH_ROOT . '/components/com_eshop/themes/' . $theme . '/css/custom.css'))
	{
		$customCss = file_get_contents(JPATH_ROOT . '/components/com_eshop/themes/' . $theme . '/css/custom.css');
	}
	elseif (JFile::exists(JPATH_ROOT . '/components/com_eshop/themes/default/css/custom.css')) 
	{
		$customCss = file_get_contents(JPATH_ROOT . '/components/com_eshop/themes/default/css/custom.css');
	}

	echo JEditor::getInstance($editorPlugin)->display('custom_css', $customCss, '100%', '550', '75', '8', false, null, null, null, array('syntax' => 'css'));

	echo JHtml::_('bootstrap.endTab');
}