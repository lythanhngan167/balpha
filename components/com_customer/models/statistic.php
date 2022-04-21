<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Customer
 * @author     tung hoang <tungvacc@gmail.com>
 * @copyright  2017 tung hoang
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Customer records.
 *
 * @since  1.6
 */
class CustomerModelStatistic extends JModelList
{
	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @see        JController
	 * @since      1.6
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);
	}
 	public function getListCat(){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from($db->quoteName('#__categories'));
		$query->where($db->quoteName('extension')." = 'com_customer'");
		$query->where($db->quoteName('published')." = 1");
		$query->order("rgt ASC");

		$db->setQuery($query);

		$result = $db->loadObjectList();
		return $result;
	}

	public function getCountContact($userid,$status_id,$catid){
		if($_GET['uid'] > 0){
			$userid = $_GET['uid'];
		}
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('count(*)');
		$query->from($db->quoteName('#__customers'));
		$query->where($db->quoteName('category_id')." = ".$catid,'AND');
		$query->where($db->quoteName('state')." = 1",'AND');
		$query->where($db->quoteName('sale_id')." = ".$userid,'AND');
		if($_GET['project'] > 0){
			$query->where($db->quoteName('project_id')." = ".$_GET['project'],'AND');
		}
		if($_GET['startdate'] != "" && $_GET['enddate'] != ""){
			$query->where($db->quoteName('buy_date')." >= '".$_GET['startdate']." 00:00:00'",'AND');
			$query->where($db->quoteName('buy_date')." <= '".$_GET['enddate']." 23:59:59'",'AND');
		}
		$query->where($db->quoteName('status_id')." = '".$status_id."'");
		$db->setQuery($query);

		$result = $db->loadResult();
		return $result;

	}

	public function getCountContactReturnCancel($userid,$status_id,$catid){
		if($_GET['uid'] > 0){
			$userid = $_GET['uid'];
		}
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('count(*)');
		$query->from($db->quoteName('#__return_store'));
		$query->where($db->quoteName('category_id')." = ".$catid,'AND');
		$query->where($db->quoteName('user_id')." = ".$userid,'AND');
		if($_GET['project'] > 0){
			$query->where($db->quoteName('project_id')." = ".$_GET['project'],'AND');
		}

		if($_GET['startdate'] != "" && $_GET['enddate'] != ""){
			$query->where($db->quoteName('buy_date')." >= '".$_GET['startdate']." 00:00:00'",'AND');
			$query->where($db->quoteName('buy_date')." <= '".$_GET['enddate']." 23:59:59'",'AND');
		}

		$query->where($db->quoteName('status_return_cancel')." = '".$status_id."'");
		$db->setQuery($query);

		$result = $db->loadResult();
		return $result;

	}

	public function getRevenueContact($userid,$status_id,$catid){
		if($_GET['uid'] > 0){
			$userid = $_GET['uid'];
		}
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('sum(total_revenue)');
		$query->from($db->quoteName('#__customers'));
		$query->where($db->quoteName('category_id')." = ".$catid,'AND');
		$query->where($db->quoteName('state')." = 1",'AND');
		$query->where($db->quoteName('sale_id')." = ".$userid,'AND');
		if($_GET['project'] > 0){
			$query->where($db->quoteName('project_id')." = ".$_GET['project'],'AND');
		}
		if($_GET['startdate'] != "" && $_GET['enddate'] != ""){
			$query->where($db->quoteName('buy_date')." >= '".$_GET['startdate']." 00:00:00'",'AND');
			$query->where($db->quoteName('buy_date')." <= '".$_GET['enddate']." 23:59:59'",'AND');
		}
		$query->where($db->quoteName('status_id')." = '".$status_id."'");
		$db->setQuery($query);

		$result = $db->loadResult();
		return $result;

	}

	public function getPriceByCat($cat_id){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('note');
		$query->from($db->quoteName('#__categories'));
		$query->where($db->quoteName('id')." = ".$cat_id);
		$db->setQuery($query);
		$result = $db->loadResult();
		return $result;
	}

	public function getListProject(){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from($db->quoteName('#__projects'));
		$query->where($db->quoteName('state')." = 1");
		$db->setQuery($query);
		$result = $db->loadObjectList();
		return $result;
	}

	public function getTotalRevenue($sale_id,$project_id,$from_date,$to_date){
		if($_GET['uid'] > 0){
			$sale_id = $_GET['uid'];
		}
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('sum(total_revenue) as total_revenue');
		$query->from($db->quoteName('#__customers'));
		$query->where($db->quoteName('state')." = 1");
		$query->where($db->quoteName('sale_id')." = ".$sale_id);
		if($project_id > 0){
			$query->where($db->quoteName('project_id')." = ".$project_id);
		}
		if($from_date !='' && $to_date !=''){
			$query->where($db->quoteName('buy_date')." >= '".$from_date."'");
			$query->where($db->quoteName('buy_date')." <= '".$to_date."'");
		}
		$db->setQuery($query);
		$result = $db->loadResult();
		return $result;
	}

	public function getTotalMoney($sale_id,$project_id,$from_date,$to_date){
		if($_GET['uid'] > 0){
			$sale_id = $_GET['uid'];
		}
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('sum(total) as total');
		$query->from($db->quoteName('#__orders'));
		$query->where($db->quoteName('created_by')." = ".$sale_id);
		if($project_id > 0){
			$query->where($db->quoteName('project_id')." = ".$project_id);
		}
		if($from_date !='' && $to_date !=''){
			$query->where($db->quoteName('create_date')." >= '".$from_date."'");
			$query->where($db->quoteName('create_date')." <= '".$to_date."'");
		}
		$db->setQuery($query);
		$result = $db->loadResult();
		return $result;
	}


}
