<?php

/**
 * Created by PhpStorm.
 * User: lvson
 * Date: 4/26/2019
 * Time: 10:53 AM
 */

namespace api\model\dao;

use api\model\AbtractDao;
use api\model\biz\UserNotificationBiz;


class UserNotificationDao extends AbtractDao
{
    public $select = array(
        'id',
        'title',
        'message',
        'user_id',
        'seen_flag',
        'created_date'
    );


    public function getTable()
    {
        return '#__notifications_user';
    }

    public function getContent($params = array())
    {
        $paramsDefault = array(
            'no_quote' => true,
            'select' => implode(',', $this->select),
            'where' => array(
                'state = 1',
                // 'show_app = 1'
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
                $biz = new UserNotificationBiz();
                $biz->setAttributes($item);
                $list[] = $biz;
            }
        }
        return $list;
    }
}
