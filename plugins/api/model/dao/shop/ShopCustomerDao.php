<?php
/**
 * Created by PhpStorm.
 * User: lvson
 * Date: 4/26/2019
 * Time: 10:53 AM
 */

namespace api\model\dao\shop;

use api\model\AbtractDao;
use api\model\biz\shop\ShopAddressBiz;
class ShopCustomerDao extends AbtractDao
{
    public $select = array(
        'ad.id',
        'ad.firstname as name',
        'ad.telephone as phone',
        'ad.address_1',
        'ad.country_id',
        'ad.zone_id',
        'z.zone_name',
        'c.country_name',
        'CONCAT_WS(\', \', ad.address_1, z.zone_name, c.country_name) as address'
    );

    public function getTable()
    {
        return '#__eshop_customers';
    }

    public function setDefaultAddress($params = array())
    {
        $paramsDefault = array(
            'set' => array(
                'address_id = '.(int)$params['address_id']
            ),
            'where' => array(
                'customer_id = '.(int)$params['user_id']
            ),

        );
        return $this->update($paramsDefault);
    }

    public function getDefaultAddress($params = array()){
        $paramsDefault = array(
            'as' => 'b',
            'no_quote' => true,
            'select' => implode(',', $this->select),
            'where' => array(),
            'join' => array(
                array(
                    'type' => 'LEFT',
                    'with_table' => '#__eshop_addresses AS ad ON ad.id = b.address_id'
                ),
                array(
                    'type' => 'LEFT',
                    'with_table' => '#__eshop_countries AS c ON c.id = ad.country_id'
                ),
                array(
                    'type' => 'LEFT',
                    'with_table' => '#__eshop_zones AS z ON z.id = ad.zone_id'
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
        $result = $this->get($paramsDefault);
        if($result){
            $bean = new ShopAddressBiz();
            $bean->setAttributes($result);
            return $bean;
        }
        return array();
    }

}
