<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Customer
 * @author     tung hoang <tungvacc@gmail.com>
 * @copyright  2017 tung hoang
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modelitem');
jimport('joomla.event.dispatcher');

use Joomla\Utilities\ArrayHelper;
use Joomla\CMS\Factory;

/**
 * Customer model.
 *
 * @since  1.6
 */
class CustomerModelCustomer extends JModelItem
{
	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return void
	 *
	 * @since    1.6
	 *
	 */
	public function canSeeCustomers($id_customer){
		$user = JFactory::getUser();
		if (($user->id) > 0) {
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('u.*');
			$query->from('`#__customers` AS u');
			$query->where('u.`sale_id` = '.$user->id);
			$query->where('u.`id` = '.$id_customer);
			$db->setQuery($query);
			$results = $db->loadObjectList();
			return $results;
		}
	}

	protected function populateState()
	{
		$customer_id = $_REQUEST['id'];
		$can_see = $this->canSeeCustomers($customer_id);
		if ($can_see) {
			//nothing
		}
		else {
			$app = JFactory::getApplication();
			$app->enqueueMessage('Bạn không có quyền truy cập Khách hàng này!', 'warning');
			$app->redirect(JRoute::_(JUri::root() . 'index.php?Itemid=282'));
		}
		$app  = JFactory::getApplication('com_customer');
		$user = JFactory::getUser();

		// Check published state
		if ((!$user->authorise('core.edit.state', 'com_customer')) && (!$user->authorise('core.edit', 'com_customer')))
		{
			$this->setState('filter.published', 1);
			$this->setState('filter.archived', 2);
		}

		// Load state from the request userState on edit or from the passed variable on default
		if (JFactory::getApplication()->input->get('layout') == 'edit')
		{
			$id = JFactory::getApplication()->getUserState('com_customer.edit.customer.id');
		}
		else
		{
			$id = JFactory::getApplication()->input->get('id');
			JFactory::getApplication()->setUserState('com_customer.edit.customer.id', $id);
		}

		$this->setState('customer.id', $id);

		// Load the parameters.
		$params       = $app->getParams();
		$params_array = $params->toArray();

		if (isset($params_array['item_id']))
		{
			$this->setState('customer.id', $params_array['item_id']);
		}

		$this->setState('params', $params);
	}

	/**
	 * Method to get an object.
	 *
	 * @param   integer $id The id of the object to get.
	 *
	 * @return  mixed    Object on success, false on failure.
	 */
	public function &getData($id = null)
	{
		if ($this->_item === null)
		{
			$this->_item = false;

			if (empty($id))
			{
				$id = $this->getState('customer.id');
			}

			// Get a level row instance.
			$table = $this->getTable();

			// Attempt to load the row.
			if ($table->load($id))
			{
				// Check published state.
				if ($published = $this->getState('filter.published'))
				{
					if (isset($table->state) && $table->state != $published)
					{
						throw new Exception(JText::_('COM_CUSTOMER_ITEM_NOT_LOADED'), 403);
					}
				}

				// Convert the JTable to a clean JObject.
				$properties  = $table->getProperties(1);
				$this->_item = ArrayHelper::toObject($properties, 'JObject');
			}
		}



		if (isset($this->_item->project_id) && $this->_item->project_id != '')
		{
			if (is_object($this->_item->project_id))
			{
				$this->_item->project_id = ArrayHelper::fromObject($this->_item->project_id);
			}

			$values = (is_array($this->_item->project_id)) ? $this->_item->project_id : explode(',',$this->_item->project_id);

			$textValue = array();

			foreach ($values as $value)
			{
				$db    = JFactory::getDbo();
				$query = "SELECT * FROM #__projects WHERE state = 1 OR state = 0  HAVING id LIKE '" . $value . "'";

				$db->setQuery($query);
				$results = $db->loadObject();

				if ($results)
				{
					$textValue[] = $results->title;
				}
			}

			$this->_item->project_id = !empty($textValue) ? implode(', ', $textValue) : $this->_item->project_id;
		}

		if (!empty($this->_item->status_id))
		{
			$this->_item->note 		= $this->_item->status_id;
			$this->_item->status_id = JText::_('COM_CUSTOMER_CUSTOMERS_STATUS_ID_OPTION_' . $this->_item->status_id);
		}
		if (!empty($this->_item->province)){
			$this->_item->province = JText::_('COM_CUSTOMER_FORM_PROVINCE_' . strtoupper($this->_item->province));
		}

		if (isset($this->_item->created_by))
		{
			$this->_item->created_by_name = JFactory::getUser($this->_item->created_by)->name;
		}

		if (isset($this->_item->modified_by))
		{
			$this->_item->modified_by_name = JFactory::getUser($this->_item->modified_by)->name;
		}

		return $this->_item;
	}

