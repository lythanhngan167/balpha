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

class ShopCategoryDao extends AbtractDao
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
        return '#__eshop_categories';
    }

    public function getCategories($params = array())
    {
        $paramsDefault = array(
            'as' => 'c',
            'no_quote' => true,
            'select' => implode(',', $this->select),
            'where' => array(
                'c.published = 1'
            ),
            'join' => array(
                array(
                    'type' => 'LEFT',
                    'with_table' => '#__eshop_categorydetails AS d ON c.id = d.category_id'
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

        $sorted = $this->_sortCategories($result);
        return $sorted;
    }

    private function _sortCategoriesByLevel($data)
    {
        $level = array();
        foreach ($data as $item) {
            $biz = new ShopCategoryBiz();
            $biz->setAttributes($item);
            $level[$item['level']][] = $biz;
        }
        return $level;
    }

    private function _sortCategories($result)
    {
        $data = $this->_sortCategoriesByLevel($result);
        $reversed = array_reverse($data);
        $count = count($reversed);
        for ($i = 0; $i < $count; $i++) {
            if (!isset($reversed[$i + 1])) {
                break;
            }
            foreach ($reversed[$i] as $cLevel) {
                foreach ($reversed[$i + 1] as &$nextLevel) {
                    if ($nextLevel->id == $cLevel->category_parent_id) {
                        $nextLevel->child[] = $cLevel;
                    }
                }
            }

        }
        return $reversed[$i];
    }

    public function getListCategoryIds($id)
    {
        $ids = array($id);
        $result = $this->getIdsByParent(array($id));
        if ($result) {
            $child = array();
            foreach ($result as $item) {
                $child[] = $item['id'];
            }
            $ids = array_merge($ids, $child);
            if ($result) {
                $result = $this->getIdsByParent($child);
                if ($result) {
                    foreach ($result as $item) {
                        $ids[] = $item['id'];
                    }
                }
            }

        }
        return $ids;
    }

    public function getIdsByParent($ids)
    {
        $ids = (array)$ids;
        if(count($ids)){
            $params = array(
                'select' => 'id',
                'where' => array(
                    'category_parent_id IN (' . implode(',', $ids) . ')'
                )
            );
            return $this->getList($params);
        }
        return array();

    }


}
