<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Notification
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
 * notification Table class
 *
 * @since  1.6
 */
class NotificationTablenotification extends \Joomla\CMS\Table\Table
{

	/**
	 * Constructor
	 *
	 * @param   JDatabase  &$db  A database connector object
	 */
	public function __construct(&$db)
	{
		JObserverMapper::addObserverClassToClass('JTableObserverContenthistory', 'NotificationTablenotification', array('typeAlias' => 'com_notification.notification'));
		parent::__construct('#__notification', 'id', $db);
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

		if (!Factory::getUser()->authorise('core.admin', 'com_notification.notification.' . $array['id']))
		{
			$actions         = Access::getActionsFromFile(
				JPATH_ADMINISTRATOR . '/components/com_notification/access.xml',
				"/access/section[@name='notification']/"
			);
			$default_actions = Access::getAssetRules('com_notification.notification.' . $array['id'])->getData();
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

	public function getUsersWithGroup($group_id)
	{
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('u.id');
			$query->from('`#__users` AS u');
			$query->join('LEFT', $db->quoteName('#__user_usergroup_map', 'm') . ' ON (' . $db->quoteName('m.user_id') . ' = ' . $db->quoteName('u.id') . ')');
			if ($group_id == 3) {
				$query->where('m.`group_id` = 3');
			}
			if ($group_id == 2) {
				$query->where('m.`group_id` = 2');
			}
			if ($group_id == 10000) {
				$query->where('m.`group_id` IN (2,3)');
			}
			$query->where("u.block = 0");
			$db->setQuery($query);
			$results = $db->loadObjectList();
			return $results;
	}

	public function getListDevice($list_user_id){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('ud.device_id');
		$query->from('`#__user_devices` AS ud');
		$query->where('ud.`user_id` IN ('.$list_user_id.')');
		$db->setQuery($query);
		$row = $db->loadObjectList();
		return $row;
	}

	public function check()
	{
		// If there is an ordering column and this is a new row then get the next ordering value
		if (property_exists($this, 'ordering') && $this->id == 0)
		{
			$this->ordering = self::getNextOrder();
		}

		// echo $title_notification." -- ".$content_notification." -- ".$tag_key." -- ".$tag_value;
		if ($_SERVER['HTTP_HOST'] != "localhost") {
			$title_notification = $this->title;
			$content_notification = $this->message;
			$page_app = $this->page_app;
			$tag_key = '';
			$tag_value = '';

			// if($this->category == NOTI_CUSTOMER_GROUP || $this->category == NOTI_AGENT_GROUP || $this->category == NOTI_ALL_GROUP){
			// 	$tag_value = '';
			// 	$segments = array('Subscribed Users');
			// }
			// if($this->category == NOTI_TESTER_GROUP){
			// 	$tag_value = '';
			// 	$segments = array('Tester');
			// }


			$group_id = 0;

			if($this->category == NOTI_CUSTOMER_GROUP){
				$group_id = 2;
			}
			if($this->category == NOTI_AGENT_GROUP){
				$group_id = 3;
			}

			if($this->category == NOTI_ALL_GROUP){
				$group_id = 10000;
			}


			if(	$group_id > 0){
				$listUser = $this->getUsersWithGroup($group_id);
				$arrayIDUser = array();
				foreach ($listUser as $key => $noti_user) {
					$arrayIDUser[] = $noti_user->id;
				}
				$listID =  implode(",",$arrayIDUser);
				$listDevice = $this->getListDevice($listID);
				$arrayDevicesID = array();
				foreach ($listDevice as $key => $row) {
				  $arrayDevicesID[] = $row->device_id;
				}
				// echo "<pre>";
				// print_r($arrayDevicesID);
				// echo "</pre>";
				// die();
			}

			// $arrayDevicesID = array();
			// $arrayDevicesID = array('5f36b9f6-3db3-44f9-b4a2-7106fa701439');
			$this->sendMessageOnesignalNotification($title_notification,$content_notification,$tag_key,$tag_value,$segments,$page_app,$arrayDevicesID);
		}


		return parent::check();
	}

	public function sendMessageOnesignalNotification($title,$content,$tag_key,$tag_value,$segments,$page_app,$arrayDevicesID){
			$content = array(
				"vi" => $content,
				"en" => $content
				);
			$headings = array(
				"vi" => $title,
				"en" => $title
				);
			// $daTags = array(
      // array("key" => $tag_key, "relation" => "=", "value" => $tag_value),
      // );
			// $filters = array(
			//     array("field" => "tag", "key" => $tag_key, "relation" => "=", "value" => $tag_value),
			// );
			$app_url = 'ebiznet://bizappco/NotifyPage/0';

			if(strlen($page_app) > 23){
				$app_url = trim($page_app);
			}
			$rest_api_key = REST_API_KEY;
			$fields = array(
				'app_id' => APP_ID,
				'include_player_ids' =>  $arrayDevicesID,
				//'included_segments' => $segments,
				'data' => array("foo" => "bar"),
				//'filters' => array(array("field" => "tag", "key" => "user_type", "relation" => "equal", "value" => "factory")),
				//"tags" => $daTags,
				//"filters" => $filters,
				'app_url' => $app_url,
				'contents' => $content,
				'headings' => $headings
			);

			$fields = json_encode($fields);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8','Authorization: Basic ' . $rest_api_key));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_HEADER, FALSE);
			curl_setopt($ch, CURLOPT_POST, TRUE);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

			$response = curl_exec($ch);
			curl_close($ch);
			return $response;
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

		return 'com_notification.notification.' . (int) $this->$k;
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
		$assetParent->loadByName('com_notification');

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

		return $result;
	}
}
