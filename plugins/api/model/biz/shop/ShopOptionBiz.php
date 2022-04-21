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
 * @OA\Schema(required={"id", "title"}, @OA\Xml(name="ShopOptionBiz"))
 */
class ShopOptionBiz extends AbtractBiz
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
    public $option_id;
    /**
     * @OA\Property(example="product_option_id")
     * @var int
     */
    public $product_option_id;



    /**
     * @OA\Property(example="sku")
     * @var string
     */
    public $sku;
    /**
     * @OA\Property(example="text")
     * @var string
     */
    public $text;

    /**
     * @OA\Property(example="price")
     * @var string
     */
    public $price;

    /**
     * @OA\Property(example="price_sign")
     * @var string
     */
    public $price_sign;

    /**
     * @OA\Property(example="price_type")
     * @var string
     */
    public $price_type;

    /**
     * @OA\Property(example="image")
     * @var string
     */
    public $image;

    public $weight;
    public $weight_sign;
    public $quantity;
    public $check_stock;
    public $selected;


}
