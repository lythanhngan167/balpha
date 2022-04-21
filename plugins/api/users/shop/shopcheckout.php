<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/27/2019
 * Time: 9:51 AM
 */

use api\model\dao\shop\ShopCheckoutDao;
use api\model\dao\shop\ShopOrderDao;
use api\model\libs\CommonGateway;

defined('_JEXEC') or die('Restricted access');
jimport('joomla.user.user');

class UsersApiResourceShopcheckout extends ApiResource
{
    static public function routes()
    {
        $routes[] = 'shopcheckout/';

        return $routes;
    }

    /**
     * @OA\Post(
     *     path="/api/users/shoporders",
     *     tags={"User"},
     *     summary="Change password user",
     *     description="Change password user",
     *     operationId="post",
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

        $data = $this->getRequestData();

        $dao = new ShopCheckoutDao();
        $dao->setProducts($data['products']);
        $dao->setAddress($data['address']);
        $dao->setShipping($data['shipping']);
        $dao->setPayment($data['payment']);
        $dao->setComment($data['comment']);
        $dao->setCoupon($data['coupon']);
        $dao->setVoucher($data['voucher']);
        $dao->setTotal($data['total']);
        $dao->setShippingFee($data['fee']);
        $dao->setShippingToken($data['fee_token']);
        if(@$data['delivery']){
            $dao->setDeliveryTime($data['delivery']);
        }
        if(@$data['delivery_hour']){
            $dao->setDeliveryHour($data['delivery_hour']);
        }

        $version = isset($data['v']) ? $data['v']: '1.0';
        $dao->setVersion($version);

        if ($dao->checkout()) {
            $url = $dao->getRedirectUrl();
            $orderId = $dao->getOrderId();
            if ($url) {
                $this->plugin->setResponse(array('redirect' => true, 'url' => $url, 'order_id' => $orderId));
            } else {
                $this->plugin->setResponse($dao->getSuccessResult());
            }

            return true;
        } else {
            ApiError::raiseError('101', $dao->getError());
            return false;
        }

    }
}
