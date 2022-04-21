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

use Joomla\Registry\Registry;

/**
 * HTML View class for EShop component
 *
 * @static
 *
 * @package    Joomla
 * @subpackage EShop
 * @since      1.5
 */
class EShopViewCheckout extends EShopView
{

	public function display($tpl = null)
	{
		$cart                    = new EshopCart();
		$this->user              = JFactory::getUser();
		$this->shipping_required = $cart->hasShipping();
		$this->bootstrapHelper       = new EshopHelperBootstrap(EshopHelper::getConfigValue('twitter_bootstrap_version'));

		switch ($this->getLayout())
		{
			case 'login':
				$this->displayLogin($tpl);
				break;
			case 'guest':
				$this->displayGuest($tpl);
				break;
			case 'register':
				$this->displayRegister($tpl);
				break;
			case 'payment_address':
				$this->displayPaymentAddress($tpl);
				break;
			case 'shipping_address':
				$this->displayShippingAddress($tpl);
				break;
			case 'guest_shipping':
				$this->displayGuestShipping($tpl);
				break;
			case 'shipping_method':
				$this->displayShippingMethod($tpl);
				break;
			case 'payment_method':
				$this->displayPaymentMethod($tpl);
				break;
			case 'confirm':
				$this->displayConfirm($tpl);
				break;
			default:
				break;
		}
	}

	/**
	 *
	 * @param string $tpl
	 */
	protected function displayLogin($tpl = null)
	{
		parent::display($tpl);
	}

	/**
	 *
	 * Function to display Guest layout
	 *
	 * @param string $tpl
	 */
	protected function displayGuest($tpl = null)
	{
		$lists   = array();
		$session = JFactory::getSession();
		$guest   = $session->get('guest');
		$fields  = EshopHelper::getFormFields('B');
		$form    = new RADForm($fields);

		if (is_array($guest))
		{
			$form->bind($guest);

			if (isset($guest['payment']))
			{
				$form->bind($guest['payment']);
			}
		}

		// Prepare default data for zone - start
		if (EshopHelper::isFieldPublished('zone_id'))
		{
			if (EshopHelper::isFieldPublished('country_id'))
			{
				$countryField = $form->getField('country_id');
				$countryId    = (int) $session->get('payment_country_id') ? $session->get('payment_country_id') : $countryField->getValue();
			}
			else
			{
				$countryId = EshopHelper::getConfigValue('country_id');
			}


			if ($countryId)
			{
				$zoneField = $form->getField('zone_id');

				if ($zoneField instanceof RADFormFieldZone)
				{
					$zoneField->setCountryId($countryId);
				}
			}
		}

		// Prepare default data for zone - end
		$this->getCustomerGroupList($lists, isset($guest['customergroup_id']) ? $guest['customergroup_id'] : '');
		$this->form            = $form;
		$this->lists           = $lists;
		$this->payment_zone_id = $session->get('payment_zone_id');

		parent::display($tpl);
	}

	/**
	 *
	 * Function to display Register layout
	 *
	 * @param string $tpl
	 */
	protected function displayRegister($tpl = null)
	{
		$lists   = array();
		$session = JFactory::getSession();
		$fields  = EshopHelper::getFormFields('B');
		$form    = new RADForm($fields);

		// Prepare default data for zone - start
		if (EshopHelper::isFieldPublished('zone_id'))
		{
			if (EshopHelper::isFieldPublished('country_id'))
			{
				$countryField = $form->getField('country_id');
				$countryId    = (int) $session->get('payment_country_id') ? $session->get('payment_country_id') : $countryField->getValue();
			}
			else
			{
				$countryId = EshopHelper::getConfigValue('country_id');
			}

			if ($countryId)
			{
				$zoneField = $form->getField('zone_id');

				if ($zoneField instanceof RADFormFieldZone)
				{
					$zoneField->setCountryId($countryId);
				}
			}
		}
		// Prepare default data for zone - end
		$accountTerms = EshopHelper::getConfigValue('account_terms');

		if ($accountTerms)
		{
			require_once JPATH_ROOT . '/components/com_content/helpers/route.php';

			if (JLanguageMultilang::isEnabled() && count(EshopHelper::getLanguages()) > 1)
			{
				$associations = JLanguageAssociations::getAssociations('com_content', '#__content', 'com_content.item', $accountTerms);
				$langCode     = JFactory::getLanguage()->getTag();

				if (isset($associations[$langCode]))
				{
					$article = EshopHelper::getArticle($associations[$langCode]->id);
				}
				else
				{
					$article = EshopHelper::getArticle($accountTerms);
				}
			}
			else
			{
				$article = EshopHelper::getArticle($accountTerms);
			}

			if (is_object($article))
			{
				$this->accountTermsLink = ContentHelperRoute::getArticleRoute($article->id, $article->catid) . '&tmpl=component&format=html';
			}
		}

		$this->getCustomerGroupList($lists);
		$this->form            = $form;
		$this->lists           = $lists;
		$this->payment_zone_id = $session->get('payment_zone_id');

		parent::display($tpl);
	}

