<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class JoomprofileProfileModelFieldgroup_usergroups extends JoomprofileModel
{	
	protected $_key = '';
	protected $_name = 'fieldgroup_usergroups';
	
	public function getTable()
	{
		return false;
	}
	
	public function getList($query = null, $indexed_by = null){
		if($query === null){
			$query = $this->getQuery();
		}
		
		$this->_db->setQuery($query->__toString());
		//TODO : Error handling
		return $this->_db->loadObjectList();
	}
	
	public function getUsergroups($fieldgroup_id)
	{
		$query = $this->getQuery();
		$query->clear('select')
				->select('ALL `usergroup_id`')
				->where('`fieldgroup_id` =' .$fieldgroup_id);
		$results = $this->getList($query);
		$groups = array();
		foreach($results as $result){
			$groups[] = $result->usergroup_id;
		}
		
		return $groups;
	}
	
	public function save($itemid, $jusergroups){
		
		$db = JFactory::getDbo();
		$sql = 'DELETE FROM `#__joomprofile_fieldgroup_usergroups` WHERE `fieldgroup_id` = '.$itemid;
		$db->setQuery($sql);
		if(!$db->query()){
			return false;
		}
		
		if(!count($jusergroups)){
			return $itemid;
		}
		
		$sql = 'INSERT INTO `#__joomprofile_fieldgroup_usergroups` (`fieldgroup_id`, `usergroup_id`) VALUES ';
		$values = array();
		foreach($jusergroups as $group_id){
			$values[] = '( '.$itemid.', '.$group_id.')'; 
		}
		
		$sql .= implode(',', $values); 
		$db->setQuery($sql);
		if(!$db->query()){
			return false;
		}
		
		return $itemid;
	}
}

class JoomprofileProfileModelformFieldgroup_usergroups extends JoomprofileModelform
{
	protected $_name 		= 'fieldgroup_usergroups';	
	protected $_location 	= __DIR__;
}