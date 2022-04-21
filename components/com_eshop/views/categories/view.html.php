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
defined('_JEXEC') or die();

/**
 * HTML View class for EShop component
 *
 * @static
 * @package        Joomla
 * @subpackage     EShop
 * @since          1.5
 */
class EShopViewCategories extends EShopView
{

	public function display($tpl = null)
	{
		$document = JFactory::getDocument();
		$app     = JFactory::getApplication();
		$session = JFactory::getSession();
		$params  = $app->getParams();
		$model   = $this->getModel();
		
		$title  = $params->get('page_title', '');

		if ($title == '')
		{
			$title = JText::_('ESHOP_CATEGORIES_TITLE');
		}

		$this->setPageTitle($title);
		
		$params->def('page_heading', JText::_('ESHOP_CATEGORIES_HEADING'));

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

		$session->set('continue_shopping_url', JUri::getInstance()->toString());

		if ($session->get('warning'))
		{
			$this->warning = $session->get('warning');
			$session->clear('warning');
		}

		$this->config           = EshopHelper::getConfig();
		$this->items            = $model->getData();
		$this->categoriesPerRow = EshopHelper::getConfigValue('items_per_row', 3);
		$this->pagination       = $model->getPagination();
		$this->params           = $params;
		$this->bootstrapHelper  = new EshopHelperBootstrap(EshopHelper::getConfigValue('twitter_bootstrap_version'));
		
		if ($this->getLayout() == 'products')
		{
			JLoader::register('EshopModelProducts', JPATH_ROOT . '/components/com_eshop/models/products.php');
			
			for ($i = 0, $n = count($this->items); $i < $n; $i++)
			{
				$item = $this->items[$i];
				$item->products = RADModel::getInstance('Products', 'EshopModel', array('remember_states' => false))
					->limitstart(0)
					->limit($params->get('number_products', '5'))
					->category_id($item->id)
					->sort_options(EshopHelper::getConfigValue('default_sorting'))
					->getData();
			}
			
			$this->tax		= new EshopTax(EshopHelper::getConfig());
			$this->currency	= new EshopCurrency();
		}

		parent::display($tpl);
	}
}