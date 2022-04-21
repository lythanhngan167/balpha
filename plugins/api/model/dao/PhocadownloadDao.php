<?php
/**
 * Created by PhpStorm.
 * User: lvson
 * Date: 4/26/2019
 * Time: 10:53 AM
 */

namespace api\model\dao;

use api\model\AbtractDao;
use api\model\biz\PhocadownloadBiz;

class PhocadownloadDao extends AbtractDao
{
    public $select = array(
        'id',
        'title',
        'filename',
        'description',
        'features',
        'notes',
        'effected_date',
        'issued_date'
    );

    public function getTable()
    {
        return '#__phocadownload';
    }

    public function getDocuments($params = array())
    {
        $paramsDefault = array(
            'select' => $this->select,
            'where' => array(
                'published = 1'
            )
        );
        if (isset($params['where']) && $params['where']) {
            foreach ($params['where'] as $item) {
                $paramsDefault['where'][] = $item;
            }
        }
        if($params){
            foreach ($params as $k => $item) {
                if($k === 'where'){
                    continue;
                }
                $paramsDefault[$k] = $item;
            }
        }
        $list = array();
        $result = $this->getList($paramsDefault);

        if ($result) {
            foreach ($result as $item) {
                $biz = new PhocadownloadBiz();
                $biz->setAttributes($item);
                $list[] = $biz;
            }
        }
        return $list;
    }

    public function getDocumentInfo($params = array())
    {
        $paramsDefault = array(
            'select' => $this->select,
            'where' => array(
                'published = 1'
            )
        );
        if ($params) {
            foreach ($params as $item) {
                $paramsDefault['where'][] = $item;
            }
        }
        $result = $this->get($paramsDefault);
        if ($result) {
            $biz = new PhocadownloadBiz();
            $biz->setAttributes($result);
            return $biz;
        }
        return null;
    }
}
