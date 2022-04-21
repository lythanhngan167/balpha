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
class UpdateMemberForm extends AbtractForm
{
    public $address;
    /**
     * @OA\Property(example="username")
     * @var string
     */
    public $birthday;
    /**
     * @OA\Property(example="password")
     * @var string
     */
    public $card_behind;
    /**
     * @OA\Property(example="name")
     * @var string
     */
    public $card_front;
    /**
     * @OA\Property(example="email")
     * @var string
     */
    public $card_id;

    /**
     * @OA\Property(example="group")
     * @var string
     */
    public $job;
    public $level;
    public $name;
    public $province;
    public $sex;
    public $username;
    public $email;
    public $bank_name;
    public $bank_account_name;
    public $bank_account_number;



    public function rule()
    {
        return array(
            'required' => array(
               'address',
               'birthday',
             //   'card_id',
                'job',
             //   'username',
                'name',
             //   'level',
                'province',
             //   'email',
                'sex'
            ),
            /*'email' => array(
                'email'
            ),
            'lengthMin' => array(
                array(
                    'password', 6
                )
            )*/
        );
    }

}