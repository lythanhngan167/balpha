<?php

namespace api\model\libs;

use api\model\AbtractBiz;
use api\model\PaymentInterface;
use api\model\dao\shop\ShopPaymentDao;
use JUri;

class VnpayGateway extends AbtractBiz implements PaymentInterface
{
    private $token = 'XHGDDBYKDHVCFZPEIQHRHOMHLSNDJOSB';
    private $merchant = 'MINHCAU1';
    private $url_request = '';
    private $url_base = 'http://sandbox.vnpayment.vn/paymentv2/vpcpay.html?';
    private $error;
    private $order_id;
    private $requestParams = array(
        'vnp_Version' => '2.0.0',
        'vnp_CurrCode' => 'VND',
        'vnp_Command' => 'pay',
        'vnp_TmnCode' => '',
        'vnp_CreateDate' => '',
        'vnp_Locale' => 'vn',
        'vnp_ReturnUrl' => '',
        'vnp_TxnRef' => '',
        'vnp_OrderInfo' => '',
        'vnp_Amount' => '',
        'vnp_IpAddr' => ''
    );

    private $sendParams = array();

    private $responeCode = '';

    public function __construct($config)
    {
        if ($config->get('enviroment') === 'dev') {
            $this->token = 'XHGDDBYKDHVCFZPEIQHRHOMHLSNDJOSB';
            $this->merchant = 'MINHCAU1';
            $this->url_base = 'http://sandbox.vnpayment.vn/paymentv2/vpcpay.html?';
        } else {
            $this->token = $config->get('token');
            $this->merchant = $config->get('merchant_id');
            $this->url_base = 'https://pay.vnpay.vn/vpcpay.html?';
        }
    }


    public function purchase()
    {
        // TODO: Implement processPayment() method.
        $url = $this->url_request;
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
        $this->requestParams['vnp_TmnCode'] = $this->merchant;

        $this->requestParams['vnp_TxnRef'] = $params['order_id'];
        $this->requestParams['vnp_OrderInfo'] = $params['order_no'];
        $this->requestParams['vnp_Amount'] = (int)$params['order_amount'] * 100;
        $this->requestParams['vnp_IpAddr'] = @$_SERVER['REMOTE_HOST'] ? @$_SERVER['REMOTE_HOST'] : @$_SERVER['REMOTE_ADDR'];
        $this->requestParams['vnp_CreateDate'] = date('YmdHis', strtotime("+7 hours"));
        $this->requestParams['vnp_ReturnUrl'] = $params['return_url'];
        // TODO: Implement setParams() method.
        $this->sendParams = array();
        $hashParams = array();
        ksort($this->requestParams);
        foreach ($this->requestParams as $key => $val) {
            $val = trim($val);
            $this->sendParams[] = "{$key}=" . urlencode($val);
            $hashParams[] = "{$key}={$val}";

        }
        $this->sendParams[] = 'vnp_SecureHashType=SHA256';
        $this->sendParams[] = 'vnp_SecureHash=' . hash('sha256', $this->token . implode('&', $hashParams));

        $this->url_request = $this->url_base . implode('&', $this->sendParams);
    }


    public function validatePayment($params = array())
    {

        ksort($params);
        $hashParams = array();
        foreach ($params as $key => $value) {
            if ($key == 'vnp_SecureHashType' || $key == 'vnp_SecureHash') {
                continue;
            }
            if (substr($key, 0, 4) == "vnp_") {
                $hashParams[] = "{$key}={$value}";
            }
        }
        $secureHash = hash('sha256', $this->token . implode('&', $hashParams));
        $this->responeCode = $params['vnp_ResponseCode'];
        return $secureHash == $params['vnp_SecureHash'];
    }

    public function isSuccessPayment()
    {
        return $this->responeCode === '00';
    }


    public function getError()
    {
        return static::getPaymentError($this->responeCode);
    }

    public static function getPaymentError($responseCode)
    {

        switch ($responseCode) {
            case "00" :
                $result = "Giao dịch thành công";
                break;
            case "01" :
                $result = "Giao dịch đã tồn tại";
                break;
            case "02" :
                $result = "Merchant không hợp lệ (kiểm tra lại vnp_TmnCode)";
                break;
            case "03" :
                $result = "Dữ liệu gửi sang không đúng định dạng";
                break;
            case "04" :
                $result = "Khởi tạo GD không thành công do Website đang bị tạm khóa";
                break;
            case "05" :
                $result = "Giao dịch không thành công do: Quý khách nhập sai mật khẩu quá số lần quy định. Xin quý khách vui lòng thực hiện lại giao dịch";
                break;
            case "13" :
                $result = "Giao dịch không thành công do Quý khách nhập sai mật khẩu xác thực giao dịch (OTP). Xin quý khách vui lòng thực hiện lại giao dịch.";
                break;
            case "07" :
                $result = "Giao dịch bị nghi ngờ là giao dịch gian lận";
                break;
            case "09" :
                $result = "Giao dịch không thành công do: Thẻ/Tài khoản của khách hàng chưa đăng ký dịch vụ InternetBanking tại ngân hàng.";
                break;
            case "10" :
                $result = "Giao dịch không thành công do: Khách hàng xác thực thông tin thẻ/tài khoản không đúng quá 3 lần";
                break;
            case "11" :
                $result = "Giao dịch không thành công do: Đã hết hạn chờ thanh toán. Xin quý khách vui lòng thực hiện lại giao dịch.";
                break;
            case "12" :
                $result = "Giao dịch không thành công do: Thẻ/Tài khoản của khách hàng bị khóa.";
                break;
            case "51" :
                $result = "Giao dịch không thành công do: Tài khoản của quý khách không đủ số dư để thực hiện giao dịch.";
                break;
            case "65" :
                $result = "Giao dịch không thành công do: Tài khoản của Quý khách đã vượt quá hạn mức giao dịch trong ngày.";
                break;
            case "08" :
                $result = "Giao dịch không thành công do: Hệ thống Ngân hàng đang bảo trì. Xin quý khách tạm thời không thực hiện giao dịch bằng thẻ/tài khoản của Ngân hàng này.";
                break;
            case "99" :
                $result = "Các lỗi khác (lỗi còn lại, không có trong danh sách mã lỗi đã liệt kê)";
                break;
            default :
                $result = "Giao dịch thất bại";
        }
        return $result;
    }
}
