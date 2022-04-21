<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class JoomprofileProfileViewHtmlUser extends JoomprofileViewHtml
{
	public $_name = 'user';
	public $_path = __DIR__;

	protected function _grid($template)
	{
		$records = parent::_grid($template);

		$model = $this->getModel();
		$usergroups 	= $model->getUsergroups(array_keys($records));
		$groupnames 	= JoomprofileHelperJoomla::getUsergroups();

		$template->set('groupnames', $groupnames)
				 ->set('usergroups', $usergroups);
		return $records;
	}

	public function registration()
	{
		$config_app = JoomprofileExtension::get('config');
		$config = $config_app->getConfig('profile');

		$step = $this->input->get('step', 1);

		$final_step = false;

		if(empty($this->reg_data->usergroups)){
			$com_users_config 	= JComponentHelper::getParams('com_users');
			$groupId 			= $com_users_config->get('new_usertype');
			$this->reg_data->usergroups = array($groupId);
		}

		$fieldgroups_ids = JoomprofileProfileHelper::getFieldgroupsByUsergroups($this->reg_data->usergroups);

		$fieldgroup = false;
		$fieldgroup_fields = false;
		$mapping = false;
		$final_step = true;

		if(count($fieldgroups_ids)){
			$groups = array();
			$fieldgroups = JoomprofileProfileHelper::getFieldgroups();
			foreach($fieldgroups as $group_id => $group){
				if($group->published && $group->registration && in_array($group_id, $fieldgroups_ids)){
					$groups[$group_id] = $group;
				}
			}

			if(!count($groups)){
				$fieldgroup = false;
				$fieldgroup_fields = false;
			}
			else{
				if(count($groups) > 1){
					$final_step = false;
				}

				$fieldgroup = array_shift($groups);
				$fieldgroup = JoomprofileLib::getObject('fieldgroup', 'Joomprofileprofile', $fieldgroup->id, array('app' => $this->app), $fieldgroup);
				list($fieldgroup_fields, $mapping) = $fieldgroup->getFieldsAndMappings();
			}
		}

		// get privacy options
		$privacy_options = JoomprofileProfileHelperField::getPrivacyOptions();

		// get joomla user groups to show
		$all_jusergroups = JoomprofileHelperJoomla::getUsergroups();
		$allowed_jusergroups = false;
		if(isset($config['registration_jusergroups_selection']) && $config['registration_jusergroups_selection']){
			if(!empty($config['registration_allowed_jusergroups'])){
				$allowed_jusergroups = $config['registration_allowed_jusergroups'];
			}
		}

		$template = $this->getTemplate();
		$template->set('fieldgroup', $fieldgroup ? $fieldgroup->toObject() : false) //  check if fieldgroup is not false
				 ->set('fieldgroup_fields', $fieldgroup_fields)
				 ->set('fieldmapping', $mapping)
				 ->set('final_step', $final_step)
				 ->set('next_step', ++$step)
				 ->set('reg_data', $this->reg_data)
				 ->set('privacy_options', $privacy_options)
				 ->set('allowed_jusergroups', $allowed_jusergroups)
				 ->set('all_jusergroups', $all_jusergroups)
				 ->set('config', $config);

		return $template->render('site.'.$this->app->getName().'.'.$this->_name.'.registration');
	}

	public function display()
	{
		$itemid = $this->getId();
		if(!$itemid){
			$itemid = JFactory::getUser()->id;
		}

		if(!$itemid){
			return false;
		}

		$user = $this->getObject($itemid);
		$userObject = $user->toObject();
		$template 	= $this->getTemplate();
		$template->set('user', $userObject);
		$template->set('avatar', $avatar = $user->getAvatar(200));
		$template->set('profileUrl', JRoute::_('index.php?option=com_joomprofile&view=profile&task=user.display&id='.$userObject->id));
		$nameParts = explode(' ', $userObject->name, 2);
		$template->set('firstName', $nameParts[0]);
    $template->set('lastName', isset($nameParts[1]) ? $nameParts[1] : '');
		return $template->render('site.'.$this->app->getName().'.'.$this->_name.'.profile.display');
	}
	

}
