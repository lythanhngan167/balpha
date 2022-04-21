<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Userlogs
 * @author     Minh Thái Thi <thiminhthaichoigame@gmail.com>
 * @copyright  2020 Minh Thái Thi
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

use \Joomla\CMS\Helper\TagsHelper;
use \Joomla\CMS\Factory;
use \Joomla\CMS\Language\Text;
use \Joomla\Utilities\ArrayHelper;
/**
 * Methods supporting a list of Userlogs records.
 *
 * @since  1.6
 */
class UserlogsModelUserlogs extends \Joomla\CMS\MVC\Model\ListModel
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
				'ordering', 'a.`ordering`',
				'created_by', 'a.`created_by`',
				'modified_by', 'a.`modified_by`',
				'status', 'a.`status`',
				'agent_id', 'a.`agent_id`',
				'user_id', 'a.`user_id`',
				'old_level', 'a.`old_level`',
				'new_level', 'a.`new_level`',
				'created_date', 'a.`created_date`',
				'state', 'a.`state`',
				'type', 'a.`type`',
				'transfer_id', 'a.`transfer_id`',
				'customer_id', 'a.`customer_id`',
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
        // List state information.
        parent::populateState('id', 'DESC');

        $context = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
        $this->setState('filter.search', $context);

        // Split context into component and optional section
        $parts = FieldsHelper::extract($context);

        if ($parts)
        {
            $this->setState('filter.component', $parts[0]);
            $this->setState('filter.section', $parts[1]);
        }
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
		$query->from('`#__userlogs` AS a');
                
		// Join over the users for the checked out user
		$query->select("uc.name AS uEditor");
		$query->join("LEFT", "#__users AS uc ON uc.id=a.checked_out");

		// Join over the user field 'created_by'
		$query->select('`created_by`.name AS `created_by`');
		$query->join('LEFT', '#__users AS `created_by` ON `created_by`.id = a.`created_by`');

		// Join over the user field 'modified_by'
		$query->select('`modified_by`.name AS `modified_by`');
		$query->join('LEFT', '#__users AS `modified_by` ON `modified_by`.id = a.`modified_by`');
		

		$logType = $this->getState('list.type');
		if($logType) {
			$query->where($db->quoteName('a.type') .'='. $logType);
		}


		// Filter by published state
		$published = $this->getState('filter.state');

		if (is_numeric($published))
		{
			$query->where('a.state = ' . (int) $published);
		}
		elseif (empty($published))
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
				
			}
		}
                

		// Filtering type
		$filter_type = $this->state->get("filter.type");

		if ($filter_type !== null && !empty($filter_type))
		{
			$query->where("a.`type` = '".$db->escape($filter_type)."'");
		}
		// Add the list ordering clause.
		$orderCol  = $this->state->get('list.ordering', 'id');
		$orderDirn = $this->state->get('list.direction', 'DESC');

		if ($orderCol && $orderDirn)
		{
			$query->order($db->escape($orderCol . ' ' . $orderDirn));
		}

		//XXX_CUSTOM_ORDER_FOR_NESTED

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

			if (isset($oneItem->type))
			{
				$db    = JFactory::getDbo();
				$query = $db->getQuery(true);

				$query
					->select($db->quoteName('title'))
					->from($db->quoteName('#__categories'))
					->where('FIND_IN_SET(' . $db->quoteName('id') . ', ' . $db->quote($oneItem->type) . ')');

				$db->setQuery($query);
				$result = $db->loadColumn();

				$oneItem->type = !empty($result) ? implode(', ', $result) : '';

				$transfer = JFactory::getUser($oneItem->transfer_id);
				$oneItem->transferName = $transfer->name;
				$oneItem->transferUsername = $transfer->username;

				$agent = JFactory::getUser($oneItem->agent_id);
				$oneItem->agentName = $agent->name;
				$oneItem->agentUsername = $agent->username;

				$query->clear();
				$query
					->select($db->quoteName(array('name', 'phone')))
					->from($db->quoteName('#__customers'))
					->where($db->quoteName('id').' = '.$db->quote($oneItem->customer_id));

				$db->setQuery($query);
				$customer = $db->loadObject();
				$oneItem->customerName = $customer->name;
				$oneItem->customerPhone = $customer->phone;

				if($oneItem->user_id > 0) {
					$user = JFactory::getUser($oneItem->user_id);
					$oneItem->username = $user->username;
					$oneItem->name = $user->name;
				}
			}
		}

		return $items;
	}
}
