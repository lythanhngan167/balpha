<?php

namespace api\model\libs;

use api\model\AbtractBiz;
use api\model\PaymentInterface;
use api\model\dao\shop\ShopPaymentDao;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class OnepayGateway extends AbtractBiz implements PaymentInterface
{
    private $token = 'A3EFDFABA8653DF2342E8DAC29B51AF0';
    private $merchant = 'ONEPAY';
    private $accessCode = 'D67342C2';
    private $gateWay = 'domestic';
    private $config;
    private $url_domestic = '/onecomm-pay/vpc.op?';
    private $url_international = '/vpcpay/vpcpay.op?';
    private $url_request = '';
    private $url_base = 'https://mtf.onepay.vn';
    private $url_query_domectic = '/onecomm-pay/Vpcdps.op?';
    private $url_query_international = '/vpcpay/Vpcdps.op?';
    private $error;
    private $order_id;
    private $requestParams = array(
        'vpc_Version' => 2,
        'vpc_Currency' => 'VND',
        'vpc_Command' => 'pay',
        'vpc_AccessCode' => '',
        'vpc_Merchant' => '',
        'vpc_Locale' => 'vn',
        'vpc_ReturnURL' => '',
        'vpc_MerchTxnRef' => '',
        'vpc_OrderInfo' => '',
        'vpc_Amount' => '',
        'vpc_TicketNo' => '',
        'AgainLink' => '',
        'Title' => '',
    );

    private $sendParams = array();

    public function __construct($name)
    {
        $this->gateWay = $name;
    }

    /**
     * @return string
     */
    public function getGateWay()
    {
        return $this->gateWay;
    }

    /**
     * @param string $gateWay
     */
    public function setGateWay($gateWay)
    {
        $this->gateWay = $gateWay;
    }

    public function getConfig()
    {
        $dao = new ShopPaymentDao();
        $this->config = $dao->getPaymentInfo(array('where' => array('name = \'os_onepay\'')));

        $params = json_decode($this->config['params'], true);
        switch ($this->gateWay) {
            case 'domestic':
                if ($params['enviroment'] == 'dev') {
                    $this->token = 'A3EFDFABA8653DF2342E8DAC29B51AF0';
                    $this->accessCode = 'D67342C2';
                    $this->merchant = 'ONEPAY';
                    $this->url_base = 'https://mtf.onepay.vn';
                } else {
                    $this->token = $params['domestic_token'];
                    $this->accessCode = $params['domestic_access_code'];
                    $this->merchant = $params['domestic_merchant_id'];
                    $this->url_base = 'https://onepay.vn';
                }
                break;

            case 'international':
                if ($params['enviroment'] == 'dev') {
                    $this->token = '6D0870CDE5F24F34F3915FB0045120DB';
                    $this->accessCode = '6BEB2546';
                    $this->merchant = 'TESTONEPAY';
                    $this->url_base = 'https://mtf.onepay.vn';
                } else {
                    $this->token = $params['international_token'];
                    $this->accessCode = $params['international_access_code'];
                    $this->merchant = $params['international_merchant_id'];
                    $this->url_base = 'https://onepay.vn';
                }
                break;
        }

    }

    public function purchase()
    {
        // TODO: Implement processPayment() method.
        $url = $this->url_request . implode('&', $this->sendParams);
        $dao = new ShopPaymentDao();
        CommonGateway::updateOrder(array(
            'set' => array(
                'payment_request = ' . $dao->db->quote($url),
                'payment_request_date = ' . $dao->db->quote(\JFactory::getDate()->toSql())
            ),
            'where' => array(
                'id = ' . $this->order_id
            )
        ));
        return $url;
    }

    public function setParams($params = array())
    {
        $this->order_id = $params['order_id'];
        $params['order_id'] .= '_' . time();
        $this->getConfig();
        $this->requestParams['vpc_AccessCode'] = $this->accessCode;
        $this->requestParams['vpc_Merchant'] = $this->merchant;

        $this->requestParams['vpc_MerchTxnRef'] = $params['order_id'];
        $this->requestParams['vpc_OrderInfo'] = $params['order_no'];
        $this->requestParams['vpc_Amount'] = (int)$params['order_amount'] * 100;
        $this->requestParams['vpc_TicketNo'] = @$_SERVER['REMOTE_HOST'] ? @$_SERVER['REMOTE_HOST'] : @$_SERVER['REMOTE_ADDR'];

        $this->requestParams['Title'] = $params['order_no'];
        $this->requestParams['AgainLink'] = 'http://minhcaumart.vn/';
        $this->requestParams['vpc_ReturnURL'] = $params['return_url'];
        // TODO: Implement setParams() method.
        $this->sendParams = array();
        $hashParams = array();
        ksort($this->requestParams);
        foreach ($this->requestParams as $key => $val) {
            $val = trim($val);
            $this->sendParams[] = "{$key}=" . urlencode($val);
            if ((strlen($val) > 0) && ((substr($key, 0, 4) == "vpc_") || (substr($key, 0, 5) == "user_"))) {
                $hashParams[] = "{$key}={$val}";
            }

        }
        $this->sendParams[] = 'vpc_SecureHash=' . strtoupper(hash_hmac('SHA256', implode('&', $hashParams), pack('H*', $this->token)));
        if ($this->gateWay == 'domestic') {
            $this->url_request = $this->url_base . $this->url_domestic;
        } else {
            $this->url_request = $this->url_base . $this->url_international;
        }
    }

    public function validatePayment($params = array())
    {

        $this->getConfig();
        ksort($params);
        if ($params["vpc_TxnResponseCode"] != "7" && $params["vpc_TxnResponseCode"] != "No Value Returned") {
            $hashParams = array();
            foreach ($params as $key => $value) {
                $value = urlencode($value);
                if ($key != "vpc_SecureHash" && (strlen($value) > 0) && ((substr($key, 0, 4) == "vpc_") || (substr($key, 0, 5) == "user_"))) {
                    $hashParams[] = "{$key}={$value}";
                }
            }
            if (strtoupper($params["vpc_SecureHash"]) == strtoupper(hash_hmac('SHA256', implode('&', $hashParams), pack('H*', $this->token)))) {
                return true;
            }
        }
        $this->error = $this->getPaymentError(@$params["vpc_TxnResponseCode"]);

        return false;
    }


    public function getError()
    {
        return $this->error;
    }

    function getPaymentError($responseCode)
    {

        switch ($responseCode) {
            case "0" :
                $result = "Giao dịch thành công - Approved";
                break;
            case "1" :
                $result = "Ngân hàng từ chối giao dịch - Bank Declined";
                break;
            case "3" :
                $result = "Mã đơn vị không tồn tại - Merchant not exist";
                break;
            case "4" :
                $result = "Không đúng access code - Invalid access code";
                break;
            case "5" :
                $result = "Số tiền không hợp lệ - Invalid amount";
                break;
            case "6" :
                $result = "Mã tiền tệ không tồn tại - Invalid currency code";
                break;
            case "7" :
                $result = "Lỗi không xác định - Unspecified Failure ";
                break;
            case "8" :
                $result = "Số thẻ không đúng - Invalid card Number";
                break;
            case "9" :
                $result = "Tên chủ thẻ không đúng - Invalid card name";
                break;
            case "10" :
                $result = "Thẻ hết hạn/Thẻ bị khóa - Expired Card";
                break;
            case "11" :
                $result = "Thẻ chưa đăng ký sử dụng dịch vụ - Card Not Registed Service(internet banking)";
                break;
            case "12" :
                $result = "Ngày phát hành/Hết hạn không đúng - Invalid card date";
                break;
            case "13" :
                $result = "Vượt quá hạn mức thanh toán - Exist Amount";
                break;
            case "21" :
                $result = "Số tiền không đủ để thanh toán - Insufficient fund";
                break;
            case "99" :
                $result = "Người sủ dụng hủy giao dịch - User cancel";
                break;
            default :
                $result = "Giao dịch thất bại - Failured";
        }
        return $result;
    }
}