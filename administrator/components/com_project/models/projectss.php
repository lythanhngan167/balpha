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
				'title', 'a.`title`',
				'short_description', 'a.`short_description`',
				'description', 'a.`description`',
				'file_1', 'a.`file_1`',
				'file_2', 'a.`file_2`',
				'file_3', 'a.`file_3`',
				'file_4', 'a.`file_4`',
				'file_5', 'a.`file_5`',
				'ordering', 'a.`ordering`',
				'state', 'a.`state`',
				'created_by', 'a.`created_by`',
				'modified_by', 'a.`modified_by`',
				'create_date', 'a.`create_date`',
				'modified_date', 'a.`modified_date`',
				'remain_customer'
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
		// Filtering create_date
		$this->setState('filter.create_date.from', $app->getUserStateFromRequest($this->context.'.filter.create_date.from', 'filter_from_create_date', '', 'string'));
		$this->setState('filter.create_date.to', $app->getUserStateFromRequest($this->context.'.filter.create_date.to', 'filter_to_create_date', '', 'string'));


		// Load the parameters.
		$params = JComponentHelper::getParams('com_project');
		$this->setState('params', $params);
		// List state information.
		parent::populateState('a.id', 'DESC');
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
		$query->from('`#__projects` AS a');

		// Join over the users for the checked out user
		$query->select("uc.name AS uEditor");
		$query->select('(SELECT COUNT(*) FROM `#__customers` WHERE `sale_id` = 0 AND `state` = 1 AND `project_id` = a.id AND category_id = 151 ) as remain_customer');
		$query->join("LEFT", "#__users AS uc ON uc.id=a.checked_out");

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
				$query->where('( a.title LIKE ' . $search . '  OR  a.short_description LIKE ' . $search . '  OR  a.description LIKE ' . $search . ' )');
			}
		}


		// Filtering create_date
		$filter_create_date_from = $this->state->get("filter.create_date.from");

		if ($filter_create_date_from !== null && !empty($filter_create_date_from))
		{
			$query->where("a.`create_date` >= '".$db->escape($filter_create_date_from)."'");
		}
		$filter_create_date_to = $this->state->get("filter.create_date.to");

		if ($filter_create_date_to !== null  && !empty($filter_create_date_to))
		{
			$query->where("a.`create_date` <= '".$db->escape($filter_create_date_to)."'");
		}
		// Add the list ordering clause.
		$orderCol  = $this->state->get('list.ordering');
		$orderDirn = $this->state->get('list.direction');

		if ($orderCol && $orderDirn)
		{

			$query->order($db->escape($orderCol . ' ' . $orderDirn));
		}
        //echo $query->__toString();
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


		return $items;
	}
}
