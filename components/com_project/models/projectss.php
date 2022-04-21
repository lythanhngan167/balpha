<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Project
 * @author     tung hoang <tungvacc@gmail.com>
 * @copyright  2017 tung hoang
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Project records.
 *
 * @since  1.6
 */
class ProjectModelProjectss extends JModelList
{
    /**
     * Constructor.
     *
     * @param array $config An optional associative array of configuration settings.
     *
     * @see        JController
     * @since      1.6
     */
    public function __construct($config = array())
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'id', 'a.id',
                'title', 'a.title',
                'short_description', 'a.short_description',
                'description', 'a.description',
                'file_1', 'a.file_1',
                'file_2', 'a.file_2',
                'file_3', 'a.file_3',
                'file_4', 'a.file_4',
                'file_5', 'a.file_5',
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
     * @param string $ordering Elements order
     * @param string $direction Order direction
     *
     * @return void
     *
     * @throws Exception
     *
     * @since    1.6
     */
    protected function populateState($ordering = null, $direction = null)
    {
        $app = JFactory::getApplication();
        $list = $app->getUserState($this->context . '.list');

        $ordering = isset($list['filter_order']) ? $list['filter_order'] : null;
        $direction = isset($list['filter_order_Dir']) ? $list['filter_order_Dir'] : null;

        $list['limit'] = (int)JFactory::getConfig()->get('list_limit', 20);
        $list['start'] = $app->input->getInt('start', 0);
        $list['ordering'] = $ordering;
        $list['direction'] = $direction;

        $app->setUserState($this->context . '.list', $list);
        $app->input->set('list', null);

        // List state information.
        parent::populateState($ordering, $direction);

        $app = JFactory::getApplication();

        $ordering = $app->getUserStateFromRequest($this->context . '.ordercol', 'filter_order', $ordering);
        $direction = $app->getUserStateFromRequest($this->context . '.orderdirn', 'filter_order_Dir', $ordering);

        $this->setState('list.ordering', $ordering);
        $this->setState('list.direction', $direction);

        $start = $app->getUserStateFromRequest($this->context . '.limitstart', 'limitstart', 0, 'int');
        $limit = $app->getUserStateFromRequest($this->context . '.limit', 'limit', 0, 'int');

        if ($limit == 0) {
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
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        // Select the required fields from the table.
        $query
            ->select(
                $this->getState(
                    'list.select', 'DISTINCT a.*'
                )
            );

        $query->from('`#__projects` AS a');

        // Join over the users for the checked out user.
        $query->select('uc.name AS uEditor');
        $query->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');

        // Join over the created by field 'created_by'
        $query->join('LEFT', '#__users AS created_by ON created_by.id = a.created_by');

        // Join over the created by field 'modified_by'
        $query->join('LEFT', '#__users AS modified_by ON modified_by.id = a.modified_by');

        if (!JFactory::getUser()->authorise('core.edit', 'com_project')) {
            $query->where('a.state = 1');
        }

        // Filter by search in title
        $search = $this->getState('filter.search');

        if (!empty($search)) {
            if (stripos($search, 'id:') === 0) {
                $query->where('a.id = ' . (int)substr($search, 3));
            } else {
                $search = $db->Quote('%' . $db->escape($search, true) . '%');
                $query->where('( a.title LIKE ' . $search . '  OR  a.short_description LIKE ' . $search . '  OR  a.description LIKE ' . $search . ' )');
            }
        }


        // Filtering create_date
        // Checking "_dateformat"
        $filter_create_date_from = $this->state->get("filter.create_date_from_dateformat");
        $filter_Qcreate_date_from = (!empty($filter_create_date_from)) ? $this->isValidDate($filter_create_date_from) : null;

        if ($filter_Qcreate_date_from != null) {
            $query->where("a.create_date >= '" . $db->escape($filter_Qcreate_date_from) . "'");
        }

        $filter_create_date_to = $this->state->get("filter.create_date_to_dateformat");
        $filter_Qcreate_date_to = (!empty($filter_create_date_to)) ? $this->isValidDate($filter_create_date_to) : null;

        if ($filter_Qcreate_date_to != null) {
            $query->where("a.create_date <= '" . $db->escape($filter_Qcreate_date_to) . "'");
        }

        // Add the list ordering clause.
        $orderCol = $this->state->get('list.ordering');
        $orderDirn = $this->state->get('list.direction');

        if ($orderCol && $orderDirn) {
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
        $app = JFactory::getApplication();
        $filters = $app->getUserState($this->context . '.filter', array());
        $error_dateformat = false;

        foreach ($filters as $key => $value) {
            if (strpos($key, '_dateformat') && !empty($value) && $this->isValidDate($value) == null) {
                $filters[$key] = '';
                $error_dateformat = true;
            }
        }

        if ($error_dateformat) {
            $app->enqueueMessage(JText::_("COM_PROJECT_SEARCH_FILTER_DATE_FORMAT"), "warning");
            $app->setUserState($this->context . '.filter', $filters);
        }

        return parent::loadFormData();
    }

    /**
     * Checks if a given date is valid and in a specified format (YYYY-MM-DD)
     *
     * @param string $date Date to be checked
     *
     * @return bool
     */
    private function isValidDate($date)
    {
        $date = str_replace('/', '-', $date);
        return (date_create($date)) ? JFactory::getDate($date)->format("Y-m-d") : null;
    }

    public function getCountByCat($project_id, $cat_id)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('count(*) as number');
        $query->from($db->quoteName('#__customers'));
        $query->where($db->quoteName('project_id') . " = " . $project_id, 'AND');
        if($cat_id == DATA_RETURN){
            $query->where('(payback = 1 OR sale_id = 0)', "AND");
        }
        $query->where($db->quoteName('category_id') . " = " . $cat_id, "AND");
        $query->where($db->quoteName('state') . " = 1");
        $db->setQuery($query);
        $result = $db->loadResult();
        return $result;
    }

    public function createOrder($data)
    {
        $order = new stdClass();
        $order->category_id = $data['category_id'];
        $order->quantity = $data['quantity'];
        $order->price = $data['price'];
        $order->total = $data['price'] * $data['quantity'];
        $order->project_id = $data['project_id'];
        $order->state = 1;
        $order->list_customer = $data['list_customer'];
        $order->created_by = $data['created_by'];
        $order->modified_by = $data['created_by'];
        $order->create_date = date("Y-m-d H:i:s");
        $order->modified_date = date("Y-m-d H:i:s");


        // Insert the object into the user profile table.
        $result = JFactory::getDbo()->insertObject('#__orders', $order);
        if ($result) {

            $id = JFactory::getDbo()->insertid();
            $custList = explode(',', $order->list_customer);
            $insert = array();
            foreach ($custList as $uid){
                $insert[] = '('.$id.','.$uid.')';
            }
            $sql = 'INSERT INTO #__track_log (order_id, user_id) VALUES '.implode(',', $insert);
            JFactory::getDbo()->setQuery($sql)->execute();


            return $id;
        } else {
            return 0;
        }
    }

    public function getRandomCustomer($projectid, $catid, $quantity)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from($db->quoteName('#__customers'));
        $query->where('( `sale_id` = 0 OR (`sale_id` <> 0 AND `payback` = 1))', 'AND');
        $query->where($db->quoteName('category_id') . " = " . $catid, 'AND');
        $query->where($db->quoteName('project_id') . " = " . $projectid, 'AND');
        //$query->where($db->quoteName('on_hold') . " = 0", 'AND');
        //$query->where('( `status_id` = 1 OR (`status_id` <> 1 AND `payback` = 1))', 'AND');
        $query->where($db->quoteName('state') . " = 1");
        $query->order('RAND() LIMIT ' . $quantity);
        //$db->setQuery($query);
        //echo $query->__toString();die;
        $db->setQuery($query->__toString() . ' FOR UPDATE');
        $result = $db->loadObjectList();
        return $result;
    }

