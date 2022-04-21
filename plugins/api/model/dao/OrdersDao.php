<?php
/**
 * Created by PhpStorm.
 * User: lvson
 * Date: 4/26/2019
 * Time: 10:53 AM
 */

namespace api\model\dao;

use api\model\AbtractDao;
use api\model\biz\OrderBiz;

class OrdersDao extends AbtractDao
{
    public $select = array(
        'id',
        'category_id',
        'quantity',
        'price',
        'total',
        'project_id',
        'create_date'
    );

    public function getTable()
    {
        return '#__orders';
    }

    public function getHistory($params = array())
    {
        $paramsDefault = array(
            'as' => 'o',
            'no_quote' => true,
            'select' => 'o.id, o.category_id, o.quantity, o.price, o.total, o.project_id, o.create_date, p.title as project_name, c.title as category_name',
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
            foreach ($result as $item) {
                $biz = new OrderBiz();
                $biz->setAttributes($item);
                $list[] = $biz;
            }
        }
        return $list;
    }


}
