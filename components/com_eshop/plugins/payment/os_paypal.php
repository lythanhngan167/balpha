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

class os_paypal extends os_payment
{
	/**
	 * Constructor functions, init some parameter
	 *
	 * @param object $config        	
	 */
	public function __construct($params)
	{
        $config = array(
            'type' => 0,
            'show_card_type' => false,
            'show_card_holder_name' => false
        );

        parent::__construct($params, $config);

		$this->mode = $params->get('paypal_mode');
		if ($this->mode)
        {
            $this->url = 'https://www.paypal.com/cgi-bin/webscr';
        }
		else
        {
            $this->url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
        }
		$this->setData('business', $params->get('paypal_id'));
		$this->setData('rm', 2);
		$this->setData('cmd', '_cart');
		$this->setData('upload', '1');
		$this->setData('no_shipping', 1);
		$this->setData('no_note', 1);
		$this->setData('lc', $params->get('paypal_lc', 'US'));
		$this->setData('currency_code', $params->get('paypal_currency', 'USD'));
        $this->setData('charset', 'utf-8');
        $this->setData('tax', 0);
	}
	
	/**
	 * Process Payment
	 *
	 * @param array $data        	
	 */
	public function processPayment($data)
	{
		$siteUrl = EshopHelper::getSiteUrl();
		$countryInfo = EshopHelper::getCountry($data['payment_country_id']);
		$countProduct = 1;
		//Do the currency convert to USD if the selected currency does not supported by PayPal
		$rate = 1;
		$availableCurrenciesArr = array('AUD', 'CAD', 'EUR', 'GBP', 'JPY', 'USD', 'NZD', 'CHF', 'HKD', 'SGD', 'SEK', 'DKK', 'PLN', 'NOK', 'HUF', 'CZK', 'ILS', 'MXN', 'BRL', 'MYR', 'PHP', 'TWD', 'THB', 'TRY', 'RUB');
		
		if (!in_array($data['currency_code'], $availableCurrenciesArr))
		{
			$currency = new EshopCurrency();
			$rate = $currency->getExchangedValue('USD') / $currency->getExchangedValue($data['currency_code']);
			$data['currency_code'] = 'USD';
		}
		
		foreach ($data['products'] as $product)
		{
			$this->setData('item_name_' . $countProduct, $product['product_name']);
			$this->setData('item_number_' . $countProduct, $product['product_sku']);
			$this->setData('amount_' . $countProduct, round($product['price'] * $rate, 2));
			$this->setData('quantity_' . $countProduct, $product['quantity']);
			$this->setData('weight_' . $countProduct, $product['weight']);

			$countProduct++;
		}
		
		if ($data['discount_amount_cart'])
		{
			$this->setData('discount_amount_cart', round($data['discount_amount_cart'] * $rate, 2));
		}
		
		if (JLanguageMultilang::isEnabled())
		{
			$db		= JFactory::getDbo();
			$query	= $db->getQuery(true);
			$tag	= JFactory::getLanguage()->getTag();
			$query->select('sef')
				->from('#__languages')
				->where('published = 1')
				->where('lang_code = ' . $db->quote($tag));
			$db->setQuery($query, 0, 1);
			$langLink = '&lang=' . $db->loadResult();
		}
		else 
		{
			$langLink = '';
		}
		
		$this->setData('currency_code', $data['currency_code']);
		$this->setData('custom', $data['order_id']);
		$this->setData('return', $siteUrl . 'index.php?option=com_eshop&view=checkout&layout=complete');
		$this->setData('cancel_return', $siteUrl . 'index.php?option=com_eshop&view=checkout&layout=cancel&id=' . $data['order_id']);
		$this->setData('notify_url', $siteUrl . 'index.php?option=com_eshop&task=checkout.verifyPayment&payment_method=os_paypal' . $langLink);
		$this->setData('address1', $data['payment_address_1']);
		$this->setData('address2', $data['payment_address_2']);
		$this->setData('city', $data['payment_city']);
		$this->setData('country', $countryInfo->iso_code_2);
		$this->setData('first_name', $data['payment_firstname']);
		$this->setData('last_name', $data['payment_lastname']);
		$this->setData('state', $data['payment_zone_name']);
		$this->setData('zip', $data['payment_postcode']);
		$this->setData('email', $data['email']);
		$this->submitPost();
	}

