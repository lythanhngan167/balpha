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
use api\model\biz\shop\ShopAttributeGroupBiz;
use api\model\biz\shop\ShopAttributeBiz;

class ShopProductAttributeDao extends AbtractDao
{
    public $select = array(
        'ov.option_id',
        'ov.product_option_id',
        'ov.sku',
        'od.value AS `text`',
        'ov.price',
        'ov.`price_sign`',
        'ov.`price_type`',
        'ov.image',
        'ov.id'
    );

    public function getTable()
    {
        return '#__eshop_productoptionvalues';
    }

    public function getProductAttributes($params = array())
    {
        $select = array(
            'ad.value',
            'at.attribute_name',
            'gd.attributegroup_id',
            'gd.attributegroup_name'
        );
        $paramsDefault = array(
            'as' => 'pa',
            'table' => '#__eshop_productattributes',
            'no_quote' => true,
            'select' => implode(',', $select),
            'where' => array(
                'pa.published = 1',
                'att.published = 1'
            ),
            'join' => array(
                array(
                    'type' => 'LEFT',
                    'with_table' => '#__eshop_productattributedetails AS ad ON ad.productattribute_id = pa.id'
                ),
                array(
                    'type' => 'LEFT',
                    'with_table' => '#__eshop_attributedetails AS at ON at.attribute_id = pa.attribute_id'
                ),
                array(
                    'type' => 'LEFT',
                    'with_table' => '#__eshop_attributes AS att ON att.id = pa.attribute_id'
                ),
                array(
                    'type' => 'LEFT',
                    'with_table' => '#__eshop_attributegroupdetails AS gd ON gd.attributegroup_id = att.attributegroup_id'
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
        $list = array();
        if($result){
            $group = array();
            foreach($result as $item){
                if(!isset($group[$item['attributegroup_id']])){
                    $group[$item['attributegroup_id']] = array(
                        'id' =>$item['attributegroup_id'],
                        'name' => $item['attributegroup_name'],
                        'list' => array()
                    );
                }
                $biz = new ShopAttributeBiz();
                $biz->setAttributes(array(
                    'name' => $item['attribute_name'],
                    'value' => $item['value'],

                ));
                $group[$item['attributegroup_id']]['list'][] = $biz;
            }
            foreach($group as $item){
                $biz = new ShopAttributeGroupBiz();
                $biz->setAttributes($item);
                $list[] = $biz;
            }
        }
        return $list;

    }
}
