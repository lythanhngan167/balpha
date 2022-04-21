<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/27/2019
 * Time: 9:51 AM
 */

use api\model\dao\UserDao;
use api\model\dao\shop\ShopOrderDao;
use api\model\dao\shop\ShopStockUserDao;
use api\model\SUtil;

defined('_JEXEC') or die('Restricted access');
jimport('joomla.user.user');

class UsersApiResourceShopreportorders extends ApiResource
{
    static public function routes()
    {
        $routes[] = 'shopreportorders/';

        return $routes;
    }

    /**
     * @OA\Post(
     *     path="/api/users/shopreportorders",
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
        $childs = SUtil::getChildList((int)$user->id);
        $db = JFactory::getDbo();
        $sql = "SELECT COUNT(*) AS num , order_status_id FROM`#__eshop_orders` WHERE 	customer_id IN (" . implode(',', $childs) . ') GROUP BY order_status_id';

        $result = $db->setQuery($sql)->loadAssocList();
        $list = array();
        if ($result) {
            $total = 0;
            foreach ($result as $item) {
                $list[$item['order_status_id']] = $item['num'];
                $total += $item['num'];
            }
            $list[0] = $total;
        }

        // Add report payment
        $sql = "SELECT COUNT(*) AS num , payment_status FROM`#__eshop_orders` WHERE 	customer_id IN (" . implode(',', $childs) . ') GROUP BY payment_status';

        $result = $db->setQuery($sql)->loadAssocList();
        if ($result) {
            foreach ($result as $item) {
                switch ($item['payment_status']) {
                    case '0':
                        $list['-1'] = $item['num'];
                        break;
                    case '1':
                        $list['-2'] = $item['num'];
                        break;
                    case '9':
                        $list['-3'] = $item['num'];
                        break;
                }
            }
            foreach ($list as $k=> $item) {
                if($item > 10){
                    $list[$k] = '10+';
                }
            }
        }

        $this->plugin->setResponse($list);
    }
}
