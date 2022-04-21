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
 * @OA\Schema(required={"username", "password"}, @OA\Xml(name="LoginForm"))
 */
class LoginForm extends AbtractForm
{
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

    public $device;


    public function rule()
    {
        return array(
            'required' => array(
                'username',
                'password'
            )
        );
    }

}