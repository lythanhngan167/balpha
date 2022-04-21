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
 * @package        Joomla
 * @subpackage     EShop
 * @since          1.5
 */
class EShopViewFrontpage extends EShopView
{

	public function display($tpl = null)
	{
		$document = JFactory::getDocument();
		$baseUri  = JUri::base(true);
		$document->addStyleSheet($baseUri . '/components/com_eshop/assets/colorbox/colorbox.css');
		$document->addStyleSheet($baseUri . '/components/com_eshop/assets/css/labels.css');

		$params = JFactory::getApplication()->getParams();
		$title  = $params->get('page_title', '');

		if ($title == '')
		{
			$title = JText::_('ESHOP_FRONT_PAGE_TITLE');
		}

		$this->setPageTitle($title);
		
		$params->def('page_heading', JText::_('ESHOP_FRONT_PAGE_HEADING'));

		// Set metakey, metadesc and robots
		if ($params->get('menu-meta_keywords'))
		{
			$document->setMetaData('keywords', $params->get('menu-meta_keywords'));
		}

		if ($params->get('menu-meta_description'))
		{
			$document->setMetaData('description', $params->get('menu-meta_description'));
		}

		if ($params->get('robots'))
		{
			$document->setMetadata('robots', $params->get('robots'));
		}

		$numberCategories = (int) $params->get('num_categories', 9);
		$numberProducts   = (int) $params->get('num_products', 9);

		JLoader::register('EshopModelCategories', JPATH_ROOT . '/components/com_eshop/models/categories.php');
		JLoader::register('EshopModelProducts', JPATH_ROOT . '/components/com_eshop/models/products.php');

		if ($numberCategories > 0)
		{
			$categories = RADModel::getInstance('Categories', 'EshopModel', array('remember_states' => false))
				->limitstart(0)
				->limit($numberCategories)
				->filter_order('a.ordering')
				->getData();
		}
		else
		{
			$categories = array();
		}

		if ($numberProducts > 0)
		{
			$products = RADModel::getInstance('Products', 'EshopModel', array('remember_states' => false))
				->limitstart(0)
				->limit($numberProducts)
				->product_featured(1)
				->sort_options(EshopHelper::getConfigValue('default_sorting'))
				->getData();
		}
		else
		{
			$products = array();
		}

		// Store session for Continue Shopping Url
		JFactory::getSession()->set('continue_shopping_url', JUri::getInstance()->toString());
		$tax      = new EshopTax(EshopHelper::getConfig());
		$currency = new EshopCurrency();

		$this->params			= $params;
		$this->categories       = $categories;
		$this->products         = $products;
		$this->tax              = $tax;
		$this->currency         = $currency;
		$this->productsPerRow   = EshopHelper::getConfigValue('items_per_row', 3);
		$this->categoriesPerRow = EshopHelper::getConfigValue('items_per_row', 3);
		$this->bootstrapHelper  = new EshopHelperBootstrap(EshopHelper::getConfigValue('twitter_bootstrap_version'));

		parent::display($tpl);
	}
}