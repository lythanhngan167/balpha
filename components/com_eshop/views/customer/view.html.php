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
defined('_JEXEC') or die;

/**
 * HTML View class for EShop component
 *
 * @static
 * @package        Joomla
 * @subpackage     EShop
 * @since          1.5
 */
class EShopViewCustomer extends EShopView
{

	public function display($tpl = null)
	{
		$app = JFactory::getApplication();

		if (EshopHelper::getConfigValue('catalog_mode'))
		{
			JFactory::getSession()->set('warning', JText::_('ESHOP_CATALOG_MODE_ON'));
			$app->redirect(JRoute::_(EshopRoute::getViewRoute('categories')));
		}
		else
		{
		    $this->bootstrapHelper       = new EshopHelperBootstrap(EshopHelper::getConfigValue('twitter_bootstrap_version'));
			$layout = $this->getLayout();

			if ($layout == 'account')
			{
				if (EshopHelper::getConfigValue('customer_manage_account', '1'))
				{
					$this->displayAccount($tpl);
					return;
				}
				else
				{
					$app->redirect(JRoute::_(EshopRoute::getViewRoute('customer')));
				}

			}
			elseif ($layout == 'orders')
			{
				if (EshopHelper::getConfigValue('customer_manage_order', '1'))
				{
					$this->displayOrders($tpl);
					return;
				}
				else
				{
					$app->redirect(JRoute::_(EshopRoute::getViewRoute('customer')));
				}
			}
			elseif ($layout == 'order')
			{
				if (EshopHelper::getConfigValue('customer_manage_order', '1'))
				{
					$this->displayOrder($tpl);
					return;
				}
				else
				{
					$app->redirect(JRoute::_(EshopRoute::getViewRoute('customer')));
				}
			}
			elseif ($layout == 'downloads')
			{
				if (EshopHelper::getConfigValue('customer_manage_download', '1'))
				{
					$this->displayDownloads($tpl);
					return;
				}
				else
				{
					$app->redirect(JRoute::_(EshopRoute::getViewRoute('customer')));
				}
			}
			elseif ($layout == 'addresses')
			{
				if (EshopHelper::getConfigValue('customer_manage_address', '1'))
				{
					$this->displayAddresses($tpl);
					return;
				}
				else
				{
					$app->redirect(JRoute::_(EshopRoute::getViewRoute('customer')));
				}
			}
			elseif ($layout == 'address')
			{
				if (EshopHelper::getConfigValue('customer_manage_address', '1'))
				{
					$this->displayAddress($tpl);
					return;
				}
				else
				{
					$app->redirect(JRoute::_(EshopRoute::getViewRoute('customer')));
				}
			}
			else
			{
				$user = JFactory::getUser();

				if ($user->id)
				{
					$session = JFactory::getSession();

					$userInfo = $this->get('user');

					// Success message
					if ($session->get('success'))
					{
						$this->success = $session->get('success');
						$session->clear('success');
					}

					$this->user = $userInfo;

					parent::display($tpl);
				}
				else
				{
					$uri = JUri::getInstance();
					$app->redirect('index.php?option=com_users&view=login&return=' . base64_encode($uri->toString()));
				}
			}
		}
	}

	/**
	 *
	 * Function to display edit account page
	 *
	 * @param string $tpl
	 */
	protected function displayAccount($tpl)
	{
		$user = JFactory::getUser();

		if ($user->id)
		{
			$userInfo = $this->get('user');

			if ($userInfo->customergroup_id)
			{
				$selected = $userInfo->customergroup_id;
			}
			else
			{
				$selected = EshopHelper::getConfigValue('customergroup_id');
			}

			$customerGroupDisplay = EshopHelper::getConfigValue('customer_groupdisplay');
			$countCustomerGroup   = count(explode(',', $customerGroupDisplay));

			if ($countCustomerGroup > 1)
			{
				$db    = JFactory::getDbo();
				$query = $db->getQuery(true);
				$query->select('a.id, b.customergroup_name AS name')
					->from('#__eshop_customergroups AS a')
					->innerJoin('#__eshop_customergroupdetails AS b ON (a.id = b.customergroup_id)')
					->where('a.published = 1')
					->where('b.language = "' . JFactory::getLanguage()->getTag() . '"');

				if ($customerGroupDisplay != '')
				{
					$query->where('a.id IN (' . $customerGroupDisplay . ')');
				}

				$query->order('b.customergroup_name');
				$db->setQuery($query);

				$this->customergroup_id = JHtml::_('select.genericlist', $db->loadObjectList(), 'customergroup_id', ' class="inputbox" ', 'id', 'name', $selected);
			}
			elseif ($countCustomerGroup == 1)
			{
				$this->default_customergroup_id = $customerGroupDisplay;
			}

			$this->user     = $user;
			$this->userInfo = $userInfo;

			parent::display($tpl);
		}
		else
		{
			JFactory::getApplication()->redirect('index.php?option=com_users&view=login&return=' . base64_encode(JUri::getInstance()->toString()));
		}
	}

