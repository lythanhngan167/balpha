<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/27/2019
 * Time: 8:01 AM
 */

namespace api\model\form;

use api\model\AbtractForm;

/**
 * @OA\Schema(required={"title", "content", "request_id"}, @OA\Xml(name="QuestionForm"))
 */
class QuestionForm extends AbtractForm
{
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


    public function rule()
    {
        return array(
            'required' => array(
                'title',
                'content'
            )
        );
    }

}