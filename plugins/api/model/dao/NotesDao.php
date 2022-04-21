<?php
/**
 * Created by PhpStorm.
 * User: lvson
 * Date: 4/26/2019
 * Time: 10:53 AM
 */

namespace api\model\dao;

use api\model\AbtractDao;
use api\model\biz\NotesBiz;
class NotesDao extends AbtractDao
{
    public $select = array(
        'id',
        'create_date',
        'note'
    );

    public function getTable()
    {
        return '#__notes';
    }

    public function insert($params = array())
    {
        $call = new \stdClass();

        $call->custommer_id = $params['customer_id'];
        $call->note = $params['reason'];
        $call->created_by = $params['user_id'];
        $call->create_date = date("Y-m-d H:i:s");

        // Insert the object into the user profile table.
        return $this->db->insertObject($this->getTable(), $call);

    }

    public function getHistory($params = array()){
        $paramsDefault = array(
            'select' => $this->select,
            'where' => array(),
            'order' => 'create_date DESC'
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
                $biz = new NotesBiz();
                $biz->setAttributes($item);
                $list[] = $biz;
            }
        }
        return $list;

    }

    

}
