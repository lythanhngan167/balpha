<?php
/**
 * @version		1.3.1
 * @package		Joomla
 * @subpackage	EShop
 * @author  	Giang Dinh Truong
 * @copyright	Copyright (C) 2012 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die();

/**
 * Eshop Currency Plugin
 *
 * @package		Joomla
 * @subpackage	EShop
 */
class plgSystemEshopCurrency extends JPlugin
{

	/**
	 * Static variable used to determin whether the plugin is running or not
	 * @var boolean
	 */
	public static $running;

	function onAfterInitialise()
	{
		jimport('joomla.filesystem.file');
		require_once JPATH_ROOT.'/components/com_eshop/helpers/helper.php';
		if (EshopHelper::getConfigValue('auto_update_currency') && JFile::exists(JPATH_ROOT . '/components/com_eshop/eshop.php') && !self::$running)
		{
			self::$running = true;
			$timePeriod = $this->params->get('time_period', 1);
			$timeUnit = $this->params->get('time_unit', 'day');
			EshopHelper::updateCurrencies(false, $timePeriod, $timeUnit);
			self::$running = false;
		}
		return true;
	}
}
