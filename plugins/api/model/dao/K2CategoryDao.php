<?php
/**
 * Created by PhpStorm.
 * User: lvson
 * Date: 4/26/2019
 * Time: 10:53 AM
 */

namespace api\model\dao;

use api\model\AbtractDao;
use api\model\biz\K2CategoryBiz;

class K2CategoryDao extends AbtractDao
{
    public $select = array(
        'c.*',
        'g.title AS groupname',
        'exfg.name as extra_fields_group',
        
    );

    public function getTable()
    {
        return '#__k2_categories';
    }

    public function getCategory($params = array())
    {
        $paramsDefault = array(
            'no_quote' => true,
            'as' => 'c',
            'select' => $this->select,
            'where' => array(
                'c.id>0',
                'c.trash=0',
                'c.id IN (' . implode(',', $params['categoryTree']) . ')'
            ),
            'order' => 'c.ordering',
            'join' => array(
                array(
                    'type' => 'LEFT',
                    'with_table' => '#__viewlevels AS g ON g.id = c.access'
                ),
                array(
                    'type' => 'LEFT',
                    'with_table' => '#__k2_extra_fields_groups AS exfg ON exfg.id = c.extraFieldsGroup'
                )
            )
            
        );
        $rows = $this->getList($paramsDefault);
        $result = array();

        foreach($rows as $row) {
            $biz = new K2CategoryBiz();
            $biz->setAttributes($row);
            $result[] = $biz;
        }

        return $result;
    }

}
