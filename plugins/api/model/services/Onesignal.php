<?php
/**
 * Created by PhpStorm.
 * User: lvson
 * Date: 8/1/2019
 * Time: 4:41 PM
 */

namespace api\model\services;
use api\model\Sconfig;
class Onesignal
{
    public static function sendMessage()
    {
        $config = new Sconfig();
        $appKey = $config->onesignalAppKey;
        $restKey = $config->onesignalRestKey;
        $content = array(
            "en" => 'English Message '.time()
        );

        $fields = array(
            'app_id' => $appKey,
            'included_segments' => array('Active Users'),
            'data' => array("foo" => "bar"),
            'contents' => $content
        );

        $fields = json_encode($fields);
        print("\nJSON sent:\n");
        print($fields);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
            'Authorization: Basic '.$restKey));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response = curl_exec($ch);
        print_r($response);
        curl_close($ch);

        return $response;
    }

}