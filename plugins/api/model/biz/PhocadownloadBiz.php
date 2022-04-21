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
 * @OA\Schema(required={"id", "title"}, @OA\Xml(name="PhocadownloadBiz"))
 */
class PhocadownloadBiz extends AbtractBiz
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
     * @OA\Property(example="filename")
     * @var string
     */
    public $filename;
    /**
     * @OA\Property(example="description")
     * @var string
     */
    public $description;

    /**
     * @OA\Property(example="features")
     * @var string
     */
    public $features;

    /**
     * @OA\Property(example="notes")
     * @var string
     */
    public $notes;

    /**
     * @OA\Property(example="issued_date")
     * @var string
     */
    public $issued_date;

    /**
     * @OA\Property(example="effected_date")
     * @var string
     */
    public $effected_date;  



    public function setAttributes($data){
        if(@$data['filename']){
            $data['filename'] = \JURI::root().'phocadownload/'.$data['filename'];
        }
        parent::setAttributes($data);
    }
}
