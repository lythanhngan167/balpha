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
defined( '_JEXEC' ) or die();
/**
 * EShop controller
 *
 * @package		Joomla
 * @subpackage	EShop
 * @since 1.5
 */
class EshopController extends JControllerLegacy
{
	/**
	 * Constructor function
	 *
	 * @param array $config
	 */
	function __construct($config = array())
	{
		parent::__construct($config);
	}

	/**
	 * Display information
	 *
	 */
	function display($cachable = false, $urlparams = false)
	{
		$input = JFactory::getApplication()->input;
		$task = $this->getTask();
		//print_r($task); die;
		$view = $input->get('view', '');
		if (!$view)
		{
			$input->set('view', 'dashboard');
		}
		EShopHelper::renderSubmenu($input->get('view', 'configuration'));
		parent::display();
		EShopHelper::displayCopyRight();
	}

	/**
	 *
	 * Function to install sample data
	 */
	function installSampleData()
	{
		$mainframe = JFactory::getApplication();
		$db = JFactory::getDbo();
		$sampleSql = JPATH_ADMINISTRATOR.'/components/com_eshop/sql/sample.eshop.sql';
		$query = JFile::read($sampleSql);
		$queries = $db->splitSql($query);
		if (count($queries))
		{
			foreach ($queries as $query)
			{
				$query = trim($query);
				if ($query != '' && $query{0} != '#')
				{
					$db->setQuery($query);
					$db->execute();
				}
			}
		}
		$mainframe->enqueueMessage(JText::_('ESHOP_INSTALLATION_DONE'));
		$mainframe->redirect('index.php?option=com_eshop&view=dashboard');
	}

	/**
	 *
	 * Function to check if extension is up to date or not
	 * @return 0: error, 1: Up to date, 2: Out of date
	 */
	function checkUpdate()
	{
		// Get the caching duration.
		$component     = JComponentHelper::getComponent('com_installer');
		$params        = $component->params;
		$cache_timeout = $params->get('cachetimeout', 6, 'int');
		$cache_timeout = 3600 * $cache_timeout;
		// Get the minimum stability.
		$minimum_stability = $params->get('minimum_stability', JUpdater::STABILITY_STABLE, 'int');
		JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_installer/models');
		/** @var InstallerModelUpdate $model */
		$model = JModelLegacy::getInstance('Update', 'InstallerModel');
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('extension_id')
			->from('#__extensions')
			->where('`type` = "package"')
			->where('`element` = "pkg_eshop"');
		$db->setQuery($query);
		$eid = (int) $db->loadResult();
		$result['status'] = 0;
		if ($eid)
		{
			$ret = JUpdater::getInstance()->findUpdates($eid, $cache_timeout, $minimum_stability);
			if ($ret)
			{
				$model->setState('list.start', 0);
				$model->setState('list.limit', 0);
				$model->setState('filter.extension_id', $eid);
				$updates          = $model->getItems();
				$result['status'] = 2;
				if (count($updates))
				{
					$result['message'] = JText::sprintf('ESHOP_UPDATE_CHECKING_UPDATE_FOUND', $updates[0]->version);
				}
				else
				{
					$result['message'] = JText::sprintf('ESHOP_UPDATE_CHECKING_UPDATE_FOUND', null);
				}
			}
			else
			{
				$result['status']  = 1;
				$result['message'] = JText::_('ESHOP_UPDATE_CHECKING_UP_TO_DATE');
			}
		}
		echo json_encode($result);
		JFactory::getApplication()->close();
	}
}
