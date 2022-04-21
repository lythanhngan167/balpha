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


class ReturnStoreDao extends AbtractDao
{
    public $select = array(
        'id',
        'title',
        'short_description',
        'description',
        'file_1',
        'file_2',
        'file_3',
        'file_4',
        'file_5'
    );

    public function getTable()
    {
        return '#__return_store';
    }

    public function insert($params = array())
    {
        $returnStore = new \stdClass();
        $returnStore->user_id = $params['user_id'];
        $returnStore->customer_id = $params['customer_id'];
        $returnStore->category_id = $params['category_id'];
        $returnStore->project_id = $params['project_id'];
        $returnStore->status_id = $params['status_id'];
        $returnStore->buy_date = $params['buy_date'];
        $returnStore->status_return_cancel = $params['status_return_cancel'];
        $returnStore->created_date = date("Y-m-d H:i:s");
        $returnStore->state = 1;

        // Insert the object into the user profile table.
        return $this->db->insertObject($this->getTable(), $returnStore);

    }


}
