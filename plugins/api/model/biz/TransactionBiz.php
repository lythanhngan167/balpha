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
class TransactionBiz extends AbtractBiz
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
    public $title;
    /**
     * @OA\Property(example="phone")
     * @var string
     */
    public $type_transaction;
    /**
     * @OA\Property(example="place")
     * @var string
     */
    public $amount;

    /**
     * @OA\Property(example="email")
     * @var string
     */
    public $reference_id;

    /**
     * @OA\Property(example="sale_id")
     * @var int
     */
    public $status;

    /**
     * @OA\Property(example="category_id")
     * @var int
     */
    public $created_date;




    public function setAttributes($data)
    {

        $data['type_transaction'] = \JText::_('COM_TRANSACTION_HISTORY_TRANSACTIONHISTORIES_TYPE_TRANSACTION_OPTION_'.strtoupper($data['type_transaction']));
        $data['status'] = \JText::_('COM_TRANSACTION_HISTORY_TRANSACTIONHISTORIES_STATUS_OPTION_'.strtoupper($data['status']));
        $data['created_date'] = \JFactory::getDate($data['created_date'])->format("d-m-Y H:i");
        $data['amount'] = number_format($data['amount'],0,",",".").' '.BIZ_XU;

        parent::setAttributes($data);
    }
}