	/**
	 *
	 * Function to display Payment Address layout
	 *
	 * @param string $tpl
	 */
	protected function displayPaymentAddress($tpl = null)
	{
		$lists   = array();
		$session = JFactory::getSession();
		$fields  = EshopHelper::getFormFields('B');
		$form    = new RADForm($fields);

		// Prepare default data for zone - start
		if (EshopHelper::isFieldPublished('zone_id'))
		{
			if (EshopHelper::isFieldPublished('country_id'))
			{
				$countryField = $form->getField('country_id');
				$countryId    = (int) $session->get('payment_country_id') ? $session->get('payment_country_id') : $countryField->getValue();
			}
			else
			{
				$countryId = EshopHelper::getConfigValue('country_id');
			}

			if ($countryId)
			{
				$zoneField = $form->getField('zone_id');

				if ($zoneField instanceof RADFormFieldZone)
				{
					$zoneField->setCountryId($countryId);
				}
			}
		}

		// Prepare default data for zone - end
		$this->getAddressList($lists, $session->get('payment_address_id'));
		$this->form            = $form;
		$this->lists           = $lists;
		$this->payment_zone_id = $session->get('payment_zone_id');

		parent::display($tpl);
	}

	/**
	 *
	 * Function to display Shipping Address layout
	 *
	 * @param string $tpl
	 */
	protected function displayShippingAddress($tpl = null)
	{
		$lists   = array();
		$session = JFactory::getSession();
		$fields  = EshopHelper::getFormFields('S');
		$form    = new RADForm($fields);

		// Prepare default data for zone - start
		if (EshopHelper::isFieldPublished('zone_id'))
		{
			if (EshopHelper::isFieldPublished('country_id'))
			{
				$countryField = $form->getField('country_id');
				$countryId    = (int) $session->get('shipping_country_id') ? $session->get('shipping_country_id') : $countryField->getValue();
			}
			else
			{
				$countryId = EshopHelper::getConfigValue('country_id');
			}

			if ($countryId)
			{
				$zoneField = $form->getField('zone_id');

				if ($zoneField instanceof RADFormFieldZone)
				{
					$zoneField->setCountryId($countryId);
				}
			}
		}

		// Prepare default data for zone - end
		$this->getAddressList($lists, $session->get('shipping_address_id'));
		$this->form             = $form;
		$this->lists            = $lists;
		$this->shipping_zone_id = $session->get('shipping_zone_id');

		parent::display($tpl);
	}

	/**
	 *
	 * Function to display Guest Shipping layout
	 *
	 * @param string $tpl
	 */
	protected function displayGuestShipping($tpl = null)
	{
		$session = JFactory::getSession();
		$guest   = $session->get('guest');
		$fields  = EshopHelper::getFormFields('S');
		$form    = new RADForm($fields);

		if (is_array($guest))
		{
			if (isset($guest['shipping']))
			{
				$shipping = $guest['shipping'];
				$form->bind($shipping);
			}
		}

		// Prepare default data for zone - start
		if (EshopHelper::isFieldPublished('zone_id'))
		{
			if (EshopHelper::isFieldPublished('country_id'))
			{
				$countryField = $form->getField('country_id');
				$countryId    = (int) $session->get('shipping_country_id') ? $session->get('shipping_country_id') : $countryField->getValue();
			}
			else
			{
				$countryId = EshopHelper::getConfigValue('country_id');
			}

			if ($countryId)
			{
				$zoneField = $form->getField('zone_id');

				if ($zoneField instanceof RADFormFieldZone)
				{
					$zoneField->setCountryId($countryId);
				}
			}
		}

		// Prepare default data for zone - end
		$this->form             = $form;
		$this->shipping_zone_id = $session->get('shipping_zone_id');

		parent::display($tpl);
	}

