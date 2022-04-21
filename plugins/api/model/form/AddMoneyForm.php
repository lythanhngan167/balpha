<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/27/2019
 * Time: 8:01 AM
 */

namespace api\model\form;

use api\model\AbtractForm;

/**
 * @OA\Schema(required={"reason"}, @OA\Xml(name="AddressForm"))
 */
class AddMoneyForm extends AbtractForm
{
    /**
     * @OA\Property(example="customer_id")
     * @var int
     */
    public $money;
    public $bank;
    public $note;


    /**
     * @OA\Property(example="address")
     * @var string
     */
    public $address;


    public function rule()
    {
        $rule = array(
            'required' => array(
                'money',
                'bank'
            )
        );

        return $rule;
    }

}