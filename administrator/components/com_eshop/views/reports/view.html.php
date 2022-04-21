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
class EshopViewReports extends JViewLegacy
{
	
	/**
	 * 
	 * Display function
	 * 
	 */
	function display($tpl = null)
	{
		// Check access first
		$mainframe = JFactory::getApplication();
		if (!JFactory::getUser()->authorise('eshop.reports', 'com_eshop'))
		{
			$mainframe->enqueueMessage(JText::_('ESHOP_ACCESS_NOT_ALLOW'), 'error');
			$mainframe->redirect('index.php?option=com_eshop&view=dashboard');
		}
		switch ($this->getLayout())
		{
			case 'orders':
				$this->_displayOrders($tpl);
				break;
			case 'viewedproducts':
				$this->_displayViewedProducts($tpl);
				break;
			case 'purchasedproducts':
				$this->_displayPurchasedProducts($tpl);
				break;
			default:
				break;
		}
	}
	
	/**
	 * 
	 * Function to display orders report
	 * @param string $tpl
	 */
	function _displayOrders($tpl)
	{
		$input = JFactory::getApplication()->input;
		$currency = new EshopCurrency();
		$lists = array();
		$options = array();
		$options[] = JHtml::_('select.option', 'year', JText::_('ESHOP_YEARS'), 'value', 'text');
		$options[] = JHtml::_('select.option', 'month', JText::_('ESHOP_MONTHS'), 'value', 'text');
		$options[] = JHtml::_('select.option', 'week', JText::_('ESHOP_WEEKS'), 'value', 'text');
		$options[] = JHtml::_('select.option', 'day', JText::_('ESHOP_DAYS'), 'value', 'text');
		$groupBy = $input->getString('group_by', 'week');
		$lists['group_by'] = JHtml::_('select.genericlist', $options, 'group_by',
			array(
				'option.text.toHtml' => false,
				'option.value' => 'value',
				'option.text' => 'text',
				'list.select' => $groupBy,
				'list.attr' => ' class="inputbox" style="width: 100px;"'));
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('a.id AS value, b.orderstatus_name AS text')
			->from('#__eshop_orderstatuses AS a')
			->innerJoin('#__eshop_orderstatusdetails AS b ON (a.id = b.orderstatus_id)')
			->where('a.published = 1')
			->where('b.language = "' . JComponentHelper::getParams('com_languages')->get('site', 'en-GB') . '"');
		$db->setQuery($query);
		$options = array();
		$options[] = JHtml::_('select.option', 0, JText::_('ESHOP_ORDERSSTATUS_ALL'));
		$options = array_merge($options, $db->loadObjectList());
		$lists['order_status_id'] = JHtml::_('select.genericlist', $options, 'order_status_id', ' class="inputbox" style="width: 150px;" ', 'value', 'text', $input->getInt('order_status_id'));
		$orders = $this->get('OrdersData');
		$pagination = $this->get('OrdersPagination');
		$this->items = $orders;
		$this->pagination = $pagination;
		$this->currency = $currency;
		$this->lists = $lists;
		parent::display($tpl);
	}
	
	/**
	 * 
	 * Function to display viewed products report
	 * @param unknown $tpl
	 */
	function _displayViewedProducts($tpl)
	{
		$products = $this->get('ViewedProductsData');
		$totalHits = 0;
		foreach ($products as $product)
		{
			$totalHits += (int) $product->hits;
		}
		$pagination = $this->get('ViewedProductsPagination');
		$this->items = $products;
		$this->pagination = $pagination;
		$this->totalHits = $totalHits;
		parent::display($tpl);
	}
	
	/**
	 * 
	 * Function to display purchased products report
	 * @param unknown $tpl
	 */
	function _displayPurchasedProducts($tpl)
	{
		$input = JFactory::getApplication()->input;
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('a.id AS value, b.orderstatus_name AS text')
			->from('#__eshop_orderstatuses AS a')
			->innerJoin('#__eshop_orderstatusdetails AS b ON (a.id = b.orderstatus_id)')
			->where('a.published = 1')
			->where('b.language = "' . JComponentHelper::getParams('com_languages')->get('site', 'en-GB') . '"');
		$db->setQuery($query);
		$options = array();
		$options[] = JHtml::_('select.option', 0, JText::_('ESHOP_ORDERSSTATUS_ALL'));
		$options = array_merge($options, $db->loadObjectList());
		$lists = array();
		$lists['order_status_id'] = JHtml::_('select.genericlist', $options, 'order_status_id', ' class="inputbox" style="width: 150px;" ', 'value', 'text', $input->getInt('order_status_id'));
		$currency = new EshopCurrency();
		$products = $this->get('PurchasedProductsData');
		$pagination = $this->get('PurchasedProductsPagination');
		$this->items = $products;
		$this->pagination = $pagination;
		$this->currency = $currency;
		$this->lists = $lists;
		parent::display($tpl);
	}
}