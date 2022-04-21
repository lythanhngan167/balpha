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

defined('_JEXEC') or die('Restricted access');
jimport('joomla.user.user');

class UsersApiResourceShopuser extends ApiResource
{
    static public function routes()
    {
        $routes[] = 'shopuser/';

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
        $data = $this->getRequestData();

        $stockDao = new ShopStockUserDao();
        $stockId = 0;
        if ($data['viewtype'] == 'stock') {
            $stockId = $stockDao->getStock(array('user_id' => $user->id));
        }

        $dao = new ShopOrderDao();
        $params = array();
        $params['offset'] = isset($data['offset']) ? (int)$data['offset'] : 0;
        $params['limit'] = isset($data['limit']) ? (int)$data['limit'] : 200;
        if ($stockId > 0) {
            $params['join'] = array(
                array(
                    'type' => 'LEFT',
                    'with_table' => '#__eshop_orderproducts AS op ON op.order_id = o.id'
                ),
                array(
                    'type' => 'LEFT',
                    'with_table' => '#__eshop_orderstatusdetails AS od ON od.orderstatus_id = op.status_id'
                )
            );
            $params['where'][] = 'op.stock_id = ' . (int)$stockId;
            $params['group'] = 'o.id';
            $params['select'] = ['SUM(op.total_price) as total', 'od.orderstatus_name'];
            if (@$data['orderstatus_id'] > 0) {
                $params['where'][] = 'orderstatus_id = ' . (int)$data['orderstatus_id'];
            }
        } elseif ($user->id > 0) {

            $params['where'][] = 'o.customer_id = ' . (int)$user->id;
            if (@$data['orderstatus_id'] > 0) {
                $data['orderstatus_id'] = $data['orderstatus_id'] == 2 ? 0 : $data['orderstatus_id'];
                $params['where'][] = 'payment_status = ' . (int)$data['orderstatus_id'];
            }
            $date = JFactory::getDate()->toSql();

            $params['select'] = ['IF(o.payment_status = 1, \'Đã thanh toán\', \'Chưa thanh toán\') as orderstatus_name'];
            $params['select'][] = 'o.total';
            $params['select'][] = 'IF(o.payment_status = 0 AND datediff( \'' . $date . '\', o.created_date) > 2, 1, 0) as pay_note';
        }
         if ($data['q']) {
             $params['where'][] = 'o.order_number LIKE '.$dao->db->quote('%'.strtoupper($data['q']).'%');
         }

        //$params['where'][] = 'od.language = \'vi-VN\'';
        $result = $dao->getOrders($params);
        $this->plugin->setResponse($result);
    }
}