	/**
	 *
	 * Function to display Shipping Method layout
	 *
	 * @param string $tpl
	 */
	protected function displayShippingMethod($tpl = null)
	{
		$session = JFactory::getSession();
		$user    = JFactory::getUser();

		if (EshopHelper::getConfigValue('require_shipping_address' , 1))
		{
			if ($user->get('id') && $session->get('shipping_address_id'))
			{
				//User Shipping
				$addressInfo = EshopHelper::getAddress($session->get('shipping_address_id'));
			}
			else
			{
				//Guest Shipping
				$guest       = $session->get('guest');
				$addressInfo = $guest['shipping'];
			}
		}
		else
		{
			$addressInfo = array();
		}

		$addressData = array(
			'firstname'    => isset($addressInfo['firstname']) ? $addressInfo['firstname'] : '',
			'lastname'     => isset($addressInfo['lastname']) ? $addressInfo['lastname'] : '',
			'company'      => isset($addressInfo['company']) ? $addressInfo['company'] : '',
			'address_1'    => isset($addressInfo['address_1']) ? $addressInfo['address_1'] : '',
			'address_2'    => isset($addressInfo['address_2']) ? $addressInfo['address_2'] : '',
			'postcode'     => isset($addressInfo['postcode']) ? $addressInfo['postcode'] : '',
			'city'         => isset($addressInfo['city']) ? $addressInfo['city'] : '',
			'zone_id'      => isset($addressInfo['zone_id']) ? $addressInfo['zone_id'] : EshopHelper::getConfigValue('zone_id'),
			'zone_name'    => isset($addressInfo['zone_name']) ? $addressInfo['zone_name'] : '',
			'zone_code'    => isset($addressInfo['zone_code']) ? $addressInfo['zone_code'] : '',
			'country_id'   => isset($addressInfo['country_id']) ? $addressInfo['country_id'] : EshopHelper::getConfigValue('country_id'),
			'country_name' => isset($addressInfo['country_name']) ? $addressInfo['country_name'] : '',
			'iso_code_2'   => isset($addressInfo['iso_code_2']) ? $addressInfo['iso_code_2'] : '',
			'iso_code_3'   => isset($addressInfo['iso_code_3']) ? $addressInfo['iso_code_3'] : ''
		);

		$quoteData = array();
		$db        = JFactory::getDbo();
		$query     = $db->getQuery(true);
		$query->select('*')
			->from('#__eshop_shippings')
			->where('published = 1')
			->order('ordering');
        /*if($addressData['country_id'] == 50){
            $query->where('`name` = \'eshop_bizappco\' ');
        }else{
            $query->where('`name` <> \'eshop_bizappco\' ');
        }*/

		$db->setQuery($query);
		$rows = $db->loadObjectList();

		for ($i = 0; $n = count($rows), $i < $n; $i++)
		{
			$shippingName = $rows[$i]->name;
			$params       = new Registry($rows[$i]->params);

			require_once JPATH_COMPONENT . '/plugins/shipping/' . $shippingName . '.php';

			$shippingClass = new $shippingName();
			$quote         = $shippingClass->getQuote($addressData, $params);

			if ($quote)
			{
				$quoteData[$shippingName] = array(
					'title'    => $quote['title'],
					'quote'    => $quote['quote'],
					'ordering' => $quote['ordering'],
					'error'    => $quote['error']
				);
			}

			if (EshopHelper::getConfigValue('only_free_shipping', 0) && strpos($shippingName, 'eshop_free') !== false)
			{
			    $session->clear('shipping_method');
			    $quoteData = array();
			    $quoteData[$shippingName] = array(
			        'title'    => $quote['title'],
			        'quote'    => $quote['quote'],
			        'ordering' => $quote['ordering'],
			        'error'    => $quote['error']
			    );
			    break;
			}
		}
		$session->set('shipping_methods', $quoteData);

		if ($session->get('shipping_methods'))
		{
			$this->shipping_methods = $session->get('shipping_methods');
		}

		$shippingMethod = $session->get('shipping_method');

		if (is_array($shippingMethod))
		{
			$this->shipping_method = $shippingMethod['name'];
		}
		else
		{
			$this->shipping_method = '';
		}

		$this->delivery_date = $session->get('delivery_date') ? $session->get('delivery_date') : '';
		$this->comment       = $session->get('comment') ? $session->get('comment') : '';

		parent::display($tpl);
	}

