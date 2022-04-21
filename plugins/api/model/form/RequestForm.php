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
 * @OA\Schema(@OA\Xml(name="RequestForm"))
 */
class RequestForm extends AbtractForm
{
    /**
     * @OA\Property(example="0")
     * @var int
     */
    public $id;

    /**
     * @OA\Property(example="0")
     * @var int
     */
    public $status_id;

    /**
     * @OA\Property(example="query")
     * @var string
     */

    public $q;


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
