<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class JoomprofileProfileHelperRegistration
{
	public static function getDataFromId($id)
	{
		// check if this seesion_id alread has the data in tem table regarding profile.registration
		$reg_model = JoomprofileExtension::get('profile')->getModel('registration');
		$reg_data  = $reg_model->getItem($id);
		
		// prepare empty record if does not exists
		if(!$reg_data->id){						
			$reg_data 				= new stdClass();
			$reg_data->id 			= $id;
			$reg_data->usergroups 	= json_encode(array());
			$date 					= new JDate();
			$reg_data->created 		= $date->toSql();
			$reg_data->values		= ''; 
		}
		
		$reg_data->usergroups = json_decode($reg_data->usergroups, true);
		//var_dump($reg_data);die();
		if(empty($reg_data->usergroups)){

			$app 			= JFactory::getApplication();
			$menu           = $app->getMenu();
			$menuusergroup  = array();
			if($menu->getActive()){
				$currentMenuId 	= $menu->getActive()->id;
				if($currentMenuId){
					$menuitem   	= $app->getMenu()->getItem($currentMenuId);
					$params 	= $menuitem->params;
					$menuusergroup  = $params['usergroup'];
				}
			}


			if(!empty($menuusergroup)){
				$reg_data->usergroups  = $menuusergroup;
			}else {
				$config = JComponentHelper::getParams('com_users');
				$groupId = $config->get('new_usertype');
				$reg_data->usergroups 	= array($groupId);
			}
		}
		
		
		$values = new JRegistry();
		$values->loadString($reg_data->values);		
		$reg_data->values = json_decode($reg_data->values, true);
		
		return $reg_data;
	}

	public static function register($session_id)
	{
		$errors = array();
		$reg_data = JoomprofileProfileHelperRegistration::getDataFromId($session_id);
		
		// get username , password, email field for regisration
		$config_app =  JoomprofileExtension::get('config');
		$config = $config_app->getConfig('profile');
		$username_field_id 	= $config['registration_field_username'];
		$password_field_id 	= $config['registration_field_password'];
		$email_field_id 	= $config['registration_field_email'];
		$name_field_id 		= $config['registration_field_name'];
		
		$email 			= isset($reg_data->values[$email_field_id]) 	? $reg_data->values[$email_field_id] : '';
		$username 		= isset($reg_data->values[$username_field_id]) 	? $reg_data->values[$username_field_id] : $email;
		$name 			= isset($reg_data->values[$name_field_id])		? $reg_data->values[$name_field_id] : $username;

		if(!empty($password_field_id) && isset($reg_data->values[$password_field_id])){
			$password = $reg_data->values[$password_field_id] ? $reg_data->values[$password_field_id] : '';
		}
		else{
			// if password field is not mapped then generate random password
			jimport('joomla.user.helper');
			$password = JUserHelper::genRandomPassword(); 
		}
		
		$retype_password = $password;
		$confirm_email = $email;
		
		// if username is not available then email will be username
		if(empty($username)){
			$username = $email;
		}		
		
		// if name is not avialable then username will be name
		if(empty($name)){
			$name = $username;
		}
		
		$requestData = array(	'username'	=> $username,
								'name'		=> $name,
								'email1'	=> $email,
								'email2'	=> $confirm_email,
								'password1' => $password, 
								'password2' => $retype_password);
		
		$app = JFactory::getApplication();
		require_once  JPATH_SITE.'/components/com_users/models/registration.php';
		$filename = 'com_users';
		$language = JFactory::getLanguage();
		$language->load($filename, JPATH_SITE);
		$model	= new UsersModelRegistration();
			
		JForm::addFormPath(JPATH_SITE.'/components/com_users/models/forms');
		JForm::addFieldPath(JPATH_SITE.'/components/com_users/models/fields');
		$form	= $model->getForm();
		$form->removeField('captcha');
		if (!$form) {
			return array(false, $model->getError());
		}
		
		$data	= $model->validate($form, $requestData);

		// Check for validation errors.
		if ($data === false) {
			// Get the validation messages.
			$reg_errors	= $model->getErrors();

			// Push up to three validation messages out to the user.
			for ($i = 0, $n = count($reg_errors); $i < $n && $i < 3; $i++) {
				if (is_a($reg_errors[$i], 'Exception') == true) {
					$errors[] = $reg_errors[$i]->getMessage();
				} else {
					$errors[] = $reg_errors[$i];
				}
			}
			
			return array(false, $errors);
		}

		// Attempt to save the data.
		$return	= $model->register($requestData);

		// Check for errors.
		if ($return === false) {
			return array(false, array(JText::sprintf('COM_USERS_REGISTRATION_SAVE_FAILED', $model->getError())));
		}

		// Redirect to the profile screen.
		if ($return === 'adminactivate'){
			return array(true, array(JText::_('COM_USERS_REGISTRATION_COMPLETE_VERIFY')));
		} elseif ($return === 'useractivate'){
			return array(true, array(JText::_('COM_USERS_REGISTRATION_COMPLETE_ACTIVATE')));
		}
		else{
			return array(true, array(JText::_('COM_USERS_REGISTRATION_SAVE_SUCCESS')));
		}		
	}
	
	public static function cleanup($values)
	{
		// get username , password, email field for regisration
		$config_app =  JoomprofileExtension::get('config');
		$config = $config_app->getConfig('profile');
		
		$params = array('registration_field_username', 'registration_field_password', 'registration_field_email', 'registration_field_name');
		foreach ($params as $param){
			if(!isset($config[$param]) || empty($config[$param])){
				continue;
			}
			
			if(!isset($values[$config[$param]])){
				continue;
			}
			
			unset($values[$config[$param]]);
		}
		
		return $values;
	}
	
	public static function updateUser($userid, $values)
	{		
		// get username , password, email field for regisration
		$config_app =  JoomprofileExtension::get('config');
		$config = $config_app->getConfig('profile');
		
		$username_field_id 	= $config['registration_field_username'];
		$password_field_id 	= $config['registration_field_password'];
		$email_field_id 	= $config['registration_field_email'];
		$name_field_id 		= $config['registration_field_name'];
		
		$user = JoomprofileHelperJoomla::getUserObject($userid);
		if(isset($values[$email_field_id])){
			$data['email'] = $values[$email_field_id];
		}

		if(isset($values[$username_field_id])){
			$data['username'] = $values[$username_field_id];
		}

		if(isset($values[$name_field_id])){
			$data['name'] = $values[$name_field_id];
		}

		if(isset($values[$password_field_id]) && $values[$password_field_id] != 'F90_USER_PASSWORD'){
			$data['password'] = $values[$password_field_id];
			$data['password2']		= $data['password'];
		}
		
		$user->bind($data);
		return $user->save();
	} 
	
	public static function cleanExpiredData()
	{
		$session = JFactory::getSession();
		$db = JFactory::getDbo();
		
		$uppertime = time();
		$uppertime -= $session->getExpire();
		$uppertime = new JDate($uppertime);
		
		$reg_model = JoomprofileExtension::get('profile')->getModel('registration');
		return $reg_model->remove(array($db->quoteName('created') . ' < ' . $db->quote($uppertime->toSql())));
	}
}

