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
class EShopModelOptions extends EShopModelList {
	/**
	 * 
	 * Constructor
	 * @param array $config
	 */
	function __construct($config) {
		$config['search_fields'] =  array('b.option_name');
		$config['translatable'] = true;
		$config['translatable_fields'] = array('option_name');
		parent::__construct($config);
	}
}