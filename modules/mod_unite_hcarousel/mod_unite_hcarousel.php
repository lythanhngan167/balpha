<?php

/**
 * @package Unite Horizontal Carousel Module for Joomla 1.7-2.5
 * @version 1.0
 * @author UniteCMS.net
 * @copyright (C) 2012- Unite CMS
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

// no direct access
defined('_JEXEC') or die;

	//include item files
	$pathIncludes = JPATH_ADMINISTRATOR."/components/com_unitehcarousel/includes.php";
	require_once $pathIncludes;

	//set active menu link
	$urlBase = JURI::base();

	$sliderID = $params->get("sliderid");

	$document = JFactory::getDocument();
	$include_jquery = $params->get("include_jquery","true");

	if($include_jquery == "true"){

		$jsPrefix = "http";
		if(JURI::getInstance()->isSSL() == true)
			$jsPrefix = "https";

		$document->addScript("{$jsPrefix}://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js");
	}

	$loadType = $params->get("js_load_type","head");
	$isJSInBody = ($loadType == "body")?true:false;
	$noConflictMode = ($params->get("no_conflict_mode") == "true")?true:false;

	HelperUniteHCar::outputSlider($sliderID,$isJSInBody,$noConflictMode);


	
