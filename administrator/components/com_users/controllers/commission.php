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
class UsersControllerCommission extends JControllerAdmin
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

		$this->registerTask('block', 'changeBlock');
		$this->registerTask('unblock', 'changeBlock');
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
		// $month = '01';
		// $year = '2020';
		if ((int)$month > 0 && (int)$year > 0) {
				$agents = $this->listAgents();
				if(count($agents) > 0){
						foreach($agents as $agent){
							$agent->total_money = 0;
							$type = 'group';
							$agent->total_money = EshopHelper::getCommissionAmount($agent->id,$month,$year,$type);
						}
						$this->exportAll($agents,$month,$year);
						echo "1";
				}else{
						echo "0";
				}
		}else{
			echo "0";
		}
		exit();
	}

	public function listAgents()
	{
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('us.*')
				->from('#__users AS us')
				->join('LEFT', '#__user_usergroup_map AS ug ON ug.user_id = us.id')
				->where("ug.group_id = 10")
				->where("us.level > 0")
				->where("us.block = 0")
				->order('us.id DESC');
			$db->setQuery($query);
			$result = $db->loadObjectList();
			return $result;
	}

	function exportAll($agents,$month,$year)
	{
			jimport('phpexcel.library.PHPExcel');
			$objPHPExcel = new PHPExcel();

			$objPHPExcel->getProperties()->setDescription("Xuat Excel Dai Ly");
			$objPHPExcel->getProperties()->setCreator("nganly");

			if (count($agents) > 0) {
					$objPHPExcel->setActiveSheetIndex(0);
					$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Mã ĐL');
					$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Họ tên');
					$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Số ĐT');
					$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Cấp ĐL');
					$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Tổng tiền');
					$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Bảo trợ');
					// $objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Tên-CN Ngân hàng');
					// $objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Tên tài khoản');
					// $objPHPExcel->getActiveSheet()->SetCellValue('I1', 'Số tài khoản');
					$objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Tháng');

					$objPHPExcel->getActiveSheet()->getColumnDimension('A1')->setAutoSize(false);
					$objPHPExcel->getActiveSheet()->getColumnDimension('B1')->setAutoSize(false);
					$objPHPExcel->getActiveSheet()->getColumnDimension('C1')->setAutoSize(false);
					$objPHPExcel->getActiveSheet()->getColumnDimension('D1')->setAutoSize(false);
					$objPHPExcel->getActiveSheet()->getColumnDimension('E1')->setAutoSize(false);
					$objPHPExcel->getActiveSheet()->getColumnDimension('F1')->setAutoSize(false);
					// $objPHPExcel->getActiveSheet()->getColumnDimension('G1')->setAutoSize(false);
					// $objPHPExcel->getActiveSheet()->getColumnDimension('H1')->setAutoSize(false);
					// $objPHPExcel->getActiveSheet()->getColumnDimension('I1')->setAutoSize(false);
					$objPHPExcel->getActiveSheet()->getColumnDimension('G1')->setAutoSize(false);

					$objPHPExcel->getActiveSheet()->getColumnDimension('A1')->setWidth("50");
					$objPHPExcel->getActiveSheet()->getColumnDimension('B1')->setWidth("50");
					$objPHPExcel->getActiveSheet()->getColumnDimension('C1')->setWidth("50");
					$objPHPExcel->getActiveSheet()->getColumnDimension('D1')->setWidth("50");
					$objPHPExcel->getActiveSheet()->getColumnDimension('E1')->setWidth("50");
					$objPHPExcel->getActiveSheet()->getColumnDimension('F1')->setWidth("50");
					// $objPHPExcel->getActiveSheet()->getColumnDimension('G1')->setWidth("50");
					// $objPHPExcel->getActiveSheet()->getColumnDimension('H1')->setWidth("50");
					// $objPHPExcel->getActiveSheet()->getColumnDimension('I1')->setWidth("50");
					$objPHPExcel->getActiveSheet()->getColumnDimension('G1')->setWidth("50");
					$index = 2;
					foreach ($agents as $key => $agent) {
							if($agent->total_money > 0){

								$objPHPExcel->getActiveSheet()->SetCellValue('A' . $index, $agent->level_tree.str_pad($agent->id,6,"0",STR_PAD_LEFT));
								$objPHPExcel->getActiveSheet()->SetCellValue('B' . $index, $agent->name);
								//$objPHPExcel->getActiveSheet()->getStyle('C'.$key)->getNumberFormat()->setFormatCode( PHPExcel_Style_NumberFormat::FORMAT_TEXT );
								$objPHPExcel->getActiveSheet()->setCellValueExplicit('C' . $index, $agent->username,PHPExcel_Cell_DataType::TYPE_STRING);
								$objPHPExcel->getActiveSheet()->SetCellValue('D' . $index, "C".$agent->level_tree);
								$objPHPExcel->getActiveSheet()->SetCellValue('E' . $index, $agent->total_money);
								$invited_id = '';
								if($agent->invited_id){
										$invi_user = JFactory::getUser($agent->invited_id);
										$invited_id = $invi_user->level_tree.str_pad($agent->invited_id,6,"0",STR_PAD_LEFT);
								}else{
									$invited_id = '';
								}

								$objPHPExcel->getActiveSheet()->SetCellValue('F' . $index, $invited_id);
								//$objPHPExcel->getActiveSheet()->SetCellValue('G' . $index, $agent->bank_name);
								//$objPHPExcel->getActiveSheet()->SetCellValue('H' . $index, $agent->bank_account_name);
								//$objPHPExcel->getActiveSheet()->getStyle('I'.$key)->getNumberFormat()->setFormatCode( PHPExcel_Style_NumberFormat::FORMAT_TEXT );
								//$objPHPExcel->getActiveSheet()->setCellValueExplicit('I' . $index, $agent->bank_account_number,PHPExcel_Cell_DataType::TYPE_STRING);
								$objPHPExcel->getActiveSheet()->SetCellValue('G' . $index, $month."-".$year);
								$index = $index + 1;
							}
					}
			}

			$objPHPExcel->getActiveSheet()->setTitle('Biznet_Agent');
			// Save Excel 2007 file
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

			$objWriter->save(JPATH_ADMINISTRATOR . DS . 'export' . DS . 'hoahongdaily.xlsx');

			// echo '<div style="text-align:center; color:green; font-weight:bold;">Xuất dữ liệu thành công!</div>';
			// echo '<br><div style="text-align:center;"> <a href="' . JUri::base() . '/export/hoahongdaily.xlsx"><button type="button" class="btn btn-warning">Tải về</button></a><div>';

	}



}
