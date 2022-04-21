<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Order
 * @author     tung hoang <tungvacc@gmail.com>
 * @copyright  2017 tung hoang
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Order records.
 *
 * @since  1.6
 */
class OrderModelOrders extends JModelList
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
				'category_id', 'a.category_id',
				'quantity', 'a.quantity',
				'price', 'a.price',
				'total', 'a.total',
				'project_id', 'a.project_id',
				'ordering', 'a.ordering',
				'state', 'a.state',
				'created_by', 'a.created_by',
				'modified_by', 'a.modified_by',
				'create_date', 'a.create_date',
				'modified_date', 'a.modified_date',
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

		$query->from('`#__orders` AS a');

		// Join over the users for the checked out user.
		$query->select('uc.name AS uEditor');
		$query->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');

		// Join over the created by field 'created_by'
		$query->join('LEFT', '#__users AS created_by ON created_by.id = a.created_by');

		// Join over the created by field 'modified_by'
		$query->join('LEFT', '#__users AS modified_by ON modified_by.id = a.modified_by');

		if (!JFactory::getUser()->authorise('core.edit', 'com_order'))
		{
			$query->where('a.state = 1');
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
			}
		}


		// Filtering create_date
		// Checking "_dateformat"
		$filter_create_date_from = $this->state->get("filter.create_date_from_dateformat");
		$filter_Qcreate_date_from = (!empty($filter_create_date_from)) ? $this->isValidDate($filter_create_date_from) : null;

		if ($filter_Qcreate_date_from != null)
		{
			$query->where("a.create_date >= '" . $db->escape($filter_Qcreate_date_from) . "'");
		}

		$filter_create_date_to = $this->state->get("filter.create_date_to_dateformat");
		$filter_Qcreate_date_to = (!empty($filter_create_date_to)) ? $this->isValidDate($filter_create_date_to) : null ;

		if ($filter_Qcreate_date_to != null)
		{
			$query->where("a.create_date <= '" . $db->escape($filter_Qcreate_date_to) . "'");
		}
		if($_GET['month'] !=''){
			$month = $_GET['month'];
			$year = date("Y");
			$first_month = $year."-".$month."-01 00:00:00";
			$query_date = $year."-".$month."-01";
			$end_month = date('Y-m-t 23:59:59', strtotime($query_date));
			$query->where("a.create_date >= '" . $first_month . "'");
			$query->where("a.create_date <= '" . $end_month . "'");
		}

		if($_GET['project'] > 0){

			$query->where("a.project_id = " . $_GET['project']);
		}

		// Filtering modified_date
		// Checking "_dateformat"
		$filter_modified_date_from = $this->state->get("filter.modified_date_from_dateformat");
		$filter_Qmodified_date_from = (!empty($filter_modified_date_from)) ? $this->isValidDate($filter_modified_date_from) : null;

		if ($filter_Qmodified_date_from != null)
		{
			$query->where("a.modified_date >= '" . $db->escape($filter_Qmodified_date_from) . "'");
		}

		$filter_modified_date_to = $this->state->get("filter.modified_date_to_dateformat");
		$filter_Qmodified_date_to = (!empty($filter_modified_date_to)) ? $this->isValidDate($filter_modified_date_to) : null ;

		if ($filter_Qmodified_date_to != null)
		{
			$query->where("a.modified_date <= '" . $db->escape($filter_Qmodified_date_to) . "'");
		}


		if(JFactory::getUser()->get('id') > 0){
			$query->where("a.created_by = " . $db->escape(JFactory::getUser()->get('id')) );
		}
		// Add the list ordering clause.
		$orderCol  = $this->state->get('list.ordering',"a.id");
		$orderDirn = $this->state->get('list.direction','DESC');

		if($orderCol == 'a.ordering'){
			$orderCol = 'a.id';
		}

		
		if ($orderCol && $orderDirn)
		{
			$query->order($db->escape($orderCol . ' ' . $orderDirn));
		}

		return $query;
	}

	/**
	 * Method to get an array of data items
	 *
	 * @return  mixed An array of data on success, false on failure.
	 */
	public function getItems()
	{
		$items = parent::getItems();


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
			$app->enqueueMessage(JText::_("COM_ORDER_SEARCH_FILTER_DATE_FORMAT"), "warning");
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
	public function getListProject(){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from($db->quoteName('#__projects'));
		$query->where($db->quoteName('state')." = 1");
		$db->setQuery($query);
		$result = $db->loadObjectList();
		return $result;
	}

	public function getTotalMoney($sale_id,$project_id,$from_date,$to_date){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('sum(total) as total');
		$query->from($db->quoteName('#__orders'));
		$query->where($db->quoteName('created_by')." = ".$sale_id);
		if($project_id > 0){
			$query->where($db->quoteName('project_id')." = ".$project_id);
		}
		if($from_date !='' && $to_date !=''){
			$query->where($db->quoteName('create_date')." >= '".$from_date."'");
			$query->where($db->quoteName('create_date')." <= '".$to_date."'");
		}
		$db->setQuery($query);
		$result = $db->loadResult();
		return $result;
	}

}
