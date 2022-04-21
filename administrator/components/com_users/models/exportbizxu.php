<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\Utilities\ArrayHelper;

/**
 * Methods supporting a list of user records.
 *
 * @since  1.6
 */
class UsersModelExportbizxu extends JModelList
{

	/**
	 * @var      string    The prefix to use with controller messages.
	 * @since    1.6
	 */
	protected $text_prefix = 'COM_USERS';

	/**
	 * @var   	string  	Alias to manage history control
	 * @since   3.2
	 */
	public $typeAlias = 'com_users.exportbizxu';

	/**
	 * @var null  Item data
	 * @since  1.6
	 */
	protected $item = null;

		/**
		 * Returns a reference to the a Table object, always creating it.
		 *
		 * @param   string  $type    The table type to instantiate
		 * @param   string  $prefix  A prefix for the table class name. Optional.
		 * @param   array   $config  Configuration array for model. Optional.
		 *
		 * @return    JTable    A database object
		 *
		 * @since    1.6
		 */
		public function getTable($type = 'User', $prefix = 'UsersModel', $config = array())
		{
			return JTable::getInstance($type, $prefix, $config);
		}

		/**
		 * Method to get the record form.
		 *
		 * @param   array    $data      An optional array of data for the form to interogate.
		 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
		 *
		 * @return  JForm  A JForm object on success, false on failure
		 *
		 * @since    1.6
		 */
		public function getForm($data = array(), $loadData = true)
		{
			// Initialise variables.
			$app = JFactory::getApplication();

			// Get the form.
			$form = $this->loadForm(
				'com_users.exportbizxu', 'exportbizxu',
				array('control' => 'jform',
					'load_data' => $loadData
				)
			);

			if (empty($form))
			{
				return false;
			}

			return $form;
		}

		/**
		 * Method to get the data that should be injected in the form.
		 *
		 * @return   mixed  The data for the form.
		 *
		 * @since    1.6
		 */
		protected function loadFormData()
		{
			// Check the session for previously entered form data.
			$data = JFactory::getApplication()->getUserState('com_users.edit.user.data', array());

			if (empty($data))
			{


				$data = $this->item;

				// Support for multiple or not foreign key field: project_id
				$array = array();

				foreach ((array) $data->project_id as $value)
				{
					if (!is_array($value))
					{
						$array[] = $value;
					}
				}

				$data->project_id = implode(',',$array);

				// Support for multiple or not foreign key field: status_id
				$array = array();

				foreach ((array) $data->status_id as $value)
				{
					if (!is_array($value))
					{
						$array[] = $value;
					}
				}

				$data->status_id = $array;
			}
				return $data;
		}
}
