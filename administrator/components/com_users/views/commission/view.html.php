<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
require_once(JPATH_SITE . '/components/com_eshop/helpers/helper.php');
/**
 * View class for a list of users.
 *
 * @since  1.6
 */
class UsersViewCommission extends JViewLegacy
{
	/**
	 * The item data.
	 *
	 * @var   object
	 * @since 1.6
	 */
	protected $items;

	/**
	 * The pagination object.
	 *
	 * @var   JPagination
	 * @since 1.6
	 */
	protected $pagination;

	/**
	 * The model state.
	 *
	 * @var   JObject
	 * @since 1.6
	 */
	protected $state;

	/**
	 * A JForm instance with filter fields.
	 *
	 * @var    JForm
	 * @since  3.6.3
	 */
	 public $filterForm;

	/**
	 * An array with active filters.
	 *
	 * @var    array
	 * @since  3.6.3
	 */
	public $activeFilters;

	/**
	 * An ACL object to verify user rights.
	 *
	 * @var    JObject
	 * @since  3.6.3
	 */
	 protected $canDo;

	/**
	 * An instance of JDatabaseDriver.
	 *
	 * @var    JDatabaseDriver
	 * @since  3.6.3
	 */
	 protected $db;

	/**
	 * Display the view
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  void
	 */
	public function display($tpl = null)
	{

		if($_GET['ajax'] == 1){
			if($_GET['type'] == 'confirmPayment'){
				$userid = $_GET['userid'];
				$monthyear = $_GET['monthyear'];
				$total = $_GET['total'];
				if($userid == '' || $monthyear == '' || $total == ""){
					echo 0;
				}else{
					$result = $this->confirmPayment($userid, $monthyear, $total);
					echo $result ;
				}
				exit();
			}
			if($_GET['type'] == 'blockUser'){
				$userid = $_GET['userid'];
				$status = $_GET['status'];
				if($userid > 0){
					$result = $this->blockUser($userid,$status);
					echo $result;
				}else{
					echo 0;
				}
				exit();
			}

			if($_GET['type'] == 'changeLevel'){
				$userid = $_GET['userid'];
				$level = (int)$_GET['level'];
				if($userid > 0 && $level >= 1 && $level <= 5){
					$result = $this->changeLevel($userid, $level);
					echo $result;
				}else{
					echo 0;
				}
				exit();
			}
			if($_GET['type'] == 'banUser'){
				$userid = $_GET['userid'];
				if($userid > 0){
					$result = $this->banUser($userid);
					echo $result;
				}else{
					echo 0;
				}
				exit();
			}

		}else{
			$this->items         = $this->get('Items');
			$this->pagination    = $this->get('Pagination');
			$this->state         = $this->get('State');

			$month_default =  $this->state->get('filter.month', '');
			$year_default =  $this->state->get('filter.year', '');

			if($month_default == ''){
				$this->state->set('filter.month', date('m'));
			}
			if($year_default == ''){
				$this->state->set('filter.year', date('Y'));
			}

			// $user_id = 2640;
			// $month = '02';
			// $year = '2020';
			// $money = EshopHelper::getAmountGroup($user_id,$month,$year);
			//print_r($money);
			// $sumMoney = 0;
			// foreach ($listOrders as $key => $order) {
			// 	if($order->cms_c1 == $user_id){
			// 		$sumMoney = $sumMoney + $order->cms_money_c1;
			// 	}
			// 	if($order->cms_c2 == $user_id){
			// 		$sumMoney = $sumMoney + $order->cms_money_c2;
			// 	}
			// 	if($order->cms_c3 == $user_id){
			// 		$sumMoney = $sumMoney + $order->cms_money_c3;
			// 	}
			// 	if($order->cms_c4 == $user_id){
			// 		$sumMoney = $sumMoney + $order->cms_money_c4;
			// 	}
			// 	if($order->cms_c5 == $user_id){
			// 		$sumMoney = $sumMoney + $order->cms_money_c5;
			// 	}
			// }
			// echo $sumMoney;



			$this->filterForm    = $this->get('FilterForm');
			$this->activeFilters = $this->get('ActiveFilters');
			$this->canDo         = JHelperContent::getActions('com_users');

			$this->db            = JFactory::getDbo();

			if($_REQUEST['clear'] == 1){
				$this->state->set('filter.month', date('m'));
				$this->state->set('filter.year', date('Y'));
				$this->state->set('filter.leader', '');
				$this->state->set('filter.search', '');
				$app =& JFactory::getApplication();
				$app->redirect('index.php?option=com_users&view=commission&filter[search]=');
			}


			$this->listLeader = $this->getListLeader();
			// Include the component HTML helpers.
			JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

			$this->addToolbar();
			//JToolBarHelper::custom('', '', '', 'Add Test Application');
			 JToolBarHelper::title("Xuất Excel HH Đại lý");
			JHtmlSidebar::addEntry(
	            '',
	            '#',1
	        );
			$this->sidebar = JHtmlSidebar::render();
			parent::display($tpl);
		}

	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	protected function addToolbar()
	{
		$canDo = $this->canDo;
		$user  = JFactory::getUser();

		// Get the toolbar object instance
		$bar = JToolbar::getInstance('toolbar');

		JToolbarHelper::title(JText::_('COM_USERS_VIEW_USERS_TITLE'), 'users user');

		if ($canDo->get('core.create'))
		{
			JToolbarHelper::addNew('user.add');
		}

		if ($canDo->get('core.edit'))
		{
			JToolbarHelper::editList('user.edit');
		}

		if ($canDo->get('core.edit.state'))
		{
			JToolbarHelper::divider();
			JToolbarHelper::publish('users.activate', 'COM_USERS_TOOLBAR_ACTIVATE', true);
			JToolbarHelper::unpublish('users.block', 'COM_USERS_TOOLBAR_BLOCK', true);
			JToolbarHelper::custom('users.unblock', 'unblock.png', 'unblock_f2.png', 'COM_USERS_TOOLBAR_UNBLOCK', true);
			JToolbarHelper::divider();
		}

		if ($canDo->get('core.delete'))
		{
			JToolbarHelper::deleteList('JGLOBAL_CONFIRM_DELETE', 'users.delete', 'JTOOLBAR_DELETE');
			JToolbarHelper::divider();
		}

		// Add a batch button
		if ($user->authorise('core.create', 'com_users')
			&& $user->authorise('core.edit', 'com_users')
			&& $user->authorise('core.edit.state', 'com_users'))
		{
			$title = JText::_('JTOOLBAR_BATCH');

			// Instantiate a new JLayoutFile instance and render the batch button
			$layout = new JLayoutFile('joomla.toolbar.batch');

			$dhtml = $layout->render(array('title' => $title));
			$bar->appendButton('Custom', $dhtml, 'batch');
		}

		if ($canDo->get('core.admin') || $canDo->get('core.options'))
		{
			JToolbarHelper::preferences('com_users');
			JToolbarHelper::divider();
		}

		JToolbarHelper::help('JHELP_USERS_USER_MANAGER');
	}

	/**
	 * Returns an array of fields the table can be sorted by
	 *
	 * @return  array  Array containing the field name to sort by as the key and display text as value
	 *
	 * @since   3.0
	 */
	protected function getSortFields()
	{
		return array(
				'a.name' => JText::_('COM_USERS_HEADING_NAME'),
				'a.username' => JText::_('JGLOBAL_USERNAME'),
				'a.block' => JText::_('COM_USERS_HEADING_ENABLED'),
				'a.activation' => JText::_('COM_USERS_HEADING_ACTIVATED'),
				'a.email' => JText::_('JGLOBAL_EMAIL'),
				'a.lastvisitDate' => JText::_('COM_USERS_HEADING_LAST_VISIT_DATE'),
				'a.registerDate' => JText::_('COM_USERS_HEADING_REGISTRATION_DATE'),
				'a.id' => JText::_('JGRID_HEADING_ID'),
				'a.level' => 'Cấp ĐL'
		);
	}

	public function  getMoneySaleByMonth($saleid, $month, $year){
		$model = $this->getModel('Commission', 'UsersModel');
		$result = $model->getMoneySaleByMonth($saleid, $month, $year);
		return $result;
	}

	public function  confirmPayment($userid, $monthyear, $total){
		$model = $this->getModel('Commission', 'UsersModel');
		$paymentNumber = $this->checkPayment($userid, $monthyear);
		if($paymentNumber >= 1 ){
			return 2;
		}
		$result = $model->confirmPayment($userid, $monthyear, $total);
		if($result){
			return 1;
		}else{
			return 0;
		}
	}
	public function  checkPayment($userid, $monthyear){
		$model = $this->getModel('Commission', 'UsersModel');
		$result = $model->checkPayment($userid, $monthyear);
		if($result >= 1){
			return 1;
		}else{
			return 0;
		}
	}

	public function blockUser($userid,$status)
	{
		$model = $this->getModel('Commission', 'UsersModel');
		$result = $model->blockUser($userid,$status);
		if($result == 1){
			return 1;
		}else{
			return 0;
		}
	}

	public function banUser($userid)
	{
		$model = $this->getModel('Commission', 'UsersModel');
		$result = $model->banUser($userid);
		if($result == 1){
			return 1;
		}else{
			return 0;
		}
	}
	public function changeLevel($userid,$level)
	{
		$model = $this->getModel('Commission', 'UsersModel');
		$result = $model->changeLevel($userid,$level);
		if($result == 1){
			return 1;
		}else{
			return 0;
		}
	}

	public function returnToStore($userid)
	{
		$model = $this->getModel('Commission', 'UsersModel');
		$result = $model->returnToStore($userid);
		if($result == 1){
			return 1;
		}else{
			return 0;
		}
	}

	public function getListLeader()
	{
		$model = $this->getModel('Commission', 'UsersModel');
		$result = $model->getListLeader();
		return $result;
	}


}
