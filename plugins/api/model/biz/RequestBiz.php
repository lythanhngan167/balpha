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
 * @OA\Schema(required={"id", "title"}, @OA\Xml(name="ContentBiz"))
 */
class RequestBiz extends AbtractBiz
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
     * @OA\Property(example="content")
     * @var string
     */
    public $content;
    /**
     * @OA\Property(example="request_id")
     * @var int
     */
    public $request_id;

    /**
     * @OA\Property(example="request_name")
     * @var string
     */
    public $request_name;


    /**
     * @OA\Property(example="request_phone")
     * @var string
     */
    public $request_phone;



    /**
     * @OA\Property(example="receipt_id")
     * @var int
     */
    public $receipt_id;


    /**
     * @OA\Property(example="receipt_name")
     * @var string
     */
    public $receipt_name;

    /**
     * @OA\Property(example="receipt_phone")
     * @var string
     */
    public $receipt_phone;

    /**
     * @OA\Property(example="receipt_id_2")
     * @var int
     */
    public $receipt_id_2;
    
    /**
     * @OA\Property(example="receipt_2_name")
     * @var string
     */
    public $receipt_2_name;

    /**
     * @OA\Property(example="receipt_2_phone")
     * @var string
     */
    public $receipt_2_phone;

    /**
     * @OA\Property(example="status_id")
     * @var string
     */
    public $status_id;

    /**
     * @OA\Property(example="created_date")
     * @var string
     */
    public $created_date;

    public function setAttributes($data){
        if(@$data['created_date']){
            $data['created_date'] = date('d/m/Y H:i', strtotime($data['created_date']));
        }
        $data['content'] = nl2br($data['content']);
        parent::setAttributes($data);
    }
}
