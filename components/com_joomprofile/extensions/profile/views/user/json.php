<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class JoomprofileProfileViewJsonUser extends JoomprofileViewjson
{
	public $_name = 'user';
	public $_path = __DIR__;

	public function registration()
	{
		$config_app = JoomprofileExtension::get('config');
		$config = $config_app->getConfig('profile');

		$template	= $this->getTemplate();

		$step = $this->input->get('step', 1);

		if(empty($this->reg_data->usergroups)){
			$com_users_config 	= JComponentHelper::getParams('com_users');
			$groupId 			= $com_users_config->get('new_usertype');
			$this->reg_data->usergroups = array($groupId);
		}

		$fieldgroups_ids = JoomprofileProfileHelper::getFieldgroupsByUsergroups($this->reg_data->usergroups);

		if(!count($fieldgroups_ids)){
			$fieldgroup = false;
			$fieldgroup_fields = false;
			$mapping = false;
			$final_step = false;
		}
		else{
			$groups = array();
			$fieldgroups = JoomprofileProfileHelper::getFieldgroups();
			foreach($fieldgroups as $group_id => $group){
				if($group->published && $group->registration && in_array($group_id, $fieldgroups_ids)){
					$groups[$group_id] = $group;
				}
			}

			$groups = array_values($groups);

			// if field group is not set
			if(!isset($groups[$step - 1])){
				throw new Exception('Invalid Field group', 404);
			}

			// if its last field groups then set it as final step
			$final_step = false;
			if(!isset($groups[$step])){
				$final_step = true;
			}

			$fieldgroup = $groups[$step-1];

			$fieldgroup = JoomprofileLib::getObject('fieldgroup', 'Joomprofileprofile', $fieldgroup->id, array('app' => $this->app), $fieldgroup);
			list($fieldgroup_fields, $mapping) = $fieldgroup->getFieldsAndMappings();
		}

		if((int)$step == 1){
			// get joomla user groups to show
			// get joomla user groups to show
			$all_jusergroups = JoomprofileHelperJoomla::getUsergroups();
			$allowed_jusergroups = false;
			if(isset($config['registration_jusergroups_selection']) && $config['registration_jusergroups_selection']){
				if(!empty($config['registration_allowed_jusergroups'])){
					$allowed_jusergroups = $config['registration_allowed_jusergroups'];
				}
			}

			$template->set('allowed_jusergroups', $allowed_jusergroups)
				 	 ->set('all_jusergroups', $all_jusergroups);
		}

		$privacy_options = JoomprofileProfileHelperField::getPrivacyOptions();

		$template->set('fieldgroup', $fieldgroup == false ? $fieldgroup : $fieldgroup->toObject())
				 ->set('fieldgroup_fields', $fieldgroup_fields)
				 ->set('fieldmapping', $mapping)
				 ->set('final_step', $final_step)
				 ->set('next_step', ++$step)
				 ->set('reg_data', $this->reg_data)
				 ->set('privacy_options', $privacy_options)
				 ->set('config', $config);
		$html =  $template->render('site.'.$this->app->getName().'.'.$this->_name.'.registration.profile');

		$response = new stdClass();
		$response->error = false;
		$response->html = $html;
		$response = json_encode($response);

		if($this->input->get('transport', 0)){
			echo '<textarea data-type="application/json">';
			echo $response;
			echo '</textarea>';
		}
		else{
			echo '#F90JSON#'.$response.'#F90JSON#';
		}

		exit();
	}

	public function register()
	{
		$result = $this->result;
		$messages= $this->messages;

		$template	= $this->getTemplate();

		$response = new stdClass();
		$response->error = false;

		if($result === true){
			$tmpl = 'site.'.$this->app->getName().'.'.$this->_name.'.registration.success';
		}
		else {
			$tmpl = 'site.'.$this->app->getName().'.'.$this->_name.'.registration.fail';
		}

		$template->set('messages', $messages);
		$response->html =  $template->render($tmpl);
		$response = json_encode($response);
		if($this->input->get('transport', 0)){
			echo '<textarea data-type="application/json">';
			echo $response;
			echo '</textarea>';
		}
		else{
			echo '#F90JSON#'.$response.'#F90JSON#';
		}
		exit();
	}

	public function editfieldgrouphtml()
	{
		// @TODO : move to proper location
		$app = JFactory::getApplication();
		if($app->isAdmin()){
			$itemid = $this->getId();
		}
		else{
			$itemid = JFactory::getUser()->id;
		}

		if(!$itemid){
			throw new Exception('INVALID USER');
		}

		// if logged in user is profile editor then get user id from input
		$user = $this->getObject($itemid);
		$isProfileEditor = false;
		if($user->isProfileEditor()){
			$isProfileEditor = true;
			$itemid = $this->getId();
			$user = $this->getObject($itemid);
		}

		$fieldgroup_id = $this->input->getInt('fieldgroup_id', 0);
		$fieldgroups = $user->getFieldgroups();

		$response = new stdClass();
		$response->error = false;

		if(!$fieldgroups || !isset($fieldgroups[$fieldgroup_id])){
			$response->error = true;
			$response->html = "Error";
		}
		else{
			list($field_values, $privacy_values) = $user->getFieldValues();
			$privacy_options = JoomprofileProfileHelperField::getPrivacyOptions();
			list($fields, $mapping) = $fieldgroups[$fieldgroup_id]->getFieldsAndMappings();

			$template	= $this->getTemplate();
			$template->set('fields', $fields)
					 ->set('fieldmapping', $mapping)
					 ->set('fieldgroup_id', $fieldgroup_id)
					 ->set('field_values', $field_values)
					 ->set('privacy_options', $privacy_options)
					 ->set('privacy_values', $privacy_values)
					 ->set('user', $user->toObject())
					 ->set('isAdmin', JoomprofileHelperJoomla::getApplication()->isAdmin())
					 ->set('isProfileEditor', $isProfileEditor);

			$response->html = $template->render('site.'.$this->app->getName().'.'.$this->_name.'.profile.editfieldgroup');
		}

		$response = json_encode($response);
		echo '#F90JSON#'.$response.'#F90JSON#';
		exit();
	}

	public function getOtherUser()
	{
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('u.*');
			$query->from('`#__users` AS u');
			$query->join('LEFT', $db->quoteName('#__user_usergroup_map', 'm') . ' ON (' . $db->quoteName('m.user_id') . ' = ' . $db->quoteName('u.id') . ')');
			$query->where('m.`group_id` = 3');
			$query->where('u.`is_consultinger` = 1');
			$query->setLimit(5);
			$query->order('RAND()');
			$db->setQuery($query);
			$results = $db->loadObjectList();
			return $results;
	}

	public function getFieldsValue($user_id)
	{
		$results = array();
		if($user_id > 0){
				$db = JFactory::getDbo();
				$query = $db->getQuery(true);
				$query->select('jfv.*');
				$query->from('`#__joomprofile_field_values` AS jfv');
				$query->where('jfv.`user_id` = '.$user_id);
				$db->setQuery($query);
				$results = $db->loadObjectList();
				return $results;
		}
		return $results;
	}

	public function getFieldName($fieldoption_id,$field_id)
	{
		if($field_id > 0 && $fieldoption_id > 0){
				$db = JFactory::getDbo();
				$query = $db->getQuery(true);
				$query->select('jf.title');
				$query->from('`#__joomprofile_fieldoption` AS jf');
				$query->where('jf.`id` = '.$fieldoption_id);
				$query->where('jf.`field_id` = '.$field_id);
				$db->setQuery($query);
				$results = $db->loadResult();
				return $results;
		}else{
			return '';
		}
	}

	public function getProvinceName($province_id)
	{
		if($province_id > 0){
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('country_name')
				->from('#__eshop_countries')
				->where('id = ' . (int) $province_id);
			$db->setQuery($query);
			return $db->loadResult();
		}else{
			return '';
		}
	}

	public function viewfieldgrouphtml()
	{
		// @TODO : move to proper location
		$app = JFactory::getApplication();

		$itemid = $this->getId();
		if(!$itemid){
			$itemid = JFactory::getUser()->id;
		}

		if(!$itemid){
			return false;
		}

		// who can edit the field details
		$isAdmin = false;
		if($app->isAdmin()){
			$isAdmin = true;
		}
		$me = JFactory::getUser();
		$isProfileEditor = $this->getObject($me->id)->isProfileEditor();
		$canEdit = $isAdmin || $me->id == $itemid;

		$user = $this->getObject($itemid);
		list($field_values, $field_privacy) = $user->getFieldValues();
		$fieldgroup_id = $this->input->getInt('fieldgroup_id', 0);

		$response = new stdClass();
		$response->error = false;

		$fieldgroups = $user->getFieldgroups();

		$avatar = $user->getAvatar(200);

		$template	= $this->getTemplate();

		$config_app = JoomprofileExtension::get('config');
        $config = $config_app->getConfig('profile');

				$otherUser = $this->getOtherUser();

        $template->set('fieldgroups', $fieldgroups)
				  ->set('field_values', $field_values)
				  ->set('field_privacy', $field_privacy)
				  ->set('fieldgroup_id', $fieldgroup_id)
				  ->set('user', $user->toObject())
					->set('otherUser', $otherUser)
					->set('counselors', $counselors)
				  ->set('avatar', $avatar)
				  ->set('loggedin', JoomprofileHelperJoomla::isUserLoggedIn($itemid))
				  ->set('can_edit', $canEdit)
				  ->set('isProfileEditor', $isProfileEditor)
				  ->set('isAdmin', $isAdmin)
                  ->set('config', $config);
		$response->html = $template->render('site.'.$this->app->getName().'.'.$this->_name.'.profile.viewfieldgroup');


		$response = json_encode($response);

		if($this->input->get('transport', 0)){
			echo '<textarea data-type="application/json">';
			echo $response;
			echo '</textarea>';
		}
		else{
			echo '#F90JSON#'.$response.'#F90JSON#';
		}

		exit();
	}

}
