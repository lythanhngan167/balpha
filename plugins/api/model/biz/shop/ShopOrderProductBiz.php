<?php
/**
 * Created by PhpStorm.
 * User: lvson
 * Date: 4/26/2019
 * Time: 10:50 AM
 */

namespace api\model\biz\shop;

use api\model\AbtractBiz;
require_once(JPATH_SITE . '/components/com_eshop/helpers/image.php');
require_once(JPATH_SITE . '/components/com_eshop/helpers/helper.php');
/**
 * @OA\Schema(required={"id", "title"}, @OA\Xml(name="ShopOrderProductBiz"))
 */
class ShopOrderProductBiz extends AbtractBiz
{
    /**
     * @OA\Property(format="id")
     * @var int
     */
    public $id; 

    /**
     * @OA\Property(format="product_id")
     * @var int
     */
    public $product_id;
    /**
     * @OA\Property(format="product_name")
     * @var string
     */
    public $product_name;
    /**
     * @OA\Property(format="product_sku")
     * @var string
     */
    public $product_sku;
    /**
     * @OA\Property(format="quantity")
     * @var string
     */
    public $quantity;
    /**
     * @OA\Property(format="price")
     * @var string
     */
    public $price;   
    /**
     * @OA\Property(format="total_price")
     * @var string
     */
    public $total_price;   
    /**
     * @OA\Property(format="tax")
     * @var string
     */
    public $tax;

    /**
     * @OA\Property(format="orderstatus_name")
     * @var string
     */
    public $orderstatus_name;

    /**
     * @OA\Property(format="status_id")
     * @var string
     */
    public $status_id;

    /**
     * @OA\Property(format="note")
     * @var string
     */
    public $note;




    /**
     * @OA\Property(format="options")
     * @var string
     */
    public $options;

    /**
     * @OA\Property(format="product_image")
     * @var string
     */
    public $product_image;

    public function setAttributes($data)
    {
        parent::setAttributes($data);
        if ($this->product_image) {
            $this->product_image = $this->getImage($this->product_image);
        }
    }

    public function getImage($image_path)
    {
        $thumbnailWidth = 300;
        $thumbnailHeight = 300;
        if ($image_path && \JFile::exists(JPATH_ROOT . '/media/com_eshop/products/' . $image_path)) {
            $image = \EshopHelper::resizeImage($image_path, JPATH_ROOT . '/media/com_eshop/products/', $thumbnailWidth, $thumbnailHeight);
        } else {
            $image = \EshopHelper::resizeImage('no-image.png', JPATH_ROOT . '/media/com_eshop/products/', $thumbnailWidth, $thumbnailHeight);
        }
        return \JURI::base() . 'media/com_eshop/products/resized/' . $image;
    }
}
