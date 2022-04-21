<?php
/**
 * @version		3.1.0
 * @package		Joomla
 * @subpackage	EShop
 * @author		Giang Dinh Truong
 * @copyright	Copyright (C) 2010 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die();
jimport('joomla.form.formfield');

class JFormFieldEshopCurrency extends JFormField
{

	/**
	 * Element name
	 *
	 * @access	protected
	 * @var		string
	 */
	var $_name = 'eshopcurrency';

	function getInput()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('currency_code, currency_name')
			->from('#__eshop_currencies')
			->order('currency_name');
		$db->setQuery($query);
		$options = array();
		$options[] = JHtml::_('select.option', '', JText::_('Select Currency'), 'currency_code', 'currency_name');
		$options = array_merge($options, $db->loadObjectList());
		return JHtml::_('select.genericlist', $options, $this->name, ' class="inputbox" ', 'currency_code', 'currency_name', $this->value);
	}
}