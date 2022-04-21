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
 * @OA\Schema(required={"customer_id, status_id"}, @OA\Xml(name="StatusForm"))
 */
class StatusForm extends AbtractForm
{
    /**
     * @OA\Property(example="customer_id")
     * @var int
     */
    public $customer_id;

    /**
     * @OA\Property(example="status_id")
     * @var string
     */
    public $status_id;

    /**
     * @OA\Property(example="total_revenue")
     * @var int
     */
    public $total_revenue;

    public $rating_id;

    public $rating_note;

    public $trash_confirmed;


    public function rule()
    {
        $rule = array(
            'required' => array(
                'customer_id',
                'status_id'
            )
        );
        if($this->status_id == 7){
            $rule['required'][] = 'total_revenue';
        }

        return $rule;
    }

}