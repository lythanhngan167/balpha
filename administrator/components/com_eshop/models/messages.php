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
class EShopModelMessages extends EShopModelList
{
	function __construct($config)
	{
		$config['translatable'] = true;
		$config['translatable_fields'] = array('message_value');
		$config['state_vars'] = array(
			'search' => array('', 'string', 1), 
			'filter_order' => array('a.message_title', 'cmd', 1), 
			'filter_order_Dir' => array('', 'cmd', 1), 
			'filter_state' => array('', 'cmd', 1));
		parent::__construct($config);
	}
}