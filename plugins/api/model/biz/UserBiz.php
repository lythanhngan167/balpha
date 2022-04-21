<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/27/2019
 * Time: 11:27 AM
 */

namespace api\model\biz;

use api\model\AbtractBiz;

/**
 * @OA\Schema(required={"id", "username"}, @OA\Xml(name="UserBiz"))
 */
class UserBiz extends AbtractBiz
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
     * @OA\Property(example="username")
     * @var string
     */
    public $username;
    /**
     * @OA\Property(example="email")
     * @var string
     */
    public $email;

    /**
     * @OA\Property(example="level")
     * @var int
     */
    public $level;

    public $level_tree;

    /**
     * @OA\Property(example="0")
     * @var string
     */
    public $is_sale;

    
    public $is_apple;


    public $id_biznet;

    public $briclevel;

    public $invited_id;
}