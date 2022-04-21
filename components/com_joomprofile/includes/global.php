<?php

/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

if(!function_exists('joomprofile_importlib')){
	
	function joomprofile_importlib($name){
		static $loaded = array();		
		$name = strtolower($name);
		
		if(!isset($loaded[$name])){
			$path = JOOMPROFILE_PATH_LIBRARIES.'/'.$name;
			if(!JFolder::exists($path) || !JFile::exists($path.'/'.$name.'.php')){
				throw new Exception(JText::_('INVALID LIB IMPORT REQUEST'), 404);
			}
			
			require_once $path.'/'.$name.'.php';
			$loaded[$name] = true;
		}
		
		return true;
	}
}