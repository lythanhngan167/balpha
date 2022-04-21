<?php
/**
 * Created by PhpStorm.
 * User: lvson
 * Date: 4/26/2019
 * Time: 10:50 AM
 */

namespace api\model\biz;

use api\model\AbtractBiz;

/**
 * @OA\Schema(required={"id", "title"}, @OA\Xml(name="ProjectBiz"))
 */
class RechargeBiz extends AbtractBiz
{
    /**
     * @OA\Property(format="int64")
     * @var int
     */
    public $id;
    /**
     * @OA\Property(example="name")
     * @var string
     */
    public $code;
    /**
     * @OA\Property(example="phone")
     * @var string
     */
    public $bank_name;
    /**
     * @OA\Property(example="place")
     * @var string
     */
    public $amount;

    /**
     * @OA\Property(example="email")
     * @var string
     */
    public $note;

    /**
     * @OA\Property(example="sale_id")
     * @var int
     */
    public $status;

    /**
     * @OA\Property(example="category_id")
     * @var int
     */
    public $created_time;

    public $pay_note;


    public function setAttributes($data)
    {

        if($data['status'] == 'unconfirm'){
            $data['pay_note'] = 'Vui lòng chuyển khoản vào tài khoản bên trên với nội dung chuyển tiền: Mã nạp tiền + username .<br />Nội dung chuyển tiền của bạn: '.$data['code'].' + '.\JFactory::getUser($data['created_by'])->username;
        }
        $data['bank_name'] = \JText::_('COM_RECHARGE_RECHARGES_BANK_NAME_OPTION_'.strtoupper($data['bank_name']));
        $data['status'] = \JText::_('COM_RECHARGE_RECHARGES_STATUS_OPTION_'.strtoupper($data['status']));
        $data['created_time'] = \JFactory::getDate($data['created_time'])->format("d-m-Y H:i");
        $data['amount'] = number_format($data['amount'],0,",",".").' '.BIZ_XU;

        parent::setAttributes($data);
    }
}
