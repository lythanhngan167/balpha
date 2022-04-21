<?php
/**
 * Created by PhpStorm.
 * User: lvson
 * Date: 4/26/2019
 * Time: 10:53 AM
 */

namespace api\model\dao;

use api\model\AbtractDao;
use api\model\biz\UserBiz;

class UserActivationDao extends AbtractDao
{
    public $select = array(
        'id',
        'user_id',

    );

    public function getTable()
    {
        return '#__user_activation';
    }

    public function insert($data){
        $data->code = rand ( 1000 , 9999 );
        $data->expired_time = time() + 30*60*60;
        return $this->db->insertObject($this->getTable(), $data);
    }

    public function find($params)
    {
        $paramsDefault = array(
            'select' => $this->select,
            'where' => array(
                'code = ' . $this->db->quote($params['code']),
                'token = ' . $this->db->quote($params['token']),
                'expired_time > ' . time()
            )
        );
        return $this->get($paramsDefault);
    }

    public function updateResetTime($id){
        $object = new \stdClass();
        $object->id = $id;
        $object->expired_time = time();
        return $this->db->updateObject($this->getTable(), $object, 'id');

    }

}