    public function holdCustomerForSale($saleid, $customerid)
    {
        // Insert the object into the user profile table.
        $sale = new stdClass();

        $sale->id = $customerid;
        $sale->hold_user = $saleid;
        $sale->on_hold = 1;
        if($this->checkReadytoHoldCustomerForSale($customerid)){
          // Update their details in the users table using id as the primary key.
          $result = JFactory::getDbo()->updateObject('#__customers', $sale, 'id');
          if ($result) {
              return 1;
          } else {
              return 0;
          }
        }

    }

    public function unHoldCustomerForSale($saleid, $customerid)
    {
        // Insert the object into the user profile table.
        $sale = new stdClass();

        $sale->id = $customerid;
        $sale->hold_user = 0;
        $sale->on_hold = 0;
        if($this->checkReadytoUnHoldCustomerForSale($saleid,$customerid)){
          // Update their details in the users table using id as the primary key.
          $result = JFactory::getDbo()->updateObject('#__customers', $sale, 'id');
          if ($result) {
              return 1;
          } else {
              return 0;
          }
        }

    }

    public function checkReadytoUnHoldCustomerForSale($saleid,$customerid)
    {
      $db = JFactory::getDbo();
      $query = $db->getQuery(true);
      $query->select('count(*)');
      $query->from($db->quoteName('#__customers'));
      $query->where($db->quoteName('id') . " = " . $customerid);
      $query->where($db->quoteName('hold_user') . " = " . $saleid);
      $db->setQuery($query);
      $result = $db->loadResult();
      if($result == 1){
        return 1;
      }else{
        return 0;
      }
    }

