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
 * EShop controller
 *
 * @package		Joomla
 * @subpackage	EShop
 * @since 1.5
 */
class EShopControllerConfiguration extends JControllerLegacy
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
	 * Save the category
	 *
	 */
	function save()
	{
		$input    = new RADInput();
		$post     = $input->getData(RAD_INPUT_ALLOWRAW);
		
		if (!isset($post['customer_group_display']))
		{
			$post['customer_group_display'] = '';
		}
		
		if (!isset($post['sort_options']))
		{
			$post['sort_options'] = '';
		}
		
		$model = $this->getModel('configuration');
		$ret = $model->store($post);
		
		if ($ret)
		{
			$msg = JText::_('ESHOP_CONFIGURATION_SAVED');
		}
		else
		{
			$msg = JText::_('ESHOP_CONFIGURATION_SAVING_ERROR');
		}
		
		$this->setRedirect('index.php?option=com_eshop&view=configuration', $msg);
	}

	/**
	 * Cancel the configuration
	 *
	 */
	function cancel()
	{
		$this->setRedirect('index.php?option=com_eshop&view=dashboard');
	}
}