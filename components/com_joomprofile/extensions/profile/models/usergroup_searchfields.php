<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class JoomprofileProfileModelUsergroup_searchfields extends JoomprofileModel
{	
	protected $_name = 'usergroup_searchfields';
	protected $_key= 'usergroup_searchfield_id';
}

class JoomprofileProfileModelformUsergroup_searchfields extends JoomprofileModelform
{
	protected $_name 		= 'usergroup_searchfields';	
	protected $_location 	= __DIR__;
}