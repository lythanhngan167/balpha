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
 * @OA\Schema(required={"id", "title"}, @OA\Xml(name="ProjectBiz"))
 */
class ShopProductBiz extends AbtractBiz
{
    /**
     * @OA\Property(format="int64")
     * @var int
     */
    public $id;
    /**
     * @OA\Property(format="name")
     * @var string
     */
    public $name;
    /**
     * @OA\Property(example="product_sku")
     * @var string
     */
    public $product_sku;
    /**
     * @OA\Property(example="product_image")
     * @var string
     */
    public $product_image;

    /**
     * @OA\Property(example="price")
     * @var string
     */
    public $price;

    /**
     * @OA\Property(example="base_price")
     * @var string
     */
    public $base_price;

    /**
     * @OA\Property(example="ori_price")
     * @var string
     */
    public $ori_price;

    /**
     * @OA\Property(example="category_id")
     * @var string
     */
    public $category_id;

    public $discount_amount = 0;

    public $p_group = '';
    public $in_stock = '';
    public $unit = '';
    public $total_weigth = 0;
    public $check_quantity = true;
    public $weight = 0;

    public function setAttributes($data)
    {
        parent::setAttributes($data);
        if ($this->product_image) {
            $this->product_image = $this->getImage($this->product_image);
        }
        $this->discount_amount = $this->ori_price - $this->price;
        if ($this->discount_amount) {
            $discountPercent = ($this->discount_amount * 100 / $this->ori_price);
            if($discountPercent == (int)$discountPercent){
                $this->discount_amount = '-'.$discountPercent . '%';
            }else{
                $this->discount_amount = number_format((0 - $this->discount_amount)/1000) . ' k';
            }

        } else {
            $this->discount_amount = '';
        }

        if ($this->p_group) {
            $this->p_group = 'group_' . $this->p_group;
        }
    }

    public function getImage($image_path)
    {
        $thumbnailWidth = 300;
        $thumbnailHeight = 300;
        if ($image_path && \JFile::exists(JPATH_ROOT . '/media/com_eshop/products/' . $image_path)) {
            $image = \EshopHelper::resizeImage($image_path, JPATH_ROOT . '/media/com_eshop/products/', $thumbnailWidth, $thumbnailHeight);
        } else {
            $image = \EshopHelper::resizeImage(JPATH_ROOT . '/media/com_eshop/products/no_image.jpg', JPATH_ROOT . '/media/com_eshop/products/', $thumbnailWidth, $thumbnailHeight);
        }
        if($image){
            return \JURI::base() . 'media/com_eshop/products/resized/' . $image;
        }
        return \JURI::base() . 'media/com_eshop/products/no_image.jpg';

    }
}
