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
class RequestPackageForm extends AbtractForm
{
    public $id;
    public $ordering;
    public $state;
    public $checked_out;
    public $checked_out_time;

    public $name;
    public $email;
    public $phone;
    public $job;
    public $address;
    public $note;
    public $province;
    public $status;
    public $company;
    public $services;



    public function rule()
    {
        return array(
            'required' => array(
                'services',
                'company',
                'name',
                'phone',
                'province'
            ),
            // 'email' => array(
            //     'email'
            // ),
            
        );
    }

}