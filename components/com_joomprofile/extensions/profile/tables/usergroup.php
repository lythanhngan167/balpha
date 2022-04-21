<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class JoomprofileProfileTableUsergroup extends JoomprofileTable
{	
	public $_name = 'usergroup';

	public function store($updateNulls = false)
	{
		$k = $this->_tbl_key;
	
		if($this->$k){
			if(isset($this->_new) && $this->_new){
				if(!$this->_db->insertObject( $this->_tbl, $this, $this->_tbl_key)){
					$this->setError(get_class( $this ).'::store failed - '.$this->_db->getErrorMsg());
					return false;
				}
				return true;
			}	
		}

		return parent::store($updateNulls);
	}
}