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

class ShopCouponDao extends AbtractDao
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

    public function getCouponData($params = array())
    {

        $status = true;
        $db = \JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*')
            ->from('#__eshop_coupons')
            ->where('coupon_code = ' . $db->quote($params['code']))
            ->where('(coupon_start_date = "0000-00-00 00:00:00" OR coupon_start_date < NOW())')
            ->where('(coupon_end_date = "0000-00-00 00:00:00" OR coupon_end_date > NOW())')
            ->where('published = 1');
        $db->setQuery($query);

        $coupon = $db->loadObject();

        if (is_object($coupon)) {
            //Check coupon per customer
            if ($coupon->coupon_per_customer) {
                $user = \JFactory::getUser();

                if (!$user->get('id')) {
                    $status = false;
                } else {
                    $query->clear()
                        ->select('COUNT(*)')
                        ->from('#__eshop_couponhistory')
                        ->where('coupon_id = ' . intval($coupon->id))
                        ->where('user_id = ' . intval($user->get('id')));
                    $db->setQuery($query);

                    if ($db->loadResult() >= $coupon->coupon_per_customer) {
                        $status = false;
                    }
                }
            }

            //Check min price condition
            if ($coupon->coupon_min_total > $params['total']) {
                $status = false;
            }

            //Check number of used times condition
            if ($coupon->coupon_times) {
                $query->clear()
                    ->select('COUNT(*)')
                    ->from('#__eshop_couponhistory')
                    ->where('coupon_id = ' . intval($coupon->id));
                $db->setQuery($query);

                if ($db->loadResult() >= $coupon->coupon_times) {
                    $status = false;
                }
            }

            //Check total amount of used coupon condition
            if ($coupon->coupon_used > 0) {
                $query->clear()
                    ->select('ABS(SUM(amount))')
                    ->from('#__eshop_couponhistory')
                    ->where('coupon_id = ' . intval($coupon->id));
                $db->setQuery($query);

                if ($db->loadResult() >= $coupon->coupon_used) {
                    $status = false;
                }
            }

            //Check coupon based on products
            $query->clear()
                ->select('product_id')
                ->from('#__eshop_couponproducts')
                ->where('coupon_id = ' . intval($coupon->id));
            $db->setQuery($query);
            $couponProductsData = $db->loadColumn();

            if (count($couponProductsData)) {
                $couponProduct = false;
                foreach ($params['product_id'] as $id) {
                    if (in_array($id, $couponProductsData)) {
                        $couponProduct = true;
                        break;
                    }
                }

                if (!$couponProduct) {
                    $status = false;
                }
            }

            //Check coupon based on categories
            $query->clear()
                ->select('category_id')
                ->from('#__eshop_couponcategories')
                ->where('coupon_id = ' . intval($coupon->id));
            $db->setQuery($query);
            $couponCategoriesData = $db->loadColumn();
            $tempCouponCategoriesData = $couponCategoriesData;


            for ($i = 0; $n = count($tempCouponCategoriesData), $i < $n; $i++) {
                $couponCategoriesData = array_merge($couponCategoriesData, \EshopHelper::getAllChildCategories($tempCouponCategoriesData[$i]));
            }

            if (count($couponCategoriesData)) {

                foreach ($params['product_id'] as $id) {
                    $query->clear()
                        ->select('category_id')
                        ->from('#__eshop_productcategories')
                        ->where('product_id = ' . intval($id));
                    $db->setQuery($query);
                    $productCategoryIds = $db->loadColumn();

                    for ($i = 0; $n = count($productCategoryIds), $i < $n; $i++) {
                        if (in_array($productCategoryIds[$i], $couponCategoriesData)) {
                            $couponProduct = true;
                            $couponProductsData[] = $id;
                            break;
                        }
                    }
                }

                if (empty($couponProduct)) {
                    $status = false;
                }
            }

            //Check coupon based on customer groups
            $query->clear()
                ->select('customergroup_id')
                ->from('#__eshop_couponcustomergroups')
                ->where('coupon_id = ' . intval($coupon->id));
            $db->setQuery($query);
            $couponCustomerGroupsData = $db->loadColumn();

            if (count($couponCustomerGroupsData)) {
                $couponCustomerGroup = false;
                $user = \JFactory::getUser();

                if ($user->get('id')) {
                    $customer = new \EshopCustomer();
                    $customerGroupId = $customer->getCustomerGroupId();
                } else {
                    $customerGroupId = \EshopHelper::getConfigValue('customergroup_id');
                }

                if (in_array($customerGroupId, $couponCustomerGroupsData)) {
                    $couponCustomerGroup = true;
                }

                if (!$couponCustomerGroup) {
                    $status = false;
                }
            }

        } else {
            $status = false;
        }

        //Return
        if ($status) {
            return array(
                'coupon_id' => $coupon->id,
                'coupon_name' => $coupon->coupon_name,
                'coupon_code' => $coupon->coupon_code,
                'coupon_type' => $coupon->coupon_type,
                'coupon_value' => $coupon->coupon_value,
                'coupon_min_total' => $coupon->coupon_min_total,
                'coupon_start_date' => $coupon->coupon_start_date,
                'coupon_end_date' => $coupon->coupon_end_date,
                'coupon_shipping' => $coupon->coupon_shipping,
                'coupon_times' => $coupon->coupon_times,
                'coupon_used' => $coupon->coupon_used,
                'coupon_products_data' => $couponProductsData);
        } else {
            return array();
        }
    }

    /**
     *
     * Function to get coupon information
     *
     * @param string $code
     *
     * @return stdClass coupon object
     */
    function getCouponInfo($code)
    {
        $db = \JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*')
            ->from('#__eshop_coupons')
            ->where('coupon_code = ' . $db->quote($code))
            ->where('(coupon_start_date = "0000-00-00 00:00:00" OR coupon_start_date < NOW())')
            ->where('(coupon_end_date = "0000-00-00 00:00:00" OR coupon_end_date > NOW())')
            ->where('published = 1');
        $db->setQuery($query);

        return $db->loadObject();
    }


}
