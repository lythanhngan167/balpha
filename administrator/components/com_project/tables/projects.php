<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Project
 * @author     tung hoang <tungvacc@gmail.com>
 * @copyright  2017 tung hoang
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

use Joomla\Utilities\ArrayHelper;
/**
 * projects Table class
 *
 * @since  1.6
 */
class ProjectTableprojects extends JTable
{
	
	/**
	 * Constructor
	 *
	 * @param   JDatabase  &$db  A database connector object
	 */
	public function __construct(&$db)
	{
		JObserverMapper::addObserverClassToClass('JTableObserverContenthistory', 'ProjectTableprojects', array('typeAlias' => 'com_project.projects'));
		parent::__construct('#__projects', 'id', $db);
	}

	/**
	 * Overloaded bind function to pre-process the params.
	 *
	 * @param   array  $array   Named array
	 * @param   mixed  $ignore  Optional array or list of parameters to ignore
	 *
	 * @return  null|string  null is operation was satisfactory, otherwise returns an error
	 *
	 * @see     JTable:bind
	 * @since   1.5
	 */
	public function bind($array, $ignore = '')
	{
	    $date = JFactory::getDate();
		$task = JFactory::getApplication()->input->get('task');
	    
		// Support for multi file field: file_1
		if (!empty($array['file_1']))
		{
			if (is_array($array['file_1']))
			{
				$array['file_1'] = implode(',', $array['file_1']);
			}
			elseif (strpos($array['file_1'], ',') != false)
			{
				$array['file_1'] = explode(',', $array['file_1']);
			}
		}
		else
		{
			$array['file_1'] = '';
		}

		// Support for multi file field: file_2
		if (!empty($array['file_2']))
		{
			if (is_array($array['file_2']))
			{
				$array['file_2'] = implode(',', $array['file_2']);
			}
			elseif (strpos($array['file_2'], ',') != false)
			{
				$array['file_2'] = explode(',', $array['file_2']);
			}
		}
		else
		{
			$array['file_2'] = '';
		}

		// Support for multi file field: file_3
		if (!empty($array['file_3']))
		{
			if (is_array($array['file_3']))
			{
				$array['file_3'] = implode(',', $array['file_3']);
			}
			elseif (strpos($array['file_3'], ',') != false)
			{
				$array['file_3'] = explode(',', $array['file_3']);
			}
		}
		else
		{
			$array['file_3'] = '';
		}

		// Support for multi file field: file_4
		if (!empty($array['file_4']))
		{
			if (is_array($array['file_4']))
			{
				$array['file_4'] = implode(',', $array['file_4']);
			}
			elseif (strpos($array['file_4'], ',') != false)
			{
				$array['file_4'] = explode(',', $array['file_4']);
			}
		}
		else
		{
			$array['file_4'] = '';
		}

		// Support for multi file field: file_5
		if (!empty($array['file_5']))
		{
			if (is_array($array['file_5']))
			{
				$array['file_5'] = implode(',', $array['file_5']);
			}
			elseif (strpos($array['file_5'], ',') != false)
			{
				$array['file_5'] = explode(',', $array['file_5']);
			}
		}
		else
		{
			$array['file_5'] = '';
		}

		$input = JFactory::getApplication()->input;
		$task = $input->getString('task', '');

		if ($array['id'] == 0 && empty($array['created_by']))
		{
			$array['created_by'] = JFactory::getUser()->id;
		}

		if ($array['id'] == 0 && empty($array['modified_by']))
		{
			$array['modified_by'] = JFactory::getUser()->id;
		}

		if ($task == 'apply' || $task == 'save')
		{
			$array['modified_by'] = JFactory::getUser()->id;
		}

		if ($array['id'] == 0)
		{
			$array['create_date'] = $date->toSql();
		}

		if ($task == 'apply' || $task == 'save')
		{
			$array['modified_date'] = $date->toSql();
		}

		if (isset($array['params']) && is_array($array['params']))
		{
			$registry = new JRegistry;
			$registry->loadArray($array['params']);
			$array['params'] = (string) $registry;
		}

		if (isset($array['metadata']) && is_array($array['metadata']))
		{
			$registry = new JRegistry;
			$registry->loadArray($array['metadata']);
			$array['metadata'] = (string) $registry;
		}

		if (!JFactory::getUser()->authorise('core.admin', 'com_project.projects.' . $array['id']))
		{
			$actions         = JAccess::getActionsFromFile(
				JPATH_ADMINISTRATOR . '/components/com_project/access.xml',
				"/access/section[@name='projects']/"
			);
			$default_actions = JAccess::getAssetRules('com_project.projects.' . $array['id'])->getData();
			$array_jaccess   = array();

			foreach ($actions as $action)
			{
                if (key_exists($action->name, $default_actions))
                {
                    $array_jaccess[$action->name] = $default_actions[$action->name];
                }
			}

			$array['rules'] = $this->JAccessRulestoArray($array_jaccess);
		}

		// Bind the rules for ACL where supported.
		if (isset($array['rules']) && is_array($array['rules']))
		{
			$this->setRules($array['rules']);
		}

		return parent::bind($array, $ignore);
	}

