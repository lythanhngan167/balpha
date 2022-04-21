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
class EShopViewOption extends EShopViewForm
{

	function _buildListArray(&$lists, $item)
	{
		$options = array();
		$options[] = JHtml::_('select.option', 'Select', 'Select');
		$options[] = JHtml::_('select.option', 'Radio', 'Radio');
		$options[] = JHtml::_('select.option', 'Checkbox', 'Checkbox');
		$options[] = JHtml::_('select.option', 'Text', 'Text');
		$options[] = JHtml::_('select.option', 'Textarea', 'Textarea');
		$options[] = JHtml::_('select.option', 'File', 'File');
		$options[] = JHtml::_('select.option', 'Date', 'Date');
		$options[] = JHtml::_('select.option', 'Datetime', 'Datetime');
		$lists['option_type'] = JHtml::_('select.genericlist', $options, 'option_type', 
			array(
				'option.text.toHtml' => false, 
				'option.text' => 'text', 
				'option.value' => 'value', 
				'list.attr' => ' class="inputbox" ', 
				'list.select' => $item->option_type));
		if ($item->id)
		{
			$lists['option_values'] = EshopHelper::getOptionValues($item->id);
		}
		else 
		{
			$lists['option_values'] = array();
		}
		parent::_buildListArray($lists, $item);
	}
}