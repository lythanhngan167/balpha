<?php
/**
 * Created by PhpStorm.
 * User: lvson
 * Date: 4/26/2019
 * Time: 10:50 AM
 */

namespace api\model\biz\shop;

use api\model\AbtractBiz;

/**
 * @OA\Schema(required={"id", "title"}, @OA\Xml(name="ShopAddressBiz"))
 */
class ShopAddressBiz extends AbtractBiz
{
    /**
     * @OA\Property(format="int64")
     * @var int
     */
    public $id;
    /**
     * @OA\Property(format="firstname")
     * @var string
     */
    public $name;
    /**
     * @OA\Property(format="telephone")
     * @var string
     */
    public $phone;

    /**
     * @OA\Property(format="address_1")
     * @var string
     */
    public $address_1;

    /**
     * @OA\Property(format="country_id")
     * @var int
     */
    public $country_id;


    /**
     * @OA\Property(format="zone_id")
     * @var int
     */
    public $zone_id;


    /**
     * @OA\Property(format="address")
     * @var string
     */
    public $address;

    /**
     * @OA\Property(format="zone_name")
     * @var string
     */
    public $zone_name;

    /**
     * @OA\Property(format="country_name")
     * @var string
     */
    public $country_name;

    /**
     * @OA\Property(format="is_default")
     * @var string
     */
    public $is_default;



    
}
