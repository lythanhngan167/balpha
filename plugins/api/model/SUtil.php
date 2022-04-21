<?php

/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/28/2019
 * Time: 10:32 AM
 */

namespace api\model;

use \Firebase\JWT\JWT;
use api\model\SParams;
use api\model\dao\UserDao;
use api\model\Sconfig;

use SoapClient;


class SUtil
{
    public static function my_encrypt($data, $key)
    {
        // Remove the base64 encoding from our key
        $encryption_key = base64_decode($key);
        // Generate an initialization vector
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        // Encrypt the data using AES 256 encryption in CBC mode using our encryption key and initialization vector.
        $encrypted = openssl_encrypt($data, 'aes-256-cbc', $encryption_key, 0, $iv);
        // The $iv is just as important as the key for decrypting, so save it with our encrypted data using a unique separator (::)
        return base64_encode($encrypted . '::' . $iv);
    }

    public static function my_decrypt($data, $key)
    {
        // Remove the base64 encoding from our key
        $encryption_key = base64_decode($key);
        // To decrypt, split the encrypted data from our IV - our unique separator used was "::"
        list($encrypted_data, $iv) = explode('::', base64_decode($data), 2);
        return openssl_decrypt($encrypted_data, 'aes-256-cbc', $encryption_key, 0, $iv);
    }

    public static function getToken($args, $info)
    {
        $key = SParams::key();
        $data = serialize($args);
        $tokenContent = ['token' => SUtil::my_encrypt($data, $key), 'iat' => time() - date("Z"), 'exp' => time() + 7 * 24 * 3600];
        if ($info) {
            $tokenContent['info'] = $info;
        }
        return JWT::encode($tokenContent, $key, SParams::algorithm());
    }

    public static function decodeToken($token, $timeout = 60)
    {
        $key = SParams::key();
        JWT::$leeway = $timeout; // $leeway in seconds
        $algorithm = array();
        $algorithm[] = SParams::algorithm();
        $decoded = (array)JWT::decode($token, $key, $algorithm);
        if (isset($decoded['token'])) {
            $data = SUtil::my_decrypt($decoded['token'], $key);
            return (array)unserialize($data);
        }
        return null;
    }

    public static function generateUserToken($id)
    {
        $dao = new UserDao();
        $user = $dao->loadUser($id);
        $params = json_decode($user->params, true);
        $avatar = isset($params['avatar']) ? $params['avatar'] : '';
        $groups = \JAccess::getGroupsByUser($user->id, false);
        $is_partner = in_array(2, $groups) || in_array(3, $groups) || in_array(13, $groups);
        $clientToken = $user->id . '.' . time() . '.' . substr(md5(mt_rand()), 0, 7);
        $userInfo = array(
            'username' => $user->username,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'm_id' => $user->id_biznet,
            'level' => (int) $user->level,
            'level_tree' => $user->level_tree,
            'approved' => $user->approved,
            'token' => $clientToken,
            'group' => $groups[0],
            'is_logined' => true,
            'avatar' => $avatar,
            'id' => $user->id,
            'role' => $is_partner ? 'stock' : 'customer',
            'is_apple' => $user->is_apple == 1 ? true : false
        );
        $info = array(
            'id' => $user->id,
            'username' => $user->username,
            'token' => $clientToken,
        );
        $token = SUtil::getToken($info, $userInfo);
        return $token;
    }

    public static function encryptData($data)
    {
        $key = SParams::key();
        return SUtil::my_encrypt($data, $key);
    }

    public static function decryptData($token)
    {
        $key = SParams::key();
        $data = SUtil::my_decrypt($token, $key);
        return (array)unserialize($data);
    }

    public static function getUserToken($id)
    {
        $key = SParams::key();
        return SUtil::my_encrypt($id, $key);
    }

    public static function decodeUserToken($token)
    {
        $key = SParams::key();
        return SUtil::my_decrypt($token, $key);
    }

    public static function getPaymentError($responseCode, $template = ' - ([result])')
    {

        switch ($responseCode) {
            case "0":
                $result = "Giao dịch thành công";
                break;
            case "1":
                $result = "Ngân hàng từ chối giao dịch";
                break;
            case "3":
                $result = "Mã đơn vị không tồn tại";
                break;
            case "4":
                $result = "Không đúng access code";
                break;
            case "5":
                $result = "Số tiền không hợp lệ";
                break;
            case "6":
                $result = "Mã tiền tệ không tồn tại";
                break;
            case "7":
                $result = "Lỗi không xác định";
                break;
            case "8":
                $result = "Số thẻ không đúng";
                break;
            case "9":
                $result = "Tên chủ thẻ không đúng";
                break;
            case "10":
                $result = "Thẻ hết hạn/Thẻ bị khóa";
                break;
            case "11":
                $result = "Thẻ chưa đăng ký sử dụng dịch vụ";
                break;
            case "12":
                $result = "Ngày phát hành/Hết hạn không đúng";
                break;
            case "13":
                $result = "Vượt quá hạn mức thanh toán";
                break;
            case "21":
                $result = "Số tiền không đủ để thanh toán";
                break;
            case "99":
                $result = "Người sủ dụng hủy giao dịch";
                break;
            default:
                $result = "Giao dịch thất bại";
        }
        return str_replace('[result]', $result, $template);
    }

