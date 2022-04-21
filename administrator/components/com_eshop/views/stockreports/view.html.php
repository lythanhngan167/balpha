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
class EshopViewStockreports extends EShopViewList
{
	public function display($tpl = null, $is_product_list = false)
    {
		$this->showReport = true;
        parent::display($tpl, false);
    }
	function _buildListArray(&$lists, $state)
	{
		$input = JFactory::getApplication()->input;
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		//Order Statuses list
		$query->select('a.id AS value, b.orderstatus_name AS text')
			->from('#__eshop_orderstatuses AS a')
			->innerJoin('#__eshop_orderstatusdetails AS b ON (a.id = b.orderstatus_id)')
			->where('a.published = 1')
			->where('b.language = "' . JComponentHelper::getParams('com_languages')->get('site', 'en-GB') . '"');
		$db->setQuery($query);
		$options = array();
		$options[] = JHtml::_('select.option', 0, JText::_('ESHOP_ORDERSSTATUS_ALL'));

		$options = array_merge($options, $db->loadObjectList());
		$options[] = JHtml::_('select.option', -3, 'Đã thanh toán');
		$options[] = JHtml::_('select.option', -1, 'Chưa thanh toán - Chờ tải ảnh CK');
		$options[] = JHtml::_('select.option', -2, 'Chưa thanh toán - Đã tải ảnh CK');

		$agents = array();
		$agents[] = JHtml::_('select.option', 0, '-- Đại lý -- ');

		$agents = array_merge($agents, $this->getListAgents());

		$lists['order_status_id'] = JHtml::_('select.genericlist', $options, 'order_status_id', ' class="inputbox" style="width: 170px;" onchange="this.form.submit();"', 'value', 'text', $input->getInt('order_status_id', 0));
		$lists['agents'] = JHtml::_('select.genericlist', $agents, 'agents', ' class="inputbox" style="width: 150px;" onchange="this.form.submit();"', 'value', 'text', $input->getInt('agents', 0));

		//Shipping methods list
		$query->clear()
		  ->select('DISTINCT(title) AS value, title AS text')
		  ->from('#__eshop_ordertotals')
		  ->where('name = "shipping"');
		$db->setQuery($query);
		$options = array();
		$options[] = JHtml::_('select.option', '', JText::_('ESHOP_SHIPPING_METHOD_ALL'));
		$options = array_merge($options, $db->loadObjectList());
		$lists['shipping_method'] = JHtml::_('select.genericlist', $options, 'shipping_method', ' class="inputbox" style="width: 200px;" onchange="this.form.submit();"', 'value', 'text', $input->getstring('shipping_method', ''));


		// Get stock
        // $query->clear()
        //     ->select('id AS value, title AS text')
        //     ->from('#__store')
        //     ->where('state = 1');
        // $db->setQuery($query);
        // $options = array();
        // $options[] = JHtml::_('select.option', '', JText::_('ESHOP_STOCK_ALL'));
        // $options = array_merge($options, $db->loadObjectList());
        // $lists['stock'] = JHtml::_('select.genericlist', $options, 'stock', ' class="inputbox" style="width: 200px;" onchange="this.form.submit();"', 'value', 'text', $input->getstring('stock', 0));


        $nullDate = $db->getNullDate();
		$this->nullDate = $nullDate;
		$currency = new EshopCurrency();
		$this->currency = $currency;
	}

	/**
	 * Override Build Toolbar function, only need Delete, Edit and Download Invoice
	 */
	function _buildToolbar()
	{
		$viewName = $this->getName();
		$controller = EShopInflector::singularize($this->getName());
		JToolBarHelper::title(JText::_($this->lang_prefix.'_'.strtoupper($viewName)));
		JToolBarHelper::deleteList(JText::_($this->lang_prefix . '_DELETE_' . strtoupper($this->getName()) . '_CONFIRM'), $controller . '.remove');
		JToolBarHelper::editList($controller.'.edit');

		// if (EshopHelper::getConfigValue('invoice_enable'))
		// {
		// 	JToolBarHelper::custom($controller.'.downloadInvoice', 'print', 'print', JText::_('ESHOP_DOWNLOAD_INVOICE'), true);
		// }

		JToolbarHelper::custom($controller.'.downloadCSV', 'download', 'download', JText::_('ESHOP_DOWNLOAD_CSV'), true);
		JToolbarHelper::custom($controller.'.downloadXML', 'download', 'download', JText::_('ESHOP_DOWNLOAD_XML'), true);
	}

		function getToday(){
	    return date('d/m/Y') . ' - ' . date('d/m/Y');
    }
    function getYesterday(){
        $yesterday = date('d/m/Y',strtotime("-1 days"));
        return $yesterday.' - '.$yesterday;
    }
    function getThisMonth(){
        return date('01/m/Y') . ' - ' . date('t/m/Y');
    }

		public function getListAgents()
		{
			$model = $this->getModel('Stockreports', 'EShopModel');
			$result = $model->getListAgents();
			return $result;
		}
}
