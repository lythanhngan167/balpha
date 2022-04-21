<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/27/2019
 * Time: 9:51 AM
 */


use api\model\dao\CategoryDao;
use api\model\dao\CustomerDao;

defined('_JEXEC') or die('Restricted access');

class UsersApiResourceReports extends ApiResource
{
    static public function routes()
    {
        $routes[] = 'reports/';

        return $routes;
    }

    public function delete()
    {
        $this->plugin->setResponse('in delete');
    }


    /**
     * @OA\Post(
     *     path="/api/users/reports",
     *     tags={"User"},
     *     summary="Report for user",
     *     description="Report for user",
     *     operationId="post",
     *     security = { { "bearerAuth": {} } },
     *     @OA\RequestBody(
     *         required=true,
     *         description="Get project list",
     *         @OA\JsonContent(ref="#/components/schemas/ReportForm"),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(ref="#/components/schemas/ReportForm"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Invalid request",
     *     )
     * )
     */
    public function post()
    {
        $data = $this->getRequestData();
        $user = JFactory::getUser();
        $dao = new CustomerDao();
        $data['sale_id'] = $user->id;
        $params = array(
            'no_quote' => true,
            'select' => 'COUNT(*) AS num, SUM(total_revenue) AS total_revenue, status_id,category_id,project_id'
        );
        if ($data['project_id'] && (int)$data['project_id'] > 0) {
            $params['where'][] = 'project_id = ' . (int)$data['project_id'];
        }

        if ($data['start_date'] && trim($data['start_date']) != '') {
            $params['where'][] = 'buy_date >= ' . $dao->db->quote(trim($data['start_date']) . ' 00:00:00');


        }
        if ($data['end_date'] && trim($data['end_date']) != '') {
            $params['where'][] = 'buy_date <= ' . $dao->db->quote(trim($data['end_date']) . ' 23:59:59');

        }

        $params['where'][] = 'state  = 1';
        $params['where'][] = 'sale_id  = ' . (int)$user->id;
        $params['group'] = 'status_id, category_id, project_id';


        $result = $dao->report($params);
        if ($data['type'] == 'customer') {
            $result = $this->prepareCustomerData($result);
            $result = array_values($result);
            $totalRevenue = $this->_getTotalRevenue($data);
            $totalMoney = $this->_getTotalMoney($data);

            $totalRevenue = number_format($totalRevenue,0,",",".").' '.BIZ_XU;
            $totalMoney = number_format($totalMoney,0,",",".").' '.BIZ_XU;
            $returnData = array('list' => $result, 'total_revenue' => $totalRevenue, 'total_money' => $totalMoney);
        } else {
            $result = $this->prepareCategoryData($result);
            $returnData = array_values($result);
        }




        $this->plugin->setResponse($returnData);
    }

    private function _getTotalRevenue($data)
    {
        $dao = new CustomerDao();
        $params = array(
            'where' => array(
                'state = 1',
                'sale_id = ' . (int)$data['sale_id'],
            )
        );
        if ($data['project_id'] && (int)$data['project_id'] > 0) {
            $params['where'][] = 'project_id = ' . (int)$data['project_id'];
        }

        if ($data['start_date'] && trim($data['start_date']) != '') {
            $params['where'][] = 'buy_date >= ' . $dao->db->quote(trim($data['start_date']) . ' 00:00:00');
        }
        if ($data['end_date'] && trim($data['end_date']) != '') {
            $params['where'][] = 'buy_date <= ' . $dao->db->quote(trim($data['end_date']) . ' 23:59:59');
        }
        return $dao->getTotalRevenue($params);

    }

    private function _getTotalMoney($data)
    {
        $dao = new CustomerDao();
        $params = array(
            'where' => array(
                'created_by = ' . (int)$data['sale_id'],
            )
        );
        if ($data['project_id'] && (int)$data['project_id'] > 0) {
            $params['where'][] = 'project_id = ' . (int)$data['project_id'];
        }

        if ($data['start_date'] && trim($data['start_date']) != '') {
            $params['where'][] = 'create_date >= ' . $dao->db->quote(trim($data['start_date']) . ' 00:00:00');
        }
        if ($data['end_date'] && trim($data['end_date']) != '') {
            $params['where'][] = 'create_date <= ' . $dao->db->quote(trim($data['end_date']) . ' 23:59:59');
        }
        return $dao->getTotalMoney($params);

    }