	/**
	 * Get an instance of JTable class
	 *
	 * @param   string $type   Name of the JTable class to get an instance of.
	 * @param   string $prefix Prefix for the table class name. Optional.
	 * @param   array  $config Array of configuration values for the JTable object. Optional.
	 *
	 * @return  JTable|bool JTable if success, false on failure.
	 */
	public function getTable($type = 'Customer', $prefix = 'CustomerTable', $config = array())
	{
		$this->addTablePath(JPATH_ADMINISTRATOR . '/components/com_customer/tables');

		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Get the id of an item by alias
	 *
	 * @param   string $alias Item alias
	 *
	 * @return  mixed
	 */
	public function getItemIdByAlias($alias)
	{
		$table      = $this->getTable();
		$properties = $table->getProperties();
		$result     = null;

		if (key_exists('alias', $properties))
		{
            $table->load(array('alias' => $alias));
            $result = $table->id;
		}

		return $result;
	}

	/**
	 * Method to check in an item.
	 *
	 * @param   integer $id The id of the row to check out.
	 *
	 * @return  boolean True on success, false on failure.
	 *
	 * @since    1.6
	 */
	public function checkin($id = null)
	{
		// Get the id.
		$id = (!empty($id)) ? $id : (int) $this->getState('customer.id');

		if ($id)
		{
			// Initialise the table
			$table = $this->getTable();

			// Attempt to check the row in.
			if (method_exists($table, 'checkin'))
			{
				if (!$table->checkin($id))
				{
					return false;
				}
			}
		}

		return true;
	}

	/**
	 * Method to check out an item for editing.
	 *
	 * @param   integer $id The id of the row to check out.
	 *
	 * @return  boolean True on success, false on failure.
	 *
	 * @since    1.6
	 */
	public function checkout($id = null)
	{
		// Get the user id.
		$id = (!empty($id)) ? $id : (int) $this->getState('customer.id');

		if ($id)
		{
			// Initialise the table
			$table = $this->getTable();

			// Get the current user object.
			$user = JFactory::getUser();

			// Attempt to check the row out.
			if (method_exists($table, 'checkout'))
			{
				if (!$table->checkout($user->get('id'), $id))
				{
					return false;
				}
			}
		}

		return true;
	}

	/**
	 * Get the name of a category by id
	 *
	 * @param   int $id Category id
	 *
	 * @return  Object|null    Object if success, null in case of failure
	 */
	public function getCategoryName($id)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query
			->select('title')
			->from('#__categories')
			->where('id = ' . $id);
		$db->setQuery($query);

		return $db->loadObject();
	}

	/**
	 * Publish the element
	 *
	 * @param   int $id    Item id
	 * @param   int $state Publish state
	 *
	 * @return  boolean
	 */
	public function publish($id, $state)
	{
		$table = $this->getTable();
		$table->load($id);
		$table->state = $state;

		return $table->store();
	}

	/**
	 * Method to delete an item
	 *
	 * @param   int $id Element id
	 *
	 * @return  bool
	 */
	public function delete($id)
	{
		$table = $this->getTable();

		return $table->delete($id);
	}

	/**
	 * @return results
	 * @todo lấy về tất cả đại lý - task: Chuyển đại lý khác
	 *
	 */
	public function getAgent($agent) {
		$user = JFactory::getUser();
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('us.*');
		$query->from('`#__users` AS us');
		$query->join('LEFT', '#__user_usergroup_map AS ug ON ug.user_id = us.id');
		$query->where('ug.`group_id` = 3');
		$query->where('us.`block` = 0');
		$query->where('us.`id` <> '.$db->quote($user->id));
		$query->where('us.`username` = '.$db->quote($agent) . ' OR us.`id_biznet` = '. $db->quote($agent));
		$db->setQuery($query);
		$results = $db->loadResult();
		return $results;
	}

