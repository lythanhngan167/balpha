<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/27/2019
 * Time: 9:51 AM
 */


use api\model\dao\shop\ShopOrderStatusDao;


defined('_JEXEC') or die('Restricted access');

class UsersApiResourceShoporderstatus extends ApiResource
{
    static public function routes()
    {
        $routes[] = 'shoporderstatus/';

        return $routes;
    }
    /**
     * @OA\Get(
     *     path="/api/users/shoporderstatus",
     *     tags={"Shop"},
     *     summary="Get order status",
     *     description="Get order status",
     *     operationId="post",
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
        $dao = new ShopOrderStatusDao();
        $result = $dao->getOrderStatus();
        $this->plugin->setResponse($result);
    }
}
