<?php
/**
 * Created by PhpStorm.
 * User: lvson
 * Date: 4/26/2019
 * Time: 10:53 AM
 */

namespace api\model\dao;

use api\model\AbtractDao;
use api\model\biz\RechargeBiz;
use Joomla\CMS\Factory;

class RechargeDao extends AbtractDao
{
    public $select = array(
        'id',
        'code',
        'bank_name',
        'amount',
        'note',
        '`status`',
        'created_by',
        'created_time'
    );

    public function getTable()
    {
        return '#__recharge';
    }

    public function getHistory($params = array())
    {
        $select = implode(',', $this->select);
        $paramsDefault = array(
            'no_quote' => true,
            'select' => $select,
            'where' => array(
                'state = 1'
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


            $ids = array();
            foreach ($result as $item) {
                $ids[] = $item['id'];
            }
            foreach ($result as $item) {

                $biz = new RechargeBiz();
                $biz->setAttributes($item);
                $list[] = $biz;
            }
        }
        return $list;
    }




}
