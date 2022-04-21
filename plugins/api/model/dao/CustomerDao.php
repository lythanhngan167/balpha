<?php
/**
 * Created by PhpStorm.
 * User: lvson
 * Date: 4/26/2019
 * Time: 10:53 AM
 */

namespace api\model\dao;

use api\model\AbtractDao;
use api\model\biz\CustomerBiz;
use api\model\dao\ReturnStoreDao;
use api\model\dao\NotesDao;

class CustomerDao extends AbtractDao
{
    public $select = array(
        'id',
        'name',
        'phone',
        'place',
        'email',
        'category_id',
        'project_id',
        'status_id',
        'total_revenue',
        'create_date',
        'buy_date',
        'modified_date',
        'rating_id',
        'rating_note',
        'trash_confirmed_by_dm'
    );

    public function getTable()
    {
        return '#__customers';
    }

    public function getCustomers($params = array())
    {
        $select = 'o.id,
        o.name,
        o.phone,
        o.place,
        o.email,
        o.category_id,
        o.project_id, 
        o.status_id,
        o.total_revenue,
        o.create_date,
        o.buy_date,
        o.modified_date, 
        p.title as project_name, 
        c.title as category_name, 
        d.country_name as country_name,
        o.rating_id,
        o.rating_note,
        o.trash_confirmed_by_dm,
        o.trash_approve';
        $paramsDefault = array(
            'as' => 'o',
            'no_quote' => true,
            'select' => $select,
            'where' => array(
                'o.state = 1'
            ),
            'join' => array(
                array(
                    'type' => 'LEFT',
                    'with_table' => '#__projects AS p ON p.id = o.project_id'
                ),
                array(
                    'type' => 'LEFT',
                    'with_table' => '#__categories AS c ON c.id = o.category_id'
                ),
                array(
                    'type' => 'LEFT',
                    'with_table' => '#__eshop_countries AS d ON d.id = o.province'
                )
            )
        );
        if (isset($params['where']) && $params['where']) {
            foreach ($params['where'] as $item) {
                $paramsDefault['where'][] = $item;
            }
        }
        if ($params) {
            foreach ($params as $k => $item) {
                if ($k === 'where') {
                    continue;
                }
                $paramsDefault[$k] = $item;
            }
        }
        $list = array();
        $result = $this->getList($paramsDefault);

        if ($result) {
            $ids = array();
            foreach ($result as $item) {
                $ids[] = $item['id'];
            }
            $notes = $this->getNote($ids);
            foreach ($result as $item) {
                $biz = new CustomerBiz();
                if (isset($notes[$item['id']])) {
                    $item['note'] = $notes[$item['id']];
                }
                $biz->setAttributes($item);
                $list[] = $biz;
            }
        }
        return $list;
    }

    public function getNote($ids)
    {
        $dao = new NotesDao();
        $params = array(
            'as' => 'c',
            'no_quote' => true,
            'select' => 'c.create_date, c.note, c.id, c.custommer_id',
            'where' => array(
                'c.custommer_id IN (' . implode(',', $ids) . ')',
                'c.id = ( SELECT max(a.id) from #__notes as  a WHERE a.custommer_id = c.custommer_id)'
            )
        );
        $result = $dao->getList($params);
        $data = array();
        if ($result) {
            foreach ($result as $item) {
                $data[$item['custommer_id']] = $item;
            }
        }
        return $data;
    }

    public function getCaringCustomer($params = array())
    {
        $paramsDefault = array(
            'is_count' => true,
            'where' => array(
                'sale_id = ' . (int)$params['user_id'],
                'project_id = ' . (int)$params['project_id'],
                'status_id IN (2,3,4)'
            )
        );
        $result = $this->get($paramsDefault);
        return $result['num'];
    }

