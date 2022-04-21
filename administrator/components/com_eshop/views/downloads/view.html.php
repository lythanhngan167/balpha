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
class EShopViewDownloads extends EShopViewList
{
	/**
	 * Override Build Toolbar function, only need Delete, Edit, New
	 */
	function _buildToolbar()
	{
		$viewName = $this->getName();
		$controller = EShopInflector::singularize($this->getName());
		JToolBarHelper::title(JText::_($this->lang_prefix.'_'.strtoupper($viewName)));
		JToolBarHelper::deleteList(JText::_($this->lang_prefix . '_DELETE_' . strtoupper($this->getName()) . '_CONFIRM'), $controller . '.remove');
		JToolBarHelper::editList($controller.'.edit');
		JToolbarHelper::addNew($controller.'.add');
	}
}