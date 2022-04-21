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
use api\model\biz\ProjectBiz;

require_once(JPATH_SITE . '/components/com_eshop/helpers/customer.php');
require_once(JPATH_SITE . '/components/com_eshop/helpers/helper.php');

class ShopProductDao extends AbtractDao
{
    public $select = array(
        'p.id',
        'p.product_sku',
        'p.product_image',
        'p.product_price as price',
        'p.product_quantity as in_stock',
        '(SELECT `weight_name` FROM `#__eshop_weightdetails` WHERE `weight_id` = p.product_weight_id AND  `language` = \'vi-VN\' limit 0, 1) as unit',
        '(SELECT `group_id` FROM `#__eshop_home_products` WHERE `product_id` = p.id limit 0, 1) as p_group',
        'd.product_name as name'
    );

    public function getTable()
    {
        return '#__eshop_products';
    }

    public function getProducts($params = array())
    {
        $paramsDefault = array(
            'as' => 'p',
            'no_quote' => true,
            'select' => implode(',', $this->select),
            'where' => array(
                'p.published = 1'
            )
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
                $biz->setAttributes($item);
                $list[] = $biz;
            }
        }
        return $list;
    }

    public function getExtraInfo($id)
    {
        $params = array(
            'table' => '#__eshop_productdetails',
            'no_quote' => true,
            'select' => 'product_desc as full_des, product_short_desc as short_des',
            'where' => array(
                'product_id = ' . (int)$id,
                'language = \'vi-VN\'',
            )
        );
        return $this->get($params);
    }

    public function getProductInfo($id)
    {
        $params = array(
            'as' => 'p',
            'table' => '#__eshop_products',
            'no_quote' => true,
            'select' => 'p.product_sku, m.manufacturer_name as manufacturer, w.weight_name, p.product_weight as weight,  0 as total_weigth, p.product_quantity as in_stock, p.product_price',
            'where' => array(
                'p.id = ' . (int)$id
            ),
            'join' => array(
                array(
                    'type' => 'LEFT',
                    'with_table' => '#__eshop_manufacturerdetails AS m ON m.id = p.manufacturer_id'
                ),
                array(
                    'type' => 'LEFT',
                    'with_table' => '#__eshop_weightdetails AS w ON ( w.weight_id = p.product_weight_id AND w.language = \'vi-VN\')'
                )
            )
        );
        return $this->get($params);
    }


    public function getCampaign($params = array())
    {
        $paramsDefault = array(
            'as' => 'h',
            'table' => '#__eshop_home_products',
            'no_quote' => true,
            'select' => implode(',', $this->select),
            'where' => array(
                'p.published = 1'
            )
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
                $biz->setAttributes($item);
                $list[] = $biz;
            }
        }
        return $list;
    }


}
