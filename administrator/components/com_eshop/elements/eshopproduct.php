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

class JFormFieldEshopProduct extends JFormField
{

	/**
	 * Element name
	 *
	 * @access	protected
	 * @var		string
	 */
	var $_name = 'eshopproduct';

	function getInput()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('a.id, b.product_name AS title')
			->from('#__eshop_products AS a')
			->innerJoin('#__eshop_productdetails AS b ON (a.id = b.product_id)')
			->where('a.published = 1')
			->where('b.language = "' . JComponentHelper::getParams('com_languages')->get('site', 'en-GB') . '"')
			->order('b.product_name');
		$db->setQuery($query);
		$options = array();
		$options[] = JHtml::_('select.option', '0', JText::_('Select Product'), 'id', 'title');
		$options = array_merge($options, $db->loadObjectList());
		return JHtml::_('select.genericlist', $options, $this->name, ' class="inputbox" ', 'id', 'title', $this->value);
	}
}
