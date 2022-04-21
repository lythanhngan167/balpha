<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/27/2019
 * Time: 9:51 AM
 */

use api\model\biz\shop\ShopCategoryBiz;
use api\model\dao\UserDao;
use api\model\dao\shop\ShopCategoryDao;
use api\model\form\ChangePasswordForm;

defined('_JEXEC') or die('Restricted access');
jimport('joomla.user.user');

class UsersApiResourceShopcategories extends ApiResource
{
    static public function routes()
    {
        $routes[] = 'shopcategories/';

        return $routes;
    }

    /**
     * @OA\Post(
     *     path="/api/users/shopcategories",
     *     tags={"Shop"},
     *     summary="Get shop category",
     *     description="Get shop category",
     *     operationId="get",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Change password",
     *         @OA\JsonContent(ref="#/components/schemas/CategoryFrom"),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(ref="#/components/schemas/CategoryFrom"),
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
        $catid = $input->get('catid', 0);
        $version = $input->get('version', '');
        $dao = new ShopCategoryDao();
        $result = array();
        if($version == 'v2'){
            if($catid > 0){
                $infoParams = array(
                    'as' => 'c',
                    'no_quote' => true,
                    'select' => implode(',', $dao->select),
                    'where' => array(
                        'c.published = 1',
                        'd.language = \'vi-VN\'',
                        'c.id = '.(int)$catid
                    ),
                    'join' => array(
                        array(
                            'type' => 'LEFT',
                            'with_table' => '#__eshop_categorydetails AS d ON c.id = d.category_id'
                        )
                    )
                );
                $info = $dao->get($infoParams);
                $infoBiz = new ShopCategoryBiz();
                $infoBiz->setAttributes($info);

                if($infoBiz->category_parent_id){
                    $parentParams = array(
                        'as' => 'c',
                        'no_quote' => true,
                        'select' => implode(',', $dao->select),
                        'where' => array(
                            'c.published = 1',
                            'd.language = \'vi-VN\'',
                            'c.id = '.(int)$infoBiz->category_parent_id
                        ),
                        'join' => array(
                            array(
                                'type' => 'LEFT',
                                'with_table' => '#__eshop_categorydetails AS d ON c.id = d.category_id'
                            )
                        )
                    );
                    $parent = $dao->get($parentParams);
                    $parentBiz = new ShopCategoryBiz();
                    $parentBiz->setAttributes($parent);
                }

                // Get child category
                $params = array();
                $params['offset'] = 0;
                $params['limit'] = 200;
                $params['where'][] = 'd.language = \'vi-VN\'';
                $params['where'][] = 'c.category_parent_id = '.(int)$infoBiz->id;
                $params['order'] = 'c.level ASC, c.ordering ASC';
                $childrend = $dao->getCategories($params);
                if($childrend){
                    $result[] = $infoBiz;
                    foreach ($childrend as $child){
                        $result[] = $child;
                    }
                }else{
                    $params = array();
                    $params['offset'] = 0;
                    $params['limit'] = 200;
                    $params['where'][] = 'd.language = \'vi-VN\'';
                    $params['where'][] = 'c.category_parent_id = '.(int)$infoBiz->category_parent_id;
                    $params['order'] = 'c.level ASC, c.ordering ASC';
                    $sibling = $dao->getCategories($params);
                    if($sibling){
                        if($infoBiz->category_parent_id){
                            $result[] = $parentBiz;
                            foreach ($sibling as $child){
                                $result[] = $child;
                            }
                        }
                    }
                }

            }

            if(!$result){
                $params = array();
                $params['offset'] = 0;
                $params['limit'] = 200;
                if ($catid) {
                    $params['where'][] = 'c.category_parent_id = 0';
                }
                $params['where'][] = 'd.language = \'vi-VN\'';
                $params['order'] = 'c.level ASC, c.ordering ASC';
                $result = $dao->getCategories($params);
            }

            $this->plugin->setResponse($result);
            return true;


        }


        $params = array();
        $params['offset'] = 0;
        $params['limit'] = 200;
        if ($catid) {
            $params['where'][] = '(c.id = ' . (int)$catid . ' OR  c.category_parent_id = ' . (int)$catid . ')';
        }
        $params['where'][] = 'd.language = \'vi-VN\'';
        $params['order'] = 'c.level ASC, c.ordering ASC';

        $result = $dao->getCategories($params);
        $this->plugin->setResponse($result);
    }
}
