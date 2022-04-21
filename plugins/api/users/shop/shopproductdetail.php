<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/27/2019
 * Time: 9:51 AM
 */
require_once JPATH_ROOT . '/components/com_eshop/helpers/helper.php';

use api\model\dao\UserDao;
use api\model\dao\shop\ShopProductDao;
use api\model\dao\shop\ShopProductOptionDao;
use api\model\dao\shop\ShopProductAttributeDao;
use api\model\dao\shop\ShopProductImageDao;
use api\model\dao\shop\ShopProductOptionDiscountDao;
use api\model\dao\shop\ShopStockDao;
use api\model\Sconfig;
use api\model\SUtil;

defined('_JEXEC') or die('Restricted access');
jimport('joomla.user.user');

class UsersApiResourceShopproductdetail extends ApiResource
{
    static public function routes()
    {
        $routes[] = 'shopproductdetail/';

        return $routes;
    }

    /**
     * @OA\Post(
     *     path="/api/users/shopproductdetail",
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


    public function get()
    {
        $result = array();
        $input = JFactory::getApplication()->input;
        $id = $input->get('id', 0);
        $optionDao = new ShopProductOptionDao();
        $params = array();
        $params['where'][] = 'ov.product_id = ' . (int)$id;
        $params['where'][] = 'od.`language` = \'vi-VN\'';
        $result['options'] = $optionDao->getProductOptions($params);

        $optionDiscountDao = new ShopProductOptionDiscountDao();
        $result['discount'] = $optionDiscountDao->getDiscount($id);

        $attributeDao = new ShopProductAttributeDao();
        $params = array();
        $params['where'][] = 'ad.product_id = ' . (int)$id;
        $params['where'][] = 'ad.`language` = \'vi-VN\'';
        $result['attributes'] = $attributeDao->getProductAttributes($params);
        $imageDao = new ShopProductImageDao();
        $result['images'] = $imageDao->getImages($id);

        $proDao = new ShopProductDao();
        $extra = $proDao->getExtraInfo($id);
        foreach ($extra as $k => $value) {
            $result[$k] = nl2br($value);
        }

        $info = $proDao->getProductInfo($id);
        foreach ($info as $k => $value) {
            $result[$k] = nl2br($value);
        }
        //print_r($result);
        // Set stock
        if ($result['total_weigth'] > 0) {
            $result['check_weight'] = true;
        }else{
            $result['check_quantity'] = true;
        }

        $productPriceArray = \EshopHelper::getProductPriceArray($result['id'], $result['product_price']);
        $result['ori_price'] = $result['product_price'];
        if ($productPriceArray['salePrice'] >= 0) {
            $result['price'] = $productPriceArray['salePrice'];
            $result['base_price'] = $productPriceArray['basePrice'];
        } else {
            $result['price'] = $productPriceArray['basePrice'];
        }

        /*$stockDao = new ShopStockDao();
        $result['in_stock'] = $stockDao->getStockQty($id);*/
        $config = new Sconfig();
        $services = $config->service;
        $result['service'] = $services;

        // Get restrict category
        $result['ship_limit'] = SUtil::checkLimitArea($id);
        $result['ship_area'] = array(-1);
        $result['limit_message'] = '';
        if ($result['ship_limit']) {
            $result['ship_area'] = array(50);
            $result['limit_message'] = $config->shippingLimitMessage;
        }
        $this->plugin->setResponse($result);
    }
}
