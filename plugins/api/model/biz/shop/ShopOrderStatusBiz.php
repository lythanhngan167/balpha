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
 * @OA\Schema(required={"id", "name"}, @OA\Xml(name="ShopOrderStatusBiz"))
 */
class ShopOrderStatusBiz extends AbtractBiz
{
    /**
     * @OA\Property(format="id")
     * @var int
     */
    public $id;
    /**
     * @OA\Property(format="name")
     * @var string
     */
    public $name;

}
