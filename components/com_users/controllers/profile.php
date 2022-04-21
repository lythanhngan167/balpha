<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JLoader::register('UsersController', JPATH_COMPONENT . '/controller.php');

/**
 * Profile controller class for Users.
 *
 * @since  1.6
 */
class UsersControllerProfile extends UsersController
{
	/**
	 * Method to check out a user for editing and redirect to the edit form.
	 *
	 * @return  boolean
	 *
	 * @since   1.6
	 */
	public function edit()
	{
		$app         = JFactory::getApplication();
		$user        = JFactory::getUser();
		$loginUserId = (int) $user->get('id');

		// Get the previous user id (if any) and the current user id.
		$previousId = (int) $app->getUserState('com_users.edit.profile.id');
		$userId     = $this->input->getInt('user_id');

		// Check if the user is trying to edit another users profile.
		if ($userId != $loginUserId)
		{
			$app->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'error');
			$app->setHeader('status', 403, true);

			return false;
		}

		$cookieLogin = $user->get('cookieLogin');

		// Check if the user logged in with a cookie
		if (!empty($cookieLogin))
		{
			// If so, the user must login to edit the password and other data.
			$app->enqueueMessage(JText::_('JGLOBAL_REMEMBER_MUST_LOGIN'), 'message');
			$this->setRedirect(JRoute::_('index.php?option=com_users&view=login', false));

			return false;
		}

		// Set the user id for the user to edit in the session.
		$app->setUserState('com_users.edit.profile.id', $userId);

		// Get the model.
		$model = $this->getModel('Profile', 'UsersModel');

		// Check out the user.
		if ($userId)
		{
			$model->checkout($userId);
		}

		// Check in the previous user.
		if ($previousId)
		{
			$model->checkin($previousId);
		}

		// Redirect to the edit screen.
		$this->setRedirect(JRoute::_('index.php?option=com_users&view=profile&layout=edit', false));

