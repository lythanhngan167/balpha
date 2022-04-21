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
class EshopViewProductreports extends EShopViewList
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
		/*JToolBarHelper::deleteList(JText::_($this->lang_prefix . '_DELETE_' . strtoupper($this->getName()) . '_CONFIRM'), $controller . '.remove');
		JToolBarHelper::editList($controller.'.edit');*/
		
		/*if (EshopHelper::getConfigValue('invoice_enable'))
		{
			JToolBarHelper::custom($controller.'.downloadInvoice', 'print', 'print', JText::_('ESHOP_DOWNLOAD_INVOICE'), true);
		}*/
		
		//JToolbarHelper::custom($controller.'.downloadCSV', 'download', 'download', JText::_('ESHOP_DOWNLOAD_CSV'), true);
		JToolbarHelper::custom($controller.'.excel', 'download', 'download', JText::_('Excel'), false);
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
}
