<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Registration
 * @author     nganly <lythanhngan167@gmail.com>
 * @copyright  2020 nganly
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
 * Registrations list controller class.
 *
 * @since  1.6
 */
class RegistrationControllerRegistrations extends \Joomla\CMS\MVC\Controller\AdminController
{
	/**
	 * Method to clone existing Registrations
	 *
	 * @return void
     *
     * @throws Exception
	 */
	public function duplicate()
	{
		// Check for request forgeries
		session::checkToken() or jexit(Text::_('JINVALID_TOKEN'));

		// Get id(s)
		$pks = $this->input->post->get('cid', array(), 'array');

		try
		{
			if (empty($pks))
			{
				throw new Exception(Text::_('COM_REGISTRATION_NO_ELEMENT_SELECTED'));
			}

			ArrayHelper::toInteger($pks);
			$model = $this->getModel();
			$model->duplicate($pks);
			$this->setMessage(Text::_('COM_REGISTRATION_ITEMS_SUCCESS_DUPLICATED'));
		}
		catch (Exception $e)
		{
			Factory::getApplication()->enqueueMessage($e->getMessage(), 'warning');
		}

		$this->setRedirect('index.php?option=com_registration&view=registrations');
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
	public function getModel($name = 'registration', $prefix = 'RegistrationModel', $config = array())
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
     *
     * @throws Exception
     */
	public function saveOrderAjax()
	{
		// Get the input
		$input = Factory::getApplication()->input;
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
		Factory::getApplication()->close();
	}

	public function deleteExpiredData(){
		$expire_date =  date("Y-m-d H:i:s",strtotime("-".EXPIRED_DATA_LANDINGPAGE." months"));
		$db = JFactory::getDbo();
		$sql = "UPDATE #__registration set phone = CONCAT(phone, 'xy')  WHERE  RIGHT(phone, 2) != 'xy' AND created_date <= '".$expire_date."'";
		//id = 49364
		$result = $db->setQuery($sql)->execute();
		if($result){
			echo  '1';
		}else{
			echo '0';
		}
		exit();
	}

	public function callApiAccessTradeReject($uuid_key,$registrationId,$duplicate_id,$reasonReject)
	{
		// $uuid_key = $_REQUEST['uuid_key'];
		// $registrationId = $_REQUEST['registrationId'];
		$config = new JConfig();
		$reason_reject = '';
		if($reasonReject != ''){
			$reason_reject = "Lý do:".$reasonReject;
		}else{
			if($duplicate_id == 1){
				$reason_reject = "Lý do: Không nhận tư vấn";
			}
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

	public function duplicateApprove() {
		$id    = $this->input->get('registrationId');
		$plusResult = true;
		$user = JFactory::getUser();
		$model = $this->getModel('Registration', 'RegistrationModel');

		$registration = $model->getItem((int)$id);

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select(array('id', 'sale_id', 'project_id'))
			  ->from('#__customers')
			  ->where($db->quoteName('phone'). ' = ' . $db->quote($registration->phone));
		$db->setQuery($query);
		$customerResult = $db->loadObject();

		if(!$customerResult) {
			return -1;
			exit();
		}

		//$plusResult = $this->plusMoney($customerResult, 'plus');

		$query->clear();
		$oRegistration = new stdClass();
		$oRegistration->id = (int)$id;
		$oRegistration->duplicate_status = 2;
		$oRegistration->duplicate_status_date = date('Y-m-d H:i:s');
		$oRegistration->duplicate_approve_user = $user->id;
		$updateResult = $db->updateObject('#__registration', $oRegistration, 'id');
		if($updateResult){
			$uuid_key = $registration->transaction_id;
			$registrationId = $registration->id;
			$reasonReject = $registration->duplicate_note;
			$duplicate_id = $registration->duplicate_id;
			if ($_SERVER['HTTP_HOST'] != "localhost") {
				if($uuid_key != '' && $registrationId > 0){
					$this->callApiAccessTradeReject($uuid_key,$registrationId,$duplicate_id,$reasonReject);
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

	public function duplicateReject() {
		$id    = $this->input->get('registrationId');

		$model = $this->getModel('Registration', 'RegistrationModel');
		$registration = $model->getItem((int)$id);
		$user = JFactory::getUser();
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$oRegistration = new stdClass();
		$oRegistration->id = (int)$id;
		$oRegistration->duplicate_status = 3;
		$oRegistration->duplicate_status_date = date('Y-m-d H:i:s');
		$oRegistration->duplicate_approve_user = $user->id;
		$updateResult = $db->updateObject('#__registration', $oRegistration, 'id');
		if(!$updateResult) {
			echo -1;
			exit();
		}
		echo 1;
		exit();
	}

	public function duplicateSuccess() {
		$id    = $this->input->get('registrationId');
		$model = $this->getModel('Registration', 'RegistrationModel');
		$oRegis = $model->getItem((int)$id);
		$user = JFactory::getUser();
		$resultATApprove = 0;
		// print_r($oRegis);
		// die;
		if($oRegis->transaction_id != '' && $user->id > 0){
			if ($_SERVER['HTTP_HOST'] != "localhost") {
				$resultATApprove = $this->callApiAccessTradeApprove($oRegis->transaction_id,$oRegis->id);
				if($resultATApprove == 1){
					$this->approveSuccessRegistration($oRegis->id,$user->id);
				}
			}else{
				$resultATApprove = 1;
			}
		}
		if($resultATApprove == 1) {
			echo '1';
		}else{
			echo '0';
		}
		exit();
	}

	public function plusMoney($customer, $type) {
		$projectMoney = $this->getProjectByID($customer->project_id);

		switch($type) {
			case 'plus':
				//cong tien
				$user = JFactory::getUser((int)$customer->sale_id);
				$newMoney = $user->money + $projectMoney['price']/2;
				$updateUser = new stdClass();
				$updateUser->id = $user->id;
				$updateUser->money = $newMoney;
				$resultUpdateUser = JFactory::getDbo()->updateObject('#__users', $updateUser, 'id');
			break;
			case 'sub':
				//tru tien
				$user = JFactory::getUser((int)$customer->sale_id);
				$newMoney = $user->money - $projectMoney['price']/2;
				$updateUser = new stdClass();
				$updateUser->id = $user->id;
				$updateUser->money = $newMoney;
				$resultUpdateUser = JFactory::getDbo()->updateObject('#__users', $updateUser, 'id');
			break;
		}

		$saveHistory = $this->_saveTransactionHistory($customer, $projectMoney['price']/2, $type);

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
				$obj->title = 'Hoàn tiền Remarketing #' . $customer->id;
				$obj->amount = $money;
			break;
			case 'sub':
				$obj->title = 'Revert hoàn tiền Remarketing #' . $customer->id;
				$obj->amount = 0-$money;
			break;
		}
		$obj->created_date = date('Y-m-d H:i:s');
		$obj->type_transaction = 'duplicate';
		$obj->reference_id = $customer->id;
		$obj->status = 'completed';
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

	public function callApiAccessTradeApprove($uuid_key,$registrationId)
	{
		$config = new JConfig();
		$data = array(
			"transaction_id" => $uuid_key,
			"status" => 1,
			"items" => array ()
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

	public function approveSuccessRegistration($regis_id,$user_id)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$oRegis = new stdClass();
		$oRegis->id = $regis_id;
		$oRegis->transaction_status = 1;
		$oRegis->transaction_approve_date = date('Y-m-d H:i:s');
		$oRegis->transaction_approve_user = $user_id;
		$updateResult = $db->updateObject('#__registration', $oRegis, 'id');
		return $updateResult;
	}

}
