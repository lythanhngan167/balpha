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
 * @OA\Schema(required={"id", "title"}, @OA\Xml(name="ShopProductOptionBiz"))
 */
class ShopProductOptionBiz extends AbtractBiz
{
    /**
     * @OA\Property(format="int64")
     * @var boolean
     */
    public $required;
    /**
     * @OA\Property(format="int64")
     * @var int
     */
    public $id;



    /**
     * @OA\Property(example="option_type")
     * @var string
     */
    public $option_type;

    /**
     * @OA\Property(example="option_name")
     * @var string
     */
    public $option_name;

    /**
     * @OA\Property(example="options")
     * @var array
     */
    public $options = [];
    
}
