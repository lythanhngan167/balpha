<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class JoomprofileProfileModelField_values extends JoomprofileModel
{	
	protected $_name = 'field_values';
	protected $_key= 'field_id';
}

class JoomprofileProfileModelformField_values extends JoomprofileModelform
{
	protected $_name 		= 'field_values';	
	protected $_location 	= __DIR__;
}