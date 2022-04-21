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
 * @OA\Schema(required={"code", "token", "password"}, @OA\Xml(name="ResetForm"))
 */
class ResetForm extends AbtractForm
{
    /**
     * @OA\Property(example="code")
     * @var string
     */
    public $code;

    /**
     * @OA\Property(example="token")
     * @var string
     */
    public $token;


    /**
     * @OA\Property(example="password")
     * @var string
     */
    public $password;




    public function rule()
    {
        return array(
            'required' => array(
                'code',
                'token',
                'password'
            ),
            'lengthMin' => array(
                array(
                    'password', 6
                )
            )
        );
    }

}