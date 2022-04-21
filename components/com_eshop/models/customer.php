<?php
/**
 * @version        3.1.0
 * @package        Joomla
 * @subpackage     EShop
 * @author         Giang Dinh Truong
 * @copyright      Copyright (C) 2012 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */

// no direct access
use api\model\SUtil;

defined('_JEXEC') or die;

class EShopModelCustomer extends EShopModel
{
	public function __construct($config = array())
	{
		parent::__construct();
	}

	/**
	 * Function to get User
	 *
	 * @return stdClass object
	 */
	public function getUser()
	{
		$user  = JFactory::getUser();
		$db    = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('a.username, a.password, b.*')
			->from('#__users AS a')
			->leftJoin('#__eshop_customers AS b ON (a.id = b.customer_id)')
			->where('a.id = ' . intval($user->get('id')));
		$db->setQuery($query);

		return $db->loadObject();
	}

	/**
	 *
	 * Get list orders of current user
	 * @return array orders object list
	 */
	public function getOrders()
	{
		$user  = JFactory::getUser();
		// Tung Hoang Edit
		$status = JFactory::getApplication()->input->get('status_id', '', 'filter');

		$db    = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('a.*, b.firstname, b.lastname')
			->from('#__eshop_orders AS a')
			->leftJoin('#__eshop_customers AS b ON (a.customer_id = b.customer_id)')
			->where('a.customer_id = ' . (int) $user->id)
			->order('a.id DESC');
		if ($status){
			$query->where('a.order_status_id = ' . (int) $status);
		}
		$db->setQuery($query);

		return $db->loadObjectList();
	}

	/**
	 *
	 * Get list of downloads of current user
	 *
	 * @return array downloads object list
	 */
	public function getDownloads()
	{
		$orders    = $this->getOrders();
		$ordersArr = array();

		foreach ($orders as $order)
		{
			if ($order->order_status_id == EshopHelper::getConfigValue('complete_status_id'))
			{
				$ordersArr[] = $order->id;
			}
		}

		$downloads = array();

		if (count($ordersArr))
		{
			$db    = $this->getDbo();
			$query = $db->getQuery(true);
			$query->select('*')
				->from('#__eshop_orderdownloads AS od')
				->innerJoin('#__eshop_downloads AS d ON (od.download_id = d.id)')
				->where('od.order_id IN (' . implode(',', $ordersArr) . ')')
				->where('((od.remaining > 0 AND d.total_downloads_allowed > 0) OR (d.total_downloads_allowed = 0))');
			$db->setQuery($query);
			$downloads = $db->loadObjectList();
		}

		return $downloads;
	}

	/**
	 *
	 * Function to process user
	 *
	 * @param array $data
	 *
	 * @return  array
	 */
	public function processUser($data)
	{
		$json  = array();
		$user  = JFactory::getUser();
		$db    = $this->getDbo();
		$query = $db->getQuery(true);

		// First Name validate
		if (strlen($data['firstname']) < 1 || strlen($data['firstname']) > 32)
		{
			$json['error']['firstname'] = JText::_('ESHOP_ERROR_FIRSTNAME');
		}

		// Last Name validate
		if (strlen($data['lastname']) < 1 || strlen($data['lastname']) > 32)
		{
			$json['error']['lastname'] = JText::_('ESHOP_ERROR_LASTNAME');
		}

		// Username validate
		if ($data['username'] == '')
		{
			$json['error']['username'] = JText::_('ESHOP_ERROR_USERNAME');
		}
		else
		{
			$query->select('COUNT(*)')
				->from('#__users')
				->where('username = "' . $data['username'] . '"')
				->where('id != ' . intval($user->get('id')));
			$db->setQuery($query);
			if ($db->loadResult())
			{
				$json['error']['username_existed'] = JText::_('ESHOP_ERROR_USERNAME_EXISTED');
			}
		}

		// Password validate
		if ($data['password1'] != '' || $data['password2'] != '')
		{
			// Confirm password validate
			if ($data['password1'] != $data['password2'])
			{
				$json['error']['confirm'] = JText::_('ESHOP_ERROR_CONFIRM_PASSWORD');
			}
		}

		// Email validate
		if (!JMailHelper::isEmailAddress($data['email']))
		{
			$json['error']['email'] = JText::_('ESHOP_ERROR_EMAIL');
		}
		else
		{
			$query->clear()
				->select('COUNT(*)')
				->from('#__users')
				->where('email = ' . $db->quote($data['email']))
				->where('id != ' . intval($user->get('id')));

			$db->setQuery($query);

			if ($db->loadResult())
			{
				$json['error']['email_existed'] = JText::_('ESHOP_ERROR_EMAIL_EXISTED');
			}
		}

		if (!$json)
		{
			// Update user
			$password = $data['password1'];
			$query->clear()
				->update('#__users')
				->set('name = ' . $db->quote($data['firstname'] . ' ' . $data['lastname']))
				->set('username = ' . $db->quote($data['username']))
				->set('email = ' . $db->quote($data['email']))
				->where('id = ' . intval($user->get('id')));

			if ($password != '')
			{
				$salt     = JUserHelper::genRandomPassword(32);
				$crypt    = JUserHelper::getCryptedPassword($password, $salt);
				$password = $crypt . ':' . $salt;
				$query->set('password = ' . $db->quote($password));
			}

			$query->where('id = ' . $user->get('id'));
			$db->setQuery($query);
			$db->execute();

			// Update user customer
			$row = JTable::getInstance('Eshop', 'Customer');

			if ($data['id'])
			{
				$row->load($data['id']);
			}
			else
			{
				$row->id          = '';
				$row->customer_id = $user->get('id');

				if (!$row->address_id)
				{
					$row->address_id = 0;
				}

				$row->published    = 1;
				$row->created_date = JFactory::getDate()->toUnix();
			}

			$row->bind($data);
			$row->modified_date = JFactory::getDate()->toUnix();

			if ($row->store())
			{
				JFactory::getSession()->set('success', JText::_('ESHOP_SAVE_USER_SUCCESS'));
				$json['return'] = JRoute::_(EshopRoute::getViewRoute('customer'));
			}
		}

		return $json;
	}


