<?php

/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/27/2019
 * Time: 8:01 AM
 */

namespace api\model\form\erp;

use api\model\AbtractForm;


class ERPUpdatePhoneByIdBiznetForm extends AbtractForm
{

    public $id_biznet;
    public $phone;
    public $is_production;

    public function rule()
    {
        return array(
            'required' => array(
                'id_biznet',
                'phone'
            ),
            'regex' => array(
                array(
                    'phone', '/^([0-9])+$/i'
                )
            ),
            'boolean' => array(
                'is_production'
            ),
            'lengthMax' => array(
                array(
                    'phone', 10
                )
            ),
            'lengthMin' => array(
                array(
                    'phone', 6
                )
            )
        );
    }
}
