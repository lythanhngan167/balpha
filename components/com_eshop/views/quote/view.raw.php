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

/**
 * HTML View class for EShop component
 *
 * @static
 *
 * @package    Joomla
 * @subpackage EShop
 * @since      1.5
 */
class EShopViewQuote extends EShopView
{

	public function display($tpl = null)
	{
	    $this->bootstrapHelper       = new EshopHelperBootstrap(EshopHelper::getConfigValue('twitter_bootstrap_version'));
	    
		switch ($this->getLayout())
		{
			case 'mini':
				$this->displayMini($tpl);
				break;
			case 'popout':
				$this->displayPopout($tpl);
				break;
			default:
				break;
		}
	}

	/**
	 *
	 * @param string $tpl
	 */
	protected function displayMini($tpl = null)
	{
		//Get quote data
		$quote               = new EshopQuote();
		$items               = $quote->getQuoteData();
		$countProducts       = $quote->countProducts();
		$this->items         = $items;
		$this->countProducts = $countProducts;

		parent::display($tpl);
	}

	protected function displayPopout($tpl = null)
	{
		JFactory::getDocument()->addStyleSheet(JUri::base(true) . '/components/com_eshop/assets/colorbox/colorbox.css');
		$session         = JFactory::getSession();
		$tax             = new EshopTax(EshopHelper::getConfig());
		$currency        = new EshopCurrency();
		$quoteData       = $this->get('QuoteData');
		$this->quoteData = $quoteData;
		$this->tax       = $tax;
		$this->currency  = $currency;

		// Success message
		if ($session->get('success'))
		{
			$this->success = $session->get('success');

			$session->clear('success');
		}

		parent::display($tpl);
	}
}