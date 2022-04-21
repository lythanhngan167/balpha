<?php
/**
 * Created by PhpStorm.
 * User: lvson
 * Date: 4/26/2019
 * Time: 10:53 AM
 */

namespace api\model\dao;

use api\model\AbtractDao;
use api\model\biz\RegistrationBiz;

class RegistrationsDao extends AbtractDao
{
    public $select = array(
        'o.id',
        'o.name',
        'o.phone',
        'o.email',
        'o.address',
        'o.note',
        'o.created_date',
        'o.province',
        'o.status'
    );

    public function getTable()
    {
        return '#__registration';
    }

    public function getRegistrations($params = array()) {
        $paramsDefault = array(
            'as' => 'o',
            'no_quote' => true,
            'select' => $this->select,
            'where' => array(
                'o.state = 1',
                'o.is_exist = 0'
            ),
            'join' => array(
                array(
                    'type' => 'LEFT',
                    'with_table' => '#__users AS created_by ON created_by.id = o.created_by'
                ),
                array(
                    'type' => 'LEFT',
                    'with_table' => '#__users AS modified_by ON modified_by.id = o.modified_by'
                )
            ),
            'order' => 'o.id DESC'
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
                $biz = new RegistrationBiz();
                $biz->setAttributes($item);
                $list[] = $biz;
            }
        }
        return $list;
    }

}
