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


class UsersApiResourceApprovedpayment extends ApiResource
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
        $data = $this->getRequestData();
        $dao = new UserDao();
        $user = JFactory::getUser();
        $sql = 'SELECT customer_id FROM #__eshop_orders WHERE id = ' . (int)$data['id'];
        $customer_id = $dao->db->setQuery($sql)->loadResult();
        if (!$customer_id) {
            ApiError::raiseError('101', 'Yêu cầu không hợp lệ.');
            return false;
        }
        if ($customer_id) {
            $childs = SUtil::getChildList($user->id);
            if (!in_array($customer_id, $childs)) {
                ApiError::raiseError('101', 'Yêu cầu không hợp lệ.');
                return false;
            }

        }
        $sql = 'UPDATE #__eshop_orders SET 	order_status_id = 10 WHERE id = ' . (int)$data['id'];
        $result = $dao->db->setQuery($sql)->execute();
        $this->plugin->setResponse($result);
        return true;
    }


}
