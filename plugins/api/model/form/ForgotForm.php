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
 * @OA\Schema(required={"email"}, @OA\Xml(name="ForgotForm"))
 */
class ForgotForm extends AbtractForm
{
    /**
     * @OA\Property(example="email")
     * @var string
     */
    public $email;

    public $phone;

    public $typeConfirm;


    public function rule()
    {
        if ($this->typeConfirm == "email") {
            return array(
                'required' => array(
                    'email',
                    'typeConfirm'
                )
            );
        } else if ($this->typeConfirm == "phone") {
            return array(
                'required' => array(
                    'phone',
                    'typeConfirm'
                )
            );
        }
    }
}
