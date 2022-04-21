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
class EShopControllerTheme extends EShopController
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

	function install()
	{
		$input = JFactory::getApplication()->input;
		$model = & $this->getModel('theme');
		$ret = $model->install();
		if ($ret)
		{
			$msg = JText::_('ESHOP_THEME_INSTALLED');
		}
		else
		{
			$msg = $input->set('msg', 'ESHOP_THEME_INSTALL_ERROR');
		}
		$this->setRedirect('index.php?option=com_eshop&view=themes', $msg);
	}
}