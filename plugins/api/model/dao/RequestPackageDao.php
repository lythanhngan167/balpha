<?php

namespace api\model\dao;

use api\model\AbtractDao;

class RequestPackageDao extends AbtractDao
{
    public $select = array(
        'id',
        'created_by',
        'name',
        'email',
        'phone',
        'job',
        'address',
        'note',
        'province',
        'status',
        'company',
        'services'
    );

    public function getTable()
    {
        return '#__request_package';
    }

    public function getRequests($params = array())
    {
        $paramsDefault = array(
            'select' => $this->select,
            'where' => array(
                'state = 1',
            ),
            'order' => 'id DESC'
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

        return $result;
    }
}
