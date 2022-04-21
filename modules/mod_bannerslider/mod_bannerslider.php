<?php
/*------------------------------------------------------------------------
# mod_bannerslider - WWM Banner Slideshow
# ------------------------------------------------------------------------
# author    walkswithme.net
# copyright Copyright (C) 2013 walkswithme.net. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.walkswithme.net/
# Technical Support:  Forum - http://www.walkswithme.net/joomla-banner-slideshow-module
-------------------------------------------------------------------------*/

defined( '_JEXEC' ) or die( 'Restricted access' );
if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
require_once( dirname(__FILE__).DS.'helper.php' );



$headerText	= trim($params->get('header_text'));
$footerText	= trim($params->get('footer_text'));

require_once JPATH_ADMINISTRATOR . '/components/com_banners/helpers/banners.php';
BannersHelper::updateReset();
$list = &modWalkswithmeBannerSlider::getList($params);
$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

$document = JFactory::getDocument();
$document->addScript(EshopHelper::getSiteUrl(). 'components/com_eshop/assets/js/noconflict.js','text/javascript', false, false);
$document->addScript(EshopHelper::getSiteUrl(). 'components/com_eshop/assets/js/eshop.js','text/javascript', false, false);
$document->addScript(EshopHelper::getSiteUrl(). 'components/com_eshop/assets/js/slick.js','text/javascript', false, false);


require( JModuleHelper::getLayoutPath( 'mod_bannerslider' ) );

?>
