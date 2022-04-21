<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/27/2019
 * Time: 9:51 AM
 */

use api\model\dao\UserDao;
use api\model\dao\shop\ShopCountryDao;

defined('_JEXEC') or die('Restricted access');
jimport('joomla.user.user');

class UsersApiResourceShopcountry extends ApiResource
{
    static public function routes()
    {
        $routes[] = 'shopcountry/';

        return $routes;
    }
    /**
     * @OA\Get(
     *     path="/api/users/shopcountry", 
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
        $dao = new ShopCountryDao();
        $params = array();
        $params['offset'] = isset($data['offset']) ? (int)$data['offset'] : 0;
        $params['limit'] = isset($data['limit']) ? (int)$data['limit'] : 200;
        $result = $dao->getCountry($params);
        $this->plugin->setResponse($result);
    }
}