    public static function canCancelOrder($orderStatus, $paymentStatus)
    {

        $config = new Sconfig();

        return in_array($orderStatus, $config->canCancelStatus) && $paymentStatus == '0';
    }


    public static function getShipableArea($id)
    {
        $db = \JFactory::getDbo();
        $sql = 'SELECT category_id FROM #__eshop_productcategories WHERE product_id = ' . (int)$id;
        $cats = $db->setQuery($sql)->loadColumn();
        if ($cats) {
            $cats = static::getCategoryTree($cats);
        }
        return $cats;
    }

    public static function getCategoryTree($cats)
    {
        $cats = (array)$cats;
        if ($cats) {
            $childCat = $cats;
            $i = 0;
            $db = \JFactory::getDbo();
            do {
                $sql = 'SELECT category_parent_id FROM #__eshop_categories WHERE id IN (' . implode(',', $childCat) . ') AND category_parent_id > 0 ';
                $parents = $db->setQuery($sql)->loadColumn();
                if ($parents) {
                    $cats = array_merge($cats, $parents);
                }
                $childCat = $parents;
                $i++;
            } while (count($childCat) && $i < 5);
        }
        return $cats;
    }

    public static function getLonLatFromAddress($address)
    {
        $config = new Sconfig();
        $address = urlencode($address);
        $url = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . $address . '&key=' . $config->google_api_key;
        $json = @file_get_contents($url);
        $data = json_decode($json);
        if (!empty($data->error_message)) {
            return $data->error_message;
        }
        return array(
            'lat' => $data->results[0]->geometry->location->lat,
            'lng' => $data->results[0]->geometry->location->lng
        );
    }

    public static function getDistance($lat1, $lon1, $lat2, $lon2, $unit = 'K')
    {
        $lat1 = (float)$lat1;
        $lon1 = (float)$lon1;
        $lat2 = (float)$lat2;
        $lon2 = (float)$lon2;
        // Get latitude and longitude from the geodata
        if (($lat1 == $lat2) && ($lon1 == $lon2)) {
            return 0;
        } else {
            $theta = $lon1 - $lon2;
            $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $miles = $dist * 60 * 1.1515;
            $unit = strtoupper($unit);

            if ($unit == "K") {
                return ($miles * 1.609344);
            } else if ($unit == "N") {
                return ($miles * 0.8684);
            } else {
                return $miles;
            }
        }
    }

    public static function getFullAddress($id)
    {
        $db = \JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('a.*, z.zone_name, c.country_name')
            ->from('#__eshop_addresses AS a')
            ->leftJoin('#__eshop_zones AS z ON (a.zone_id = z.id)')
            ->leftJoin('#__eshop_countries AS c ON (a.country_id = c.id)')
            ->where('a.id = ' . (int)$id)
            ->where('a.address_1 != ""');
        $db->setQuery($query);
        $address = $db->loadObject();

        $addressText = array();

        if ($address->address_1) {
            $addressText[] = $address->address_1;
        }
        if ($address->city != '') {
            $addressText[] = $address->city;
        }
        if ($address->zone_name != '') {
            $addressText[] = $address->zone_name;
        }
        if ($address->country_id != '') {
            $addressText[] = $address->country_name;
        }
        return implode(', ', $addressText);
    }

    public static function checkLimitArea($product_id)
    {
        $config = new Sconfig();
        $db = \JFactory::getDbo();
        $sql = 'SELECT category_id FROM #__eshop_productcategories WHERE product_id = ' . (int)$product_id;
        $cats = $db->setQuery($sql)->loadColumn();
        $limitCat = $config->shippingLimitCategory;
        $result = array_intersect($cats, $limitCat);
        return $result ? true : false;
    }

    public static function getUserByToken()
    {

        $headers = null;

        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        } elseif (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();

            // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));

            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }

