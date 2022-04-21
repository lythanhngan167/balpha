<?php
/**
 * Created by PhpStorm.
 * User: lvson
 * Date: 4/26/2019
 * Time: 10:53 AM
 */

namespace api\model\dao\shop;

use api\model\AbtractDao;
use api\model\biz\shop\ShopCategoryBiz;

class ShopStockDao extends AbtractDao
{
    public $select = array(
        'c.id',
        'c.category_parent_id',
        'c.category_image',
        'c.category_image_icon',
        'c.level',
        'd.category_name'
    );

    public function getTable()
    {
        return '#__eshop_stock';
    }

    public function getAvailabelStock($params = array())
    {
        $paramsDefault = array(
            'table' => '#__eshop_stock_product',
            'no_quote' => true,
            'select' => 'stock_id',
            'where' => array(
                'product_id = ' . (int)$params['id'],
                'qty >= ' . (int)$params['quantity'],
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
        $result = $this->get($paramsDefault);
        return $result['stock_id'];
    }

    public function getStockQty($id){
        $paramsDefault = array(
            'table' => '#__eshop_stock_product',
            'no_quote' => true,
            'select' => 'MAX(qty) as qty',
            'where' => array(
                'product_id = ' . (int)$id
            )
        );
        $result = $this->get($paramsDefault);
        return $result['qty'];
    }

    public function getAvailabelStocks($params = array())
    {
        $paramsDefault = array(
            'table' => '#__eshop_stock_product',
            'no_quote' => true,
            'select' => 'stock_id',
            'where' => array(
                'product_id = ' . (int)$params['id'],
                'qty >= ' . (int)$params['quantity'],
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
                $list[] = $item['stock_id'];
            }
        }
        return $list;
    }


}
