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
class CustomerControllerImportCustomers extends JControllerAdmin
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
	public function getModel($name = 'importcustomer', $prefix = 'CustomerModel', $config = array())
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));

		return $model;
	}

	public function readExcelImportCustomers()
	{
		require_once 'Classes/PHPExcel.php';

		$path_file = JPATH_SITE.DIRECTORY_SEPARATOR."media".DIRECTORY_SEPARATOR."upload".DIRECTORY_SEPARATOR."file-import-datacenter-24-12-2020.xlsx";
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
				'name' => '',
				'phone' => '',
				'email' => '',
				'project_id' => '',
				'link' => ''
			);
			for ($col = 0; $col < $highestColumnIndex;++$col)
			{
				$value = $objWorksheet->getCellByColumnAndRow($col, $row)->getValue();

				if($value){
					//$arraydata[$row-1][$col] = trim($value," ");
					if($col == 0){
						$params['name'] = trim($value," ");
					}
					if($col == 1){
						$params['phone']	= trim($value," ");
					}
					if($col == 2){
						$params['email']	= trim($value," ");
					}
					if($col == 4){
						$params['project_id'] = trim($value," ");
					}
					if($col == 7){
						$params['link'] = trim($value," ");
					}

				}
			}

			if($params != '' && !empty($params) && count($params) > 0){

				if($params['phone'] != '' && $params['project_id'] != ''){
					// //var_dump($params);
					// print_r($params);
					// echo "<br>";
					$ok = $this->importExcelCustomer($params);
					if($ok == 0){
						$arrayError[] = 'Tên khách hàng: '.$params['name'].' - Số điện thoại: '.$params['phone'] .' đã tồn tại.';
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

	public function importExcelCustomer($params){
			$db = JFactory::getDbo();
			$queryInsert = $db->getQuery(true);
			// Insert columns.
			$created_date = date("Y-m-d H:i:s");
			$customer = new stdClass();

			$customer->name = $params['name'];
			$customer->phone = $params['phone'];
			$customer->email = $params['email'];
			$customer->project_id = $params['project_id'];
			$customer->link = $params['link'];
			$customer->created_date = $created_date;
			$customer->status = 0;

			$columns = array(
			'name',
			'phone',
			'email',
			'project_id',
			'link',
			'status',
			'created_date');
			// Insert values.
			$values = array(
			$db->quote($customer->name),
			$db->quote($customer->phone),
			$db->quote($customer->email),
			$db->quote($customer->project_id),
			$db->quote($customer->link),
			$db->quote($customer->status),
			$db->quote($customer->created_date));
			// Prepare the insert query.

			$queryInsert
				->insert($db->quoteName('#__import_customers'))
				->columns($db->quoteName($columns))
				->values(implode(',', $values));
			// Set the query using our newly populated query object and execute it.
			$db->setQuery($queryInsert);
			$result = $db->execute();
			if($result){
				return 1;
			}else{
				return 0;
			}
	}
	public function updateStatus(){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$id = $_REQUEST['id'];
		$oCustomer = new stdClass();
		$oCustomer->id = (int)$id;
		$oCustomer->status = 1;
		$updateResult = $db->updateObject('#__import_customers', $oCustomer, 'id');
	}


	public function testFunction(){
		die;
	}

}
