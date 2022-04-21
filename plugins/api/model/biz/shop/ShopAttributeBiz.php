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
class ShopAttributeBiz extends AbtractBiz
{
    /**
     * @OA\Property(format="int64")
     * @var string
     */
    public $name;
    /**
     * @OA\Property(format="int64")
     * @var string
     */
    public $value;

    
}
