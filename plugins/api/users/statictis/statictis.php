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

class UsersApiResourceStatictis extends ApiResource
{
    static public function routes()
    {
        $routes[] = 'statictis/';

        return $routes;
    }

    public function delete()
    {
        $this->plugin->setResponse('in delete');
    }


    /**
     * @OA\Get(
     *     path="/api/users/statictis",
     *     tags={"User"},
     *     summary="statictis for user",
     *     description="statictis for user",
     *     operationId="get",
     *     security = { { "bearerAuth": {} } },
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
    public function get()
    {
        $user = JFactory::getUser();
        $dao = new CustomerDao();
        $params = array(
            'no_quote' => true,
            'select' => 'COUNT(*) AS num, status_id'
        );
        $params['where'][] = 'sale_id  = ' . (int)$user->id;
        $params['where'][] = 'state = 1';
        $params['where'][] = "RIGHT(phone, 2) <> 'xy'";
        $params['where'][] = '((status_id <> "99") OR (status_id = "99" AND trash_approve <> "1"))';
        $params['group'] = 'status_id';
        // $params['union'] = 'select COUNT(*) AS num, 50
        // from wmspj_customers
        // where state = "1"
        // and sale_id = '.(int)$user->id.'
        // and status_id in (2,3,4)
        // group by status_id';
        $result = $dao->report($params);
        $list = new \stdClass();
        if($result){
            foreach ($result as $item) {
                $list->{$item['status_id']} = $item['num'];
            }
        }

        $params = array(
            'no_quote' => true,
            'select' => 'COUNT(*) AS num'
        );
        $params['where'][] = 'sale_id  = ' . (int)$user->id;
        $params['where'][] = 'status_id  IN ( 2, 3, 4)'; // dang cham soc
        $params['where'][] = "RIGHT(phone, 2) <> 'xy'";
        $result = $dao->get($params);
        if($result['num'] > 0){
            $id = 50;
            $list->{$id} = $result['num'];
        }

        $this->plugin->setResponse($list);
    }



}
