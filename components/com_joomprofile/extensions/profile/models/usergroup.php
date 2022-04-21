<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class JoomprofileProfileModelUsergroup extends JoomprofileModel
{	
	public $name 			= 'usergroup';
	protected $_tablename 	= '#__joomprofile_usergroup';
	protected $_key 		= 'usergroup_id';
	
	public $_filters = array();

	public function getQuery(){
		$db = $this->_db;
		$query = $db->getQuery(true);
		$query->select('`tbl1`.`id` as usergroup_id, `tbl1`.`title`, `tbl`.`params`')
				->from($db->quoteName('#__usergroups').' AS `tbl1`')
				->leftjoin($db->quoteName($this->_tablename). ' as `tbl` ON `tbl1`.`id` =  `tbl`.`usergroup_id`');
		if($this->_key){
			$query->order($this->_key);
		}
		return $query;
	}

	public function getItem($itemid){
		if ($itemid)
		{	
			$query = $this->getQuery();
			$query->where('`tbl1`.`id` = '.$itemid);
			$record = $this->getList($query);
			return array_shift($record);
		}
		
		// Convert to the JObject before adding other data.
		$item = (object)$this->_table->getProperties(1);

		return $item;		
	}

	public function save($itemid, $data){
		$this->_table->_new = $this->getTable()->load($itemid) ? false : true;

		return parent::save($itemid, $data);
	}
}

class JoomprofileProfileModelformUsergroup extends JoomprofileModelform
{
	protected $_name 		= 'usergroup';	
	protected $_location 	= __DIR__;
}