<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/27/2019
 * Time: 9:51 AM
 */

use api\model\dao\shop\ShopAddressDao;
use api\model\dao\shop\ShopOrderDetailDao;
use api\model\dao\shop\ShopOrderProductDao;
use api\model\Sconfig;
use api\model\SUtil;
use Joomla\Registry\Registry;

require_once(JPATH_SITE . '/components/com_eshop/helpers/helper.php');
defined('_JEXEC') or die('Restricted access');
jimport('joomla.user.user');

class UsersApiResourceShopshippingfee extends ApiResource
{
    public $error = array();

    static public function routes()
    {
        $routes[] = 'shippingfee/';

        return $routes;
    }

    /**
     * @OA\Post(
     *     path="/api/users/shopcoupon",
     *     tags={"User"},
     *     summary="Get customer address",
     *     description="Get customer address",
     *     operationId="get",
     *     security = { { "bearerAuth": {} } },
     *     @OA\Response(
     *         response=200,
     *         description="successful login",
     *         @OA\Schema(ref="#/components/schemas/ErrorModel"),
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Invalid request",
     *     )
     * )
     */

    public function post()
    {
        $return = array();
        $data = $this->getRequestData();
        if (@$data['info'] && $data['info']['id']) {
            $addressInfo = EshopHelper::getAddress($data['info']['id']);
            $addressData = array(
                'id' => isset($addressInfo['id']) ? $addressInfo['id'] : '',
                'firstname' => isset($addressInfo['firstname']) ? $addressInfo['firstname'] : '',
                'lastname' => isset($addressInfo['lastname']) ? $addressInfo['lastname'] : '',
                'company' => isset($addressInfo['company']) ? $addressInfo['company'] : '',
                'address_1' => isset($addressInfo['address_1']) ? $addressInfo['address_1'] : '',
                'address_2' => isset($addressInfo['address_2']) ? $addressInfo['address_2'] : '',
                'postcode' => isset($addressInfo['postcode']) ? $addressInfo['postcode'] : '',
                'city' => isset($addressInfo['city']) ? $addressInfo['city'] : '',
                'zone_id' => isset($addressInfo['zone_id']) ? $addressInfo['zone_id'] : EshopHelper::getConfigValue('zone_id'),
                'zone_name' => isset($addressInfo['zone_name']) ? $addressInfo['zone_name'] : '',
                'zone_code' => isset($addressInfo['zone_code']) ? $addressInfo['zone_code'] : '',
                'country_id' => isset($addressInfo['country_id']) ? $addressInfo['country_id'] : EshopHelper::getConfigValue('country_id'),
                'country_name' => isset($addressInfo['country_name']) ? $addressInfo['country_name'] : '',
                'iso_code_2' => isset($addressInfo['iso_code_2']) ? $addressInfo['iso_code_2'] : '',
                'iso_code_3' => isset($addressInfo['iso_code_3']) ? $addressInfo['iso_code_3'] : '',
                'lng' => isset($addressInfo['lng']) ? $addressInfo['lng'] : '',
                'lat' => isset($addressInfo['lat']) ? $addressInfo['lat'] : ''
            );

            $quoteData = array();

            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select('*')
                ->from('#__eshop_shippings')
                ->where('published = 1')
                ->order('ordering');
            if($addressData['country_id'] == 50){
                $query->where('`name` = \'eshop_bizappco\' ');
            }else{
                $query->where('`name` <> \'eshop_bizappco\' ');
            }
            $db->setQuery($query);
            $rows = $db->loadObjectList();
            include (JPATH_SITE.'/components/com_eshop/plugins/shipping/eshop_shipping.php');
            require_once(JPATH_SITE . '/components/com_eshop/helpers/customer.php');
            require_once(JPATH_SITE . '/components/com_eshop/helpers/currency.php');
            require_once(JPATH_SITE . '/components/com_eshop/helpers/weight.php');
            require_once(JPATH_SITE . '/components/com_eshop/helpers/tax.php');
            include (JPATH_SITE.'/components/com_eshop/helpers/cart.php');
            for ($i = 0; $n = count($rows), $i < $n; $i++) {
                $shippingName = $rows[$i]->name;
                $params = new Registry($rows[$i]->params);

                require_once JPATH_SITE . '/components/com_eshop/plugins/shipping/' . $shippingName . '.php';

                $shippingClass = new $shippingName();
                $quote = $shippingClass->getQuote($addressData, $params);
                if ($quote) {
                    $quoteData[$shippingName] = array(
                        'title' => $quote['title'],
                        'quote' => $quote['quote'],
                        'ordering' => @$quote['ordering'],
                        'error' => $quote['error']
                    );
                }
            }
            if($quoteData){
                $item = reset($quoteData);
                $fee = reset($item['quote']);
                $return['shipping_token'] = SUtil::encryptData(serialize($fee));
                $return['fee'] = $fee['cost'];
            }
        }
        $this->plugin->setResponse($return);
        return true;
    }
}
