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
class EShopModelProductreports extends EShopModelList
{

    function __construct($config)
    {
        $config['search_fields'] = array('op.product_name', 'op.product_sku');
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
        $select[] = 'op.product_name';
        $select[] = 'op.product_sku';
        $select[] = 'p.product_price as price';
        $select[] = 'SUM(op.quantity) as num';
        $select[] = 'w.weight_name';
        $select[] = 'm.manufacturer_id';
        $select[] = 'm.manufacturer_name';

        $query->select(implode(',', $select))
            ->from('#__eshop_orders AS a')
            ->join('LEFT', '#__eshop_orderproducts AS op ON (a.id = op.order_id)')
            ->join('LEFT', '#__eshop_products AS p ON (p.id = op.product_id)')
            ->join('LEFT', '#__eshop_manufacturerdetails AS m ON (m.manufacturer_id = p.manufacturer_id and m.`language` = \'vi-VN\' )')
            ->join('LEFT', '#__eshop_weightdetails AS w ON (w.weight_id = p.product_weight_id AND w.`language` = \'vi-VN\' )');

        $where = $this->_buildContentWhereArray();

        if (count($where)) {
            $query->where($where);
        }
        $query->group('p.id');

        $orderby = $this->_buildContentOrderBy();

        if ($orderby != '') {
            $query->order($orderby);
        }
        //echo $query->__toString(); die;

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
        
        $where[] = 'a.order_status_id <> 1';
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
        }else{
            $today = date('Y-m-d');
                $where[] = 'DATE_FORMAT(a.created_date, \'%Y-%m-%d\') = ' . $db->quote($today);
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


        return $where;
    }

    public function getTotal()
    {
        // Lets load the content if it doesn't already exist


        return 100;
    }


    
}
