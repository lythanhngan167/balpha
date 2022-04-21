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
class CategoryBiz extends AbtractBiz
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
     * @OA\Property(example="amount")
     * @var int
     */
    public $amount;
    /**
     * @OA\Property(example="max_pick")
     * @var int
     */
    public $max_pick;

    /**
     * @OA\Property(example="price")
     * @var int
     */
    public $price;

    /**
     * @OA\Property(example="price_formated")
     * @var int
     */
    public $price_formated;

     /**
     * @OA\Property(example="image")
     * @var string
     */
    public  $image;

    /**
     * @OA\Property(example="description")
     * @var string
     */
    public  $description;

    /**
     * @OA\Property(example="is_return")
     * @var int
     */
    public $is_return;


    public function setAttributes($data){
        if(isset($data['params'])){
            $tmp = json_decode($data['params']);
            if($tmp->image){
                $data['image'] = \JURI::root().$tmp->image;
            }
        }
        $data['is_return'] = 0;
        if($data['id'] == 150){
            $data['is_return'] = 1;
        }
        parent::setAttributes($data);

    }
}
