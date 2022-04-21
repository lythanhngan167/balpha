<?php
use api\model\dao\SocialDao;

defined('_JEXEC') or die('Restricted access');
jimport('joomla.user.user');

class UsersApiResourceConfirmRegister extends ApiResource{
    public function post()
    {
        $data = $this->getRequestData();
        $dao = new SocialDao();
        $config = new JConfig();
        if($config->enable_agency_signup == "0" && $data['group'] == "3"){
            ApiError::raiseError('100', 'Chức năng đăng ký Tư vấn viên hiện chưa hỗ trợ.');
            return false;
        }
        $data['socialType'] = 'phone';
        $data['fieldValue'] = $data['username'];
        $token = $dao->createVerifyCode($data);
        $message = 'Chúng tôi đã gửi mã xác nhận qua số điện thoại <span>' . $data['username'] . '</span>. Vui lòng kiểm tra và nhập vào ô bên dưới.';
        $this->plugin->setResponse(array('code_token' => $token, 'message' => $message));
        return true;
    }
}
?>
