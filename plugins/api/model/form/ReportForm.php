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
 * @OA\Schema(required={}, @OA\Xml(name="ReportForm"))
 */
class ReportForm extends AbtractForm
{

    /**
     * @OA\Property(example="project_id")
     * @var int
     */
    public $project_id;

    /**
     * @OA\Property(example="customer")
     * @var int
     */
    public $type;


    /**
     * @OA\Property(example="start_date")
     * @var string
     */
    public $start_date;

    /**
     * @OA\Property(example="end_date")
     * @var string
     */
    public $end_date;


}
