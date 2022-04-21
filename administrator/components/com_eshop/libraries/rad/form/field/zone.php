<?php
/**
 * Supports a custom field which display list of countries
 *
 * @package     Joomla.RAD
 * @subpackage  Form
 */
class RADFormFieldZone extends RADFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 */
	public $type = 'Zone';	
	/**
	 * ID of the country used to build the zone
	 * 
	 * @var int
	 */
	protected $countryId = null;
	/**
	 * Method to instantiate the form field object.
	 *
	 * @param   JTable  $row  the table object store form field definitions
	 * @param	mixed	$value the initial value of the form field
	 *
	 */
	public function __construct($row, $value)
	{
		parent::__construct($row, $value);
	}

	/**
	 * Set ID of the country used to generate the zones list
	 * @param int $countryId
	 */
	public function setCountryId($countryId)
	{
		$this->countryId = (int) $countryId;
	}
	/**
	 * Method to get the custom field options.
	 * Use the query attribute to supply a query to generate the list.
	 *
	 * @return  array  The field option objects.
	 *
	 */
	protected function getOptions()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('id AS `value`, zone_name AS `text`')
			->from('#__eshop_zones')
			->where('country_id = ' . (int)$this->countryId)
			->where('published = 1');
		$db->setQuery($query);
		$options = array();
		$options[] = JHtml::_('select.option', '', JText::_('ESHOP_PLEASE_SELECT'));
		$options = array_merge($options, $db->loadObjectList());
		return $options;
	}		
}
