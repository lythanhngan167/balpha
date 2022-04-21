<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/27/2019
 * Time: 9:51 AM
 */


use api\model\dao\ProjectDao;


defined('_JEXEC') or die('Restricted access');

class UsersApiResourceOrders extends ApiResource
{
    static public function routes()
    {
        $routes[] = 'orders/';

        return $routes;
    }

    public function delete()
    {
        $this->plugin->setResponse('in delete');
    }

    private function _getDefaultMaxPick()
    {
        $app = JFactory::getApplication();
        $cparams = $app->getParams('com_maxpick_level');
        return $cparams->get('maxpickdefault');
    }


    /**
     * @OA\Post(
     *     path="/api/users/projects",
     *     tags={"Projects"},
     *     summary="Get project list",
     *     description="Get project list",
     *     operationId="post",
     *     security = { { "bearerAuth": {} } },
     *     @OA\RequestBody(
     *         required=true,
     *         description="Get project list",
     *         @OA\JsonContent(ref="#/components/schemas/ProjectQueryForm"),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(ref="#/components/schemas/ProjectQueryForm"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/ProjectBiz")
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

        $params = array();
        $params['offset'] = isset($data['offset']) ? (int)$data['offset'] : 0;
        $params['limit'] = isset($data['limit']) ? (int)$data['limit'] : 20;
        if ($data['id']) {
            $params['where'][] = 'id = ' . (int)$data['id'];
        }
        if(@$data['include_project']){
            $params['include_project'] = $data['include_project'];
            $params['level_id'] = $this->_getLevelId();
            $params['max_pick'] = $this->_getDefaultMaxPick();
        }
        $dao = new ProjectDao();
        $result = $dao->getProject($params);
        $this->plugin->setResponse($result);
    }

    /**
     * @OA\Get(
     *     path="/api/users/projects/{id}",
     *     tags={"Projects"},
     *     summary="Get Project information",
     *     description="Get Project information",
     *     operationId="post",
     *     security = { { "bearerAuth": {} } },
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Project id",
     *         required=false,
     *         @OA\Schema(
     *           type="int",
     *           default="null"
     *         ),
     *         style="form"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/CategoryBiz")
     *         ),
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
        $id = $input->get('id', 0);
        $params = array();
        $params['id'] = (int)$id;
        $params['level_id'] = $this->_getLevelId();
        $params['max_pick'] = $this->_getDefaultMaxPick();

        $dao = new ProjectDao();
        $result = $dao->getProjectInfo($params);
        $this->plugin->setResponse($result);
    }

    private function _getLevelId()
    {
        $user = JFactory::getUser();
        $level = (int)$user->get('level_tree');
        switch ($level) {
            case 1:
                $id = 167;
                break;
            case 2:
                $id = 168;
                break;
            case 3:
                $id = 169;
                break;
            case 4:
                $id = 170;
                break;
            case 5:
                $id = 171;
                break;
            default:
                $id = 0;
        }
        return $id;
    }

}
