<?php
use api\model\dao\SocialDao;

defined('_JEXEC') or die('Restricted access');
jimport('joomla.user.user');

class UsersApiResourceConfirmPhoneUser extends ApiResource{
    
    public function post()
    {
        $data = $this->getRequestData();
        $dao = new SocialDao();
        $data['socialType'] = 'phone';
        $data['fieldValue'] = $data['phone'];
        $db = JFactory::getDbo();
		$sql = 'SELECT COUNT(*)
				FROM #__social
                WHERE field_value = ' . $data['fieldValue'] . 
                ' AND created_date >= DATE_SUB(now(), INTERVAL 24 HOUR) ';
		$count = $db->setQuery($sql)->loadResult();
        
        if($count >= 2){
            $message = 'Số điện thoại này đã được gửi mã xác nhận 2 lần trong hôm nay. Vui lòng thử lại sau 24 giờ.';
            $this->plugin->setResponse(array('message' => $message));
        }else{
            $token = $dao->createVerifyCode($data);
            $message = 'Chúng tôi đã gửi mã xác nhận qua số điện thoại <span>' . $data['fieldValue'] . '</span>. Vui lòng kiểm tra và nhập vào ô bên dưới.';
            $this->plugin->setResponse(array('code_token' => $token, 'message' => $message));
        }
        return true;
    }
}
