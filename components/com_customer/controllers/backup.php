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

/**
 * Customer controller class.
 *
 * @since  1.6
 */
class CustomerControllerBackup extends JControllerForm
{
	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @since   1.6
	 * @see     JController
	 */
	public $url = 'https://biznetweb.local';

	public function __construct($config = array())
	{
		parent::__construct($config);
	}
	public function customers(){
	
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => $this->url.'/index.php?option=com_customer&task=backup.customers',
			CURLOPT_USERAGENT => 'Biznet',
			CURLOPT_SSL_VERIFYPEER => false
		));
		$resp = curl_exec($curl);
		$values = json_decode($resp);
		curl_close($curl);
		$db    = JFactory::getDbo();
		foreach ($values as $value){
			$query = $db->getQuery(true);
			$query->select('*');
			$query->from($db->quoteName('#__customers'));
			$query->where($db->quoteName('id')." = ".$value->id);
			$db->setQuery($query);
			$ocustomer = $db->loadResult();
			if(!$ocustomer){
				$result = JFactory::getDbo()->insertObject('#__customers', $value);
			}
		}
		exit();
	}
	public function customersUpdate(){
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => $this->url.'/index.php?option=com_customer&task=backup.customersUpdate',
			CURLOPT_USERAGENT => 'Biznet',
			CURLOPT_SSL_VERIFYPEER => false
		));
		$resp = curl_exec($curl);
		$customers = json_decode($resp);
		curl_close($curl);
		$db    = JFactory::getDbo();
		foreach ($customers as $customer){
			$result = JFactory::getDbo()->updateObject('#__customers', $customer ,'id');
		}
		exit();
	}
	public function recharge(){
	
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => $this->url.'/index.php?option=com_customer&task=backup.recharge',
			CURLOPT_USERAGENT => 'Biznet',
			CURLOPT_SSL_VERIFYPEER => false
		));
		$resp = curl_exec($curl);
		$values = json_decode($resp);
		curl_close($curl);
		
		$db    = JFactory::getDbo();
		foreach ($values as $value){
			
			$query = $db->getQuery(true);
			$query->select('*');
			$query->from($db->quoteName('#__recharge'));
			$query->where($db->quoteName('id')." = ".$value->id);
			$db->setQuery($query);
			$ovalue = $db->loadResult();
			if(!$ovalue){
				$result = JFactory::getDbo()->insertObject('#__recharge', $value);
			}
		}
		exit();
	}
	public function registration(){
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => $this->url.'/index.php?option=com_customer&task=backup.registration',
			CURLOPT_USERAGENT => 'Biznet',
			CURLOPT_SSL_VERIFYPEER => false
		));
		$resp = curl_exec($curl);
		$values = json_decode($resp);
		curl_close($curl);
		$db    = JFactory::getDbo();
		foreach ($values as $value){

			$query = $db->getQuery(true);
			$query->select('*');
			$query->from($db->quoteName('#__registration'));
			$query->where($db->quoteName('id')." = ".$value->id);
			$db->setQuery($query);
			$ovalue = $db->loadResult();

			if(!$ovalue){
				$result = JFactory::getDbo()->insertObject('#__registration', $value ,true);
			}	
		}
		exit();
	}
	public function orders(){
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => $this->url.'/index.php?option=com_customer&task=backup.orders',
			CURLOPT_USERAGENT => 'Biznet',
			CURLOPT_SSL_VERIFYPEER => false
		));
		$resp = curl_exec($curl);
		$values = json_decode($resp);
		curl_close($curl);
		
		$db    = JFactory::getDbo();
		foreach ($values as $value){
			$query = $db->getQuery(true);
			$query->select('*');
			$query->from($db->quoteName('#__orders'));
			$query->where($db->quoteName('id')." = ".$value->id);
			$db->setQuery($query);
			$ovalue = $db->loadResult();
			if(!$ovalue){
				$result = JFactory::getDbo()->insertObject('#__orders', $value);
			}
		}
		exit();
	}
	public function transactionHistory(){
	
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => $this->url.'/index.php?option=com_customer&task=backup.transactionHistory',
			CURLOPT_USERAGENT => 'Biznet',
			CURLOPT_SSL_VERIFYPEER => false
		));
		$resp = curl_exec($curl);
		$values = json_decode($resp);
		curl_close($curl);
		
		$db    = JFactory::getDbo();
		foreach ($values as $value){
			
			$query = $db->getQuery(true);
			$query->select('*');
			$query->from($db->quoteName('#__transaction_history'));
			$query->where($db->quoteName('id')." = ".$value->id);
			$db->setQuery($query);
			$ovalue = $db->loadResult();
			if(!$ovalue){
				$result = JFactory::getDbo()->insertObject('#__transaction_history', $value);
			}
		}
		exit();
	}
	public function users(){
	
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => $this->url.'/index.php?option=com_customer&task=backup.users',
			CURLOPT_USERAGENT => 'Biznet',
			CURLOPT_SSL_VERIFYPEER => false
		));
		$resp = curl_exec($curl);
		$values = json_decode($resp);
		curl_close($curl);
		
		$db    = JFactory::getDbo();
		foreach ($values as $value){
			
			$query = $db->getQuery(true);
			$query->select('*');
			$query->from($db->quoteName('#__users'));
			$query->where($db->quoteName('id')." = ".$value->id);
			$db->setQuery($query);
			$ovalue = $db->loadResult();
			
			if(!$ovalue){
				$result = JFactory::getDbo()->insertObject('#__users', $value);
			}
		}
		exit();
	}
	public function usersUpdate(){
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => $this->url.'/index.php?option=com_customer&task=backup.usersUpdate',
			CURLOPT_USERAGENT => 'Biznet',
			CURLOPT_SSL_VERIFYPEER => false
		));
		$resp = curl_exec($curl);
		$users = json_decode($resp);
		curl_close($curl);
		$db    = JFactory::getDbo();
		foreach ($users as $user){
			$result = JFactory::getDbo()->updateObject('#__users', $user ,'id');
		}
		exit();
	}
	public function userGroupMap(){
	
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => $this->url.'/index.php?option=com_customer&task=backup.userGroupMap',
			CURLOPT_USERAGENT => 'Biznet',
			CURLOPT_SSL_VERIFYPEER => false
		));
		$resp = curl_exec($curl);
		$values = json_decode($resp);
		curl_close($curl);
		
		$db    = JFactory::getDbo();
		foreach ($values as $value){
			$query = $db->getQuery(true);
			$query->delete($db->quoteName('#__user_usergroup_map'));
			$query->where($db->quoteName('user_id')." = ".$value->user_id);
			$db->setQuery($query);
			$result = $db->execute();
		}

		foreach ($values as $value){
			$result = JFactory::getDbo()->insertObject('#__user_usergroup_map', $value);
		}
		exit();
	}
}
