<?php

/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/27/2019
 * Time: 9:51 AM
 */

use api\model\dao\UserDao;
use api\model\dao\UserActivationDao;
use api\model\form\ForgotForm;
use api\model\Sconfig;
use api\model\SUtil;

defined('_JEXEC') or die('Restricted access');
jimport('joomla.user.user');

class UsersApiResourceForgotPassword extends ApiResource
{
    /**
     * @OA\Post(
     *     path="/api/users/forgotpassword",
     *     tags={"User"},
     *     summary="Forgot password user",
     *     description="Forgot password user",
     *     operationId="post",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Forgot password",
     *         @OA\JsonContent(ref="#/components/schemas/ForgotForm"),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(ref="#/components/schemas/ForgotForm"),
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
        $form = new ForgotForm();
        $form->setAttributes($data);
        if ($form->validate()) {
            if ($form->typeConfirm == "email") {
                $data = $form->toArray();
                $dao = new UserDao();
                $user = $dao->get(array(
                    'select' => array(
                        'id',
                        'name',
                        'email'
                    ),
                    'where' => array(
                        'email= ' . $dao->db->quote($data['email'])
                    )
                ));
                if ($user) {
                    $row = new stdClass();
                    $row->user_id = $user['id'];
                    $row->token = $this->_getToken(25);
                    $row->field_value = $user['email'];
                    $codeDao = new UserActivationDao();

                    if ($codeDao->insert($row)) {
                        $params = array(
                            'subject' => 'Thiết lập lại mật khẩu',
                            'name' => $user['name'],
                            'code' => $row->code
                        );
                        $user['mail'] = $user['email'];
                        $this->_sendMail('forgot_password', $user['mail'], $params);
                        $this->plugin->setResponse($row->token);
                        return true;
                    }
                }
            } else if ($form->typeConfirm == "phone") {
                $dao = new UserDao();
                $user = $dao->get(array(
                    'select' => array(
                        'id',
                        'name',
                        'username'
                    ),
                    'where' => array(
                        'username= ' . $dao->db->quote($data['phone'])
                    )
                ));
                if ($user) {
                    $sql = 'SELECT COUNT(*)
                            FROM #__user_activation
                            WHERE field_value = ' . $data['phone'] .
                        ' AND created_date >= DATE_SUB(now(), INTERVAL 24 HOUR) ';
                    $db = JFactory::getDbo();
                    $count = $db->setQuery($sql)->loadResult();

                    if ($count >= 2) {
                        $message = 'Số điện thoại này đã được gửi mã xác nhận 2 lần trong hôm nay. Vui lòng thử lại sau 24 giờ hoặc vui lòng xác nhận qua email.';
                        $this->plugin->setResponse(array('message' => $message));
                        return false;
                    }

                    $row = new stdClass();
                    $row->user_id = $user['id'];
                    $row->token = $this->_getToken(25);
                    $row->field_value = $user['username'];
                    $codeDao = new UserActivationDao();

                    if ($codeDao->insert($row)) {
                        $phone = $user['username'];
                        $content = 'BIZNET gui ban ma xac nhan: ' . $row->code;
                        SUtil::sendSMS($phone, $content);
                        $this->plugin->setResponse($row->token);
                        return true;
                    }
                }
            }
            ApiError::raiseError('301', 'Yêu cầu không hợp lệ.');
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

    private function _generateCode()
    {
        $config = new Sconfig();
        if ($config->evn == 'develop') {
            return '999999';
        } else {
            return rand(100000, 999999);
        }
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
        try {
            $mailer->Send();
            return true;
        } catch (Exception $e) {
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
