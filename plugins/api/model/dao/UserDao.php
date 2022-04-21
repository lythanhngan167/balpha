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

require_once(JPATH_SITE . '/components/com_eshop/helpers/helper.php');
class UserDao extends AbtractDao
{
    public $select = array(
        'id',
        'name',
        'username',
        'email',
        'level_tree',
        'briclevel',
        'invited_id',
        'id_biznet'
    );

    public function getTable()
    {
        return '#__users';
    }

    public function getUsers($params = array())
    {
        $paramsDefault = array(
            'select' => $this->select,
            'where' => array(
                'block = 0'
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
        $result = $this->get($paramsDefault);

        if ($result) {
            $biz = new UserBiz();
            $biz->setAttributes($result);
            return $biz;
        }
        return null;
    }

    public function getUserInfo($userName)
    {
        $paramsDefault = array(
            'select' => $this->select,
            'where' => array(
                'block = 0',
                'username = ' . $this->db->quote($userName)
            )
        );
        $result = $this->get($paramsDefault);

        if ($result) {
            $biz = new UserBiz();
            $biz->setAttributes($result);
            return $biz;
        }
        return null;
    }

    public function loadUser($id)
    {
        $sql = 'SELECT * FROM ' . $this->getTable() . ' WHERE id = ' . $id;
        return $this->db->setQuery($sql)->loadObject();
    }

    public function getMembers($params)
    {
        $select = array(
            'u.name',
            'u.level_tree',
            'u.id',
            'u.approved',
            'u.id_biznet',
            '(SELECT country_name FROM #__eshop_countries WHERE id = u.province) as province',
            'DATE_FORMAT(u.registerDate, \'%d/%m/%Y\') as registerDate'
        );
        $paramsDefault = array(
            'select' => $select,
            'as' => 'u',
            'no_quote' => true,
            'where' => array(
                'u.block = 0'
            ),
            'join' => array(
                array(
                    'type' => 'RIGHT',
                    'with_table' => '#__user_usergroup_map AS m ON ( m.user_id = u.id AND m.group_id = 3)'
                )
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
        $result = $this->getList($paramsDefault);

        if ($result) {
            $month = date('m');
            $year = date('Y');
            foreach ($result as &$item) {
                // Get revenue amount
                $item['am_revenue'] = \EshopHelper::getRevenueAmount($item['id'], $month, $year, 'individual');
                // $item['m_id'] = $item['level_tree'] . str_pad($item['id'], 6, "0", STR_PAD_LEFT);
                $item['m_id'] = $item['id_biznet'];
                $item['level'] = $item['level_tree'] ? 'Cấp ' . $item['level_tree'] : '';
            }
            return $result;
        }
        return null;
    }


    public function getListMembers($params)
    {
        $select = array(
            'u.name',
            'u.level_tree',
            'u.id'

        );
        $paramsDefault = array(
            'select' => $select,
            'as' => 'u',
            'no_quote' => true,
            'where' => array(
                'u.block = 0',
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
        $result = $this->getList($paramsDefault);

        if ($result) {

            foreach ($result as &$item) {

                $item['name'] .= ' (Cấp ' . $item['level_tree'] . ')';
            }
            return $result;
        }
        return null;
    }


    public function getAgencyByIdOrPhonenumber($idBiznet, $phone)
    {
        if (isset($idBiznet) && isset($phone)) {
            $agency_group_id = 3;
            $select = array(
                //'u.username as phone',
                'u.name',
                'u.username',
                'u.level_tree',
                'u.id',
                'u.approved',
                'u.id_biznet',
                '(SELECT country_name FROM #__eshop_countries WHERE id = u.province) as province',
                'DATE_FORMAT(u.registerDate, \'%d/%m/%Y\') as registerDate'
            );
            $paramsDefault = array(
                'select' => $select,
                'as' => 'u',
                'no_quote' => true,
                'where' => array(
                    'u.block = 0',
                    '( u.username = "' . $phone . '" OR u.id_biznet ="' . $idBiznet . '")'
                ),
                'join' => array(
                    array(
                        'type' => 'INNER',
                        'with_table' => '#__user_usergroup_map AS m ON ( m.user_id = u.id AND m.group_id = ' . $agency_group_id . ')'
                    )
                ),
                'order' => 'u.name,u.id_biznet ASC',
                'limit' => 1
            );
            if (isset($params['where']) && $params['where']) {
                foreach ($params['where'] as $item) {
                    $paramsDefault['where'][] = $item;
                }
            }

            if (isset($params['where']) && $params['where']) {
                foreach ($params['where'] as $item) {
                    $paramsDefault['where'][] = $item;
                }
            }

            $result = $this->get($paramsDefault);

            if ($result) {
                foreach ($result as &$item) {
                    $item['m_id'] = $item['id_biznet'];
                }
                return $result;
            }
        }
        return null;
    }
}
