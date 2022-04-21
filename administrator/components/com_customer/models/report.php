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
class CustomerModelReport extends JModelList
{
	/**
	 * @var      string    The prefix to use with controller messages.
	 * @since    1.6
	 */
	protected $text_prefix = 'COM_CUSTOMER';

	/**
	 * @var   	string  	Alias to manage history control
	 * @since   3.2
	 */
	public $typeAlias = 'com_customer.report';

	/**
	 * @var null  Item data
	 * @since  1.6
	 */
	protected $item = null;

	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param   string  $type    The table type to instantiate
	 * @param   string  $prefix  A prefix for the table class name. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return    JTable    A database object
	 *
	 * @since    1.6
	 */
	public function getTable($type = 'Customer', $prefix = 'CustomerTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Method to get the record form.
	 *
	 * @param   array    $data      An optional array of data for the form to interogate.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 *
	 * @return  JForm  A JForm object on success, false on failure
	 *
	 * @since    1.6
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Initialise variables.
		$app = JFactory::getApplication();

		// Get the form.
		$form = $this->loadForm(
			'com_customer.report', 'report',
			array('control' => 'jform',
				'load_data' => $loadData
			)
		);

		if (empty($form))
		{
			return false;
		}

		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return   mixed  The data for the form.
	 *
	 * @since    1.6
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_customer.edit.customer.data', array());

		if (empty($data))
		{


			$data = $this->item;

			// Support for multiple or not foreign key field: project_id
			$array = array();

			foreach ((array) $data->project_id as $value)
			{
				if (!is_array($value))
				{
					$array[] = $value;
				}
			}

			$data->project_id = implode(',',$array);

			// Support for multiple or not foreign key field: status_id
			$array = array();

			foreach ((array) $data->status_id as $value)
			{
				if (!is_array($value))
				{
					$array[] = $value;
				}
			}

			$data->status_id = $array;
		}
			return $data;
	}

	public function getAllProjects()
  {
      $db = JFactory::getDbo();
      $query = $db->getQuery(true);
      $query
          ->select('*')
          ->from('#__projects')
          ->where('state = 1')
					->order('title ASC');
      $db->setQuery($query);
      return $db->loadObjectList();
  }

	public function countNewData($project_id, $year, $month, $from_date, $to_date){
		if($project_id > 0){
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('count(*)');
			$query->from($db->quoteName('#__customers'));
			$query->where($db->quoteName('project_id') . " = ".$project_id);
			$query->where($db->quoteName('state') . " = 1");
			if($from_date != '' && $to_date != ''){
					$query->where("create_date >= '".$from_date." 00:00:00'");
					$query->where("create_date <= '".$to_date." 23:59:59'");
			}else{
				if($year != ''){
					if($month != ''){
						$query->where("DATE_FORMAT(create_date,'%Y-%m') = '".$year."-".$month."'");
					}else{
						$query->where("DATE_FORMAT(create_date,'%Y') = '".$year."'");
					}
				}
			}

			$db->setQuery($query);
			// echo $query->__toString();
			// die;
			$result = $db->loadResult();
			return $result;
		}else{
			return 0;
		}

	}

	public function countTrashData($project_id, $year, $month, $from_date, $to_date){
		if($project_id > 0){
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('count(*)');
			$query->from($db->quoteName('#__customers'));
			$query->where($db->quoteName('project_id') . " = ".$project_id);
			$query->where($db->quoteName('status_id') . " = 99");
			$query->where($db->quoteName('state') . " = 1");
			if($from_date != '' && $to_date != ''){
					$query->where("create_date >= '".$from_date." 00:00:00'");
					$query->where("create_date <= '".$to_date." 23:59:59'");
			}else{
				if($year != ''){
					if($month != ''){
						$query->where("DATE_FORMAT(create_date,'%Y-%m') = '".$year."-".$month."'");
					}else{
						$query->where("DATE_FORMAT(create_date,'%Y') = '".$year."'");
					}
				}
			}

			$db->setQuery($query);
			$result = $db->loadResult();
			return $result;
		}else{
			return 0;
		}

	}


	public function countTrashDataReturnMoney($project_id, $year, $month, $from_date, $to_date){
		if($project_id > 0){
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('count(*)');
			$query->from($db->quoteName('#__customers'));
			$query->where($db->quoteName('project_id') . " = ".$project_id);
			$query->where($db->quoteName('status_id') . " = 99");
			$query->where($db->quoteName('state') . " = 1");
			$query->where($db->quoteName('trash_approve') . " = 1");
			if($from_date != '' && $to_date != ''){
					$query->where("create_date >= '".$from_date." 00:00:00'");
					$query->where("create_date <= '".$to_date." 23:59:59'");
			}else{
				if($year != ''){
					if($month != ''){
						$query->where("DATE_FORMAT(create_date,'%Y-%m') = '".$year."-".$month."'");
					}else{
						$query->where("DATE_FORMAT(create_date,'%Y') = '".$year."'");
					}
				}
			}

			$db->setQuery($query);
			$result = $db->loadResult();
			return $result;
		}else{
			return 0;
		}

	}


	public function countExistData($project_id, $year, $month, $from_date, $to_date){
		if($project_id > 0){
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('count(distinct a.phone)');
			$query->from($db->quoteName('#__registration')." AS a");
			$query->where($db->quoteName('a.project_id') . " = ".$project_id);
			$query->where($db->quoteName('a.state') . " = 1");
			$query->where($db->quoteName('a.is_exist') . " = 1");
			if($from_date != '' && $to_date != ''){
					$query->where("a.created_date >= '".$from_date." 00:00:00'");
					$query->where("a.created_date <= '".$to_date." 23:59:59'");
			}else{
				if($year != ''){
					if($month != ''){
						$query->where("DATE_FORMAT(a.created_date,'%Y-%m') = '".$year."-".$month."'");
					}else{
						$query->where("DATE_FORMAT(a.created_date,'%Y') = '".$year."'");
					}
				}
			}

			$db->setQuery($query);
			// echo $query->__toString();
			// die;
			$result = $db->loadResult();
			return $result;
		}else{
			return 0;
		}

	}

