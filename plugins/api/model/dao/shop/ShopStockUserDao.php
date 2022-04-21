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

class ShopStockUserDao extends AbtractDao
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
        return '#__eshop_stock_user';
    }

    public function getStock($params = array())
    {
        $paramsDefault = array(
            'no_quote' => true,
            'select' => 'stock_id',
            'where' => array(
                'user_id = ' . (int)$params['user_id']
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

    public function isStockAdmin($params = array())
    {
        $paramsDefault = array(
            'no_quote' => true,
            'table' => '#__eshop_stock_user',
            'select' => 'stock_id',
            'where' => array(
                'is_stock_manager = 1',
                'user_id = ' . (int)$params['user_id'],
                'stock_id = ' . (int)$params['id'],
            )
        );
        $result = $this->get($paramsDefault);
        return $result['stock_id'] > 0;
    }

    public function getStoreAdmin($params = array())
    {
        $paramsDefault = array(
            'no_quote' => true,
            'table' => '#__store',
            'select' => 'id, title',
            'where' => array(
                'adminstore = ' . (int)$params['user_id']
            )
        );
        $result = $this->getList($paramsDefault);
        return $result;
    }


}
