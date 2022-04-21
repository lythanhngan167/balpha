<?php

namespace api\model\form;

use api\model\AbtractForm;

/**
 * @OA\Schema(required={"username", "password", "name", "email"}, @OA\Xml(name="RegisterForm"))
 */
class UserSeenNotificationForm extends AbtractForm
{
    public $id;

    public function rule()
    {
        return array(
            'required' => array(
                'id'
            )
        );
    }
}