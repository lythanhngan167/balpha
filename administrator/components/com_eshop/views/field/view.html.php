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
class EShopViewField extends EShopViewForm
{

	function _buildListArray(&$lists, $item)
	{		
		$fieldTypes = array('Text', 'Textarea', 'List', 'Checkboxes', 'Radio', 'Countries', 'Zone');
		$options = array();
		$options[] = JHtml::_('select.option', -1, JText::_('ESHOP_FIELD_TYPE'));
		$options = array();
		foreach ($fieldTypes as $fieldType)
		{
			$options[] = JHtml::_('select.option', $fieldType, $fieldType);
		}
		if ($item->is_core)
		{
			$disabled = " disabled "; 
		}
		else 
		{
			$disabled = '';
		}
		$lists['fieldtype'] = JHtml::_('select.genericlist', $options, 'fieldtype', 'class="input-large" onchange="changeField(this.value)" '.$disabled, 'value', 'text', $item->fieldtype);
		
		$validateRules = array(
			'numeric' => JText::_('ESHOP_NUMERIC'),
			'integer' => JText::_('ESHOP_INTEGER'),
			'float' => JText::_('ESHOP_FLOAT'),						
			'max_len,32' => JText::_('ESHOP_MAX_LENGTH'),
			'min_len,1' => JText::_('ESHOP_MIN_LENGTH'),
			'exact_len,10' => JText::_('ESHOP_EXACT_LENGTH'),
			'max_numeric,100' => JText::_('ESHOP_MAX_NUMERIC'),
			'min_numeric,1' => JText::_('ESHOP_MIN_NUMERIC'),			
			'valid_email' => JText::_('ESHOP_VALID_EMAIL'),			
			'valid_url' => JText::_('ESHOP_VALID_URL')
		);
		$options = array();
		$options[] = JHtml::_('select.option', '', JText::_('ESHOP_NONE'));
		foreach ($validateRules as $rule => $title)
		{
			$options[] = JHtml::_('select.option', $rule, $title);			
		}
		$lists['validation_rule'] = JHtml::_('select.genericlist', $options, 'validation_rule[]', ' class="input-large" multiple size="10" onclick="buildValidationString();"', 'value', 'text', explode('|', $item->validation_rule));
		$options = array();
		$options[] = JHtml::_('select.option', 'A', JText::_('ESHOP_ALL'));
		$options[] = JHtml::_('select.option', 'B', JText::_('ESHOP_BILLING_ADDRESS'));
		$options[] = JHtml::_('select.option', 'S', JText::_('ESHOP_SHIPPING_ADDRESS'));
		$lists['address_type'] = JHtml::_('select.genericlist', $options, 'address_type', 'class="input-large" '.$disabled, 'value', 'text', $item->address_type);
		if (in_array($item->name, EShopModelField::$protectedFields))
		{
			$disabled = " disabled ";
		}
		else 
		{
			$disabled = "";
		}
		$lists['required'] = JHtml::_('select.booleanlist', 'required', ' class="inputbox" onclick="buildValidationString();" '.$disabled, $item->required);		
		$lists['multiple'] = JHtml::_('select.booleanlist', 'multiple', ' class="inputbox" '.$disabled, $item->multiple);
	}
}