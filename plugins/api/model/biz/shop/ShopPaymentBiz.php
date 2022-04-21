<?php
/**
 * Created by PhpStorm.
 * User: lvson
 * Date: 4/26/2019
 * Time: 10:50 AM
 */

namespace api\model\biz\shop;

use api\model\AbtractBiz;
use Joomla\Registry\Registry;
/**
 * @OA\Schema(required={"id", "title"}, @OA\Xml(name="ShopPaymentBiz"))
 */
class ShopPaymentBiz extends AbtractBiz
{
    /**
     * @OA\Property(format="int64")
     * @var int
     */
    public $id;
    /**
     * @OA\Property(format="firstname")
     * @var string
     */
    public $name;
    /**
     * @OA\Property(format="title")
     * @var string
     */
    public $title;

    /**
     * @OA\Property(format="params")
     * @var string
     */
    public $params;

    public function setAttributes($data)
    {
        if($data['params']){
            $params = new Registry($data['params']);
            $data['params'] = nl2br($params->get('payment_info'));
        }
        parent::setAttributes($data);


    }


}
