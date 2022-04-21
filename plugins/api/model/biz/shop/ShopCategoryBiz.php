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
class ShopCategoryBiz extends AbtractBiz
{
    /**
     * @OA\Property(format="int64")
     * @var int
     */
    public $id;
    /**
     * @OA\Property(format="int64")
     * @var int
     */
    public $category_parent_id;


    /**
     * @OA\Property(example="category_image")
     * @var string
     */
    public $category_image;
    /**
     * @OA\Property(example="category_image_icon")
     * @var string
     */
    public $category_image_icon;

    /**
     * @OA\Property(example="category_name")
     * @var string
     */
    public $category_name;

    /**
     * @OA\Property(example="child")
     * @var array
     */
    public $child;

    public $page = 'ProductListPage';
    /**
     * @OA\Property(example="")
     * @var string
     */
    public $thumb;

    public $img_ori;

    public function setAttributes($data)
    {
        parent::setAttributes($data);
        if ($this->category_image_icon) {
            $this->thumb = $this->getImage($this->category_image_icon);
            $this->img_ori = \JURI::base() . 'media/com_eshop/categories/' . $this->category_image_icon;
        } elseif ($this->category_image) {
            $this->thumb = $this->getImage($this->category_image);
            $this->img_ori = \JURI::base() . 'media/com_eshop/categories/' . $this->category_image;
        }

        unset($this->category_image_icon);
        unset($this->category_image);
    }

    public function getImage($image_path)
    {
        $thumbnailWidth = 200;
        $thumbnailHeight = 200;
        if ($image_path && \JFile::exists(JPATH_ROOT . '/media/com_eshop/categories/' . $image_path)) {
            $image = \EshopHelper::resizeImage($image_path, JPATH_ROOT . '/media/com_eshop/categories/', $thumbnailWidth, $thumbnailHeight);
        } else {
            $image = \EshopHelper::resizeImage('no-image.png', JPATH_ROOT . '/media/com_eshop/categories/', $thumbnailWidth, $thumbnailHeight);
        }
        return \JURI::base() . 'media/com_eshop/categories/resized/' . $image;
    }

}
