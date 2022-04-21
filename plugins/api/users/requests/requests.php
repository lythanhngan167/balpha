<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/27/2019
 * Time: 9:51 AM
 */


use api\model\dao\RequestDao;
use api\model\form\RequestForm;


defined('_JEXEC') or die('Restricted access');

class UsersApiResourceRequests extends ApiResource
{
    static public function routes()
    {
        $routes[] = 'requests/';

        return $routes;
    }

    public function delete()
    {
        $this->plugin->setResponse('in delete');
    }


    /**
     * @OA\Post(
     *     path="/api/users/requests",
     *     tags={"Request"},
     *     summary="List request",
     *     description="List request",
     *     operationId="post",
     *     security = { { "bearerAuth": {} } },
     *     @OA\RequestBody(
     *         required=true,
     *         description="Form data",
     *         @OA\JsonContent(ref="#/components/schemas/RequestForm"),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(ref="#/components/schemas/RequestForm"),
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
        $params = array();
        $params['offset'] = isset($data['offset']) ? (int)$data['offset'] : 0;
        $params['limit'] = isset($data['limit']) ? (int)$data['limit'] : 20;
        $params['where'][] = 'request_id = ' . (int)$user->id;
        $dao = new RequestDao();
        if ($data['status_id']) {
            $params['where'][] = 'status_id = ' . $dao->db->quote($data['status_id']);
        }
        $result = $dao->getRequests($params);
        $this->plugin->setResponse($result);
    }



}
