<?php
/**
 * Created by PhpStorm.
 * User: lvson
 * Date: 4/26/2019
 * Time: 10:53 AM
 */

namespace api\model\dao\shop;

use api\model\AbtractDao;
use api\model\biz\shop\ShopOrderStatusBiz;

class ShopOrderStatusDao extends AbtractDao
{
    public $select = array(
        'o.id',
        'd.orderstatus_name as name'
    );

    public function getTable()
    {
        return '#__eshop_orderstatuses';
    }

    public function getOrderStatus($params = array())
    {
        $paramsDefault = array(
            'as' => 'o',
            'no_quote' => true,
            'select' => implode(',',$this->select),
            'where' => array(
                'published = 1'
            ),
            'join' => array(
                array(
                    'type' => 'LEFT',
                    'with_table' => '#__eshop_orderstatusdetails AS d ON d.orderstatus_id = o.id'
                )
            ),
            'order' => 'o.ordering ASC'

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

        // $biz = new ShopOrderStatusBiz();
        // $biz->setAttributes(array(
        //     'id' => -1,
        //     'name' => 'Chờ tải ảnh CK'
        // ));
        // $list[] = $biz;
        //
        // $biz = new ShopOrderStatusBiz();
        // $biz->setAttributes(array(
        //     'id' => -2,
        //     'name' => 'Đã tải ảnh CK'
        // ));
        // $list[] = $biz;


        // $biz = new ShopOrderStatusBiz();
        // $biz->setAttributes(array(
        //     'id' => -3,
        //     'name' => 'Đã thanh toán'
        // ));
        // $list[] = $biz;


        if($result){
            foreach($result as $item){
                $biz = new ShopOrderStatusBiz();
                $biz->setAttributes($item);
                $list[] = $biz;
            }
        }
        return $list;
    }



}