	/**
	 * @param {agentId} id đại lý
	 * @param {customerId} id khách hàng
	 * @return 1- success, 0- fail
	 */
	public function updateSaleId($agentId, $customerId) {
		$db 					= JFactory::getDbo();
		$query 					= $db->getQuery(true);

		$customer 				= new stdClass();
		$customer->id 			= $customerId;
		$customer->sale_id		= $agentId;
		$customer->status_id	= 1;						//Chuyển trạng thái đang chờ

		$result = JFactory::getDbo()->updateObject('#__customers', $customer, 'id');

		if ($result) {
			return 1;
		} else {
			return 0;
		}
	}

	/**
	 *
	 */
	public function saveTransferLog($agentId, $customerId) {
		$db 			= JFactory::getDbo();
		$query 			= $db->getQuery(true);

		$user 			= JFactory::getUser();
		if($user->id <= 0) {
			return -1;
		}

		$log 				= new stdClass();
		$log->type			= TRANSFER_AGENT;
		$log->created_by 	= (int)$user->id;
		$log->modified_by 	= (int)$user->id;
		$log->status		= 1;
		$log->agent_id		= (int)$agentId; // chuyen toi
		$log->transfer_id	= (int)$user->id; // nguoi chuyen
		$log->customer_id	= (int)$customerId;
		$log->state			= 1;
		$log->created_date	= date('Y-m-d H:i:s');

		$result = JFactory::getDbo()->insertObject('#__userlogs', $log);
	}

	/**
	 *
	 */
	public function addToTrash($customerId, $ratingId, $ratingNote, $confirmedByDM) {
		$db 			= JFactory::getDbo();
		$query 			= $db->getQuery(true);
		$user			= JFactory::getUser();

		$customer 				= new stdClass();
		$customer->id			= (int)$customerId;
		$customer->status_id 	= 99;
		$customer->rating_id	= (int)$ratingId;
		$customer->rating_note	= $ratingNote;
		$customer->trash_confirmed_by_dm	= $confirmedByDM;
		$customer->modified_date	= date('Y-m-d H:i:s');

		$result = JFactory::getDbo()->updateObject('#__customers', $customer, 'id');

		if ($result) {
			return 1;
		} else {
			return 0;
		}
	}

	public function addConfirmData($customerId, $confirmDataId, $confirmDataNote) {
		$db 			= JFactory::getDbo();
		$query 			= $db->getQuery(true);
		$oRegis = $this->getCustomer($customerId);
		if($oRegis->regis_id > 0){
			$registration 				= new stdClass();
			$registration->id			= (int)$oRegis->regis_id;
			$registration->duplicate_id	= (int)$confirmDataId;
			$registration->duplicate_note	= $confirmDataNote;
			$registration->duplicate_status_date	= date('Y-m-d H:i:s');
			$registration->duplicate_status	= 1; // Chờ duyệt
			$result = JFactory::getDbo()->updateObject('#__registration', $registration, 'id');
		}
		if ($result) {
			return 1;
		} else {
			return 0;
		}

	}



	public function getCustomer($id)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query
			->select('*')
			->from('#__customers')
			->where('id = ' . $id);
		$db->setQuery($query);
		return $db->loadObject();
	}

	public function getRegistration($id)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query
			->select('*')
			->from('#__registration')
			->where('id = ' . $id);
		$db->setQuery($query);
		return $db->loadObject();
	}

	public function approveSuccessRegistration($regis_id,$user_id)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$oRegis = new stdClass();
		$oRegis->id = $regis_id;
		$oRegis->transaction_status = 1;
		$oRegis->transaction_approve_date = date('Y-m-d H:i:s');
		$oRegis->transaction_approve_user = $user_id;
		$updateResult = $db->updateObject('#__registration', $oRegis, 'id');
		return $updateResult;
	}

}