		return true;
	}

	/**
	 * Method to save a user's profile data.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	public function save()
	{
		// Check for request forgeries.
		$this->checkToken();

		$app    = JFactory::getApplication();
		$model  = $this->getModel('Profile', 'UsersModel');
		$user   = JFactory::getUser();
		$userId = (int) $user->get('id');

		// Get the user data.
		$requestData = $app->input->post->get('jform', array(), 'array');

		// Force the ID to this user.
		$requestData['id'] = $userId;

		// Validate the posted data.
		$form = $model->getForm();

		if (!$form)
		{
			JError::raiseError(500, $model->getError());

			return false;
		}

		// Send an object which can be modified through the plugin event
		$objData = (object) $requestData;
		$app->triggerEvent(
			'onContentNormaliseRequestData',
			array('com_users.user', $objData, $form)
		);
		$requestData = (array) $objData;

		// Validate the posted data.
		$data = $model->validate($form, $requestData);

		// Check for errors.
		if ($data === false)
		{
			// Get the validation messages.
			$errors = $model->getErrors();

			// Push up to three validation messages out to the user.
			for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++)
			{
				if ($errors[$i] instanceof Exception)
				{
					$app->enqueueMessage($errors[$i]->getMessage(), 'warning');
				}
				else
				{
					$app->enqueueMessage($errors[$i], 'warning');
				}
			}

			// Unset the passwords.
			unset($requestData['password1'], $requestData['password2']);

			// Save the data in the session.
			$app->setUserState('com_users.edit.profile.data', $requestData);

			// Redirect back to the edit screen.
			$userId = (int) $app->getUserState('com_users.edit.profile.id');
			$this->setRedirect(JRoute::_('index.php?option=com_users&view=profile&layout=edit&user_id=' . $userId, false));

			return false;
		}

		// Attempt to save the data.
		$return = $model->save($data);

		// Check for errors.
		if ($return === false)
		{
			// Save the data in the session.
			$app->setUserState('com_users.edit.profile.data', $data);

			// Redirect back to the edit screen.
			$userId = (int) $app->getUserState('com_users.edit.profile.id');
			$this->setMessage(JText::sprintf('COM_USERS_PROFILE_SAVE_FAILED', $model->getError()), 'warning');
			$this->setRedirect(JRoute::_('index.php?option=com_users&view=profile&layout=edit&user_id=' . $userId, false));

			return false;
		}

		// Redirect the user and adjust session state based on the chosen task.
		switch ($this->getTask())
		{
			case 'apply':
				// Check out the profile.
				$app->setUserState('com_users.edit.profile.id', $return);
				$model->checkout($return);

				// Redirect back to the edit screen.
				$this->setMessage(JText::_('COM_USERS_PROFILE_SAVE_SUCCESS'));

				$redirect = $app->getUserState('com_users.edit.profile.redirect');

				// Don't redirect to an external URL.
				if (!JUri::isInternal($redirect))
				{
					$redirect = null;
				}

				if (!$redirect)
				{
					$redirect = 'index.php?option=com_users&view=profile&layout=edit&hidemainmenu=1';
				}

				$this->setRedirect(JRoute::_($redirect, false));
				break;

			default:
				// Check in the profile.
				$userId = (int) $app->getUserState('com_users.edit.profile.id');

				if ($userId)
				{
					$model->checkin($userId);
				}

				// Clear the profile id from the session.
				$app->setUserState('com_users.edit.profile.id', null);

				$redirect = $app->getUserState('com_users.edit.profile.redirect');

				// Don't redirect to an external URL.
				if (!JUri::isInternal($redirect))
				{
					$redirect = null;
				}

				if (!$redirect)
				{
					$redirect = 'index.php?option=com_users&view=profile&user_id=' . $return;
				}

				// Redirect to the list screen.
				$this->setMessage(JText::_('COM_USERS_PROFILE_SAVE_SUCCESS'));
				$this->setRedirect(JRoute::_($redirect, false));
				break;
		}

		// Flush the data from the session.
		$app->setUserState('com_users.edit.profile.data', null);
	}

	//Thai - Check phone number ajax
	public function checkPhones() {

		$input = JFactory::getApplication()->input;
		$phone = $input->get('phone');
		$result = array();

		$result['isPhone'] = preg_match('/^0[0-9]{9}+$/', $phone) ? 'true' : 'false';

		$db = JFactory::getDbo();
		$sql = 'SELECT username 
				FROM #__users
				WHERE username=' . $db->quote($phone);
		$username = $db->setQuery($sql)->loadResult();
		$result['isPhoneInUse'] = $username ? 'true' : 'false';
		
		echo new \Joomla\CMS\Response\JsonResponse($result);
		exit();
	}

	public function getVerifyCode() {
		$input = JFactory::getApplication()->input;
		$model = $this->getModel('Profile', 'UsersModel');
		$result = array();

		$data['socialType'] = $input->get('socialType');
		$data['fieldValue'] = $input->get('phone');

		$isPhone = preg_match('/^0[0-9]{9}+$/', $data['fieldValue']);
		if($isPhone) {
			$time = $model->getUserVerifyTime($data['fieldValue']);
			if($time >= 2) {
				$result['time'] = $time + 1;
			} else {
				$result['token'] = $model->createVerifyCode($data);
				$time = $model->getUserVerifyTime($data['fieldValue']);
				$result['time'] = $time;

			}
		}
		
		echo new \Joomla\CMS\Response\JsonResponse($result);
		exit();

	}

	public function verifyPhone() {
		$input = JFactory::getApplication()->input;
		$model = $this->getModel('Profile', 'UsersModel');
		$code = $input->get('code');
		$tokenCode = $input->get('tokenCode');
		$username = $input->get('username');

		$result = $model->doVerifyPhone($code, $tokenCode, $username);

		if($result) {
			$model->updateUsername($username);
		}

		$result = $result ? 'true' : 'false';
		echo new \Joomla\CMS\Response\JsonResponse($result);
		exit();
	}

}
