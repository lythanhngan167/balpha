<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/27/2019
 * Time: 9:51 AM
 */

use api\model\dao\UserDao;
use api\model\dao\shop\ShopProductDao;
use api\model\dao\shop\ShopCategoryDao;
use api\model\form\ChangePasswordForm;
use api\model\Sconfig;
use api\model\SUtil;

defined('_JEXEC') or die('Restricted access');
jimport('joomla.user.user');

class UsersApiResourceShopproducts extends ApiResource
{
    static public function routes()
    {
        $routes[] = 'shopproducts/';

        return $routes;
    }

    /**
     * @OA\Post(
     *     path="/api/users/shopproducts",
     *     tags={"User"},
     *     summary="Change password user",
     *     description="Change password user",
     *     operationId="post",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Change password",
     *         @OA\JsonContent(ref="#/components/schemas/ProductForm"),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(ref="#/components/schemas/ProductForm"),
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
        $dao = new ShopProductDao();
        $params = array();
        $params['offset'] = isset($data['offset']) ? (int)$data['offset'] : 0;
        $params['limit'] = 40;//isset($data['limit']) ? (int)$data['limit'] : 20;
        $order_by = isset($data['order_by']) ? $data['order_by'] : 'product_name';
        $order_by = $order_by == 'product_name' ? 'd.product_name' : 'p.' . $order_by;
        $order = isset($data['order']) ? $data['order'] : 'ASC';
        $params['order'] = "{$order_by} {$order}";
        /*$limitLocation = false;
        $location = $data['location'];
        $sconfig = new Sconfig();
        if (in_array($location, $sconfig->shippingLimitArea)) {
            if ($data['catid'] && $data['catid'] > 0 && in_array($data['catid'], $sconfig->shippingLimitCategory)) {
                $limitLocation = true;
            }
        }
        if($limitLocation){
            ApiError::raiseError('403', 'Minh Cầu Mart hiện tại chưa phục vụ danh mục sản phẩm này tại khu vực của quý khách.');
            return false;
        }*/

        if ($data['q']) {
            $params['where'][] = '(MATCH( d.`product_name`) AGAINST(' . $dao->db->quote($data['q']) . ') OR ( p.product_sku LIKE ' . $dao->db->quote('%' . $data['q'] . '%') . '))';
            $params['order'] = 'MATCH( d.`product_name`) AGAINST(' . $dao->db->quote($data['q']) . ') DESC ';

        }
        if ($data['catid'] && $data['catid'] > 0) {
            $categoryDao = new ShopCategoryDao();
            $ids = (array)$categoryDao->getListCategoryIds((int)$data['catid']);
            if ($ids) {
                $params['join'][] = array(
                    'type' => 'LEFT',
                    'with_table' => '#__eshop_productcategories AS pc ON p.id = pc.product_id'
                );
                $params['where'][] = 'pc.category_id IN (' . implode(',', $ids) . ')';
            }
        }
        if (@$data['catid'] == -2) {
            $userInfo = SUtil::getUserByToken();
            if (!$userInfo) {
                $this->plugin->setResponse(array());
                return true;
            }
            $params['join'][] =
                array(
                    'type' => 'RIGHT',
                    'with_table' => '#__eshop_wishlists AS w ON p.id = w.product_id'
                );
            $params['where'][] = 'w.customer_id = ' . (int)$userInfo['id'];
            $params['order'] = 'w.id DESC ';
        }

        $params['join'][] =
            array(
                'type' => 'LEFT',
                'with_table' => '#__eshop_productdetails AS d ON p.id = d.product_id'
            );
        $params['where'][] = 'd.language = \'vi-VN\'';
        $result = $dao->getProducts($params);
        $this->plugin->setResponse($result);
    }
}
