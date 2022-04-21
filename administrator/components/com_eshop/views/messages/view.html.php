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
class EshopViewMessages extends EShopViewList
{
	/**
	 * Build the toolbar for view list
	 */
	public function _buildToolbar()
	{
		$controller = EShopInflector::singularize($this->getName());
		JToolBarHelper::editList($controller.'.edit');
		$viewName = $this->getName();
		JToolBarHelper::title(JText::_($this->lang_prefix.'_'.strtoupper($viewName)));
	}
}