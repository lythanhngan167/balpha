<?php
if (!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
/**
 * @package     Joomla.Administrator
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
require_once(JPATH_SITE . '/components/com_eshop/helpers/helper.php');

use Joomla\Utilities\ArrayHelper;

/**
 * Users list controller class.
 *
 * @since  1.6
 */
class UsersControllerSearch extends JControllerAdmin
{
	/**
	 * @var    string  The prefix to use with controller messages.
	 * @since  1.6
	 */
	protected $text_prefix = 'COM_USERS_COMMISSION';

	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @since   1.6
	 * @see     JController
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);

		$this->registerTask('block', 'changeBlock');
		$this->registerTask('unblock', 'changeBlock');
	}

	/**
	 * Proxy for getModel.
	 *
	 * @param   string  $name    The model name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  object  The model.
	 *
	 * @since   1.6
	 */
	public function getModel($name = 'User', $prefix = 'UsersModel', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}

	/**
	 * Method to change the block status on a record.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	public function changeBlock()
	{
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$ids    = $this->input->get('cid', array(), 'array');
		$values = array('block' => 1, 'unblock' => 0);
		$task   = $this->getTask();
		$value  = ArrayHelper::getValue($values, $task, 0, 'int');

		if (empty($ids))
		{
			JError::raiseWarning(500, JText::_('COM_USERS_USERS_NO_ITEM_SELECTED'));
		}
		else
		{
			// Get the model.
			$model = $this->getModel();

			// Change the state of the records.
			if (!$model->block($ids, $value))
			{
				JError::raiseWarning(500, $model->getError());
			}
			else
			{
				if ($value == 1)
				{
					$this->setMessage(JText::plural('COM_USERS_N_USERS_BLOCKED', count($ids)));
				}
				elseif ($value == 0)
				{
					$this->setMessage(JText::plural('COM_USERS_N_USERS_UNBLOCKED', count($ids)));
				}
			}
		}

		$this->setRedirect('index.php?option=com_users&view=users');
	}

	/**
	 * Method to activate a record.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	public function activate()
	{
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$ids = $this->input->get('cid', array(), 'array');

		if (empty($ids))
		{
			JError::raiseWarning(500, JText::_('COM_USERS_USERS_NO_ITEM_SELECTED'));
		}
		else
		{
			// Get the model.
			$model = $this->getModel();

			// Change the state of the records.
			if (!$model->activate($ids))
			{
				JError::raiseWarning(500, $model->getError());
			}
			else
			{
				$this->setMessage(JText::plural('COM_USERS_N_USERS_ACTIVATED', count($ids)));
			}
		}

		$this->setRedirect('index.php?option=com_users&view=users');
	}

	public function openLandingpage(){
		$user_id = $_REQUEST['user_id'];
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$user   = JFactory::getUser();
		if($user->id > 0){
			$userLandingpage  = JFactory::getUser($user_id);
			if($user_id > 0 ){
				$oUser = new stdClass();
				$oUser->id = $user_id;
				$oUser->block_landingpage = 0;

				if($userLandingpage->lpage_date == null){ // nguoi bat Landingpage lan dau
					$oUser->lpage_date = date('Y-m-d H:i:s');
				}
				if($userLandingpage->block_landingpage_user == 0) { // bat Landingpage lan dau
					$oUser->block_landingpage_user = $user->id;
				}

				$updateResult = $db->updateObject('#__users', $oUser, 'id');
				if($updateResult){
					echo "1";
				}else{
					echo "0";
				}
			}else{
				echo "-2";
			}
		}else{
			echo "-1";
		}
		exit();

	}

	public function closeLandingpage(){
		$user_id = $_REQUEST['user_id'];
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$user   = JFactory::getUser();
		if($user->id > 0){
			if($user_id > 0){
				$oUser = new stdClass();
				$oUser->id = $user_id;
				$oUser->block_landingpage = 1;
				//$oUser->lpage_date = date('Y-m-d H:i:s');
				//$oUser->block_landingpage_user = $user->id;
				$updateResult = $db->updateObject('#__users', $oUser, 'id');
				if($updateResult){
					echo "1";
				}else{
					echo "0";
				}
			}else{
				echo "-2";
			}
		}else{
			echo "-1";
		}
		exit();
	}

	public function changeLevel()
	{
		$userid = $_REQUEST['userid'];
		$level = (int)$_REQUEST['level'];
		$model = $this->getModel('Search', 'UsersModel');
		if($userid > 0 && $level >= 4 && $level <= 5){
			$result = $model->changeLevel($userid, $level);
			echo $result;
		}else{
			echo 0;
		}
		exit();
	}

	public function checkUserBelongToBDM(){
		$model = $this->getModel('Search', 'UsersModel');
		$oUser = $model->findParentUser(2273,3499);
		print_r($oUser); die;
	}


}
