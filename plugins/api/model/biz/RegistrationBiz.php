<?php
/**
 * Created by VSCode.
 * User: thai
 * Date: 09/27/2020
 * Time: 1:00 PM
 */

namespace api\model\biz;

use api\model\AbtractBiz;

/**
 * @OA\Schema(required={"id", "title"}, @OA\Xml(name="ProjectBiz"))
 */
class RegistrationBiz extends AbtractBiz
{
    /**
     * @OA\Property(format="int64")
     * @var int
     */
    public $id;
    /**
     * @OA\Property(example="name")
     * @var string
     */
    public $name;
    /**
     * @OA\Property(example="phone")
     * @var string
     */
    public $phone;
     /**
     * @OA\Property(example="email")
     * @var string
     */
    public $email;
     /**
     * @OA\Property(example="address")
     * @var string
     */
    public $address;
      /**
     * @OA\Property(example="note")
     * @var string
     */
    public $note;
      /**
     * @OA\Property(example="created_date")
     * @var string
     */
    public $created_date;
      /**
     * @OA\Property(example="province")
     * @var string
     */
    public $province;
      /**
     * @OA\Property(example="status")
     * @var string
     */
    public $status;


}