    public function getCountCustomerDataToday($params = array())
    {
        $paramsDefault = array(
            'is_count' => true,
            'where' => array(
                'sale_id = ' . (int)$params['user_id'],
                'project_id = ' . (int)$params['project_id'],
                'category_id = ' . (int)$params['cat_id'],
                'DATE_FORMAT(buy_date, "%Y-%m-%d") = \'' . date('Y-m-d') . '\''
            )
        );
        $result = $this->get($paramsDefault);
        return $result['num'];

    }


    public function getCountByCat($params = array())
    {

        $paramsDefault = array(
            'is_count' => true,
            'where' => array(
                'state = 1',
                'project_id = ' . (int)$params['project_id'],
                'category_id = ' . (int)$params['cat_id']
            )
        );
        $result = $this->get($paramsDefault);
        return $result['num'];
    }

    public function getRandomCustomers($params = array())
    {

        $paramsDefault = array(
            'no_quote' => true,
            'select' => 'id',
            'where' => array(
                'sale_id = 0',
                'status_id = 1',
                'state = 1',
                'project_id = ' . (int)$params['project_id'],
                'category_id = ' . (int)$params['cat_id']
            ),
            'order' => 'RAND()',
            'limit' => $params['limit']
        );
        $list = array();
        $result = $this->getList($paramsDefault);
        if ($result) {
            foreach ($result as $item) {
                $list[] = $item['id'];
            }
        }
        return implode(',', $list);
    }

    public function updateSale($params = array())
    {

    }

    public function updateStatus($params = array())
    {
        // Insert the object into the user profile table.
        $status = new \stdClass();
        if ($params['status_id'] == 7) {
            $status->total_revenue = $params['total_revenue'];
        }

        if ($params['status_id'] == 6 || $params['status_id'] == 8) {
            if ($params['customer_id'] > 0) {
                $customerInfo = $this->get(array('where' => array(
                    'id' => $params['customer_id']
                )));
                $returnStore = array();
                $returnStore['user_id'] = $customerInfo['sale_id'];
                $returnStore['customer_id'] = $customerInfo['id'];
                $returnStore['category_id'] = $customerInfo['category_id'];
                $returnStore['project_id'] = $customerInfo['project_id'];
                $returnStore['status_id'] = $customerInfo['status_id'];
                $returnStore['buy_date'] = $customerInfo['buy_date'];
                $returnStore['status_return_cancel'] = $params['status_id'];
                $returnDao = new ReturnStoreDao();
                $returnDao->insert($returnStore);
            }
            $status->id = $params['customer_id'];
            $status->status_id = 1;
            $status->sale_id = 0;
            $status->modified_date = date("Y-m-d H:i:s");
            $status->total_revenue = 0;
            if ($params['status_id'] == 6) {
                $status->category_id = 150;
            }
            if ($params['status_id'] == 8) {
                $status->category_id = 161;
            }
        } else {
            $status->id = $params['customer_id'];
            $status->status_id = $params['status_id'];
            $status->modified_date = date("Y-m-d H:i:s");
        }
        if(isset($params['rating_id'])){
            $status->rating_id = $params['rating_id'];
        }else{
            $status->rating_id="";
        }

        if(isset($params['rating_note'])){
            $status->rating_note = $params['rating_note'];
        }else{
            $status->rating_note = "";
        }

        if(isset($params['trash_confirmed'])){
            $status->trash_confirmed_by_dm = $params['trash_confirmed'];
        }else{
            $status->trash_confirmed_by_dm = "";
        }

        return $this->db->updateObject($this->getTable(), $status, 'id');
    }

    public function report($params = array())
    {

        return $this->getList($params);
    }

    public function getTotalRevenue($params = array())
    {
        $params = array(
            'no_quote' => true,
            'select' => 'sum(total_revenue) as num',
            'where' => $params['where']
        );
        $result = $this->get($params);
        return $result['num'];
    }

    public function getTotalMoney($params = array())
    {
        $params = array(
            'no_quote' => true,
            'table' => '#__orders',
            'select' => 'sum(total) as num',
            'where' => $params['where']
        );
        $result = $this->get($params);
        return $result['num'];
    }


}
