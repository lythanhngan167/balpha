<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/27/2019
 * Time: 9:51 AM
 */


use api\model\dao\CustomerDao;


defined('_JEXEC') or die('Restricted access');

class UsersApiResourceCustomers extends ApiResource
{
    static public function routes()
    {
        $routes[] = 'customers/';

        return $routes;
    }

    public function delete()
    {
        $this->plugin->setResponse('in delete');
    }


    /**
     * @OA\Post(
     *     path="/api/users/customers",
     *     tags={"Customers"},
     *     summary="Get customers list",
     *     description="Get customers list",
     *     operationId="post",
     *     security = { { "bearerAuth": {} } },
     *     @OA\RequestBody(
     *         required=true,
     *         description="Get project list",
     *         @OA\JsonContent(ref="#/components/schemas/CustomerQueryForm"),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(ref="#/components/schemas/CustomerQueryForm"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/CustomerBiz")
     *         ),
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
        $params = array();
        $params['offset'] = isset($data['offset']) ? (int)$data['offset'] : 0;
        $params['limit'] = isset($data['limit']) ? (int)$data['limit'] : 20;
        if ($data['id']) {
            $params['where'][] = 'o.id = ' . (int)$data['id'];
        }
        if ($data['status_id']) {
            switch ($data['status_id']){
                case 50:
                    $params['where'][] = 'o.status_id IN (2, 3, 4)';
                    break;
                case 99:
                    $params['where'][] = 'o.trash_approve <> 1';
                default:
                    $params['where'][] = 'o.status_id = ' . (int)$data['status_id'];
            }
        }
        if (@$data['q']) {
            $q = $dao->db->quote('%'.trim($data['q']).'%');
            $params['where'][] = "(o.`name` LIKE {$q} OR o.phone LIKE {$q} OR o.email LIKE {$q} OR o.place LIKE {$q})";
        }

        if (@$data['searchKey']) {
            $searchKey = $dao->db->quote('%'.trim($data['searchKey']).'%');
            $params['where'][] = "(o.`name` LIKE {$searchKey} OR o.phone LIKE {$searchKey} OR o.email LIKE {$searchKey})";
        }



        $params['where'][] = 'o.sale_id = '.$user->id;

        $params['where'][] = "RIGHT(o.phone, 2) <> 'xy'";

        $result = $dao->getCustomers($params);
        $this->plugin->setResponse($result);
    }



}
