<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/27/2019
 * Time: 9:51 AM
 */

use api\model\dao\UserDao;
use api\model\dao\shop\ShopAddressDao;

defined('_JEXEC') or die('Restricted access');
jimport('joomla.user.user');

class UsersApiResourceShopaddress extends ApiResource
{
    static public function routes()
    {
        $routes[] = 'shopaddress/';

        return $routes;
    }

    /**
     * @OA\Get(
     *     path="/api/users/shopaddress",
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

    public function get()
    {
        $user = JFactory::getUser();
        $dao = new ShopAddressDao();
        $params = array();
        $params['offset'] = 0;
        $params['limit'] = 200;
        $params['where'][] = 'ad.customer_id = ' . (int)$user->id;
        $params['where'][] = 'ad.country_id > 0';
        $params['where'][] = 'ad.zone_id > 0 ';
        $result = $dao->getAddress($params);
        $this->plugin->setResponse($result);
    }
}
