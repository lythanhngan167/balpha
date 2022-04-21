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
 * View to edit
 *
 * @since  1.6
 */
class CustomerViewSumary extends JViewLegacy
{
	protected $state;

	protected $item;

	protected $form;

	protected $params;

	protected $canSave;

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
		$app  = JFactory::getApplication();
		$user = JFactory::getUser();
		$this->listCat = $this->getListCat();
		$this->listProject = $this->getListProject();

		$this->params  = $app->getParams('com_customer');
		parent::display($tpl);
	}
	public function getListCat(){
		$model = & $this->getModel('Sumary', 'CustomerModel');
		$listCat = $model->getListCat();
		return $listCat;
	}
	public function getCountContact($userid,$status_id,$catid){
		$model = & $this->getModel('Sumary', 'CustomerModel');
		$counter = $model->getCountContact($userid,$status_id,$catid);
		return $counter;
	}

	public function getRevenueContact($userid,$status_id,$catid){
		$model = & $this->getModel('Sumary', 'CustomerModel');
		$revenue= $model->getRevenueContact($userid,$status_id,$catid);
		return $revenue;
	}

	public function getPriceByCat($cat_id){
		$model = & $this->getModel('Sumary', 'CustomerModel');
		$price= $model->getPriceByCat($cat_id);
		return $price;
	}

	public function getListProject(){
		$model = & $this->getModel('Sumary', 'CustomerModel');
		$project = $model->getListProject();
		return $project;
	}
	public function getTotalRevenue($sale_id,$project_id,$from_date,$to_date){
		$model = & $this->getModel('Sumary', 'CustomerModel');
		$revenue = $model->getTotalRevenue($sale_id,$project_id,$from_date,$to_date);
		return $revenue;
	}
	public function getTotalMoney($sale_id,$project_id,$from_date,$to_date){
		$model = & $this->getModel('Sumary', 'CustomerModel');
		$money = $model->getTotalMoney($sale_id,$project_id,$from_date,$to_date);
		return $money;
	}


}
