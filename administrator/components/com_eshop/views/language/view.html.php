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
class EShopViewLanguage extends JViewLegacy
{

	function display($tpl = null)
	{
		$input = JFactory::getApplication()->input;
		$mainframe = JFactory::getApplication();
		$option = 'com_eshop';
		$lang = $mainframe->getUserStateFromRequest($option . 'lang', 'lang', 'en-GB', 'string');
		if (!$lang)
			$lang = 'en-GB';
		$search = $mainframe->getUserStateFromRequest('com_eshop.language.search', 'search', '', 'string');
		$search = JString::strtolower($search);
		$lists['search'] = $search;
		$item = $input->get('item', 'com_eshop');
		if (!$item)
			$item = 'com_eshop';
		$model = $this->getModel('language');
		$trans = $model->getTrans($lang, $item);
		$languages = $model->getSiteLanguages();
		$pagination = $model->getPagination();
		$options = array();
		foreach ($languages as $language)
		{
			$options[] = JHtml::_('select.option', $language, $language);
		}
		$lists['lang'] = JHtml::_('select.genericlist', $options, 'lang', ' class="inputbox"  onchange="submit();" ', 'value', 'text', $lang);
		$options = array();
		$options[] = JHtml::_('select.option', 'com_eshop', JText::_('ESHOP_FRONT_END'));
		$options[] = JHtml::_('select.option', 'admin.com_eshop', JText::_('ESHOP_BACK_END'));
		$lists['item'] = JHtml::_('select.genericlist', $options, 'item', ' class="inputbox"  onchange="submit();" ', 'value', 'text', $item);
		$this->assignRef('trans', $trans);
		$this->assignRef('lists', $lists);
		$this->assignRef('lang', $lang);
		$this->assignRef('item', $item);
		$this->pagination = $pagination;
		parent::display($tpl);
	}
}