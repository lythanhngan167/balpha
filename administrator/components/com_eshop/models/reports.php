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
class EShopModelReports extends JModelLegacy
{
	
	/**
	 * Total orders
	 *
	 * @var int
	 */
	protected $_totalOrders = 0;
	
	/**
	 * Orders data
	 *
	 * @var array
	 */
	protected $_ordersData = null;
	
	/**
	 * Pagination object
	 *
	 * @var object
	 */
	protected $_ordersPagination = null;
	
	/**
	 * Total viewed products
	 *
	 * @var int
	 */
	protected $_totalViewedProducts = 0;
	
	/**
	 * Viewed products data
	 *
	 * @var array
	 */
	protected $_viewedProductsData = null;
	
	/**
	 * Pagination object
	 *
	 * @var object
	 */
	protected $_viewedProductsPagination = null;
	
	/**
	 * Total purchased products
	 *
	 * @var int
	 */
	protected $_totalPurchasedProducts = 0;
	
	/**
	 * Purchased products data
	 *
	 * @var array
	 */
	protected $_purchasedProductsData = null;
	
	/**
	 * Pagination object
	 *
	 * @var object
	 */
	protected $_purchasedProductsPagination = null;
	
	function __construct()
	{
		parent::__construct();
		$input = JFactory::getApplication()->input;
		$mainframe = JFactory::getApplication();
		// Get the pagination request variables
		$limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
		$layout = $input->get('layout');
		$limitstart = $mainframe->getUserStateFromRequest('EShop.reports.'.$layout.'.limitstart', 'limitstart', 0, 'int');
		
		// In case limit has been changed, adjust limitstart accordingly
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
		
		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
	}
	
	/**
	 * Method to get viewed products data
	 *
	 * @access public
	 * @return array
	 */
	public function getOrdersData()
	{
		if (empty($this->_ordersData))
		{
			$db = $this->getDbo();
			$query = $this->_buildOrdersQuery();
			$this->_ordersData = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
		}
		return $this->_ordersData;
	}
	
