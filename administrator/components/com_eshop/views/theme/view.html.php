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
class EshopViewTheme extends EShopViewForm
{

	function _buildListArray(&$lists, $item)
	{
		$registry = new JRegistry();
		$registry->loadString($item->params);
		$data = new stdClass();
		$data->params = $registry->toArray();
		$form = JForm::getInstance('eshop', JPATH_ROOT . '/components/com_eshop/themes/' . $item->name . '/' . $item->name . '.xml', array(), false, '//config');
		$form->bind($data);
		$this->form = $form;
		return true;
	}
	
	/**
	 * Override Build Toolbar function, only need Save, Save & Close and Close
	 */
	function _buildToolbar()
	{
		$viewName = $this->getName();
		$canDo = EshopHelper::getActions($viewName);
		$text = JText::_($this->lang_prefix . '_EDIT');
		JToolBarHelper::title(JText::_($this->lang_prefix . '_' . $viewName) . ': <small><small>[ ' . $text . ' ]</small></small>');
		if ($canDo->get('core.edit'))
		{
			JToolBarHelper::apply($viewName . '.apply');
			JToolBarHelper::save($viewName . '.save');
		}
		JToolBarHelper::cancel($viewName . '.cancel', 'JTOOLBAR_CLOSE');
	}
}