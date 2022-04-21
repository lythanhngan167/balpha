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
				'id', 'a.`id`',
				'name', 'a.`name`',
				'phone', 'a.`phone`',
				'email', 'a.`email`',
				'place', 'a.`place`',
				'province', 'a.`province`',
				'sale_id', 'a.`sale_id`',
				'category_id', 'a.`category_id`',
				'project_id', 'a.`project_id`',
				'status_id', 'a.`status_id`',
				'total_revenue', 'a.`total_revenue`',
				'state', 'a.`state`',
				'ordering', 'a.`ordering`',
				'modified_date', 'a.`modified_date`',
				'created_by', 'a.`created_by`',
				'modified_by', 'a.`modified_by`',
				'create_date', 'a.`create_date`',
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
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication('administrator');

		// Load the filter state.
		$search = $app->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$published = $app->getUserStateFromRequest($this->context . '.filter.state', 'filter_published', '', 'string');
		$this->setState('filter.state', $published);
		// Filtering category_id
		$this->setState('filter.category_id', $app->getUserStateFromRequest($this->context.'.filter.category_id', 'filter_category_id', '', 'string'));

		// Filtering status_id
		$this->setState('filter.status_id', $app->getUserStateFromRequest($this->context.'.filter.status_id', 'filter_status_id', '', 'string'));

		$this->setState('filter.type', $app->getUserStateFromRequest($this->context.'.filter.type', 'filter_type', '', 'string'));

		// Load the parameters.
		$params = JComponentHelper::getParams('com_customer');

		$this->setState('params', $params);

		// List state information.
		//parent::populateState('a.name', 'asc');
		parent::populateState('a.id', 'desc');
	}

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param   string  $id  A prefix for the store id.
	 *
	 * @return   string A store id.
	 *
	 * @since    1.6
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id .= ':' . $this->getState('filter.search');
		$id .= ':' . $this->getState('filter.state');

		return parent::getStoreId($id);
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
		$query->select(
			$this->getState(
				'list.select', 'DISTINCT a.*'
			)
		);
		$query->from('`#__customers` AS a');

		// Join over the users for the checked out user
		$query->select("uc.name AS uEditor");
		$query->join("LEFT", "#__users AS uc ON uc.id=a.checked_out");
		// Join over the category 'category_id'
		$query->select('`category_id`.title AS `category_id`');
		$query->join('LEFT', '#__categories AS `category_id` ON `category_id`.id = a.`category_id`');

		// Join over the user field 'created_by'
		$query->select('`created_by`.name AS `created_by`');
		$query->join('LEFT', '#__users AS `created_by` ON `created_by`.id = a.`created_by`');

		// Join over the user field 'modified_by'
		$query->select('`modified_by`.name AS `modified_by`');
		$query->join('LEFT', '#__users AS `modified_by` ON `modified_by`.id = a.`modified_by`');

		// Filter by published state
		$published = $this->getState('filter.state');

		if (is_numeric($published))
		{
			$query->where('a.state = ' . (int) $published);
		}
		elseif ($published === '')
		{
			$query->where('(a.state IN (0, 1))');
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
				$query->where('( a.name LIKE ' . $search . '  OR  a.phone LIKE ' . $search . '  OR  a.email LIKE ' . $search . '  OR  a.place LIKE ' . $search . '  OR  a.sale_id LIKE ' . $search . '  OR  a.status_id LIKE ' . $search . ' )');
			}
		}


		// Filtering category_id
		$filter_category_id = $this->state->get("filter.category_id");

		if ($filter_category_id !== null && !empty($filter_category_id))
		{
			$query->where("a.`category_id` = '".$db->escape($filter_category_id)."'");
		}

		// Filtering status_id
		$filter_status_id = $this->state->get("filter.status_id");
		$filter_type = $this->state->get("filter.type");

		if ($filter_status_id !== null && (is_numeric($filter_status_id) || !empty($filter_status_id)))
		{
			$query->where("a.`status_id` = '".$db->escape($filter_status_id)."'");
		}

		$filter_project_id = $this->state->get("filter.project_id");
		if ($filter_project_id !== null && (is_numeric($filter_project_id) || !empty($filter_project_id)))
		{
			$query->where("a.`project_id` = '".$db->escape($filter_project_id)."'");
		}

		if ($filter_type == 'accesstrade')
		{
			$query->where("a.`project_id` = ".AT_PROJECT);
			$query->where("a.`status_id` != 99");
			$query->select('`regis`.`transaction_status`,`regis`.`transaction_id`');
			$query->join('LEFT', '#__registration AS `regis` ON `regis`.id = a.`regis_id`');
			$query->where("`regis`.`transaction_status` = 0");
		}else{
			$query->select('`regis`.`transaction_status`,`regis`.`transaction_id`');
			$query->join('LEFT', '#__registration AS `regis` ON `regis`.id = a.`regis_id`');
		}

		$query->where("RIGHT(a.phone, 2) <> 'xy'");
		// Add the list ordering clause.

		$orderCol  = $this->state->get('list.ordering');
		$orderDirn = $this->state->get('list.direction');

		if ($orderCol && $orderDirn)
		{
			if ($filter_type == 'accesstrade' && $orderCol == 'a.id'){
				$query->order('a.id ASC');
			}else{
				if((int)$filter_status_id == 99) {
					if($orderCol != 'a.id'){
						$query->order($db->escape($orderCol . ' ' . $orderDirn));
					}
					$query->order('a.trash_approve ASC, a.modified_date DESC');
				}else{
					$query->order($db->escape($orderCol . ' ' . $orderDirn));
				}

			}

		}

		//echo $query->__toString();
		//die;
		return $query;
	}

	/**
	 * Get an array of data items
	 *
	 * @return mixed Array of data items on success, false on failure.
	 */
	public function getItems()
	{
		$items = parent::getItems();

		foreach ($items as $oneItem)
		{

			if (isset($oneItem->project_id))
			{
				$values = explode(',', $oneItem->project_id);

				$textValue = array();
				foreach ($values as $value)
				{
					if (!empty($value))
					{
						$db = JFactory::getDbo();
						$query = "SELECT * FROM #__projects WHERE state = 1 OR state = 0 HAVING id LIKE '" . $value . "'";
						$db->setQuery($query);
						$results = $db->loadObject();

						if ($results)
						{
							$textValue[] = $results->title;
						}
					}
				}
				$oneItem->project_id_int =  $oneItem->project_id;
				$oneItem->project_id = !empty($textValue) ? implode(', ', $textValue) : $oneItem->project_id;

			}
			$oneItem->status_id_int = $oneItem->status_id;
			$oneItem->status_id = JText::_('COM_CUSTOMER_CUSTOMERS_STATUS_ID_OPTION_' . strtoupper($oneItem->status_id));
			if($oneItem->province != NULL){
				$oneItem->province_text = JText::_('COM_CUSTOMER_FORM_PROVINCE_' . strtoupper($oneItem->province));
			}

		}

		return $items;
	}

	public function getItemsOriginal()
	{
		$items = parent::getItems();

		return $items;
	}

	public function checkConfirmNumberDayIsOk($buy_date){
		$config = new JConfig();
		$countDate = 0;
		$now = time();
		$your_date = strtotime($buy_date);
		$datediff = $now - $your_date;
		$countDate = round($datediff / (60 * 60 * 24));
		// khong vuot qua 7 ngay
		if($countDate <= $config->numberDayToConfirmDataBiznet){
			return 1;
		}else{
			if(date("Y-m",$your_date) == '2020-11'){ // chi thang 11-2020
				return 1;
			}else{
				return 0;
			}

		}
	}

	public function getCustomerLandingpageByPhone($phone){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from($db->quoteName('#__customers'));
		$query->where($db->quoteName('phone') . " = '".$phone."'" );
		//$query->where($db->quoteName('project_id') . " = 22" );
		//$query->where($db->quoteName('sale_id') . " = 0");
		$query->where($db->quoteName('state') . " = 1");
		$query->order('id ASC');
		$query->setLimit(1);
		$db->setQuery($query);
		$customer = $db->loadObject();
		return $customer;
	}

}
