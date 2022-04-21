<?php
/**
 * @version		3.1.0
 * @package		Joomla
 * @subpackage	EShop
 * @author  	Giang Dinh Truong
 * @copyright	Copyright (C) 2012 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die();

/**
 * Eshop Component Model
 *
 * @package		Joomla
 * @subpackage	EShop
 * @since 1.5
 */
class EShopModelCustomer extends EShopModel
{
	function __construct($config)
	{
		parent::__construct($config);
	}
	
	/**
	 * Load the data
	 *
	 */
	public function _loadData()
	{
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('a.*, b.name, b.username')
			->from($this->_tableName . ' AS a')
			->leftJoin('#__users AS b ON (a.customer_id = b.id)')
			->where('a.id = ' . intval($this->_id));
		$db->setQuery($query);
		$row = $db->loadObject();
		$this->_data = $row;
	}
	
	/**
	 * Init Category data
	 *
	 */
	public function _initData()
	{
		$db = $this->getDbo();
		$row = new EShopTable($this->_tableName, 'id', $db);
		$this->_data = $row;
	}
	
	/**
	 * Function to store product
	 * @see EShopModel::store()
	 */
	function store(&$data)
	{
		if (!$data['id'] && $data['username'] && $data['password'])
		{
			// Store this account into the system
			jimport('joomla.user.helper');
			$params = JComponentHelper::getParams('com_users');
			$newUserType = $params->get('new_usertype', 2);

			$data['groups'] = array();
			$data['groups'][] = $newUserType;
			$data['block'] = 0;
			$data['name'] = $data['firstname'] . ' ' . $data['lastname'];
			$data['password1'] = $data['password2'] = $data['password'];
			$data['email1'] = $data['email2'] = $data['email'];
			$user = new JUser();
			$user->bind($data);
			if (!$user->save())
			{
				JFactory::getApplication()->enqueueMessage($user->getError(), 'error');
				JFactory::getApplication()->redirect('index.php?option=com_eshop&view=customers');
			}
			$data['customer_id'] = $user->id;
		}
		// Check and store address
		$addresses = $data['addresses'];
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->delete('#__eshop_addresses')
			->where('customer_id = ' . intval($data['customer_id']));
		if (count($addresses))
		{
			$addressArr = array();
			foreach ($addresses as $address)
			{
				if ($address > 0)
				{
					$addressArr[] = $address;
				}
			}
			if (count($addressArr))
			{
				$query->where('id NOT IN (' . implode(',', $addressArr) . ')');
			}
		}
		$db->setQuery($query);
		$db->execute();
		$addressId = 0;
		$addressFirstname = $data['address_firstname'];
		$addressLastname = $data['address_lastname'];
		$addressEmail = $data['address_email'];
		$addressTelephone = $data['address_telephone'];
		$addressFax = $data['address_fax'];
		$addressCompany = $data['address_company'];
		$addressCompanyId = $data['address_company_id'];
		$addressAddress1 = $data['address_address_1'];
		$addressAddress2 = $data['address_address_2'];
		$addressCity = $data['address_city'];
		$addressPostcode = $data['address_postcode'];
		for ($i = 0; $n = count($addresses), $i < $n; $i++)
		{
			$row = JTable::getInstance('Eshop', 'Address');
			$row->load($addresses[$i]);
			$row->id = $addresses[$i];
			$row->customer_id = $data['customer_id'];
			$row->firstname = $addressFirstname[$i];
			$row->lastname = $addressLastname[$i];
			$row->email = $addressEmail[$i];
			$row->telephone = $addressTelephone[$i];
			$row->fax = $addressFax[$i];
			$row->company = $addressCompany[$i];
			$row->company_id = $addressCompanyId[$i];
			$row->address_1 = $addressAddress1[$i];
			$row->address_2 = $addressAddress2[$i];
			$row->city = $addressCity[$i];
			$row->postcode = $addressPostcode[$i];
			$row->country_id = $data['address_country_id_' . ($i + 1)];
			$row->zone_id = $data['address_zone_id_' . ($i + 1)];
			if (!$row->id)
			{
				$row->created_date = JFactory::getDate()->toSql();
			}
			$row->modified_date = JFactory::getDate()->toSql();
			$row->store();
			if ($i == 0)
			{
				$addressId = $row->id; 
			}
		}
		$data['address_id'] = $addressId;
		parent::store($data);
		return true;
	}
}