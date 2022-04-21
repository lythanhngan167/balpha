<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Agent_images
 * @author     nganly <lythanhngan167@gmail.com>
 * @copyright  2020 nganly
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

use \Joomla\Utilities\ArrayHelper;
use \Joomla\CMS\Factory;
use \Joomla\CMS\Access\Access;
use \Joomla\CMS\Language\Text;
use \Joomla\CMS\Table\Table;

/**
 * agentimage Table class
 *
 * @since  1.6
 */
class Agent_imagesTableagentimage extends \Joomla\CMS\Table\Table
{
	
	/**
	 * Constructor
	 *
	 * @param   JDatabase  &$db  A database connector object
	 */
	public function __construct(&$db)
	{
		JObserverMapper::addObserverClassToClass('JTableObserverContenthistory', 'Agent_imagesTableagentimage', array('typeAlias' => 'com_agent_images.agentimage'));
		parent::__construct('#__agent_images', 'id', $db);
        $this->setColumnAlias('published', 'state');
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
     * @throws Exception
	 */
	public function bind($array, $ignore = '')
	{
	    $date = Factory::getDate();
		$task = Factory::getApplication()->input->get('task');
	    
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
		// Support for multi file field: image1
		if (!empty($array['image1']))
		{
			if (is_array($array['image1']))
			{
				$array['image1'] = implode(',', $array['image1']);
			}
			elseif (strpos($array['image1'], ',') != false)
			{
				$array['image1'] = explode(',', $array['image1']);
			}
		}
		else
		{
			$array['image1'] = '';
		}

		// Support for multi file field: image2
		if (!empty($array['image2']))
		{
			if (is_array($array['image2']))
			{
				$array['image2'] = implode(',', $array['image2']);
			}
			elseif (strpos($array['image2'], ',') != false)
			{
				$array['image2'] = explode(',', $array['image2']);
			}
		}
		else
		{
			$array['image2'] = '';
		}

		// Support for multi file field: image3
		if (!empty($array['image3']))
		{
			if (is_array($array['image3']))
			{
				$array['image3'] = implode(',', $array['image3']);
			}
			elseif (strpos($array['image3'], ',') != false)
			{
				$array['image3'] = explode(',', $array['image3']);
			}
		}
		else
		{
			$array['image3'] = '';
		}

		// Support for multi file field: image4
		if (!empty($array['image4']))
		{
			if (is_array($array['image4']))
			{
				$array['image4'] = implode(',', $array['image4']);
			}
			elseif (strpos($array['image4'], ',') != false)
			{
				$array['image4'] = explode(',', $array['image4']);
			}
		}
		else
		{
			$array['image4'] = '';
		}

		// Support for multi file field: image5
		if (!empty($array['image5']))
		{
			if (is_array($array['image5']))
			{
				$array['image5'] = implode(',', $array['image5']);
			}
			elseif (strpos($array['image5'], ',') != false)
			{
				$array['image5'] = explode(',', $array['image5']);
			}
		}
		else
		{
			$array['image5'] = '';
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

		if (!Factory::getUser()->authorise('core.admin', 'com_agent_images.agentimage.' . $array['id']))
		{
			$actions         = Access::getActionsFromFile(
				JPATH_ADMINISTRATOR . '/components/com_agent_images/access.xml',
				"/access/section[@name='agentimage']/"
			);
			$default_actions = Access::getAssetRules('com_agent_images.agentimage.' . $array['id'])->getData();
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
		
		
		// Support multi file field: image1
		$app = JFactory::getApplication();
		$files = $app->input->files->get('jform', array(), 'raw');
		$array = $app->input->get('jform', array(), 'ARRAY');

		if ($files['image1'][0]['size'] > 0)
		{
			// Deleting existing files
			$oldFiles = Agent_imagesHelper::getFiles($this->id, $this->_tbl, 'image1');

			foreach ($oldFiles as $f)
			{
				$oldFile = JPATH_ROOT . '/images/landingpage/' . $f;

				if (file_exists($oldFile) && !is_dir($oldFile))
				{
					unlink($oldFile);
				}
			}

			$this->image1 = "";

			foreach ($files['image1'] as $singleFile )
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
					if (isset($array['image1']))
					{
						$this->image1 = $array['image1'];
					}
				}
				else
				{
					// Check for filesize
					$fileSize = $singleFile['size'];

					if ($fileSize > 10485760)
					{
						$app->enqueueMessage('File bigger than 10MB', 'warning');

						return false;
					}

					// Check for filetype
					$okMIMETypes = 'image/jpeg,image/gif,image/png,image/bmp';
					$validMIMEArray = explode(',', $okMIMETypes);
					$fileMime = $singleFile['type'];

					if (!in_array($fileMime, $validMIMEArray))
					{
						$app->enqueueMessage('This filetype is not allowed', 'warning');

						return false;
					}

					// Replace any special characters in the filename
					jimport('joomla.filesystem.file');
					$filename = JFile::stripExt($singleFile['name']);
					$extension = JFile::getExt($singleFile['name']);
					$filename = preg_replace("/[^A-Za-z0-9]/i", "-", $filename);
					$filename = $filename . '.' . $extension;
					$uploadPath = JPATH_ROOT . '/images/landingpage/' . $filename;
					$fileTemp = $singleFile['tmp_name'];

					if (!JFile::exists($uploadPath))
					{
						if (!JFile::upload($fileTemp, $uploadPath))
						{
							$app->enqueueMessage('Error moving file', 'warning');

							return false;
						}
					}

					$this->image1 .= (!empty($this->image1)) ? "," : "";
					$this->image1 .= $filename;
				}
			}
		}
		else
		{
			$this->image1 .= $array['image1_hidden'];
		}
		// Support multi file field: image2
		$app = JFactory::getApplication();
		$files = $app->input->files->get('jform', array(), 'raw');
		$array = $app->input->get('jform', array(), 'ARRAY');

		if ($files['image2'][0]['size'] > 0)
		{
			// Deleting existing files
			$oldFiles = Agent_imagesHelper::getFiles($this->id, $this->_tbl, 'image2');

			foreach ($oldFiles as $f)
			{
				$oldFile = JPATH_ROOT . '/images/landingpage/' . $f;

				if (file_exists($oldFile) && !is_dir($oldFile))
				{
					unlink($oldFile);
				}
			}

			$this->image2 = "";

			foreach ($files['image2'] as $singleFile )
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
					if (isset($array['image2']))
					{
						$this->image2 = $array['image2'];
					}
				}
				else
				{
					// Check for filesize
					$fileSize = $singleFile['size'];

					if ($fileSize > 10485760)
					{
						$app->enqueueMessage('File bigger than 10MB', 'warning');

						return false;
					}

					// Check for filetype
					$okMIMETypes = 'image/jpeg,image/gif,image/png,image/bmp';
					$validMIMEArray = explode(',', $okMIMETypes);
					$fileMime = $singleFile['type'];

					if (!in_array($fileMime, $validMIMEArray))
					{
						$app->enqueueMessage('This filetype is not allowed', 'warning');

						return false;
					}

					// Replace any special characters in the filename
					jimport('joomla.filesystem.file');
					$filename = JFile::stripExt($singleFile['name']);
					$extension = JFile::getExt($singleFile['name']);
					$filename = preg_replace("/[^A-Za-z0-9]/i", "-", $filename);
					$filename = $filename . '.' . $extension;
					$uploadPath = JPATH_ROOT . '/images/landingpage/' . $filename;
					$fileTemp = $singleFile['tmp_name'];

					if (!JFile::exists($uploadPath))
					{
						if (!JFile::upload($fileTemp, $uploadPath))
						{
							$app->enqueueMessage('Error moving file', 'warning');

							return false;
						}
					}

					$this->image2 .= (!empty($this->image2)) ? "," : "";
					$this->image2 .= $filename;
				}
			}
		}
		else
		{
			$this->image2 .= $array['image2_hidden'];
		}
		// Support multi file field: image3
		$app = JFactory::getApplication();
		$files = $app->input->files->get('jform', array(), 'raw');
		$array = $app->input->get('jform', array(), 'ARRAY');

