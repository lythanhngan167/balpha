<?php
/**
 * Created by PhpStorm.
 * User: thai
 * Date: 09/01/2020
 * Time: 10:50 AM
 */

namespace api\model\biz;

use api\model\AbtractBiz;

/**
 * @OA\Schema(required={"id", "name", "image"}, @OA\Xml(name="K2CategoryBiz"))
 */
class K2CategoryBiz extends AbtractBiz
{
    /**
     * @OA\Property(format="int64")
     * @var int
     */
    public $id;
    /**
     * @OA\Property(example="name")
     * @var string
     */
    public $name;
    /**
     * @OA\Property(example="image")
     * @var string
     */
    public $image;

    /**
     * @OA\Property(example="parent")
     * @var int
     */
    public $parent;

    /**
     * @OA\Property(example="layout")
     * @var string
     */
    public $layout;

    public function setAttributes($data) {
        parent::setAttributes($data);
    }
    
}