	/**
	 *
	 * Function to display Payment Method layout
	 *
	 * @param string $tpl
	 */
	protected function displayPaymentMethod($tpl = null)
	{
		$session       = JFactory::getSession();
		$paymentMethod = $this->input->post->getString('payment_method', os_payments::getDefautPaymentMethod());

		if (!$paymentMethod)
		{
			$paymentMethod = os_payments::getDefautPaymentMethod();
		}

		$this->comment       = $session->get('comment') ? $session->get('comment') : '';
		$this->methods       = os_payments::getPaymentMethods();
		$this->paymentMethod = $paymentMethod;
		$checkoutTerms       = EshopHelper::getConfigValue('checkout_terms');

		if ($checkoutTerms)
		{
			require_once JPATH_ROOT . '/components/com_content/helpers/route.php';

			if (JLanguageMultilang::isEnabled() && count(EshopHelper::getLanguages()) > 1)
			{
				$associations = JLanguageAssociations::getAssociations('com_content', '#__content', 'com_content.item', $checkoutTerms);
				$langCode     = JFactory::getLanguage()->getTag();

				if (isset($associations[$langCode]))
				{
					$article = EshopHelper::getArticle($associations[$langCode]->id);
				}
				else
				{
					$article = EshopHelper::getArticle($checkoutTerms);
				}
			}
			else
			{
				$article = EshopHelper::getArticle($checkoutTerms);
			}

			if (is_object($article))
			{
				$this->checkoutTermsLink = ContentHelperRoute::getArticleRoute($article->id, $article->catid) . '&tmpl=component&format=html';
			}
		}

        $privacyPolicyArticle       = EshopHelper::getConfigValue('privacy_policy_article');

		if ($privacyPolicyArticle)
		{
		    require_once JPATH_ROOT . '/components/com_content/helpers/route.php';

		    if (JLanguageMultilang::isEnabled() && count(EshopHelper::getLanguages()) > 1)
		    {
		        $associations = JLanguageAssociations::getAssociations('com_content', '#__content', 'com_content.item', $privacyPolicyArticle);
		        $langCode     = JFactory::getLanguage()->getTag();

		        if (isset($associations[$langCode]))
		        {
		            $article = EshopHelper::getArticle($associations[$langCode]->id);
		        }
		        else
		        {
		            $article = EshopHelper::getArticle($privacyPolicyArticle);
		        }
		    }
		    else
		    {
		        $article = EshopHelper::getArticle($privacyPolicyArticle);
		    }

		    if (is_object($article))
		    {
		        $this->privacyPolicyArticleLink = ContentHelperRoute::getArticleRoute($article->id, $article->catid) . '&tmpl=component&format=html';
		    }
		}

		$this->coupon_code          = $session->get('coupon_code');
		$this->voucher_code         = $session->get('voucher_code');
		$this->checkout_terms_agree = $session->get('checkout_terms_agree');
		$this->currency             = new EshopCurrency();

		parent::display($tpl);
	}

