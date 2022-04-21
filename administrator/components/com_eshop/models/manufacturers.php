<?php
/**
 * @version		3.1.0
 * @package		Joomla
 * @subpackage	EShop
 * @author  	Giang Dinh Truong
 * @copyright	Copyright (C) 2012 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die();

/**
 * Eshop Component Model
 *
 * @package		Joomla
 * @subpackage	EShop
 * @since 1.5
 */
class EShopModelManufacturers extends EShopModelList
{

	function __construct($config)
	{
		$config['search_fields'] = array('b.manufacturer_name', 'b.manufacturer_desc');
		$config['translatable'] = true;
		$config['translatable_fields'] = array('manufacturer_name', 'manufacturer_alias', 'manufacturer_desc');
		
		parent::__construct($config);
	}
}