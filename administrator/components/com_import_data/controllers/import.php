<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Import_data
 * @author     tung hoang <tungvacc@gmail.com>
 * @copyright  2017 tung hoang
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

/**
 * Data controller class.
 *
 * @since  1.6
 */
class Import_dataControllerImport extends JControllerLegacy
{
	/**
	 * Constructor
	 *
	 * @throws Exception
	 */
	public function __construct()
	{
		$this->view_list = 'datas';
		parent::__construct();
	}

	function isPhoneNumber($mobile){
	    return preg_match('/^0[0-9]{9}+$/', $mobile);
	}

	public function proceed($tpl = null)
	{
		require_once 'Classes/PHPExcel.php';

		$id = $_REQUEST['id'];
		$view = $_REQUEST['view'];
		$file_name = $this->getFileName($id);
		$path_file = JPATH_SITE.DIRECTORY_SEPARATOR."media".DIRECTORY_SEPARATOR."upload".DIRECTORY_SEPARATOR.$file_name->file_excel;
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
				'place' => '',
				'project_id' => '',
				'category_id' => ''
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
					if($col == 3){
						$params['place'] = trim($value," ");
					}
					if($col == 4){
						$params['project_id'] = trim($value," ");
					}
					if($col == 5){
						$params['category_id'] = trim($value," ");
					}

				}
			}

			if($params != '' && !empty($params) && count($params) > 0){
				if($params['name'] != '' && $params['phone'] != ''){
					if($this->checkCategory($params['category_id']) == 1){
						if($this->checkProject($params['project_id']) == 1){
							if($this->isPhoneNumber($params['phone']) == 1){
								$ok = $this->importExcel($params);
								if($ok == 0){
									$arrayError[] = 'Tên khách hàng: '.$params['name'].' - Số điện thoại: '.$params['phone'] .' đã tồn tại.';
								}
							}else{
								$arrayError[] = 'Tên khách hàng: '.$params['name'].' - Số điện thoại: '.$params['phone'] .' không đúng định dạng 10 số.';
							}
						}else{
							$arrayError[] = 'Tên khách hàng: '.$params['name'].' - Số điện thoại: '.$params['phone'].' - Không tồn tại Dự án với mã: #'.$params['project_id'] ;
						}
					}else{
						$arrayError[] = 'Tên khách hàng: '.$params['name'].' - Số điện thoại: '.$params['phone'].' - Không tồn tại Danh mục với mã: #'.$params['category_id'] ;
					}

				}else{
					if($params['category_id'] != '' || $params['project_id'] != ''){
						$arrayError[] = 'Số điện thoại và Họ tên là bắt buộc nhập, Dự án mã: #'.$params['project_id'].', Danh mục mã: #'.$params['category_id'].'.';
					}
				}

				// if($this->checkCategory($params['category_id']) == 1){
				// 	if($this->checkProject($params['project_id']) == 1){
				// 		if($params['name'] != '' && $params['phone'] != ''){
				// 			if($this->isPhoneNumber($params['phone']) == 1){
				// 				$ok = $this->importExcel($params);
				// 				if($ok == 0){
				// 					$arrayError[] = 'Tên khách hàng: '.$params['name'].' - Số điện thoại: '.$params['phone'] .' đã tồn tại.';
				// 				}
				// 			}else{
				// 				$arrayError[] = 'Tên khách hàng: '.$params['name'].' - Số điện thoại: '.$params['phone'] .' không đúng định dạng 10 số.';
				// 			}
				//
				// 		}else{
				// 			$arrayError[] = 'Số điện thoại và Họ tên là bắt buộc nhập.';
				// 		}
				//
				// 	}else{
				// 		$arrayError[] = 'Tên khách hàng: '.$params['name'].' - Số điện thoại: '.$params['phone'].' - Không tồn tại Dự án với mã: #'.$params['project_id'] ;
				// 	}
				//
				// }else{
				// 	$arrayError[] = 'Tên khách hàng: '.$params['name'].' - Số điện thoại: '.$params['phone'].' - Không tồn tại Danh mục với mã: #'.$params['category_id'] ;
				// }

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
			if ($view == 'datas')
			{
				echo '<p><a class="btn btn-success" href="' . JRoute::_('index.php?option=com_import_data&view=datas') . '">Quay về trang trước</a></p>';
			}else if($view == 'data'){
				echo '<p><a class="btn btn-success" href="' . JRoute::_('index.php?option=com_import_data&view=data&layout=edit&id='.$id) . '">Quay về trang trước</a></p>';
			}
			return false;
		}
		if ($view == 'datas'){
			$this->setMessage(JText::_('COM_IMPORT_DATA_IMPORT_SUCCESS'));
			$this->setRedirect(JRoute::_('index.php?option=com_import_data&view=datas', false));
		}elseif ($view == 'data'){
			$this->setMessage(JText::_('COM_IMPORT_DATA_IMPORT_SUCCESS'));
			$this->setRedirect(JRoute::_('index.php?option=com_import_data&view=data&layout=edit&id='.$id, false));
		}

		return true;
	}

	protected function importExcel($params){
		$db = JFactory::getDbo();
		$query = "SELECT * FROM #__customers WHERE phone = '".$params['phone']."' AND state = 1";

		$db->setQuery($query);
		$phone = $db->loadObject();
		$existPhone = 0;
		if($phone->phone > 0){
			$existPhone = 1;
		}
		if($existPhone == 0 ){
			$queryInsert = $db->getQuery(true);
			// Insert columns.
			$time_created = date("Y-m-d H:i:s");
			//JFactory::getDate('now', JFactory::getConfig()->get('offset'))->toSql(true);
			$customer = new stdClass();
			$customer->name = $params['name'];
			$customer->phone = $params['phone'];
			$customer->email = $params['email'];
			$customer->place = $params['place'];
			$customer->project_id = $params['project_id'];
			$customer->category_id = $params['category_id'];
			$customer->status_id = 1;
			$customer->modified_date = $time_created;
			$customer->create_date = $time_created;
			$columns = array('name', 'phone', 'email', 'place', 'project_id', 'category_id', 'status_id', 'modified_date', 'create_date','state');
			// Insert values.
			$values = array($db->quote($customer->name), $db->quote($customer->phone), $db->quote($customer->email), $db->quote($customer->place), $db->quote($customer->project_id), $db->quote($customer->category_id), $db->quote($customer->status_id), $db->quote($customer->modified_date), $db->quote($customer->create_date),1);
			// Prepare the insert query.

			$queryInsert
				->insert($db->quoteName('#__customers'))
				->columns($db->quoteName($columns))
				->values(implode(',', $values));
			// Set the query using our newly populated query object and execute it.
			$db->setQuery($queryInsert);
			$db->execute();
			return 1;
		}else{
			return 0;
		}
	}

	protected function getFileName($id){
		$db = JFactory::getDbo();
		$query = "SELECT * FROM #__import_data WHERE id = $id";
		$db->setQuery($query);
		$result = $db->loadObject();
		return $result;
	}

	protected function checkProject($id){
		$db = JFactory::getDbo();
		$query = "SELECT count(*)  FROM #__projects WHERE id = '".$id."' AND state = 1";
		$db->setQuery($query);
		$result = $db->loadResult();
		return $result;
	}

	protected function checkCategory($id){
		if($id <= 1) return 0;
		$db = JFactory::getDbo();
		$query = "SELECT count(*) FROM #__categories WHERE id = '".$id."' AND published = 1";
		$db->setQuery($query);
		$result = $db->loadResult();
		return $result;
	}



}
