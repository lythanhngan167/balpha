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
 * @OA\Schema(@OA\Xml(name="BuyQueryForm"))
 */
class BuyQueryForm extends AbtractForm
{
    /**
     * @OA\Property(example="0")
     * @var int
     */
    public $project_id;

    /**
     * @OA\Property(example="0")
     * @var int
     */
    public $cat_id;


    /**
     * @OA\Property(example="0")
     * @var int
     */
    public $quantity;

    public function rule()
    {
        return array(
            'required' => array(
                'project_id',
                'cat_id',
                'quantity'
            )
        );
    }

}
