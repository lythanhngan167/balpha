<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/27/2019
 * Time: 9:51 AM
 */

use api\model\dao\UserDao;
use api\model\dao\shop\ShopStockUserDao;
use api\model\form\shop\AddressForm;

defined('_JEXEC') or die('Restricted access');
jimport('joomla.user.user');

class UsersApiResourceShopupdateorder extends ApiResource
{
    static public function routes()
    {
        $routes[] = 'shopupdateorder/';

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
        $db = JFactory::getDbo();
        $user = JFactory::getUser();
        $stockDao = new ShopStockUserDao();
        $stock = $stockDao->getStock(array('user_id' => $user->id));
        $set = array();
        $date = JFactory::getDate()->toSql();
        switch ($data['type']) {
            case 'status_id':
                $set[] = "status_id = " . (int)$data['value'];
                break;
            case 'note':
                $set[] = "note = " . $db->quote($data['value']);
                break;
        }

        $set[] = "modefied_by = " . (int)$user->id;
        $set[] = "modified_date = '{$date}'";
        $sql = 'UPDATE #__eshop_orderproducts SET ' . implode(', ', $set) . ' WHERE order_id = ' . (int)$data['id'] . " AND stock_id =" . (int)$stock;
        $result = $db->setQuery($sql)->execute();
        $this->plugin->setResponse($result);
        return true;

    }
}
