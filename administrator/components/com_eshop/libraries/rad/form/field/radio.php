<?php
/**
 * Form Field class for the Joomla RAD.
 * Supports a radiolist custom field.
 *
 * @package     Joomla.RAD
 * @subpackage  Form
 */
class RADFormFieldRadio extends RADFormField
{

	/**
	 * The form field type.
	 *
	 * @var    string
	 *	 
	 */
	protected $type = 'Radio';

	/**
	 * Options for Radiolist
	 * @var array
	 */
	protected $options = array();

	/**
	 * Number options displayed perrow
	 * @var int
	*/
	protected $optionsPerRow = 1;

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
		if ((int) $row->size)
		{
			$this->optionsPerRow = (int) $row->size;
		}
		if (is_array($row->values))
		{
			$this->options = $row->values;
		}
		elseif (strpos($row->values, "\r\n") !== FALSE)
		{
			$this->options = explode("\r\n", $row->values);
		}
		else
		{
			$this->options = explode(",", $row->values);
		}
	}

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 *	 
	 */
	protected function getInput()
	{
		$html = array();
		$options = (array) $this->options;
		$attributes = $this->buildAttributes();
		$value = trim($this->value);
		$html[] = '<fieldset id="' . $this->name . '"' . '>';
		$html[] = '<ul class="clearfix">';
		$i = 0;
		$optionsPerRow = (int) $this->optionsPerRow;
		if (!$optionsPerRow)
		{
			$optionsPerRow = 1;
		}
		$span = intval(12 / $optionsPerRow);
		$numberOptions = count($options);
		foreach ($options as $option)
		{
			$i++;
			$optionValue = trim($option);
			$checked = ($optionValue == $value) ? 'checked' : '';
			$html[] = '<li class="span' . $span . '">';
			$html[] = '<label class="radio" for="' . $this->name . $i . '">';
			$html[] = '<input type="radio" id="' . $this->name . $i . '" name="' . $this->name . '" value="' .
				 htmlspecialchars($optionValue, ENT_COMPAT, 'UTF-8') . '"' . $checked . $attributes . $this->extraAttributes . '/>'.$option;
			$html[] = '</label>';
			$html[] = '</li>';
			if ($i % $optionsPerRow == 0 && $i < $numberOptions)
			{
				$html[] = '</ul>';
				$html[] = '<ul class="clearfix">';
			}
		}
		// End the checkbox field output.
		$html[] = '</fieldset>';
		
		return implode($html);
	}
}