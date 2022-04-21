<?php
/**
 * Created by PhpStorm.
 * User: lvson
 * Date: 4/26/2019
 * Time: 10:53 AM
 */

namespace api\model\dao\shop;

use api\model\AbtractDao;
use api\model\biz\shop\ShopPaymentBiz;

class ShopPaymentDao extends AbtractDao
{
    public $select = array(
        'id',
        'name',
        'title',
        'params'
    );

    public function getTable()
    {
        return '#__eshop_payments';
    }

    public function getPayments($params = array())
    {
        $paramsDefault = array(
            'select' => $this->select,
            'where' => array(
                'published = 1'
            ),
            'order' => 'ordering ASC'
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
                $biz = new ShopPaymentBiz();
                $biz->setAttributes($item);
                $list[] = $biz;
            }
        }   
        return $list;
    }



}
