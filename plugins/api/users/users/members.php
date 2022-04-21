<?php

/**
 * @package Com_api
 * @copyright Copyright (C) 2009 2014 Techjoomla, Tekdi Technologies Pvt. Ltd. All rights reserved.
 * @license GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     http://www.techjoomla.com
 */

defined('_JEXEC') or die('Restricted access');

use api\model\dao\UserDao;
use api\model\dao\ProjectDao;
use api\model\SUtil;

class UsersApiResourceMembers extends ApiResource
{

    static public function routes()
    {
        $routes[] = 'members/';

        return $routes;
    }

    public function delete()
    {
        $this->plugin->setResponse('in delete');
    }


    /**
     * @OA\Post(
     *     path="/api/users/contents",
     *     tags={"Content"},
     *     summary="Get content list",
     *     description="Get content list",
     *     operationId="post",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Register user to system",
     *         @OA\JsonContent(ref="#/components/schemas/ContentQueryForm"),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(ref="#/components/schemas/ContentQueryForm"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/ContentBiz")
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
        $dao = new UserDao();
        $db = JFactory::getDbo();
        $data = $this->getRequestData();
        $user_id = null;
        if ($data['user_id']) {
            $user = $dao->getUsers(array(
                'where' => array(
                    'id = ' . $db->quote($data['user_id']),
                    'block = 0'
                )
            ));
            $user_id = $data['user_id'];
        } else {
            $user = JFactory::getUser();
            $user_id = $user->id;
        }
        // $childs = SUtil::getChildList($user_id);
        $params = array();
        $params['offset'] = isset($data['offset']) ? (int)$data['offset'] : 0;
        $params['limit'] = isset($data['limit']) ? (int)$data['limit'] : 20;
        if ($data['level'] && (int)$data['level'] !== 0) {
            $params['where'][] = 'level_tree = ' . (int)$data['level'];
        } 
        $params['where'][] = 'id <> ' . (int)$user_id;
        // $params['where'][] = 'id IN (' . implode(',', $childs) . ')';
        $params['where'][] = 'invited_id = ' . $db->quote($user_id);
        $params['order'] = 'id DESC';
        $result = $dao->getMembers($params);
        $this->plugin->setResponse($result);
    }
}
