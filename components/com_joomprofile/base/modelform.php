<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
jimport('joomla.application.component.modelform');
class JoomprofileModelform extends JModelForm
{
	protected $_forms_path 		= null;
	protected $_fields_path 	= null;
	protected $_location 		= null;
	
	protected $_name;
	public function __construct($config = array())
	{
		parent::__construct($config);
			
		$this->_forms_path  = $this->_location.'/forms';
		$this->_fields_path = $this->_location.'/fields';
		// Setup path for forms
		JForm::addFormPath($this->_forms_path);
		JForm::addFieldPath($this->_fields_path);
	}
	
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		//TODO : name must be unique for different form
		$name = 'joomprofile.'.$this->getName();
		$form = $this->loadForm($name, $this->getName(), array('control' => 'joomprofile_form', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}

		return $form;
	}
	
	protected function loadFormData()
	{
		return $this->data;
	}
	
	public function getPrefix()
	{
		if (empty($this->_prefix))
		{
			$r = null;
			if (!preg_match('/(.*)Modelform/i', get_class($this), $r))
			{
				throw new Exception(JText::_('JLIB_APPLICATION_ERROR_MODELFORM_GET_PREFIX'), 500);
			}
			$this->_prefix = strtolower($r[1]);
		}

		return $this->_prefix;
	}
	
	public function getName()
	{
		if (empty($this->_name))
		{
			$r = null;
			if (!preg_match('/Modelform(.*)/i', get_class($this), $r))
			{
				throw new Exception(JText::_('JLIB_APPLICATION_ERROR_MODELFORM_GET_NAME'), 500);
			}
			$this->_name = strtolower($r[1]);
		}

		return $this->_name;
	}
	
	public function setData($data)
	{
		$this->data = (array)$data;
		return $this;
	}
	
	/**
	 * over-rided because it uses JPATH_COMPONENT constant, without checking
	 * (non-PHPdoc)
	 * @see libraries/legacy/model/JModelForm::loadForm()
	 */
	protected function loadForm($name, $source = null, $options = array(), $clear = false, $xpath = false)
	{
		// Handle the optional arguments.
		$options['control'] = JArrayHelper::getValue($options, 'control', false);

		// Create a signature hash.
		$hash = md5($source . serialize($options));

		// Check if we can use a previously loaded form.
		if (isset($this->_forms[$hash]) && !$clear)
		{
			return $this->_forms[$hash];
		}

		// Get the form.
		// check before using
		if(defined('JPATH_COMPONENT')){
			JForm::addFormPath(JPATH_COMPONENT . '/models/forms');
			JForm::addFieldPath(JPATH_COMPONENT . '/models/fields');
		}

		try
		{
			$form = JForm::getInstance($name, $source, $options, false, $xpath);

			if (isset($options['load_data']) && $options['load_data'])
			{
				// Get the data for the form.
				$data = $this->loadFormData();
			}
			else
			{
				$data = array();
			}

			// Allow for additional modification of the form, and events to be triggered.
			// We pass the data because plugins may require it.
			$this->preprocessForm($form, $data);

			// Load the data into the form after the plugins have operated.
			$form->bind($data);

		}
		catch (Exception $e)
		{
			$this->setError($e->getMessage());
			return false;
		}

		// Store the form for later.
		$this->_forms[$hash] = $form;

		return $form;
	}
}