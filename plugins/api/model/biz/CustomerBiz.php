<?php

/**
 * Created by PhpStorm.
 * User: lvson
 * Date: 4/26/2019
 * Time: 10:50 AM
 */

namespace api\model\biz;

use api\model\AbtractBiz;

/**
 * @OA\Schema(required={"id", "title"}, @OA\Xml(name="ProjectBiz"))
 */
class CustomerBiz extends AbtractBiz
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
     * @OA\Property(example="place")
     * @var string
     */
    public $place;

    /**
     * @OA\Property(example="email")
     * @var string
     */
    public $email;

    /**
     * @OA\Property(example="sale_id")
     * @var int
     */
    public $sale_id;

    /**
     * @OA\Property(example="category_id")
     * @var int
     */
    public $category_id;

    /**
     * @OA\Property(example="category_name")
     * @var string
     */
    public $category_name;

    /**
     * @OA\Property(example="project_id")
     * @var string
     */
    public $project_id;

    /**
     * @OA\Property(example="project_name")
     * @var string
     */
    public $project_name;

    /**
     * @OA\Property(example="status_id")
     * @var int
     */
    public $status_id;


    /**
     * @OA\Property(example="status_name")
     * @var int
     */
    public $status_name;

    /**
     * @OA\Property(example="total_revenue")
     * @var int
     */
    public $total_revenue;

    /**
     * @OA\Property(example="create_date")
     * @var string
     */
    public $create_date;

    /**
     * @OA\Property(example="buy_date")
     * @var string
     */
    public $buy_date;

    /**
     * @OA\Property(example="modified_date")
     * @var string
     */
    public $modified_date;


    public $note;

    public $country_name;

    public $rating_id;

    public $rating_name;

    public $rating_note;

    public $trash_approve;

    public $trash_approve_name;

    public $trash_approve_color;

    public $trash_confirmed_by_dm;

    public function setAttributes($data)
    {
        switch ($data['status_id']) {
            case 1:
                $data['status_name'] =  'Mới';
                break;
            case 2:
                $data['status_name'] =  'Lưỡng lự';
                break;
            case 3:
                $data['status_name'] =  'Quan tâm';
                break;
            case 4:
                $data['status_name'] =  'Rất Quan tâm';
                break;
            case 5:
                $data['status_name'] =  'Tiềm năng';
                break;
            case 6:
                $data['status_name'] =  'Trả lại';
                break;
            case 7:
                $data['status_name'] =  'Hoàn thành';
                break;
            case 99:
                $data['status_name'] =  'Sọt rác';
                switch ($data['rating_id']) {
                    case 1:
                        $data['rating_name'] = 'Sai thông tin';
                        break;
                    case 2:
                        $data['rating_name'] = 'Không nhu cầu';
                        break;
                    case 3:
                        $data['rating_name'] = 'Khác';
                        break;
                }
                switch ($data['trash_approve']) {
                    case 0:
                        $data['trash_approve_name'] = 'Chờ duyệt';
                        $data['trash_approve_color'] = 'biz_main_title';
                        break;
                    case 1:
                        $data['trash_approve_name'] = 'Đã duyệt';
                        $data['trash_approve_color'] = 'secondary';
                        break;
                    case 2:
                        $data['trash_approve_name'] = 'Từ chối';
                        $data['trash_approve_color'] = 'danger';
                        break;
                }
                break;
            case 8:
                $data['status_name'] =  'Hủy';
                break;
        }

        $data['buy_date'] = \JFactory::getDate($data['buy_date'])->format("d-m-Y H:i");
        $data['modified_date'] = \JFactory::getDate($data['modified_date'])->format("d-m-Y H:i");
        $data['total_revenue'] = number_format($data['total_revenue'], 0, ",", ".") . ' ' . BIZ_XU;
        if ($data['note']) {
            $data['note']['create_date'] = \JFactory::getDate($data['note']['create_date'])->format("d-m-Y H:i");
        }
        parent::setAttributes($data);
    }
}
