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
class EShopModelCustomergroups extends EShopModelList
{
	function __construct($config)
	{
		$config['state_vars'] = array('filter_order' => array('b.customergroup_name', 'string', 1));
		$config['translatable'] = true;
		$config['search_fields'] = array('b.customergroup_name');
		$config['translatable_fields'] = array('customergroup_name');
		parent::__construct($config);
	}
}