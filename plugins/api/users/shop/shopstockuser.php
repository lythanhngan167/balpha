<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/27/2019
 * Time: 9:51 AM
 */


use api\model\dao\shop\ShopStockUserDao;


defined('_JEXEC') or die('Restricted access');
jimport('joomla.user.user');

class UsersApiResourceShopstockuser extends ApiResource
{
    static public function routes()
    {
        $routes[] = 'shopstockuser/';

        return $routes;
    }

    /**
     * @OA\Post(
     *     path="/api/users/shopuser",
     *     tags={"User"},
     *     summary="Change password user",
     *     description="Change password user",
     *     operationId="post",
     *     security = { { "bearerAuth": {} } },
     *     @OA\RequestBody(
     *         required=true,
     *         description="Change password",
     *         @OA\JsonContent(ref="#/components/schemas/OrderForm"),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(ref="#/components/schemas/OrderForm"),
     *         )
     *     ),
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
        $user = JFactory::getUser();
        $dao = new ShopStockUserDao();
        $result = $dao->getStoreAdmin(array('user_id' => $user->id));
        $this->plugin->setResponse($result);
    }
}