	public function countRegisAgainData($project_id, $year, $month, $from_date, $to_date){
		if($project_id > 0){
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('count(*)');
			$query->from($db->quoteName('#__registration')." AS a");
			$query->where($db->quoteName('a.project_id') . " = ".$project_id);
			$query->where($db->quoteName('a.state') . " = 1");
			$query->where($db->quoteName('a.duplicate_first_bca') . " = 1");
			if($from_date != '' && $to_date != ''){
					$query->where("a.created_date >= '".$from_date." 00:00:00'");
					$query->where("a.created_date <= '".$to_date." 23:59:59'");
			}else{
				if($year != ''){
					if($month != ''){
						$query->where("DATE_FORMAT(a.created_date,'%Y-%m') = '".$year."-".$month."'");
					}else{
						$query->where("DATE_FORMAT(a.created_date,'%Y') = '".$year."'");
					}
				}
			}

			$db->setQuery($query);
			$result = $db->loadResult();
			return $result;
		}else{
			return 0;
		}

	}

	public function countConfirmedData($project_id, $year, $month, $from_date, $to_date){
		if($project_id > 0){
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('count(*)');
			$query->from($db->quoteName('#__registration')." AS a");
			$query->where($db->quoteName('a.project_id') . " = ".$project_id);
			$query->where($db->quoteName('a.state') . " = 1");
			$query->where($db->quoteName('a.transaction_status') . " = 1");
			if($from_date != '' && $to_date != ''){
					$query->where("a.created_date >= '".$from_date." 00:00:00'");
					$query->where("a.created_date <= '".$to_date." 23:59:59'");
			}else{
				if($year != ''){
					if($month != ''){
						$query->where("DATE_FORMAT(a.created_date,'%Y-%m') = '".$year."-".$month."'");
					}else{
						$query->where("DATE_FORMAT(a.created_date,'%Y') = '".$year."'");
					}
				}
			}

			$db->setQuery($query);
			$result = $db->loadResult();
			return $result;
		}else{
			return 0;
		}
	}

	public function countPendingData($project_id, $year, $month, $from_date, $to_date){
		if($project_id > 0){
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('count(*)');

			$query->from($db->quoteName('#__customers')." AS cus");
			$query->join('INNER', '#__registration AS regis ON regis.id = cus.regis_id');
			$query->where($db->quoteName('cus.project_id') . " = ".$project_id);
			$query->where($db->quoteName('cus.status_id') . " = 1");
			$query->where($db->quoteName('cus.state') . " = 1");
			$query->where($db->quoteName('regis.transaction_status') . " = 0"); // pending
			if($from_date != '' && $to_date != ''){
					$query->where("cus.create_date >= '".$from_date." 00:00:00'");
					$query->where("cus.create_date <= '".$to_date." 23:59:59'");
			}else{
				if($year != ''){
					if($month != ''){
						$query->where("DATE_FORMAT(cus.create_date,'%Y-%m') = '".$year."-".$month."'");
					}else{
						$query->where("DATE_FORMAT(cus.create_date,'%Y') = '".$year."'");
					}
				}
			}

			$db->setQuery($query);
			$result = $db->loadResult();
			return $result;
		}else{
			return 0;
		}
	}


	public function countTrashRejectedData($project_id, $year, $month, $from_date, $to_date){
		if($project_id > 0){
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('count(*)');
			$query->from($db->quoteName('#__customers')." AS cus");
			$query->join('INNER', '#__registration AS regis ON regis.id = cus.regis_id');
			$query->where($db->quoteName('cus.project_id') . " = ".$project_id);
			$query->where($db->quoteName('cus.status_id') . " = 99");
			$query->where($db->quoteName('cus.state') . " = 1");
			$query->where($db->quoteName('regis.transaction_status') . " = 2"); // rejected
			if($from_date != '' && $to_date != ''){
					$query->where("cus.create_date >= '".$from_date." 00:00:00'");
					$query->where("cus.create_date <= '".$to_date." 23:59:59'");
			}else{
				if($year != ''){
					if($month != ''){
						$query->where("DATE_FORMAT(cus.create_date,'%Y-%m') = '".$year."-".$month."'");
					}else{
						$query->where("DATE_FORMAT(cus.create_date,'%Y') = '".$year."'");
					}
				}
			}

			$db->setQuery($query);
			$result = $db->loadResult();
			return $result;
		}else{
			return 0;
		}
	}


	public function listRejectAT($project_id,$month, $year){
		$result = array();
		if($project_id > 0){
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('*');
			$query->from($db->quoteName('#__customers')." AS cus");
			$query->join('INNER', '#__registration AS regis ON regis.id = cus.regis_id');
			$query->where($db->quoteName('cus.project_id') . " = ".$project_id);
			$query->where($db->quoteName('cus.status_id') . " = 99");
			$query->where($db->quoteName('cus.state') . " = 1");
			$query->where($db->quoteName('regis.transaction_status') . " = 2"); // rejected
			if($year != ''){
				if($month != ''){
					$query->where("DATE_FORMAT(cus.create_date,'%Y-%m') = '".$year."-".$month."'");
				}else{
					$query->where("DATE_FORMAT(cus.create_date,'%Y') = '".$year."'");
				}
			}

			$db->setQuery($query);
			$result = $db->loadObjectList();
			return $result;
		}else{
			return $result;
		}
	}







}
