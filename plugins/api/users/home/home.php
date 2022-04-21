<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/27/2019
 * Time: 9:51 AM
 */
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
use api\model\dao\ContentDao;
use api\model\dao\shop\ShopHomeProductDao;
use api\model\dao\shop\ShopCategoryDao;
use api\model\dao\shop\ShopWishlistDao;
use api\model\Sconfig;
use api\model\SUtil;
use Joomla\Registry\Registry;
defined('_JEXEC') or die('Restricted access');

class UsersApiResourceHome extends ApiResource
{
    static public function routes()
    {
        $routes[] = 'home/';

        return $routes;
    }

    public function delete()
    {
        $this->plugin->setResponse('in delete');
    }


    /**
     * @OA\Get(
     *     path="/api/users/home",
     *     tags={"User"},
     *     summary="Get home page",
     *     description="Get home page",
     *     operationId="get",
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Invalid request",
     *     )
     * )
     */
    public function get()
    {
        $config = new Sconfig();
        $result = array();
        $dao = new ContentDao();
        $params = array(
            'order' => 'id DESC',
            'limit' => 4
        );
        $limit = 3;
        $offset = 0;
        // $result['contents'] = $dao->getContent($params);
        /*$result['slide'] = array(
            array('thumb' => 'http://aloluatsu.bizappco.com/images/2017/1529916327_banner_1.jpg'),
            array('thumb' => 'http://aloluatsu.bizappco.com/images/2017/1529916327_banner_1.jpg'),
            array('thumb' => 'http://aloluatsu.bizappco.com/images/2017/1529916327_banner_1.jpg'),
            array('thumb' => 'http://aloluatsu.bizappco.com/images/2017/1529916327_banner_1.jpg')
        );*/
        $result['slide'] = $this->getBannerSlide();
        $result['modules'] = array_values($this->getHomeProduct());
        $result['categories'] = $this->getCategories();
        $result['preferred'] = $this->_getK2Content(PREFERRED, $limit, $offset);
        $result['knowlegde'] = $this->_getK2Content(KNOWLEGDE, $limit, $offset);
        $result['news'] = $this->_getK2Content(NEWS, $limit, $offset);
        $this->plugin->setResponse($result);

    }
    public function getBannerSlide(){
        require_once JPATH_SITE.'/modules/mod_bannerslider/helper.php';
        $db = JFactory::getDBO();
        $db->setQuery("SELECT * FROM `#__modules` WHERE module = 'mod_bannerslider' AND published = 1 ");
        $module = $db->loadObject();
        // Get module parameters
        $params = new Registry($module->params);
        $list = &modWalkswithmeBannerSlider::getList($params);

        $slides = array();
        if($list){
            $baseUrl = JUri::base();

            foreach ($list as $item){
                $sql = 'SELECT id_app, page_app, page_title_app FROM #__banners  WHERE id = '.(int)$item->id;
                $info = $db->setQuery($sql)->loadObject();
                $slides[] = array(
                    'thumb' => $baseUrl.$item->params->get('imageurl'),
                    'id' => $info->id_app,
                    'page' => $info->page_app,
                    'title' => $info->page_title_app,
                );
            }
        }

        return $slides;
    }
    private function getHomeProduct()
    {
        $params['join'][] =
            array(
                'type' => 'LEFT',
                'with_table' => '#__eshop_products AS p ON p.id = hp.product_id'
            );
        $params['join'][] =
            array(
                'type' => 'LEFT',
                'with_table' => '#__eshop_productdetails AS d ON p.id = d.product_id'
            );

        $params['join'][] =
            array(
                'type' => 'LEFT',
                'with_table' => '#__eshop_productcategories AS pc ON ( pc.product_id = p.id AND pc.main_category = 1)'
            );
        $params['where'][] = 'd.language = \'vi-VN\'';
        $dao = new ShopHomeProductDao();
        return $dao->getProducts($params);

    }

    private function getCategories()
    {
        $dao = new ShopCategoryDao();
        $params = array();
        $params['offset'] = 0;
        $params['limit'] = 200;

        $params['where'][] = 'd.language = \'vi-VN\'';
        $params['order'] = 'c.level ASC, c.ordering ASC';

        return $dao->getCategories($params);

    }

    //thaitm - get k2 content
    private function _getK2Content($id, $limit, $limitstart) {
        $host = JURI::base();   //get host
        $path = "index.php?option=com_k2&view=itemlist&layout=category&task=category&id=".$id."&limitstart=".$limitstart."&limit=".$limit."&format=json";
            
        $json = file_get_contents($host.$path);
        $result = json_decode($json);

        return $result;
    }


}
