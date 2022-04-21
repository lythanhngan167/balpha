<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * User controller class.
 *
 * @since  1.6
 */
class UsersControllerUser extends JControllerForm
{
	/**
	 * @var    string  The prefix to use with controller messages.
	 * @since  1.6
	 */
	protected $text_prefix = 'COM_USERS_USER';

	/**
	 * Overrides JControllerForm::allowEdit
	 *
	 * Checks that non-Super Admins are not editing Super Admins.
	 *
	 * @param   array   $data  An array of input data.
	 * @param   string  $key   The name of the key for the primary key.
	 *
	 * @return  boolean  True if allowed, false otherwise.
	 *
	 * @since   1.6
	 */
	protected function allowEdit($data = array(), $key = 'id')
	{
		// Check if this person is a Super Admin
		if (JAccess::check($data[$key], 'core.admin'))
		{
			// If I'm not a Super Admin, then disallow the edit.
			if (!JFactory::getUser()->authorise('core.admin'))
			{
				return false;
			}
		}

		return parent::allowEdit($data, $key);
	}

	/**
	 * Method to run batch operations.
	 *
	 * @param   object  $model  The model.
	 *
	 * @return  boolean  True on success, false on failure
	 *
	 * @since   2.5
	 */
	public function batch($model = null)
	{
		$this->checkToken();

		// Set the model
		$model = $this->getModel('User', '', array());

		// Preset the redirect
		$this->setRedirect(JRoute::_('index.php?option=com_users&view=users' . $this->getRedirectToListAppend(), false));

		return parent::batch($model);
	}

	/**
	 * Function that allows child controller access to model data after the data has been saved.
	 *
	 * @param   JModelLegacy  $model      The data model object.
	 * @param   array         $validData  The validated data.
	 *
	 * @return  void
	 *
	 * @since   3.1
	 */
	protected function postSaveHook(JModelLegacy $model, $validData = array())
	{
		return;
	}

	public function assign_customer()
	{
			$return = array(
					'error' => false,
					'message' => ''
			);
			define('DATA_RETURN', 150);
			include(JPATH_ROOT . '/components/com_project/models/projectss.php');
			include(JPATH_ROOT . '/components/com_project/views/projectss/view.html.php');
			$post = $_POST;
			$user = JFactory::getUser();
			$catId = 151;
			$model = new ProjectModelProjectss();
			$view = new ProjectViewProjectss();
			$countContact = $model->getCountByCat($post['pid'], $catId);
			if ($post['qty'] > $countContact) {
					$return['error'] = true;
					$return['message'] = 'Số lượng khách hàng không đủ.';
					echo json_encode($return);
					exit();
			}


			if ($user->id > 0) {

					$projectInfo = $view->getProjectByID($post['pid']);


					$projectInfo['price'] = $catId != DATA_RETURN ? $projectInfo['price'] : 0;
					$totalPrice = $projectInfo['price'] * $post['qty'];
					if ($view->getMoney($post['user_id']) < $totalPrice) {
							$return['error'] = true;
							$return['message'] = 'Số dư tài khoản không đủ';
							echo json_encode($return);
							exit();
					}

					if ($post['qty'] > 0) {

							$randomCustomer = $model->getRandomCustomer($post['pid'], $catId, $post['qty']);
							if (count($randomCustomer) != $post['qty']) {
									$return['error'] = true;
									$return['message'] = 'Số lượng khách hàng không đủ!';
									echo json_encode($return);
									exit();
							}
							$arrCustomer = array();
							foreach ($randomCustomer as $customer) {
									$arrCustomer[] = $customer->id;
									$model->asignCustomerToSale($post['user_id'], $customer->id);
							}
							if (count($arrCustomer) > 0) {
									$data = array();
									$data['created_by'] = $post['user_id'];
									$data['category_id'] = $catId;
									$data['price'] = $catId != DATA_RETURN ? $projectInfo['price'] : 0;
									$data['quantity'] = $post['qty'];
									$data['project_id'] = $post['pid'];
									$list_customer = implode(",", $arrCustomer);
									$data['list_customer'] = $list_customer;


									$order_id = $model->createOrder($data);

									if ($order_id) {
											// Add history
											$obj = new stdClass();
											$obj->state = 1;
											$obj->created_by = $post['user_id'];
											$obj->title = 'Mua dữ liệu (gán) #' . $order_id;
											$obj->amount = 0 - $totalPrice;
											$obj->created_date = date('Y-m-d H:i:s');
											$obj->type_transaction = 'buydata';
											$obj->status = 'completed';
											$obj->reference_id = $order_id;

											if($post['user_id'] > 0){
											  $userSaleHistory   = $this->getUserByID($post['user_id']);
											  $obj->current_money = $userSaleHistory->money - $totalPrice;
											  $obj->current_money_before_operation = $userSaleHistory->money;
											}

											$db = JFactory::getDbo();
											$db->insertObject('#__transaction_history', $obj, 'id');

											// Descrease money
											$sql = "UPDATE #__users set money = money - " . $totalPrice . ' WHERE id = ' . $post['user_id'];
											$db->setQuery($sql)->execute();
											$userAssign = JFactory::getUser($post['user_id']);
											$return['message'] = 'Gán '.$post['qty'].' khách hàng cho '.$userAssign->name.' thành công!';
											echo json_encode($return);
											exit();

									} else {
											$return['error'] = true;
											$return['message'] = 'Vui lòng thử lại sau...';
											echo json_encode($return);
											exit();
									}


							}

					}


			} else {
					$return['error'] = true;
					$return['message'] = 'Vui lòng thử lại sau...';
					echo json_encode($return);
					exit();
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
}
