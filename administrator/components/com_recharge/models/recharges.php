<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Recharge
 * @author     nganly <lythanhngan167@gmail.com>
 * @copyright  2019 nganly
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');
jimport( 'joomla.access.access' );

/**
 * Methods supporting a list of Recharge records.
 *
 * @since  1.6
 */
class RechargeModelRecharges extends \Joomla\CMS\MVC\Model\ListModel
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
				'state', 'a.`state`',
				'created_by', 'a.`created_by`',
				'modified_by', 'a.`modified_by`',
				'bank_name', 'a.`bank_name`',
				'amount', 'a.`amount`',
				'note', 'a.`note`',
				'status', 'a.`status`',
				'image', 'a.`image`',
				'created_time', 'a.`created_time`',
				'code', 'a.`code`',
				'type', 'a.`type`',
				'updated_time', 'a.`updated_time`',
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
		$query->from('`#__recharge` AS a');

		// Join over the users for the checked out user
		$query->select("uc.name AS uEditor");
		$query->join("LEFT", "#__users AS uc ON uc.id=a.checked_out");

		// Join over the user field 'created_by'
		$query->select('`created_by`.name AS `created_by`, `created_by`.username AS `created_by_username`, `created_by`.id AS `created_by_id`');
		$query->join('LEFT', '#__users AS `created_by` ON `created_by`.id = a.`created_by`');

		// Join over the user field 'created_by'
		$query->select('`sbdm`.name AS `sbdm_name`, `sbdm`.username AS `sbdm_username`');
		$query->join('LEFT', '#__users AS `sbdm` ON `sbdm`.id = a.`sbdm_id`');

		// Join over the user field 'modified_by'
		$query->select('`modified_by`.name AS `modified_by`');
		$query->join('LEFT', '#__users AS `modified_by` ON `modified_by`.id = a.`modified_by`');

		$user = JFactory::getUser();
		$userGroup = JAccess::getGroupsByUser($user->id, false);

		if((int)$userGroup[0] === 15) {
			$query->where($db->quoteName('a.sbdm_id') . '=' . $db->quote($user->id));
		}


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
				if($search != ''){
					$query->where("(created_by.username = '".$search."' OR created_by.id_biznet = '".$search."' OR a.code LIKE '%".$search."%' )");
				}

				// $search = $db->Quote('%' . $db->escape($search, true) . '%');
				// $query->where('( a.bank_name LIKE ' . $search . '  OR  a.status LIKE ' . $search . '  OR  a.code LIKE ' . $search . ' )');

			}
		}


		// Filtering status
		$filter_status = $this->state->get("filter.status");

		if ($filter_status !== null && (is_numeric($filter_status) || !empty($filter_status)))
		{
			$query->where("a.`status` = '".$db->escape($filter_status)."'");
		}

		// Filtering type
		$filter_type = $this->state->get("filter.type");

		if ($filter_type !== null && (is_numeric($filter_type) || !empty($filter_type)))
		{
			$query->where("a.`type` = '".$db->escape($filter_type)."'");
		}

		// Filtering created_by
		$filter_created_by = $this->state->get("filter.created_by");
		if ($filter_created_by !== null && !empty($filter_created_by))
		{
			$query->where("a.`created_by` = '".$db->escape($filter_created_by)."'");
		}

		// Add the list ordering clause.
		$orderCol  = $this->state->get('list.ordering', 'id');
		$orderDirn = $this->state->get('list.direction', 'DESC');

		if ($orderCol && $orderDirn)
		{
			$query->order($db->escape($orderCol . ' ' . $orderDirn));
		}

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
    //print_r($items);
		foreach ($items as $oneItem)
		{

				// Get the title of every option selected.

				$options = explode(',', $oneItem->bank_name);

				$options_text = array();

				foreach ((array) $options as $option)
				{
					$options_text[] = JText::_('COM_RECHARGE_RECHARGES_BANK_NAME_OPTION_' . strtoupper($option));
				}

				$oneItem->bank_name = !empty($options_text) ? implode(',', $options_text) : $oneItem->bank_name;
					$oneItem->status = JText::_('COM_RECHARGE_RECHARGES_STATUS_OPTION_' . strtoupper($oneItem->status));
					$oneItem->type = JText::_('COM_RECHARGE_RECHARGES_TYPE_OPTION_' . strtoupper($oneItem->type));
		}

		return $items;
	}

  public function updateStatus($data)
	{
		// Insert the object into the user profile table.
		$recharge = new stdClass();

		$recharge->id = $data['id'];
		$recharge->status = $data['status'];
    $recharge->modified_by = $data['user_id'];
		//$color->modified_date = date("Y-m-d H:i:s");

		// Must be a valid primary key value.


		// Update their details in the users table using id as the primary key.
		$result = JFactory::getDbo()->updateObject('#__recharge', $recharge, 'id');
    if($result & $data['status'] == 'confirmed'){
			$userSaleHistory   = $this->getUserByID($data['uid']);
      // inscrease money
      $db = JFactory::getDbo();
      $sql = "UPDATE #__users set money = money + " . (int)$data['amount'] . ' WHERE id = ' . $data['uid'];
      $change_money = $db->setQuery($sql)->execute();
      if($change_money){
        $user = JFactory::getUser();
        $obj = new stdClass();
        $obj->state = 1;
        $obj->created_by = $data['uid'];
        $obj->title = 'Nạp BizXu #' . $data['code'];
        $obj->amount = $data['amount'];
        $obj->created_date = date('Y-m-d H:i:s');
        $obj->type_transaction = 'charge';
        $obj->status = 'completed';
        $obj->reference_id = $data['code'];

				if($data['uid'] > 0){
					$obj->current_money = $userSaleHistory->money - $data['amount'];
					$obj->current_money_before_operation = $userSaleHistory->money;
				}

        $db->insertObject('#__transaction_history', $obj, 'id');
      }
    }
		if($result){
			return 1;
		}else{
			return 0;
		}
	}

	public function getUserByID($user_id)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from($db->quoteName('#__users'));
		$query->where($db->quoteName('id') . " = '" .$user_id."'");
		$db->setQuery($query);
		$result = $db->loadObject();
		return $result;
	}

}
