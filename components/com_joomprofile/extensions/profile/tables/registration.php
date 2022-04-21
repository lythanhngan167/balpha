<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class JoomprofileProfileTableRegistration extends JoomprofileTable
{	
	public $_name = 'registration';

	public function hasPrimaryKey()
	{
		$key = $this->getKeyName();
		
		if(!$this->$key){
			return false;
		}
		
		$query = $this->_db->getQuery(true)
			->select('COUNT(*)')
			->from($this->_tbl)
			->where('`'.$key.'` = "'.$this->$key.'"');
		$this->_db->setQuery($query);
		$count = $this->_db->loadResult();
		if($count){
			return true;
		}
		
		return false;
	}
}