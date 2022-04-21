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
use api\model\biz\ProjectBiz;

class MaxPickDao extends AbtractDao
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
        return '#__maxpick_level';
    }

    public function getMaxPick($params = array())
    {
        $paramsDefault = array(
            'select' => array('maxpick'),
            'where' => array(
                'level = '.(int)$params['level'],
                'category_customer = '.(int)$params['cat_id']
            )
        );
        $result = $this->get($paramsDefault);
        return $result['maxpick'];
    }


}
