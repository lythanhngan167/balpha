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
class EShopViewDashboard extends JViewLegacy
{

    public function display($tpl = null)
    {
    	$db = JFactory::getDbo();
    	$query = $db->getQuery(true);
    	$currency = new EshopCurrency();
    	$defaultCurrencyCode = EshopHelper::getConfigValue('default_currency_code');
    	$query->select('*')
    		->from('#__eshop_currencies')
    		->where('currency_code = ' . $db->quote($defaultCurrencyCode));
    	$db->setQuery($query);
    	$defaultCurrency = $db->loadObject();
		$this->shopStatistics = $this->get('ShopStatistics');
		$this->recentOrders = $this->get('RecentOrders');
		$this->recentReviews = $this->get('RecentReviews');
		$this->topSales = $this->get('TopSales');
		$this->topHits = $this->get('TopHits');
		$this->currency = $currency;
		$this->defaultCurrency = $defaultCurrency;
		$nullDate = $db->getNullDate();
		$this->nullDate = $nullDate;
		$this->model = $this->getModel();
		parent::display($tpl);
	}
}