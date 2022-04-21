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

class EShopModelManufacturer extends EShopModelProducts
{
	protected function _buildQueryJoins(JDatabaseQuery $query)
	{
		$query->innerJoin('#__eshop_manufacturers AS pc ON (a.manufacturer_id = pc.id)');

		return parent::_buildQueryJoins($query);
	}

	protected function _buildQueryWhere(JDatabaseQuery $query)
	{
		$query->where('pc.id = ' . $this->state->id);

		return parent::_buildQueryWhere($query);
	}
}