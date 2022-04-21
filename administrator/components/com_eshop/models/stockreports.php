<?php
/**
 * @version        3.1.0
 * @package        Joomla
 * @subpackage    EShop
 * @author    Giang Dinh Truong
 * @copyright    Copyright (C) 2012 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die();

/**
 * Eshop Component Model
 *
 * @package        Joomla
 * @subpackage    EShop
 * @since 1.5
 */
class EShopModelStockreports extends EShopModelList
{

    function __construct($config)
    {
        $config['search_fields'] = array('a.order_number', 'a.firstname', 'a.lastname', 'a.email', 'a.payment_firstname', 'a.payment_lastname', 'a.shipping_firstname', 'a.shipping_lastname');
        $config['state_vars'] = array(
            'filter_order_Dir' => array('DESC', 'cmd', 1));
        parent::__construct($config);
        $this->mainTable = '#__eshop_orders';
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
        $select = array();
        $select[] = 'a.order_number';
        $select[] = 'a.customer_id';

        $select[] = 'a.firstname';
        $select[] = 'a.lastname';
        $select[] = 'a.created_date';
        $select[] = 'a.currency_code';
        $select[] = 'a.currency_exchanged_value';
        $select[] = 'a.channel';
//        $select[] = 'a.*';
//        $select[] = 'a.*';
//        $select[] = 'a.*';
//        $select[] = 'a.*';
//        $select[] = 'a.*';
        $select[] = 'a.cms_done_date';
        $select[] = 'a.order_status_id';
        $select[] = 'a.payment_status';
        $select[] = 'a.id';
        $select[] = 'a.modified_date';
        $select[] = 'a.total';
        $query->select(implode(',', $select))
            ->from('#__eshop_orders AS a');

        $where = $this->_buildContentWhereArray();

        if (count($where)) {
            $query->where($where);
        }
        //$query->group('p.order_id, p.stock_id');

        $orderby = $this->_buildContentOrderBy();

        if ($orderby != '') {
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
        if ($state->search) {
            $search = $db->quote('%' . $db->escape($state->search, true) . '%', false);

            if (is_array($this->searchFields)) {
                $whereOr = array();
                foreach ($this->searchFields as $titleField) {
                    $whereOr[] = " LOWER($titleField) LIKE " . $search;
                }
                $where[] = ' (' . implode(' OR ', $whereOr) . ') ';
            } else {
                $where[] = 'LOWER(' . $this->searchFields . ') LIKE ' . $db->quote('%' . $db->escape($state->search, true) . '%', false);
            }
        }


        $shippingMethod = $input->getString('shipping_method');

        if ($shippingMethod != '') {
            $where[] = 'a.id IN (SELECT order_id FROM #__eshop_ordertotals WHERE name = "shipping" AND title = ' . $db->quote($shippingMethod) . ')';
        }

        return $where;
    }


    public function getTotal()
    {
        // Lets load the content if it doesn't already exist
        if (empty($this->_total)) {
            $db = $this->getDbo();
            $where = $this->_buildContentWhereArray();
            $query = $db->getQuery(true);
            $query->select('COUNT(*)')
                ->from('#__eshop_orderproducts as p')
                ->join('LEFT', $this->mainTable . ' AS a  ON a.id = p.order_id')
                ->join('LEFT', '#__store AS s  ON s.id = p.stock_id');
            if (count($where))
                $query->where($where);

            $db->setQuery($query);
            $this->_total = $db->loadResult();
        }

        return $this->_total;
    }

    public function getReport()
    {
        $result = array();
        $db = $this->getDbo();
        $query = $db->getQuery(true);
        $where = $this->_buildContentWhereArray();
        $query->select('SUM(a.total)')
            ->from($this->mainTable . ' as a');
        if (count($where)) {
            $query->where($where);
        }


        $db->setQuery($query);
        $result['total'] = $db->loadResult();


        $query = $db->getQuery(true);
        $query->select('sum(a.total) as total, a.order_status_id, count(*) as `rows`')
            ->from($this->mainTable . ' as a');
        if (count($where)) {
            $query->where($where);
        }
        $query->group('a.order_status_id');

        $sql = 'select `rows` , sum(t.total) as `total`, t.order_status_id  from (' . $query->__toString() . ') as t group by t.order_status_id';
        $db->setQuery($sql);
        $statusReport = $db->loadAssocList();


        $query = $db->getQuery(true);
        $query->select('sum(a.total) as total, a.payment_status, count(*) as `rows`')
            ->from($this->mainTable . ' as a');
        if (count($where)) {
            $query->where($where);
        }
        $query->group('a.payment_status');

        $sql = 'select `rows` , sum(t.total) as `total`, t.payment_status  from (' . $query->__toString() . ') as t group by t.payment_status';
        $db->setQuery($sql);

        $paymentReport = $db->loadAssocList();

        $query = $db->getQuery(true);
        $query->select('o.id, d.orderstatus_name')
            ->from('#__eshop_orderstatuses as o')
            ->join('LEFT', '#__eshop_orderstatusdetails AS d  ON d.orderstatus_id = o.id')
            ->where('o.published = 1')
            ->order('o.ordering ASC');
        $db->setQuery($query);
        $statustList = $db->loadAssocList();

        $paymentList = array();
        $payment_status_text0 = '
        <div class="status-title"><a href="'. JURI::base().'index.php?option=com_eshop&view=stockreports&order_status_id=-1">Chưa thanh toán</a></div>
        <div class="status-title-desc"><i>Chờ tải ảnh CK</i></div>';
        $payment_status_text1 = '
        <div class="status-title"><a href="'. JURI::base().'index.php?option=com_eshop&view=stockreports&order_status_id=-2">Chưa thanh toán</a></div>
        <div class="status-title-desc"><i>Đã tải ảnh CK</i></div>';

        $payment_status_text9 = '<div class="status-title"><a href="'. JURI::base().'index.php?option=com_eshop&view=stockreports&order_status_id=-3">Đã thanh toán</a></div>';

        if ($paymentReport) {
            $payment_status_text = '';

            $miss0 = 1;
            $miss1 = 1;
            $miss9 = 1;


            foreach ($paymentReport as $index => $r) {
                if($r['payment_status'] == 0){
                  $payment_status_text = $payment_status_text0;
                  $miss0 = 0;
                }
                if($r['payment_status'] == 1){
                  $payment_status_text = $payment_status_text1;
                  $miss1 = 0;
                }
                if($r['payment_status'] == 9){
                  $payment_status_text = $payment_status_text9;
                  $miss9 = 0;
                }
                $paymentList[] = array(
                    'orderstatus_name' => $payment_status_text,
                    'rows' => $r['rows'],
                    'total' => $r['total'],
                );

            }

            if($miss0 == 1){
              $paymentList[] = array(
                  'orderstatus_name' => $payment_status_text0,
                  'rows' => 0,
                  'total' => 0,
              );
            }
            if($miss1 == 1){
              $paymentList[] = array(
                  'orderstatus_name' => $payment_status_text1,
                  'rows' => 0,
                  'total' => 0,
              );
            }

            if($miss9 == 1){
              $paymentList[] = array(
                  'orderstatus_name' => $payment_status_text9,
                  'rows' => 0,
                  'total' => 0,
              );
            }
        }
        //print_r($statustList);
        foreach ($statustList as &$item) {
            $item['total'] = 0;
            $item['rows'] = 0;

            if ($statusReport) {
                foreach ($statusReport as $r) {
                    if ($r['order_status_id'] == $item['id']) {
                        $item['total'] = $r['total'];
                        $item['rows'] = $r['rows'];
                        break;
                    }
                }
            }
            $item['orderstatus_name'] = '<div class="status-title"><a href="'.JURI::base().'index.php?option=com_eshop&view=stockreports&order_status_id='.$item[id].'">'.$item['orderstatus_name'].'</a></div>';
        }
        //print_r($paymentList);
        $result['statusList'] = array_merge($paymentList, $statustList);

        return $result;
    }

    public function getListAgents(){
  		$db    = $this->getDbo();
  		$query = $db->getQuery(true);
  		// Join over the group mapping table.

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
