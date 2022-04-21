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
 * @OA\Schema(required={"reason"}, @OA\Xml(name="NotesForm"))
 */
class NotesForm extends AbtractForm
{
    /**
     * @OA\Property(example="customer_id")
     * @var int
     */
    public $customer_id;

    /**
     * @OA\Property(example="reason")
     * @var string
     */
    public $reason;


    public function rule()
    {
        $rule = array(
            'required' => array(
                'reason'
            )
        );

        return $rule;
    }

}