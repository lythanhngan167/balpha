<?php
/**
 * Created by PhpStorm.
 * User: lvson
 * Date: 4/26/2019
 * Time: 10:53 AM
 */

namespace api\model\dao\shop;

use api\model\AbtractDao;
use api\model\biz\shop\ShopOrderDetailBiz;

class ShopOrderDetailDao extends AbtractDao
{
    public $select = array(
        'o.id',
        'CONCAT_WS(\' \', o.shipping_firstname,o.shipping_lastname) AS shipping_name',
        'o.shipping_telephone',
        'CONCAT_WS(\', \', o.shipping_address_1, o.shipping_address_2, o.shipping_zone_name, o.shipping_country_name) AS ship_address',
        'o.order_number',
        'o.order_status_id',
        'o.created_date',
        'o.total',
        "CONCAT(u.level_tree, LPAD(u.id,6,'0'), '(',u.name, ')') as customer_name",
        'o.comment',
        'o.payment_status',
        'o.payment_image',
        'o.payment_method_title'
    );

    public function getTable()
    {
        return '#__eshop_orders';
    }

    public function getOrdeInfo($params = array())
    {
        $user = \JFactory::getUser();

        if($user->get('level_tree') == 1){
            $this->select[] = 'IF(o.customer_id <> '.$user->id.', 1, 0)as can_approve';
        }else{
            $this->select[] = '0 as can_approve';
        }

        $this->select[] = 'IF(o.customer_id = '.$user->id.', 1, 0)as can_upload';
        if (@$params['select']) {
            $this->select = array_merge($this->select, $params['select']);
        }
        $this->select[] = 'pa.params';
        $paramsDefault = array(
            'as' => 'o',
            'no_quote' => true,
            'select' => implode(',', $this->select),
            'where' => array(),
            'join' => array(
                array(
                    'type' => 'LEFT',
                    'with_table' => '#__eshop_orderstatusdetails AS od ON od.orderstatus_id = o.order_status_id'
                ),
                array(
                    'type' => 'LEFT',
                    'with_table' => '#__eshop_payments AS pa ON pa.name = o.payment_method'
                ),
                array(
                    'type' => 'LEFT',
                    'with_table' => '#__users AS u ON u.id = o.customer_id'
                )
            ),
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
                    foreach ($item as $table) {
                        $paramsDefault['join'][] = $table;
                    }
                    //$paramsDefault['join'] = array_merge($paramsDefault['join'], $item);
                } else {
                    $paramsDefault[$k] = $item;
                }
            }
        }
        $result = $this->get($paramsDefault);
        $list = array();
        if ($result) {
            $biz = new ShopOrderDetailBiz();
            $result['options'] = $this->getOrderAttribute($result['id']);
            $biz->setAttributes($result);
            $list = $biz;
        }
        return $list;
    }

    public function getOrderAttribute($id)
    {
        $list = array();
        $paramsDefault = array(
            'no_quote' => true,
            'table' => '#__eshop_orderoptions',
            'select' => 'order_product_id, option_name, GROUP_CONCAT( option_value SEPARATOR  \',\' ) AS option_value',
            'where' => array(
                'order_id = ' . (int)$id
            ),
            'group by' => 'product_option_id'
        );
        $result = $this->getList($paramsDefault);
        if ($result) {
            foreach ($result as $item) {
                if (!isset($list[$item['order_product_id']])) {
                    $list[$item['order_product_id']] = array();
                }
                $list[$item['order_product_id']][] = $item;
            }
        }
        return $list;
    }


}
