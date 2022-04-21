<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/27/2019
 * Time: 8:01 AM
 */

namespace api\model\form\shop;

use api\model\AbtractForm;

/**
 * @OA\Schema(required={"reason"}, @OA\Xml(name="AddressForm"))
 */
class VoucherForm extends AbtractForm
{
    /**
     * @OA\Property(example="0")
     * @var int
     */
    public $code;


    public function rule()
    {
        return array(
            'required' => array(
                'code'
            )
        );
    }

}