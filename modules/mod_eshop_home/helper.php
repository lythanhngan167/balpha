<?php
/**
 * @version		1.3.1
 * @package		Joomla
 * @subpackage	EShop
 * @author  	Giang Dinh Truong
 * @copyright	Copyright (C) 2011 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
use api\model\dao\shop\ShopHomeProductDao;

defined('_JEXEC') or die;

class modEshopHomeHelper
{



    public static function getHomeProduct()
    {
        $params['join'][] =
            array(
                'type' => 'LEFT',
                'with_table' => '#__eshop_products AS p ON p.id = hp.product_id'
            );
        $params['join'][] =
            array(
                'type' => 'LEFT',
                'with_table' => '#__eshop_productdetails AS d ON p.id = d.product_id'
            );

        $params['join'][] =
            array(
                'type' => 'LEFT',
                'with_table' => '#__eshop_productcategories AS pc ON ( pc.product_id = p.id AND pc.main_category = 1)'
            );
        $params['where'][] = 'd.language = \'vi-VN\'';
        $dao = new ShopHomeProductDao();
        return $dao->getProducts($params);

    }

}
