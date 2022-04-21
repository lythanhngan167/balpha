<?php
/**
 * Created by PhpStorm.
 * User: lvson
 * Date: 4/26/2019
 * Time: 10:50 AM
 */

namespace api\model\biz\shop;

use api\model\AbtractBiz;
use api\model\SUtil;
use JUri;

/**
 * @OA\Schema(required={"id", "title"}, @OA\Xml(name="ShopOrderBiz"))
 */
class ShopOrderBiz extends AbtractBiz
{
    /**
     * @OA\Property(format="id")
     * @var int
     */
    public $id;
    /**
     * @OA\Property(format="order_number")
     * @var string
     */
    public $order_number;
    /**
     * @OA\Property(format="created_date")
     * @var string
     */
    public $created_date;
    /**
     * @OA\Property(format="total")
     * @var string
     */
    public $total;
    /**
     * @OA\Property(format="orderstatus_name")
     * @var string
     */
    public $orderstatus_name;
    /**
     * @OA\Property(format="pay_note")
     * @var string
     */
    public $pay_note;
    public $payment_status;
    public $payment_text;
    public $payment_image;
    public $can_approve;
    public $can_upload;
    public $viewable = 0;

    public $customer_name = '';
    public $order_status_id = '';


    public function setAttributes($data)
    {
        $data['created_date'] = SUtil::formatDate($data['created_date'], 'd-m-Y H:i');
        if ($data['payment_image']) {
            $data['payment_image'] = JURI::base() . 'images/payment/' . $data['payment_image'];
        }
        if($data['order_status_id'] != 8){
            $data['can_approve'] = 0;
        }
        switch ($data['payment_status']) {
            case 0:
                $data['payment_text'] = 'Đang chờ thanh toán';
                break;

            case 1:
                $data['payment_text'] = 'Đã cập nhật thông tin thanh toán';
                break;

            case 9:
                $data['payment_text'] = 'Đã phê duyệt';
                break;
        }

        parent::setAttributes($data);
    }
}
