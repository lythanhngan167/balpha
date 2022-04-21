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

class JFormFieldEshopweight extends JFormField
{

	/**
	 * Element name
	 *
	 * @access	protected
	 * @var		string
	 */
	var $_name = 'eshopweight';

	function getInput()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('a.id AS value, b.weight_name AS text')
			->from('#__eshop_weights AS a')
			->innerJoin('#__eshop_weightdetails AS b ON (a.id = b.weight_id)')
			->where('a.published = 1')
			->where('b.language = "' . JComponentHelper::getParams('com_languages')->get('site', 'en-GB') . '"')
			->order('b.weight_name');
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		$taxClass = JHtml::_('select.genericlist', $rows, $this->name, 
			array(
				'option.text.toHtml' => false, 
				'option.value' => 'value', 
				'option.text' => 'text', 
				'list.attr' => ' class="inputbox" ', 
				'list.select' => $this->value));
		return $taxClass;
	}
}