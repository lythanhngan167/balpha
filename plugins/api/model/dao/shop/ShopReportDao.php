<?php
/**
 * Created by PhpStorm.
 * User: lvson
 * Date: 4/26/2019
 * Time: 10:53 AM
 */

namespace api\model\dao\shop;

use api\model\AbtractDao;
use api\model\biz\shop\ShopCategoryBiz;

class ShopReportDao extends AbtractDao
{
    public $select = array(
        'c.id',
        'c.category_parent_id',
        'c.category_image',
        'c.category_image_icon',
        'c.level',
        'd.category_name'
    );

    public function getTable()
    {
        return '#__eshop_stock';
    }

    public function reportByWeek($params = array())
    {
        $where = array();
        $date = date('Y-m');

        
        $sql = 'SELECT COUNT(*) AS num,
                       report_date
                FROM
                  (SELECT DATE_FORMAT(modified_date, \'%Y-%m-%d\') AS report_date
                   FROM `prfwj_eshop_orderproducts`
                   WHERE ' . implode(' AND ', $params['where']) . '
                
                   GROUP BY DATE_FORMAT(modified_date, \'%Y-%m-%d\'),
                        order_id) AS a
                GROUP BY a.report_date';
        $result = $this->db->setQuery($sql)->loadAssocList();
        $list = array();
        if ($result) {
            foreach ($result as $item) {
                $list[$item['report_date']] = $item['num'];
            }
        }
        return $list;
    }

    public function reportByMonth($params = array())
    {
        $where = array();
        $date = date('Y-m');
        $where[] = 'stock_id = ' . (int)$params['stock_id'];
        $where[] = 'DATE_FORMAT(modified_date, \'%Y-%m\') = \'' . $date . '\'';
        $sql = 'SELECT COUNT(*) AS num,
                       report_date
                FROM
                  (SELECT DATE_FORMAT(modified_date, \'%Y-%m\') AS report_date
                   FROM `prfwj_eshop_orderproducts`
                   WHERE ' . implode(' AND ', $where) . '
                
                   GROUP BY DATE_FORMAT(modified_date, \'%Y-%m\'),
                        order_id) AS a
                GROUP BY a.report_date';
        $result = $this->db->setQuery($sql)->loadAssocList();
        $list = array();
        if ($result) {
            foreach ($result as $item) {
                $list[$item['report_date']] = $item['num'];
            }
        }
        return $list;
    }


    public function reportByDate($params = array())
    {
        $where = array();
        $date = date('Y-m-d');
        $where[] = 'stock_id = ' . (int)$params['stock_id'];
        $where[] = 'DATE_FORMAT(modified_date, \'%Y-%m-%d\') = \'' . $date . '\'';
        $sql = 'SELECT COUNT(*) AS num,
                       status_id
                FROM
                  (SELECT status_id
                   FROM `#__eshop_orderproducts`
                   WHERE ' . implode(' AND ', $where) . '
                   GROUP BY status_id,
                            order_id) AS a
                GROUP BY a.status_id';
        $result = $this->db->setQuery($sql)->loadAssocList();
        $list = array();
        if ($result) {
            foreach ($result as $item) {
                $list[$item['status_id']] = $item['num'];
            }
        }
        return $list;
    }


}