        if (isset($_SERVER['X-Authorization'])) {
            $headers = trim($_SERVER["X-Authorization"]);
        } elseif (isset($_SERVER['HTTP_X_AUTHORIZATION'])) {
            $headers = trim($_SERVER["HTTP_X_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();

            // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));

            if (isset($requestHeaders['X-Authorization'])) {
                $headers = trim($requestHeaders['X-Authorization']);
            }
        }
        if ($headers) {
            $headers = trim(str_replace('Bearer', '', $headers));
            $info = SUtil::decodeToken($headers);
            return $info;
        }
        return array();
    }

    public static function checkValidEmail($email)
    {
        return strpos($email, 'tk_') === false ? true : false;
    }

    public static function formatDate($time, $format = 'd/m/Y')
    {
        return date($format, strtotime($time . ' +7 hours'));
    }

    public static function getChildByParents($ids)
    {
        if ($ids) {
            $db = \JFactory::getDbo();
            $sql = 'SELECT u.id, u.block FROM #__users as u LEFT JOIN #__user_usergroup_map as m on m.user_id = u.id WHERE m.group_id = 3 AND u.invited_id IN (' . implode(',', $ids) . ')';
            //echo $sql; die;
            return $db->setQuery($sql)->loadAssocList();
        }
        return array();
    }

    public static function getChildList($id)
    {
        $list = array($id);
        $parent = array($id);
        do {
            $children = static::getChildByParents($parent);
            $parent = array();
            if ($children) {
                foreach ($children as $item) {
                    $parent[] = $item['id'];
                    if ($item['block'] == 0) {
                        $list[] = $item['id'];
                    }
                }
            }
        } while ($parent);
        return $list;
    }

    public static function getChildListByLevel($id, $levelParam)
    {
        $level = 0;
        $list = array(array(
            'id' => $id,
            'level' => $level
        ));
        $parent = array($id);
        do {
            $level++;
            $children = static::getChildByParents($parent);
            $parent = array();
            if ($children) {
                foreach ($children as $item) {
                    $parent[] = $item['id'];
                    if ($item['block'] == 0) {
                        array_push($list, array(
                            'id' => $item['id'],
                            'level' => $level
                        ));
                    }
                }
            }
        } while ($parent);
        if (isset($levelParam)) {
            $result = static::filter_by_value($list, 'level', $levelParam);
        } else {
            $result = $list;
        }
        // return array_map(
        //     function ($item) {
        //         return $item['id'];
        //     },
        //     $result
        // );
        return $result;
    }

    public static function filter_by_value($array, $index, $value)
    {
        if (is_array($array) && count($array) > 0) {
            foreach (array_keys($array) as $key) {
                $temp[$key] = $array[$key][$index];

                if ($temp[$key] == $value) {
                    $newarray[$key] = $array[$key];
                }
            }
        }
        return $newarray;
    }

    public static function getParent($id)
    {
        $db = \JFactory::getDbo();
        $sql = 'SELECT u.invited_id FROM #__users AS u LEFT JOIN #__user_usergroup_map as m on m.user_id = u.id WHERE  m.group_id = 10 AND u.block = 0 AND id =' . (int)$id;
        return $db->setQuery($sql)->loadResult();
    }

    public static function getParentsById($id)
    {
        $list = array($id);
        $child = $id;
        do {

            $parent = static::getParent($child);

            if ($parent) {
                $list[] = $parent;
                $child = $parent;
            }
        } while ($parent);
        return $list;
    }

    public static function sendSMS($phone, $content)
    {
        // $USERNAME  = 'BIZNET';
        // $PASSWORD  = '123456';
        // $BRANDNAME = 'VT DI DONG';
        // $MESSAGE = $content;
        // $TYPE  = 1;
        // $PHONE  = str_replace("'", '', $phone);
        // $IDREQ  = time();
        // $client  = new SoapClient("http://210.211.109.118/apibrandname/send?wsdl");
        // $result  = $client->send(array("USERNAME" => $USERNAME, "PASSWORD" => $PASSWORD, "BRANDNAME" => $BRANDNAME, "MESSAGE" => $MESSAGE, "TYPE" => $TYPE, "PHONE" => $PHONE, "IDREQ" => $IDREQ));
        // return $result;

        $Phone 		= $phone;
		$Content 	= rawurlencode($content);
		$ApiKey		= "005D3E0E086414D36C60A8BF76E569";
		$SecretKey	= "3221D69ABD12DA15ECF05342C501C6";
		$IsUnicode 	= 0;
		$Brandname 	= "BIZNET";
		$SmsType 	= 2;
		$url 		= "https://restapi.esms.vn/MainService.svc/json/SendMultipleMessage_V4_get?Phone=".$Phone ."&Content=".$Content."&ApiKey=".$ApiKey."&SecretKey=".$SecretKey."&IsUnicode=".$IsUnicode."&Brandname=".$Brandname."&SmsType=".$SmsType;
		$result 	= file_get_contents($url);
		return $result;
    }
}
