<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class JoomprofileProfileModelUser extends JoomprofileModel
{
	public $name = 'user';
	protected $_tablename = '#__users';

	public $_filters = array('search', 'usergroup');

	public function getUsergroups($userIds)
	{
		if(empty($userIds)){
			return array();
		}

		// Get the counts from the database only for the users in the list.
		$db = $this->_db;
		$query = $db->getQuery(true);

		$tmpQuery = $this->_db->getQuery(true);
		$tmpQuery->select('*')
				->from('#__user_usergroup_map as `map`')
				->where('map.user_id IN (' . implode(',', $userIds) . ')');

		// Join over the group mapping table.
		$query->select('*')
			->from('#__usergroups AS g2')
			// Join over the user groups table.
			->rightJoin(' ( '.$tmpQuery->__tostring().' ) as `mapping` ON g2.id = `mapping`.group_id');

		$db->setQuery($query);
		$records = $db->loadObjectList();

		$usergroups = array();
		foreach($records as $record){
			$usergroups[$record->user_id][] = $record;
		}

		return $usergroups;
	}

	protected function _buildFilterQuery($query)
	{
		/* @var $query JDatabaseQueryMysql */
		$search = $this->getState('filter.search');
		if(!empty($search)){
			$query->where('`username` LIKE '.$this->_db->quote('%'.$search.'%'), 'OR')
					->where('`name` LIKE '.$this->_db->quote('%'.$search.'%'), 'OR')
					->where('`email` LIKE '.$this->_db->quote('%'.$search.'%'), 'OR');
		}

		$usergroup = $this->getState('filter.usergroup');
		if(!empty($usergroup)){
			$tmpQuery = $this->_db->getQuery(true);
			$tmpQuery->select('*')
					->from('#__user_usergroup_map as `map`')
					->where('`map`.`group_id` = '.(int)$usergroup);
			$query->rightJoin(' ( '.$tmpQuery->__tostring().' ) as `mapping` ON id = `mapping`.user_id');
		}
		return true;
	}
}

class JoomprofileProfileModelformUser extends JoomprofileModelform
{
	protected $_name 		= 'user';
	protected $_location 	= __DIR__;
}
