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

class JFormFieldEshopCustomergroups extends JFormField
{

	/**
	 * Element name
	 *
	 * @access	protected
	 * @var		string
	 */
	var $_name = 'eshopcustomergroups';

	function getInput()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('a.id AS value, b.customergroup_name AS text')
			->from('#__eshop_customergroups AS a')
			->innerJoin('#__eshop_customergroupdetails AS b ON (a.id = b.customergroup_id)')
			->where('a.published = 1')
			->where('b.language = "' . JComponentHelper::getParams('com_languages')->get('site', 'en-GB') . '"')
			->order('b.customergroup_name');
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		return JHtml::_('select.genericlist', $rows, $this->name.'[]', 
			array(
				'option.text.toHtml' => false, 
				'option.value' => 'value', 
				'option.text' => 'text', 
				'list.attr' => ' class="inputbox" multiple="multiple"',
				'list.select' => $this->value));
	}
}