	/**
	 * This function convert an array of JAccessRule objects into an rules array.
	 *
	 * @param   array  $jaccessrules  An array of JAccessRule objects.
	 *
	 * @return  array
	 */
	private function JAccessRulestoArray($jaccessrules)
	{
		$rules = array();

		foreach ($jaccessrules as $action => $jaccess)
		{
			$actions = array();

			if ($jaccess)
			{
				foreach ($jaccess->getData() as $group => $allow)
				{
					$actions[$group] = ((bool)$allow);
				}
			}

			$rules[$action] = $actions;
		}

		return $rules;
	}

	/**
	 * Overloaded check function
	 *
	 * @return bool
	 */
	public function check()
	{
		// If there is an ordering column and this is a new row then get the next ordering value
		if (property_exists($this, 'ordering') && $this->id == 0)
		{
			$this->ordering = self::getNextOrder();
		}
		
		
		// Support multi file field: file_1
		$app = JFactory::getApplication();
		$files = $app->input->files->get('jform', array(), 'raw');
		$array = $app->input->get('jform', array(), 'ARRAY');

		if ($files['file_1'][0]['size'] > 0)
		{
			// Deleting existing files
			$oldFiles = ProjectHelper::getFiles($this->id, $this->_tbl, 'file_1');

			foreach ($oldFiles as $f)
			{
				$oldFile = JPATH_ROOT . '/media/upload/' . $f;

				if (file_exists($oldFile) && !is_dir($oldFile))
				{
					unlink($oldFile);
				}
			}

			$this->file_1 = "";

			foreach ($files['file_1'] as $singleFile )
			{
				jimport('joomla.filesystem.file');

				// Check if the server found any error.
				$fileError = $singleFile['error'];
				$message = '';

				if ($fileError > 0 && $fileError != 4)
				{
					switch ($fileError)
					{
						case 1:
							$message = JText::_('File size exceeds allowed by the server');
							break;
						case 2:
							$message = JText::_('File size exceeds allowed by the html form');
							break;
						case 3:
							$message = JText::_('Partial upload error');
							break;
					}

					if ($message != '')
					{
						$app->enqueueMessage($message, 'warning');

						return false;
					}
				}
				elseif ($fileError == 4)
				{
					if (isset($array['file_1']))
					{
						$this->file_1 = $array['file_1'];
					}
				}
				else
				{

					// Replace any special characters in the filename
					jimport('joomla.filesystem.file');
					$filename = JFile::stripExt($singleFile['name']);
					$extension = JFile::getExt($singleFile['name']);
					$filename = preg_replace("/[^A-Za-z0-9]/i", "-", $filename);
					$filename = $filename . '.' . $extension;
					$uploadPath = JPATH_ROOT . '/media/upload/' . $filename;
					$fileTemp = $singleFile['tmp_name'];

					if (!JFile::exists($uploadPath))
					{
						if (!JFile::upload($fileTemp, $uploadPath))
						{
							$app->enqueueMessage('Error moving file', 'warning');

							return false;
						}
					}

					$this->file_1 .= (!empty($this->file_1)) ? "," : "";
					$this->file_1 .= $filename;
				}
			}
		}
		else
		{
			$this->file_1 .= $array['file_1_hidden'];
		}
		// Support multi file field: file_2
		$app = JFactory::getApplication();
		$files = $app->input->files->get('jform', array(), 'raw');
		$array = $app->input->get('jform', array(), 'ARRAY');

		if ($files['file_2'][0]['size'] > 0)
		{
			// Deleting existing files
			$oldFiles = ProjectHelper::getFiles($this->id, $this->_tbl, 'file_2');

			foreach ($oldFiles as $f)
			{
				$oldFile = JPATH_ROOT . '/media/upload/' . $f;

				if (file_exists($oldFile) && !is_dir($oldFile))
				{
					unlink($oldFile);
				}
			}

			$this->file_2 = "";

			foreach ($files['file_2'] as $singleFile )
			{
				jimport('joomla.filesystem.file');

				// Check if the server found any error.
				$fileError = $singleFile['error'];
				$message = '';

				if ($fileError > 0 && $fileError != 4)
				{
					switch ($fileError)
					{
						case 1:
							$message = JText::_('File size exceeds allowed by the server');
							break;
						case 2:
							$message = JText::_('File size exceeds allowed by the html form');
							break;
						case 3:
							$message = JText::_('Partial upload error');
							break;
					}

					if ($message != '')
					{
						$app->enqueueMessage($message, 'warning');

						return false;
					}
				}
				elseif ($fileError == 4)
				{
					if (isset($array['file_2']))
					{
						$this->file_2 = $array['file_2'];
					}
				}
				else
				{

					// Replace any special characters in the filename
					jimport('joomla.filesystem.file');
					$filename = JFile::stripExt($singleFile['name']);
					$extension = JFile::getExt($singleFile['name']);
					$filename = preg_replace("/[^A-Za-z0-9]/i", "-", $filename);
					$filename = $filename . '.' . $extension;
					$uploadPath = JPATH_ROOT . '/media/upload/' . $filename;
					$fileTemp = $singleFile['tmp_name'];

					if (!JFile::exists($uploadPath))
					{
						if (!JFile::upload($fileTemp, $uploadPath))
						{
							$app->enqueueMessage('Error moving file', 'warning');

							return false;
						}
					}

					$this->file_2 .= (!empty($this->file_2)) ? "," : "";
					$this->file_2 .= $filename;
				}
			}
		}
		else
		{
			$this->file_2 .= $array['file_2_hidden'];
		}
		// Support multi file field: file_3
		$app = JFactory::getApplication();
		$files = $app->input->files->get('jform', array(), 'raw');
		$array = $app->input->get('jform', array(), 'ARRAY');

		if ($files['file_3'][0]['size'] > 0)
		{
			// Deleting existing files
			$oldFiles = ProjectHelper::getFiles($this->id, $this->_tbl, 'file_3');

			foreach ($oldFiles as $f)
			{
				$oldFile = JPATH_ROOT . '/media/upload/' . $f;

				if (file_exists($oldFile) && !is_dir($oldFile))
				{
					unlink($oldFile);
				}
			}

			$this->file_3 = "";

			foreach ($files['file_3'] as $singleFile )
			{
				jimport('joomla.filesystem.file');

				// Check if the server found any error.
				$fileError = $singleFile['error'];
				$message = '';

				if ($fileError > 0 && $fileError != 4)
				{
					switch ($fileError)
					{
						case 1:
							$message = JText::_('File size exceeds allowed by the server');
							break;
						case 2:
							$message = JText::_('File size exceeds allowed by the html form');
							break;
						case 3:
							$message = JText::_('Partial upload error');
							break;
					}

					if ($message != '')
					{
						$app->enqueueMessage($message, 'warning');

						return false;
					}
				}
				elseif ($fileError == 4)
				{
					if (isset($array['file_3']))
					{
						$this->file_3 = $array['file_3'];
					}
				}
				else
				{

					// Replace any special characters in the filename
					jimport('joomla.filesystem.file');
					$filename = JFile::stripExt($singleFile['name']);
					$extension = JFile::getExt($singleFile['name']);
					$filename = preg_replace("/[^A-Za-z0-9]/i", "-", $filename);
					$filename = $filename . '.' . $extension;
					$uploadPath = JPATH_ROOT . '/media/upload/' . $filename;
					$fileTemp = $singleFile['tmp_name'];

					if (!JFile::exists($uploadPath))
					{
						if (!JFile::upload($fileTemp, $uploadPath))
						{
							$app->enqueueMessage('Error moving file', 'warning');

							return false;
						}
					}

					$this->file_3 .= (!empty($this->file_3)) ? "," : "";
					$this->file_3 .= $filename;
				}
			}
		}
		else
		{
			$this->file_3 .= $array['file_3_hidden'];
		}
		// Support multi file field: file_4
		$app = JFactory::getApplication();
		$files = $app->input->files->get('jform', array(), 'raw');
		$array = $app->input->get('jform', array(), 'ARRAY');

		if ($files['file_4'][0]['size'] > 0)
		{
			// Deleting existing files
			$oldFiles = ProjectHelper::getFiles($this->id, $this->_tbl, 'file_4');

			foreach ($oldFiles as $f)
			{
				$oldFile = JPATH_ROOT . '/media/upload/' . $f;

				if (file_exists($oldFile) && !is_dir($oldFile))
				{
					unlink($oldFile);
				}
			}

			$this->file_4 = "";

			foreach ($files['file_4'] as $singleFile )
			{
				jimport('joomla.filesystem.file');

				// Check if the server found any error.
				$fileError = $singleFile['error'];
				$message = '';

				if ($fileError > 0 && $fileError != 4)
				{
					switch ($fileError)
					{
						case 1:
							$message = JText::_('File size exceeds allowed by the server');
							break;
						case 2:
							$message = JText::_('File size exceeds allowed by the html form');
							break;
						case 3:
							$message = JText::_('Partial upload error');
							break;
					}

					if ($message != '')
					{
						$app->enqueueMessage($message, 'warning');

						return false;
					}
				}
				elseif ($fileError == 4)
				{
					if (isset($array['file_4']))
					{
						$this->file_4 = $array['file_4'];
					}
				}
				else
				{

					// Replace any special characters in the filename
					jimport('joomla.filesystem.file');
					$filename = JFile::stripExt($singleFile['name']);
					$extension = JFile::getExt($singleFile['name']);
					$filename = preg_replace("/[^A-Za-z0-9]/i", "-", $filename);
					$filename = $filename . '.' . $extension;
					$uploadPath = JPATH_ROOT . '/media/upload/' . $filename;
					$fileTemp = $singleFile['tmp_name'];

					if (!JFile::exists($uploadPath))
					{
						if (!JFile::upload($fileTemp, $uploadPath))
						{
							$app->enqueueMessage('Error moving file', 'warning');

							return false;
						}
					}

					$this->file_4 .= (!empty($this->file_4)) ? "," : "";
					$this->file_4 .= $filename;
				}
			}
		}
		else
		{
			$this->file_4 .= $array['file_4_hidden'];
		}
		// Support multi file field: file_5
		$app = JFactory::getApplication();
		$files = $app->input->files->get('jform', array(), 'raw');
		$array = $app->input->get('jform', array(), 'ARRAY');

		if ($files['file_5'][0]['size'] > 0)
		{
			// Deleting existing files
			$oldFiles = ProjectHelper::getFiles($this->id, $this->_tbl, 'file_5');

			foreach ($oldFiles as $f)
			{
				$oldFile = JPATH_ROOT . '/media/upload/' . $f;

				if (file_exists($oldFile) && !is_dir($oldFile))
				{
					unlink($oldFile);
				}
			}

			$this->file_5 = "";

			foreach ($files['file_5'] as $singleFile )
			{
				jimport('joomla.filesystem.file');

				// Check if the server found any error.
				$fileError = $singleFile['error'];
				$message = '';

				if ($fileError > 0 && $fileError != 4)
				{
					switch ($fileError)
					{
						case 1:
							$message = JText::_('File size exceeds allowed by the server');
							break;
						case 2:
							$message = JText::_('File size exceeds allowed by the html form');
							break;
						case 3:
							$message = JText::_('Partial upload error');
							break;
					}

					if ($message != '')
					{
						$app->enqueueMessage($message, 'warning');

						return false;
					}
				}
				elseif ($fileError == 4)
				{
					if (isset($array['file_5']))
					{
						$this->file_5 = $array['file_5'];
					}
				}
				else
				{

					// Replace any special characters in the filename
					jimport('joomla.filesystem.file');
					$filename = JFile::stripExt($singleFile['name']);
					$extension = JFile::getExt($singleFile['name']);
					$filename = preg_replace("/[^A-Za-z0-9]/i", "-", $filename);
					$filename = $filename . '.' . $extension;
					$uploadPath = JPATH_ROOT . '/media/upload/' . $filename;
					$fileTemp = $singleFile['tmp_name'];

					if (!JFile::exists($uploadPath))
					{
						if (!JFile::upload($fileTemp, $uploadPath))
						{
							$app->enqueueMessage('Error moving file', 'warning');

							return false;
						}
					}

					$this->file_5 .= (!empty($this->file_5)) ? "," : "";
					$this->file_5 .= $filename;
				}
			}
		}
		else
		{
			$this->file_5 .= $array['file_5_hidden'];
		}

		return parent::check();
	}

