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
class CustomerControllerExportcustomers extends JControllerAdmin
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
	public function getModel($name = 'Exportcustomers', $prefix = 'CustomerModel', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}

	public function exportExcel(){
		$status = $_POST['status'];

		if(isset($status)) {
			switch((int)$status) {
				case 8:
					$status = array(2,3,4);
				break;
				case 0: 
					$status = array(1,2,3,4,7,6);
				break;
				default:
					$status = array($status);
				break;
			}
		}

		if($_REQUEST['test'] == 1){
			$month = '09';
			$year = '2020';
		}

		$list_customers = $this->listCustomers($status);
		if(count($list_customers) > 0){
				$this->exportAll($list_customers);
				echo "1";
		}else{
				echo "0";
		}
		exit();
	}

	public function listCustomers($status)
	{
		$db = JFactory::getDbo();
		$user = JFactory::getUser();
		$query = $db->getQuery(true);
		$query->select('a.id, a.name, a.phone, a.email, a.place, a.province, a.project_id, a.status_id');
		$query->from($db->quoteName('#__customers','a'));

		$query->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');
		// Join over the category 'category_id'
		$query->select('categories_2794246.title AS category_id');
		$query->join('LEFT', '#__categories AS categories_2794246 ON categories_2794246.id = a.category_id');

		// Join over the created by field 'created_by'
		$query->join('LEFT', '#__users AS created_by ON created_by.id = a.created_by');

		// Join over the created by field 'modified_by'
		$query->join('LEFT', '#__users AS modified_by ON modified_by.id = a.modified_by');

		$query->where('a.state = 1');

		if($user->id > 0) {
			$query->where('a.sale_id = '. $db->quote($user->id));
		}

		$query->where('a.status_id IN(' . implode(',', $status).')');

		$query->order('a.id DESC');

		$db->setQuery($query);

		//echo $query->__toString();

		$result = $db->loadObjectList();
		return $result;
	}

	function exportAll($list_customers)
	{
			jimport('phpexcel.library.PHPExcel');
			$objPHPExcel = new PHPExcel();

			$objPHPExcel->getProperties()->setDescription("Xuat Excel Khach Hang");
			$objPHPExcel->getProperties()->setCreator("nganly");

			if (count($list_customers) > 0) {
					$objPHPExcel->setActiveSheetIndex(0);
					$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'ID');
					$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Họ Tên');
					$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Số Điện Thoại');
					$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Email');
					$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Địa chỉ');
					$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Tỉnh Thành');
					$objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Dự án') ;
					$objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Trạng thái');
					$objPHPExcel->getActiveSheet()->SetCellValue('I1', 'Ghi chú');

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
					foreach ($list_customers as $key => $customer) {

								$objPHPExcel->getActiveSheet()->SetCellValue('A' . $index, $customer->id);
								$objPHPExcel->getActiveSheet()->SetCellValue('B' . $index, $customer->name);
								$objPHPExcel->getActiveSheet()->setCellValueExplicit('C' . $index, $customer->phone,PHPExcel_Cell_DataType::TYPE_STRING);
								$objPHPExcel->getActiveSheet()->SetCellValue('D' . $index, $customer->email);
								$objPHPExcel->getActiveSheet()->SetCellValue('E' . $index, $customer->place);
								$objPHPExcel->getActiveSheet()->SetCellValue('F' . $index, $this->getProvinceName($customer->province));
								$objPHPExcel->getActiveSheet()->SetCellValue('G' . $index, $this->getProject($customer->project_id));
								$objPHPExcel->getActiveSheet()->SetCellValue('H' . $index, JText::_('COM_CUSTOMER_CUSTOMERS_STATUS_ID_OPTION_' . strtoupper($customer->status_id)));
								$objPHPExcel->getActiveSheet()->SetCellValue('I' . $index, $this->getLatestNote($customer->id)->note);
								$index = $index + 1;


					}
			}
			// var_dump($regis_date);
			// echo "<pre>";
			// print_r($user);
			// echo "</pre>";
			// die;
			$objPHPExcel->getActiveSheet()->setTitle('Danh Sach Khach Hang');
			// Save Excel 2007 file
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

			$objWriter->save(JPATH_SITE . DS . 'export' . DS . 'danhsachkhachhang.xlsx');
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

	public function getLatestNote($customer_id){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from($db->quoteName('#__notes'));
		$user = JFactory::getUser();
		if($user->id > 0){
			$query->where($db->quoteName('created_by')." = ".$user->id);
		}
		$query->where($db->quoteName('custommer_id')." = ".$customer_id);
		$query->order("id DESC");
		$query->limit(0,1);
		$db->setQuery($query);

		$result = $db->loadObject();
		return $result;
	}

	public function getProject($project_id) {
		if($project_id) {
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('title');
			$query->from($db->quoteName('#__projects'));
			$query->where($db->quoteName('id')." = ".$project_id);
			$db->setQuery($query);
			$result = $db->loadResult();
			return $result;
		} else {
			return '';
		}
	}

}
