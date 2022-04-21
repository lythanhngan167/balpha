<?php
/**
 * Created by PhpStorm.
 * User: lvson
 * Date: 4/26/2019
 * Time: 10:53 AM
 */

namespace api\model\dao\shop;

use api\model\AbtractDao;
use api\model\biz\shop\ShopOrderProductBiz;

class ShopOrderProductDao extends AbtractDao
{
    public $select = array(
        'o.id',
        'o.product_id',
        'o.product_name',
        'o.product_sku',
        'o.quantity',
        'o.price',
        'o.total_price',
        'o.tax',
        'od.orderstatus_name',
        'o.status_id',
        'p.product_image',
        'o.note'
    );

    public function getTable()
    {
        return '#__eshop_orderproducts';
    }

    public function getProducts($params = array())
    {
        $paramsDefault = array(
            'as' => 'o',
            'select' => $this->select,
            'where' => array(),
            'join' => array(
                array(
                    'type' => 'LEFT',
                    'with_table' => '#__eshop_products AS p ON p.id = o.product_id'
                ),
                array(
                    'type' => 'LEFT',
                    'with_table' => '#__eshop_orderstatusdetails AS od ON od.orderstatus_id = o.status_id'
                )
            ),

        );
        if (isset($params['where']) && $params['where']) {
            foreach ($params['where'] as $item) {
                $paramsDefault['where'][] = $item;
            }
        }
        if($params){
            foreach ($params as $k => $item) {
                if($k === 'where'){
                    continue;
                }
                $paramsDefault[$k] = $item;
            }
        }
        $result = $this->getList($paramsDefault);
        $list = array();
        if($result){
            foreach($result as $item){
                $biz = new ShopOrderProductBiz();
                $biz->setAttributes($item);
                $list[] = $biz;
            }
        }
        return $list;
    }

   

}
