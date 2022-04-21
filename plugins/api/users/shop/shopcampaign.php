<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/27/2019
 * Time: 9:51 AM
 */

use api\model\biz\shop\ShopProductBiz;
use api\model\dao\UserDao;
use api\model\dao\shop\ShopProductDao;
use api\model\dao\shop\ShopCategoryDao;
use api\model\form\ChangePasswordForm;
use api\model\Sconfig;
use api\model\SUtil;

defined('_JEXEC') or die('Restricted access');
jimport('joomla.user.user');


class UsersApiResourceShopcampaign extends ApiResource
{
    static public function routes()
    {
        $routes[] = 'shopcampaign/';

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
        $params['limit'] = 20;
        $order_by = isset($data['order_by']) ? $data['order_by'] : 'product_name';
        $order_by = $order_by == 'product_name' ? 'd.product_name' : 'p.' . $order_by;
        $order = isset($data['order']) ? $data['order'] : 'ASC';
        $params['order'] = "{$order_by} {$order}";

        if (@$data['group_id']) {
            switch ($data['group_id']) {
                case 1:
                    $result = $this->getFeatureProduct($params);
                    break;
                case -2:
                    $userInfo = SUtil::getUserByToken();
                    if (!$userInfo) {
                        $this->plugin->setResponse(array());
                        return true;
                    }
                    $params['where'][] = 'w.customer_id = ' . (int)$userInfo['id'];
                    $result = $this->getWishlist($params);
                    break;
                /* case 2:
                     $result = $this->getFeatureProduct($params);
                     break;*/
                default:
                    $params['where'][] = 'h.group_id = ' . (int)$data['group_id'];
                    $params['join'][] = array(
                        'type' => 'LEFT',
                        'with_table' => '#__eshop_products AS p ON p.id = h.product_id'
                    );
                    $params['join'][] = array(
                        'type' => 'LEFT',
                        'with_table' => '#__eshop_productdetails AS d ON p.id = d.product_id'
                    );
                    $params['where'][] = 'd.language = \'vi-VN\'';
                    //$params['order'] = 'c.level ASC, c.ordering ASC';

                    $result = $dao->getCampaign($params);
            }

        }


        $this->plugin->setResponse($result);
    }


    public function getFeatureProduct($params = array())
    {
        $select = array(

            'p.id',
            '1 as p_group',
            'p.product_sku',
            'p.product_image',
            'p.product_price as price',
            'p.product_quantity as in_stock',
            '(SELECT `weight_name` FROM `#__eshop_weightdetails` WHERE `weight_id` = p.product_weight_id AND  `language` = \'vi-VN\' limit 0, 1) as unit',
            '(SELECT `group_id` FROM `#__eshop_home_products` WHERE `product_id` = p.id limit 0, 1) as p_group',
            'd.product_name as name'
        );
        $paramsDefault = array(
            'as' => 'p',
            'no_quote' => true,
            'select' => implode(',', $select),
            'table' => '#__eshop_products',
            'where' => array(
                'p.published = 1',
                'p.product_featured = 1'
            ),
            'join' => array(
                array(
                    'type' => 'LEFT',
                    'with_table' => '#__eshop_productdetails AS d ON p.id = d.product_id'
                )
            ),
            'limit' => $params['limit']
        );
        $paramsDefault['where'][] = 'd.language = \'vi-VN\'';
        if (isset($params['where']) && $params['where']) {
            foreach ($params['where'] as $item) {
                $paramsDefault['where'][] = $item;
            }
        }
        if ($params) {
            foreach ($params as $k => $item) {
                if ($k === 'where') {
                    continue;
                }
                $paramsDefault[$k] = $item;
            }
        }
        $dao = new ShopProductDao();
        $result = $dao->getList($paramsDefault);
        $list = array();
        if ($result) {
            foreach ($result as $item) {
                $biz = new ShopProductBiz();
                $productPriceArray = \EshopHelper::getProductPriceArray($item['id'], $item['price']);
                $item['ori_price'] = $item['price'];
                if ($productPriceArray['salePrice'] >= 0) {
                    $item['price'] = $productPriceArray['salePrice'];
                    $item['base_price'] = $productPriceArray['basePrice'];
                } else {
                    $item['price'] = $productPriceArray['basePrice'];
                }
                $biz->setAttributes($item);

                $list[] = $biz;
            }
        }
        return $list;
    }

