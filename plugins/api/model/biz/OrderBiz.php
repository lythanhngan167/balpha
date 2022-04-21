<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/27/2019
 * Time: 11:27 AM
 */

namespace api\model\biz;

use api\model\AbtractBiz;

/**
 * @OA\Schema(required={"id", "username"}, @OA\Xml(name="OrderBiz"))
 */
class OrderBiz extends AbtractBiz
{
    /**
     * @OA\Property(format="int64")
     * @var int
     */
    public $id;
    /**
     * @OA\Property(example="category_id")
     * @var int
     */
    public $category_id;
    /**
     * @OA\Property(example="quantity")
     * @var int
     */
    public $quantity;
    /**
     * @OA\Property(example="price")
     * @var int
     */
    public $price;

    /**
     * @OA\Property(example="total")
     * @var int
     */
    public $total;

    /**
     * @OA\Property(example="project_id")
     * @var int
     */
    public $project_id;

    /**
     * @OA\Property(example="create_date")
     * @var string
     */
    public $create_date;

    /**
     * @OA\Property(example="project_name")
     * @var string
     */
    public $project_name;

    /**
     * @OA\Property(example="category_name")
     * @var string
     */
    public $category_name;

    public function setAttributes($data)
    {


        $data['create_date'] = \JFactory::getDate($data['create_date'])->format("d-m-Y H:i");
        $data['total'] = number_format($data['total'],0,",",".").' '.BIZ_XU;
        $data['price'] = number_format($data['price'],0,",",".").' '.BIZ_XU;


        parent::setAttributes($data);
    }
}
