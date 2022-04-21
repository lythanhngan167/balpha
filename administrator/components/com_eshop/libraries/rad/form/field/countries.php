<?php
/**
 * Supports a custom field which display list of countries
 *
 * @package     Joomla.RAD
 * @subpackage  Form
 */
class RADFormFieldCountries extends RADFormFieldList
{

	/**
	 * The form field type.
	 *
	 * @var    string	 
	 */
	public $type = 'Countries';

	/**
	 * Method to get the custom field options.
	 * Use the query attribute to supply a query to generate the list.
	 *
	 * @return  array  The field option objects.
	 *
	 */
	protected function getOptions()
	{
		try
		{
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('id AS `value`, country_name AS `text`')
				->from('#__eshop_countries')
				->where('published = 1')
				->order('country_name');
			$db->setQuery($query);
			$options = array();
			$options[] = JHtml::_('select.option', '', JText::_('ESHOP_PLEASE_SELECT'));
			$options = array_merge($options, $db->loadObjectList());
		}
		catch (Exception $e)
		{
			$options = array();
		}
		
		return $options;
	}
}