	/**
	 *
	 * Function to get addresses object list for user
	 * @return array object list
	 */
	public function getAddresses()
	{
		$user  = JFactory::getUser();
		$db    = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('a.*, b.country_name, c.zone_name')
			->from('#__eshop_addresses AS a')
			->leftJoin('#__eshop_countries AS b ON (a.country_id = b.id)')
			->leftJoin('#__eshop_zones AS c ON (a.zone_id = c.id)')
			->where('a.customer_id = ' . (int) $user->get('id'));
		$db->setQuery($query);

		return $db->loadObjectList();
	}

	/**
	 *
	 * Function to get address detail
	 * @return stdClass address object
	 */
	public function getAddress()
	{
		$user  = JFactory::getUser();
		$id    = JFactory::getApplication()->input->getInt('aid', 0);
		$db    = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('*')
			->from('#__eshop_addresses')
			->where('customer_id = ' . (int) $user->get('id'))
			->where('id = ' . (int) $id);
		$db->setQuery($query);

		return $db->loadObject();
	}

	/**
	 *
	 * Function to process address
	 *
	 * @param array $data
	 *
	 * @return array
	 */
	public function processAddress($data)
	{
		$session        = JFactory::getSession();
		$json           = array();
		$db             = $this->getDbo();
		$query          = $db->getQuery(true);
		$excludedFields = array('email', 'telephone', 'fax');
		$fields         = EshopHelper::getFormFields('A', $excludedFields);
		$form           = new RADForm($fields);

		if (!EshopHelper::hasZone($data['country_id']))
		{
			$form->removeRule('zone_id');
		}

		$valid = $form->validate($data);

		if (!$valid)
		{
			$json['error'] = $form->getErrors();
		}

		if (!$json)
		{
			$user = JFactory::getUser();

			//update user customer
			$row = JTable::getInstance('Eshop', 'Address');

			if (!$row->bind($data))
			{
				$json['error']['warning'] = JText::sprintf('ESHOP_ADDRESS_BIND_FAILED', $this->setError($db->getErrorMsg()));
			}

			$row->customer_id = $user->get('id');

			if (!$data['id'])
			{
				$row->id           = '';
				$row->created_date = JFactory::getDate()->toSql();
			}

			$row->modified_date = JFactory::getDate()->toSql();




			if ($row->store())
			{
				$addressId = $row->id;

				if ($data['default_address'] != 0)
				{
					$query->update('#__eshop_customers')
						->set('address_id = ' . (int) $addressId)
						->where('customer_id = ' . (int) $user->get('id'));
					$db->setQuery($query);
					$db->execute();
				}

				$session->set('success', JText::_('ESHOP_SAVE_ADDRESS_SUCCESS'));

				$json['return'] = JRoute::_(EshopRoute::getViewRoute('customer') . '&layout=addresses');
			}
		}

        // Update long lat
        // Add long lat
        $fullAddress = SUtil::getFullAddress($row->id);
        $longlatInfo = SUtil::getLonLatFromAddress($fullAddress);
        if ($longlatInfo && $longlatInfo['lat']) {
            $row->lat = $longlatInfo['lat'];
            $row->lng = $longlatInfo['lng'];
            $row->store();
        }

		return $json;
	}

	/**
	 *
	 * Function to delete an address
	 *
	 * @param int $id
	 *
	 * @return  array
	 */
	public function deleteAddress($id)
	{
		$json = array();

		if ($id)
		{
			$session = JFactory::getSession();

			if (EshopHelper::getDefaultAddressId($id) == $id)
			{
				$session->set('warning', JText::_('ESHOP_CAN_NOT_REMOVE_ADDRESS'));
				$json['return'] = JRoute::_(EshopRoute::getViewRoute('customer') . '&layout=addresses');
			}
			else
			{
				$db    = $this->getDbo();
				$query = $db->getQuery(true);
				$query->delete('#__eshop_addresses')
					->where('id=' . (int) $id);
				$db->setQuery($query);
				$db->execute();
				$session->set('success', JText::_('ESHOP_REMOVE_ADDRESS_SUCCESS'));
				$json['return'] = JRoute::_(EshopRoute::getViewRoute('customer') . '&layout=addresses');

				if ($session->get('shipping_address_id') && $session->get('shipping_address_id') == $id)
				{
					$session->clear('shipping_address_id');
					$session->clear('shipping_country_id');
					$session->clear('shipping_zone_id');
					$session->clear('shipping_postcode');
					$session->clear('shipping_method');
					$session->clear('shipping_methods');
				}

				if ($session->get('payment_address_id') && $session->get('payment_address_id') == $id)
				{
					$session->clear('payment_address_id');
					$session->clear('payment_country_id');
					$session->clear('payment_zone_id');
					$session->clear('payment_method');
				}
			}
		}

		return $json;
	}
}
