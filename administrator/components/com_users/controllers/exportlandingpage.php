<?php
if (!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
/**
 * @package     Joomla.Administrator
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
require_once(JPATH_SITE . '/components/com_eshop/helpers/helper.php');

use Joomla\Utilities\ArrayHelper;

/**
 * Users list controller class.
 *
 * @since  1.6
 */
class UsersControllerExportlandingpage extends JControllerAdmin
{
	/**
	 * @var    string  The prefix to use with controller messages.
	 * @since  1.6
	 */
	protected $text_prefix = 'COM_USERS_COMMISSION';

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
	public function changeBlock()
	{
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

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
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

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

	public function exportExcel(){
		$month = $_POST['month'];
		$year = $_POST['year'];

		if($_REQUEST['test'] == 1){
			$month = '09';
			$year = '2020';
		}

		$list_users = $this->listUsers($month,$year);
		if(count($list_users) > 0){
				$this->exportAll($list_users);
				echo "1";
		}else{
				echo "0";
		}
		exit();
	}

	public function listUsers($month,$year)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('a.id, a.name, a.username, a.email, a.id_biznet, a.province,a.lpage_date, a.registerDate, a.block_landingpage_user');
		$query->from($db->quoteName('#__users','a'));

		$query->join('LEFT', '#__user_usergroup_map AS ug ON ug.user_id = a.id');
		$query->where("ug.group_id = 3");

		//block_landingpage
		$query->where('a.block_landingpage = 0');
		$query->where('a.block = 0');

		if($month == '' && $year > 0){
			$from_date = $year."-01-01 00:00:00";
			$to_date = $year."-12-31 23:59:59";
			$query->where("a.lpage_date >= '".$from_date."' AND a.lpage_date <= '".$to_date."'");
		}
		if($month != '' && $year > 0){
			$from_date = $year."-".$month."-01 00:00:00";
			$to_date = $year."-".$month."-31 23:59:59";
			$query->where("a.lpage_date >= '".$from_date."' AND a.lpage_date <= '".$to_date."'");
		}

		$query->order('a.id DESC');

		$db->setQuery($query);

		//echo $query->__toString();

		$result = $db->loadObjectList();
		return $result;
	}

	function exportAll($list_users)
	{
			jimport('phpexcel.library.PHPExcel');
			$objPHPExcel = new PHPExcel();

			$objPHPExcel->getProperties()->setDescription("Xuat Excel Landingpage");
			$objPHPExcel->getProperties()->setCreator("nganly");

			if (count($list_users) > 0) {
					$objPHPExcel->setActiveSheetIndex(0);
					$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'ID');
					$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Họ Tên');
					$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Số Điện Thoại');
					$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Email');
					$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'ID Biznet');
					$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Tỉnh Thành');
					$objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Ngày đăng ký') ;
					$objPHPExcel->getActiveSheet()->SetCellValue('H1', 'BDM') ;

					$objPHPExcel->getActiveSheet()->getColumnDimension('A1')->setAutoSize(false);
					$objPHPExcel->getActiveSheet()->getColumnDimension('B1')->setAutoSize(false);
					$objPHPExcel->getActiveSheet()->getColumnDimension('C1')->setAutoSize(false);
					$objPHPExcel->getActiveSheet()->getColumnDimension('D1')->setAutoSize(false);
					$objPHPExcel->getActiveSheet()->getColumnDimension('E1')->setAutoSize(false);
					$objPHPExcel->getActiveSheet()->getColumnDimension('F1')->setAutoSize(false);
					$objPHPExcel->getActiveSheet()->getColumnDimension('G1')->setAutoSize(false);
					$objPHPExcel->getActiveSheet()->getColumnDimension('H1')->setAutoSize(false);

					$objPHPExcel->getActiveSheet()->getColumnDimension('A1')->setWidth("50");
					$objPHPExcel->getActiveSheet()->getColumnDimension('B1')->setWidth("50");
					$objPHPExcel->getActiveSheet()->getColumnDimension('C1')->setWidth("50");
					$objPHPExcel->getActiveSheet()->getColumnDimension('D1')->setWidth("50");
					$objPHPExcel->getActiveSheet()->getColumnDimension('E1')->setWidth("50");
					$objPHPExcel->getActiveSheet()->getColumnDimension('F1')->setWidth("50");
					$objPHPExcel->getActiveSheet()->getColumnDimension('G1')->setWidth("50");
					$objPHPExcel->getActiveSheet()->getColumnDimension('H1')->setWidth("50");
					$index = 2;
					foreach ($list_users as $key => $user) {
								$regis_date = $user->lpage_date;
								if($regis_date == NULL){
									$regis_date =  $user->registerDate;
								}
								$nameBDM = '';

								if($user->block_landingpage_user > 0){
									$bdm = $this->getUserByID($user->block_landingpage_user);
									$nameBDM = $bdm->username.' - '.$bdm->name;
								}

								$objPHPExcel->getActiveSheet()->SetCellValue('A' . $index, $user->id);
								$objPHPExcel->getActiveSheet()->SetCellValue('B' . $index, $user->name);
								$objPHPExcel->getActiveSheet()->setCellValueExplicit('C' . $index, $user->username,PHPExcel_Cell_DataType::TYPE_STRING);
								$objPHPExcel->getActiveSheet()->SetCellValue('D' . $index, $user->email);
								$objPHPExcel->getActiveSheet()->SetCellValue('E' . $index, $user->id_biznet);
								$objPHPExcel->getActiveSheet()->SetCellValue('F' . $index, $this->getProvinceName($user->province));
								$objPHPExcel->getActiveSheet()->SetCellValue('G' . $index, date("d-m-Y H:i" ,strtotime($regis_date)));
								$objPHPExcel->getActiveSheet()->SetCellValue('H' . $index, $nameBDM);
								$index = $index + 1;


					}
			}

			$objPHPExcel->getActiveSheet()->setTitle('Danh Sach Landingpage');
			// Save Excel 2007 file
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

			$objWriter->save(JPATH_ADMINISTRATOR . DS . 'export' . DS . 'danhsachlandingpage.xlsx');
	}

	public function getProvinceName($pro_id)
	{
		if($pro_id > 0){
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);

			$query->select('ec.*');
			$query->from('`#__eshop_countries` AS ec');
			$query->where('ec.id = '.$pro_id);
			$db->setQuery($query);
			$result = $db->loadObject();
			return $result->country_name;
		}else{
			return '';
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

}
