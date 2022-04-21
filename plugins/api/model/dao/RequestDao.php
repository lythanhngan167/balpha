<?php
/**
 * Created by PhpStorm.
 * User: lvson
 * Date: 4/26/2019
 * Time: 10:53 AM
 */

namespace api\model\dao;

use api\model\AbtractDao;
use api\model\biz\RequestBiz;
use api\model\dao\UserDao;

class RequestDao extends AbtractDao
{
    public $select = array(
        'id',
        'title',
        'content',
        'request_id',
        'receipt_id',
        'receipt_id_2',
        'status_id',
        'created_date',
    );

    public function getTable()
    {
        return '#__request';
    }

    public function getRequests($params = array())
    {
        $paramsDefault = array(
            'select' => $this->select,
            'where' => array(
                'state = 1',
            ),
            'order' => 'created_date DESC'
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
        if ($result) {
            $uids = array();
            foreach ($result as $item) {
                $uids[] = $item['request_id'];
                $uids[] = $item['receipt_id'];
                $uids[] = $item['receipt_id_2'];
            }
            $uids = array_filter($uids);
            if ($uids) {
                $userDao = new UserDao();
                $params = array(
                    'select' => array('id', 'name', 'username'),
                    'where' => array(
                        'id IN (' . implode(',', $uids) . ')',
                    ),
                );
                $users = $userDao->getList($params);
                $usersList = array();
                if ($users) {
                    foreach ($users as $item) {
                        $usersList[$item['id']] = $item;
                    }
                }
            }
            
            foreach ($result as $item) {
                if (isset($usersList[$item['request_id']])) {
                    $item['request_name'] = $usersList[$item['request_id']]['name'];
                    $item['request_phone'] = $usersList[$item['request_id']]['username'];
                }
                if (isset($usersList[$item['receipt_id']])) {
                    $item['receipt_name'] = $usersList[$item['receipt_id']]['name'];
                    $item['receipt_phone'] = $usersList[$item['receipt_id']]['username'];
                }
                if (isset($usersList[$item['receipt_id_2']])) {
                    $item['receipt_2_name'] = $usersList[$item['receipt_id_2']]['name'];
                    $item['receipt_2_phone'] = $usersList[$item['receipt_id_2']]['username'];
                }
                $biz = new RequestBiz();
                $biz->setAttributes($item);
                $list[] = $biz;
            }
        }
        return $list;
    }

    public function getRequestInfo($id)
    {
        $paramsDefault = array(
            'select' => $this->select,
            'where' => array(
                'block = 0',
                'id = ' . $this->db->quote($id),
            ),
        );
        $result = $this->get($paramsDefault);

        if ($result) {
            $biz = new RequestBiz();
            $biz->setAttributes($result);
            return $biz;
        }
        return null;
    }

    public function updateStatus($params = array())
    {
        // Insert the object into the user profile table.
        $status = new \stdClass();
        $status->id = $params['id'];
        $status->status_id = $params['status_id'];
        $status->modified_by = $params['modified_by'];
        return $this->db->updateObject($this->getTable(), $status, 'id');
    }
}
