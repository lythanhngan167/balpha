<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Customer
 * @author     tung hoang <tungvacc@gmail.com>
 * @copyright  2017 tung hoang
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Customer records.
 *
 * @since  1.6
 */
class CustomerModelCustomers extends JModelList
{
	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @see        JController
	 * @since      1.6
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'a.id',
				'name', 'a.name',
				'phone', 'a.phone',
				'email', 'a.email',
				'place', 'a.place',
				'sale_id', 'a.sale_id',
				'category_id', 'a.category_id',
				'project_id', 'a.project_id',
				'status_id', 'a.status_id',
				'total_revenue', 'a.total_revenue',
				'state', 'a.state',
				'ordering', 'a.ordering',
				'modified_date', 'a.modified_date',
				'created_by', 'a.created_by',
				'modified_by', 'a.modified_by',
				'create_date', 'a.create_date',
			);
		}

		parent::__construct($config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   Elements order
	 * @param   string  $direction  Order direction
	 *
	 * @return void
	 *
	 * @throws Exception
	 *
	 * @since    1.6
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		$app  = JFactory::getApplication();
		$list = $app->getUserState($this->context . '.list');

		$ordering  = isset($list['filter_order'])     ? $list['filter_order']     : null;
		$direction = isset($list['filter_order_Dir']) ? $list['filter_order_Dir'] : null;

		$list['limit']     = (int) JFactory::getConfig()->get('list_limit', 20);
		$list['start']     = $app->input->getInt('start', 0);
		$list['ordering']  = $ordering;
		$list['direction'] = $direction;

		$app->setUserState($this->context . '.list', $list);
		$app->input->set('list', null);

		// List state information.
		parent::populateState($ordering, $direction);

        $app = JFactory::getApplication();

        $ordering  = $app->getUserStateFromRequest($this->context . '.ordercol', 'filter_order', $ordering);
        $direction = $app->getUserStateFromRequest($this->context . '.orderdirn', 'filter_order_Dir', $ordering);

        $this->setState('list.ordering', $ordering);
        $this->setState('list.direction', $direction);

        $start = $app->getUserStateFromRequest($this->context . '.limitstart', 'limitstart', 0, 'int');
        $limit = $app->getUserStateFromRequest($this->context . '.limit', 'limit', 0, 'int');

        if ($limit == 0)
        {
            $limit = $app->get('list_limit', 0);
        }

        $this->setState('list.limit', $limit);
        $this->setState('list.start', $start);
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return   JDatabaseQuery
	 *
	 * @since    1.6
	 */
	protected function getListQuery()
	{
		// Create a new query object.
		$db    = $this->getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query
			->select(
				$this->getState(
					'list.select', 'DISTINCT a.*'
				)
			);

		$query->from('`#__customers` AS a');

		// Join over the users for the checked out user.
		$query->select('uc.name AS uEditor');
		$query->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');
		// Join over the category 'category_id'
		$query->select('categories_2794246.title AS category_id');
		$query->join('LEFT', '#__categories AS categories_2794246 ON categories_2794246.id = a.category_id');

		// Join over the created by field 'created_by'
		$query->join('LEFT', '#__users AS created_by ON created_by.id = a.created_by');

		// Join over the created by field 'modified_by'
		$query->join('LEFT', '#__users AS modified_by ON modified_by.id = a.modified_by');

		$query->where('a.trash_approve <> 1');

		if (!JFactory::getUser()->authorise('core.edit', 'com_customer'))
		{
			$query->where('a.state = 1');
		}

		if ($_GET['color'])
		{
			$query->where("a.color = '".$_GET['color']."'");
		}

		// Filter by search in title
		$search = $this->getState('filter.search');

		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where('a.id = ' . (int) substr($search, 3));
			}
			else
			{
				$search = $db->Quote('%' . $db->escape($search, true) . '%');
				$query->where('( a.name LIKE ' . $search . '  OR  a.phone LIKE ' . $search . '  OR  a.email LIKE ' . $search . '  OR  a.place LIKE ' . $search . ' )');
			}
		}


		// Filtering category_id
		$filter_category_id = $this->state->get("filter.category_id");

		if ($filter_category_id)
		{
			$query->where("a.`category_id` = '" . $db->escape($filter_category_id) . "'");
		}

		// Filtering status_id
		$filter_status_id = $this->state->get("filter.status_id");
		if ($filter_status_id != '') {
			$query->where("a.status_id = '".$db->escape($filter_status_id)."'");
		}

		if(JFactory::getUser()->get('id') > 0){
			$query->where("a.sale_id = " . $db->escape(JFactory::getUser()->get('id')) );
		}
		$query->where("RIGHT(a.phone, 2) <> 'xy'");

		$app = JFactory::getApplication();
		$menuid = $app->getMenu()->getActive()->id;

		$status_id = $this->getNote($menuid);
		$arrStatus = explode(",",$status_id);

		if((int)$arrStatus[0] > 0){
			if((int)$arrStatus[1] > 0){
				$status_1 = 'OR  a.status_id = ' . $db->escape($arrStatus[1]);
			}
			if((int)$arrStatus[2] > 0){
				$status_2 = 'OR  a.status_id = ' . $db->escape($arrStatus[2]);
			}
			if((int)$arrStatus[3] > 0){
				$status_3 = 'OR  a.status_id = ' . $db->escape($arrStatus[3]);
			}
			if((int)$arrStatus[4] > 0){
				$status_4 = 'OR  a.status_id = ' . $db->escape($arrStatus[4]);
			}
			$query->where('( a.status_id = ' . $db->escape($arrStatus[0]). '  '.$status_1.' '.$status_2.' '.$status_3.' '.$status_4.'  )');

		}

		// Add the list ordering clause.
		$orderCol  = $this->state->get('list.ordering','a.buy_date');
		$orderDirn = $this->state->get('list.direction','DESC');


		if ($orderCol && $orderDirn)
		{
			$query->order($db->escape($orderCol . ' ' . $orderDirn));
		}
		return $query;
	}
	public function getNote($menuid){

		if($menuid > 0){
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);

			$query->select('note');
			$query->from($db->quoteName('#__menu'));
			$query->where($db->quoteName('id')." = ".$menuid);
			$db->setQuery($query);

			$result = $db->loadResult();
			return $result;
		}else{
			return 0;
		}

	}
	/**
	 * Method to get an array of data items
	 *
	 * @return  mixed An array of data on success, false on failure.
	 */
	public function getItems()
	{
		$items = parent::getItems();

		foreach ($items as $item)
		{

			if (isset($item->category_id))
			{

				// Get the title of that particular template
					$title = CustomerHelpersCustomer::getCategoryNameByCategoryId($item->category_id);

					// Finally replace the data object with proper information
					$item->category_id = !empty($title) ? $title : $item->category_id;
				}

			if (isset($item->project_id))
			{
				$values    = explode(',', $item->project_id);
				$textValue = array();

				foreach ($values as $value)
				{
					if (!empty($value))
					{
						$db    = JFactory::getDbo();
						$query = "SELECT * FROM #__projects WHERE id LIKE '" . $value . "'";

						$db->setQuery($query);
						$results = $db->loadObject();

						if ($results)
						{
							$textValue[] = $results->title;
						}
					}
				}
				$item->project_id_int = $item->project_id;
				$item->project_id = !empty($textValue) ? implode(', ', $textValue) : $item->project_id;
			}

			if(isset($item->rating_id)) {
				switch((int)$item->rating_id){
					case 3:
						$item->rating_text = 'Khác';
					break;
					case 1:
						$item->rating_text = 'Sai thông tin';
					break;
					case 2:
						$item->rating_text = 'Không có nhu cầu';
					break;
				}
			}
			$item->province = JText::_('COM_CUSTOMER_FORM_PROVINCE_' . strtoupper($item->province));
			$item->status_id = JText::_('COM_CUSTOMER_CUSTOMERS_STATUS_ID_OPTION_' . strtoupper($item->status_id));
		}

		return $items;
	}


	/**
	 * Overrides the default function to check Date fields format, identified by
	 * "_dateformat" suffix, and erases the field if it's not correct.
	 *
	 * @return void
	 */
	protected function loadFormData()
	{
		$app              = JFactory::getApplication();
		$filters          = $app->getUserState($this->context . '.filter', array());
		$error_dateformat = false;

		foreach ($filters as $key => $value)
		{
			if (strpos($key, '_dateformat') && !empty($value) && $this->isValidDate($value) == null)
			{
				$filters[$key]    = '';
				$error_dateformat = true;
			}
		}

		if ($error_dateformat)
		{
			$app->enqueueMessage(JText::_("COM_CUSTOMER_SEARCH_FILTER_DATE_FORMAT"), "warning");
			$app->setUserState($this->context . '.filter', $filters);
		}

		return parent::loadFormData();
	}

	/**
	 * Checks if a given date is valid and in a specified format (YYYY-MM-DD)
	 *
	 * @param   string  $date  Date to be checked
	 *
	 * @return bool
	 */
	private function isValidDate($date)
	{
		$date = str_replace('/', '-', $date);
		return (date_create($date)) ? JFactory::getDate($date)->format("Y-m-d") : null;
	}

	public function saveCall($data)
	{
		$call = new stdClass();

		$call->custommer_id = $data['customer_id'];
		$call->note = $data['reason'];
		$call->created_by = $data['user_id'];
		$call->create_date = date("Y-m-d H:i:s");

		// Insert the object into the user profile table.
		$result = JFactory::getDbo()->insertObject('#__notes', $call);
		if($result){
			return 1;
		}else{
			return 0;
		}
	}

	public function getCustomer($customerid)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from($db->quoteName('#__customers'));
		$query->where($db->quoteName('id')." = ".$customerid);
		$db->setQuery($query);
		$result = $db->loadObject();
		return $result;
	}

	public function saveReturnStore($data)
	{
		$returnStore = new stdClass();

		$returnStore->user_id = $data['user_id'];
		$returnStore->customer_id = $data['customer_id'];
		$returnStore->category_id = $data['category_id'];
		$returnStore->project_id = $data['project_id'];
		$returnStore->status_id = $data['status_id'];
		$returnStore->buy_date = $data['buy_date'];
		$returnStore->status_return_cancel = $data['status_return_cancel'];
		$returnStore->created_date = date("Y-m-d H:i:s");
		$returnStore->state = 1;

		// Insert the object into the user profile table.
		$result = JFactory::getDbo()->insertObject('#__return_store', $returnStore);
		if($result){
			return 1;
		}else{
			return 0;
		}
	}

	public function saveStatus($data)
	{
		// Insert the object into the user profile table.
		$status = new stdClass();
		if($data['status_id'] == 7){
			$status->total_revenue = $data['total_revenue'];
		}


		if($data['status_id'] == 6 || $data['status_id'] == 8){
			if($data['customer_id'] > 0){
				$customerInfo = $this->getCustomer($data['customer_id']);
				$returnStore = array();
				$returnStore['user_id'] = $customerInfo->sale_id;
				$returnStore['customer_id'] = $customerInfo->id;
				$returnStore['category_id'] = $customerInfo->category_id;
				$returnStore['project_id'] = $customerInfo->project_id;
				$returnStore['status_id'] = $customerInfo->status_id;
				$returnStore['buy_date'] = $customerInfo->buy_date;
				$returnStore['status_return_cancel'] = $data['status_id'];

				$this->saveReturnStore($returnStore);
			}
			//$catid = $this->getCatID($data['customer_id']);
			$status->id = $data['customer_id'];
			//$status->status_id = 1;
			//$status->sale_id = 0;
			$status->modified_date = date("Y-m-d H:i:s");
			$status->total_revenue = 0;
			if($data['status_id'] == 6 ){
				$status->status_id = 6;
				$status->category_id = 150;
			}
			if($data['status_id'] == 8 ){
				$status->status_id = 8;
				//$status->category_id = 161;
			}

		}else{
			$status->id = $data['customer_id'];
			$status->status_id = $data['status_id'];
			$status->modified_date = date("Y-m-d H:i:s");
		}

		// Must be a valid primary key value.


		// Update their details in the users table using id as the primary key.
		$result = JFactory::getDbo()->updateObject('#__customers', $status, 'id');

		if($result){
			return 1;
		}else{
			return 0;
		}
	}


	public function saveColor($data)
	{
		// Insert the object into the user profile table.
		$color = new stdClass();

		$color->id = $data['customer_id'];
		$color->color = $data['color_id'];
		//$color->modified_date = date("Y-m-d H:i:s");

		// Must be a valid primary key value.


		// Update their details in the users table using id as the primary key.
		$result = JFactory::getDbo()->updateObject('#__customers', $color, 'id');

		if($result){
			return 1;
		}else{
			return 0;
		}
	}


	public function updateAddress($data)
	{
		// Insert the object into the user profile table.
		$status = new stdClass();

		$status->id = $data['customer_id'];
		$status->place = $data['place'];
		//$status->modified_date = date("Y-m-d H:i:s");

		// Must be a valid primary key value.


		// Update their details in the users table using id as the primary key.
		$result = JFactory::getDbo()->updateObject('#__customers', $status, 'id');
		if($result){
			return 1;
		}else{
			return 0;
		}
	}

	public function updateRevenue($data)
	{
		// Insert the object into the user profile table.
		$status = new stdClass();

		$status->id = $data['customer_id'];
		$status->total_revenue = $data['total_revenue'];
		$status->modified_date = date("Y-m-d H:i:s");

		// Must be a valid primary key value.


		// Update their details in the users table using id as the primary key.
		$result = JFactory::getDbo()->updateObject('#__customers', $status, 'id');
		if($result){
			return 1;
		}else{
			return 0;
		}
	}

	public function getLatestNote($customer_id){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from($db->quoteName('#__notes'));
		$user = JFactory::getUser();
		if($user->id > 0){
			$query->where($db->quoteName('created_by')." = ".$user->id);
		}
		$query->where($db->quoteName('custommer_id')." = ".$customer_id);
		$query->order("id DESC");
		$query->limit(0,1);
		$db->setQuery($query);

		$result = $db->loadObject();
		return $result;
	}

	public function getCatID($customer_id){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('category_id');
		$query->from($db->quoteName('#__customers'));
		$query->where($db->quoteName('id')." = ".$customer_id);
		$db->setQuery($query);
		$result = $db->loadResult();
		return $result;
	}

	public function getTotalCaring($userid){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('count(*) as total');
		$query->from($db->quoteName('#__customers'));
		$query->where($db->quoteName('sale_id')." = ".$userid);
		$query->where($db->quoteName('status_id')." IN (1,2,3,4)");
		$db->setQuery($query);
		$result = $db->loadResult();
		return $result;
	}

	public function returnStore24h($time){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$fields = array(
		    $db->quoteName('sale_id') . ' = 0',
		    $db->quoteName('status_id') . " = '1'");

		$conditions = array(
		    $db->quoteName('sale_id') . ' > 0',
				$db->quoteName('buy_date') . ' <= date_sub("'.date("Y-m-d H:i:s").'", INTERVAL 24 hour)',
		    $db->quoteName('status_id') . " = '1'");

		$query->update($db->quoteName('#__customers'))->set($fields)->where($conditions);
		$db->setQuery($query);

		$result = $db->execute();
		if($result) return 1;
		return 0;
	}

	public function test(){
		// Create an object for the record we are going to update.
		$object = new stdClass();

		// Must be a valid primary key value.
		$object->id = 1;
		$object->note = 'babc';

		// Update their details in the users table using id as the primary key.
		$result = JFactory::getDbo()->updateObject('#__categories', $object, 'id');
	}



}
