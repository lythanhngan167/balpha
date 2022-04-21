<?php
/**
 * Created by PhpStorm.
 * User: lvson
 * Date: 4/26/2019
 * Time: 10:53 AM
 */

namespace api\model\dao\shop;

use api\model\AbtractDao;

use api\model\biz\shop\ShopDiscountBiz;

require_once(JPATH_SITE . '/components/com_eshop/helpers/customer.php');

class ShopProductOptionDiscountDao extends AbtractDao
{
    public $select = array(
        'ov.product_option_id',
        'ov.option_id',
        'ov.sku',
        'od.value AS `text`',
        'ov.price',
        'ov.`price_sign`',
        'ov.`price_type`',
        'ov.image',
        'ov.id'
    );

    public function getTable()
    {
        return '#__eshop_productoptionvalues';
    }


    /**
     *
     * Function to get configuration object
     */
    public static function getConfig()
    {
        static $config;

        if (is_null($config)) {
            $config = new \stdClass();
            $db = \JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select('config_key, config_value')
                ->from('#__eshop_configs');
            $db->setQuery($query);
            $rows = $db->loadObjectList();

            foreach ($rows as $row) {
                $config->{$row->config_key} = $row->config_value;
            }
        }

        return $config;
    }

    /**
     *
     * Function to get value of configuration variable
     *
     * @param string $configKey
     * @param string $default
     *
     * @return string
     */
    public static function getConfigValue($configKey, $default = null)
    {
        $config = self::getConfig();

        if (isset($config->{$configKey})) {
            return $config->{$configKey};
        }

        return $default;
    }

    public function getDiscount($productId)
    {
        $user = \JFactory::getUser();

        if ($user->get('id')) {
            $customer = new \EshopCustomer();
            $customerGroupId = $customer->getCustomerGroupId();
        } else {
            $customerGroupId = self::getConfigValue('customergroup_id');
        }

        if (!$customerGroupId) {
            $customerGroupId = 0;
        }

        $db = \JFactory::getDbo();
        $query = $db->getQuery(true);

        // Check for product discount
        $query->select('a.id')
            ->from('#__eshop_discounts AS a')
            ->innerJoin('#__eshop_discountelements AS b ON a.id = b.discount_id')
            ->where('a.published = 1')
            ->where('b.element_type = "product" AND (b.element_id = ' . intval($productId) . ' OR b.element_id = 0)')
            ->order('a.id DESC');
        $db->setQuery($query, 0, 1);
        $discountId = $db->loadResult();

        if (!$discountId) {
            // Check for product categories and manufacturers
            $query->clear()
                ->select('a.id')
                ->from('#__eshop_discounts AS a')
                ->innerJoin('#__eshop_discountelements AS b ON a.id = b.discount_id')
                ->where('a.published = 1')
                ->where('(b.element_type = "manufacturer" AND (b.element_id = (SELECT manufacturer_id FROM #__eshop_products WHERE id = ' . intval($productId) . ') OR b.element_id = 0)) OR (b.element_type = "category" AND (b.element_id IN (SELECT category_id FROM #__eshop_productcategories WHERE product_id = ' . intval($productId) . ')  OR b.element_id = 0))')
                ->order('a.id DESC');
            $db->setQuery($query, 0, 1);
            $discountId = $db->loadResult();
        }

        if ($discountId) {
            $currentDate = $db->quote(\JHtml::_('date', 'Now', 'Y-m-d', null));
            $query->clear()
                ->select('*')
                ->from('#__eshop_discounts')
                ->where('id = ' . intval($discountId))
                ->where('published = 1')
                ->where('((discount_customergroups = "") OR (discount_customergroups IS NULL) OR (discount_customergroups = "' . $customerGroupId . '") OR (discount_customergroups LIKE "' . $customerGroupId . ',%") OR (discount_customergroups LIKE "%,' . $customerGroupId . ',%") OR (discount_customergroups LIKE "%,' . $customerGroupId . '"))')
                ->where('(discount_start_date = "0000-00-00 00:00:00" OR DATE(discount_start_date) <= ' . $currentDate . ')')
                ->where('(discount_end_date = "0000-00-00 00:00:00" OR DATE(discount_end_date) >= ' . $currentDate . ')');
            $db->setQuery($query);
            $row = $db->loadObject();
            $biz = new ShopDiscountBiz();
            $biz->setAttributes($row);
            return $biz;
        }

        return null;
    }
}
