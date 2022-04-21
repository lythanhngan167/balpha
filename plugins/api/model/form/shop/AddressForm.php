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
class AddressForm extends AbtractForm
{
    /**
     * @OA\Property(example="0")
     * @var int
     */
    public $id;

    /**
     * @OA\Property(example="name")
     * @var string
     */
    public $name;

    /**
     * @OA\Property(example="phone")
     * @var string
     */
    public $phone;

    /**
     * @OA\Property(example="address_1")
     * @var string
     */
    public $address_1;

    /**
     * @OA\Property(example="country_id")
     * @var string
     */
    public $country_id;

    /**
     * @OA\Property(example="zone_id")
     * @var string
     */
    public $zone_id;

    /**
     * @OA\Property(example="is_default")
     * @var string
     */
    public $is_default;

    public function rule()
    {
        return array(
            'required' => array(
                'name',
                'address_1',
                'country_id',
                'zone_id'
            )
        );
    }

}