	/**
	 *
	 * Function to display list orders for user
	 *
	 * @param string $tpl
	 */
	protected function displayOrders($tpl)
	{
		$user = JFactory::getUser();

		if ($user->id)
		{
			$tax      = new EshopTax(EshopHelper::getConfig());
			$currency = new EshopCurrency();
			$orders   = $this->get('Orders');

			for ($i = 0; $n = count($orders), $i < $n; $i++)
			{
				$orders[$i]->total = $currency->format($orders[$i]->total, $orders[$i]->currency_code, $orders[$i]->currency_exchanged_value);
			}

			$this->tax      = $tax;
			$this->orders   = $orders;
			$this->currency = $currency;

			// Warning message
			$session = JFactory::getSession();

			if ($session->get('warning'))
			{
				$this->warning = $session->get('warning');
				$session->clear('warning');
			}

			parent::display($tpl);
		}
		else
		{
			JFactory::getApplication()->redirect('index.php?option=com_users&view=login&return=' . base64_encode(JUri::getInstance()->toString()));
		}

	}

	/**
	 *
	 * Function to display order information
	 *
	 * @param string $tpl
	 */
	protected function displayOrder($tpl)
	{
		$user = JFactory::getUser();

		if ($user->id)
		{
			$orderId = $this->input->getInt('order_id');

			//Get order infor
			$orderInfor = EshopHelper::getOrder((int) $orderId);
			if($orderInfor->payment_method == 'os_bank_transfer'){
				$object_bank_transfer = EshopHelper::getPaymentInfo('os_bank_transfer');
				$json_bank_transfer = json_decode($object_bank_transfer->params);
				$orderInfor->bank_transfer = $json_bank_transfer->payment_info;
			}

			if (!is_object($orderInfor) || (is_object($orderInfor) && $orderInfor->customer_id != $user->get('id')))
			{
				JFactory::getSession()->set('warning', JText::_('ESHOP_ORDER_DOES_NOT_EXITS'));
				JFactory::getApplication()->redirect(EshopRoute::getViewRoute('customer') . '&layout=orders');
			}
			else
			{
				$db       = JFactory::getDbo();
				$query    = $db->getQuery(true);
				$tax      = new EshopTax(EshopHelper::getConfig());
				$currency = new EshopCurrency();

				$orderProducts = EshopHelper::getOrderProducts($orderId);

				for ($i = 0; $n = count($orderProducts), $i < $n; $i++)
				{
					$orderProducts[$i]->options = $orderProducts[$i]->orderOptions;
				}

				$orderTotals = EshopHelper::getOrderTotals($orderId);

				//Payment custom fields here
				$form                = new RADForm(EshopHelper::getFormFields('B'));
				$this->paymentFields = $form->getFields();

				//Shipping custom fields here
				$form                 = new RADForm(EshopHelper::getFormFields('S'));
				$this->shippingFields = $form->getFields();
				$this->orderProducts  = $orderProducts;
				$this->orderInfor     = $orderInfor;
				$this->orderTotals    = $orderTotals;
				$this->tax            = $tax;
				$this->currency       = $currency;

				parent::display($tpl);
			}
		}
		else
		{
			JFactory::getApplication()->redirect('index.php?option=com_users&view=login&return=' . base64_encode(JUri::getInstance()->toString()));
		}
	}

