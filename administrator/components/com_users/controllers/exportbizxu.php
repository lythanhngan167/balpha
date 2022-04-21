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
use Joomla\Utilities\ArrayHelper;

/**
 * Users list controller class.
 *
 * @since  1.6
 */
class UsersControllerExportbizxu extends JControllerAdmin
{

	public function __construct($config = array())
	{
		parent::__construct($config);

	}

	/**
	 * Proxy for getModel.
	 *
	 * @param   string  $name    Optional. Model name
	 * @param   string  $prefix  Optional. Class prefix
	 * @param   array   $config  Optional. Configuration array for model
	 *
	 * @return  object	The Model
	 *
	 * @since    1.6
	 */
	public function getModel($name = 'exportbizxu', $prefix = 'UsersModel', $config = array())
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}


	public function exportRestBizXuExcel(){
		$month = $_REQUEST['month'];
		$year = $_REQUEST['year'];
		$from_date = $_REQUEST['from_date'];
		$to_date = $_REQUEST['to_date'];
		// echo $year;
		// echo "\n";
		// echo $month;
		// echo "\n";
		// echo $from_date;
		// echo "\n";
		// echo $to_date;
		// die;
		if($year != '0' && $month != ''){
			$month_year = $year.'-'.$month.'-01 23:59:59';
			$end_date = date("Y-m-t H:i:s", strtotime($month_year));
			$type = 'month';
		}

		if($from_date != '' && $to_date != ''){
			$end_date = $to_date.' 23:59:59';
			$type = 'day';
		}

		$list_user = $this->listAgents();
		if(count($list_user) > 0){
				$this->exportBizXuAll($list_user,$month,$year,$from_date,$to_date,$end_date,$type);
				echo "1";
		}else{
				echo "0";
		}
		exit();
	}

	public function listAgents()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('us.id, us.name, us.username, us.email, us.id_biznet, us.province, us.registerDate');
		$query->from($db->quoteName('#__users','us'));
		$query->join('LEFT', '#__user_usergroup_map AS ug ON ug.user_id = us.id');
		$query->where("ug.group_id = 3");
		$query->where($db->quoteName('us.block') . " = 0");

		// if($month == '' && $year > 0){
		// 	$from_date = $year."-01-01 00:00:00";
		// 	$to_date = $year."-12-31 23:59:59";
		// 	$query->where("us.registerDate >= '".$from_date."'");
		// 	$query->where("us.registerDate <= '".$to_date."'");
		// }
		// if($month != '' && $year > 0){
		// 	$from_date = $year."-".$month."-01 00:00:00";
		// 	$to_date = $year."-".$month."-31 23:59:59";
		// 	$query->where("us.registerDate >= '".$from_date."'");
		// 	$query->where("us.registerDate <= '".$to_date."'");
		// }

		$query->order('us.name ASC');
		$db->setQuery($query);
		$result = $db->loadObjectList();
		return $result;
	}

	function exportBizXuAll($agents,$month,$year,$from_date,$to_date,$end_date,$type)
	{
			jimport('phpexcel.library.PHPExcel');
			$objPHPExcel = new PHPExcel();

			$objPHPExcel->getProperties()->setDescription("Xuat Excel So Du BizXu");
			$objPHPExcel->getProperties()->setCreator("nganly");

			if (count($agents) > 0) {
					$objPHPExcel->setActiveSheetIndex(0);
					$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'ID');
					$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Họ tên');
					$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Số ĐT');
					$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Email');
					$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'ID Biznet');
					$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Số dư BizXu');
					$objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Tỉnh/TP');
					$objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Ngày đăng ký');
					$objPHPExcel->getActiveSheet()->SetCellValue('I1', 'Tháng');
					$objPHPExcel->getActiveSheet()->SetCellValue('J1', 'Đến ngày');

					$objPHPExcel->getActiveSheet()->getColumnDimension('A1')->setAutoSize(false);
					$objPHPExcel->getActiveSheet()->getColumnDimension('B1')->setAutoSize(false);
					$objPHPExcel->getActiveSheet()->getColumnDimension('C1')->setAutoSize(false);
					$objPHPExcel->getActiveSheet()->getColumnDimension('D1')->setAutoSize(false);
					$objPHPExcel->getActiveSheet()->getColumnDimension('E1')->setAutoSize(false);
					$objPHPExcel->getActiveSheet()->getColumnDimension('F1')->setAutoSize(false);
					$objPHPExcel->getActiveSheet()->getColumnDimension('G1')->setAutoSize(false);
					$objPHPExcel->getActiveSheet()->getColumnDimension('H1')->setAutoSize(false);
					$objPHPExcel->getActiveSheet()->getColumnDimension('I1')->setAutoSize(false);
					$objPHPExcel->getActiveSheet()->getColumnDimension('J1')->setAutoSize(false);

					$objPHPExcel->getActiveSheet()->getColumnDimension('A1')->setWidth("50");
					$objPHPExcel->getActiveSheet()->getColumnDimension('B1')->setWidth("50");
					$objPHPExcel->getActiveSheet()->getColumnDimension('C1')->setWidth("50");
					$objPHPExcel->getActiveSheet()->getColumnDimension('D1')->setWidth("50");
					$objPHPExcel->getActiveSheet()->getColumnDimension('E1')->setWidth("50");
					$objPHPExcel->getActiveSheet()->getColumnDimension('F1')->setWidth("50");
					$objPHPExcel->getActiveSheet()->getColumnDimension('G1')->setWidth("50");
					$objPHPExcel->getActiveSheet()->getColumnDimension('H1')->setWidth("50");
					$objPHPExcel->getActiveSheet()->getColumnDimension('I1')->setWidth("50");
					$objPHPExcel->getActiveSheet()->getColumnDimension('J1')->setWidth("50");

					$index = 2;
					foreach ($agents as $key => $agent) {
								$objPHPExcel->getActiveSheet()->SetCellValue('A' . $index, $agent->id);
								$objPHPExcel->getActiveSheet()->SetCellValue('B' . $index, $agent->name);
								$objPHPExcel->getActiveSheet()->setCellValueExplicit('C' . $index, $agent->username,PHPExcel_Cell_DataType::TYPE_STRING);
								$objPHPExcel->getActiveSheet()->SetCellValue('D' . $index, $agent->email);
								$objPHPExcel->getActiveSheet()->SetCellValue('E' . $index, $agent->id_biznet);

								$transaction_history = $this->getTranHistoryAgentByEndDate($agent->id,$end_date);

								//$objPHPExcel->getActiveSheet()->setCellValueExplicit('F' . $index, number_format($transaction_history->current_money, 0, '.', '.'),PHPExcel_Cell_DataType::TYPE_STRING);
								$objPHPExcel->getActiveSheet()->SetCellValue('F' . $index, $transaction_history->current_money);
								$objPHPExcel->getActiveSheet()->SetCellValue('G' . $index, $this->getProvinceName($agent->province));
								$objPHPExcel->getActiveSheet()->SetCellValue('H' . $index, date("d-m-Y H:i" ,strtotime($agent->registerDate)) );
								if($type == 'day'){
									$month_year = '';
								}else{
									$month_year = $month.'-'.$year;
								}
								$objPHPExcel->getActiveSheet()->SetCellValue('I' . $index, $month_year);
								$objPHPExcel->getActiveSheet()->SetCellValue('J' . $index, date("d-m-Y" ,strtotime($end_date)) );
								$index = $index + 1;
					}
			}

			$objPHPExcel->getActiveSheet()->setTitle('So Du BizXu Dai Ly');
			// Save Excel 2007 file
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

			$objWriter->save(JPATH_ADMINISTRATOR . DS . 'export' . DS . 'sodubizxu.xlsx');
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

	public function getTransactionHistoryByAgent($user_id)
	{
		$result = array();
		if($user_id > 0){
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('th.*');
			$query->from('`#__transaction_history` AS th');
			$query->where('th.created_by = '.$user_id);
			$query->order('th.id DESC');
			$db->setQuery($query);
			//echo $query->__toString();
			$result = $db->loadObjectList();
			return $result;
		}else{
			return $result;
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

	public function updateCurrentBizXuToTranHistory($id,$current_money,$current_money_before_operation){
		$user = JFactory::getUser();
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$oTranHistory = new stdClass();
		$oTranHistory->id = (int)$id;
		$oTranHistory->current_money = $current_money;
		$oTranHistory->current_money_before_operation = $current_money_before_operation;
		$updateResult = $db->updateObject('#__transaction_history', $oTranHistory, 'id');
		if($updateResult){
			return 1;
		}else{
			return 0;
		}
	}

	public function updateCurrentBizXuToTranHistoryAllAgent(){
		$list_user = $this->listAgents();
		if(count($list_user) > 0){
				$current_money = 0;
				foreach ($list_user as $key => $user) {
					$transaction_history = $this->getTransactionHistoryByAgent($user->id);
					$user_info = $this->getUserByID($user->id);
					$current_money = $user_info->money;
					$current_money_before_operation = 0;
					if(count($transaction_history) > 0){
						foreach ($transaction_history as $key => $transaction) {
							$current_money_before_operation = $current_money - (int)$transaction->amount;
							if($key == 0){
								$this->updateCurrentBizXuToTranHistory($transaction->id,$current_money,$current_money_before_operation);
								$current_money =  $current_money_before_operation;
							}else{
								$this->updateCurrentBizXuToTranHistory($transaction->id,$current_money,$current_money_before_operation);
								$current_money =  $current_money_before_operation;
							}
						}
					}
				}
				echo "1";
		}else{
				echo "0";
		}
		exit();
	}

	public function getTranHistoryAgentByEndDate($user_id,$end_date)
	{
		$result = new stdClass();
		if($user_id > 0){
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('th.*');
			$query->from('`#__transaction_history` AS th');
			$query->where('th.created_by = '.$user_id);
			$query->where("th.created_date <= '".$end_date."'");
			$query->order('th.id DESC');
			$query->setLimit(1);
			$db->setQuery($query);
			//echo $query->__toString();
			$result = $db->loadObject();
			return $result;
		}else{
			return $result;
		}
	}

	public function testFunction(){
		$a = $this->getTranHistoryAgentByEndDate(1829,'2020-09-06 23:29:59');
		print_r($a->current_money);
		die;
	}

	public function testFunction2(){
		$transaction_history = $this->getTransactionHistoryByAgent(1829);
		$user_info = $this->getUserByID(1829);//1829
		// $id = 34669;
		// $current_money = 80000;
		// $current_money_before_operation = 180000;
		// $this->updateCurrentBizXuToTranHistory($id,$current_money,$current_money_before_operation);
		echo "<pre>";
		//print_r($user_info->money);
		$current_money = $user_info->money;
		echo $current_money;
		$current_money_before_operation = 0;
		foreach ($transaction_history as $key => $transaction) {
			$current_money_before_operation = $current_money - (int)$transaction->amount;
			echo "<br>current_money: ".$current_money."\n";
			if($key == 0){
				//$this->updateCurrentBizXuToTranHistory($transaction->id,$current_money,$current_money_before_operation);
				$current_money =  $current_money_before_operation;
			}else{
				//$this->updateCurrentBizXuToTranHistory($transaction->id,$current_money,$current_money_before_operation);
				$current_money =  $current_money_before_operation;
			}
			echo "<br>current_money_before_operation: ".$current_money_before_operation."\n";
			print_r($transaction);
		}
		echo "</pre>";
		die;
	}



}
