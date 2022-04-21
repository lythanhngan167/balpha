<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Customer
 * @author     tung hoang <tungvacc@gmail.com>
 * @copyright  2017 tung hoang
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

/**
 * Customer controller class.
 *
 * @since  1.6
 */
class CustomerControllerCustomer extends JControllerLegacy
{
	/**
	 * Method to check out an item for editing and redirect to the edit form.
	 *
	 * @return void
	 *
	 * @since    1.6
	 */

	public function edit()
	{
		$app = JFactory::getApplication();

		// Get the previous edit id (if any) and the current edit id.
		$previousId = (int) $app->getUserState('com_customer.edit.customer.id');
		$editId     = $app->input->getInt('id', 0);

		// Set the user id for the user to edit in the session.
		$app->setUserState('com_customer.edit.customer.id', $editId);

		// Get the model.
		$model = $this->getModel('Customer', 'CustomerModel');

		// Check out the item
		if ($editId)
		{
			$model->checkout($editId);
		}

		// Check in the previous user.
		if ($previousId && $previousId !== $editId)
		{
			$model->checkin($previousId);
		}

		// Redirect to the edit screen.
		$this->setRedirect(JRoute::_('index.php?option=com_customer&view=customerform&layout=edit', false));
	}

	/**
	 * Method to save a user's profile data.
	 *
	 * @return    void
	 *
	 * @throws Exception
	 * @since    1.6
	 */
	public function publish()
	{
		// Initialise variables.
		$app = JFactory::getApplication();

		// Checking if the user can remove object
		$user = JFactory::getUser();

		if ($user->authorise('core.edit', 'com_customer') || $user->authorise('core.edit.state', 'com_customer'))
		{
			$model = $this->getModel('Customer', 'CustomerModel');

			// Get the user data.
			$id    = $app->input->getInt('id');
			$state = $app->input->getInt('state');

			// Attempt to save the data.
			$return = $model->publish($id, $state);

			// Check for errors.
			if ($return === false)
			{
				$this->setMessage(JText::sprintf('Save failed: %s', $model->getError()), 'warning');
			}

			// Clear the profile id from the session.
			$app->setUserState('com_customer.edit.customer.id', null);

			// Flush the data from the session.
			$app->setUserState('com_customer.edit.customer.data', null);

			// Redirect to the list screen.
			$this->setMessage(JText::_('COM_CUSTOMER_ITEM_SAVED_SUCCESSFULLY'));
			$menu = JFactory::getApplication()->getMenu();
			$item = $menu->getActive();

			if (!$item)
			{
				// If there isn't any menu item active, redirect to list view
				$this->setRedirect(JRoute::_('index.php?option=com_customer&view=customers', false));
			}
			else
			{
				$this->setRedirect(JRoute::_($item->link . $menuitemid, false));
			}
		}
		else
		{
			throw new Exception(500);
		}
	}

