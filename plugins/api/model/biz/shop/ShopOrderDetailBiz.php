<?php
/**
 * Created by PhpStorm.
 * User: lvson
 * Date: 4/26/2019
 * Time: 10:50 AM
 */

namespace api\model\biz\shop;

use api\model\biz\shop\ShopOrderBiz;
use Joomla\Registry\Registry;
/**
 * @OA\Schema(required={"id", "title"}, @OA\Xml(name="ShopOrderBiz"))
 */
class ShopOrderDetailBiz extends ShopOrderBiz
{
   
    /**
     * @OA\Property(format="payment_method_title")
     * @var string
     */
    public $payment_method_title;
    /**
     * @OA\Property(format="comment")
     * @var string
     */
    public $comment;
    /**
     * @OA\Property(format="options")
     * @var string
     */
    public $options;

    /**
     * @OA\Property(format="shipping_name")
     * @var string
     */
    public $shipping_name;

    /**
     * @OA\Property(format="shipping_telephone")
     * @var string
     */
    public $shipping_telephone;

    /**
     * @OA\Property(format="ship_address")
     * @var string
     */
    public $ship_address;

    /**
     * @OA\Property(format="params")
     * @var string
     */
    public $params;
    public $shipping_method;
    public $shipping_method_title;
    public $ref_fee;
    public $discount_code;
    public $payment_status;
    public $delivery_date;

    public function setAttributes($data){
        if($data['params']){
            $params = new Registry($data['params']);
            $data['params'] = nl2br($params->get('payment_info'));
        }
        if ($data['coupon_code'] != '') {
            $data['discount_code'] = $data['coupon_code'];
        }
        if ($data['voucher_code'] != '') {
            $data['discount_code'] = $data['voucher_code'];
        }

        if ($data['delivery_date']) {
            if ($data['delivery_hour']) {
                $data['delivery_date'] = date('d/m/Y', strtotime($data['delivery_date'])) . ', ' . $data['delivery_hour'];
            } else {
                $data['delivery_date'] = date('d/m/Y H:i', strtotime($data['delivery_date']));
            }

        }
        parent::setAttributes($data);
    }
}
