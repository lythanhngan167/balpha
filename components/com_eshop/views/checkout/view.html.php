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
use Joomla\Registry\Registry;

defined('_JEXEC') or die;

/**
 * HTML View class for EShop component
 *
 * @static
 * @package        Joomla
 * @subpackage     EShop
 * @since          1.5
 */
class EShopViewCheckout extends EShopView
{
	public function display($tpl = null)
	{
		if (version_compare(JVERSION, '3.6.9', 'ge'))
		{
			JHtml::_('calendar', '', 'id', 'name');
		}
		else
		{
			JHtml::_('behavior.calendar');
		}
		JHtml::_('behavior.tooltip');

		$app = JFactory::getApplication();
		$this->bootstrapHelper       = new EshopHelperBootstrap(EshopHelper::getConfigValue('twitter_bootstrap_version'));


		if (EshopHelper::getConfigValue('catalog_mode'))
		{
			JFactory::getSession()->set('warning', JText::_('ESHOP_CATALOG_MODE_ON'));
			$app->redirect(JRoute::_(EshopRoute::getViewRoute('categories')));
		}
		else
		{
			$menu     = $app->getMenu();
			$menuItem = $menu->getActive();

			if ($menuItem)
			{
				if (isset($menuItem->query['view']) && ($menuItem->query['view'] == 'frontpage'))
				{
					$pathway = $app->getPathway();
					$pathUrl = EshopRoute::getViewRoute('frontpage');
					$pathway->addItem(JText::_('ESHOP_CHECKOUT'), $pathUrl);
				}
			}

			if ($this->getLayout() == 'complete')
			{
				$this->displayComplete($tpl);
			}
			elseif ($this->getLayout() == 'cancel')
			{
				$this->displayCancel($tpl);
			}
			else
			{
				$cart = new EshopCart();

				// Check if cart has products or not
				if (!$cart->hasProducts() || !$cart->canCheckout() || $cart->getMinSubTotalWarning() != '' || $cart->getMinQuantityWarning() != '' || $cart->getMinProductQuantityWarning() != '' || $cart->getMaxProductQuantityWarning() != '')
				{
					$app->redirect(JRoute::_(EshopRoute::getViewRoute('cart')));
				}

				JFactory::getDocument()->addStyleSheet(JUri::base(true) . '/components/com_eshop/assets/colorbox/colorbox.css');

				$this->setPageTitle(JText::_('ESHOP_CHECKOUT'));

				$user                    = JFactory::getUser();
				$this->user              = $user;
				$this->shipping_required = $cart->hasShipping();

				if (EshopHelper::getConfigValue('enable_checkout_captcha') || EshopHelper::getConfigValue('enable_register_account_captcha'))
				{
					$captchaPlugin = $app->getParams()->get('captcha', JFactory::getConfig()->get('captcha'));

					if ($captchaPlugin == 'recaptcha')
					{
						if (EshopHelper::getConfigValue('enable_checkout_captcha'))
						{
							JCaptcha::getInstance($captchaPlugin)->initialise('dynamic_recaptcha_1');
						}
						if (EshopHelper::getConfigValue('enable_register_account_captcha'))
						{
							JCaptcha::getInstance($captchaPlugin)->initialise('dynamic_recaptcha_1');
						}
					}
				}

				// Tung add =======
                $this->displayPaymentAddress($tpl);
                $this->displayShippingMethod($tpl);
                $this->displayConfirm($tpl);
                // end
				parent::display($tpl);
			}
		}
	}

	/**
	 *
	 * Function to display complete layout
	 *
	 * @param string $tpl
	 */
	protected function displayComplete($tpl)
	{
		$cart       = new EshopCart();
		$session    = JFactory::getSession();
		$orderId    = $session->get('order_id');
		$orderInfor = EshopHelper::getOrder($orderId);

		if (is_object($orderInfor))
		{
			if ($orderInfor->payment_method == 'os_ideal' && $orderInfor->order_status_id != EshopHelper::getConfigValue('complete_status_id'))
			{
				JFactory::getApplication()->redirect('index.php?option=com_eshop&view=checkout&layout=cancel&id=' . $orderInfor->id);
			}

			$db            = JFactory::getDbo();
			$query         = $db->getQuery(true);
			$tax           = new EshopTax(EshopHelper::getConfig());
			$currency      = new EshopCurrency();
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
			$this->orderTotals    = $orderTotals;
			$this->tax            = $tax;
			$this->currency       = $currency;

			// Clear cart and session
			if ($session->get('order_id'))
			{
				$cart->clear();
				$session->clear('shipping_method');
				$session->clear('shipping_methods');
				$session->clear('payment_method');
				$session->clear('guest');
				$session->clear('comment');
				$session->clear('order_id');
				$session->clear('coupon_code');
				$session->clear('voucher_code');
			}

			if($orderInfor->payment_request){
                parse_str($orderInfor->payment_request, $payInfo);
                if($payInfo['vnp_TxnRef']){
                    $orderInfor->order_number .= ' ( Mã đơn hàng tại VNPAY: '.$payInfo['vnp_TxnRef'].')';
                }
            }
		}

		$this->orderInfor = $orderInfor;

		if($this->orderInfor->payment_method == 'os_bank_transfer'){
			$object_bank_transfer = EshopHelper::getPaymentInfo('os_bank_transfer');
			$json_bank_transfer = json_decode($object_bank_transfer->params);
			$this->orderInfor->bank_transfer = $json_bank_transfer->payment_info;
		}

		if (is_object($orderInfor))
		{
			$this->conversionTrackingCode = EshopHelper::getConversionTrackingCode($orderInfor);

			if (EshopHelper::getConfigValue('ga_tracking_id') != '')
			{
				if (EshopHelper::getConfigValue('ga_js_type', 'ga.js') == 'ga.js')
				{
					EshopGoogleAnalytics::processClassicAnalytics($orderInfor);
				}
				else
				{
					EshopGoogleAnalytics::processUniversalAnalytics($orderInfor);
				}
			}
		}
		else
		{
			$this->conversionTrackingCode = '';
		}

		if (EshopHelper::getConfigValue('completed_url') != '')
		{
			JFactory::getApplication()->redirect(EshopHelper::getConfigValue('completed_url'));
		}
		else
		{
			parent::display($tpl);
		}
	}

	// tung add ====
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

       // parent::display($tpl);
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

        //parent::display($tpl);
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

      //  parent::display($tpl);
    }
    // end
	/**
	 *
	 * Function to display cancel layout
	 *
	 * @param string $tpl
	 */
	protected function displayCancel($tpl)
	{
		parent::display($tpl);
	}


    /**
     * Tung add ======
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
                $lists['address_id'] = JHtml::_('select.genericlist', $addresses, 'address_id', '', 'id', 'addressText', $selected);
            }
        }
    }
    //end
}