	/**
	 * Remove data
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	public function remove()
	{
		// Initialise variables.
		$app = JFactory::getApplication();

		// Checking if the user can remove object
		$user = JFactory::getUser();

		if ($user->authorise('core.delete', 'com_customer'))
		{
			$model = $this->getModel('Customer', 'CustomerModel');

			// Get the user data.
			$id = $app->input->getInt('id', 0);

			// Attempt to save the data.
			$return = $model->delete($id);

			// Check for errors.
			if ($return === false)
			{
				$this->setMessage(JText::sprintf('Delete failed', $model->getError()), 'warning');
			}
			else
			{
				// Check in the profile.
				if ($return)
				{
					$model->checkin($return);
				}

                $app->setUserState('com_customer.edit.inventory.id', null);
                $app->setUserState('com_customer.edit.inventory.data', null);

                $app->enqueueMessage(JText::_('COM_CUSTOMER_ITEM_DELETED_SUCCESSFULLY'), 'success');
                $app->redirect(JRoute::_('index.php?option=com_customer&view=customers', false));
			}

			// Redirect to the list screen.
			$menu = JFactory::getApplication()->getMenu();
			$item = $menu->getActive();
			$this->setRedirect(JRoute::_($item->link, false));
		}
		else
		{
			throw new Exception(500);
		}
	}

	public function transferToAgent() {
		$user			= JFactory::getUser();
		$input 			= JFactory::getApplication()->input;
		$agentId 		= $input->get('agentId');
		$customerId		= $input->get('customerId');

		$model = $this->getModel('Customer', 'CustomerModel');

		if($user->id <= 0) {
			echo -2;
			exit();
		}

		if($agentId != "" && $customerId != "") {
			$result = $model->updateSaleId($agentId, $customerId);
			$log	= $model->saveTransferLog($agentId, $customerId);
			echo $result;
			exit();
		} else {
			echo -1;
			exit();
		}
	}

	public function isAgentExist() {
		$input 			= JFactory::getApplication()->input;
		$agent 			= $input->get('agent');

		$model = $this->getModel('Customer', 'CustomerModel');

		if(!$agent) {
			echo -1;
			exit();
		}

		$result = $model->getAgent($agent);
		if($result == null) {
			echo -1;
			exit();
		}

		echo $result;
		exit();

	}

	public function addToTrash() {
		$user			= JFactory::getUser();
		$input 			= JFactory::getApplication()->input;
		$ratingId 		= $input->get('ratingId');
		$ratingNote		= $_REQUEST['ratingNote'];
		$confirmedByDM		= $_REQUEST['confirmedByDM'];
		$customerId		= $input->get('customerId');
		$model 		= $this->getModel('Customer', 'CustomerModel');
		$oCustomer = $model->getCustomer($customerId);

		if($oCustomer->regis_id > 0 && $oCustomer->project_id == AT_PROJECT){
			$oRegistration = $model->getRegistration($oCustomer->regis_id);

			if($oRegistration->transaction_approve_date != '0000-00-00 00:00:00'){
				$config = new JConfig();
				$countDate = 0;
				$now = time();
				$your_date = strtotime($oRegistration->transaction_approve_date);
				$datediff = $now - $your_date;
				$countDate = round($datediff / (60 * 60 * 24));
				// if($countDate < $config->numberDayConfirmSuccessAT){
				// 	echo -9;
				// 	exit();
				// }
			}
		}

		if($ratingId == "") {
			echo -1;
			exit();
		}

		if((int)$ratingId === 3 && $ratingNote == "") {
			echo -2;
			exit();
		}

		if($confirmedByDM == "") {
			echo -4;
			exit();
		}

		if($user->id <= 0) {
			echo -3;
			exit();
		}

		if($customerId != "") {
			$result 	= $model->addToTrash($customerId, $ratingId, $ratingNote,$confirmedByDM);
			if($result && $user->id > 0){
				JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_project/models', 'ProjectModel');
				$modelProject = JModelLegacy::getInstance('Projectss', 'ProjectModel', array('ignore_request' => true));
				$modelProject->updateBuyAll($user->id, -1);

				JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_registration/models', 'RegistrationModel');
				$modelRegis = JModelLegacy::getInstance('RegistrationForm', 'RegistrationModel', array('ignore_request' => true));
				$modelRegis->upgradeAgentLevel($user->id);
			}
			echo $result;
			exit();
		}
	}


	public function addConfirmData() {
		$user			= JFactory::getUser();
		$input 			= JFactory::getApplication()->input;
		$confirmDataId 		= $input->get('confirmDataId');
		$confirmDataNote		= $_REQUEST['confirmDataNote'];
		$customerId		= $input->get('customerId');
		$model 		= $this->getModel('Customer', 'CustomerModel');

		if($confirmDataId == "") {
			echo -1;
			exit();
		}

		if((int)$confirmDataId === 3 && $confirmDataNote == "") {
			echo -2;
			exit();
		}

		if($user->id <= 0) {
			echo -3;
			exit();
		}

		if($customerId != "") {
			$result 	= $model->addConfirmData($customerId, $confirmDataId, $confirmDataNote);
			echo $result;
			exit();
		}
	}


}
