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
class EShopViewManufacturers extends EShopView
{

	public function display($tpl = null)
	{
		$app     = JFactory::getApplication();
		$session = JFactory::getSession();
		$params  = $app->getParams();
		$model   = $this->getModel();

		$this->setPageTitle($params->get('page_title', ''));

		$session->set('continue_shopping_url', JUri::getInstance()->toString());

		if ($session->get('warning'))
		{
			$this->warning = $session->get('warning');
			$session->clear('warning');
		}

		$this->config           = EshopHelper::getConfig();
		$this->items            = $model->getData();
		$this->manufacturersPerRow = EshopHelper::getConfigValue('items_per_row', 3);
		$this->pagination       = $model->getPagination();
		$this->params           = $params;
		$this->bootstrapHelper  = new EshopHelperBootstrap(EshopHelper::getConfigValue('twitter_bootstrap_version'));

		parent::display($tpl);
	}
}