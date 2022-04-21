<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Recharge
 * @author     nganly <lythanhngan167@gmail.com>
 * @copyright  2019 nganly
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
 * recharge Table class
 *
 * @since  1.6
 */
class RechargeTablerecharge extends \Joomla\CMS\Table\Table
{

	/**
	 * Constructor
	 *
	 * @param   JDatabase  &$db  A database connector object
	 */
	public function __construct(&$db)
	{
		JObserverMapper::addObserverClassToClass('JTableObserverContenthistory', 'RechargeTablerecharge', array('typeAlias' => 'com_recharge.recharge'));
		parent::__construct('#__recharge', 'id', $db);
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

		$app = JFactory::getApplication();

		if(!isset($_GET['id'])){
			$isExist = $this->checkCodeRecharge($array['code']);
			if($isExist){
				$message = 'Mã nạp BizXu đã tồn tại, vui lòng thử lại.';
				$app->enqueueMessage($message, 'error');
				return;
			}
		}

		if ($array['id'] == 0 && empty($array['created_by']))
		{
			$array['created_by'] = JFactory::getUser()->id;
		}

		if ($array['id'] == 0){
			if($array['sale'] > 0){
				$array['created_by'] = $array['sale'];
				$this->updateMoney($array['sale'],$array['amount'],$array['code']);
			}
		}

		if ($array['id'] == 0 && empty($array['modified_by']))
		{
			$array['modified_by'] = JFactory::getUser()->id;
		}

		if ($task == 'apply' || $task == 'save')
		{
			$array['modified_by'] = JFactory::getUser()->id;
		}

		// Support for multiple field: status
		if (isset($array['status']))
		{
			if (is_array($array['status']))
			{
				$array['status'] = implode(',',$array['status']);
			}
			elseif (strpos($array['status'], ',') != false)
			{
				$array['status'] = explode(',',$array['status']);
			}
			elseif (strlen($array['status']) == 0)
			{
				$array['status'] = '';
			}
		}
		else
		{
			$array['status'] = '';
		}
		// Support for multi file field: image
		if (!empty($array['image']))
		{
			if (is_array($array['image']))
			{
				$array['image'] = implode(',', $array['image']);
			}
			elseif (strpos($array['image'], ',') != false)
			{
				$array['image'] = explode(',', $array['image']);
			}
		}
		else
		{
			$array['image'] = '';
		}


		if ($array['id'] == 0)
		{
			//$array['created_time'] = $date->toSql();
			if($array['created_time'] == ''){
				$array['created_time'] = $date->toSql();
			}else{

			}

		}

		// Support for multiple field: type
		if (isset($array['type']))
		{
			if (is_array($array['type']))
			{
				$array['type'] = implode(',',$array['type']);
			}
			elseif (strpos($array['type'], ',') != false)
			{
				$array['type'] = explode(',',$array['type']);
			}
			elseif (strlen($array['type']) == 0)
			{
				$array['type'] = '';
			}
		}
		else
		{
			$array['type'] = '';
		}

		if ($task == 'apply' || $task == 'save')
		{
			$array['updated_time'] = $date->toSql();
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

		if (!Factory::getUser()->authorise('core.admin', 'com_recharge.recharge.' . $array['id']))
		{
			$actions         = Access::getActionsFromFile(
				JPATH_ADMINISTRATOR . '/components/com_recharge/access.xml',
				"/access/section[@name='recharge']/"
			);
			$default_actions = Access::getAssetRules('com_recharge.recharge.' . $array['id'])->getData();
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


		// Support for checkboxes field: bank_name
		$this->bank_name = implode(',', (array) $this->bank_name);
		// Support multi file field: image
		$app = JFactory::getApplication();
		$files = $app->input->files->get('jform', array(), 'raw');
		$array = $app->input->get('jform', array(), 'ARRAY');

		if ($files['image'][0]['size'] > 0)
		{
			// Deleting existing files
			$oldFiles = RechargeHelper::getFiles($this->id, $this->_tbl, 'image');

			foreach ($oldFiles as $f)
			{
				$oldFile = JPATH_ROOT . '/images/banking/' . $f;

				if (file_exists($oldFile) && !is_dir($oldFile))
				{
					unlink($oldFile);
				}
			}

			$this->image = "";

			foreach ($files['image'] as $singleFile )
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
					if (isset($array['image']))
					{
						$this->image = $array['image'];
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

					// Replace any special characters in the filename
					jimport('joomla.filesystem.file');
					$filename = JFile::stripExt($singleFile['name']);
					$extension = JFile::getExt($singleFile['name']);
					$filename = preg_replace("/[^A-Za-z0-9]/i", "-", $filename);
					$filename = $filename . '.' . $extension;
					$uploadPath = JPATH_ROOT . '/images/banking/' . $filename;
					$fileTemp = $singleFile['tmp_name'];

					if (!JFile::exists($uploadPath))
					{
						if (!JFile::upload($fileTemp, $uploadPath))
						{
							$app->enqueueMessage('Error moving file', 'warning');

							return false;
						}
					}

					$this->image .= (!empty($this->image)) ? "," : "";
					$this->image .= $filename;
				}
			}
		}
		else
		{
			$this->image .= $array['image_hidden'];
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

		return 'com_recharge.recharge.' . (int) $this->$k;
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
		$assetParent->loadByName('com_recharge');

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

			foreach ($this->image as $imageFile)
			{
				JFile::delete(JPATH_ROOT . '/images/banking/' . $imageFile);
			}
		}

		return $result;
	}
	public function updateMoney($user_id,$money,$code){
		if($user_id > 0){
			$userSaleHistory   = $this->getUserByID($user_id);
      // inscrease money
      $db = JFactory::getDbo();
      $sql = "UPDATE #__users set money = money + " . (int)$money . ' WHERE id = ' . $user_id;
      $change_money = $db->setQuery($sql)->execute();
      if($change_money){
        $user = JFactory::getUser();
        $obj = new stdClass();
        $obj->state = 1;
        $obj->created_by = $user_id;
        $obj->title = 'Nạp BizXu #' . $code;
        $obj->amount = $money;
        $obj->created_date = date('Y-m-d H:i:s');
        $obj->type_transaction = 'charge';
        $obj->status = 'completed';
        $obj->reference_id = $code;

				if($user_id > 0){
					$obj->current_money = $userSaleHistory->money + $money;
					$obj->current_money_before_operation = $userSaleHistory->money;
				}

        $db->insertObject('#__transaction_history', $obj, 'id');
      }
    }
	}

	public function checkCodeRecharge($code){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('id');
		$query->from($db->quoteName('#__recharge'));
		$query->where($db->quoteName('code') . " = '" .$code."'");
		$query->order('id DESC');
		$query->setLimit(1);
		$db->setQuery($query);
		$result = $db->loadResult();
		$isExist = 0;
		if($result > 0){
			$isExist = 1;
		}else{
			$isExist = 0;
		}
		return $isExist;
	}

	public function getUserByID($user_id)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from($db->quoteName('#__users'));
		$query->where($db->quoteName('id') . " = '" .$user_id."'");
		$db->setQuery($query);
		$result = $db->loadObject();
		return $result;
	}

}
