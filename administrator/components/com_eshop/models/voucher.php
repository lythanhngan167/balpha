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
 * EShop Component Voucher Model
 *
 * @package		Joomla
 * @subpackage	EShop
 * @since 1.5
 */
class EshopModelVoucher extends EShopModel
{
	
	function store(&$data)
	{
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		// Check duplicated voucher
		$query->select('COUNT(*)')
			->from('#__eshop_vouchers')
			->where('voucher_code = ' . $db->quote($data['voucher_code']));
		if ($data['id'])
		{
			$query->where('id != ' . intval($data['id']));
		}
		$db->setQuery($query);
		if ($db->loadResult())
		{
			$mainframe = JFactory::getApplication();
			$mainframe->enqueueMessage(JText::_('ESHOP_VOUCHER_EXISTED'), 'error');
			$mainframe->redirect('index.php?option=com_eshop&task=voucher.edit&cid[]=' . $data['id']);
		}
		parent::store($data);
		return true;
	}
	
	/**
	 * Method to remove vouchers
	 *
	 * @access	public
	 * @return boolean True on success
	 * @since	1.5
	 */
	public function delete($cid = array())
	{
		//Remove voucher history
		if (count($cid))
		{
			$db = $this->getDbo();
			$query = $db->getQuery(true);
			$query->delete('#__eshop_voucherhistory')
				->where('voucher_id IN (' . implode(',', $cid) . ')');
			$db->setQuery($query);
			$db->execute();
		}
		parent::delete($cid);
	}
}