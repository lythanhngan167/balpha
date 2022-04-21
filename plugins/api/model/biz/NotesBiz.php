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
 * @OA\Schema(required={"id", "username"}, @OA\Xml(name="NotesBiz"))
 */
class NotesBiz extends AbtractBiz
{
    /**
     * @OA\Property(format="int64")
     * @var string
     */
    public $id;
    /**
     * @OA\Property(example="create_date")
     * @var string
     */
    public $create_date;
    /**
     * @OA\Property(example="create_date_formated")
     * @var string
     */
    public $create_date_formated;
    /**
     * @OA\Property(example="note")
     * @var string
     */
    public $note;
    

    public function setAttributes($data)
    {
        if(@$data['create_date']){
            $data['create_date_formated'] = date('d/m/Y', strtotime($data['create_date']));
            $data['time'] = date('H:i', strtotime($data['create_date']));
        }

        parent::setAttributes($data);
    }
}
