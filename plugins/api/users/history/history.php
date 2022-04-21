<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/27/2019
 * Time: 9:51 AM
 */


use api\model\form\HistoryForm;
use api\model\dao\OrdersDao;
use api\model\dao\CustomerDao;

defined('_JEXEC') or die('Restricted access');

class UsersApiResourceHistory extends ApiResource
{
    static public function routes()
    {
        $routes[] = 'history/';

        return $routes;
    }

    public function delete()
    {
        $this->plugin->setResponse('in delete');
    }


    /**
     * @OA\Post(
     *     path="/api/users/history",
     *     tags={"User"},
     *     summary="Get buy history",
     *     description="Get buy history",
     *     operationId="post",
     *     security = { { "bearerAuth": {} } },
     *     @OA\RequestBody(
     *         required=true,
     *         description="Form data",
     *         @OA\JsonContent(ref="#/components/schemas/HistoryForm"),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(ref="#/components/schemas/HistoryForm"),
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
        $form = new HistoryForm();
        $form->setAttributes($data);
        if ($form->validate()) {
            $data = $form->toArray();
            $user = JFactory::getUser();
            $data['sale_id'] = (int)$user->id;
            $dao = new OrdersDao();
            $params = array();

            $params['offset'] = isset($data['offset']) ? (int)$data['offset'] : 0;
            $params['limit'] = isset($data['limit']) ? (int)$data['limit'] : 20;
            if ($data['project_id'] && (int)$data['project_id'] > 0) {
                $params['where'][] = 'o.project_id = ' . (int)$data['project_id'];
            }
            if ($data['month'] && $data['month'] != '') {
                $params['where'][] = 'DATE_FORMAT(o.create_date, "%Y-%m")= ' . $dao->db->quote(trim($data['month']));
            }
            $params['where'][] = 'o.created_by  = ' . (int)$user->id;
            $params['where'][] = 'o.category_id  > 0 ';
            $result = $dao->getHistory($params);

            $totalMoney = $this->_getTotalMoney($data);
            $totalMoney = number_format($totalMoney, 0, ",", ".") . ' '.BIZ_XU;
            $returnData = array('list' => $result, 'total_money' => $totalMoney);
            $this->plugin->setResponse($returnData);
        } else {
            ApiError::raiseError('101', $form->getFirstError());
            return false;
        }
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
        if ($data['month'] && $data['month'] != '') {
            $params['where'][] = 'DATE_FORMAT(create_date, "%Y-%m")= ' . $dao->db->quote(trim($data['month']));
        }
        return $dao->getTotalMoney($params);

    }


}
