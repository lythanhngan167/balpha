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
use api\model\dao\shop\ShopCustomerDao;
use api\model\SUtil;

class ShopAddressDao extends AbtractDao
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
        return '#__eshop_addresses';
    }

    public function getAddress($params = array())
    {
        $paramsDefault = array(
            'as' => 'ad',
            'no_quote' => true,
            'select' => implode(',', $this->select),
            'where' => array(),
            'join' => array(
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
        $result = $this->getList($paramsDefault);
        $list = array();
        if ($result) {
            $defaultAddress = $this->getDefaultAddress();
            foreach ($result as $item) {
                $biz = new ShopAddressBiz();
                $item['is_default'] = $item['id'] == $defaultAddress ? 1 : 0;
                $biz->setAttributes($item);
                $list[] = $biz;
            }
        }
        return $list;
    }

    public function getDefaultAddress()
    {
        $user = \JFactory::getUser();
        $paramsDefault = array(
            'no_quote' => true,
            'table' => '#__eshop_customers',
            'select' => 'address_id',
            'where' => array(
                'customer_id = ' . (int)$user->get('id')
            )
        );
        $result = $this->get($paramsDefault);
        return $result['address_id'];
    }

    public function upsert($params)
    {
        $address = new \stdClass();
        $address->id = @$params['id'];
        $address->customer_id = $params['customer_id'];
        $address->firstname = $params['firstname'];
        $address->lastname = $params['lastname'];
        $address->email = $params['email'];
        $address->telephone = $params['telephone'];
        $address->address_1 = $params['address_1'];
        $address->country_id = $params['country_id'];
        $address->zone_id = $params['zone_id'];
        if (@$params['id'] > 0) {
            $address->modified_date = date("Y-m-d H:i:s");
            // Insert the object into the user profile table.
            $result = $this->db->updateObject($this->getTable(), $address, 'id');
        } else {
            $address->created_date = date("Y-m-d H:i:s");
            // Insert the object into the user profile table.
            $result = $this->db->insertObject($this->getTable(), $address);
            $params['id'] = $this->db->insertid();
        }

        /*if(!$address->lng){
            $address->id = $params['id'];
            $fullAddress = SUtil::getFullAddress($params['id']);
            $longlatInfo = SUtil::getLonLatFromAddress($fullAddress);
            $address->lat = $longlatInfo['lat'];
            $address->lng = $longlatInfo['lng'];
            $result = $this->db->updateObject($this->getTable(), $address, 'id');
        }*/

        if (@$params['is_default'] == 1) {
            $cusDao = new ShopCustomerDao();
            $params = array(
                'user_id' => $address->customer_id,
                'address_id' => $params['id']
            );
            $cusDao->setDefaultAddress($params);
        }
        return $result;
    }


}
