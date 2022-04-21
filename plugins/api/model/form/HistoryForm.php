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
 * @OA\Schema(required={}, @OA\Xml(name="HistoryForm"))
 */
class HistoryForm extends AbtractForm
{

    /**
     * @OA\Property(example="project_id")
     * @var int
     */
    public $project_id;
    /**
     * @OA\Property(example="month")
     * @var string
     */
    public $month;


    /**
     * @OA\Property(example="0")
     * @var int
     */

    public $offset;

    /**
     * @OA\Property(example="0")
     * @var int
     */

    public $limit;



}