    private function _getCategory()
    {
        $dao = new CategoryDao();
        $params = array(
            'no_quote' => true,
            'select' => 'id, title',
            'where' => array(
                'extension = \'com_customer\' ',
                'published = 1'
            ),
            'order' => 'lft  ASC'
        );
        return $dao->getList($params);
    }

    private function prepareCustomerData($data)
    {
        $cat = $this->_getCategory();
        $list = array();
        foreach ($data as $item) {
            $key = $item['category_id'];
            if (!isset($list[$key])) {
                $list[$key] = array();
            }

            $sub_key = $item['status_id'];
            $list[$key][$sub_key] = $item;
        }
        $status = $this->getListStatus();
        $last = array(
            'title' => 'Tổng cộng',
            'num' => 0,
            'total_revenue' => 0
        );
        foreach ($status as $key => $val) {
            $k = $val['id'];
            $status[$key]['categories'] = $cat;
            foreach ($status[$key]['categories'] as &$cat_item) {
                if (isset($list[$cat_item['id']][$k])) {
                    $this->_updateData($cat_item, $list[$cat_item['id']][$k]);
                } else {
                    $tmp = array(
                        'num' => 0,
                        'total_revenue' => 0,
                        'project_id' => 0,
                        'status_id' => $k,
                        'category_id' => $cat_item['id'],

                    );
                    $this->_updateData($cat_item, $tmp);
                }
                $last['num'] += $cat_item['num'];
                $last['total_revenue'] += $cat_item['total_revenue'];
                $cat_item['total_revenue'] = number_format($cat_item['total_revenue'], 0, ",", ".") . ' '.BIZ_XU;

            }
            $last['total_revenue'] = number_format($last['total_revenue'], 0, ",", ".") . ' '.BIZ_XU;
            //$status[$key]['categories'][] = $last;
        }
        return $status;
    }

    private function prepareCategoryData($data)
    {
        $cat = $this->_getCategory();
        $list = array();
        foreach ($data as $item) {
            $key = $item['category_id'];
            if (!isset($list[$key])) {
                $list[$key] = array();
            }

            $sub_key = $item['status_id'];
            $list[$key][$sub_key] = $item;
        }
        $status = $this->getListReportProjectStatus();
        $last = array(
            'name' => 'Tổng cộng',
            'num' => 0,
            'total_revenue' => 0
        );
        foreach ($cat as &$val) {
            $val['status'] = $status;
            foreach ($val['status'] as &$status_item) {
                $k = $val['id'];
                if (isset($list[$val['id']][$status_item['id']])) {
                    $this->_updateData($status_item, $list[$val['id']][$status_item['id']]);

                } else {
                    $tmp = array(
                        'num' => 0,
                        'total_revenue' => 0,
                        'project_id' => 0,
                        'status_id' => $status_item['id'],
                        'category_id' => $k,

                    );
                    $this->_updateData($status_item, $tmp);
                }
                $last['num'] += $status_item['num'];
                $last['total_revenue'] += @$status_item['total_revenue'];
                $status_item['total_revenue'] = number_format($status_item['total_revenue'], 0, ",", ".") . ' '.BIZ_XU;

            }
            $last['total_revenue'] = number_format($last['total_revenue'], 0, ",", "."). ' '.BIZ_XU;
            $val['status'][] = $last;
        }
        return $cat;
    }

    private function _updateData(&$destination, $source)
    {
        foreach ($source as $k => $value) {
            $destination[$k] = $value;
        }
    }

    private function getListStatus()
    {
        return array(
            array(
                'id' => 1,
                'name' => 'Khách hàng đang chờ'
            ),
            array(
                'id' => 2,
                'name' => 'Khách hàng lưỡng lự'
            ),
            array(
                'id' => 3,
                'name' => 'Khách hàng quan tâm'
            )
            // , array(
            //     'id' => 4,
            //     'name' => 'Khách hàng rất quan tâm'
            // )
            , array(
                'id' => 7,
                'name' => 'Khách hàng hoàn tất'
            ), array(
                'id' => 6,
                'name' => 'Khách hàng trả lại'
            )

        );
    }

    private function getListReportProjectStatus()
    {
        return array(
            array(
                'id' => 2,
                'name' => 'Lưỡng lự'
            ),
            array(
                'id' => 3,
                'name' => 'Quan tâm'
            ), array(
                'id' => 4,
                'name' => 'Rất quan tâm'
            ), array(
                'id' => 7,
                'name' => 'Hoàn thành'
            ), array(
                'id' => 6,
                'name' => 'Trả lại'
            ), array(
                'id' => 8,
                'name' => 'Hủy'
            )

        );
    }


}
