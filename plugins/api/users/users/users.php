<?php
/**
 * @package Com_api
 * @copyright Copyright (C) 2009 2014 Techjoomla, Tekdi Technologies Pvt. Ltd. All rights reserved.
 * @license GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     http://www.techjoomla.com
 */

use api\model\dao\UserDao;

defined('_JEXEC') or die('Restricted access');

class UsersApiResourceUsers extends ApiResource
{

    static public function routes()
    {
        $routes[] = 'users/';

        return $routes;
    }

    public function delete()
    {
        $this->plugin->setResponse('in delete');
    }

    public function post()
    {
        $this->plugin->setResponse('in post');
    }


    /**
     * @OA\Get(
     *     path="/api/users/users/{id}",
     *     tags={"User"},
     *     summary="Get user by id",
     *     description="Get user by id",
     *     operationId="get",
     *     security = { { "bearerAuth": {} } },
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="User id to filter by",
     *         required=false,
     *         @OA\Schema(
     *           type="int",
     *           default="0"
     *         ),
     *         style="form"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/UserBiz")
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
        $params = array();
        $input = JFactory::getApplication()->input;
        $id = $input->get('id', 0);
        if ($id) {
            $params['where'][] = 'id = ' . (int)$id;
        }
        $dao = new UserDao();
        $result = $dao->getUsers($params);
        $this->plugin->setResponse($result);
        return true;
    }


}
