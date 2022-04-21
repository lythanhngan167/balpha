<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/27/2019
 * Time: 9:51 AM
 */

use api\model\dao\shop\ShopCheckoutDao;
use api\model\dao\shop\ShopOrderDao;
use api\model\dao\UserDao;
use api\model\dao\shop\ShopZoneDao;

defined('_JEXEC') or die('Restricted access');
jimport('joomla.user.user');

class UsersApiResourceShoppaymentstatus extends ApiResource
{
    static public function routes()
    {
        $routes[] = 'shoppaymentstatus/';

        return $routes;
    }

    /**
     * @OA\Get(
     *     path="/api/users/shopzone",
     *     tags={"User"},
     *     summary="Change password user",
     *     description="Change password user",
     *     operationId="get",
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

    public function get()
    {
        $dao = new ShopOrderDao();
        $input = JFactory::getApplication()->input;
        $id = $input->get('id', 0);
        $result = $dao->get(array('select' => '*', 'no_quote' => true, 'where' => array('id = ' . (int)$id)));
        $is_success = 0;
        $message = array();
        if ($result) {
            $checkoutDao = new ShopCheckoutDao();
            $is_success = $result['payment_status'] === '1' ? 1 : 0;
            if(!$is_success){
                $message = 'Thanh toán không thành công.';
            }
            //$message = $checkoutDao->getOnePayMessage($result['order_number']);
        }
        $return = array('status' => $is_success, 'message' => $message);
        $this->plugin->setResponse($return);
    }
}