    public function getWishlist($params = array())
    {
        $select = array(

            'p.id',
            '1 as p_group',
            'p.product_sku',
            'p.product_image',
            'p.product_price as price',
            'p.product_quantity as in_stock',
            '(SELECT `weight_name` FROM `#__eshop_weightdetails` WHERE `weight_id` = p.product_weight_id AND  `language` = \'vi-VN\' limit 0, 1) as unit',
            '(SELECT `group_id` FROM `#__eshop_home_products` WHERE `product_id` = p.id limit 0, 1) as p_group',
            'd.product_name as name'
        );
        $paramsDefault = array(
            'as' => 'w',
            'no_quote' => true,
            'select' => implode(',', $select),
            'table' => '#__eshop_wishlists',
            'where' => array(
                'p.published = 1',
            ),
            'order' => 'w.id DESC',
            'join' => array(
                array(
                    'type' => 'LEFT',
                    'with_table' => '#__eshop_products AS p ON p.id = w.product_id'
                ),
                array(
                    'type' => 'LEFT',
                    'with_table' => '#__eshop_productdetails AS d ON p.id = d.product_id'
                )
            ),
            'limit' => $params['limit']
        );
        $paramsDefault['where'][] = 'd.language = \'vi-VN\'';

        if (isset($params['where']) && $params['where']) {
            foreach ($params['where'] as $item) {
                $paramsDefault['where'][] = $item;
            }
        }
        if ($params) {
            foreach ($params as $k => $item) {
                if ($k === 'where') {
                    continue;
                }
                $paramsDefault[$k] = $item;
            }
        }
        $dao = new ShopProductDao();
        $result = $dao->getList($paramsDefault);
        $list = array();
        if ($result) {
            foreach ($result as $item) {
                $biz = new ShopProductBiz();
                $productPriceArray = \EshopHelper::getProductPriceArray($item['id'], $item['price']);
                $item['ori_price'] = $item['price'];
                if ($productPriceArray['salePrice'] >= 0) {
                    $item['price'] = $productPriceArray['salePrice'];
                    $item['base_price'] = $productPriceArray['basePrice'];
                } else {
                    $item['price'] = $productPriceArray['basePrice'];
                }
                $biz->setAttributes($item);

                $list[] = $biz;
            }
        }
        return $list;
    }

    public function getNewProduct($params = array())
    {
        $select = array(

            'p.id',
            'p.product_sku',
            'p.product_image',
            'p.product_price as price',
            '(SELECT `weight_name` FROM `#__eshop_weightdetails` WHERE `weight_id` = p.product_weight_id AND  `language` = \'vi-VN\' limit 0, 1) as unit',
            '(SELECT `group_id` FROM `#__eshop_home_products` WHERE `product_id` = p.id limit 0, 1) as p_group',
            'd.product_name as name'
        );

        $paramsDefault = array(
            'as' => 'p',
            'no_quote' => true,
            'select' => implode(',', $select),
            'table' => '#__eshop_products',
            'where' => array(
                'p.published = 1'
            ),
            'join' => array(
                array(
                    'type' => 'LEFT',
                    'with_table' => '#__eshop_productdetails AS d ON p.id = d.product_id'
                )
            ),
            'order' => 'p.created_date DESC',
            'limit' => $params['limit']
        );
        $paramsDefault['where'][] = 'd.language = \'vi-VN\'';
        if (isset($params['where']) && $params['where']) {
            foreach ($params['where'] as $item) {
                $paramsDefault['where'][] = $item;
            }
        }
        if ($params) {
            foreach ($params as $k => $item) {
                if ($k === 'where') {
                    continue;
                }
                $paramsDefault[$k] = $item;
            }
        }
        $result = $this->getList($paramsDefault);
        $list = array();
        if ($result) {
            foreach ($result as $item) {
                $biz = new ShopProductBiz();
                $productPriceArray = \EshopHelper::getProductPriceArray($item['id'], $item['price']);
                $item['ori_price'] = $item['price'];
                if ($productPriceArray['salePrice'] >= 0) {
                    $item['price'] = $productPriceArray['salePrice'];
                    $item['base_price'] = $productPriceArray['basePrice'];
                } else {
                    $item['price'] = $productPriceArray['basePrice'];
                }
                $biz->setAttributes($item);

                $list[] = $biz;
            }
        }
        return $list;
    }
}
