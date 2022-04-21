<?php

/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/27/2019
 * Time: 8:01 AM
 */

namespace api\model\form\erp;

use api\model\AbtractForm;

/**
 * @OA\Schema(required={"username", "password", "name", "email"}, @OA\Xml(name="RegisterForm"))
 */
class ERPRegisterForm extends AbtractForm
{
    public $name;
    public $phone;
    public $email;
    public $password;
    public $id_biznet;
    public $invited_id;
    public $is_production;

    public function rule()
    {
        return array(
            'required' => array(
                'name',
                'phone',
                'email',
                'password',
                'name',
                'is_production'
            ),
            'email' => array(
                'email'
            ),
            'boolean'=> array (
                'is_production'
            )
            ,
            'lengthMin' => array(
                array(
                    'password', 6
                )
            ),
            'lengthMax' => array(
                array(
                    'name', 400,
                ),
                array(
                    'password', 100,
                ),
                array(
                    'email', 100,
                )
            )
        );
    }
}
