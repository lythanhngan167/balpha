<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Customer
 * @author     tung hoang <tungvacc@gmail.com>
 * @copyright  2017 tung hoang
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View class for a list of Customer.
 *
 * @since  1.6
 */
class CustomerViewCustomers extends JViewLegacy
{
	protected $items;

	protected $pagination;

	protected $state;

	protected $params;

	/**
	 * Display the view
	 *
	 * @param   string  $tpl  Template name
	 *
	 * @return void
	 *
	 * @throws Exception
	 */

	public function display($tpl = null)
	{

		if($_GET['ajax'] == '1'){
			//ajax
			$model = $this->getModel('Customers', 'CustomerModel');
			if($_GET['type'] == 'call' && $_GET['reason'] !='' && $_GET['customer_id'] !=''){
				$reason = $_GET['reason'];
				$customer_id = $_GET['customer_id'];
				$data = array();
				$data['reason'] = $reason;
				$data['customer_id'] = $customer_id;

				$user = JFactory::getUser();
				if($user->id > 0){
					$data['user_id'] = $user->id;
					$call = $model->saveCall($data);
					echo $call;
				}else{
					echo "-1";
				}
			}
			if($_GET['type'] == 'status'){
				$status_id = $_GET['status_id'];
				$customer_id = $_GET['customer_id'];
				$total_revenue = $_GET['total_revenue'];
				$data = array();
				$data['status_id'] = $status_id;
				$data['customer_id'] = $customer_id;
				$data['total_revenue'] = $total_revenue;

				$user = JFactory::getUser();
				if($user->id > 0){
					$data['user_id'] = $user->id;
          if($status_id == 6){
              //Get current customer category
              $db = JFactory::getDbo();
              $sql = 'SELECT category_id FROM #__customers WHERE id = '.(int)$customer_id;
              $cat = $db->setQuery($sql)->loadResult();
              if($cat == DATA_RETURN){
                  $sql = 'UPDATE #__customers SET payback = 0, sale_id = 0, status_id = 1 WHERE id = ' . (int)$customer_id;
                  $call = $db->setQuery($sql)->execute();

              }else{
                  $call = $model->saveStatus($data);
              }

          }else{
							JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_customer/models', 'CustomerModel');
							$modelCustomer = JModelLegacy::getInstance('Customer', 'CustomerModel', array('ignore_request' => true));
							if($customer_id > 0){
								$oCustomer = $modelCustomer->getCustomer($customer_id);
							}
							$tranferAT = 0;
							if($oCustomer->status_id == 1){ // trang thai ban dau
								$tranferAT = 1;
							}
              $call = $model->saveStatus($data);

							if($oCustomer->project_id == AT_PROJECT && $tranferAT == 1){
								if($oCustomer->regis_id > 0){
									$oRegis = $modelCustomer->getRegistration($oCustomer->regis_id);
									if($oRegis->transaction_id != ''){
										if ($_SERVER['HTTP_HOST'] != "localhost") {
											// $resultATApprove = $this->callApiAccessTradeApprove($oRegis->transaction_id,$oCustomer->regis_id);
											// if($resultATApprove == 1){
											// 	$modelCustomer->approveSuccessRegistration($oCustomer->regis_id,$user->id);
											// }
										}
									}
								}
							}
          }
					echo $call;
				}else{
					echo "-1";
				}
			}
			if($_GET['type'] == 'color'){
				$color_id = $_GET['color_id'];
				$customer_id = $_GET['customer_id'];
				$data = array();
				$data['color_id'] = $color_id;
				$data['customer_id'] = $customer_id;

				$user = JFactory::getUser();
				if($user->id > 0){
					$call = $model->saveColor($data);
					echo $call;
				}else{
					echo "-1";
				}
			}
			if($_GET['type'] == 'revenue'){
				$customer_id = $_GET['customer_id'];
				$total_revenue = $_GET['total_revenue'];
				$data = array();
				$data['customer_id'] = $customer_id;
				$data['total_revenue'] = $total_revenue;
				$user = JFactory::getUser();
				if($user->id > 0){
					$revenue = $model->updateRevenue($data);
					echo $revenue;
				}else{
					echo "-1";
				}
			}
			if($_GET['type'] == 'address'){
				$customer_id = $_GET['customer_id'];
				$address = $_GET['address'];
				$data = array();
				$data['customer_id'] = $customer_id;
				$data['place'] = $address;
				$user = JFactory::getUser();
				if($user->id > 0){
					$address = $model->updateAddress($data);
					echo $address;
				}else{
					echo "-1";
				}
			}
			exit();
		}else{
			if($_GET['returnStore24h'] == '1'){
				$model = & $this->getModel('Customers', 'CustomerModel');
				$time = 24;
				$result = $model->returnStore24h($time);
				if($result){
					echo "Chạy kiểm tra hết hạn thành công!";
				}else{
					echo "Chạy kiểm tra hết hạn thất bại!";
				}


			}else{
				if($_GET['test'] == 1){
					$model = & $this->getModel('Customers', 'CustomerModel');
					$result = $this->test();
				}else{

					$app = JFactory::getApplication();
					$model = & $this->getModel('Customers', 'CustomerModel');

					$user = JFactory::getUser();

					$totalCaring = $model->getTotalCaring($user->id);
					$this->totalCaring = $totalCaring;
					$this->state = $this->get('State');
					$this->items = $this->get('Items');
					$this->pagination = $this->get('Pagination');
					$this->params = $app->getParams('com_customer');

					$this->filterForm = $this->get('FilterForm');
					$this->activeFilters = $this->get('ActiveFilters');

					$menuid = $app->getMenu()->getActive()->id;

					$this->status_id = $model->getNote($menuid);

					// Check for errors.
					if (count($errors = $this->get('Errors')))
					{
						throw new Exception(implode("\n", $errors));
					}

					$this->_prepareDocument();
					parent::display($tpl);
				}

			}
		}
	}


