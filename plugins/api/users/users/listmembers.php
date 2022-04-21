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

class UsersApiResourceListmembers extends ApiResource
{

    static public function routes()
    {
        $routes[] = 'listmembers/';

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
    public function get()
    {
        $data = $this->getRequestData();
        $user = JFactory::getUser();
        $childs = SUtil::getChildList((int)$user->id);
        $params = array();
        $params['offset'] = 0;
        $params['limit'] = 500;
        $params['where'][] = 'id IN ('.implode(',', $childs).')';
        $params['order'] = '`level_tree` ASC';


        $dao = new UserDao();
        $result = $dao->getListMembers($params);
        $this->plugin->setResponse($result);
    }


}
