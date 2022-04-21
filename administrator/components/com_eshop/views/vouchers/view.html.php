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
 * HTML View class for EShop component
 *
 * @static
 * @package		Joomla
 * @subpackage	EShop
 * @since 1.5
 */
class EshopViewVouchers extends EShopViewList
{
	function _buildListArray(&$lists, $state)
	{
		$db = JFactory::getDbo();
		$nullDate = $db->getNullDate();
		$this->nullDate = $nullDate;
		$this->currency = new EshopCurrency();
	}
}