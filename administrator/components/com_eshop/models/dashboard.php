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
 * EShop Component Configuration Model
 *
 * @package		Joomla
 * @subpackage	EShop
 * @since 1.5
 */
class EShopModelDashboard extends JModelLegacy
{

    public function __construct()
	{
		parent::__construct();
	}

	/**
	 * 
	 * Function to get shop statistics
	 * @return array
	 */
    public function getShopStatistics()
	{
        $data = array();
        $db = $this->getDbo();
        $query = $db->getQuery(true);
        
        //Products
        $query->clear();
        $query->select('COUNT(*)')
            ->from('#__eshop_products')
            ->where('published = 1');
        $db->setQuery($query);
        $data['products'] = $db->loadResult();

        //Categories
        $query->clear();
        $query->select('COUNT(*)')
            ->from('#__eshop_categories')
            ->where('published = 1');
        $db->setQuery($query);
        $data['categories'] = $db->loadResult();

        //Manufacturers
        $query->clear();
        $query->select('COUNT(*)')
            ->from('#__eshop_manufacturers')
            ->where('published = 1');
        $db->setQuery($query);
        $data['manufacturers'] = $db->loadResult();

        //Customers
        $query->clear();
        $query->select('COUNT(*)')
            ->from('#__eshop_customers AS a')
			->innerJoin('#__users AS b ON (a.customer_id = b.id)')
            ->where('a.published = 1');
        $db->setQuery($query);
        $data['customers'] = $db->loadResult();

        //Reviews
        $query->clear();
        $query->select('COUNT(*)')
            ->from('#__eshop_reviews')
            ->where('published = 1');
        $db->setQuery($query);
        $data['reviews'] = $db->loadResult();

        //Pending orders
        $query->clear();
        $query->select('COUNT(*)')
            ->from('#__eshop_orders')
            ->where('order_status_id = 8');
        $db->setQuery($query);
        $data['pending_orders'] = $db->loadResult();

        //Processed orders
        $query->clear();
        $query->select('COUNT(*)')
            ->from('#__eshop_orders')
            ->where('order_status_id = 9');
        $db->setQuery($query);
        $data['processed_orders'] = $db->loadResult();

        //Complete orders
        $query->clear();
        $query->select('COUNT(*)')
            ->from('#__eshop_orders')
            ->where('order_status_id = 4');
        $db->setQuery($query);
        $data['complete_orders'] = $db->loadResult();

        //Shipped orders
        $query->clear();
        $query->select('COUNT(*)')
            ->from('#__eshop_orders')
            ->where('order_status_id = 13');
        $db->setQuery($query);
        $data['shipped_orders'] = $db->loadResult();

        //Refunded orders
        $query->clear();
        $query->select('COUNT(*)')
            ->from('#__eshop_orders')
            ->where('order_status_id = 11');
        $db->setQuery($query);
        $data['refunded_orders'] = $db->loadResult();
        
		return $data;
	}

	/**
	 * Function to get recent orders
	 * @return orders object list
	 */
    public function getRecentOrders()
	{
        $db = $this->getDbo();
        $query = $db->getQuery(true);
        $query->select('a.id, a.firstname, a.lastname, a.total, a.created_date, b.orderstatus_name, a.currency_code, a.currency_exchanged_value')
            ->from('#__eshop_orders AS a')
            ->innerJoin('#__eshop_orderstatusdetails AS b ON (a.order_status_id = b.orderstatus_id AND b.language = "'.JComponentHelper::getParams('com_languages')->get('site', 'en-GB').'")')
            ->order('a.created_date DESC LIMIT 5');
        $db->setQuery($query);
        $data = $db->loadObjectList();
		return $data;
	}
	
	/**
	 * 
	 * Function to get recent reviews
	 * @return reviews object list
	 */
	public function getRecentReviews()
	{
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('*')
			->from('#__eshop_reviews')
			->order('created_date DESC LIMIT 5');
		$db->setQuery($query);
		$data = $db->loadObjectList();
		return $data;
	}

    public function getMonthlyReport($current_month_offset, $before, $after)
	{
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*')
            ->from('#__eshop_orders')
            ->where('(order_status_id = '.EshopHelper::getConfigValue('complete_status_id', 4) . ' OR order_status_id = 13)')
            ->where('created_date <= "'.$before.'"')
            ->where('created_date >= "'.$after.'"')
            ->order('created_date DESC');
        $db->setQuery($query);

        $data = $db->loadObjectList();

		return $data;
	}
	
	/**
	 *
	 * Function to get top sales products
	 * @return products opject list
	 */
	function getTopSales()
	{
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('a.id, b.product_name, SUM(quantity) AS sales')
			->from('#__eshop_orderproducts AS a')
			->innerJoin('#__eshop_productdetails AS b ON (a.product_id = b.product_id)')
			->innerJoin('#__eshop_orders AS c ON (a.order_id = c.id)')
			->where('b.language = "' . JComponentHelper::getParams('com_languages')->get('site', 'en-GB') . '"')
			->where('(c.order_status_id = ' . (int) EshopHelper::getConfigValue('complete_status_id', 4) . ' OR c.order_status_id = 13)')
			->group('a.product_id')
			->order('sales DESC LIMIT 0, 5');
		$db->setQuery($query);
		return $db->loadObjectList();
	}
	
	/**
	 *
	 * Function to get top hits products
	 * @return products opject list
	 */
	function getTopHits()
	{
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('a.id, a.hits, b.product_name')
			->from('#__eshop_products AS a')
			->innerJoin('#__eshop_productdetails AS b ON (a.id = b.product_id)')
			->where('a.published = 1')
			->where('b.language = "' . JComponentHelper::getParams('com_languages')->get('site', 'en-GB') . '"')
			->order('a.hits DESC LIMIT 0, 5');
		$db->setQuery($query);
		return $db->loadObjectList();
	}
}