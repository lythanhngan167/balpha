<?php
/**
 * Created by PhpStorm.
 * User: lvson
 * Date: 4/26/2019
 * Time: 10:53 AM
 */

namespace api\model\dao\shop;

use api\model\AbtractDao;
use api\model\biz\shop\ShopCountryBiz;
use api\model\biz\ProjectBiz;

class ShopCountryDao extends AbtractDao
{
    public $select = array(
        'id',
        'country_name'
    );

    public function getTable()
    {
        return '#__eshop_countries';
    }

    public function getCountry($params = array())
    {
        $paramsDefault = array(
            'as' => 'ad',
            'no_quote' => true,
            'select' => implode(',',$this->select),
            'where' => array(
                'published = 1'
            )
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
                $biz = new ShopCountryBiz();
                $biz->setAttributes($item);
                $list[] = $biz;
            }
        }   
        return $list;
    }


}
