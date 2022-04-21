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

class UsersApiResourceShoporders extends ApiResource
{
    static public function routes()
    {
        $routes[] = 'shoporders/';

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

    public function post()
    {
        $data = $this->getRequestData();
        $user = JFactory::getUser();


        $dao = new ShopOrderDao();
        $params = array();
        $params['offset'] = isset($data['offset']) ? (int)$data['offset'] : 0;
        $params['limit'] = isset($data['limit']) ? (int)$data['limit'] : 10;
        $params['join'] = array(
            array(
                'type' => 'LEFT',
                'with_table' => '#__eshop_orderstatusdetails AS od ON od.orderstatus_id = o.order_status_id'
            ),
            array(
                'type' => 'LEFT',
                'with_table' => '#__users AS u ON u.id = o.customer_id'
            )
        );
        if ($user->id > 0) {

            //$params['where'][] = 'o.customer_id = ' . (int)$user->id;
            if (@$data['orderstatus_id'] > 0) {
                $params['where'][] = 'orderstatus_id = ' . (int)$data['orderstatus_id'];
            } else if (@$data['orderstatus_id']) {
                switch ($data['orderstatus_id']) {
                    case -1:
                        $params['where'][] = 'payment_status = 0';
                        break;
                    case -2:
                        $params['where'][] = 'payment_status = 1';
                        break;

                    case -3:
                        $params['where'][] = 'payment_status = 9';
                        break;
                }
            }

            $params['select'] = ['od.orderstatus_name'];
            $params['select'][] = 'o.total';
        }
        if ($data['q']) {
            $params['where'][] = 'o.order_number LIKE ' . $dao->db->quote('%' . strtoupper($data['q']) . '%');
        }
        if (@$data['mid']) {
            $params['where'][] = 'o.customer_id = ' . (int)$data['mid'];
        } else {
            $childs = SUtil::getChildList((int)$user->id);
            $params['where'][] = 'o.customer_id IN (' . implode(',', $childs) . ')';
        }

        //$params['where'][] = 'od.language = \'vi-VN\'';
        $result = $dao->getOrders($params);
        $this->plugin->setResponse($result);
    }
}
