<?php
/**
 * @version        3.1.0
 * @package        Joomla
 * @subpackage    EShop
 * @author    Giang Dinh Truong
 * @copyright    Copyright (C) 2012 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die();

class os_payments
{
    public static $methods;

    /**
     * Get list of payment methods
     *
     * @return array
     */
    public static function getPaymentMethods()
    {
        if (self::$methods == null) {
            $session = JFactory::getSession();
            $user = JFactory::getUser();
            if ($user->get('id') && $session->get('payment_address_id')) {
                $paymentAddress = EshopHelper::getAddress($session->get('payment_address_id'));
            } else {
                $guest = $session->get('guest');
                $paymentAddress = isset($guest['payment']) ? $guest['payment'] : '';
            }
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $shippingMethod = $session->get('shipping_method');
            if (is_array($shippingMethod)) {
                $shippingMethodName = $shippingMethod['name'];
                $shippingMethodNameArr = explode('.', $shippingMethodName);
                $query->select('params')
                    ->from('#__eshop_shippings')
                    ->where('name = "' . $shippingMethodNameArr[0] . '"');
                $db->setQuery($query);
                $params = $db->loadResult();
                if ($params) {
                    $params = new JRegistry($params);
                    $paymentMethods = $params->get('payment_methods');
                }
            }
            $query->clear();
            $query->select('*')
                ->from('#__eshop_payments')
                ->where('published = 1')
                ->order('ordering');
            if (isset($paymentMethods)) {
                $query->where('id IN (' . implode(',', $paymentMethods) . ')');
            }
            $db->setQuery($query);
            $rows = $db->loadObjectList();
            foreach ($rows as $row) {
                if (file_exists(JPATH_ROOT . '/components/com_eshop/plugins/payment/' . $row->name . '.php')) {
                    require_once JPATH_ROOT . '/components/com_eshop/plugins/payment/' . $row->name . '.php';
                    $params = new JRegistry($row->params);
                    $status = true;

                    if ($params->get('geozone_id', '0') && isset($paymentAddress['country_id'])) {
                        $query->clear();
                        $query->select('COUNT(*)')
                            ->from('#__eshop_geozonezones')
                            ->where('geozone_id = ' . intval($params->get('geozone_id')))
                            ->where('country_id = ' . intval($paymentAddress['country_id']));

                        if (isset($paymentAddress['zone_id'])) {
                            $query->where('(zone_id = 0 OR zone_id = ' . intval($paymentAddress['zone_id']) . ')');
                        } else {
                            $query->where('zone_id = 0');
                        }

                        $db->setQuery($query);

                        if (!$db->loadResult()) {
                            $status = false;
                        }
                    }
                    //Check customer groups
                    $customerGroups = $params->get('customer_groups');
                    if (count($customerGroups)) {
                        $user = JFactory::getUser();
                        if ($user->get('id')) {
                            $customer = new EshopCustomer();
                            $customerGroupId = $customer->getCustomerGroupId();
                        } else {
                            $customerGroupId = EshopHelper::getConfigValue('customergroup_id');
                        }
                        if (!$customerGroupId)
                            $customerGroupId = 0;
                        if (!in_array($customerGroupId, $customerGroups)) {
                            $status = false;
                        }
                    }
                    if ($status) {
                        $method = new $row->name($params);
                        $method->setTitle(JText::_($row->title));
                        $iconUri = '';
                        $baseUri = JUri::base(true);
                        $icon = $params->get('icon');
                        if ($icon != '') {
                            if (file_exists(JPATH_ROOT . '/media/com_eshop/payments/' . $icon)) {
                                $iconUri = $baseUri . '/media/com_eshop/payments/' . $icon;
                            } elseif (file_exists(JPATH_ROOT . '/' . $icon)) {
                                $iconUri = $baseUri . '/' . $icon;
                            }
                        }
                        $method->iconUri = $iconUri;
                        if ($method->name == 'os_onepay') {
                            if ((!empty($params->get('domestic_merchant_id'))
                                    && !empty($params->get('domestic_access_code'))
                                    && !empty($params->get('domestic_token')))
                                || $params->get('enviroment') == 'dev') {
                                $domestic = clone $method;
                                $domestic->setTitle($params->get('domestic_name'));
                                $domestic->name .= '_domestic';
                                self::$methods[] = $domestic;
                            }
                            if ((!empty($params->get('international_merchant_id'))
                                    && !empty($params->get('international_access_code'))
                                    && !empty($params->get('international_token')))
                                || $params->get('enviroment') == 'dev') {
                                $international = clone $method;
                                $international->setTitle($params->get('international_name'));
                                $international->name .= '_international';
                                self::$methods[] = $international;
                            }

                        } else {
                            self::$methods[] = $method;
                        }


                    }
                }
            }
        }

        return self::$methods;
    }

    /**
     * Load information about the payment method
     *
     * @param string $name
     * Name of the payment method
     */
    public static function loadPaymentMethod($name)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*')
            ->from('#__eshop_payments')
            ->where('name = "' . $name . '"');
        $db->setQuery($query);
        return $db->loadObject();
    }

    /**
     * Get default payment gateway
     *
     * @return string
     */
    public static function getDefautPaymentMethod()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('name')
            ->from('#__eshop_payments')
            ->where('published = 1')
            ->order('ordering');
        $db->setQuery($query, 0, 1);
        return $db->loadResult();
    }

    /**
     * Get the payment method object based on it's name
     *
     * @param string $name
     * @return object
     */
    public static function getPaymentMethod($name)
    {
        $methods = self::getPaymentMethods();
        foreach ($methods as $method) {
            if ($method->getName() == $name) {
                return $method;
            }
        }
        return null;
    }
}

?>