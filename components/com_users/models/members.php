<?php

/**
 * @version    CVS: 1.0.0
 * @package    com_users
 * @author     tung hoang <tungvacc@gmail.com>
 * @copyright  2017 tung hoang
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');
require_once(JPATH_SITE . '/components/com_eshop/helpers/helper.php');

/**
 * Methods supporting a list of Customer records.
 *
 * @since  1.6
 */
class UsersModelMembers extends JModelList
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

		$ordering  = isset($list['filter_membersLevel'])     ? $list['filter_order']     : null;
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
		$app  = JFactory::getApplication();
		$db    = $this->getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select('a.name, a.level_tree, a.id, a.approved, a.id_biznet, a.province, DATE_FORMAT(a.registerDate, \'%d/%m/%Y\') as registerDate');

		$query->from($db->quoteName('#__users') . ' AS a');

		$query->where('a.block = 0');

		$query->join('RIGHT', '#__user_usergroup_map AS m ON ( m.user_id = a.id AND m.group_id = 3)');

		$clear = $app->input->get('clear');
		if(isset($clear) && (int)$clear === 0) {
			$this->setState('filter.membersLevel', 0);
		}

		// Filter members level
		$membersLevel = $this->getState('filter.membersLevel');

		if (!empty($membersLevel) && (int)$membersLevel !== 0)
		{
			$query->where($db->quoteName('a.level_tree') .'=' .$db->quote($membersLevel));
		}

		$user_id = $app->input->get('id');
		if(!empty($user_id)) {
			$query->where($db->quoteName('a.invited_id') . '=' . $db->quote($user_id));
			$query->where($db->quoteName('a.id') . '<>' . $db->quote($user_id));
		} else {
			$user = JFactory::getUser();
			if($user->id > 0) {
				$query->where($db->quoteName('a.invited_id') . '=' . $db->quote($user->id));
				$query->where($db->quoteName('a.id') . '<>' . $db->quote($user->id));
			}
			
		}

		// Add the list ordering clause.
		$query->order('a.id DESC');

		return $query;
	}
	/**
	 * Method to get an array of data items
	 *
	 * @return  mixed An array of data on success, false on failure.
	 */
	public function getItems()
	{
		// Get a storage key.
		$store = $this->getStoreId();

		// Try to load the data from internal storage.
		if (empty($this->cache[$store]))
		{
			$groups  = $this->getState('filter.groups');
			$groupId = $this->getState('filter.group_id');

			if (isset($groups) && (empty($groups) || $groupId && !in_array($groupId, $groups)))
			{
				$items = array();
			}
			else
			{
				$items = parent::getItems();
			}

			// Bail out on an error or empty list.
			if (empty($items))
			{
				$this->cache[$store] = $items;

				return $items;
			}

			$month = date('m');
            $year = date ('Y');

			// Second pass: collect the group counts into the master items array.
			foreach ($items as &$item)
			{
				if(isset($item->province)) {
					$item->province = $this->getProvinceName($item->province);
				}
				// Get revenue amount
				$item->am_revenue = \EshopHelper::getRevenueAmount($item->id, $month, $year, 'individual');
				// $item['m_id'] = $item['level_tree'] . str_pad($item['id'], 6, "0", STR_PAD_LEFT);
				$item->m_id = $item->id_biznet;
				$item->level = $item->level_tree ? 'Cấp '.$item->level_tree : '';
			}

			// Add the items to the internal cache.
			$this->cache[$store] = $items;
		}

		return $this->cache[$store];
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

	public function getProvinceName($pro_id)
	{
		if($pro_id > 0){
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);

			$query->select('ec.*');
			$query->from('`#__eshop_countries` AS ec');
			$query->where('ec.id = '.$pro_id);
			$db->setQuery($query);
			$result = $db->loadObject();
			return $result->country_name;
		}else{
			return '';
		}

	}

}
