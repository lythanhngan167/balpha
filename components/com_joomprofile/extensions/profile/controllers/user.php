<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class JoomprofileProfileControllerUser extends JoomprofileController
{
	public $_name = 'user';

	public function registration()
	{
		//if user is already logged in then redirect him to profile/wall
		$user = JFactory::getUser();
		if($user->id){
			$this->redirect_url = "index.php?option=com_joomprofile&view=profile&task=user.display&id=".$user->id;
			return false;
		}

		// lets clean expired session
		$reg_data  = JoomprofileProfileHelperRegistration::cleanExpiredData();
		// get session id
		$session 	= JFactory::getSession();
		$session_id = $session->getId();

		// get view and assign reg_data on it
		$view = $this->get_view();
		$reg_data  = JoomprofileProfileHelperRegistration::getDataFromId($session_id);

        $app 		    = JFactory::getApplication();
        $menu           = $app->getMenu();
        if($menu->getActive()){
            $currentMenuId 	= $menu->getActive()->id;
            if($currentMenuId){
                $menuitem   	= $app->getMenu()->getItem($currentMenuId);
                $params 	    = $menuitem->params;

                if(!empty($params['usergroup'])){
                    $reg_data->usergroups  = $params['usergroup'];
                }
            }
        }

        if(!$this->getModel('registration')->save($session_id, (array)$reg_data)){
			//@TODO : Error
		}
		$view->reg_data = $reg_data;
		return true;
	}

	public function registration_save()
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		//if user is already logged in then redirect him to profile/wall
		$user = JFactory::getUser();
		if($user->id){
			//@TODO : redirect
		}

		// get session id
		$session 	= JFactory::getSession();
		$session_id = $session->getId();

		$reg_data = JoomprofileProfileHelperRegistration::getDataFromId($session_id);

		// get the data posted in joomprofile-field
		$field_values = $this->input->get('joomprofile-field', array(), 'Array');
		$field_privacy  = $this->input->get('joomprofile-field-privacy', array(), 'Array');

		// validate fields
		// First get all fields
		$fields = JoomprofileProfileHelper::getFields();

		// get the fiels posted
		if(isset($_FILES['joomprofile-field']) && !empty($_FILES['joomprofile-field'])){
			$files = $_FILES['joomprofile-field']['tmp_name'];
			foreach ($files as $field_id => $file){
				if(!isset($fields[$field_id]) || !$fields[$field_id]->published){
					//@TODO : error
					continue;
				}

				$existing_file = isset($reg_data->values[$field_id]) ? $reg_data->values[$field_id] : false;
				jimport('joomla.filesystem.file');
				if($existing_file && JFile::exists(JPATH_SITE.$existing_file)){
					JFile::delete($existing_file);
				}

				$name = $session_id.'_'.$field_id.'_'.$_FILES['joomprofile-field']['name'][$field_id];

				$fieldObject  = $this->app->getObject('field', $this->getPrefix(), $field_id, array(), $fields[$field_id]);
				$fieldObject = $fieldObject->toObject();

				// validate
				$field_instance = $this->app->getField($fieldObject->type);
				$errors = $field_instance->validate($fieldObject, $name, 0); // at registration time user id is 0

				if(!empty($errors)){
					// @TODO : Error
					continue;
				}

				if(move_uploaded_file($file, JPATH_SITE.'/'.JOOMPROFILE_PATH_MEDIA_TMP.'/'.$name)){
					//@TODO : Raise Error
					$field_values[$field_id] = JOOMPROFILE_PATH_MEDIA_TMP.'/'.$name;
				}
			}
		}

		$values = array();
		$privacy = array();
		foreach($field_values as $field_id => &$value){
			// if field is not set
			if(!isset($fields[$field_id]) || !$fields[$field_id]->published){
				// @TODO : Error
				continue;
			}

			$fieldObject  = $this->app->getObject('field', $this->getPrefix(), $field_id, array(), $fields[$field_id]);
			$fieldObject = $fieldObject->toObject();

			// validate
			$field_instance = $this->app->getField($fieldObject->type);
			$errors = $field_instance->validate($fieldObject, $value, 0); // at registration time user id is 0

			if(!empty($errors)){
				// @TODO : Error
			}

			// format value before saving
			$values[$field_id] 	= $value;
			$privacy[$field_id] = isset($field_privacy[$field_id]) ? $field_privacy[$field_id] : 0; // @TODO : constant

			$reg_data->values[$field_id] = $values[$field_id];
			$reg_data->privacy[$field_id] = $privacy[$field_id];
		}

		if(!$this->getModel('registration')->save($session_id, (array)$reg_data)){
			//@TODO : Error
		}

		// if registration is complete then
		if($this->input->get('finish', 0)){
			list($result, $messages) = JoomprofileProfileHelperRegistration::register($session_id);

			$userid = $session->get('JOOMPROFILE_PROFILE_REGISTERED_USER');
			$session->set('JOOMPROFILE_PROFILE_REGISTERED_USER', null);
			if($userid || $result){
				// IMP :if user registered and result false :means was not able to send email
				// but still we set result to true and display a messgae
				$result = true;

				// if registration is done, then do not store these fields in our DB
				$values = $reg_data->values;
				$values = JoomprofileProfileHelperRegistration::cleanup($values);

				if($userid){
					foreach($values as $field_id => &$value){
						$field_instance = $this->app->getField($fields[$field_id]->type);
						$value = $field_instance->format($fields[$field_id], $value, $userid, JOOMPROFILE_PROFILE_ON_SAVE);
					}

					// save user groups
					$juser = JFactory::getUser($userid);
					if(empty($reg_data->usergroups)){
						$config = JComponentHelper::getParams('com_users');
						$groupId = $config->get('new_usertype');
						$reg_data->usergroups = array($groupId);
					}

					$juser->groups = $reg_data->usergroups;
					$juser->save();

					//@TODO : Validation
					$user = $this->getObject($juser->id);
					$user->setFieldValues($values, $privacy);
					$user->save();
				}
				else{
					$messages[] = JText::_('Not able to find user id');
				}

				// if registered then delete data from session
				$this->getModel('registration')->remove(array('`id` = "'.$session_id.'"'));
			}

			$view = $this->get_view();
			$view->result = $result;
			$view->messages = $messages;
			return $view->register();
		}

		if($this->registration()){
			return $this->get_view()->registration();
		}
	}

	public function display()
	{
		$itemid = $this->getId();
		if(!$itemid){
			$itemid = JoomprofileHelperJoomla::getUserObject()->id;

			if ($itemid) {
                $this->redirect_url = "index.php?option=com_joomprofile&view=profile&task=user.display&id=".$itemid;
                return false;
            }
		}
		if(!$itemid){
			$url = "index.php?option=com_joomprofile&view=profile&task=user.display&id=".$itemid;
			$this->redirect_url = JRoute::_("index.php?option=com_users&return_url=".base64_encode($url), false);
			return false;
		}
		return true;
	}

	public function viewfieldgrouphtml()
	{
		return true;
	}

	public function editfieldgrouphtml()
	{
		return true;
	}

	public function saveisconsultinger()
	{
		if($_REQUEST['is_consultinger'] >= 0 && $_REQUEST['user_id'] > 0){
			$user = JFactory::getUser($_REQUEST['user_id']);
			if($user->id > 0){
		      $sale = new stdClass();
		      $sale->id = $user->id;
		      $sale->is_consultinger = $_REQUEST['is_consultinger'];
		      $result = JFactory::getDbo()->updateObject('#__users', $sale, 'id');
		      if ($result) {
		        echo  '1';
		      }else{
						echo '0';
					}
			}else{
				 echo '0';
			}
		}else{
			 echo '0';
		}
		exit();
	}


