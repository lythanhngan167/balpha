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
 * @OA\Schema(required={"id", "title"}, @OA\Xml(name="ShopDiscountBiz"))
 */
class ShopDiscountBiz extends AbtractBiz
{
    /**
     * @OA\Property(format="int64")
     * @var int
     */
    public $id;
    /**
     * @OA\Property(format="int64")
     * @var int
     */
    public $discount_type;
    /**
     * @OA\Property(example="discount_value")
     * @var int
     */
    public $discount_value;


    
}
