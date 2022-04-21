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

class JFormFieldEshopmanufacturers extends JFormField
{

	/**
	 * Element name
	 *
	 * @access	protected
	 * @var		string
	 */
	var $_name = 'eshopmanufacturers';

	function getInput()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('a.id AS value, b.manufacturer_name AS text')
			->from('#__eshop_manufacturers AS a')
			->innerJoin('#__eshop_manufacturerdetails AS b ON (a.id = b.manufacturer_id)')
			->where('a.published = 1')
			->where('b.language = "' . JComponentHelper::getParams('com_languages')->get('site', 'en-GB') . '"')
			->order('b.manufacturer_name');
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		$manufacturer = JHtml::_('select.genericlist', $rows, $this->name.'[]', 
			array(
				'option.text.toHtml' => false, 
				'option.value' => 'value', 
				'option.text' => 'text', 
				'list.attr' => ' class="inputbox" multiple="multiple"',
				'list.select' => $this->value));
		return $manufacturer;
	}
}