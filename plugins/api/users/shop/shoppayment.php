<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/27/2019
 * Time: 9:51 AM
 */

use api\model\dao\UserDao;
use api\model\dao\shop\ShopPaymentDao;

defined('_JEXEC') or die('Restricted access');
jimport('joomla.user.user');

class UsersApiResourceShoppayment extends ApiResource
{
    static public function routes()
    {
        $routes[] = 'shoppayment/';

        return $routes;
    }
    /**
     * @OA\Get(
     *     path="/api/users/shoppayment", 
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

    public function get()
    {
        $dao = new ShopPaymentDao();
        $params = array();
        $params['offset'] = 0;
        $params['limit'] = 200;
        $result = $dao->getPayments($params);
        $this->plugin->setResponse($result);
    }
}