	/**
	 *
	 * Function to display list downloads for user
	 *
	 * @param string $tpl
	 */
	protected function displayDownloads($tpl)
	{
		$user = JFactory::getUser();

		if ($user->id)
		{
			$downloads = $this->get('Downloads');

			foreach ($downloads as $download)
			{
				$size   = filesize(JPATH_SITE . '/media/com_eshop/downloads/' . $download->filename);
				$i      = 0;
				$suffix = array(
					'B',
					'KB',
					'MB',
					'GB',
					'TB',
					'PB',
					'EB',
					'ZB',
					'YB'
				);

				while (($size / 1024) > 1)
				{
					$size = $size / 1024;
					$i++;
				}

				$download->size = round(substr($size, 0, strpos($size, '.') + 4), 2) . $suffix[$i];
			}

			$this->downloads = $downloads;

			// Warning message
			$session = JFactory::getSession();

			if ($session->get('warning'))
			{
				$this->warning = $session->get('warning');
				$session->clear('warning');
			}

			parent::display($tpl);
		}
		else
		{
			JFactory::getApplication()->redirect('index.php?option=com_users&view=login&return=' . base64_encode(JUri::getInstance()->toString()));
		}
	}

	/**
	 *
	 * Function to display addresses for user
	 *
	 * @param string $tpl
	 */
	protected function displayAddresses($tpl)
	{
		$user = JFactory::getUser();

		if ($user->id)
		{
			$addresses       = $this->get('addresses');
			$this->addresses = $addresses;

			// Warning message
			$session = JFactory::getSession();

			if ($session->get('success'))
			{
				$this->success = $session->get('success');
				$session->clear('success');
			}

			if ($session->get('warning'))
			{
				$this->warning = $session->get('warning');
				$session->clear('warning');
			}

			parent::display($tpl);
		}
		else
		{
			JFactory::getApplication()->redirect('index.php?option=com_users&view=login&return=' . base64_encode(JUri::getInstance()->toString()));
		}
	}

	/**
	 *
	 * Function to display address form
	 *
	 * @param string $tpl
	 */
	protected function displayAddress($tpl)
	{
		$user = JFactory::getUser();

		if ($user->id)
		{
			$address = $this->get('address');
			$lists   = array();

			if (is_object($address))
			{
				(EshopHelper::getDefaultAddressId($address->customer_id) == $address->id) ? $isDefault = 1 : $isDefault = 0;
			}
			else
			{
				$isDefault = 0;
			}

			$fields       = EshopHelper::getFormFields('A');
			$form         = new RADForm($fields);
			$countryField = $form->getField('country_id');
			$zoneField    = $form->getField('zone_id');

			if (is_object($address))
			{
				$data = array();

				foreach ($fields as $field)
				{
					if (property_exists($address, $field->name))
					{
						$data[$field->name] = $address->{$field->name};
					}
				}

				$form->bind($data);

				if ($zoneField instanceof RADFormFieldZone)
				{
					$zoneField->setCountryId($address->country_id);
				}
			}
			else
			{
				if ($zoneField instanceof RADFormFieldCountries)
				{
					$countryId = $countryField->getValue();

					if ($countryId && $zoneField instanceof RADFormFieldZone)
					{
						$zoneField->setCountryId($countryId);
					}
				}
			}

			$lists['default_address'] = JHtml::_('select.booleanlist', 'default_address', ' class="inputbox" ', $isDefault);

			// first time add address
			$modelCustomer = JModelLegacy::getInstance('Customer', 'EShopModel', array('ignore_request' => true));
			$list_address = $modelCustomer->getAddresses();
			$phonedefault = new stdClass();
			if(count($list_address) == 0){
				if (strpos($user->email, 'mctn_') !== false) {
				   $phonedefault->email = '';
				}else{
					 $phonedefault->email = $user->email;
				}
				if($user->name != ''){
					$phonedefault->firstname = $user->name;
				}
				if($user->username != ''){
					$phonedefault->telephone = $user->username;
				}
				$this->phonedefault = $phonedefault;
			}
			//end first time add address

			$this->address = $address;
			$this->lists   = $lists;
			$this->form    = $form;

			parent::display($tpl);
		}
		else
		{
			JFactory::getApplication()->redirect('index.php?option=com_users&view=login&return=' . base64_encode(JUri::getInstance()->toString()));
		}
	}
}
