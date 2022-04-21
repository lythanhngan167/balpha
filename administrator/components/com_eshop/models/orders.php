<?php
/**
 * @version		3.1.0
 * @package		Joomla
 * @subpackage	EShop
 * @author  	Giang Dinh Truong
 * @copyright	Copyright (C) 2012 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die();

/**
 * Eshop Component Model
 *
 * @package		Joomla
 * @subpackage	EShop
 * @since 1.5
 */
class EShopModelOrders extends EShopModelList
{
	function __construct($config)
	{
		$config['search_fields'] = array('order_number', 'firstname', 'lastname', 'email', 'payment_firstname', 'payment_lastname', 'shipping_firstname', 'shipping_lastname');
		$config['state_vars'] = array(
			'filter_order_Dir' => array('DESC', 'cmd', 1));
		parent::__construct($config);
	}

	/**
	 * Basic build Query function.
	 * The child class must override it if it is necessary
	 *
	 * @return string
	 */
	public function _buildQuery()
	{
		$db = $this->getDbo();
		$state = $this->getState();
		$query = $db->getQuery(true);
        $date = JFactory::getDate()->toSql();
		$query->select('a.*, IF(a.payment_status = 0 AND datediff( \'' . $date . '\', a.created_date) > '.ESHOP_PAYMENT_OVER.', 1, 0) as pay_note, IF(a.payment_status = 0  AND ( a.last_email is NULL OR datediff( \'' . $date . '\', a.last_email) > '.ESHOP_SEND_EMAIL_DATE.'), 1, 0) as email_note')
			->from($this->mainTable . ' AS a ');

		$where = $this->_buildContentWhereArray();

		if (count($where))
		{
			$query->where($where);
		}

		$orderby = $this->_buildContentOrderBy();

		if ($orderby != '')
		{
			$query->order($orderby);
		}

		//echo $query->__toString();
		return $query;
	}

	private function _parseDate($inputDate)
	{
			//var_dump($inputDate);
			$tmp = explode('/', $inputDate);
			return $tmp[2] . '-' . $tmp[1] . '-' . $tmp[0];
	}
	/**
	 * Build an where clause array
	 *
	 * @return array
	 */
	public function _buildContentWhereArray()
	{
		$input = JFactory::getApplication()->input;
		$db = $this->getDbo();
		$state = $this->getState();
		$where = array();

		//$paymentStatus = $input->getInt('payment_status', 0);
		$orderStatusId = $input->getInt('order_status_id', 0);
		$agent_id = $input->getInt('agents', 0);

		if($agent_id > 0){
			$where[] = 'a.customer_id = '.$agent_id;
		}
		if ($orderStatusId) {
				if ($orderStatusId > 0) {
						$where[] = 'a.order_status_id = ' . intval($orderStatusId);
				} else {
						if ($orderStatusId == -1) {
								$where[] = 'a.payment_status = 0';
						} elseif ($orderStatusId == -2) {
								$where[] = 'a.payment_status = 1';
						}elseif ($orderStatusId == -3) {
								$where[] = 'a.payment_status = 9';
						}
				}

		}

		$datarange = $input->get('daterange', '', 'STRING');
		if ($datarange != '') {
				$dateFilter = explode(' - ', $datarange);
				if (empty($dateFilter[0])) {
						$today = date('Y-m-d');
						$where[] = 'DATE_FORMAT(a.created_date, \'%Y-%m-%d\') = ' . $db->quote($today);
				} else {
						$startDate = $this->_parseDate($dateFilter[0]);
						$where[] = 'DATE_FORMAT(a.created_date, \'%Y-%m-%d\') >= ' . $db->quote($startDate);
						if ($dateFilter[1]) {
								$endDate = $this->_parseDate($dateFilter[1]);
								$where[] = 'DATE_FORMAT(a.created_date, \'%Y-%m-%d\') <= ' . $db->quote($endDate);
						}
				}
		}

    if ($paymentStatus)
    {
        $pStatus = $paymentStatus == 2 ? 0 : $paymentStatus;
        if($pStatus == 3){
            $where[] = 'a.payment_status = 0';
            $date = JFactory::getDate()->toSql();
            $where[] = ' datediff( \'' . $date . '\', a.created_date) > 2';
        }else{
            $where[] = 'a.payment_status = ' . intval($pStatus);
        }

    }

		if ($state->search)
		{
			$search = $db->quote('%' . $db->escape($state->search, true) . '%', false);

			if (is_array($this->searchFields))
			{
				$whereOr = array();
				foreach ($this->searchFields as $titleField)
				{
					$whereOr[] = " LOWER($titleField) LIKE " . $search;
				}
				$where[] = ' (' . implode(' OR ', $whereOr) . ') ';
			}
			else
			{
				$where[] = 'LOWER(' . $this->searchFields . ') LIKE ' . $db->quote('%' . $db->escape($state->search, true) . '%', false);
			}
		}

		$shippingMethod = $input->getString('shipping_method');

		if ($shippingMethod != '')
		{
		    $where[] = 'a.id IN (SELECT order_id FROM #__eshop_ordertotals WHERE name = "shipping" AND title = ' . $db->quote($shippingMethod) .  ')';
		}

		$user   = JFactory::getUser();
		$groups = $user->get('groups');
		$group_13 = 0;
		foreach ($groups as $group)
		{
				if($group == 13){
					$group_13 = 1;
				}
		}

		if ($user->id > 0 && $group_13 == 1) {
				$where[] = 'a.merchant_id = ' . intval($user->id);
		}

		return $where;
	}

	public function getListAgents(){
		$db    = $this->getDbo();
		$query = $db->getQuery(true);

		$query->select('us.id as value, CONCAT(us.name," - ",us.username) as text')
			->from('#__users AS us')
			->join('LEFT', '#__user_usergroup_map AS ug ON ug.user_id = us.id')
			->where("(ug.group_id = 10 || ug.group_id = 2)")
			->where("us.level > 0")
			->where("us.block = 0");
			// Join over the user groups table.
		$db->setQuery($query);
		$result = $db->loadObjectList();
		return $result;
	}
}
