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
class EShopModelCurrency extends EShopModel
{
	function __construct($config)
	{
		parent::__construct($config);
	}
	
	function store(&$data)
	{
		parent::store($data);
		//Update currencies
		if (EshopHelper::getConfigValue('auto_update_currency'))
			EshopHelper::updateCurrencies(true);
		return true;
	}
}