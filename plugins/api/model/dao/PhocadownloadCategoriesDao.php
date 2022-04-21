<?php
/**
 * Created by PhpStorm.
 * User: lvson
 * Date: 4/26/2019
 * Time: 10:53 AM
 */

namespace api\model\dao;

use api\model\AbtractDao;
use api\model\biz\PhocadownloadCategoriesBiz;
use api\model\biz\PhocadownloadBiz;

class PhocadownloadCategoriesDao extends AbtractDao
{
    public $select = array(
        'id',
        'title',
        'image'
    );

    public function getTable()
    {
        return '#__phocadownload_categories';
    }

    public function getCategories()
    {
        $paramsDefault = array(
            'select' => $this->select,
            'where' => array(
                'published = 1'
            ),
            'order' => 'ordering ASC'
        );
        $result = $this->getList($paramsDefault);

        $list = array();
        foreach ($result as $item) {
            $biz = new PhocadownloadCategoriesBiz();
            $biz->setAttributes($item);
            $list[] = $biz;
        }
        return $list;
    }

    public function getContent($ids, $limit)
    {
        $sql = array();
        foreach ($ids as $id) {
            $sql[] = '(SELECT id, catid, title, filename, description, features, notes
                FROM #__phocadownload
                WHERE catid = ' . (int)$id . '
                ORDER BY id DESC 
                LIMIT 0 , ' . (int)$limit . ')';
        }
        $result = $this->getListBySql(implode(' UNION ', $sql));
        $list = array();
        foreach ($result as $item) {
            $biz = new PhocadownloadBiz();
            $biz->setAttributes($item);
            if (!isset($list[$item['catid']])) {
                $list[$item['catid']] = array();
            }
            $list[$item['catid']][] = $biz;
        }
        return $list;
    }


}
