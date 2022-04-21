<?php
/**
 * Created by PhpStorm.
 * User: lvson
 * Date: 4/26/2019
 * Time: 10:50 AM
 */

namespace api\model\biz;

use api\model\AbtractBiz;

/**
 * @OA\Schema(required={"id", "title"}, @OA\Xml(name="ProjectBiz"))
 */
class ProjectBiz extends AbtractBiz
{
    /**
     * @OA\Property(format="int64")
     * @var int
     */
    public $id;
    /**
     * @OA\Property(example="title")
     * @var string
     */
    public $title;
    /**
     * @OA\Property(example="short_description")
     * @var string
     */
    public $short_description;
    /**
     * @OA\Property(example="description")
     * @var string
     */
    public $description;

        /**
     * @OA\Property(example="is_recruitment")
     * @var int
     */
    public $is_recruitment;

    /**
     * @OA\Property(example="file_1")
     * @var string
     */
    public $file_1;

    /**
     * @OA\Property(example="file_2")
     * @var string
     */
    public $file_2;

    /**
     * @OA\Property(example="file_3")
     * @var string
     */
    public $file_3;

    /**
     * @OA\Property(example="file_4")
     * @var string
     */
    public $file_4;

    /**
     * @OA\Property(example="file_5")
     * @var string
     */
    public $file_5;

    /**
     * @OA\Property()
     * @var array
     */
    public $list = array();
}
