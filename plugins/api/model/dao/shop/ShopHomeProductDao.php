<?php
/**
 * Created by PhpStorm.
 * User: lvson
 * Date: 4/26/2019
 * Time: 10:53 AM
 */

namespace api\model\dao\shop;

use api\model\AbtractDao;
use api\model\biz\shop\ShopProductBiz;

require_once(JPATH_SITE . '/components/com_eshop/helpers/customer.php');
require_once(JPATH_SITE . '/components/com_eshop/helpers/helper.php');

class ShopHomeProductDao extends AbtractDao
{
    public $select = array(
        'hp.group_id',
        'hp.group_id as p_group',
        'p.id',
        'p.product_sku',
        'p.product_image',
        'p.product_price as price',
        'd.product_name as name',
        'p.product_quantity as in_stock',
        '(SELECT `weight_name` FROM `#__eshop_weightdetails` WHERE `weight_id` = p.product_weight_id AND  `language` = \'vi-VN\' limit 0, 1) as unit',
        'pc.category_id'
    );

    public function getTable()
    {
        return '#__eshop_home_products';
    }

    public function getHomeProduct($params = array(), $group_id, $limit)
    {
        $date = date('Y-m-d H:i:s');
        $paramsDefault = array(
            'as' => 'hp',
            'no_quote' => true,
            'select' => implode(',', $this->select),
            'where' => array(
                'p.published = 1',
                'hp.group_id =' . (int)$group_id
                /*'hp.date_start <= ' . $this->db->quote($date),
                'hp.date_end >= ' . $this->db->quote($date)*/
            ),
            'limit' => $limit,
            'order' => 'RAND()'
        );
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
                if ($item['total_weigth'] > 0) {
                    $item['check_weight'] = true;
                }else{
                    $item['check_quantity'] = true;
                }
                $biz->setAttributes($item);
                $list[] = $biz;
            }
        }
        return $list;
    }

    public function getProducts($params = array())
    {

        $groupData = $this->getGroupInfo();
        $homeCatIndex = -1;
        $homeCats = array();
        foreach ($groupData as $k => &$item) {
            if ($item['group_type'] == 'home_page') {
                $homeCatIndex = $k;
                $homeProduct = $this->getHomeProduct($params, $item['id'], 500);
                $homeCats = $this->buildCategoryBlock($homeProduct, $item['max_num'], $item);
                unset($groupData[$k]);
                continue;
            } elseif ($item['group_type'] == 'product_featured') {
                $item['products'] = $this->getFeatureProduct(array('limit' => $item['max_num']));
                continue;
            } elseif ($item['group_type'] == 'product_new') {
                $item['products'] = $this->getNewProduct(array('limit' => $item['max_num']));
                if (empty($item['products'])) {
                    unset($groupData[$k]);
                }
                continue;
            } elseif ($item['group_type'] == 'discount') {
                $item['products'] = $this->getHomeProduct($params, $item['id'], $item['max_num']);
            } else {
                $item['products'] = $this->getHomeProduct($params, $item['id'], $item['max_num']);
            }

        }
        if ($homeCats) {
            array_splice($groupData, $homeCatIndex, 1, $homeCats);
        }

        foreach ($groupData as $k => $module){
            if(empty($module['products'])){
                unset($groupData[$k]);
            }
        }

        return $groupData;
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

    public function getNewProduct($params = array())
    {
        $select = array(

            'p.id',
            'p.product_sku',
            'p.product_image',
            'p.product_price as price',
            'p.product_quantity as in_stock',
            '(SELECT `weight_name` FROM `#__eshop_weightdetails` WHERE `weight_id` = p.product_weight_id AND  `language` = \'vi-VN\' limit 0, 1) as unit',
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

    public function getGroupInfo()
    {
        $params = array(
            'table' => '#__eshop_home_group',
            'no_quote' => true,
            'select' => 'id, title, group_type, max_num, list_type, view_more',
            'where' => array(
                'published = 1'
            ),
            'order' => 'ordering ASC'
        );
        return $this->getList($params);

    }

    public function buildCategoryBlock($products, $limit, $info = array())
    {
        $cats = $this->getCateogoriesTree($info);


        foreach ($products as $item) {
            foreach ($cats as $k => $cat) {
                if (in_array($item->category_id, $cat['ids'])) {
                    $cats[$k]['products'][] = $item;
                }
            }
        }
        foreach ($cats as $index => $e) {
            if (empty($e['products'])) {
                unset($cats[$index]);
            } else {
                unset($cats[$index]['ids']);
                $cats[$index]['products'] = array_slice($e['products'], 0, $limit);
            }
        }
        return $cats;

    }

    public function getCateogoriesTree($info = array())
    {
        $conf = \JFactory::getConfig();

        // Setting a location for your cached data.
        $cacheBase = JPATH_SITE . '/cache';

        // Your custom cachegroup
        $cacheGroup = 'homecache';

        // Lifetime for your cache
        $lifetime = 60;

        // Setting your options
        $options = array(
            'defaultgroup'  => $cacheGroup,
            'storage'       => $conf->get('cache_handler', ''),
            'caching'       => true,
            'cachebase'     => $cacheBase,
            'lifetime'      => $lifetime,
        );

        // Instantiate your cache object
        $cache = \JCache::getInstance('', $options);

        // Create $cacheDataId
        $cacheDataId = 'category_tree';


        // It's time to check for cached data
        if ( false &&  $cache->get($cacheDataId) !== false )
        {
            $list = $cache->get($cacheDataId); // We got data from cache
        }
        else
        {
            $params = array(
                'table' => '#__eshop_categories',
                'no_quote' => true,
                'select' => 'id, category_parent_id, ordering',
                'where' => array(
                    'published = 1',
                    'level = 3'
                ),
                'order' => 'category_parent_id ASC',
                'limit' => 500
            );
            $grandCats = $this->getList($params);


            $params = array(
                'table' => '#__eshop_categories',
                'no_quote' => true,
                'select' => 'id, category_parent_id, ordering',
                'where' => array(
                    'published = 1',
                    'level = 2'
                ),
                'order' => 'category_parent_id ASC',
                'limit' => 500
            );
            $result = $this->getList($params);


            $list = array();
            if ($result) {
                foreach ($result as $item) {
                    $cat = $item['category_parent_id'];
                    if (!isset($list[$cat])) {
                        $list[$cat] = array('ids' => array());
                    }
                    $list[$cat]['ids'][] = $item['id'];
                }

            }

            $params = array(
                'table' => '#__eshop_categories',
                'no_quote' => true,
                'select' => 'id, category_parent_id, ordering',
                'where' => array(
                    'published = 1',
                    'level = 1'
                ),
                'order' => 'category_parent_id ASC',
                'limit' => 500
            );
            $result = $this->getList($params);
            foreach ($result as $item) {
                $cat = $item['id'];
                if (!isset($list[$cat])) {
                    $list[$cat] = array('ids' => array());
                }
                $list[$cat]['ids'][] = $item['id'];
            }

            foreach ($grandCats as $grand) {
                foreach ($list as &$level1) {

                    if (in_array($grand['category_parent_id'], $level1['ids'])) {
                        $level1['ids'][] = $grand['id'];
                    }
                }

            }
            $ids = array_keys($list);
            if ($ids) {
                $params = array(
                    'as' => 'c',
                    'table' => '#__eshop_categories',
                    'no_quote' => true,
                    'select' => 'c.id, c.category_parent_id,c.category_image,c.category_image_icon,d.category_name, c.ordering',
                    'where' => array(
                        'c.published = 1',
                        'd.language = \'vi-VN\'',
                        'c.id IN (' . implode(',', $ids) . ')'
                    ),
                    'join' => array(
                        array(
                            'type' => 'LEFT',
                            'with_table' => '#__eshop_categorydetails AS d ON c.id = d.category_id'
                        )
                    ),
                    'order' => 'c.ordering ASC'
                );
                $result = $this->getList($params);
                foreach ($result as $item) {
                    $list[$item['id']]['image'] = '';//$item['category_image'] ? \JURI::base() . 'media/com_eshop/categories/' . $item['category_image'] : '';
                    //$list[$item['id']]['category_image_icon'] = $item['category_image_icon'];
                    $list[$item['id']]['title'] = $item['category_name'];
                    $list[$item['id']]['products'] = array();
                    $list[$item['id']]['group_type'] = 'home_category';
                    $list[$item['id']]['list_type'] = @$info['list_type'];
                    $list[$item['id']]['view_more'] = @$info['view_more'];
                    $list[$item['id']]['id'] = $item['id'];
                    $list[$item['id']]['ordering'] = $item['ordering'];
                }

            }
            uasort($list, function ($a, $b){
                if ($a['ordering'] == $b['ordering']) {
                    return 0;
                }
                return ($a['ordering'] < $b['ordering']) ? -1 : 1;
            });


            // Cache the data for the next time
            $cache->store($list, $cacheDataId);
        }
        return $list;
    }


}


