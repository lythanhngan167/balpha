<?php
/**
 * Created by PhpStorm.
 * User: lvson
 * Date: 4/26/2019
 * Time: 10:53 AM
 */

namespace api\model\dao\shop;

use api\model\AbtractDao;
use api\model\biz\shop\ShopCountryBiz;
use api\model\biz\ProjectBiz;
require_once(JPATH_SITE . '/components/com_eshop/helpers/image.php');
require_once(JPATH_SITE . '/components/com_eshop/helpers/customer.php');
require_once(JPATH_SITE . '/components/com_eshop/helpers/helper.php');
class ShopProductImageDao extends AbtractDao
{
    public $select = array(
        'image'
    );

    public function getTable()
    {
        return '#__eshop_productimages';
    }

    public function getImages($id)
    {
        $paramsDefault = array(
            'no_quote' => true,
            'select' => implode(',',$this->select),
            'where' => array(
                'published = 1',
                'product_id = '.(int)$id
            ),
            'order' => 'ordering ASC'
        );

        $result = $this->getList($paramsDefault);
        $list = array();
        if($result){
            foreach($result as $item){
                $ori = \JURI::base() . 'media/com_eshop/products/' . $item['image'];
                $list[] = array( 
                    'thumb' => $this->getImage($item['image']),
                    'ori' => $ori
                );
            }
        }
        return $list;
    }

    public function getImage($image_path)
    {
        $thumbnailWidth = 500;
        $thumbnailHeight = 500;
        if ($image_path && \JFile::exists(JPATH_ROOT . '/media/com_eshop/products/' . $image_path)) {
            $image = \EshopHelper::resizeImage($image_path, JPATH_ROOT . '/media/com_eshop/products/', $thumbnailWidth, $thumbnailHeight);
        } else {
            $image = \EshopHelper::resizeImage('no-image.png', JPATH_ROOT . '/media/com_eshop/products/', $thumbnailWidth, $thumbnailHeight);
        }
        return \JURI::base() . 'media/com_eshop/products/resized/' . $image;
    }


}