public function checkRated($user_id, $user_id_rating)
	{
		//check user rated
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('rs.user_id_rating');
		$query->from('`#__user_star_rating` AS rs');
		$query->where('rs.`user_id_rating` = '.$user_id_rating);
		$query->where('rs.`user_id` = '.$user_id);
		$db->setQuery($query);
		$rated = $db->loadResult();
		if ($rated) {
			return 1;
		}

	}


 public function ratingStar()
	{
		if($_REQUEST['user_id'] > 0 && $_REQUEST['user_rating_id'] > 0 && $_REQUEST['star'] > 0){
		  $user = JFactory::getUser($_REQUEST['user_id']);
		  $userLoged = JFactory::getUser();
				if($user->id > 0){
					if ($_REQUEST['user_rating_id'] == $_REQUEST['user_id']) {
						echo "4";
					}
					else {
						$rated = $this->checkRated($_REQUEST['user_id'], $_REQUEST['user_rating_id']);
						if ($rated == 1) {
							echo "3";
						}else {
							$db = JFactory::getDbo();
							$query = $db->getQuery(true);
							$query->select('rs.star');
							$query->from('`#__user_star_rating` AS rs');
							$query->where('rs.`user_id` = '.$_REQUEST['user_id']);
							$db->setQuery($query);
							$sumStar = $db->loadObjectList();
							$sum = 0 ;
							foreach ($sumStar as $s) {
								$sum = $sum + $s->star;
							}

							//updating star for user in table user
							$object = new stdClass();
							$object->id = $user->id;
							if ($sum == 0) {
								$object->star = $_REQUEST['star'];
								$object->rating_times = $user->rating_times + 1;
							}
							if ($sum > 0) {
								$object->rating_times = $user->rating_times + 1;
								$object->star = $sum / $object->rating_times;
							}

							$result = JFactory::getDbo()->updateObject('#__users', $object, 'id');

							// insert rating times in table user_star_rating
							$rating = new stdClass();
							$rating->user_id = $user->id;
							$rating->user_id_rating = $_REQUEST['user_rating_id'];
							$rating->star = $_REQUEST['star'];
							$result2 = JFactory::getDbo()->insertObject('#__user_star_rating', $rating);

							if ($result && $result2) {
								echo  '1';
							}else{
								echo '0';
							}

						}

					}

					}else{
						 echo '0';
					}

		  }else{
		    echo "2";
		  }

			exit();
	}


	public function savefieldgroup()
	{
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// @TODO : move to proper location
		$app = JFactory::getApplication();
		if($app->isAdmin()){
			$itemid = $this->getId();
			$user = JFactory::getUser($itemid);
		}
		else{
			$user = JFactory::getUser();
		}

		if(!$user->id){
			//@TODO : Error
		}

		// if logged in user is profile editor then get user id from input
		$userObj = $this->getObject($user->id);
		if($userObj->isProfileEditor()){
			$isProfileEditor = true;
			$itemid = $this->getId();
			$user = JFactory::getUser($itemid);
		}

		$field_values = $this->input->get('joomprofile-field', array(), 'Array');
		$field_privacy  = $this->input->get('joomprofile-field-privacy', array(), 'Array');

		// validate fields
		// First get all fields
		$fields = JoomprofileProfileHelper::getFields();

		// get the fiels posted
		if(isset($_FILES['joomprofile-field']) && !empty($_FILES['joomprofile-field'])){
			$files = $_FILES['joomprofile-field']['tmp_name'];
			foreach ($files as $field_id => $file){
				if(!$fields[$field_id]->published){
					//@TODO : Error
					continue;
				}
				$fieldObject  = $this->app->getObject('field', $this->getPrefix(), $field_id, array(), $fields[$field_id]);
				$fieldObject = $fieldObject->toObject();
				$name = md5($user->id.'_'.$field_id).'_'.$_FILES['joomprofile-field']['name'][$field_id];
				// validate
				$field_instance = $this->app->getField($fieldObject->type);
				$errors = $field_instance->validate($fieldObject, $name, 0); // at registration time user id is 0

				if(!empty($errors)){
					// @TODO : Error
					continue;
				}

				if(move_uploaded_file($file, JPATH_SITE.'/'.JOOMPROFILE_PATH_MEDIA_TMP.'/'.$name)){
					//@TODO : Raise Error$values = $this->post->get('joomprofile-field');
					$field_values[$field_id] = JOOMPROFILE_PATH_MEDIA_TMP.'/'.$name;
				}
			}
		}

		$values = array();
		foreach($field_values as $field_id => &$value){
			// if field is not set
			if(!isset($fields[$field_id]) || !$fields[$field_id]->published){
				// @TODO : Error
				continue;
			}

			$fieldObject  = $this->app->getObject('field', $this->getPrefix(), $field_id, array(), $fields[$field_id]);
			$fieldObject = $fieldObject->toObject();
			// validate
			$field_instance = $this->app->getField($fieldObject->type);
			$errors = $field_instance->validate($fieldObject, $value, $user->id);

			if(!empty($errors)){
				// @TODO : Error
			}

			// format value before saving
			$values[$field_id] 	= $field_instance->format($fieldObject, $value, $user->id, JOOMPROFILE_PROFILE_ON_SAVE);
			$privacy[$field_id] = isset($field_privacy[$field_id]) ? $field_privacy[$field_id] : JOOMPROFILE_PROFILE_PRIVACY_PUBLIC;
		}

		//@TODO : Error Validation
		$user = $this->getObject($user->id);
		$user->setFieldValues($values, $privacy);
		$user->save();

		$this->get_view()->viewfieldgrouphtml();
	}

	public function add_registration_usergroup()
	{
		$response = new stdClass();
		$response->error = false;
		$response->html = '';

		$usergroup_id = $this->input->getInt('usergroup_id');

		$user = JFactory::getUser();
		if($user->id || !$usergroup_id){
			//@TODO : redirect
		}

		$config_app = JoomprofileExtension::get('config');
		$config = $config_app->getConfig('profile');

		$allowed_jusergroups = false;
		if(isset($config['registration_jusergroups_selection']) && $config['registration_jusergroups_selection']){
			if(!empty($config['registration_allowed_jusergroups'])){
				$allowed_jusergroups = $config['registration_allowed_jusergroups'];
			}
		}

		if($allowed_jusergroups == false || !in_array($usergroup_id, $allowed_jusergroups)){
			$response->error = true;
			$response->html = JText::_('Invalid Usergroup Selection');
			echo '#F90JSON#'.json_encode($response).'#F90JSON#';
			exit();
		}

		// get session id
		$session 	= JFactory::getSession();
		$session_id = $session->getId();

		$reg_data = JoomprofileProfileHelperRegistration::getDataFromId($session_id);
		if(!in_array($usergroup_id, $reg_data->usergroups)){
			$reg_data->usergroups[] = $usergroup_id;
		}

		if(!$this->getModel('registration')->save($session_id, (array)$reg_data)){
			$response->error = true;
			$response->html = JText::_('Error in saving data');
		}

		echo '#F90JSON#'.json_encode($response).'#F90JSON#';
		exit();
	}

	public function remove_registration_usergroup()
	{
		$usergroup_id = $this->input->getInt('usergroup_id');

		$user = JFactory::getUser();
		if($user->id || !$usergroup_id){
			//@TODO : redirect
		}

		// get session id
		$session 	= JFactory::getSession();
		$session_id = $session->getId();

		$reg_data = JoomprofileProfileHelperRegistration::getDataFromId($session_id);
		if(in_array($usergroup_id, $reg_data->usergroups)){
			$pos = array_search($usergroup_id, $reg_data->usergroups);
			unset($reg_data->usergroups[$pos]);
		}

		$response = new stdClass();
		$response->error = false;
		$response->html = '';

		if(!$this->getModel('registration')->save($session_id, (array)$reg_data)){
			$response->error = true;
			$response->html = JText::_('Error in saving data');
		}

		echo '#F90JSON#'.json_encode($response).'#F90JSON#';
		exit();
	}

	public function set_usergroup()
	{
		$response = new stdClass();
		$response->error = false;
		$response->html = '';

		$usergroup_id = $this->input->getInt('usergroup_id');

		$user = JFactory::getUser();
		if($user->id || !$usergroup_id){
			//@TODO : redirect
		}

		$config_app = JoomprofileExtension::get('config');
		$config = $config_app->getConfig('profile');

		$allowed_jusergroups = false;
		if(isset($config['registration_jusergroups_selection']) && $config['registration_jusergroups_selection']){
			if(!empty($config['registration_allowed_jusergroups'])){
				$allowed_jusergroups = $config['registration_allowed_jusergroups'];
			}
		}

		if($allowed_jusergroups == false || !in_array($usergroup_id, $allowed_jusergroups)){
			$response->error = true;
			$response->html = JText::_('Invalid Usergroup Selection');
			echo '#F90JSON#'.json_encode($response).'#F90JSON#';
			exit();
		}

		// get session id
		$session 	= JFactory::getSession();
		$session_id = $session->getId();

		$reg_data = JoomprofileProfileHelperRegistration::getDataFromId($session_id);
		$reg_data->usergroups = array($usergroup_id);

		if(!$this->getModel('registration')->save($session_id, (array)$reg_data)){
			$response->error = true;
			$response->html = JText::_('Error in saving data');
		}

		echo '#F90JSON#'.json_encode($response).'#F90JSON#';
		exit();
	}

	public function export()
	{
		JSession::checkToken( 'get' ) or die( 'Invalid Token' );

		//@TODO : PROPER Handling
		if(!$this->app->japp->isAdmin()){
			die('UN-AUTHORIZED ACCESS');
		}

		$db = JoomprofileHelperJoomla::getDBO();

		//get the fields excludig core regstration fields
		$fields = JoomprofileProfileHelper::getFields();
		// get username , password, email field for regisration
		$config = $this->app->getConfig();

		if(isset($config['registration_field_username']) && isset($fields[$config['registration_field_username']])){
			unset($fields[$config['registration_field_username']]);
		}
		if(isset($config['registration_field_password']) && isset($fields[$config['registration_field_password']])){
			unset($fields[$config['registration_field_password']]);
		}
		if(isset($config['registration_field_email']) && isset($fields[$config['registration_field_email']])){
			unset($fields[$config['registration_field_email']]);
		}
		if(isset($config['registration_field_name']) && isset($fields[$config['registration_field_name']])){
			unset($fields[$config['registration_field_name']]);
		}

		// get all usergroups
		$usergroups = JoomprofileHelperJoomla::getUsergroups();

		// get all users
		$sql = "SELECT * FROM `#__users` as utbl LEFT JOIN "
				." (SELECT user_id, GROUP_CONCAT(group_id) FROM `#__user_usergroup_map` GROUP BY `user_id`) as ugtbl "
				." ON utbl.id = ugtbl.user_id";
		$db->setQuery($sql);
		$users = $db->loadObjectList('id');

		// get all users' fields values
		$sql = "SELECT * FROM `#__joomprofile_field_values`";
		$db->setQuery($sql);
		$results = $db->loadObjectList();

		$fieldvalues = array();
		foreach ($results as $result){
			if(!isset($fieldvalues[$result->user_id])){
				$fieldvalues[$result->user_id] = array();
			}

			if(!isset($fieldvalues[$result->user_id][$result->field_id])){
				$fieldvalues[$result->user_id][$result->field_id] = $result->value;
				continue;
			}

			if(!is_array($fieldvalues[$result->user_id][$result->field_id])){
				$fieldvalues[$result->user_id][$result->field_id] = array($fieldvalues[$result->user_id][$result->field_id]);
			}

			$fieldvalues[$result->user_id][$result->field_id][] = $result->value;
		}

		$csvFieldname = array("Id","Username","Name","Email", "Block", "Register Date");
		foreach($fields as $field){
			$field->params = json_decode($field->params, true);
			$fieldInstance = JoomprofileLibField::get($field->type);
			$csvFieldname  = array_merge($csvFieldname, $fieldInstance->getExportColumn($field));
		}

		$csv = '"'.implode('","', $csvFieldname).'"'."\r\n";

		foreach($users as $user){
			$values = array();
			$values[] = $user->id;
			$values[] = $user->username;
			$values[] = $user->name;
			$values[] = $user->email;
			$values[] = $user->block;
			$values[] = $user->registerDate;

			foreach($fields as $field){
				$fieldInstance = JoomprofileLibField::get($field->type);
				$tmp = $fieldInstance->getExportValue($field, isset($fieldvalues[$user->id]) && isset($fieldvalues[$user->id][$field->id]) ? $fieldvalues[$user->id][$field->id] : '', $user->id);

				if(!is_array($tmp)){
					$tmp = array($tmp);
				}

				foreach($tmp as $t){
                    $t = JoomprofileHelperJoomla::nl2br($t);
					$values[] = JoomprofileHelperJoomla::escape($t);
				}
			}

			$csv .= '"'.implode('","', $values).'"'."\r\n";
		}

		header('Content-Description: File Transfer');
		header('Content-Type: application/vnd.ms-excel');
		header('Content-disposition: attachment; filename="users.csv"');

		echo trim($csv);
		exit();
	}

}
