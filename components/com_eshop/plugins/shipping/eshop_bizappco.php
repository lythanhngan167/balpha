<?php
/**
 * @version        3.1.0
 * @package        Joomla
 * @subpackage    EShop - UPS Shipping
 * @author    Giang Dinh Truong
 * @copyright    Copyright (C) 2012 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */

// no direct access
use api\model\Sconfig;
use api\model\SUtil;

defined('_JEXEC') or die();

class eshop_bizappco extends eshop_shipping
{

    /**
     *
     * Constructor function
     */
    public function __construct()
    {
        parent::setName('eshop_bizappco');
        parent::__construct();
    }
    public function checkExtraFee($weight){
        return $weight >= 8;
    }
    /**
     *
     * Function tet get quote for ups shipping
     * @param array $addressData
     * @param object $params
     */
    function getQuote($addressData, $params)
    {
        $shippingFee = 0;
        $discount = false;
        $lng = '';
        $lat = '';
        if ($addressData['country_id'] == $params->get('shipping_area')) {

            $lng = $addressData['lng'];
            $lat = $addressData['lat'];
            if ($addressData['lng'] == '' || $addressData['lat'] == '') {
                $fullAddress = SUtil::getFullAddress($addressData['id']);
                $info = SUtil::getLonLatFromAddress($fullAddress);
                $lng = $info['lng'];
                $lat = $info['lat'];
            }
            $config = new Sconfig();
            $shop_position = $config->shop_longlat;
            $distance = SUtil::getDistance($shop_position['lat'], $shop_position['lng'], $lat, $lng);
            $cart = new EshopCart();
            $weight = $cart->getWeight();
            $extraFee = 0;
            if ($distance < 5) {
                $shippingFee = 13000;
                if($this->checkExtraFee($weight)){
                    $extraFee = 10000;
                }
            } elseif ($distance < 10) {
                $shippingFee = 15000;
                if($this->checkExtraFee($weight)){
                    $extraFee = 15000;
                }
            } elseif ($distance < 15) {
                $shippingFee = 25000;
                if($this->checkExtraFee($weight)){
                    $extraFee = 20000;
                }
            } elseif ($distance < 20) {
                $shippingFee = 30000;
                if($this->checkExtraFee($weight)){
                    $extraFee = 25000;
                }
            } else {
                $shippingFee = 45000;
                if($this->checkExtraFee($weight)){
                    $extraFee = 30000;
                }
            }
            $shippingFee += $extraFee;
            if($distance < 10){
                $shippingFee = $shippingFee * 0.5;
                $discount = true;
            }
        }
        $shipTitle = $params->get('shipping_name');
        if($discount){
            $shipTitle .="(-50%)";
        }
        $quoteData = array(
            'minhcau' => array(
                'name' => 'eshop_bizappco',
                'title' => $shipTitle,
                'cost' => $shippingFee,
                'lng' => $lng,
                'lat' => $lat,
                'taxclass_id' => '',
                'text' => number_format((float)$shippingFee, 0, '', '.'),
            )
        );

        $methodData = array(
            'name' => 'eshop_bizappco',
            'title' => $shipTitle,
            'quote' => $quoteData,
            'lng' => $lng,
            'lat' => $lat,
            'ordering' => -1,
            'error' => null
        );

        return $methodData;
    }
}
