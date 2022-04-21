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
 * EShop controller
 *
 * @package		Joomla
 * @subpackage	EShop
 * @since 1.5
 */
class EShopControllerReports extends JControllerLegacy
{

	/**
	 * Constructor function
	 *
	 * @param array $config
	 */
	function __construct($config = array())
	{
		parent::__construct($config);
	
	}
	
	function cancel()
	{
		$this->setRedirect('index.php?option=com_eshop&view=dashboard');
	}
}