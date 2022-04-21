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
class EshopViewVoucher extends EShopViewForm
{
	function _buildListArray(&$lists, $item)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$nullDate = $db->getNullDate();
		//Get history voucher
		$query->clear();
		$query->select('*')
			->from('#__eshop_voucherhistory')
			->where('voucher_id = ' . intval($item->id));
		$db->setQuery($query);
		$voucherHistories = $db->loadObjectList();
		$this->voucherHistories = $voucherHistories;
		$this->lists = $lists;
		$this->nullDate = $nullDate;
	}
}