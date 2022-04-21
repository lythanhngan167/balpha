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
 * @OA\Schema(required={"id", "title"}, @OA\Xml(name="PhocadownloadCategoriesBiz"))
 */
class PhocadownloadCategoriesBiz extends AbtractBiz
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
     * @OA\Property(example="image")
     * @var string
     */
    public $image;

    public $list;

    public function setAttributes($data)
    {
        if (@$data['params']) {
            $info = json_decode($data['params']);
            if ($info->image) {
                $data['image'] = \JURI::root() . $info->image;
           }
        }
        parent::setAttributes($data);
    }
}
