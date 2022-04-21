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
 * @OA\Schema(required={"reason"}, @OA\Xml(name="CategoryFrom"))
 */
class CategoryFrom extends AbtractForm
{
    /**
     * @OA\Property(example="0")
     * @var int
     */
    public $catid;
    /**
     * @OA\Property(example="0")
     * @var int
     */
    public $id;

    /**
     * @OA\Property(example="0")
     * @var string
     */
    public $q;

    /**
     * @OA\Property(example="0")
     * @var int
     */

    public $offset;

    /**
     * @OA\Property(example="0")
     * @var int
     */

    public $limit;

}