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
defined('_JEXEC') or die;
require_once dirname(__FILE__) . '/products.php';

class EShopModelCategory extends EShopModelProducts
{
	protected function _buildQueryJoins(JDatabaseQuery $query)
	{
		return parent::_buildQueryJoins($query);
	}

	protected function _buildQueryWhere(JDatabaseQuery $query)
	{
		parent::_buildQueryWhere($query);

		if (EshopHelper::getConfigValue('show_products_in_all_levels'))
		{
			$categoryIds = array_merge(array($this->state->id), EshopHelper::getAllChildCategories($this->state->id));
		}
		else
		{
			$categoryIds = array($this->state->id);
		}

		$db       = $this->getDbo();
		$subQuery = $db->getQuery(true);
		$subQuery->select('pc.product_id FROM #__eshop_productcategories AS pc WHERE pc.category_id IN (' . implode(',', $categoryIds) . ')');
		$query->where('a.id IN (' . (string) $subQuery . ')');

		return $this;
	}
}