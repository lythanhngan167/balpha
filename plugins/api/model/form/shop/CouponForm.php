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
class CouponForm extends AbtractForm
{
    /**
     * @OA\Property(example="0")
     * @var int
     */
    public $code;

    /**
     * @OA\Property(example="total")
     * @var string
     */
    public $total;

    /**
     * @OA\Property(example="product_id")
     * @var array
     */
    public $product_id;


    public function rule()
    {
        return array(
            'required' => array(
                'code',
                'total',
                'product_id'
            )
        );
    }

}