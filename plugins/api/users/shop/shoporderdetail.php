<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/27/2019
 * Time: 9:51 AM
 */

use api\model\dao\shop\ShopStockUserDao;
use api\model\dao\UserDao;
use api\model\dao\shop\ShopOrderDetailDao;
use api\model\dao\shop\ShopOrderProductDao;
use api\model\form\shop\OrderForm;
use api\model\SUtil;

defined('_JEXEC') or die('Restricted access');
jimport('joomla.user.user');

class UsersApiResourceShoporderdetail extends ApiResource
{
    static public function routes()
    {
        $routes[] = 'shoporderdetail/';

        return $routes;
    }

    /**
     * @OA\Get(
     *     path="/api/users/shoporderdetail",
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
        $input = JFactory::getApplication()->input;
        $id = $input->get('id', 0);
        $viewtype = $input->get('viewtype', 'customer');
        $user = JFactory::getUser();

        $stockDao = new ShopStockUserDao();
        $stockId = 0;
        $stockAdmin = 0;
        if ($viewtype == 'stock') {
            //$stockId = $stockDao->getStock(array('user_id' => $user->id));
            //$stockAdmin = $stockDao->isStockAdmin(array('user_id' => $user->id, 'id' => $stockId));
        }

        $dao = new ShopOrderDetailDao();
        $orderProductDao = new ShopOrderProductDao();
        $params = array();
        $params['where'][] = 'o.id = ' . (int)$id;


        if ($user->id > 0) {
            $childs = SUtil::getChildList((int)$user->id);
            $params['where'][] = 'o.customer_id IN (' . implode(',', $childs).')';
            $date = JFactory::getDate()->toSql();
            $params['select'] = ['od.orderstatus_name as orderstatus_name'];
            $params['select'][] = 'o.total';
            $params['select'][] = 'IF(o.payment_status = 0 AND datediff( \'' . $date . '\', o.created_date) > 2, 1, 0) as pay_note';
        }
        // if ($data['q']) { }
        // if ($data['catid']) {
        //     $params['where'][] = '(c.id = ' . (int)$data['catid'] . ' OR  c.category_parent_id = ' . (int)$data['catid'] . ')';
        // }
        $params['where'][] = 'od.language = \'vi-VN\'';
        $result = $dao->getOrdeInfo($params);
        //print_r($result);die;
        $productParams = array(
            'where' => array(
                'o.order_id =' . (int)$id
            )
        );

        $result->products = $orderProductDao->getProducts($productParams);
        foreach ($result->products as &$item) {
            if (isset($result->options[$item->id])) {
                $item->options = $result->options[$item->id];
            }
        }

        $result->is_admin = $stockAdmin;
        unset($result->options);
        $this->plugin->setResponse($result);
    }
}
