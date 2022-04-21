<?php
/**
 * @version        2.8.2
 * @package        Joomla
 * @subpackage    EShop - UPS 2 Shipping
 * @author    Giang Dinh Truong
 * @copyright    Copyright (C) 2012 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die();

class eshop_ghtk extends eshop_shipping
{

    /**
     *
     * Constructor function
     */
    public function __construct()
    {
        parent::setName('eshop_ghtk');
        parent::__construct();
    }

    /**
     *
     * Function tet get quote for ups shipping
     * @param array $addressData
     * @param object $params
     */
    function getQuote($addressData, $params)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        if (!$params->get('ups_geozone_id')) {
            $status = true;
        } else {
            $query->select('COUNT(*)')
                ->from('#__eshop_geozonezones')
                ->where('geozone_id = ' . intval($params->get('ups_geozone_id')))
                ->where('country_id = ' . intval($addressData['country_id']))
                ->where('(zone_id = 0 OR zone_id = ' . intval($addressData['zone_id']) . ')');
            $db->setQuery($query);
            if ($db->loadResult()) {
                $status = true;
            } else {
                $status = false;
            }
            //Check geozone postcode status
            if ($status) {
                $gzpStatus = EshopHelper::getGzpStatus($params->get('geozone_id'), $addressData['postcode']);

                if (!$gzpStatus) {
                    $status = false;
                }
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
        $cart = new EshopCart();
        $total = $cart->getTotal();
        $minTotal = $params->get('min_total', 0);
        if ($minTotal > 0 && $total >= $minTotal) {
            $status = false;
        }
        $methodData = array();
        if ($status) {
            // Get weight and weight code
            $weight = $cart->getWeight();
            $weight = ($weight < 0.1 ? 0.1 : $weight);
            $error = '';
            $data = array(
                "province" => $addressData['country_name'],
                "district" => $addressData['zone_name'],
                "address" => $addressData['address_1'],
                "weight" => $weight,
                "value" => $total
            );

            $base_url = JUri::base();
            $url = $base_url . 'api/users/shopshipghtk?' . http_build_query($data);
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            $result = json_decode(curl_exec($curl));
            curl_close($curl);
            $quoteData = array();

            if ($result) {
                $tax = new EshopTax(EshopHelper::getConfig());
                $currency = new EshopCurrency();
                $query->clear();
                $query->select('*')
                    ->from('#__eshop_shippings')
                    ->where('name = "eshop_ghtk"');
                $db->setQuery($query);
                $row = $db->loadObject();
                $dataShip = $result->data;
                $cost = $dataShip->fee;
                $quoteData['fee']['ordering'] = $row->ordering;
                if ($params->get('show_shipping_cost_with_tax', 1)) {
                    $text = $currency->format($tax->calculate($cost, $params->get('taxclass_id'), EshopHelper::getConfigValue('tax')));
                } else {
                    $text = $currency->format($cost);
                }
                $quoteData['fee'] = array(
                    'name' => 'eshop_ghtk.fee',
                    'title' => 'Giao Hàng Ngoại Tỉnh (tạm tính)',
                    'cost' => $cost,
                    'lng' => $addressData['lng'],
                    'lat' => $addressData['lat'],
                    'taxclass_id' => $params->get('taxclass_id'),
                    'text' => $text
                );
            }
            $methodData = array(
                'name' => 'eshop_ghtk',
                'title' => 'Giao Hàng Ngoại Tỉnh (tạm tính)',
                'lng' => $addressData['lng'],
                'lat' => $addressData['lat'],
                'quote' => $quoteData,
                'error' => $error
            );
        }
        return $methodData;
    }
}
