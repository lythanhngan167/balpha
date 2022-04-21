<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class JoomprofileProfileHelper
{
	public static function getUsegroupFieldgroupMapping()
	{
		static $mapping = null;

		if($mapping === null){
			$app 	 = JoomprofileExtension::get('profile');
			$model 	 = $app->getModel('fieldgroup_usergroups');
			$records = $model->getList();

			foreach($records as $record){
				if(!isset($mapping[$record->usergroup_id])){
					$mapping[$record->usergroup_id] = array();
				}

				$mapping[$record->usergroup_id][] = $record->fieldgroup_id;
			}
		}

		return $mapping;
	}

	public static function getFieldgroupUsegroupMapping()
	{
		static $mapping = null;

		if($mapping === null){
			$app 	 = JoomprofileExtension::get('profile');
			$model 	 = $app->getModel('fieldgroup_usergroups');
			$records = $model->getList();

			foreach($records as $record){
				if(!isset($mapping[$record->fieldgroup_id])){
					$mapping[$record->fieldgroup_id] = array();
				}

				$mapping[$record->fieldgroup_id][] = $record->usergroup_id;
			}
		}

		return $mapping;
	}

	public static function getFieldgroupsByUsergroups(Array $usergroups)
	{
		$mapping = JoomprofileProfileHelper::getUsegroupFieldgroupMapping();
		$mappedFieldgroups = array();
		foreach($usergroups as $usergroup_id){
			if(isset($mapping[$usergroup_id])){
				$mappedFieldgroups = array_merge($mappedFieldgroups, $mapping[$usergroup_id]);
			}
		}

		$mappedFieldgroups = array_unique($mappedFieldgroups);
		return $mappedFieldgroups;
	}

	public static function getFieldgroupFieldMapping()
	{
		static $mapping = array();

		if($mapping === array()){
			$app 	 = JoomprofileExtension::get('profile');
			$model 	 = $app->getModel('field_fieldgroups');
			$records = $model->getList();

			foreach($records as $record){
				if(!isset($mapping[$record->fieldgroup_id])){
					$mapping[$record->fieldgroup_id] = array();
				}

				$mapping[$record->fieldgroup_id][] = $record->field_id;
			}
		}
		return $mapping;
	}

	public static function getFieldFieldgroupMapping()
	{
		static $mapping = null;

		if($mapping === null){
			$records = self::getFieldgroupFieldMapping();

			foreach($records as $fieldgroup_id => $fields){
				foreach($fields as $field_id){
					if(!isset($mapping[$field_id])){
						$mapping[$field_id] = array();
					}

					$mapping[$field_id][] = $fieldgroup_id;
				}
			}
		}

		return $mapping;
	}

	public static function getFieldgroups()
	{
		static $fieldgroups = null;

		if($fieldgroups === null){
			$app 	 = JoomprofileExtension::get('profile');
			$model 	 = $app->getModel('fieldgroup');
			$query = $model->getQuery();
			$query->clear('order');
			$query->order('ordering');
			$fieldgroups = $model->getList($query);
		}

		return $fieldgroups;
	}

    public static function getSearchableAllFields()
    {
        static $fields = null;

        if($fields === null){
            $app 	 = JoomprofileExtension::get('profile');
            $model 	 = $app->getModel('field');
            $query = $model->getQuery();
            $query->clear('order');
            $query->order('title');
            $fields = $model->getSearchableList($query);
            // @TODO : trigger for accessibility
        }

        return $fields;
    }

	public static function getFields()
	{
		static $fields = null;

		if($fields === null){
			$app 	 = JoomprofileExtension::get('profile');
			$model 	 = $app->getModel('field');
			$query = $model->getQuery();
			$query->clear('order');
			$query->order('title');
			$fields = $model->getList($query);
			// @TODO : trigger for accessibility
		}

		return $fields;
	}

	public static function updateSearchField($fieldid, $value)
	{
		$session = JoomprofileHelperJoomla::getSession();
		$conditions = $session->get('search_conditions', array(), 'JOOMPROFILE');

		// IMP : Filter array, so that empty value can be removed
		if(is_array($value)){
			$value = array_filter($value, 'strlen');
		}

		if(empty($value) && $value !== 0){
			if(isset($conditions[$fieldid])){
				unset($conditions[$fieldid]);
			}
		}
		else{
			$conditions[$fieldid] = $value;
		}

		$app   = JoomprofileExtension::get('profile');
		$fields = JoomprofileProfileHelper::getFields();
		$field = $app->getObject('field', 'Joomprofileprofile', $fieldid, array(), $fields[$fieldid]);

		if(!$field->getFieldInstance()->isValueSearchable($field->toObject(), $conditions[$fieldid])){
			unset($conditions[$fieldid]);
		}

		$session->set('search_conditions', $conditions, 'JOOMPROFILE');
		return $conditions;
	}

	public static function getSearchConditions()
	{
		$session = JoomprofileHelperJoomla::getSession();
		return $session->get('search_conditions', array(), 'JOOMPROFILE');
	}

	public static function getSearchResults($page = 1, $orderby = 'name', $orderin = 'asc')
	{
		$db = JoomprofileHelperJoomla::getDBO();
		$fields = JoomprofileProfileHelper::getFields();
		$params = array('registration_field_username', 'registration_field_email', 'registration_field_name');
		$app 	= JoomprofileExtension::get('profile');
		$config = $app->getConfig();
		$queries = array();

		$addedConditions = JoomprofileProfileHelper::getSearchConditions();
		foreach($addedConditions as $fieldid => $value){
			if(isset($fields[$fieldid]) && $fields[$fieldid]->published){
				$query = $db->getQuery(true);
				if(isset($config['registration_field_username']) && $fieldid == $config['registration_field_username']){
					$query->select('id as user_id')
							->from('#__users')
							->where('`username` LIKE '.$db->quote('%'.$value.'%'));
				}
				elseif(isset($config['registration_field_email']) && $fieldid == $config['registration_field_email']){
					$query->select('id as user_id')
							->from('#__users')
							->where('`email` LIKE '.$db->quote('%'.$value.'%'));
				}
				elseif(isset($config['registration_field_name']) && $fieldid == $config['registration_field_name']){
					$query->select('id as user_id')
							->from('#__users')
							->where('`name` LIKE '.$db->quote('%'.$value.'%'));
				}
				else{
					$query->select('DISTINCT user_id')
							->from('#__joomprofile_field_values')
							->where('`field_id` = '.$db->quote($fieldid));
					$field = $app->getObject('field', 'Joomprofileprofile', $fieldid, array(), $fields[$fieldid]);
					$field->getFieldInstance()->buildSearchQuery($field->toObject(), $query, $value);
				}

				$queries[] = $query;
			}
		}

		$user = JoomprofileHelperJoomla::getUserObject();
		$user = $app->getObject('user', 'joomprofileprofile', $user->id);
		$notSearchableGroups = $user->getNotSearchableUsergroups();
		if(is_array($notSearchableGroups) && count($notSearchableGroups)){
			$query = $db->getQuery(true);
			$query->select('DISTINCT user_id')
							->from('#__user_usergroup_map')
							->where('`group_id` NOT IN ('.implode(',',$notSearchableGroups).')');
			$queries[] = $query;
		}

		//custom joomprofile only select user group 3: đại lý
        $query = $db->getQuery(true);
        $query->select('DISTINCT user_id')
            ->from('#__user_usergroup_map')
            ->where('`group_id` = '.$db->quote(3));
        $queries[] = $query;

        $search_query = self::_getKeywordSearchQuery();
		if(!empty($search_query)){
			$queries[] = $search_query;
		}

		$limit = isset($config['search_result_counter']) ? $config['search_result_counter']: JOOMPROFILE_PROFILE_LIMIT;

		$offset = ($page-1) * $limit;
		$results = array();
		if(!empty($queries)){
			$queries = '( SELECT * FROM (('.implode(' ) UNION ALL ( ', $queries).')) as `result` GROUP BY `result`.`user_id` having ( count(`result`.`user_id`)  > ' . (count($queries) -1). ' ) ) ';
			$countQuery = 'SELECT count(*) FROM  '.$queries.'  as `tbl` JOIN `#__users` as `user_tbl` ON `tbl`.`user_id` = `user_tbl`.id';
			$queries = 'SELECT * FROM  '.$queries.'  as `tbl` JOIN `#__users` as `user_tbl` ON `tbl`.`user_id` = `user_tbl`.id AND block = 0 AND is_consultinger = 1';
			$queries .= ' ORDER BY '.$db->quoteName($orderby).'  '.$orderin.' LIMIT '. $offset.', '.$limit;
		}
		else{
			if(isset($config['search_show_all']) && !$config['search_show_all']){
				return array(array(), 0);
			}

			$queries = 'SELECT * FROM `#__users` WHERE block = 0 AND is_consultinger = 1';
			$countQuery = 'SELECT count(*) FROM `#__users`';
			$queries .= ' ORDER BY '.$db->quoteName($orderby).'  '.$orderin.' LIMIT '. $offset.', '.$limit;
		}

		$db->setQuery($queries);
		$results = $db->loadObjectList('id');

		$db->setQuery($countQuery);
		$count = $db->loadResult();

		return array($results, $count);
	}

	protected static function _getKeywordSearchQuery()
	{
		$session = JoomprofileHelperJoomla::getSession();
		$search_word 	= $session->get('search_word', '', 'JOOMPROFILE');
		if(empty($search_word)){
			return false;
		}

		$app 	= JoomprofileExtension::get('profile');
		$config = $app->getConfig();
		if(!isset($config['allow_keyword_search']) || $config['allow_keyword_search'] == false){
			return false;
		}

		if(isset($config['keyword_search_in']) && $config['keyword_search_in'] == 'allowed'){
			$user   = JoomprofileHelperJoomla::getUserObject();
			$user   = $app->getObject('user', 'joomprofileprofile', $user->id);
			list($fields, $mapping) = $user->getSearchableFieldsAndMapping();

			$user_query = array();
			if(isset($config['registration_field_username']) && isset($fields[$config['registration_field_username']])){
				$user_query[] = ' `username` LIKE "%'.$search_word.'%" ';
			}
			if(isset($config['registration_field_name']) && isset($fields[$config['registration_field_name']])){
				$user_query[] = ' `name` LIKE "%'.$search_word.'%" ';
			}
			if(isset($config['registration_field_email']) && isset($fields[$config['registration_field_email']])){
				$user_query[] = ' `email` LIKE "%'.$search_word.'%" ';
			}

			if(!empty($user_query)){
				$user_query = 'SELECT `id` as `user_id` '
							.' FROM #__users WHERE '
							.implode(' OR ', $user_query);
			}
			else{
				$user_query = '';
			}
		}
		else{
			$fields = JoomprofileProfileHelper::getFields();
			$user_query = 'SELECT `id` as `user_id` '
							.' FROM #__users WHERE `username` LIKE "%'.$search_word.'%" '
							.' OR `email` LIKE "%'.$search_word.'%"'
							.' OR `name` LIKE "%'.$search_word.'%"';
		}

		$search_query 	= '';

		if(empty($fields)){
			return false;
		}

		$field_ids    = array_keys($fields);
		$search_query = 'SELECT a.`user_id` as `user_id` FROM `#__joomprofile_field_values` as a '
						.' INNER JOIN ( '
						.' SELECT * FROM `#__joomprofile_fieldoption` WHERE '
						.'`field_id` IN ('.implode(',', $field_ids).') '
						.'AND ';

		$value_query = 'SELECT * FROM `#__joomprofile_field_values` WHERE '
						.'`field_id` IN ('.implode(',', $field_ids).') AND ' ;

		if(strlen($search_word) > 3){
			$value_query  .= 'MATCH (`value`) AGAINST ("'.$search_word.'" IN BOOLEAN MODE)';
			$search_query .= 'MATCH(`title`) AGAINST ("'.$search_word.'" IN BOOLEAN MODE)';
		}
		else{
			$value_query .= '`value` LIKE "%'.$search_word.'%" ';
			$search_query .= '`title` LIKE "%'.$search_word.'%" ';
		}

		$search_query .= ' ) as b '
						.' ON a.`field_id` = b.`field_id` AND a.`value` = b.id ';

		$search_query = 'SELECT * FROM (('.$search_query .') ';
		if(!empty($user_query)){
			$search_query .= ' UNION  ( '.$user_query.')';
		}
		$search_query .=' UNION ( SELECT vt.`user_id` FROM ( '.$value_query.') as vt )) as ktbl';

		return $search_query;
	}

	public static function getUsergroupSearchFields($usergroup_id){

		static $fields = null;

		if($fields === null){
			$app 	 = JoomprofileExtension::get('profile');
			$model 	 = $app->getModel('usergroup_searchfields');
			$query = $model->getQuery();
			$query->clear('order')
					->order('`ordering`');
			$usergroup_fields = $model->getList($query);

			foreach($usergroup_fields as $usergroup_field){
				if(!isset($fields[$usergroup_field->usergroup_id])){
					$fields[$usergroup_field->usergroup_id] = array();
				}

				$fields[$usergroup_field->usergroup_id][] = $usergroup_field;
			}
		}

		return isset($fields[$usergroup_id]) ? $fields[$usergroup_id] : array();
	}
}
