<?php
/**
 * @version        3.1.0
 * @package        Joomla
 * @subpackage    EShop
 * @author    Giang Dinh Truong
 * @copyright    Copyright (C) 2012 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die();

/**
 * EShop controller
 *
 * @package        Joomla
 * @subpackage    EShop
 * @since 1.5
 */
class EShopControllerImport extends JControllerLegacy
{

    /**
     * Constructor function
     *
     * @param array $config
     */
    public function __construct($config = array())
    {
        parent::__construct($config);
    }

    public function products()
    {
        $filename = $_FILES["excel_file"]["tmp_name"];
        if ($_FILES["excel_file"]["size"] > 0) {
            IF ($_FILES["excel_file"]["type"] == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') {
                include(JPATH_ADMINISTRATOR . '/components/com_eshop/helper/BizHelper.php');
                BizHelper::import($filename);
            }
        }
    }

}
