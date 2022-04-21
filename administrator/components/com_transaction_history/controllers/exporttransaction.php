<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Transaction_history
 * @author     nganly <lythanhngan167@gmail.com>
 * @copyright  2019 nganly
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

use \Joomla\Utilities\ArrayHelper;
use \Joomla\CMS\Session\session;
use \Joomla\CMS\Factory;
use \Joomla\CMS\Language\Text;

/**
 * Transactionhistories list controller class.
 *
 * @since  1.6
 */
class Transaction_historyControllerExporttransaction extends \Joomla\CMS\MVC\Controller\AdminController
{
	public function exportExcel(){
		$month = $_POST['month'];
		$year = $_POST['year'];

		$list_transaction = $this->listTransaction($month,$year);
		if(count($list_transaction) > 0){
				$this->exportAll($list_transaction);
				echo "1";
		}else{
				echo "0";
		}
		exit();
	}

	public function listTransaction($month,$year)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('a.id, a.title, a.amount, a.created_date, a.type_transaction, a.status, a.reference_id, a.customer_id, a.current_money, a.current_money_before_operation, created_by.name, created_by.username');
		$query->from($db->quoteName('#__transaction_history','a'));
		// Join over the user field 'created_by'
		$query->join('LEFT', '#__users AS `created_by` ON `created_by`.id = a.`created_by`');

		// Join over the user field 'modified_by'
		//$query->join('LEFT', '#__users AS `modified_by` ON `modified_by`.id = a.`modified_by`');

		if($month == '' && $year > 0){
			$from_date = $year."-01-01 00:00:00";
			$to_date = $year."-12-31 23:59:59";
			$query->where("a.created_date >= '".$from_date."'");
			$query->where("a.created_date <= '".$to_date."'");
		}
		if($month != '' && $year > 0){
			$from_date = $year."-".$month."-01 00:00:00";
			$to_date = $year."-".$month."-31 23:59:59";
			$query->where("a.created_date >= '".$from_date."'");
			$query->where("a.created_date <= '".$to_date."'");
		}

		$query->order('a.id DESC');
		$db->setQuery($query);
		$result = $db->loadObjectList();
		return $result;
	}

	function exportAll($list_transaction)
	{
			jimport('phpexcel.library.PHPExcel');
			$objPHPExcel = new PHPExcel();

			$objPHPExcel->getProperties()->setDescription("Xuat Excel Lich Su Giao Dich");
			$objPHPExcel->getProperties()->setCreator("nganly");

			if (count($list_transaction) > 0) {
					$objPHPExcel->setActiveSheetIndex(0);
					$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'ID');
					$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Tiêu đề');
					$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Tên Đại lý');
					$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Số Điện Thoại');
					$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Số BizXu');
					$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Loại giao dịch');
					$objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Ngày tạo');
					$objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Mã tham chiếu');

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
					foreach ($list_transaction as $key => $transaction) {
								$transaction_name = '';
								switch ($transaction->type_transaction) {
									case 'buydata':
										$transaction_name = 'Mua dữ liệu';
										break;
									case 'charge':
										$transaction_name = 'Nạp BizXu';
										break;
									case 'tranfermoney':
										$transaction_name = 'Chuyển BizXu';
										break;
									case 'trash':
										$transaction_name = 'Hoàn BizXu Data Sọt rác';
										break;
									default:
										$transaction_name = '';
										break;
								}
								$objPHPExcel->getActiveSheet()->SetCellValue('A' . $index, $transaction->id);
								$objPHPExcel->getActiveSheet()->SetCellValue('B' . $index, $transaction->title);
								$objPHPExcel->getActiveSheet()->setCellValueExplicit('C' . $index, $transaction->name,PHPExcel_Cell_DataType::TYPE_STRING);
								$objPHPExcel->getActiveSheet()->setCellValueExplicit('D' . $index, $transaction->username, PHPExcel_Cell_DataType::TYPE_STRING);
								$objPHPExcel->getActiveSheet()->SetCellValue('E' . $index, $transaction->amount);
								$objPHPExcel->getActiveSheet()->SetCellValue('F' . $index, $transaction_name);
								$objPHPExcel->getActiveSheet()->SetCellValue('G' . $index, date("d-m-Y H:i" ,strtotime($transaction->created_date)));
								$objPHPExcel->getActiveSheet()->SetCellValue('H' . $index, $transaction->reference_id);
								$index = $index + 1;
					}
			}

			$objPHPExcel->getActiveSheet()->setTitle('Danh Sach Lich Su Giao Dich');
			// Save Excel 2007 file
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

			$objWriter->save(JPATH_ADMINISTRATOR . DS . 'export' . DS . 'lichsugiaodich.xlsx');
	}

}
