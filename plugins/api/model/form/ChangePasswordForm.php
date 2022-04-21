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
 * @OA\Schema(required={"old_password", "new_password"}, @OA\Xml(name="ChangePasswordForm"))
 */
class ChangePasswordForm extends AbtractForm
{
    /**
     * @OA\Property(example="old_password")
     * @var string
     */
    public $old_password;

    /**
     * @OA\Property(example="new_password")
     * @var string
     */
    public $new_password;

    public function rule()
    {
        return array(
            'required' => array(
                'old_password',
                'new_password'
            )
        );
    }

}