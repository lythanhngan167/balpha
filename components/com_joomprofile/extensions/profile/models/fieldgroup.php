<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class JoomprofileProfileModelFieldgroup extends JoomprofileModel
{	
	protected $_name = 'fieldgroup';
	protected $_default_ordering_col = 'ordering';
	
	protected function _buildFilterQuery($query)
	{
		$search = $this->getState('filter.search');
		if(!empty($search)){
			$query->where('`title` LIKE '.$this->_db->quote('%'.$search.'%'), 'OR')
					->where('`description` LIKE '.$this->_db->quote('%'.$search.'%'), 'OR');
		}
		
		return true;
	}
}

class JoomprofileProfileModelformFieldgroup extends JoomprofileModelform
{
	protected $_name 		= 'fieldgroup';	
	protected $_location 	= __DIR__;
}