<?php

/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/27/2019
 * Time: 8:01 AM
 */

namespace api\model\form\erp;

use api\model\AbtractForm;


class ERPUpdateInviteIdForm extends AbtractForm
{

    public $id_biznet;
    public $invited_id;
    public $is_production;

    public function rule()
    {
        return array(
            'required' => array(
                'id_biznet',
                'invited_id'
            ),
            'boolean'=> array (
                'is_production'
            )
        );
    }
}
