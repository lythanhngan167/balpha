<?php
/**
 * @package API plugins
 * @copyright Copyright (C) 2009 2014 Techjoomla, Tekdi Technologies Pvt. Ltd. All rights reserved.
 * @license GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link http://www.techjoomla.com
 */

use api\model\dao\UserDao;
use api\model\SUtil;


defined('_JEXEC') or die('Restricted access');


class UsersApiResourceApproved extends ApiResource
{

    /**
     * @OA\Post(
     *     path="/api/users/login",
     *     tags={"User"},
     *     summary="Login user to system by username and password",
     *     description="Login user to system by username and password",
     *     operationId="post",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Login to system",
     *         @OA\JsonContent(ref="#/components/schemas/LoginForm"),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(ref="#/components/schemas/LoginForm"),
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
    public function post()
    {
        $user = JFactory::getUser();
        $data = $this->getRequestData();
        $uid = (int)$data['id'];
        if ($uid) {
            $childs = SUtil::getChildList($user->id);
            if (!in_array($uid, $childs)) {
                ApiError::raiseError('101', 'Yêu cầu không hợp lệ.');
                return false;
            }

            $set = array();
            $set[] = 'approved = 9';
            $set[] = 'approved_by = ' . (int)$user->id;
            if (@$data['level']) {
                $set[] = ' `level_tree` = ' . (int)$data['level'];
            }
            $sql = 'UPDATE #__users SET ' . implode(', ', $set) . ' WHERE id = ' . (int)$data['id'] . ' AND invited_id = ' . (int)$user->id;
            $dao = new UserDao();
            $result = $dao->db->setQuery($sql)->execute();
        }
        $this->plugin->setResponse($result);
    }


}
