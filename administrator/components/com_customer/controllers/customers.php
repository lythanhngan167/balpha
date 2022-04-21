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
class CustomerControllerCustomers extends JControllerAdmin
{

	public function __construct($config = array())
	{
		parent::__construct($config);

		$this->registerTask('trashapprove', 'trashApprove');
		$this->registerTask('trashreject', 'trashReject');
		$this->registerTask('untrashapprove', 'trashApprove');
		$this->registerTask('untrashreject', 'trashReject');
	}
	/**
	 * Method to clone existing Customers
	 *
	 * @return void
	 */
	public function duplicate()
	{
		// Check for request forgeries
		Jsession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Get id(s)
		$pks = $this->input->post->get('cid', array(), 'array');

		try
		{
			if (empty($pks))
			{
				throw new Exception(JText::_('COM_CUSTOMER_NO_ELEMENT_SELECTED'));
			}

			ArrayHelper::toInteger($pks);
			$model = $this->getModel();
			$model->duplicate($pks);
			$this->setMessage(Jtext::_('COM_CUSTOMER_ITEMS_SUCCESS_DUPLICATED'));
		}
		catch (Exception $e)
		{
			JFactory::getApplication()->enqueueMessage($e->getMessage(), 'warning');
		}

		$this->setRedirect('index.php?option=com_customer&view=customers');
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
	public function getModel($name = 'customer', $prefix = 'CustomerModel', $config = array())
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));

		return $model;
	}

	/**
	 * Method to save the submitted ordering values for records via AJAX.
	 *
	 * @return  void
	 *
	 * @since   3.0
	 */
	public function saveOrderAjax()
	{
		// Get the input
		$input = JFactory::getApplication()->input;
		$pks   = $input->post->get('cid', array(), 'array');
		$order = $input->post->get('order', array(), 'array');

		// Sanitize the input
		ArrayHelper::toInteger($pks);
		ArrayHelper::toInteger($order);

		// Get the model
		$model = $this->getModel();

		// Save the ordering
		$return = $model->saveorder($pks, $order);

		if ($return)
		{
			echo "1";
		}

		// Close the application
		JFactory::getApplication()->close();
	}

	public function deleteExpiredData(){
		$expire_date =  date("Y-m-d H:i:s",strtotime("-".EXPIRED_DATA_CUSTOMER." months"));
		$db = JFactory::getDbo();
		$sql = "UPDATE #__customers set phone = CONCAT(phone, 'xy')  WHERE  RIGHT(phone, 2) != 'xy' AND create_date <= '".$expire_date."'";
		//id = 49364
		$result = $db->setQuery($sql)->execute();
		if($result){
			echo  '1';
		}else{
			echo '0';
		}
		exit();
	}

	public function deleteExpiredDataLandingpage(){
		$expire_date =  date("Y-m-d H:i:s",strtotime("-".EXPIRED_DATA_LANDINGPAGE." months"));
		//$expire_date =  '2020-07-31 23:59:59';
		$project_id = 0;
		$project_id = PROJECT_LANDINGPAGE;
		$db = JFactory::getDbo();
		$sql = "UPDATE #__customers set phone = CONCAT(phone, 'xy')  WHERE   RIGHT(phone, 2) != 'xy' AND project_id = ".$project_id." AND create_date <= '".$expire_date."'";
		//id = 49364
		$result = $db->setQuery($sql)->execute();
		if($result){
			echo  '1';
		}else{
			echo '0';
		}
		exit();
	}

	public function trashApprove() {
		$id    = $this->input->get('customerId');
		$plusResult = true;
		$user = JFactory::getUser();
		$model = $this->getModel('Customer', 'CustomerModel');
		$customer = $model->getItem((int)$id);
		$transaction_id = '';
		if($customer->regis_id > 0){
			$transaction_id = $this->getTransactionID($customer->regis_id);
		}

		$plusResult = $this->plusMoney($customer, 'plus');

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$oCustomer = new stdClass();
		$oCustomer->id = (int)$id;
		$oCustomer->trash_approve = 1;
		$oCustomer->trash_approve_date = date('Y-m-d H:i:s');
		$oCustomer->trash_approve_user = $user->id;
		$updateResult = $db->updateObject('#__customers', $oCustomer, 'id');
		if($updateResult){
			if($id > 0){
				$sqlUpdateXy = "UPDATE #__customers set phone = CONCAT(phone, 'xy')  WHERE  id = ".$id;
				$resultXy = $db->setQuery($sqlUpdateXy)->execute();
			}
			if($transaction_id != '' && $customer->regis_id > 0){
				$reasonReject = $this->getReasonReject($customer->regis_id);
				if ($_SERVER['HTTP_HOST'] != "localhost") {
					$uuid_key = $transaction_id;
					$registrationId = $customer->regis_id;
					$this->callApiAccessTradeReject($uuid_key,$registrationId,$reasonReject);
				}
			}

		}
		if(!$updateResult || !$plusResult) {
			echo -1;
			exit();
		}
		echo 1;
		exit();
	}

	public function successApprove() {
		$id    = $this->input->get('customerId');
		$user = JFactory::getUser();
		$model = $this->getModel('Customer', 'CustomerModel');
		$customer = $model->getItem((int)$id);
		$transaction_id = '';
		if($customer->regis_id > 0){
			$transaction_id = $this->getTransactionID($customer->regis_id);
		}

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		if($customer->regis_id > 0){
			$oRegis = new stdClass();
			$oRegis->id = (int)$customer->regis_id;
			$oRegis->transaction_status = 1;
			$oRegis->transaction_approve_date = date('Y-m-d H:i:s');
			$oRegis->transaction_approve_user = $user->id;
			$updateResult = $db->updateObject('#__registration', $oRegis, 'id');
			if($updateResult){
				if($transaction_id != '' && $customer->regis_id > 0){
					if ($_SERVER['HTTP_HOST'] != "localhost") {
						$uuid_key = $transaction_id;
						$registrationId = $customer->regis_id;
						$this->callApiAccessTradeApprove($uuid_key,$registrationId);
					}
				}
			}
		}

		if(!$updateResult) {
			echo -1;
			exit();
		}
		echo 1;
		exit();
	}

	public function trashReject() {
		$id    = $this->input->get('customerId');

		$model = $this->getModel('Customer', 'CustomerModel');
		$customer = $model->getItem((int)$id);
		$user = JFactory::getUser();
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$oCustomer = new stdClass();
		$oCustomer->id = (int)$id;
		$oCustomer->trash_approve = 2;
		$oCustomer->trash_approve_date = date('Y-m-d H:i:s');
		$oCustomer->trash_approve_user = $user->id;
		$updateResult = $db->updateObject('#__customers', $oCustomer, 'id');
		if(!$updateResult) {
			echo -1;
			exit();
		}
		echo 1;
		exit();
	}

	public function plusMoney($customer, $type) {
		$projectMoney = $this->getProjectByID($customer->project_id);
		$projectPrice = $this->getPriceProjectByOrder($customer->id);
		$priceProject = 0;
		$priceProject = $projectPrice->price;
		if($priceProject == 0){
			$priceProject = $projectMoney['price'];
		}
		switch($type) {
			case 'plus':
				//cong tien
				$user = JFactory::getUser((int)$customer->sale_id);
				$newMoney = $user->money + $priceProject;
				$updateUser = new stdClass();
				$updateUser->id = $user->id;
				$updateUser->money = $newMoney;
				$resultUpdateUser = JFactory::getDbo()->updateObject('#__users', $updateUser, 'id');
			break;
			case 'sub':
				//tru tien
				$user = JFactory::getUser((int)$customer->sale_id);
				$newMoney = $user->money - $priceProject;
				$updateUser = new stdClass();
				$updateUser->id = $user->id;
				$updateUser->money = $newMoney;
				$resultUpdateUser = JFactory::getDbo()->updateObject('#__users', $updateUser, 'id');
			break;
		}

		$saveHistory = $this->_saveTransactionHistory($customer, $projectMoney['price'], $type);

		if($saveHistory && $resultUpdateUser) {
			return true;
		}else {
			return false;
		}

	}

	private function _saveTransactionHistory($customer, $money, $type) {
		$obj = new stdClass();
		$obj->state = 1;
		$obj->created_by = $customer->sale_id;
		switch($type) {
			case 'plus':
				$obj->title = 'Hoàn BizXu Data Sọt rác #' . $customer->id;
				$obj->amount = $money;
			break;
			case 'sub':
				$obj->title = 'Revert hoàn BizXu Data Sọt rác #' . $customer->id;
				$obj->amount = 0-$money;
			break;
		}
		$obj->created_date = date('Y-m-d H:i:s');
		$obj->type_transaction = 'trash';
		$obj->reference_id = $customer->id;
		$obj->status = 'completed';

		if($customer->sale_id > 0){
			$userSaleHistory   = $this->getUserByID($customer->sale_id);
			$obj->current_money = $userSaleHistory->money;
			$obj->current_money_before_operation = $userSaleHistory->money - $money;
		}

		$db = JFactory::getDbo();
		$result = $db->insertObject('#__transaction_history', $obj, 'id');
		if($result) {
		  return true;
		} else {
		  return false;
		}
	}

	public function getProjectByID($project_id)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('is_recruitment,price,title');
        $query->from($db->quoteName('#__projects'));
        $query->where($db->quoteName('id') . " = " . $project_id);
        // $query->where($db->quoteName('state') . " = 1");
        $db->setQuery($query);
        $result = $db->loadAssoc();
        return $result;
	}

	public function callApiAccessTradeReject($uuid_key,$registrationId,$reasonReject)
	{
		// $uuid_key = $_REQUEST['uuid_key'];
		// $registrationId = $_REQUEST['registrationId'];
		$config = new JConfig();
		$reason_reject = '';
		if($reasonReject != ''){
			$reason_reject = "Lý do:".$reasonReject;
		}
		$transaction_cancel_reason = "Hủy vì TVV đánh giá Khách hàng này không chất lượng.".$reason_reject;
		$data = array(
			"transaction_id" => $uuid_key,
			"status" => 2,
			"rejected_reason" => $transaction_cancel_reason,
			"items" => array (
				array(
					"id" => $uuid_key,
					"status" => 2,
					"extra" => array(
						"rejected_reason" => $transaction_cancel_reason
					),
				),
			)
		);
		$token = $config->accesstradeKey;
		$data_string = json_encode($data);
		$ch = curl_init($config->urlPostAccessTrade);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Authorization: Token '.$token.''
		));
		$result = curl_exec($ch);
		$json_result = json_decode($result);
		$is_success = 0;
		$cancel_date = date("Y-m-d H:i:s");
		if($json_result->success === true){
			$is_success = 1;
			if($registrationId > 0){
				$db = JFactory::getDbo();
				$sql = 'UPDATE #__registration set transaction_cancel_reason = '.$db->quote($transaction_cancel_reason).', transaction_status = 2 , transaction_cancel_date = '.$db->quote($cancel_date).'  WHERE id = '.$db->quote($registrationId);
				$result2 = $db->setQuery($sql)->execute();
			}
		}else{
			$is_success = 0;
		}
		return $is_success;
	}


	public function callApiAccessTradeApprove($uuid_key,$registrationId)
	{
		$config = new JConfig();
		$data = array(
			"transaction_id" => $uuid_key,
			"status" => 1,
			"items" => array (
				array(
					"id" => $uuid_key,
					"status" => 1
				),
			)
		);
		$token = $config->accesstradeKey;
		$data_string = json_encode($data);
		$ch = curl_init($config->urlPostAccessTrade);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Authorization: Token '.$token.''
		));
		$result = curl_exec($ch);
		$json_result = json_decode($result);
		$is_success = 0;

		if($json_result->success === true){
			$is_success = 1;
		}else{
			$is_success = 0;
		}
		return $is_success;
	}

	public function updateRegisIDForCustomer(){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from($db->quoteName('#__customers'));
		$query->where($db->quoteName('project_id') . " = 32" );
		$query->where($db->quoteName('state') . " = 1");
		$db->setQuery($query);
		$customers = $db->loadObjectList();
		foreach($customers as $customer){
			$regis_id = 0;
			$regis_id = $this->getRegisIDOfCustomer($customer->phone);
			$oCustomer = new stdClass();
			$oCustomer->id = $customer->id;
			$oCustomer->regis_id = $regis_id;
			$updateResult = $db->updateObject('#__customers', $oCustomer, 'id');
		}
	}

	public function getRegisIDOfCustomer($phone){

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from($db->quoteName('#__registration'));
		$query->where($db->quoteName('project_id') . " = 32" );
		$query->where($db->quoteName('state') . " = 1");
		$query->where($db->quoteName('phone') . " = '".$phone."'");
		$query->order('id ASC');
		$query->setLimit(1);
		$db->setQuery($query);
		$registration = $db->loadObject();
		if($registration->id > 0){
			return $registration->id;
		}else{
			return 0;
		}
	}

	public function getTransactionID($regis_id){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('transaction_id');
		$query->from($db->quoteName('#__registration'));
		$query->where($db->quoteName('id')." = ".$regis_id);
		$db->setQuery($query);
		$result = $db->loadResult();
		return $result;
	}

	public function getReasonReject($regis_id){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('transaction_cancel_reason');
		$query->from($db->quoteName('#__registration'));
		$query->where($db->quoteName('id')." = ".$regis_id);
		$db->setQuery($query);
		$result = $db->loadResult();
		return $result;
	}


	// None AT project
	public function trashBiznetApprove() {
		$id    = $this->input->get('customerId');
		$plusResult = true;
		$user = JFactory::getUser();
		$model = $this->getModel('Customer', 'CustomerModel');
		$customer = $model->getItem((int)$id);
		$model2 = $this->getModel('Customers', 'CustomerModel');
		if($customer->buy_date != '0000-00-00 00:00:00'){
			$checkValidDay = $model2->checkConfirmNumberDayIsOk($customer->buy_date);
			if($checkValidDay == 0){
				echo -2;
				exit();
			}
		}

		$plusResult = $this->plusMoney($customer, 'plus');

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$oCustomer = new stdClass();
		$oCustomer->id = (int)$id;
		$oCustomer->trash_approve = 1;
		$oCustomer->trash_approve_date = date('Y-m-d H:i:s');
		$oCustomer->trash_approve_user = $user->id;
		$updateResult = $db->updateObject('#__customers', $oCustomer, 'id');
		if($id > 0 && $updateResult){
			$sqlUpdateXy = "UPDATE #__customers set phone = CONCAT(phone, 'xy')  WHERE  id = ".$id;
			$resultXy = $db->setQuery($sqlUpdateXy)->execute();
		}
		if(!$updateResult || !$plusResult) {
			echo -1;
			exit();
		}
		echo 1;
		exit();
	}

	public function trashBiznetReject() {
		$id    = $this->input->get('customerId');

		$model = $this->getModel('Customer', 'CustomerModel');
		$customer = $model->getItem((int)$id);
		$user = JFactory::getUser();
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$oCustomer = new stdClass();
		$oCustomer->id = (int)$id;
		$oCustomer->trash_approve = 2;
		$oCustomer->trash_approve_date = date('Y-m-d H:i:s');
		$oCustomer->trash_approve_user = $user->id;
		$updateResult = $db->updateObject('#__customers', $oCustomer, 'id');
		if(!$updateResult) {
			echo -1;
			exit();
		}
		echo 1;
		exit();
	}

	public function updateLandingpageIDForCustomer(){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from($db->quoteName('#__customers'));
		$query->where($db->quoteName('project_id') . " = 22" );
		$query->where($db->quoteName('sale_id') . " = 0");
		$query->where($db->quoteName('from_landingpage') . " = 0");
		$query->where($db->quoteName('state') . " = 1");
		$db->setQuery($query);

		$customers = $db->loadObjectList();
		foreach($customers as $customer){
			$arrPhone = explode('xy' , $customer->phone);
			$regis =  $this->getIDRegisFirstMemberLandingpage($arrPhone[0]);
			if($regis->created_by > 0){
				$oCustomer = new stdClass();
				$oCustomer->id = $customer->id;
				$oCustomer->from_landingpage = $regis->created_by;
				$updateResult = $db->updateObject('#__customers', $oCustomer, 'id');
			}
		}
	}

	public function getIDRegisFirstMemberLandingpage($phone){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from($db->quoteName('#__registration'));
		$query->where($db->quoteName('phone') . " = '".$phone."'" );
		$query->where($db->quoteName('state') . " = 1");
		$query->order('id ASC');
		$query->setLimit(1);
		$db->setQuery($query);
		$regis = $db->loadObject();
		return $regis;
	}

	public function updateFirstDuplicateBCA($project_id){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('id, phone, name, email,project_id,created_date');
		$query->from($db->quoteName('#__registration'));
		$query->where($db->quoteName('project_id') . " = ".$project_id);
		$query->where($db->quoteName('is_exist') . " = 1");
		$query->where($db->quoteName('state') . " = 1");
		$query->order('phone ASC');
		$query->order('id ASC');
		$db->setQuery($query);

		$regiss = $db->loadObjectList();

		echo "<pre>";
		$phone_first = $regiss[0]->phone;

		foreach($regiss as $index => $regis){
			if($regis->phone == $phone_first && $index > 0){
				echo $regis->id."-bilaplai-".$index."-project-".$project_id;
			}else{
				echo $regis->id."-khongbilaplai-".$index."-project-".$project_id;
				$phone_first = $regis->phone;
				$is_duplicate_project_bca = $this->checkIsDuplicateProjectBCA($regis->phone,$project_id);
				echo "project-".$project_id."-is_duplicate_project_bca-".$is_duplicate_project_bca;
				if($is_duplicate_project_bca == 1){
					$oRegis = new stdClass();
					$oRegis->id = (int)$regis->id;
					$oRegis->duplicate_first_bca = 1;
					$updateResult = $db->updateObject('#__registration', $oRegis, 'id');
				}
			}
			// echo $phone_first;
			// if($regis->phone == $phone_first){
			// 	echo $regis->id;
			// }else{
			// 	echo "khong trung";
			// }
			print_r($regis);
		}
		echo "</pre>";
	}

	public function checkIsDuplicateProjectBCA($phone,$project_id){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from($db->quoteName('#__customers'));
		$query->where($db->quoteName('project_id') . " <> ".$project_id );
		$query->where($db->quoteName('phone') . " = '".$phone."'" );
		$query->where($db->quoteName('state') . " = 1");
		$db->setQuery($query);
		$customer = $db->loadObject();
		if($customer->id  > 0){
			return 1;
		}else{
			return 0;
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

	public function getPriceProjectByOrder($customer_id){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from($db->quoteName('#__orders'));
		$query->where("(".$db->quoteName('list_customer')." LIKE '".$customer_id."' OR ".$db->quoteName('list_customer')." LIKE '".$customer_id.",%' OR ".$db->quoteName('list_customer')." LIKE '%,".$customer_id.",%' OR ".$db->quoteName('list_customer')." LIKE '%,".$customer_id."')" );
		$query->setLimit(1);
		$db->setQuery($query);
		//print_r($query->__toString());
		$order = $db->loadObject();
		return $order;
	}

	public function updateFirstDuplicateBCAAllProject(){
		$listProject = array(13,21,22,27,29,30,31,33,34,35,36);
		//$listProject = array(35,36);
		foreach ($listProject as $key => $project_id) {
			$this->updateFirstDuplicateBCA($project_id);
		}
		die;
	}



	public function testFunction(){
		// $created_date = date('2020-11-20 23:45:11');
		// $model = $this->getModel('Customers', 'CustomerModel');
		// $checkConfirmOk = $model->checkConfirmNumberDayIsOk($created_date);
		// echo $checkConfirmOk;
		// $checkConfirmOk = $this->checkIsDuplicateProjectBCA('0385371729');
		// echo $checkConfirmOk;
		$order = $this->getPriceProjectByOrder(65447);
		print_r($order->price);
		die;
	}

}
