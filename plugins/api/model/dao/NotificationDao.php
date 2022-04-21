<?php
/**
 * Created by PhpStorm.
 * User: lvson
 * Date: 4/26/2019
 * Time: 10:53 AM
 */

namespace api\model\dao;

use api\model\AbtractDao;
use api\model\biz\NotificationBiz;

class NotificationDao extends AbtractDao
{
    public $select = array(
        'id',
        'title',
        'message'
    );

    public function getTable()
    {
        return '#__notification';
    }

    public function getContent($params = array())
    {
        $paramsDefault = array(
            'no_quote' => true,
            'select' => implode(',', $this->select),
            'where' => array(
                'state = 1',
                'show_app = 1'
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
                $biz = new NotificationBiz();
                $biz->setAttributes($item);
                $list[] = $biz;
            }
        }
        return $list;
    }

    public function getContentInfo($params = array())
    {
        $paramsDefault = array(
            'no_quote' => true,
            'select' => implode(',', $this->select),
            'where' => array(
                'state = 1',
                'show_app = 1',
            )
        );
        if ($params) {
            foreach ($params as $item) {
                $paramsDefault['where'][] = $item;
            }
        }
        $result = $this->get($paramsDefault);
        if ($result) {
            $biz = new NotificationBiz();
            if($result['message']){
                $result['message'] = nl2br($result['message']);
            }
            $biz->setAttributes($result);
            return $biz;
        }
        return null;
    }
}
