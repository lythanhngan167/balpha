<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class JoomprofileConfigModelConfig extends JoomprofileModel
{	
	public $name = 'config';
	
	public function getTable()
	{
		return false;
	}
	
	public function save($itemid, $data)
	{
		$sql = "DELETE FROM `".$this->_tablename."`";//."` WHERE `id` IN ('".implode("', '", array_keys($data))."') ";
		$this->_db->setQuery($sql);
		$this->_db->query();
		
		$sql = 'INSERT INTO `'.$this->_tablename.'` VALUES ';
		$values = array();
		foreach ($data as $key => $value){
			$values[] = '('.$this->_db->quote($key).', '.$this->_db->quote($value).')';
		} 
		
		$sql .= implode(", ", $values);
		$this->_db->setQuery($sql);
		return $this->_db->query();		
	}
}