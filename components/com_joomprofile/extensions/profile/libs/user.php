<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class JoomprofileProfileLibUser extends JoomprofileLib
{
	protected $id		= 0;
	protected $name		= '';
	protected $username	= '';
	protected $email	= '';
	protected $registerDate = '';

	/**
	 * @var JUser
	 */
	protected $_juser 	= null;
	protected $_searchablefields = false;
	protected $_searchablemapping = false;
	protected $_notsearchablegroups = array();
	protected $_fieldgroups = array();

	protected function __construct($config = array())
	{
		parent::__construct($config);

		$this->id = 0;
		$this->name = '';
		$this->username = '';
		$this->email = '';
		// TODO : not working
		$this->registerDate = JDate::getInstance();
	}

	public function getJUser()
	{
		if(!( $this->_juser instanceof JUser)){
			$this->_juser = JoomprofileHelperJoomla::getUserObject($this->getId());
		}

		return $this->_juser;
	}

	public function getUsergroups()
	{
		if(!( $this->_juser instanceof JUser)){
			$user = JoomprofileHelperJoomla::getUserObject($this->getId());
		}
		else{
			$user = $this->_juser;
		}

		return $user->getAuthorisedGroups();
	}

	public function getAvatar($size)
	{
		list($field_values, $privacy) = $this->getFieldValues();
		$config = $this->_app->getConfig();
		$avatar_option = isset($config['show_avatar_from']) ? $config['show_avatar_from'] : 2; // @TODO : use constant
		if(intval($avatar_option) === 2){
			$avatar = "https://secure.gravatar.com/avatar/" . md5( strtolower( trim( $this->email ) ) ). "?s=".$size;
		}
		elseif(intval($avatar_option) === 1){
			$avatar = (count($field_values) && isset($field_values[$config['avatar_field']])) ? $field_values[$config['avatar_field']] : 'media/com_joomprofile/images/default.png';

			if(strpos($avatar, "http://") !== 0 && strpos($avatar, "https://") !== 0){
			    $avatar = JUri::root().$avatar;
			}
		}
		else{
			$avatar = false;
		}
		return $avatar;
	}

	public function getFieldgroups()
	{
		if(!empty($this->_fieldgroups)){
			return $this->_fieldgroups;
		}

		// get mapped field groups
		$usergroups = $this->getUsergroups();
		$mappedFieldgroup = JoomprofileProfileHelper::getFieldgroupsByUsergroups($usergroups);

		$fieldgroups = JoomprofileProfileHelper::getFieldgroups();

		foreach ($fieldgroups as $fieldgroup){
			if($fieldgroup->published  && in_array($fieldgroup->id, $mappedFieldgroup)){
				$this->_fieldgroups[$fieldgroup->id] = JoomprofileLib::getObject('fieldgroup', $this->_prefix, $fieldgroup->id, array('app' => $this->_app), $fieldgroup);
			}
		}

		return $this->_fieldgroups;
	}

	public function save()
	{
		list($values, $privacy) = $this->getFieldValues();

		$args = array($this->id, &$values, &$privacy);
		JoomprofileHelperJoomla::trigger('onJoomprofileUserBeforeSave', $args, array('user', 'joomprofile'));

		//@TODO : Error Validation
		// store registration values first
		JoomprofileProfileHelperRegistration::updateUser($this->id, $values);

		// do not store registration fields in our DB
		$values = JoomprofileProfileHelperRegistration::cleanup($values);
		$privacy = JoomprofileProfileHelperRegistration::cleanup($privacy);

		if(!JoomprofileProfileHelperField::save($this->id, $values, $privacy)){
			throw new Exception("Error in saving User", 1);
		}

		$this->_fieldValues = array($values, $privacy);
		$args = array($this->id, $values, $privacy);
		JoomprofileHelperJoomla::trigger('onJoomprofileUserAfterSave', $args, array('user', 'joomprofile'));
		return $this;
	}

	public function setFieldValues($values, $privacy)
	{
		list($old_values, $old_privacy) = $this->getFieldValues();
		foreach($old_values as $key => $value){
			if(!isset($values[$key])){
				$values[$key]  = $value;
				$privacy[$key] = $old_privacy[$key];
			}
		}

		$this->_fieldValues = array($values, $privacy);

		return $this;
	}

	public function getFieldValues()
	{
		if(empty($this->_fieldValues)){
			$fieldValues 	= array();
			$userid 		= $this->id;
			$db 			= JFactory::getDbo();
			//@TODO : use model if required
			$query = "SELECT * FROM `#__joomprofile_field_values` WHERE `user_id` = ".$userid;
			$db->setQuery($query);

			$results = $db->loadObjectList();

			$values = array();
			$privacy = array();
      $fields = JoomprofileProfileHelper::getFields();
			foreach ($results as $result){
				$privacy[$result->field_id] = $result->privacy;

                $value = $result->value;
                $field_instance = $this->_app->getField($fields[$result->field_id]->type);
                $value = $field_instance->loadValue($fields[$result->field_id], $value, $userid);

                if(!isset($values[$result->field_id])){
					$values[$result->field_id] = $value;
					continue;
				}

				if(!is_array($values[$result->field_id])){
					$values[$result->field_id] = array($values[$result->field_id]);
				}

				$values[$result->field_id][] = $value;
			}

			// get username , password, email field for regisration
			$config = $this->_app->getConfig();

			$username_field_id 	= isset($config['registration_field_username']) ? $config['registration_field_username'] : 0;
			$password_field_id 	= isset($config['registration_field_password']) ? $config['registration_field_password'] : 0;
			$email_field_id 	= isset($config['registration_field_email']) ? $config['registration_field_email'] : 0;
			$name_field_id 		= isset($config['registration_field_name']) ? $config['registration_field_name'] : 0;

			// IMP :: As loggedin user data is saved in session and never get updated
			//		  So need to get new instance
			$user  = new JUser($this->getId());
			if(!empty($email_field_id)){
				$values[$email_field_id] = $user->email;
				$privacy[$email_field_id] = JOOMPROFILE_PROFILE_PRIVACY_ONLY_ME;
	 		}

			if(!empty($username_field_id)){
				$values[$username_field_id] = $user->username;
				$privacy[$username_field_id] = JOOMPROFILE_PROFILE_PRIVACY_ONLY_ME;
			}

			if(!empty($name_field_id)){
				$values[$name_field_id] = $user->name;
				$privacy[$name_field_id] = JOOMPROFILE_PROFILE_PRIVACY_ONLY_ME;
			}

			if(!empty($password_field_id)){
				$values[$password_field_id] = 'F90_USER_PASSWORD';
				$privacy[$password_field_id] = JOOMPROFILE_PROFILE_PRIVACY_ONLY_ME;
			}

			$this->_fieldValues = array($values, $privacy);
		}

		return $this->_fieldValues;
	}

	function getSearchableFieldsAndMapping()
	{
		if($this->_searchablefields == false){
			$this->_searchablefields = array();
			$usergroups = $this->getUsergroups();
			foreach ($usergroups as $usergroup){
				$usergroup_searchfields = JoomprofileProfileHelper::getUsergroupSearchFields($usergroup);

				$fields = JoomprofileProfileHelper::getFields();

				foreach($usergroup_searchfields as $map_data){
					$this->_searchablefields[$map_data->field_id] = JoomprofileProfileLibField::getObject('field', 'Joomprofileprofile', $map_data->field_id, array('app' => $this->_app), $fields[$map_data->field_id]);

					$this->_searchablemapping[$map_data->field_id] = $map_data;
				}
			}
		}

		return array($this->_searchablefields, $this->_searchablemapping);
	}

	public function getNotSearchableUsergroups()
	{
		if($this->_notsearchablegroups == array()){
			$usergroups = $this->getUsergroups();
			$model = $this->getModel('usergroup');
			$query = $model->getQuery();
			$query->where('`usergroup_id` IN ('.implode(',',$usergroups).')');
			$_notsearchablegroups = $model->getList($query);

			foreach ($_notsearchablegroups as $id => $usergroup) {
					$params = json_decode($usergroup->params);
					if(isset($params->not_searchable) && !empty($params->not_searchable)){
						$this->_notsearchablegroups = array_merge($this->_notsearchablegroups, $params->not_searchable);
					}
			}
		}

		return $this->_notsearchablegroups;
	}

	public function isProfileEditor()
	{
		// Do not allow guest user to update users profile
		$currentUser = JoomprofileHelperJoomla::getUserObject();
		if(!$currentUser->id){
			return false;
		}

		$config = $this->_app->getConfig();
		if(isset($config['profile_editor']) && !empty($config['profile_editor'])
			&& array_intersect($config['profile_editor'], $this->getUsergroups())){
			return true;
		}

		return false;
	}
}
