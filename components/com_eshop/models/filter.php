<?php
/**
 * @version        3.1.0
 * @package        Joomla
 * @subpackage     EShop
 * @author         Giang Dinh Truong
 * @copyright      Copyright (C) 2012 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die();
require_once dirname(__FILE__) . '/products.php';

class EShopModelFilter extends EShopModelProducts
{
	protected function _buildQueryWhere(JDatabaseQuery $query)
	{
		parent::_buildQueryWhere($query);

		$filterData = EshopFilter::getFilterData();

		if (!empty($filterData['product_ids']))
		{
			$query->where('a.id IN (' . implode(',', $filterData['product_ids']) . ')');
		}

		//Check viewable of customer groups
		$user = JFactory::getUser();

		if ($user->get('id'))
		{
			$customer        = new EshopCustomer();
			$customerGroupId = $customer->getCustomerGroupId();
		}
		else
		{
			$customerGroupId = EshopHelper::getConfigValue('customergroup_id');
		}

		if (!$customerGroupId)
		{
			$customerGroupId = 0;
		}

		$query->where('((a.product_customergroups = "") OR (a.product_customergroups IS NULL) OR (a.product_customergroups = "' . $customerGroupId . '") OR (a.product_customergroups LIKE "' . $customerGroupId . ',%") OR (a.product_customergroups LIKE "%,' . $customerGroupId . ',%") OR (a.product_customergroups LIKE "%,' . $customerGroupId . '"))');
		
		$currentDate = $this->getDbo()->quote(EshopHelper::getServerTimeFromGMTTime());
		$query->where('(a.product_available_date = "0000-00-00 00:00:00" OR a.product_available_date <= ' . $currentDate . ')');
		
		$langCode = JFactory::getLanguage()->getTag();
		$query->where('((a.product_languages = "") OR (a.product_languages IS NULL) OR (a.product_languages = "' . $langCode . '") OR (a.product_languages LIKE "' . $langCode . ',%") OR (a.product_languages LIKE "%,' . $langCode . ',%") OR (a.product_languages LIKE "%,' . $langCode . '"))');

		return $this;
	}
}