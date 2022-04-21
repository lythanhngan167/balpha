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
class EShopViewCategory extends EShopViewForm
{

	function _buildListArray(&$lists, $item)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('a.id, b.category_name AS title, a.category_parent_id AS parent_id')
			->from('#__eshop_categories AS a')
			->innerJoin('#__eshop_categorydetails AS b ON (a.id = b.category_id)')
			->where('a.published = 1')
			->where('a.id !=' . (int) $item->id)
			->where('b.language = "' . JComponentHelper::getParams('com_languages')->get('site', 'en-GB') . '"');
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		$children = array();
		if ($rows)
		{
			// first pass - collect children
			foreach ($rows as $v)
			{
				$pt = $v->parent_id;
				$list = @$children[$pt] ? $children[$pt] : array();
				array_push($list, $v);
				$children[$pt] = $list;
			}
		}
		$list = JHtml::_('menu.treerecurse', 0, '', array(), $children, 9999, 0, 0);
		$options = array();
		$options[] = JHtml::_('select.option', '0', JText::_('ESHOP_TOP'));
		foreach ($list as $listItem)
		{
			$options[] = JHtml::_('select.option', $listItem->id, $listItem->treename);
		}
		$lists['category_parent_id'] = JHtml::_('select.genericlist', $options, 'category_parent_id', 
			array(
				'option.text.toHtml' => false, 
				'option.text' => 'text', 
				'option.value' => 'value', 
				'list.attr' => ' class="inputbox chosen" ', 
				'list.select' => $item->category_parent_id));
		//Build customer groups list
		$query->clear();
		$query->select('a.id AS value, b.customergroup_name AS text')
			->from('#__eshop_customergroups AS a')
			->innerJoin('#__eshop_customergroupdetails AS b ON (a.id = b.customergroup_id)')
			->where('a.published = 1')
			->where('b.language = "' . JComponentHelper::getParams('com_languages')->get('site', 'en-GB') . '"')
			->order('b.customergroup_name');
		$db->setQuery($query);
		$options = $db->loadObjectList();
		if ($item->category_customergroups != '')
		{
			$selectedItems = explode(',', $item->category_customergroups);
		}
		else
		{
			$selectedItems = array();
		}
		$lists['category_customergroups'] = JHtml::_('select.genericlist', $options, 'category_customergroups[]',
			array(
				'option.text.toHtml' => false,
				'option.text' => 'text',
				'option.value' => 'value',
				'list.attr' => ' class="inputbox chosen" multiple ',
				'list.select' => $selectedItems));
		//Build category layout list
		$options   = array();
		$options[] = JHtml::_('select.option', 'default', JText::_('ESHOP_CATEGORY_LAYOUT_DEFAULT'));
		$options[] = JHtml::_('select.option', 'table', JText::_('ESHOP_CATEGORY_LAYOUT_TABLE'));
		$lists['category_layout'] = JHtml::_('select.genericlist', $options, 'category_layout', ' class="inputbox" ', 'value', 'text', $item->category_layout);
	}
	
	/**
	 * Build the toolbar for view list
	 */
	public function _buildToolbar()
	{
		$input = JFactory::getApplication()->input;
		$viewName = $this->getName();
		$canDo = EshopHelper::getActions($viewName);
		$edit = $input->get('edit');
		$text = $edit ? JText::_($this->lang_prefix . '_EDIT') : JText::_($this->lang_prefix . '_NEW');
		if (JLanguageMultilang::isEnabled() && count(EshopHelper::getLanguages()) > 1)
		{
			$categoryName = $this->item->{'category_name_' . JComponentHelper::getParams('com_languages')->get('site', 'en-GB')};
		}
		else 
		{
			$categoryName = $this->item->category_name;
		}
		if ($edit)
		{
			$categoryInfo = ' [ ' . $categoryName . ' ]';
		}
		else
		{
			$categoryInfo = '';
		}
		JToolBarHelper::title(JText::_($this->lang_prefix . '_' . $viewName) . ': <small><small>[ ' . $text . ' ]' . $categoryInfo . '</small></small>');
		if ($canDo->get('core.edit') || $canDo->get('core.create'))
		{
			JToolBarHelper::apply($viewName . '.apply');
			JToolBarHelper::save($viewName . '.save');
			JToolBarHelper::save2new($viewName . '.save2new');
		}
		if ($edit)
			JToolBarHelper::cancel($viewName . '.cancel', 'JTOOLBAR_CLOSE');
		else
			JToolBarHelper::cancel($viewName . '.cancel');
	}
}