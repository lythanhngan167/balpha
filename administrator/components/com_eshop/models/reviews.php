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
class EShopModelReviews extends EShopModelList
{

	function __construct($config)
	{
		$config['search_fields'] = array('a.review');
		parent::__construct($config);
	}

	public function _buildQuery()
	{
		$db = $this->getDbo();
		$state = $this->getState();
		$query = $db->getQuery(true);
		$query->select('a.*, b.product_name')
			->from($this->mainTable . ' AS a ')
			->innerJoin('#__eshop_productdetails AS b ON (a.product_id = b.product_id)')
			->where('b.language = "' . JComponentHelper::getParams('com_languages')->get('site', 'en-GB') . '"');
		$where = $this->_buildContentWhereArray();
		if (count($where))
			$query->where($where);
		return $query;
	}
}