	/**
	 *
	 * Function to display Confirm layout
	 *
	 * @param string $tpl
	 */
	protected function displayConfirm($tpl = null)
	{
		// Get information for the order
		$session  = JFactory::getSession();
		$tax      = new EshopTax(EshopHelper::getConfig());
		$currency = new EshopCurrency();
		$cartData = $this->get('CartData');
		$model    = $this->getModel();
		$model->getCosts();
		$totalData       = $model->getTotalData();
		$total           = $model->getTotal();
		$taxes           = $model->getTaxes();
		$this->cartData  = $cartData;
		$this->totalData = $totalData;
		$this->total     = $total;
		$this->taxes     = $taxes;
		$this->tax       = $tax;
		$this->currency  = $currency;

		// Success message
		if ($session->get('success'))
		{
			$this->success = $session->get('success');
			$session->clear('success');
		}

		if ($total > 0)
		{
			// Payment method
			$db            = JFactory::getDbo();
			$query         = $db->getQuery(true);
			$paymentMethod = $session->get('payment_method');
			if($paymentMethod == ''){
				$paymentMethod = 'os_offline';
			}

			if(strpos($paymentMethod, 'os_onepay') !== false){
                $paymentMethod = 'os_onepay';
            }

			require_once JPATH_COMPONENT . '/plugins/payment/' . $paymentMethod . '.php';
			$query->select('params')
				->from('#__eshop_payments')
				->where('name = "' . $paymentMethod . '"');
			$db->setQuery($query);
			$plugin             = $db->loadObject();
			$params             = new Registry($plugin->params);
			$paymentClass       = new $paymentMethod($params);
			$this->paymentClass = $paymentClass;
		}

		parent::display($tpl);
	}

	/**
	 *
	 * Private method to get Customer Group List
	 *
	 * @param array $lists
	 */
	protected function getCustomerGroupList(&$lists, $selected = '')
	{
		if (!$selected)
		{
			$selected = EshopHelper::getConfigValue('customergroup_id');
		}

		$customerGroupDisplay = EshopHelper::getConfigValue('customer_group_display');
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
			$lists['customergroup_id'] = JHtml::_('select.genericlist', $db->loadObjectList(), 'customergroup_id', ' class="inputbox" ', 'id', 'name', $selected);
		}
		elseif ($countCustomerGroup == 1)
		{
			$lists['default_customergroup_id'] = $customerGroupDisplay;
		}
	}

	/**
	 *
	 * Function to get Address List
	 *
	 * @param array $lists
	 * @param mixed $selected
	 */
	protected function getAddressList(&$lists, $selected = '')
	{
		//Get address list
		$user = JFactory::getUser();

		if ($user->get('id'))
		{
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true);
			//$query->select('a.id, CONCAT(a.firstname, " ", a.lastname, ", ", a.address_1, ", ", a.city, ", ", IF(z.zone_name <> "", CONCAT(z.zone_name, ", "), ""), c.country_name) AS name')
			$query->select('a.*, z.zone_name, c.country_name')
				->from('#__eshop_addresses AS a')
				->leftJoin('#__eshop_zones AS z ON (a.zone_id = z.id)')
				->leftJoin('#__eshop_countries AS c ON (a.country_id = c.id)')
				->where('a.customer_id = ' . (int) $user->get('id'))
				->where('a.address_1 != ""');
			$db->setQuery($query);
			$addresses = $db->loadObjectList();

			for ($i = 0; $n = count($addresses), $i < $n; $i++)
			{
				$address     = $addresses[$i];
				$addressText = $address->firstname;

				if (EshopHelper::isFieldPublished('lastname') && $address->lastname != '')
				{
					$addressText .= ' ' . $address->lastname;
				}

				$addressText .= ', ' . $address->address_1;

				if (EshopHelper::isFieldPublished('city') && $address->city != '')
				{
					$addressText .= ', ' . $address->city;
				}

				if (EshopHelper::isFieldPublished('zone_id') && $address->zone_name != '')
				{
					$addressText .= ', ' . $address->zone_name;
				}

				if (EshopHelper::isFieldPublished('country_id') && $address->country_id != '')
				{
					$addressText .= ', ' . $address->country_name;
				}

				$addresses[$i]->addressText = $addressText;
			}

			if (!$selected)
			{
				//Get default address
				$query->clear()
					->select('address_id')
					->from('#__eshop_customers')
					->where('customer_id = ' . (int) $user->get('id'));
				$db->setQuery($query);
				$selected = $db->loadResult();
			}

			if (count($addresses))
			{
				$lists['address_id'] = JHtml::_('select.genericlist', $addresses, 'address_id', 'class="form-control"', 'id', 'addressText', $selected);
			}
		}
	}
}
