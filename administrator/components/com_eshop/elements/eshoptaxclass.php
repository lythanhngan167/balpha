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

class JFormFieldEshoptaxclass extends JFormField
{

	/**
	 * Element name
	 *
	 * @access	protected
	 * @var		string
	 */
	var $_name = 'eshoptaxclass';

	function getInput()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('id AS value, taxclass_name AS text')
			->from('#__eshop_taxclasses')
			->where('published = 1')
			->order('taxclass_name');
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		$options = array();
		$options[] = JHtml::_('select.option', 0, JText::_('ESHOP_NONE'), 'value', 'text');
		if (count($rows))
		{
			$options = array_merge($options, $rows);
		}
		$taxClass = JHtml::_('select.genericlist', $options, $this->name, 
			array(
				'option.text.toHtml' => false, 
				'option.value' => 'value', 
				'option.text' => 'text', 
				'list.attr' => ' class="inputbox" ', 
				'list.select' => $this->value));
		return $taxClass;
	}
}