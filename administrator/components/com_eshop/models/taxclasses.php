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
class EShopModelTaxclasses extends EShopModelList
{

	function __construct($config)
	{
		$config['search_fields'] = array('a.taxclass_name');
		$config['state_vars'] = array('filter_order' => array('a.taxclass_name', 'string', 1));
		
		parent::__construct($config);
	}
}