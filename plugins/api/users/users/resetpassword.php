<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/27/2019
 * Time: 9:51 AM
 */

use api\model\dao\UserActivationDao;
use api\model\form\ResetForm;
use api\model\SUtil;

defined('_JEXEC') or die('Restricted access');

class UsersApiResourceResetPassword extends ApiResource
{
    /**
     * @OA\Post(
     *     path="/api/users/resetpassword",
     *     tags={"User"},
     *     summary="Reset password user",
     *     description="Reset password user",
     *     operationId="post",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Reset password",
     *         @OA\JsonContent(ref="#/components/schemas/ResetForm"),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(ref="#/components/schemas/ResetForm"),
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
        $form = new ResetForm();
        $form->setAttributes($data);
        if ($form->validate()) {
            $data = $form->toArray();
            $dao = new UserActivationDao();
            $userInfo = $dao->find($data);
            if ($userInfo) {
                // Get the user object.
                $user = JUser::getInstance($userInfo['user_id']);
                if ($user->block) {
                    ApiError::raiseError('301', 'Tài khoản tạm thời bị khoá. Vui lòng liên hệ Quản trị viên để được hỗ trợ.');
                    return false;
                }

                // Update the user object.
                $user->password = JUserHelper::hashPassword($data['password']);
                $user->activation = '';
                // Save the user to the database.
                if (!$user->save(true)) {
                    ApiError::raiseError('301', 'Lỗi hệ thống. Vui lòng thử lại sau.');
                    return false;
                }
                $dao->updateResetTime($userInfo['id']);
                $this->plugin->setResponse('');
                return true;
            }
            ApiError::raiseError('301', 'Yêu cầu không hợp lệ.');
            return false;
        } else {
            ApiError::raiseError('101', $form->getFirstError());
            return false;
        }

    }


}