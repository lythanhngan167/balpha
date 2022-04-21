<?php
/**
 * Created by PhpStorm.
 * User: lvson
 * Date: 4/26/2019
 * Time: 10:50 AM
 */

namespace api\model\biz;

use api\model\AbtractBiz;
use api\model\libs\simple_html_dom;
/**
 * @OA\Schema(required={"id", "title"}, @OA\Xml(name="ContentBiz"))
 */
class UserNotificationBiz extends AbtractBiz
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
     * @OA\Property(example="introtext")
     * @var string
     */
    public $message;
    /**
     * @OA\Property(example="fulltext")
     * @var string
     */
    public $user_id;
    /**
     * @OA\Property(example="fulltext")
     * @var boolean
     */
    public $seen_flag;

    public $created_date;

    public function setAttributes($data){
        parent::setAttributes($data);
    }
}