	/**
	 * Get total viewed products
	 *
	 * @return int
	 */
	public function getOrdersTotal()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_totalOrders))
		{
			$db = $this->getDbo();
			$query = $this->_buildOrdersQuery();
			$db->setQuery($query);
			$this->_totalOrders = count($db->loadObjectList());
		}
		return $this->_totalOrders;
	}
	
	/**
	 * Function to buld orders query
	 * @return object list
	 */
	public function _buildOrdersQuery()
	{
		$db = $this->getDbo();
		$input = JFactory::getApplication()->input;
		$query = $db->getQuery(true);
		$dateStart = $input->getString('date_start', date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01')));
		$dateEnd = $input->getString('date_end', date('Y-m-d'));
		$groupBy = $input->getString('group_by', 'week');
		$orderStatusId = $input->getInt('order_status_id', 0);
		$from = '';
		$from = 'SELECT o.id, ';
		$from .= '(SELECT SUM(op.quantity) FROM #__eshop_orderproducts AS op WHERE op.order_id = o.id GROUP BY op.order_id) AS products, ';
		$from .= '(SELECT SUM(ot.value) FROM #__eshop_ordertotals AS ot WHERE ot.order_id = o.id AND ot.name = "tax" GROUP BY ot.order_id) AS tax, ';
		$from .= '(SELECT SUM(ot.value) FROM #__eshop_ordertotals AS ot WHERE ot.order_id = o.id AND ot.name = "sub_total" GROUP BY ot.order_id) AS sub_total, ';
		$from .= '(SELECT SUM(ot.value) FROM #__eshop_ordertotals AS ot WHERE ot.order_id = o.id AND ot.name = "shipping" GROUP BY ot.order_id) AS shipping, ';
		$from .= 'o.total, o.created_date ';
		$from .= 'FROM #__eshop_orders AS o ';
		if ($orderStatusId)
		{
			$from .= 'WHERE o.order_status_id = ' . (int) $orderStatusId . ' ';
		}
		else
		{
			$from .= 'WHERE 1 ';
		}
		if (!empty($dateStart))
		{
			$from .= 'AND created_date >= "' . $dateStart . ' 00:00:00" ';
		}
		if (!empty($dateEnd))
		{
			$from .= 'AND created_date <= "' . $dateEnd . '  23:59:59" ';
		}
		$from .= 'GROUP BY (o.id)';
		$query->select('MIN(tmp.created_date) AS date_start')
			->select('MAX(tmp.created_date) AS date_end')
			->select('COUNT(tmp.id) AS orders')
			->select('SUM(tmp.products) AS products')
			->select('SUM(tmp.tax) AS tax')
			->select('SUM(tmp.sub_total) AS sub_total')
			->select('SUM(tmp.shipping) AS shipping')
			->select('SUM(tmp.total) AS total')
			->from('(' . $from . ') AS tmp');
		switch ($groupBy)
		{
			case 'day':
				$query->group('DAY(tmp.created_date)');
				break;
			default:
			case 'week':
				$query->group('WEEK(tmp.created_date)');
				break;
			case 'month':
				$query->group('MONTH(tmp.created_date)');
				break;
			case 'year':
				$query->group('YEAR(tmp.created_date)');
				break;
		}
		$query->order('tmp.created_date DESC');
		return $query;
	}	
	
	/**
	 * Method to get a pagination object
	 *
	 * @access public
	 * @return integer
	 */
	public function getOrdersPagination()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_ordersPagination))
		{
			jimport('joomla.html.pagination');
			$this->_ordersPagination = new JPagination($this->getOrdersTotal(), $this->getState('limitstart'), $this->getState('limit'));
		}
		return $this->_ordersPagination;
	}
	
	/**
	 * Method to get viewed products data
	 *
	 * @access public
	 * @return array
	 */
	public function getViewedProductsData()
	{
		if (empty($this->_viewedProductsData))
		{
			$db = $this->getDbo();
			$query = $db->getQuery(true);
			$query->select('a.id, a.product_sku, a.hits, b.product_name')
				->from('#__eshop_products AS a')
				->innerJoin('#__eshop_productdetails AS b ON (a.id = b.product_id)')
				->where('a.published = 1')
				->where('b.language = "' . JComponentHelper::getParams('com_languages')->get('site', 'en-GB') . '"');
			$this->_viewedProductsData = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
		}
		return $this->_viewedProductsData;
	}

	/**
	 * Get total viewed products
	 *
	 * @return int
	 */
	public function getViewedProductsTotal()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_totalViewedProducts))
		{
			$db = $this->getDbo();
			$query = $db->getQuery(true);
			$query->select('COUNT(*)')
				->from('#__eshop_products')
				->where('published = 1');
			$db->setQuery($query);
			$this->_totalViewedProducts = $db->loadResult();
		}
		return $this->_totalViewedProducts;
	}

	/**
	 * Method to get a pagination object
	 *
	 * @access public
	 * @return integer
	 */
	public function getViewedProductsPagination()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_viewedProductsPagination))
		{
			jimport('joomla.html.pagination');
			$this->_viewedProductsPagination = new JPagination($this->getViewedProductsTotal(), $this->getState('limitstart'), $this->getState('limit'));
		}
		return $this->_viewedProductsPagination;
	}
	
	/**
	 * Method to get purchased products data
	 *
	 * @access public
	 * @return array
	 */
	public function getPurchasedProductsData()
	{
		if (empty($this->_purchasedProductsData))
		{
			$query = $this->_getPurchasedProductsQuery();
			$this->_purchasedProductsData = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
		}
		return $this->_purchasedProductsData;
	}
	
	/**
	 * Get total purchased products
	 *
	 * @return int
	 */
	public function getPurchasedProductsTotal()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_totalPurchasedProducts))
		{
			$db = $this->getDbo();
			$query = $this->_getPurchasedProductsQuery();
			$db->setQuery($query);
			$this->_totalPurchasedProducts = count($db->loadObjectList());
		}
		return $this->_totalPurchasedProducts;
	}
	
	private function _getPurchasedProductsQuery()
	{
		$input = JFactory::getApplication()->input;
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('a.id, a.product_sku, a.product_name, SUM(a.quantity) AS quantity, SUM(a.total_price) AS total_price, b.product_cost')
			->from('#__eshop_orderproducts AS a')
			->innerJoin('#__eshop_products AS b ON (a.product_id = b.id)')
			->innerJoin('#__eshop_orders AS c ON (a.order_id = c.id)');
		$dateStart = $input->getString('date_start', date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01')));
		$dateEnd = $input->getString('date_end', date('Y-m-d'));
		$orderStatusId = $input->getInt('order_status_id', 0);
		
		if (!empty($dateStart))
		{
			$query->where('c.created_date >= "' . $dateStart . ' 00:00:00"');
		}
		
		if (!empty($dateEnd))
		{
			$query->where('c.created_date <= "' . $dateEnd . '  23:59:59"');
		}
		
		if ($orderStatusId > 0)
		{
			$query->where('c.order_status_id = ' . (int) $orderStatusId);
		}
		
		$query->group('a.product_id');
		$query->order('total_price DESC');
		
		return $query;
	}
	
	/**
	 * Method to get a pagination object
	 *
	 * @access public
	 * @return integer
	 */
	public function getPurchasedProductsPagination()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_purchasedProductsPagination))
		{
			jimport('joomla.html.pagination');
			$this->_purchasedProductsPagination = new JPagination($this->getPurchasedProductsTotal(), $this->getState('limitstart'), $this->getState('limit'));
		}
		return $this->_purchasedProductsPagination;
	}
}