    public function checkReadytoHoldCustomerForSale($customerid)
    {
      $db = JFactory::getDbo();
      $query = $db->getQuery(true);
      $query->select('*');
      $query->from($db->quoteName('#__customers'));
      $query->where($db->quoteName('id') . " = " . $customerid);
      $db->setQuery($query);
      $result = $db->loadObject();

      if($result->on_hold == 1 || $result->sale_id > 0){
        return 0;
      }else{
        return 1;
      }
    }

    public function asignCustomerToSale($saleid, $customerid)
    {
        // Insert the object into the user profile table.
        $sale = new stdClass();

        $sale->id = $customerid;
        $sale->sale_id = $saleid;
        $sale->modified_date = date("Y-m-d H:i:s");
        $sale->buy_date = date("Y-m-d H:i:s");
        $sale->payback = 0;
        $sale->status_id = 1;

        // Update their details in the users table using id as the primary key.
        $result = JFactory::getDbo()->updateObject('#__customers', $sale, 'id');
        if ($result) {
            return 1;
        } else {
            return 0;
        }
    }

    public function getCountCustomerDataToday($userid, $projectid, $catid)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select('count(*)');
        $query->from($db->quoteName('#__customers'));
        //$query->where($db->quoteName('project_id') . " = " . $projectid, "AND");
        $query->where($db->quoteName('category_id') . " = " . $catid, "AND");
        $query->where($db->quoteName('sale_id') . " = " . $userid, "AND");
        $query->where("buy_date >= '" . date("Y-m-d 00:00:00") . "'", "AND");
        $query->where("buy_date <= '" . date("Y-m-d 23:59:59") . "'", "AND");
        //$query->where($db->quoteName('status_id')." = '1'");
        //echo $query->__toString();
        $db->setQuery($query);
        $result = $db->loadResult();
        return $result;
    }

    public function getCaringCustomer($userid, $projectid)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('count(*)');
        $query->from($db->quoteName('#__customers'));
        $query->where($db->quoteName('sale_id') . " = " . $userid, 'AND');
        $query->where($db->quoteName('project_id') . " = " . $projectid, 'AND');
        $query->where("(status_id = 2 OR status_id = 3 OR status_id = 4)");
        $db->setQuery($query);
        $result = $db->loadResult();
        return $result;
    }

    public function getTotalCaring($userid)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('count(*) as total');
        $query->from($db->quoteName('#__customers'));
        $query->where($db->quoteName('sale_id') . " = " . $userid);
        $query->where($db->quoteName('status_id') . " IN (1,2,3,4)");
        $db->setQuery($query);
        $result = $db->loadResult();
        return $result;
    }

    public function setAutoBuy($userid,$on_off){
      $sale = new stdClass();
      $sale->id = $userid;
      $sale->autobuy = $on_off;
      $result = JFactory::getDbo()->updateObject('#__users', $sale, 'id');
      if ($result) {
          return 1;
      } else {
          return 0;
      }

    }

    public function checkAssignForSale($customerid)
    {
      $db = JFactory::getDbo();
      $query = $db->getQuery(true);
      $query->select('*');
      $query->from($db->quoteName('#__customers'));
      $query->where($db->quoteName('id') . " = " . $customerid);
      $db->setQuery($query);
      $result = $db->loadObject();

      if($result->sale_id == 0){
        return 1;
      }else{
        return 0;
      }
    }

    public function getMaxPickByCat($cat_id, $level)
    {

        if ($level == 1) {
            $levelid = 167;
        }
        if ($level == 2) {
            $levelid = 168;
        }
        if ($level == 3) {
            $levelid = 169;
        }
        if ($level == 4) {
            $levelid = 170;
        }
        if ($level == 5) {
            $levelid = 171;
        }

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('maxpick');
        $query->from($db->quoteName('#__maxpick_level'));
        $query->where($db->quoteName('level') . " = " . $levelid);
        $query->where($db->quoteName('category_customer') . " = " . $cat_id);
        $db->setQuery($query);

        $result = $db->loadResult();
        if ($result <= 0) {
            $config = JFactory::getConfig();
            $app = JFactory::getApplication();
            $cparams = $app->getParams('com_maxpick_level');
            $maxpickdefault = $cparams->get('maxpickdefault');
            return $maxpickdefault;
        } else {
            return $result;
        }
    }

    public function updateBuyAll($userid,$number){
      $db = JFactory::getDbo();
      $sql = "UPDATE #__users set buyall = buyall + " . $number . "  WHERE id = " . $userid;
      $result = $db->setQuery($sql)->execute();
      if ($result) {
          return 1;
      } else {
          return 0;
      }
    }

    // $query->select('id');
    // $query->from($db->quoteName('#__users'));
    // $query->where($db->quoteName('id_biznet') . " = '". $params['IDNguoiTuyenDung']."'");
    // $db->setQuery($query);
    // $userId = $db->loadResult();
}
