<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\Utilities\ArrayHelper;

/**
 * Users list controller class.
 *
 * @since  1.6
 */
class UsersControllerUsers extends JControllerAdmin
{
	/**
	 * @var    string  The prefix to use with controller messages.
	 * @since  1.6
	 */
	protected $text_prefix = 'COM_USERS_USERS';

	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @since   1.6
	 * @see     JController
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);

		$this->registerTask('block', 'changeBlock');
		$this->registerTask('unblock', 'changeBlock');
		$this->registerTask('blocklanding', 'changeBlockLanding');
		$this->registerTask('unblocklanding', 'changeBlockLanding');
	}

	/**
	 * Proxy for getModel.
	 *
	 * @param   string  $name    The model name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  object  The model.
	 *
	 * @since   1.6
	 */
	public function getModel($name = 'User', $prefix = 'UsersModel', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}


	/**
	 * Method to change the block status on a record.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	public function changeBlockLanding()
	{
		// Check for request forgeries.
		$this->checkToken();

		$ids    = $this->input->get('cid', array(), 'array');
		$values = array('blocklanding' => 1, 'unblocklanding' => 0);
		$task   = $this->getTask();
		$value  = ArrayHelper::getValue($values, $task, 0, 'int');

		if (empty($ids))
		{
			JError::raiseWarning(500, JText::_('COM_USERS_USERS_NO_ITEM_SELECTED'));
		}
		else
		{
			// Get the model.
			$model = $this->getModel();

			// Change the state of the records.
			if (!$model->blockLanding($ids, $value))
			{
				JError::raiseWarning(500, $model->getError());
			}
			else
			{
				if ($value == 1)
				{
					$this->setMessage(JText::plural('COM_USERS_N_USERS_BLOCKED_LANDINGPAGE', count($ids)));
				}
				elseif ($value == 0)
				{
					$this->setMessage(JText::plural('COM_USERS_N_USERS_UNBLOCKED_LANDINGPAGE', count($ids)));


					$db = JFactory::getDbo();
					$query = $db->getQuery(true);

					$query->select('block_landingpage_user,lpage_date');
					$query->from('#__users');
					$query->where('id='. $ids[0]);
					$db->setQuery($query);
					$result = $db->loadObject();

					date_default_timezone_set('Asia/Ho_Chi_Minh');
					if($result->block_landingpage_user == 0){ // nguoi bat Landingpage lan dau
						$oUser1 = new stdClass();
						$oUser1->id = $ids[0];
						$loggedUser = JFactory::getUser();
						$oUser1->block_landingpage_user = $loggedUser->id;
						$query->clear();
						$updateResult = $db->updateObject('#__users', $oUser1, 'id');
					}

					if($result->lpage_date == null) { // bat Landingpage lan dau
						$oUser = new stdClass();
						$oUser->id = $ids[0];
						$oUser->lpage_date = date('Y-m-d H:i:s');
						$query->clear();
						$updateResult = $db->updateObject('#__users', $oUser, 'id');
						if(!$updateResult) {
							JError::raiseWarning(500, "Lỗi không cập nhật thành công");
						}
					}
				}

			}
		}

		$this->setRedirect('index.php?option=com_users&view=users');
	}

	/**
	 * Method to change the block status on a record.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	public function changeBlock()
	{
		// Check for request forgeries.
		$this->checkToken();

		$ids    = $this->input->get('cid', array(), 'array');
		$values = array('block' => 1, 'unblock' => 0);
		$task   = $this->getTask();
		$value  = ArrayHelper::getValue($values, $task, 0, 'int');

		if (empty($ids))
		{
			JError::raiseWarning(500, JText::_('COM_USERS_USERS_NO_ITEM_SELECTED'));
		}
		else
		{
			// Get the model.
			$model = $this->getModel();

			// Change the state of the records.
			if (!$model->block($ids, $value))
			{
				JError::raiseWarning(500, $model->getError());
			}
			else
			{
				if ($value == 1)
				{
					$this->setMessage(JText::plural('COM_USERS_N_USERS_BLOCKED', count($ids)));
				}
				elseif ($value == 0)
				{
					$this->setMessage(JText::plural('COM_USERS_N_USERS_UNBLOCKED', count($ids)));
				}
			}
		}

		$this->setRedirect('index.php?option=com_users&view=users');
	}

	/**
	 * Method to activate a record.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	public function activate()
	{
		// Check for request forgeries.
		$this->checkToken();

		$ids = $this->input->get('cid', array(), 'array');

		if (empty($ids))
		{
			JError::raiseWarning(500, JText::_('COM_USERS_USERS_NO_ITEM_SELECTED'));
		}
		else
		{
			// Get the model.
			$model = $this->getModel();

			// Change the state of the records.
			if (!$model->activate($ids))
			{
				JError::raiseWarning(500, $model->getError());
			}
			else
			{
				$this->setMessage(JText::plural('COM_USERS_N_USERS_ACTIVATED', count($ids)));
			}
		}

		$this->setRedirect('index.php?option=com_users&view=users');
	}

	public function listUserByLevel($list = '1,2,3')
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('a.*');
		$query->from($db->quoteName('#__users','a'));

		$query->join('LEFT', '#__user_usergroup_map AS ug ON ug.user_id = a.id');
		$query->where("ug.group_id = 3");

		$query->where('a.level IN ('.$list.')');
		$query->where('a.block = 0');
		$query->order('a.id DESC');
		$db->setQuery($query);

		$result = $db->loadObjectList();

		//echo "<pre>";
		//echo count($result);
		//print_r($result);
		//echo "</pre>";
		//echo $query->__toString();
		return $result;
	}

	public function countCustomerByAgent($user_id)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('count(*)');
		$query->from($db->quoteName('#__customers','a'));
		$query->where('a.state = 1');
		$query->where('a.status_id <> 99');
		$query->where('a.sale_id = '.$user_id);
		$query->where('a.is_tranfer = 0');
		$db->setQuery($query);
		//echo $query->__toString();
		$result = $db->loadResult();
		return $result;
	}

	public function updateCustomerNoTranferForUser()
	{
		$listUser = $this->listUserByLevel('1,2,3,4,5');
		foreach($listUser as $user){
			$numberCustomer = 0;
			$numberCustomer = $this->countCustomerByAgent($user->id);
			$this->updateCountCustomerNoTranfer($user->id,$numberCustomer);
			// echo $user->id."==".$user->level."==".$numberCustomer;
			// echo "<br>";
		}
	}

	public function updateCountCustomerNoTranfer($user_id, $count_number){
		$db = JFactory::getDbo();
		$oUser = new stdClass();
		$oUser->id = $user_id;
		$oUser->count_no_tranfer = $count_number;
		$updateResult = $db->updateObject('#__users', $oUser, 'id');
	}

	public function upgradeAgentToLevel($user_id,$buyall){
		$config = new JConfig();
		if($config->numberDataLevel1 != ''){
			$arrFromTo1 = explode("-",$config->numberDataLevel1);
		}
		if($config->numberDataLevel2 != ''){
			$arrFromTo2 = explode("-",$config->numberDataLevel2);
		}
		if($config->numberDataLevel3 != ''){
			$arrFromTo3 = explode("-",$config->numberDataLevel3);
		}
		$to_level = 0;
		if($buyall >= (int)$arrFromTo1[0] && $buyall <= (int)$arrFromTo1[1]){
			$to_level = 1;
		}
		if($buyall >= (int)$arrFromTo2[0] && $buyall <= (int)$arrFromTo2[1]){
			$to_level = 2;
		}
		if($buyall >= (int)$arrFromTo3[0] && $buyall <= (int)$arrFromTo3[1]){
			$to_level = 3;
		}
		$user   = $this->getUserByID($user_id);
		$current_level = $user->level;
		if($to_level > 0){
			$object = new stdClass();
			$object->id = $user_id;
			$object->level = $to_level;
			$object->upgraded_date = date("Y-m-d H:i:s");
			$result = JFactory::getDbo()->updateObject('#__users', $object, 'id');
			if($result){
				$log 				= new stdClass();
				$log->type			= LEVEL_UPDATE;
				$log->created_by 	= (int)$user_id;
				$log->modified_by 	= (int)$user_id;
				$log->status		= 1;
				$log->user_id		= (int)$user_id;
				$log->old_level	= $current_level;
				$log->new_level	= $to_level;
				$log->state			= 1;
				$log->created_date	= date('Y-m-d H:i:s');
				$result2 = JFactory::getDbo()->insertObject('#__userlogs', $log);
			}
		}
	}


	public function upgradeLevelUser(){
		$listUser = $this->listUserByLevel('1,2,3');
		foreach($listUser as $user){
			$this->upgradeAgentToLevel($user->id,$user->buyall);
		}
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


	public function readExcelPortal()
	{
		require_once 'Classes/PHPExcel.php';
		$path_file = JPATH_SITE.DIRECTORY_SEPARATOR."media".DIRECTORY_SEPARATOR."upload".DIRECTORY_SEPARATOR."employees.xls";
		$filename = $path_file;
		$inputFileType = PHPExcel_IOFactory::identify($filename);
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);

		$objReader->setReadDataOnly(true);

		/**  Load $inputFileName to a PHPExcel Object  **/
		$objPHPExcel    = $objReader->load("$filename");
		$objWorksheet  = $objPHPExcel->setActiveSheetIndex(0);
		$highestRow    = $objWorksheet->getHighestRow();
		$highestColumn = $objWorksheet->getHighestColumn();
		$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);

		$arrayError = array();
		for ($row = 3; $row <= $highestRow; $row++){
			$params = array(
				'ordering' => '',
				'id_biznet_sponsor' => '',
				'id_biznet' => '',
				'name' => '',
				'level' => '',
				'phone' => '',
				'email' => ''
			);
			for ($col = 0; $col < $highestColumnIndex;++$col)
			{
				$value = $objWorksheet->getCellByColumnAndRow($col, $row)->getValue();

				if($value){
					//$arraydata[$row-1][$col] = trim($value," ");
					if($col == 0){
						$params['ordering'] = trim($value," ");
					}
					if($col == 1){
						$params['id_biznet_sponsor']	= trim($value," ");
					}
					if($col == 2){
						$params['id_biznet']	= trim($value," ");
					}
					if($col == 3){
						$params['name'] = trim($value," ");
					}
					if($col == 4){
						$params['level'] = trim($value," ");
					}
					if($col == 6){
						$params['phone'] = trim($value," ");
						$params['phone'] = str_replace('"', '', $params['phone']);
						$params['phone'] = str_replace('=', '', $params['phone']);
					}
					if($col == 7){
						$params['email'] = trim($value," ");
					}
				}
			}
			// print_r($params);
			// echo "<br>";
			if($params != '' && !empty($params) && count($params) > 0){
				if($params['id_biznet'] != '' && $params['id_biznet_sponsor'] != ''){
					$ok = $this->importExcelPortal($params);
					if($ok == 0){
						$arrayError[] = 'Tên khách hàng: '.$params['id_biznet'].' - Số điện thoại: '.$params['phone'] .' đã tồn tại.';
					}
				}
			}
		}

		if(count($arrayError) > 0){
			echo "<h3>Đã thực hiện Import Excel</h3>";
			echo "<p style='color: #ff0000; font-weight:bold;'>Danh sách khách hàng không import được:</p>";
			echo '<p  style="color:orange;">Lý do: Số điện thoại đã có trong hệ thống, Danh mục không tồn tại hoặc Dự án không tồn tại !</p>';
			foreach($arrayError as $aError){
				echo $aError."<br>";
			}
			echo "<br><br>";

			return false;
		}

		return true;
	}

	public function importExcelPortal($params){
		$db = JFactory::getDbo();
		// Insert columns.
		$created_date = date("Y-m-d H:i:s");
		//JFactory::getDate('now', JFactory::getConfig()->get('offset'))->toSql(true);
		$customer = new stdClass();
		$customer->id_biznet_sponsor = $params['id_biznet_sponsor'];
		$customer->id_biznet = $params['id_biznet'];
		$customer->name = $params['name'];
		$customer->level = $params['level'];
		$customer->phone = $params['phone'];
		$customer->email = $params['email'];
		$customer->created_date = $created_date;
		$customer->status = 0;
		$result = JFactory::getDbo()->insertObject('#__portal_users', $customer);
		if($result){
			return 1;
		}else{
			return 0;
		}

	}


	public function readExcelCS()
	{
		require_once 'Classes/PHPExcel.php';
		$path_file = JPATH_SITE.DIRECTORY_SEPARATOR."media".DIRECTORY_SEPARATOR."upload".DIRECTORY_SEPARATOR."ThanhVienCS.xlsx";
		$filename = $path_file;
		$inputFileType = PHPExcel_IOFactory::identify($filename);
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);

		$objReader->setReadDataOnly(true);

		/**  Load $inputFileName to a PHPExcel Object  **/
		$objPHPExcel    = $objReader->load("$filename");
		$objWorksheet  = $objPHPExcel->setActiveSheetIndex(0);
		$highestRow    = $objWorksheet->getHighestRow();
		$highestColumn = $objWorksheet->getHighestColumn();
		$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);

		$arrayError = array();
		for ($row = 2; $row <= $highestRow; $row++){
			$params = array(
				// 'period' => '',
				'ordering' => '',
				'id_biznet' => '',
				'name' => '',
				'name_sponsor' => '',
				'id_biznet_sponsor' => '',
				'level_old' => '',
				'level_new' => '',
				'brics_code' => ''
			);
			for ($col = 0; $col < $highestColumnIndex;++$col)
			{
				$value = $objWorksheet->getCellByColumnAndRow($col, $row)->getValue();

				if($value){
					//$arraydata[$row-1][$col] = trim($value," ");
					// if($col == 0){
					// 	$params['period'] = trim($value," ");
					// }
					if($col == 0){
						$params['ordering']	= trim($value," ");
					}
					if($col == 1){
						$params['id_biznet']	= trim($value," ");
					}
					if($col == 2){
						$params['name'] = trim($value," ");
					}
					if($col == 3){
						$params['name_sponsor'] = trim($value," ");
					}
					if($col == 4){
						$params['id_biznet_sponsor'] = trim($value," ");
					}
					if($col == 5){
						$params['level_old'] = trim($value," ");
					}
					if($col == 6){
						$params['level_new'] = trim($value," ");
					}
					if($col == 7){
						$params['brics_code'] = trim($value," ");
					}
				}
			}
			// print_r($params);
			// echo "<br>";
			if($params != '' && !empty($params) && count($params) > 0){
				if($params['id_biznet'] != '' && $params['id_biznet_sponsor'] != ''){
					$isok = $this->importExcelCS($params);
					if($isok == 0){
						$arrayError[] = 'Tên khách hàng: '.$params['id_biznet'].' - Số điện thoại: '.$params['phone'] .' đã tồn tại.';
					}
				}
			}
		}

		if(count($arrayError) > 0){
			echo "<h3>Đã thực hiện Import Excel</h3>";
			echo "<p style='color: #ff0000; font-weight:bold;'>Danh sách khách hàng không import được:</p>";
			echo '<p  style="color:orange;">Lý do: Số điện thoại đã có trong hệ thống, Danh mục không tồn tại hoặc Dự án không tồn tại !</p>';
			foreach($arrayError as $aError){
				echo $aError."<br>";
			}
			echo "<br><br>";

			return false;
		}

		return true;
	}

	public function importExcelCS($params){
		$db = JFactory::getDbo();
		// Insert columns.
		$created_date = date("Y-m-d H:i:s");
		//JFactory::getDate('now', JFactory::getConfig()->get('offset'))->toSql(true);
		$customer = new stdClass();
		//$customer->period = $params['period'];
		$customer->ordering = $params['ordering'];
		$customer->id_biznet = $params['id_biznet'];
		$customer->name = $params['name'];
		$customer->name_sponsor = $params['name_sponsor'];
		$customer->id_biznet_sponsor = $params['id_biznet_sponsor'];
		$customer->level_old = $params['level_old'];
		$customer->level_new = $params['level_new'];
		$customer->brics_code = $params['brics_code'];
		$customer->created_date = $created_date;
		$customer->status = 0;
		$result = JFactory::getDbo()->insertObject('#__cs_users', $customer);
		if($result){
			return 1;
		}else{
			return 0;
		}
	}

	function isPhoneNumber($mobile){
	    return preg_match('/^0[0-9]{9}+$/', $mobile);
	}

	public function testFunction(){
		$a = $this->countCustomerByAgent(3165);
		print_r($a);
		die;
	}


}