	/**
	 * Method to set the publishing state for a row or list of rows in the database
	 * table.  The method respects checked out rows by other users and will attempt
	 * to checkin rows that it can after adjustments are made.
	 *
	 * @param   mixed    $pks     An optional array of primary key values to update.  If not
	 *                            set the instance property value is used.
	 * @param   integer  $state   The publishing state. eg. [0 = unpublished, 1 = published]
	 * @param   integer  $userId  The user id of the user performing the operation.
	 *
	 * @return   boolean  True on success.
	 *
	 * @since    1.0.4
	 *
	 * @throws Exception
	 */
	public function publish($pks = null, $state = 1, $userId = 0)
	{
		// Initialise variables.
		$k = $this->_tbl_key;

		// Sanitize input.
		ArrayHelper::toInteger($pks);
		$userId = (int) $userId;
		$state  = (int) $state;

		// If there are no primary keys set check to see if the instance key is set.
		if (empty($pks))
		{
			if ($this->$k)
			{
				$pks = array($this->$k);
			}
			// Nothing to set publishing state on, return false.
			else
			{
				throw new Exception(500, JText::_('JLIB_DATABASE_ERROR_NO_ROWS_SELECTED'));
			}
		}

		// Build the WHERE clause for the primary keys.
		$where = $k . '=' . implode(' OR ' . $k . '=', $pks);

		// Determine if there is checkin support for the table.
		if (!property_exists($this, 'checked_out') || !property_exists($this, 'checked_out_time'))
		{
			$checkin = '';
		}
		else
		{
			$checkin = ' AND (checked_out = 0 OR checked_out = ' . (int) $userId . ')';
		}

		// Update the publishing state for rows with the given primary keys.
		$this->_db->setQuery(
			'UPDATE `' . $this->_tbl . '`' .
			' SET `state` = ' . (int) $state .
			' WHERE (' . $where . ')' .
			$checkin
		);
		$this->_db->execute();

		// If checkin is supported and all rows were adjusted, check them in.
		if ($checkin && (count($pks) == $this->_db->getAffectedRows()))
		{
			// Checkin each row.
			foreach ($pks as $pk)
			{
				$this->checkin($pk);
			}
		}

		// If the JTable instance value is in the list of primary keys that were set, set the instance.
		if (in_array($this->$k, $pks))
		{
			$this->state = $state;
		}

		return true;
	}