	public function getLatestNote($customer_id){
		$model = & $this->getModel('Customers', 'CustomerModel');
		$lastestNote = $model->getLatestNote($customer_id);
		return $lastestNote;
	}

	public function returnStore24h($time){
		$model = & $this->getModel('Customers', 'CustomerModel');
		$result = $model->returnStore24h($time);
		return $result;
	}

	public function test(){
		$model = & $this->getModel('Customers', 'CustomerModel');
		$result = $model->test();
		return $result;
	}

	/**
	 * Prepares the document
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	protected function _prepareDocument()
	{
		$app   = JFactory::getApplication();
		$menus = $app->getMenu();
		$title = null;

		// Because the application sets a default page title,
		// we need to get it from the menu item itself
		$menu = $menus->getActive();

		if ($menu)
		{
			$this->params->def('page_heading', $this->params->get('page_title', $menu->title));
		}
		else
		{
			$this->params->def('page_heading', JText::_('COM_CUSTOMER_DEFAULT_PAGE_TITLE'));
		}

		$title = $this->params->get('page_title', '');

		if (empty($title))
		{
			$title = $app->get('sitename');
		}
		elseif ($app->get('sitename_pagetitles', 0) == 1)
		{
			$title = JText::sprintf('JPAGETITLE', $app->get('sitename'), $title);
		}
		elseif ($app->get('sitename_pagetitles', 0) == 2)
		{
			$title = JText::sprintf('JPAGETITLE', $title, $app->get('sitename'));
		}

		$this->document->setTitle($title);

		if ($this->params->get('menu-meta_description'))
		{
			$this->document->setDescription($this->params->get('menu-meta_description'));
		}

		if ($this->params->get('menu-meta_keywords'))
		{
			$this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
		}

		if ($this->params->get('robots'))
		{
			$this->document->setMetadata('robots', $this->params->get('robots'));
		}
	}

	/**
	 * Check if state is set
	 *
	 * @param   mixed  $state  State
	 *
	 * @return bool
	 */
	public function getState($state)
	{
		return isset($this->state->{$state}) ? $this->state->{$state} : false;
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



}
