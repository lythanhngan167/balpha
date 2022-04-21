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
class EshopViewQuote extends EShopViewForm
{

	function _buildListArray(&$lists, $item)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		//Quote products list
		$query->select('*')
			->from('#__eshop_quoteproducts')
			->where('quote_id = ' . intval($item->id));
		$db->setQuery($query);
		$quoteProducts = $db->loadObjectList();
		for ($i = 0; $n = count($quoteProducts), $i < $n; $i++)
		{
			$query->clear();
			$query->select('*')
				->from('#__eshop_quoteoptions')
				->where('quote_product_id = ' . intval($quoteProducts[$i]->id));
			$db->setQuery($query);
			$quoteProducts[$i]->options = $db->loadObjectList();
		}
		$lists['quote_products'] = $quoteProducts;
		$currency = new EshopCurrency();
		$this->currency = $currency;
	}

	/**
	 * Override Build Toolbar function, only need Save, Save & Close and Close
	 */
	function _buildToolbar()
	{
		$viewName = $this->getName();
		JToolBarHelper::title(JText::_('ESHOP_QUOTE_DETAILS'));
		JToolBarHelper::cancel($viewName . '.cancel', 'JTOOLBAR_CLOSE');
	}
}