	/**
	 * Validate the post data from paypal to our server
	 *
	 * @return string
	 */
	protected function validate()
	{
		if ($this->params->get('use_new_paypal_ipn_verification') && function_exists('curl_init'))
		{
			return $this->validateIPN();
		}
		
		$errNum = "";
		$errStr = "";
		$urlParsed = parse_url($this->url);
		$host = $urlParsed['host'];
		$path = $urlParsed['path'];
		$postString = '';
		$response = '';
		foreach ($_POST as $key => $value)
		{
			$this->postData[$key] = $value;
			$postString .= $key . '=' . urlencode(stripslashes($value)) . '&';
		}
		$postString .= 'cmd=_notify-validate';
		$fp = fsockopen($host, '80', $errNum, $errStr, 30);
		if (!$fp)
		{
			$response = 'Could not open SSL connection to ' . $this->url;
			$this->logGatewayData($response);
			return false;
		}
		else
		{
			fputs($fp, "POST $path HTTP/1.1\r\n");
			fputs($fp, "Host: $host\r\n");
			fputs($fp, "User-Agent: EShop\r\n");
			fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
			fputs($fp, "Content-length: " . strlen($postString) . "\r\n");
			fputs($fp, "Connection: close\r\n\r\n");
			fputs($fp, $postString . "\r\n\r\n");
			while (!feof($fp))
			{
				$response .= fgets($fp, 1024);
			}
			fclose($fp);
			$this->logGatewayData($response);
		}
		if (!$this->mode || (stristr($response, "VERIFIED") && ($this->postData['payment_status'] == 'Completed')))
		{
			return true;
		}

		return false;
	}
	
	/**
	 * Process payment
	 */
	public function verifyPayment()
	{
		$ret = $this->validate();
		$currency = new EshopCurrency();
		
		if ($ret)
		{
			$row = JTable::getInstance('Eshop', 'Order');
			$id = $this->postData['custom'];
			$amount = $this->postData['mc_gross'];
			
			if ($amount < 0)
			{
				return false;
			}
			
			$row->load($id);
			
			if (!$row->id)
			{
				return false;
			}
			
			// Validate payment status
			if ($row->order_status_id == EshopHelper::getConfigValue('complete_status_id'))
			{
				return false;
			}
			
			// Validate receiver account
			$payPalId		= strtoupper($this->params->get('paypal_id'));
			$receiverEmail	= strtoupper($this->postData['receiver_email']);
			$receiverId		= strtoupper($this->postData['receiver_id']);
			$business		= strtoupper($this->postData['business']);
				
			if ($receiverEmail != $payPalId && $receiverId != $payPalId && $business != $payPalId)
			{
				return false;
			}
			
			// Validate currency
			$availableCurrenciesArr = array('AUD', 'CAD', 'EUR', 'GBP', 'JPY', 'USD', 'NZD', 'CHF', 'HKD', 'SGD', 'SEK', 'DKK', 'PLN', 'NOK', 'HUF', 'CZK', 'ILS', 'MXN', 'BRL', 'MYR', 'PHP', 'TWD', 'THB', 'TRY', 'RUB');
			$receiverCurrency	= strtoupper($this->postData['mc_currency']);
			$orderCurrency		= strtoupper($row->currency_code);
				
			if (!in_array($orderCurrency, $availableCurrenciesArr))
			{
				$orderCurrency = 'USD';
			}
				
			if ($receiverCurrency != $orderCurrency)
			{
				return false;
			}

			// Validate payment amount
			if (!in_array(strtoupper($row->currency_code), $availableCurrenciesArr))
			{
				$total = $currency->convert($row->total, EshopHelper::getConfigValue('default_currency_code'), 'USD');
			}
			else 
			{
				$total = round($row->total * $row->currency_exchanged_value, 2);
			}
			
			if (abs($total - $amount) > 1)
			{
				return false;
			}
			
			$row->transaction_id = $this->postData['txn_id'];
			$row->order_status_id = EshopHelper::getConfigValue('complete_status_id');
			$row->store();
			EshopHelper::completeOrder($row);
			JPluginHelper::importPlugin('eshop');
			JFactory::getApplication()->triggerEvent('onAfterCompleteOrder', array($row));
			//Send confirmation email here
			if (EshopHelper::getConfigValue('order_alert_mail'))
			{
				EshopHelper::sendEmails($row);
			}
			return true;
		}
		else
		{
			return false;
		}
	}
	
	/**
	 * Validate PayPal IPN using PayPal library
	 *
	 * @return bool
	 */
	protected function validateIPN()
	{
		JLoader::register('PaypalIPN', JPATH_ROOT . '/components/com_eshop/plugins/payment/paypal/PayPalIPN.php');
	
		$ipn = new PaypalIPN;
	
		// Use sandbox URL if test mode is configured
		if (!$this->mode)
		{
			$ipn->useSandbox();
		}
	
		// Disable use custom certs
		$ipn->usePHPCerts();
	
		$this->postData = $_POST;
	
		try
		{
			$valid = $ipn->verifyIPN();
			$this->logGatewayData($ipn->getResponse());
	
			if (!$this->mode || $valid)
			{
				return true;
			}
	
			return false;
		}
		catch (Exception $e)
		{
			$this->logGatewayData($e->getMessage());
	
			return false;
		}
	}
}