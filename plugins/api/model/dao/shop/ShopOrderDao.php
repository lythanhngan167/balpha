<?php
/**
 * Created by PhpStorm.
 * User: lvson
 * Date: 4/26/2019
 * Time: 10:53 AM
 */

namespace api\model\dao\shop;

use api\model\AbtractDao;
use api\model\biz\shop\ShopOrderBiz;

class ShopOrderDao extends AbtractDao
{


    public $select = array(
        'o.id',
        'o.order_number',
        'o.created_date',
        'o.payment_status',
        'o.order_status_id',
        'o.payment_image',
        "CONCAT(u.level_tree, LPAD(u.id,6,'0'), '(',u.name, ')') as customer_name"

    );

    public function getTable()
    {
        return '#__eshop_orders';
    }

    public function getOrders($params = array())
    {
        $user = \JFactory::getUser();
        $approved = $user->get('level_tree', 0) == '1' ? 1 : 0;
        $this->select[] = $approved.' as can_approve';
        $this->select[] = 'IF(o.customer_id = '.$user->id.', 1, 0)as can_upload';
        if (@$params['select']) {
            $this->select = array_merge($this->select, $params['select']);
        }
        $paramsDefault = array(
            'as' => 'o',
            'no_quote' => true,
            'select' => implode(',', $this->select),
            'where' => array(),
            'join' => array(),
            'order' => 'o.id DESC'

        );
        if (isset($params['where']) && $params['where']) {
            foreach ($params['where'] as $item) {
                $paramsDefault['where'][] = $item;
            }
        }
        if ($params) {
            foreach ($params as $k => $item) {
                if ($k === 'where' || $k === 'select') {
                    continue;
                } elseif ($k === 'join') {
                    $paramsDefault['join'] = array_merge($item, $paramsDefault['join']);
                } else {
                    $paramsDefault[$k] = $item;
                }

            }
        }
        $result = $this->getList($paramsDefault);
        $list = array();
        if ($result) {
            foreach ($result as $item) {
                $biz = new ShopOrderBiz();
                $biz->setAttributes($item);
                $list[] = $biz;
            }
        }
        return $list;
    }


}
