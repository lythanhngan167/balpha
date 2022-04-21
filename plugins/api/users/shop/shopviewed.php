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

defined('_JEXEC') or die('Restricted access');
jimport('joomla.user.user');

class UsersApiResourceShopviewed extends ApiResource
{
    static public function routes()
    {
        $routes[] = 'shopviewed/';

        return $routes;
    }

    /**
     * @OA\Get(
     *     path="/api/users/shopviewed",
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
        $input = JFactory::getApplication()->input;
        $ids = $input->getString('ids', null);
        $dao = new ShopProductDao();
        $params = array();
        $params['offset'] = 0;
        $params['limit'] = 200;
        if ($ids) {
            $ids = explode(',', $ids);
            foreach ($ids as &$item) {
                $item = (int)$item;
            }
            $params['where'][] = 'p.id IN (' . implode(',', $ids) . ')';
        }

        $params['join'][] =
            array(
                'type' => 'LEFT',
                'with_table' => '#__eshop_productdetails AS d ON p.id = d.product_id'
            );
        $params['where'][] = 'd.language = \'vi-VN\'';
        $result = $dao->getProducts($params);
        if($result){
            $list = array();
            foreach($result as $item){
                $list[$item->id] = $item;
            }
            $result = array();
            foreach($ids as $k){
                if(isset($list[$k])){
                    $result[] = $list[$k];
                }
            }
        }

        $this->plugin->setResponse($result);
    }
}
