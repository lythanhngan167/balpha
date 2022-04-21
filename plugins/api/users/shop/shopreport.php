<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/27/2019
 * Time: 9:51 AM
 */


use api\model\dao\shop\ShopReportDao;
use api\model\dao\shop\ShopOrderStatusDao;


defined('_JEXEC') or die('Restricted access');
jimport('joomla.user.user');

class UsersApiResourceShopreport extends ApiResource
{
    static public function routes()
    {
        $routes[] = 'shopreport/';

        return $routes;
    }

    /**
     * @OA\Post(
     *     path="/api/users/shopreport",
     *     tags={"User"},
     *     summary="Change password user",
     *     description="Change password user",
     *     operationId="post",
     *     security = { { "bearerAuth": {} } },
     *     @OA\RequestBody(
     *         required=true,
     *         description="Change password",
     *         @OA\JsonContent(ref="#/components/schemas/OrderForm"),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(ref="#/components/schemas/OrderForm"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful login",
     *         @OA\Schema(ref="#/components/schemas/ErrorModel"),
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Invalid request",
     *     )
     * )
     */

    public function get()
    {
        $input = JFactory::getApplication()->input;
        $reportBy = $input->get('viewtype', 'week');
        $list = array();
        $stock_id = $input->get('stock_id', 0);
        $date = $input->get('date', date('Y-m-d'));
        if ($stock_id > 0) {
            $params = array();
            $params['stock_id'] = $stock_id;
            $params['date'] = $date;
            switch ($reportBy) {
                case 'date':
                    $list = $this->reportByDate($params);
                    break;
                case 'week':
                    $list = $this->reportByWeek($params);
                    break;

                case 'month':
                    $list = $this->reportByMonth($params);
                    break;
            }


            $this->plugin->setResponse($list);
            return true;
        }

        ApiError::raiseError('101', 'Invalid request');
        return false;

    }

    private function reportByDate($params = array())
    {
        $dao = new ShopReportDao();
        $statusDao = new ShopOrderStatusDao();
        $status = $statusDao->getOrderStatus();
        $result = $dao->reportByDate($params);
        $list = array();

        foreach ($status as $item) {
            $list[] = array(
                'id' => $item->id,
                'num' => isset($result[$item->id]) ? $result[$item->id] : 0
            );
        }
        return $list;
    }

    private function reportByWeek($params = array())
    {
        $dao = new ShopReportDao();
        $date = isset($params['date']) ? $params['date'] : date('Y-m-d');
        $days = $this->getWeekDay($date);
        $start_date = reset($days)['date'];
        $end_date = end($days)['date'];
        $params['where'] = array(
            'stock_id ='.(int)$params['stock_id'], 
            'modified_date >= \'' . $start_date . '\'',
            'modified_date <= \'' . $end_date . '\''
        );
        $result = $dao->reportByWeek($params);
        if ($result) {
            foreach ($days as &$item) {
                $item['num'] = 0;
                if (isset($result[$item['date']])) {
                    $item['num'] = $result[$item['date']];
                }
            }
        }
        return $days;
    }

    private function reportByMonth($params = array())
    {
        $dao = new ShopReportDao();
        $months = $this->getMonths();
        $year = isset($params['year']) ? $params['year'] : date('Y');
        $params['where'] = array(
            'DATE_FORMAT(o.created_date, \'%Y\') = ' . $year
        );
        $result = $dao->reportByMonth($params);
        if ($result) {
            foreach ($months as &$item) {
                $item['num'] = 0;
                if (isset($result[$item['date']])) {
                    $item['num'] = $result[$item['date']];
                }
            }
        }
        return $months;
    }

    private function getWeekDay($date)
    {
        $days = array();
        $ts = strtotime($date);
        // find the year (ISO-8601 year number) and the current week
        $year = date('o', $ts);
        $week = date('W', $ts);
        // print week for the current date
        for ($i = 1; $i <= 7; $i++) {
            // timestamp from ISO week date format
            $ts = strtotime($year . 'W' . $week . $i);
            $days[] = array(
                'formated' => date("m/d/Y", $ts),
                'date' => date("Y-m-d", $ts)
            );
        }
        return $days;
    }

    private function getMonths()
    {
        $year = 2019;
        $months = array();

        for ($i = 1; $i <= 9; $i++) {
            // timestamp from ISO week date format
            $months[] = array(
                'formated' => "0{$i}/{$year}",
                'date' => "{$year}-0{$i}",
            );
        }
        for ($i = 10; $i <= 12; $i++) {
            // timestamp from ISO week date format
            $months[] = array(
                'formated' => "{$i}/{$year}",
                'date' => "{$year}-{$i}",
            );
        }
        return $months;
    }
}
