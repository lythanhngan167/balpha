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

class ProjectDao extends AbtractDao
{
    public $select = array(
        'id',
        'title',
        'short_description',
        'description',
        'is_recruitment',
        'price',
        'file_1',
        'file_2',
        'file_3',
        'file_4',
        'file_5'
    );

    public function getTable()
    {
        return '#__projects';
    }

    public function getProject($params = array())
    {
        $paramsDefault = array(
            'select' => $this->select,
            'where' => array(
                'state = 1'
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
        $list = array();
        $result = $this->getList($paramsDefault);

        if ($result) {
            foreach ($result as $item) {
                $params['id'] = $item['id'];
                $params['price'] = $item['price'];
                if (@$params['include_project']) {
                    $item['list'] = $this->getProjectInfo($params);
                }
                $biz = new ProjectBiz();
                $biz->setAttributes($item);
                $list[] = $biz;
            }
        }
        return $list;
    }

    public function getProjectInfo($params = array())
    {
        $sql = 'SELECT
                    c.id,
                    c.title,
                    c.note AS price,
                    IF(lv.maxpick > 0, lv.maxpick, ' . (int)$params['max_pick'] . ' ) as max_pick,
                    (
                    SELECT
                        COUNT(*)
                    FROM
                        `#__customers` AS cus
                    WHERE
                        cus.category_id = c.id AND cus.project_id = ' . $this->db->quote($params['id']) . ' AND cus.sale_id = 0 AND cus.state = 1
                ) AS amount
                FROM
                    `#__categories` AS c
                LEFT JOIN `#__maxpick_level` AS lv
                ON
                    (
                        lv.level = ' . $this->db->quote($params['level_id']) . ' AND lv.category_customer = c.id AND lv.state = 1
                    )
                WHERE
                    c.`extension` = \'com_customer\' AND c.`published` = 1
                    AND c.id in (151)
                ORDER BY
                    c.`rgt` ASC';
                    //c.id in 150 tra lai

        $result = $this->getListBySql($sql);
        $list = array();

        if ($result) {
            foreach ($result as $item) {
                $biz = new CategoryBiz();
                $item['price'] = $item['id'] != 150 ? $params['price'] : 0;
                $biz->setAttributes($item);
                $list[] = $biz;
            }
        }
        return $list;
    }
}
