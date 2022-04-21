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
class RechargeControllerExportrecharge extends JControllerAdmin
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
		$day = $_POST['day'];

		if($_REQUEST['test'] == 1){
			// $month = '12';
			// $year = '';
			// $gr_id = 3;
		}

		$list_recharge = $this->listRecharges($day,$month,$year);
		if(count($list_recharge) > 0){
				foreach($list_recharge as $i=>$l_recharge){
					$list_recharge[$i]->amount = number_format($l_recharge->amount, 0);
				}
				$this->exportAll($list_recharge);
				echo "1";
		}else{
				echo "0";
		}
		exit();
	}

	public function listRecharges($day,$month,$year)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('a.id, a.code, created_by.name, created_by.username, a.amount, a.created_time, a.note, a.sbdm_id, a.status');
		$query->from($db->quoteName('#__recharge','a'));
		// Join over the user field 'created_by'
		$query->join('LEFT', '#__users AS `created_by` ON `created_by`.id = a.`created_by`');

		// Join over the user field 'modified_by'
		//$query->join('LEFT', '#__users AS `modified_by` ON `modified_by`.id = a.`modified_by`');

		if($month == '' && $year > 0){
			$from_date = $year."-01-01 00:00:00";
			$to_date = $year."-12-31 23:59:59";
			$query->where("a.created_time >= '".$from_date."'");
			$query->where("a.created_time <= '".$to_date."'");
		}
		if($month != '' && $year > 0 && $day ==''){
			$from_date = $year."-".$month."-01 00:00:00";
			$to_date = $year."-".$month."-31 23:59:59";
			$query->where("a.created_time >= '".$from_date."'");
			$query->where("a.created_time <= '".$to_date."'");
		}
		if($day != '' && $month != '' && $year > 0){
			$from_date = $year."-".$month."-".$day." 00:00:00";
			$to_date = $year."-".$month."-".$day." 23:59:59";
			$query->where("a.created_time >= '".$from_date."'");
			$query->where("a.created_time <= '".$to_date."'");
		}


		$query->order('a.id DESC');

		$db->setQuery($query);
		$result = $db->loadObjectList();
		return $result;
	}

	function exportAll($list_recharge)
	{
			jimport('phpexcel.library.PHPExcel');
			$objPHPExcel = new PHPExcel();

			$objPHPExcel->getProperties()->setDescription("Xuat Excel Nap Tien");
			$objPHPExcel->getProperties()->setCreator("nganly");

			if (count($list_recharge) > 0) {
					$objPHPExcel->setActiveSheetIndex(0);
					$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'ID');
					$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Mã Nạp BizXu');
					$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Người Nạp');
					$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Số Điện Thoại');
					$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Tiền');
					$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Người Tạo');
					$objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Ngày Nạp');
					$objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Trạng thái');
					$objPHPExcel->getActiveSheet()->SetCellValue('I1', 'Ghi Chú');

					$objPHPExcel->getActiveSheet()->getColumnDimension('A1')->setAutoSize(false);
					$objPHPExcel->getActiveSheet()->getColumnDimension('B1')->setAutoSize(false);
					$objPHPExcel->getActiveSheet()->getColumnDimension('C1')->setAutoSize(false);
					$objPHPExcel->getActiveSheet()->getColumnDimension('D1')->setAutoSize(false);
					$objPHPExcel->getActiveSheet()->getColumnDimension('E1')->setAutoSize(false);
					$objPHPExcel->getActiveSheet()->getColumnDimension('F1')->setAutoSize(false);
					$objPHPExcel->getActiveSheet()->getColumnDimension('G1')->setAutoSize(false);
					$objPHPExcel->getActiveSheet()->getColumnDimension('H1')->setAutoSize(false);
					$objPHPExcel->getActiveSheet()->getColumnDimension('I1')->setAutoSize(false);

					$objPHPExcel->getActiveSheet()->getColumnDimension('A1')->setWidth("50");
					$objPHPExcel->getActiveSheet()->getColumnDimension('B1')->setWidth("50");
					$objPHPExcel->getActiveSheet()->getColumnDimension('C1')->setWidth("50");
					$objPHPExcel->getActiveSheet()->getColumnDimension('D1')->setWidth("50");
					$objPHPExcel->getActiveSheet()->getColumnDimension('E1')->setWidth("50");
					$objPHPExcel->getActiveSheet()->getColumnDimension('F1')->setWidth("50");
					$objPHPExcel->getActiveSheet()->getColumnDimension('G1')->setWidth("50");
					$objPHPExcel->getActiveSheet()->getColumnDimension('H1')->setWidth("50");
					$objPHPExcel->getActiveSheet()->getColumnDimension('I1')->setWidth("50");

					$index = 2;
					foreach ($list_recharge as $key => $recharge) {
								$sbdm_name = '';
								if($recharge->sbdm_id > 0){
									$sbdmUser = JFactory::getUser($recharge->sbdm_id);
									$sbdm_name = $sbdmUser->name." (".$sbdmUser->username.")";
								}
								$status_text = '';
								switch ($recharge->status) {
									case 'unconfirm':
										$status_text = 'Chưa xác nhận';
										break;
									case 'confirmed':
										$status_text = 'Đã xác nhận';
										break;
									case 'waiting':
										$status_text = 'Đang chờ';
										break;
									case 'cancel':
										$status_text = 'Hủy';
										break;
									default:
										$status_text = '';
										break;
								}

								$objPHPExcel->getActiveSheet()->SetCellValue('A' . $index, $recharge->id);
								$objPHPExcel->getActiveSheet()->SetCellValue('B' . $index, $recharge->code);
								$objPHPExcel->getActiveSheet()->setCellValueExplicit('C' . $index, $recharge->name,PHPExcel_Cell_DataType::TYPE_STRING);
								$objPHPExcel->getActiveSheet()->setCellValueExplicit('D' . $index, $recharge->username, PHPExcel_Cell_DataType::TYPE_STRING);
								$objPHPExcel->getActiveSheet()->SetCellValue('E' . $index, $recharge->amount);
								$objPHPExcel->getActiveSheet()->SetCellValue('F' . $index, $sbdm_name);
								$objPHPExcel->getActiveSheet()->SetCellValue('G' . $index, date("d-m-Y H:i" ,strtotime($recharge->created_time)));
								$objPHPExcel->getActiveSheet()->SetCellValue('H' . $index, $status_text);
								$objPHPExcel->getActiveSheet()->SetCellValue('I' . $index, $recharge->note);
								$index = $index + 1;
					}
			}

			$objPHPExcel->getActiveSheet()->setTitle('Danh Sach Nap Tien');
			// Save Excel 2007 file
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

			$objWriter->save(JPATH_ADMINISTRATOR . DS . 'export' . DS . 'danhsachnaptien.xlsx');
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




}
