<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class JoomprofileProfileModelField_fieldgroups extends JoomprofileModel
{	
	protected $_name = 'field_fieldgroups';
	protected $_key= 'field_fieldgroup_id';
}

class JoomprofileProfileModelformField_fieldgroups extends JoomprofileModelform
{
	protected $_name 		= 'field_fieldgroups';	
	protected $_location 	= __DIR__;
}