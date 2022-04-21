<?php
/**
 * Created by PhpStorm.
 * User: lvson
 * Date: 4/26/2019
 * Time: 10:53 AM
 */

namespace api\model\dao;

use api\model\AbtractDao;
use api\model\biz\CategoryBiz;

class CategoryDao extends AbtractDao
{
    public $select = array(
        'id',
        'title',
        'short_description',
        'description',
        'file_1',
        'file_2',
        'file_3',
        'file_4',
        'file_5'
    );

    public function getTable()
    {
        return '#__categories';
    }

    public function getPrice($params = array())
    {
        $paramsDefault = array(
            'select' => array('note'),
            'where' => array(
                'id = '.(int)$params['cat_id']
            )
        );
        $result = $this->get($paramsDefault);
        return $result['note'];
    }

    public function getSubCategory($id)
    {
        $paramsDefault = array(
            'select' => array(
                'id',
                'title',
                'description',
                'params'
            ),
            'where' => array(
                'published = 1',
                'parent_id = ' . (int)$id,
            ),
            'order' => 'lft ASC'
        );
        $result = $this->getList($paramsDefault);
        $list = array();
        if ($result) {
            foreach ($result as $item) {
                $biz = new CategoryBiz();
                $biz->setAttributes($item);
                $list[] = $biz;
            }
        }
        return $list;
    }


}
