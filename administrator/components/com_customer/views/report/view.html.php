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
class CustomerViewReport extends JViewLegacy
{

	protected $state;
	protected $form;
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
		$model = $this->getModel();
		$this->state = $this->get('State');
		$this->form  = $this->get('Form');
		$this->listProjects = $model->getAllProjects();

		$this->newData = 0;
		$this->regisAgainData = 0;
		$this->existData = 0;
		$this->trashData = 0;
		$this->trashDataReturnMoney = 0;


		$month = $this->state->get('filter.month', date('m'));
		$year = $this->state->get('filter.year', date('Y'));
		$project_id = $this->state->get('filter.project_id', AT_PROJECT);

		$from_date = $_REQUEST['jform']['from_date'];
		$to_date = $_REQUEST['jform']['to_date'];

		$this->newData = $model->countNewData($project_id, $year, $month, $from_date, $to_date);
		$this->regisAgainData = $model->countRegisAgainData($project_id, $year, $month, $from_date, $to_date);
		$this->existData = $model->countExistData($project_id, $year, $month, $from_date, $to_date);
		$this->trashData = $model->countTrashData($project_id, $year, $month, $from_date, $to_date);
		$this->trashDataReturnMoney = $model->countTrashDataReturnMoney($project_id, $year, $month, $from_date, $to_date);
		$this->confirmedData = $model->countConfirmedData($project_id, $year, $month, $from_date, $to_date);
		$this->rejectedData = $model->countTrashRejectedData($project_id, $year, $month, $from_date, $to_date);
		$this->pendingData = $model->countPendingData($project_id, $year, $month, $from_date, $to_date);

		$user = JFactory::getUser();
		$groups = \JAccess::getGroupsByUser($user->id, false);
		//$this->customerActive = $model->getCustomerLandingpageByPhone($phone_biznet_id);
		$this->db            = JFactory::getDbo();

		if($_REQUEST['clear'] == 1){
			$this->state->set('filter.month', '');
			$this->state->set('filter.year', '');

			$this->state->set('filter.project_id', 0);
			$app =& JFactory::getApplication();
			$app->redirect('index.php?option=com_customer&view=report&filter[month]=&jform[filter][from_date]=&jform[filter][to_date]=');
		}

		JToolBarHelper::title('Thống kê Khách hàng theo Dự án', 'customers.png');
		parent::display($tpl);
	}


}
