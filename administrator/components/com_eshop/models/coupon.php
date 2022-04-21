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
 * EShop Component Coupon Model
 *
 * @package		Joomla
 * @subpackage	EShop
 * @since 1.5
 */
class EshopModelCoupon extends EShopModel
{

	function store(&$data)
	{
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		if ($data['id'])
		{
			//Delete coupon products
			$query->delete('#__eshop_couponproducts')
				->where('coupon_id = ' . intval($data['id']));
			$db->setQuery($query);
			$db->execute();
			//Delete coupon categories
			$query->clear();
			$query->delete('#__eshop_couponcategories')
				->where('coupon_id = ' . intval($data['id']));
			$db->setQuery($query);
			$db->execute();
			//Delete coupon customer groups
			$query->clear();
			$query->delete('#__eshop_couponcustomergroups')
				->where('coupon_id = ' . intval($data['id']));
			$db->setQuery($query);
			$db->execute();
		}
		// Check duplicated coupon
		$query->clear();
		$query->select('COUNT(*)')
			->from('#__eshop_coupons')
			->where('coupon_code = ' . $db->quote($data['coupon_code']));
		if ($data['id'])
		{
			$query->where('id != ' . intval($data['id']));
		}
		$db->setQuery($query);
		if ($db->loadResult())
		{
			$mainframe = JFactory::getApplication();
			$mainframe->enqueueMessage(JText::_('ESHOP_COUPON_EXISTED'), 'error');
			$mainframe->redirect('index.php?option=com_eshop&task=coupon.edit&cid[]=' . $data['id']);
		}
		parent::store($data);
		$couponId = $data['id'];
		//save new data
		if (isset($data['product_id']))
		{
			$productIds = $data['product_id'];
			if (count($productIds))
			{
				$query->clear();
				$query->insert('#__eshop_couponproducts')
					->columns('coupon_id, product_id');
				for ($i = 0; $i < count($productIds); $i++)
				{
					$productId = $productIds[$i];
					$query->values("$couponId, $productId");
				}
				$db->setQuery($query);
				$db->execute();
			}
		}
		if (isset($data['category_id']))
		{
			$categoryIds = $data['category_id'];
			if (count($categoryIds))
			{
				$query->clear();
				$query->insert('#__eshop_couponcategories')
					->columns('coupon_id, category_id');
				for ($i = 0; $i < count($categoryIds); $i++)
				{
					$categoryId = $categoryIds[$i];
					$query->values("$couponId, $categoryId");
				}
				$db->setQuery($query);
				$db->execute();
			}
		}
		if (isset($data['customergroup_id']))
		{
			$customergroupIds = $data['customergroup_id'];
			if (count($customergroupIds))
			{
				$query->clear();
				$query->insert('#__eshop_couponcustomergroups')
					->columns('coupon_id, customergroup_id');
				for ($i = 0; $i < count($customergroupIds); $i++)
				{
					$customergroupId = $customergroupIds[$i];
					$query->values("$couponId, $customergroupId");
				}
				$db->setQuery($query);
				$db->execute();
			}
		}
		return true;
	}
	
	/**
	 * Method to remove coupons
	 *
	 * @access	public
	 * @return boolean True on success
	 * @since	1.5
	 */
	public function delete($cid = array())
	{
		//Remove coupon products and history
		if (count($cid))
		{
			$db = $this->getDbo();
			$query = $db->getQuery(true);
			$query->delete('#__eshop_couponproducts')
				->where('coupon_id IN (' . implode(',', $cid) . ')');
			$db->setQuery($query);
			$db->execute();
			$query->clear();
			$query->delete('#__eshop_couponcategories')
				->where('coupon_id IN (' . implode(',', $cid) . ')');
			$db->setQuery($query);
			$db->execute();
			$query->clear();
			$query->delete('#__eshop_couponcustomergroups')
				->where('coupon_id IN (' . implode(',', $cid) . ')');
			$db->setQuery($query);
			$db->execute();
			$query->clear();
			$query->delete('#__eshop_couponhistory')
				->where('coupon_id IN (' . implode(',', $cid) . ')');
			$db->setQuery($query);
			$db->execute();
		}
		parent::delete($cid);
	}

}