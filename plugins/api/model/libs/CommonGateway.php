<?php


namespace api\model\libs;


use api\model\AbtractBiz;
use api\model\dao\shop\ShopOrderDao;

class CommonGateway extends AbtractBiz
{

    public static function getGateway($name, $config)
    {
        $instance = null;
        switch ($name) {
            case 'OnepayDomestic':
                $instance = new OnepayGateway('domestic', $config);
                break;
            case 'OnepayInternational':
                $instance = new OnepayGateway('international', $config);
                break;
            case 'VNPay':
                $instance = new VnpayGateway($config);
                break;
        }
        return $instance;
    }

    public static function updateOrder($params){
        $dao = new ShopOrderDao();
        return $dao->update($params);
    }
}