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
 * Reset controller class for Users.
 *
 * @since  1.6
 */
class UsersControllerReset extends UsersController
{
	/**
	 * Method to request a password reset.
	 *
	 * @return  boolean
	 *
	 * @since   1.6
	 */
	public function request()
	{
		// Check the request token.
		$this->checkToken('post');

		$app   = JFactory::getApplication();
		$model = $this->getModel('Reset', 'UsersModel');
		$data  = $this->input->post->get('jform', array(), 'array');

		// Submit the password reset request.
		$return	= $model->processResetRequest($data);

		// Check for a hard error.
		if ($return instanceof Exception)
		{
			// Get the error message to display.
			if ($app->get('error_reporting'))
			{
				$message = $return->getMessage();
			}
			else
			{
				$message = JText::_('COM_USERS_RESET_REQUEST_ERROR');
			}

			// Go back to the request form.
			$this->setRedirect(JRoute::_('index.php?option=com_users&view=reset', false), $message, 'error');

			return false;
		}
		elseif ($return === false)
		{
			// The request failed.
			// Go back to the request form.
			$message = JText::sprintf('COM_USERS_RESET_REQUEST_FAILED', $model->getError());
			$this->setRedirect(JRoute::_('index.php?option=com_users&view=reset', false), $message, 'notice');

			return false;
		}
		else
		{
			// The request succeeded.
			// Proceed to step two.
			$this->setRedirect(JRoute::_('index.php?option=com_users&view=reset&layout=confirm', false));

			return true;
		}
	}

	/**
	 * Method to confirm the password request.
	 *
	 * @return  boolean
	 *
	 * @access	public
	 * @since   1.6
	 */
	public function confirm()
	{
		// Check the request token.
		$this->checkToken('request');

		$app   = JFactory::getApplication();
		$model = $this->getModel('Reset', 'UsersModel');
		$data  = $this->input->get('jform', array(), 'array');

		// Confirm the password reset request.
		$return	= $model->processResetConfirm($data);

		// Check for a hard error.
		if ($return instanceof Exception)
		{
			// Get the error message to display.
			if ($app->get('error_reporting'))
			{
				$message = $return->getMessage();
			}
			else
			{
				$message = JText::_('COM_USERS_RESET_CONFIRM_ERROR');
			}

			// Go back to the confirm form.
			$this->setRedirect(JRoute::_('index.php?option=com_users&view=reset&layout=confirm', false), $message, 'error');

			return false;
		}
		elseif ($return === false)
		{
			// Confirm failed.
			// Go back to the confirm form.
			$message = JText::sprintf('COM_USERS_RESET_CONFIRM_FAILED', $model->getError());
			$this->setRedirect(JRoute::_('index.php?option=com_users&view=reset&layout=confirm', false), $message, 'notice');

			return false;
		}
		else
		{
			// Confirm succeeded.
			// Proceed to step three.
			$this->setRedirect(JRoute::_('index.php?option=com_users&view=reset&layout=complete', false));

			return true;
		}
	}

	/**
	 * Method to complete the password reset process.
	 *
	 * @return  boolean
	 *
	 * @since   1.6
	 */
	public function complete()
	{
		// Check for request forgeries
		$this->checkToken('post');

		$app   = JFactory::getApplication();
		$model = $this->getModel('Reset', 'UsersModel');
		$data  = $this->input->post->get('jform', array(), 'array');

		// Complete the password reset request.
		$return	= $model->processResetComplete($data);

		// Check for a hard error.
		if ($return instanceof Exception)
		{
			// Get the error message to display.
			if ($app->get('error_reporting'))
			{
				$message = $return->getMessage();
			}
			else
			{
				$message = JText::_('COM_USERS_RESET_COMPLETE_ERROR');
			}

			// Go back to the complete form.
			$this->setRedirect(JRoute::_('index.php?option=com_users&view=reset&layout=complete', false), $message, 'error');

			return false;
		}
		elseif ($return === false)
		{
			// Complete failed.
			// Go back to the complete form.
			$message = JText::sprintf('COM_USERS_RESET_COMPLETE_FAILED', $model->getError());
			$this->setRedirect(JRoute::_('index.php?option=com_users&view=reset&layout=complete', false), $message, 'notice');

			return false;
		}
		else
		{
			// Complete succeeded.
			// Proceed to the login form.
			$message = JText::_('COM_USERS_RESET_COMPLETE_SUCCESS');
			$this->setRedirect(JRoute::_('index.php?option=com_users&view=login', false), $message);

			return true;
		}
	}