		if ($files['image3'][0]['size'] > 0)
		{
			// Deleting existing files
			$oldFiles = Agent_imagesHelper::getFiles($this->id, $this->_tbl, 'image3');

			foreach ($oldFiles as $f)
			{
				$oldFile = JPATH_ROOT . '/images/landingpage/' . $f;

				if (file_exists($oldFile) && !is_dir($oldFile))
				{
					unlink($oldFile);
				}
			}

			$this->image3 = "";

			foreach ($files['image3'] as $singleFile )
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
					if (isset($array['image3']))
					{
						$this->image3 = $array['image3'];
					}
				}
				else
				{
					// Check for filesize
					$fileSize = $singleFile['size'];

					if ($fileSize > 10485760)
					{
						$app->enqueueMessage('File bigger than 10MB', 'warning');

						return false;
					}

					// Check for filetype
					$okMIMETypes = 'image/jpeg,image/gif,image/png,image/bmp';
					$validMIMEArray = explode(',', $okMIMETypes);
					$fileMime = $singleFile['type'];

					if (!in_array($fileMime, $validMIMEArray))
					{
						$app->enqueueMessage('This filetype is not allowed', 'warning');

						return false;
					}

					// Replace any special characters in the filename
					jimport('joomla.filesystem.file');
					$filename = JFile::stripExt($singleFile['name']);
					$extension = JFile::getExt($singleFile['name']);
					$filename = preg_replace("/[^A-Za-z0-9]/i", "-", $filename);
					$filename = $filename . '.' . $extension;
					$uploadPath = JPATH_ROOT . '/images/landingpage/' . $filename;
					$fileTemp = $singleFile['tmp_name'];

					if (!JFile::exists($uploadPath))
					{
						if (!JFile::upload($fileTemp, $uploadPath))
						{
							$app->enqueueMessage('Error moving file', 'warning');

							return false;
						}
					}

					$this->image3 .= (!empty($this->image3)) ? "," : "";
					$this->image3 .= $filename;
				}
			}
		}
		else
		{
			$this->image3 .= $array['image3_hidden'];
		}
		// Support multi file field: image4
		$app = JFactory::getApplication();
		$files = $app->input->files->get('jform', array(), 'raw');
		$array = $app->input->get('jform', array(), 'ARRAY');

		if ($files['image4'][0]['size'] > 0)
		{
			// Deleting existing files
			$oldFiles = Agent_imagesHelper::getFiles($this->id, $this->_tbl, 'image4');

			foreach ($oldFiles as $f)
			{
				$oldFile = JPATH_ROOT . '/images/landingpage/' . $f;

				if (file_exists($oldFile) && !is_dir($oldFile))
				{
					unlink($oldFile);
				}
			}

			$this->image4 = "";

			foreach ($files['image4'] as $singleFile )
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
					if (isset($array['image4']))
					{
						$this->image4 = $array['image4'];
					}
				}
				else
				{
					// Check for filesize
					$fileSize = $singleFile['size'];

					if ($fileSize > 10485760)
					{
						$app->enqueueMessage('File bigger than 10MB', 'warning');

						return false;
					}

					// Check for filetype
					$okMIMETypes = 'image/jpeg,image/gif,image/png,image/bmp';
					$validMIMEArray = explode(',', $okMIMETypes);
					$fileMime = $singleFile['type'];

					if (!in_array($fileMime, $validMIMEArray))
					{
						$app->enqueueMessage('This filetype is not allowed', 'warning');

						return false;
					}

					// Replace any special characters in the filename
					jimport('joomla.filesystem.file');
					$filename = JFile::stripExt($singleFile['name']);
					$extension = JFile::getExt($singleFile['name']);
					$filename = preg_replace("/[^A-Za-z0-9]/i", "-", $filename);
					$filename = $filename . '.' . $extension;
					$uploadPath = JPATH_ROOT . '/images/landingpage/' . $filename;
					$fileTemp = $singleFile['tmp_name'];

					if (!JFile::exists($uploadPath))
					{
						if (!JFile::upload($fileTemp, $uploadPath))
						{
							$app->enqueueMessage('Error moving file', 'warning');

							return false;
						}
					}

					$this->image4 .= (!empty($this->image4)) ? "," : "";
					$this->image4 .= $filename;
				}
			}
		}
		else
		{
			$this->image4 .= $array['image4_hidden'];
		}
		// Support multi file field: image5
		$app = JFactory::getApplication();
		$files = $app->input->files->get('jform', array(), 'raw');
		$array = $app->input->get('jform', array(), 'ARRAY');

		if ($files['image5'][0]['size'] > 0)
		{
			// Deleting existing files
			$oldFiles = Agent_imagesHelper::getFiles($this->id, $this->_tbl, 'image5');

			foreach ($oldFiles as $f)
			{
				$oldFile = JPATH_ROOT . '/images/landingpage/' . $f;

				if (file_exists($oldFile) && !is_dir($oldFile))
				{
					unlink($oldFile);
				}
			}

			$this->image5 = "";

			foreach ($files['image5'] as $singleFile )
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
					if (isset($array['image5']))
					{
						$this->image5 = $array['image5'];
					}
				}
				else
				{
					// Check for filesize
					$fileSize = $singleFile['size'];

					if ($fileSize > 10485760)
					{
						$app->enqueueMessage('File bigger than 10MB', 'warning');

						return false;
					}

					// Check for filetype
					$okMIMETypes = 'image/jpeg,image/gif,image/png,image/bmp';
					$validMIMEArray = explode(',', $okMIMETypes);
					$fileMime = $singleFile['type'];

					if (!in_array($fileMime, $validMIMEArray))
					{
						$app->enqueueMessage('This filetype is not allowed', 'warning');

						return false;
					}

					// Replace any special characters in the filename
					jimport('joomla.filesystem.file');
					$filename = JFile::stripExt($singleFile['name']);
					$extension = JFile::getExt($singleFile['name']);
					$filename = preg_replace("/[^A-Za-z0-9]/i", "-", $filename);
					$filename = $filename . '.' . $extension;
					$uploadPath = JPATH_ROOT . '/images/landingpage/' . $filename;
					$fileTemp = $singleFile['tmp_name'];

					if (!JFile::exists($uploadPath))
					{
						if (!JFile::upload($fileTemp, $uploadPath))
						{
							$app->enqueueMessage('Error moving file', 'warning');

							return false;
						}
					}

					$this->image5 .= (!empty($this->image5)) ? "," : "";
					$this->image5 .= $filename;
				}
			}
		}
		else
		{
			$this->image5 .= $array['image5_hidden'];
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
				throw new Exception(500, Text::_('JLIB_DATABASE_ERROR_NO_ROWS_SELECTED'));
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
	 * @see Table::_getAssetName
	 */
	protected function _getAssetName()
	{
		$k = $this->_tbl_key;

		return 'com_agent_images.agentimage.' . (int) $this->$k;
	}

	/**
	 * Returns the parent asset's id. If you have a tree structure, retrieve the parent's id using the external key field
	 *
	 * @param   JTable   $table  Table name
	 * @param   integer  $id     Id
	 *
	 * @see Table::_getAssetParentId
	 *
	 * @return mixed The id on success, false on failure.
	 */
	protected function _getAssetParentId(JTable $table = null, $id = null)
	{
		// We will retrieve the parent-asset from the Asset-table
		$assetParent = Table::getInstance('Asset');

		// Default: if no asset-parent can be found we take the global asset
		$assetParentId = $assetParent->getRootId();

		// The item has the component as asset-parent
		$assetParent->loadByName('com_agent_images');

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

			$checkImageVariableType = gettype($this->image1);

			switch ($checkImageVariableType)
			{
			case 'string':
				JFile::delete(JPATH_ROOT . '/images/landingpage/' . $this->image1);
			break;
			default:
			foreach ($this->image1 as $image1File)
			{
				JFile::delete(JPATH_ROOT . '/images/landingpage/' . $image1File);
			}
			}
			jimport('joomla.filesystem.file');

			$checkImageVariableType = gettype($this->image2);

			switch ($checkImageVariableType)
			{
			case 'string':
				JFile::delete(JPATH_ROOT . '/images/landingpage/' . $this->image2);
			break;
			default:
			foreach ($this->image2 as $image2File)
			{
				JFile::delete(JPATH_ROOT . '/images/landingpage/' . $image2File);
			}
			}
			jimport('joomla.filesystem.file');

			$checkImageVariableType = gettype($this->image3);

			switch ($checkImageVariableType)
			{
			case 'string':
				JFile::delete(JPATH_ROOT . '/images/landingpage/' . $this->image3);
			break;
			default:
			foreach ($this->image3 as $image3File)
			{
				JFile::delete(JPATH_ROOT . '/images/landingpage/' . $image3File);
			}
			}
			jimport('joomla.filesystem.file');

			$checkImageVariableType = gettype($this->image4);

			switch ($checkImageVariableType)
			{
			case 'string':
				JFile::delete(JPATH_ROOT . '/images/landingpage/' . $this->image4);
			break;
			default:
			foreach ($this->image4 as $image4File)
			{
				JFile::delete(JPATH_ROOT . '/images/landingpage/' . $image4File);
			}
			}
			jimport('joomla.filesystem.file');

			$checkImageVariableType = gettype($this->image5);

			switch ($checkImageVariableType)
			{
			case 'string':
				JFile::delete(JPATH_ROOT . '/images/landingpage/' . $this->image5);
			break;
			default:
			foreach ($this->image5 as $image5File)
			{
				JFile::delete(JPATH_ROOT . '/images/landingpage/' . $image5File);
			}
			}
		}

		return $result;
	}
}
