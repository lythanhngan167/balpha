<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/27/2019
 * Time: 9:51 AM
 */

use api\model\dao\UserDao;
use api\model\dao\UserActivationDao;
use api\model\form\ChangePasswordForm;

defined('_JEXEC') or die('Restricted access');
jimport('joomla.user.user');

class UsersApiResourceChangePassword extends ApiResource
{
    /**
     * @OA\Post(
     *     path="/api/users/changepassword",
     *     tags={"User"},
     *     summary="Change password user",
     *     description="Change password user",
     *     operationId="post",
     *     security = { { "bearerAuth": {} } },
     *     @OA\RequestBody(
     *         required=true,
     *         description="Change password",
     *         @OA\JsonContent(ref="#/components/schemas/ChangePasswordForm"),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(ref="#/components/schemas/ChangePasswordForm"),
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
        $form = new ChangePasswordForm();
        $form->setAttributes($data);
        if ($form->validate()) {
            $data = $form->toArray();
            $user = JFactory::getUser();
            $authenticate = JAuthentication::getInstance();
            $credentials = array(
                'username' => $user->username,
                'password' => $data['old_password'],
            );
            $response = $authenticate->authenticate($credentials, array('silent' => true));
            if ($response->status === JAuthentication::STATUS_SUCCESS) {
                // Update the user object.
                $user->password = JUserHelper::hashPassword($data['new_password']);
                $user->activation = '';
                // Save the user to the database.
                if (!$user->save(true)) {
                    ApiError::raiseError('301', JText::sprintf('COM_USERS_USER_SAVE_FAILED', $user->getError()));
                    return false;
                }
                $this->plugin->setResponse('');
                return true;
            }            
            ApiError::raiseError('301', 'Mật khẩu cũ không chính xác. Vui lòng thử lại.');
            return false;
        } else {
            ApiError::raiseError('101', $form->getFirstError());
            return false;
        }

    }

    private function _getToken($length)
    {
        $token = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet .= "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet .= "0123456789";
        $max = strlen($codeAlphabet); // edited

        for ($i = 0; $i < $length; $i++) {
            $token .= $codeAlphabet[random_int(0, $max - 1)];
        }

        return $token;
    }

    private function _sendMail($type, $recipient, $params)
    {
        $mailer = JFactory::getMailer();
        $config = JFactory::getConfig();
        $sender = array(
            $config->get('mailfrom'),
            $config->get('fromname')
        );
        $mailer->setSender($sender);
        $mailer->addRecipient($recipient);
        $mailer->isHtml(true);

        $body = $this->_getTemplate($type, $params);
        $mailer->setSubject($params['subject']);
        $mailer->setBody($body);
        try{
            $mailer->Send();
            return true;
        } catch (Exception $e){
            return false;
        }

    }

    private function _getTemplate($type, $params)
    {
        $message = '';
        switch ($type) {
            case 'forgot_password':
                $message = "<p>Chào " . $params['name'] . ",</p>";
                $message .= "<p>Bạn vừa yêu cầu thiết lập lại mật khẩu.</p>";
                $message .= "<p>Nhập Mã xác nhận để có thể đổi lại mật khẩu của bạn: <b>{$params['code']}</b></p>";
                $message .= "<p>Cảm ơn!</p>";
                break;
        }
        return $message;
    }

}