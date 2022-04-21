<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/27/2019
 * Time: 9:51 AM
 */

use api\model\dao\UserDao;
use api\model\dao\shop\ShopAddressDao;
use api\model\form\shop\AddressForm;

defined('_JEXEC') or die('Restricted access');
jimport('joomla.user.user');

class UsersApiResourceShopremoveaddress extends ApiResource
{
    static public function routes()
    {
        $routes[] = 'shopremoveaddress/';

        return $routes;
    }

    /**
     * @OA\Post(
     *     path="/api/users/shopremoveaddress",
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
        $user = JFactory::getUser();
        $sql = 'DELETE FROM #__eshop_addresses WHERE id = ' . $data['id'] . ' AND customer_id = ' . (int)$user->id;
        $dao = new ShopAddressDao();
        $dao->db->setQuery($sql)->execute();
        $this->plugin->setResponse('');
        return true;
    }
}