	public function requestCode() {
		$input   = JFactory::getApplication()->input;
		$phoneNumber = $input->get('phoneNumber');
		$model = $this->getModel('Reset', 'UsersModel');

		$data = array();

		$isPhone = preg_match('/^0[0-9]{9}+$/', $phoneNumber);

		if($phoneNumber !== null && $isPhone && $model->checkUser($phoneNumber)) {
			$profileModel = JModelLegacy::getInstance('Profile','UsersModel');
			$data['socialType'] = 'phone';
			$data['fieldValue'] = $phoneNumber;
			$token = $profileModel->createVerifyCode($data);
			$model->setState('tokens', $token);
			JFactory::getApplication()->setUserState('tokens', $token);
			JFactory::getApplication()->setUserState('phoneNumber', $phoneNumber);
			// The request succeeded.
			// Proceed to step two.
			$this->setRedirect(JRoute::_('index.php?option=com_users&view=reset&layout=confirm&type=phone', false));

			return true;
		} else {
			// The request failed.
			// Go back to the request form.
			$model->setError("Số điện thoại không hợp lệ");
			$message = JText::sprintf('COM_USERS_RESET_REQUEST_FAILED', $model->getError());
			$this->setRedirect(JRoute::_('index.php?option=com_users&view=reset', false), $message, 'notice');
			return false;
		}
	}

	public function comfirmCode() {
		$app = JFactory::getApplication();
		$input   = $app->input;
		$phoneNumber = $app->getUserState("phoneNumber");;
		$profileModel = JModelLegacy::getInstance('Profile','UsersModel');
		$model = $this->getModel('Reset', 'UsersModel');

		$code = $input->get("verifyCodeForReset");
		$token = $app->getUserState("tokens");
		$passWord1 = $input->get("newPassword1");
		$passWord2 = $input->get("newPassword2");
		$data = array(
			'password1'=> $passWord1,
			'password2'=> $passWord2,
		);

		$isPassWord = preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$/', $passWord1);
		if(!$isPassWord) {
			$message = JText::_('Mật khẩu không hợp lệ(6 kí tự, bao gồm chữ và số)');
			$this->setRedirect(JRoute::_('index.php?option=com_users&view=reset&layout=confirm&type=phone', false), $message, 'error');
			return false;
		}

		$isPhone = preg_match('/^0[0-9]{9}+$/', $phoneNumber);

		if($phoneNumber !== null && $isPhone && $code !== null && $token !== null) {
			if($profileModel->doVerifyPhone($code, $token, $phoneNumber) === true) {

				if($model->updatePassWord($data) === true) {
					// Complete succeeded.
					// Proceed to the login form.
					$message = JText::_('COM_USERS_RESET_COMPLETE_SUCCESS');
					$this->setRedirect(JRoute::_('index.php?option=com_users&view=login', false), $message);
					return true;
				} else {
					$message = JText::sprintf('COM_USERS_RESET_COMPLETE_FAILED', $model->getError());
					$this->setRedirect(JRoute::_('index.php?option=com_users&view=reset&layout=confirm&type=phone', false), $message, 'error');
					return false;
				}
			} else {
				$model->setError("Mã xác thực không chính xác!");
				$message = JText::sprintf('COM_USERS_RESET_CONFIRM_FAILED', $model->getError());
				$this->setRedirect(JRoute::_('index.php?option=com_users&view=reset&layout=confirm&type=phone', false), $message, 'error');
				return false;
			}
		}
	}

}
