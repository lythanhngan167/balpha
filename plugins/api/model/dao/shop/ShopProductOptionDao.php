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
use api\model\biz\ProjectBiz;
use api\model\biz\shop\ShopProductOptionBiz;
use api\model\biz\shop\ShopOptionBiz;

class ShopProductOptionDao extends AbtractDao
{
    public $select = array(
        'ov.product_option_id',
        'ov.option_id',
        'ov.sku',
        'od.value AS `text`',
        'ov.price',
        'ov.weight',
        'ov.weight_sign',
        'ov.`price_sign`',
        'ov.`price_type`',
        'ov.image',
        'ov.id',
        'ov.quantity'
    );

    public function getTable()
    {
        return '#__eshop_productoptionvalues';
    }

    public function getOptions($params = array())
    {
        $paramsDefault = array(
            'as' => 'ov',
            'no_quote' => true,
            'select' => implode(',', $this->select),
            'where' => array(),
            'join' => array(
                array(
                    'type' => 'LEFT',
                    'with_table' => '#__eshop_optionvaluedetails AS od ON ov.option_value_id = od.`optionvalue_id`'
                )
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
        return $result;
    }

    public function getProductOptions($params = array())
    {
        $select = array(
            'ov.required',
            'op.id',
            'op.option_type',
            'od.option_name',
        );
        $paramsDefault = array(
            'as' => 'ov',
            'table' => '#__eshop_productoptions',
            'no_quote' => true,
            'select' => implode(',', $select),
            'where' => array(
                'op.published = 1'
            ),
            'join' => array(
                array(
                    'type' => 'LEFT',
                    'with_table' => '#__eshop_options AS op ON op.id = ov.`option_id`'
                ),
                array(
                    'type' => 'LEFT',
                    'with_table' => '#__eshop_optiondetails AS od ON od.option_id = ov.`option_id`'
                ),

                
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
        $options = $this->getOptions($params);
        $list = array();
        if ($result) {
            foreach ($result as $item) {
                $item['options'] = array();
                foreach ($options as $op) {
                    $opBiz = new ShopOptionBiz();
                    $opBiz->setAttributes($op);
                    if ($op['option_id'] == $item['id']) {
                        $item['options'][] = $opBiz;
                    }
                }
                $biz = new ShopProductOptionBiz();
                $biz->setAttributes($item);
                $list[] = $biz;
            }
        }
        return $list;
    }
}