	/**
	 * Define a namespaced asset name for inclusion in the #__assets table
	 *
	 * @return string The asset name
	 *
	 * @see JTable::_getAssetName
	 */
	protected function _getAssetName()
	{
		$k = $this->_tbl_key;

		return 'com_project.projects.' . (int) $this->$k;
	}

	/**
	 * Returns the parent asset's id. If you have a tree structure, retrieve the parent's id using the external key field
	 *
	 * @param   JTable   $table  Table name
	 * @param   integer  $id     Id
	 *
	 * @see JTable::_getAssetParentId
	 *
	 * @return mixed The id on success, false on failure.
	 */
	protected function _getAssetParentId(JTable $table = null, $id = null)
	{
		// We will retrieve the parent-asset from the Asset-table
		$assetParent = JTable::getInstance('Asset');

		// Default: if no asset-parent can be found we take the global asset
		$assetParentId = $assetParent->getRootId();

		// The item has the component as asset-parent
		$assetParent->loadByName('com_project');

		// Return the found asset-parent-id
		if ($assetParent->id)
		{
			$assetParentId = $assetParent->id;
		}

		return $assetParentId;
	}

	/**
	 * Delete a record by id
	 *
	 * @param   mixed  $pk  Primary key value to delete. Optional
	 *
	 * @return bool
	 */
	public function delete($pk = null)
	{
		$this->load($pk);
		$result = parent::delete($pk);
		
		if ($result)
		{
			jimport('joomla.filesystem.file');

			foreach ($this->file_1 as $file_1File)
			{
				JFile::delete(JPATH_ROOT . '/media/upload/' . $file_1File);
			}
			jimport('joomla.filesystem.file');

			foreach ($this->file_2 as $file_2File)
			{
				JFile::delete(JPATH_ROOT . '/media/upload/' . $file_2File);
			}
			jimport('joomla.filesystem.file');

			foreach ($this->file_3 as $file_3File)
			{
				JFile::delete(JPATH_ROOT . '/media/upload/' . $file_3File);
			}
			jimport('joomla.filesystem.file');

			foreach ($this->file_4 as $file_4File)
			{
				JFile::delete(JPATH_ROOT . '/media/upload/' . $file_4File);
			}
			jimport('joomla.filesystem.file');

			foreach ($this->file_5 as $file_5File)
			{
				JFile::delete(JPATH_ROOT . '/media/upload/' . $file_5File);
			}
		}

		return $result;
	}
}
