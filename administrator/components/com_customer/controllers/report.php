<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Customer
 * @author     tung hoang <tungvacc@gmail.com>
 * @copyright  2017 tung hoang
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

use Joomla\Utilities\ArrayHelper;

/**
 * Customers list controller class.
 *
 * @since  1.6
 */
class CustomerControllerReport extends JControllerAdmin
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
	public function getModel($name = 'report', $prefix = 'CustomerModel', $config = array())
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}


	public function exportExcelRejectAT(){
		$project_id = $_REQUEST['project_id'];
		$month = $_REQUEST['month'];
		$year = $_REQUEST['year'];


		// $project_id = 32;
		// $month = 12;
		// $year = 2020;

		$model = $this->getModel();
		$listReject = $model->listRejectAT($project_id,$month,$year);
		if(count($listReject) > 0){
				$this->exportAll($listReject,$month,$year);
				echo "1";
		}else{
				echo "0";
		}
		exit();
	}

	function exportAll($listReject,$month,$year)
	{
			jimport('phpexcel.library.PHPExcel');
			$objPHPExcel = new PHPExcel();

			$objPHPExcel->getProperties()->setDescription("Xuat Excel Reject AT");
			$objPHPExcel->getProperties()->setCreator("nganly");

			if (count($listReject) > 0) {
					$objPHPExcel->setActiveSheetIndex(0);
					$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'ID');
					$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Họ tên');
					$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Điện thoại');
					$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Email/Facebook');
					$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Ngày tạo');
					$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'TransactionID');

					$objPHPExcel->getActiveSheet()->getColumnDimension('A1')->setAutoSize(false);
					$objPHPExcel->getActiveSheet()->getColumnDimension('B1')->setAutoSize(false);
					$objPHPExcel->getActiveSheet()->getColumnDimension('C1')->setAutoSize(false);
					$objPHPExcel->getActiveSheet()->getColumnDimension('D1')->setAutoSize(false);
					$objPHPExcel->getActiveSheet()->getColumnDimension('E1')->setAutoSize(false);
					$objPHPExcel->getActiveSheet()->getColumnDimension('F1')->setAutoSize(false);


					$objPHPExcel->getActiveSheet()->getColumnDimension('A1')->setWidth("50");
					$objPHPExcel->getActiveSheet()->getColumnDimension('B1')->setWidth("50");
					$objPHPExcel->getActiveSheet()->getColumnDimension('C1')->setWidth("50");
					$objPHPExcel->getActiveSheet()->getColumnDimension('D1')->setWidth("50");
					$objPHPExcel->getActiveSheet()->getColumnDimension('E1')->setWidth("50");
					$objPHPExcel->getActiveSheet()->getColumnDimension('F1')->setWidth("50");

					$index = 2;
					foreach ($listReject as $key => $rejectat) {
								$objPHPExcel->getActiveSheet()->SetCellValue('A' . $index, $rejectat->id);
								$objPHPExcel->getActiveSheet()->SetCellValue('B' . $index, $rejectat->name);
								$objPHPExcel->getActiveSheet()->setCellValueExplicit('C' . $index, $rejectat->phone,PHPExcel_Cell_DataType::TYPE_STRING);
								$objPHPExcel->getActiveSheet()->setCellValueExplicit('D' . $index, $rejectat->email, PHPExcel_Cell_DataType::TYPE_STRING);
								$objPHPExcel->getActiveSheet()->SetCellValue('E' . $index, date("d-m-Y H:i" ,strtotime($rejectat->create_date)));
								$objPHPExcel->getActiveSheet()->SetCellValue('F' . $index, $rejectat->transaction_id);
								$index = $index + 1;
					}
			}

			$objPHPExcel->getActiveSheet()->setTitle('Danh Sach Reject AT '.$month."-".$year);
			// Save Excel 2007 file
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

			$objWriter->save(JPATH_ADMINISTRATOR . DS . 'export' . DS . 'rejectAT.xlsx');
	}

	public function testFunction(){
		$model = $this->getModel();
		print_r($model);
		echo "abc";
		die;
	}


}
