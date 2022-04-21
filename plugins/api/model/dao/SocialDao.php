<?php

/**
 * Created by PhpStorm.
 * User: lvson
 * Date: 4/26/2019
 * Time: 10:53 AM
 */

namespace api\model\dao;

use api\model\Sconfig;
use api\model\SUtil;
use api\model\AbtractDao;

class SocialDao extends AbtractDao
{

    public function getTable()
    {
        return '#__social';
    }

    public function createVerifyCode($params = array())
    {
        $token = $this->_getToken(64);
        $verifyCode = $this->_getVerifyCode();
        $obj = new \stdClass();
        $obj->token = $token;
        $user = \JFactory::getUser();
        $timezone = $user->getTimezone();
        $obj->created_date = \JFactory::getDate()->setTimezone($timezone)->toSql(true);
        $obj->verify_code = $verifyCode;
        $obj->social_type = $params['socialType'];
        $obj->field_value = $params['fieldValue'];
        $obj->id = $params['id'];
        if ($this->db->insertObject($this->getTable(), $obj)) {
            if ($params['socialType'] == 'id') {
                $obj->field_value = $params['username'];
            }
            $this->sendVerifyCode($obj->social_type, $obj->verify_code, $obj->field_value);
            return $token;
        }
        return null;
    }

    public function sendVerifyCode($socialType, $verifyCode, $value)
    {
        $config = new Sconfig();
        switch ($socialType) {
            // case 'email':
            //     $to = $value;
            //     $subject = 'Ma xac thuc tai Sieu Thi Minh Cau cua ban: ' . $verifyCode;
            //     $template = 'verifycode';
            //     $params = array(
            //         'code' => $verifyCode,
            //         'content' => 'Ma xac thuc tai Sieu Thi Minh Cau cua ban: ' . $verifyCode
            //     );
            //     SUtil::sendMail($to, $subject, $template, $params);
            //     break;
            case 'phone':
                $phone = $value;
                $content = 'BIZNET gui ban ma xac nhan: ' . $verifyCode;
                return SUtil::sendSMS($phone, $content);
                break;

            case 'id':
            case 'forgot_password':
                $phone = $value;
                $content = 'BIZNET gui ban ma xac nhan: ' . $verifyCode;
                return SUtil::sendSMS($phone, $content);
                break;
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

    private function _getVerifyCode()
    {
        $config = new Sconfig();
        if ($config->enviroment == 'dev') {
            return '9999';
        }
        return rand(1000, 9999);
    }

}
