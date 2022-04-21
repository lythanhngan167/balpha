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
 * @OA\Schema(required={"username", "password", "name", "email"}, @OA\Xml(name="RegisterForm"))
 */
class RegisterForm extends AbtractForm
{
    public $mobile;
    /**
     * @OA\Property(example="username")
     * @var string
     */
    public $username;
    /**
     * @OA\Property(example="password")
     * @var string
     */
    public $password;
    /**
     * @OA\Property(example="name")
     * @var string
     */
    public $name;
    /**
     * @OA\Property(example="email")
     * @var string
     */
    public $email;

    /**
     * @OA\Property(example="group")
     * @var string
     */
    public $group;
    public $device;
    public $af_phone;
    public $province;
    public $favorite_service;
    public $code_token;
    public $code;

    public function rule()
    {
        return array(
            'required' => array(
                'username',
                'password',
                'name'
            ),
            'email' => array(
                'email'
            ),
            'lengthMin' => array(
                array(
                    'password', 6
                )
            )
        );
    }

}
