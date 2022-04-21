<?php
/**
 * @version		3.1.0
 * @package		Joomla
 * @subpackage	EShop
 * @author  	Giang Dinh Truong
 * @copyright	Copyright (C) 2013 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die();
jimport('joomla.form.formfield');

class JFormFieldEshoppayments extends JFormField
{

	/**
	 * Element name
	 *
	 * @access	protected
	 * @var		string
	 */
	var $_name = 'eshoppayments';

	function getInput()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('id AS value, title AS text')
			->from('#__eshop_payments')
			->where('published = 1')
			->order('title');
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		$payments = JHtml::_('select.genericlist', $rows, $this->name.'[]', 
			array(
				'option.text.toHtml' => false, 
				'option.value' => 'value', 
				'option.text' => 'text', 
				'list.attr' => ' class="inputbox" multiple="multiple"',
				'list.select' => $this->value));
		return $payments;
	}
}