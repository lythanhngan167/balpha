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
defined('_JEXEC') or die();

/**
 * EShop controller
 *
 * @package        Joomla
 * @subpackage     EShop
 * @since          1.5
 */
class EShopControllerCheckout extends JControllerLegacy
{
	/**
	 * Constructor function
	 *
	 * @param array $config
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);
	}

	/**
	 * Function to login user
	 */
	public function login()
	{
		JSession::checkToken('post') or jexit(JText::_('JInvalid_Token'));

		$app = JFactory::getApplication();

		// Populate the data array:
		$data             = array();
		$data['username'] = $this->input->post->get('username', '', 'USERNAME');
		$data['password'] = $this->input->post->get('password', '', 'RAW');

		// Get the log in options.
		$options             = array();
		$options['remember'] = $this->input->post->getBool('remember', false);

		// Get the log in credentials.
		$credentials             = array();
		$credentials['username'] = $data['username'];
		$credentials['password'] = $data['password'];

		$json = array();

		// Perform the log in.
		if (true === $app->login($credentials, $options))
		{
			// Success
			if (EshopHelper::getConfigValue('active_https'))
			{
				$json['return'] = JRoute::_(EshopRoute::getViewRoute('checkout'), true, 1);
			}
			else
			{
				$json['return'] = JRoute::_(EshopRoute::getViewRoute('checkout'));
			}
		}
		else
		{
			// Login failed !
			$json['error']['warning'] = JText::_('ESHOP_LOGIN_WARNING');
		}

		echo json_encode($json);

		$app->close();
	}

	/**
	 * Function to register user
	 */
	public function register()
	{
		$filterInput = JFilterInput::getInstance(null, null, 1, 1);
		$post        = $this->input->post->getArray();
		$post        = $filterInput->clean($post, null);

		foreach ($post as $field => $value)
		{
			if (is_array($post[$field]))
			{
				$post[$field] = json_encode($value);
			}
		}

		/* @var EShopModelCheckout $model */
		$model = $this->getModel('Checkout');
		$json  = $model->register($post);
		echo json_encode($json);

		JFactory::getApplication()->close();
	}

	/**
	 * Function to guest
	 */
	public function guest()
	{
		$filterInput = JFilterInput::getInstance(null, null, 1, 1);
		$post        = $this->input->post->getArray();
		$post        = $filterInput->clean($post, null);

		foreach ($post as $field => $value)
		{
			if (is_array($post[$field]))
			{
				$post[$field] = json_encode($value);
			}
		}

		/* @var EShopModelCheckout $model */
		$model = $this->getModel('Checkout');
		$json  = $model->guest($post);
		echo json_encode($json);

		JFactory::getApplication()->close();
	}

	/**
	 * Function to process guest shipping
	 */
	public function processGuestShipping()
	{
		$filterInput = JFilterInput::getInstance(null, null, 1, 1);
		$post        = $this->input->post->getArray();
		$post        = $filterInput->clean($post, null);

		foreach ($post as $field => $value)
		{
			if (is_array($post[$field]))
			{
				$post[$field] = json_encode($value);
			}
		}

		/* @var EShopModelCheckout $model */
		$model = $this->getModel('Checkout');
		$json  = $model->processGuestShipping($post);
		echo json_encode($json);

		JFactory::getApplication()->close();
	}

	/**
	 * Function to process payment address
	 */
	public function processPaymentAddress()
	{
		$filterInput = JFilterInput::getInstance(null, null, 1, 1);
		$post        = $this->input->post->getArray();
		$post        = $filterInput->clean($post, null);

		foreach ($post as $field => $value)
		{
			if (is_array($post[$field]))
			{
				$post[$field] = json_encode($value);
			}
		}

		/* @var EShopModelCheckout $model */
		$model = $this->getModel('Checkout');
		$json  = $model->processPaymentAddress($post);
		echo json_encode($json);

		JFactory::getApplication()->close();
	}

	/**
	 * Function to process shipping address
	 */
	public function processShippingAddress()
	{
		$filterInput = JFilterInput::getInstance(null, null, 1, 1);
		$post        = $this->input->post->getArray();
		$post        = $filterInput->clean($post, null);

		foreach ($post as $field => $value)
		{
			if (is_array($post[$field]))
			{
				$post[$field] = json_encode($value);
			}
		}

		/* @var EShopModelCheckout $model */
		$model = $this->getModel('Checkout');
		$json  = $model->processShippingAddress($post);
		echo json_encode($json);

		JFactory::getApplication()->close();
	}

	/**
	 * Function to process shipping method
	 */
	public function processShippingMethod()
	{
		/* @var EShopModelCheckout $model */
		$model = $this->getModel('Checkout');
		$json  = $model->processShippingMethod();
		echo json_encode($json);

		JFactory::getApplication()->close();
	}

	/**
	 * Function to process payment method
	 */
	public function processPaymentMethod()
	{
		/* @var EShopModelCheckout $model */
		$model = $this->getModel('Checkout');
		$json  = $model->processPaymentMethod();
		echo json_encode($json);

		JFactory::getApplication()->close();
	}

	/**
	 *
	 * Function to validate captcha from checkout form
	 */
	public function validateCaptcha()
	{
		/* @var JApplicationSite $app */
		$app  = JFactory::getApplication();
		$json = array();

		if (EshopHelper::getConfigValue('enable_checkout_captcha'))
		{
			$captchaPlugin = $app->getParams()->get('captcha', JFactory::getConfig()->get('captcha'));

			if ($captchaPlugin == 'recaptcha')
			{
				$res = JCaptcha::getInstance($captchaPlugin)->checkAnswer($this->input->post->get('recaptcha_response_field', '', 'string'));

				if (!$res)
				{
					$json['error'] = JText::_('ESHOP_INVALID_CAPTCHA');
				}
			}
		}

		if (!$json)
		{
			$json['success'] = true;
		}

		echo json_encode($json);

		$app->close();
	}

	/**
	 * Function to process order
	 */
	public function processOrder()
	{
		JSession::checkToken('post') or jexit(JText::_('JInvalid_Token'));

		$session = JFactory::getSession();
		$user    = JFactory::getUser();

		/* @var EShopModelCheckout $model */
		$model = $this->getModel('Checkout');
		$cart  = new EshopCart();

		// Get information for the order
		$model->getCosts();
		$totalData = $model->getTotalData();
		$total     = $model->getTotal();
		$return    = '';

		if ($cart->hasShipping())
		{
			// Validate if shipping address is set
			if ($user->get('id') && $session->get('shipping_address_id'))
			{
				$shippingAddress = EshopHelper::getAddress($session->get('shipping_address_id'));
			}
			else
			{
				$guest           = $session->get('guest');
				$shippingAddress = isset($guest['shipping']) ? $guest['shipping'] : '';
			}

			if (empty($shippingAddress) && EshopHelper::getConfigValue('require_shipping_address', 1))
			{
				if (EshopHelper::getConfigValue('active_https'))
				{
					$return = JRoute::_(EshopRoute::getViewRoute('checkout'), true, 1);
				}
				else
				{
					$return = JRoute::_(EshopRoute::getViewRoute('checkout'));
				}
			}

			// Validate if shipping method is set
			if (!$session->get('shipping_method'))
			{
				if (EshopHelper::getConfigValue('active_https'))
				{
					$return = JRoute::_(EshopRoute::getViewRoute('checkout'), true, 1);
				}
				else
				{
					$return = JRoute::_(EshopRoute::getViewRoute('checkout'));
				}
			}
		}
		else
		{
			$session->clear('shipping_method');
			$session->clear('shipping_methods');
		}

		// Validate if payment address has been set.
		if ($user->get('id') && $session->get('payment_address_id'))
		{
			$paymentAddress = EshopHelper::getAddress($session->get('payment_address_id'));
		}
		else
		{
			$guest          = $session->get('guest');
			$paymentAddress = isset($guest['payment']) ? $guest['payment'] : '';
		}

		if (empty($paymentAddress))
		{
			if (EshopHelper::getConfigValue('active_https'))
			{
				$return = JRoute::_(EshopRoute::getViewRoute('checkout'), true, 1);
			}
			else
			{
				$return = JRoute::_(EshopRoute::getViewRoute('checkout'));
			}
		}

		if ($total > 0)
		{
			// Validate if payment method has been set
			if (!$session->get('payment_method'))
			{
				if (EshopHelper::getConfigValue('active_https'))
				{
					$return = JRoute::_(EshopRoute::getViewRoute('checkout'), true, 1);
				}
				else
				{
					$return = JRoute::_(EshopRoute::getViewRoute('checkout'));
				}
			}
		}

		// Validate if cart has products
		if (!$cart->hasProducts())
		{
			$return = JRoute::_(EshopRoute::getViewRoute('cart'));
		}

		if (!$return)
		{
		    $filterInput    = JFilterInput::getInstance(null, null, 1, 1);
			$data           = $this->input->post->getArray();
			$data           = $filterInput->clean($data, null);

			// Prepare customer data
			if ($user->get('id'))
			{
				$data['customer_id'] = $user->get('id');
				$data['email']       = $user->get('email');
				$customer            = EshopHelper::getCustomer($user->get('id'));

				if (is_object($customer))
				{
					$data['customergroup_id'] = $customer->customergroup_id;
					$data['firstname']        = $customer->firstname;
					$data['lastname']         = $customer->lastname;
					$data['telephone']        = $customer->telephone;
					$data['fax']              = $customer->fax;
				}
				else
				{
					$data['customergroup_id'] = '';
					$data['firstname']        = '';
					$data['lastname']         = '';
					$data['telephone']        = '';
					$data['fax']              = '';
				}

				$paymentAddress = EshopHelper::getAddress($session->get('payment_address_id'));
			}
			else
			{
				$data['customer_id']      = 0;
				$data['customergroup_id'] = $guest['customergroup_id'];
				$data['firstname']        = $guest['firstname'];
				$data['lastname']         = $guest['lastname'];
				$data['email']            = $guest['email'];
				$data['telephone']        = $guest['telephone'];
				$data['fax']              = $guest['fax'];

				$guest          = $session->get('guest');
				$paymentAddress = $guest['payment'];
			}

			// Prepare payment data
			$billingFields = EshopHelper::getFormFields('B');

			foreach ($billingFields as $field)
			{
				$fieldName = $field->name;

				if (isset($paymentAddress[$fieldName]))
				{
					if (is_array($paymentAddress[$fieldName]))
					{
						$data['payment_' . $fieldName] = json_encode($paymentAddress[$fieldName]);
					}
					else
					{
						$data['payment_' . $fieldName] = $paymentAddress[$fieldName];
					}
				}
				else
				{
					$data['payment_' . $fieldName] = '';
				}
			}

			$data['payment_zone_name']    = $paymentAddress['zone_name'];
			$data['payment_country_name'] = $paymentAddress['country_name'];
			$data['payment_method']       = $session->get('payment_method');
			$data['payment_method_title'] = EshopHelper::getPaymentTitle($data['payment_method']);

			// Prepare shipping data
			$shippingFields = EshopHelper::getFormFields('S');

			if ($cart->hasShipping())
			{
				if ($user->get('id'))
				{
					$shippingAddress = EshopHelper::getAddress($session->get('shipping_address_id'));
				}
				else
				{
					$guest           = $session->get('guest');
					$shippingAddress = $guest['shipping'];
				}

				foreach ($shippingFields as $field)
				{
					$fieldName = $field->name;

					if (isset($shippingAddress[$fieldName]))
					{
						if (is_array($shippingAddress[$fieldName]))
						{
							$data['shipping_' . $fieldName] = json_encode($shippingAddress[$fieldName]);
						}
						else
						{
							$data['shipping_' . $fieldName] = $shippingAddress[$fieldName];
						}
					}
					else
					{
						$data['shipping_' . $fieldName] = '';
					}
				}

				$data['shipping_zone_name']    = $shippingAddress['zone_name'];
				$data['shipping_country_name'] = $shippingAddress['country_name'];
				$shippingMethod                = $session->get('shipping_method');

				if (is_array($shippingMethod))
				{
					$data['shipping_method']       = $shippingMethod['name'];
					$data['shipping_method_title'] = $shippingMethod['title'];
				}
				else
				{
					$data['shipping_method']       = '';
					$data['shipping_method_title'] = '';
				}
			}
			else
			{
				foreach ($shippingFields as $field)
				{
					$fieldName                      = $field->name;
					$data['shipping_' . $fieldName] = '';
				}

				$data['shipping_zone_name']    = '';
				$data['shipping_country_name'] = '';
				$data['shipping_method']       = '';
				$data['shipping_method_title'] = '';
			}

			$data['totals']          = $totalData;
			$data['delivery_date']   = $session->get('delivery_date');
			$data['comment']         = $session->get('comment');
			$data['order_status_id'] = EshopHelper::getConfigValue('order_status_id');
			$data['language']        = JFactory::getLanguage()->getTag();
			$currency                = new EshopCurrency();
			$data['currency_id']     = $currency->getCurrencyId();
			$data['currency_code']   = $currency->getCurrencyCode();

			if ($session->get('coupon_code'))
			{
				$coupon              = EshopHelper::getCoupon($session->get('coupon_code'));
				$data['coupon_id']   = $coupon->id;
				$data['coupon_code'] = $coupon->coupon_code;
			}
			else
			{
				$data['coupon_id']   = 0;
				$data['coupon_code'] = '';
			}

			if ($session->get('voucher_code'))
			{
				$voucher              = EshopHelper::getVoucher($session->get('voucher_code'));
				$data['voucher_id']   = $voucher->id;
				$data['voucher_code'] = $voucher->voucher_code;
			}
			else
			{
				$data['voucher_id']   = 0;
				$data['voucher_code'] = '';
			}

			$data['currency_exchanged_value'] = $currency->getExchangedValue();
			$data['total']                    = $total;
			$data['order_number']             = strtoupper(JUserHelper::genRandomPassword(10));
			$data['invoice_number']           = EshopHelper::getInvoiceNumber();

			$model->processOrder($data);
		}
		else
		{
			JFactory::getApplication()->redirect($return);
		}
	}


	public function processOrderAPI()
	{

		header("HTTP/1.1 200 OK");
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
		$user_id = $_POST['user_id'];
		$customer_id = $_POST['customer_id'];
		$comment = $_POST['comment'];
		$payment_method = $_POST['payment_method'];
		$payment_address_id = $_POST['payment_address_id'];
		$products = $_POST['products'];

		$test = 0;

		if($test == 1){
			$user_id = 760;
			$customer_id = 760;
			$payment_method = 'os_offline';
			$payment_address_id = 0;
			//$products[0] = array('product_id' => 1,'qty' => 1);
		}

		// $dataAPI = array();
		// $dataAPI[] = "nganly";
		// $dataAPI[] = "nganly123";

		// echo json_encode($dataAPI);
		// exit();

		$session = JFactory::getSession();
		//$user    = JFactory::getUser();
		$user = JFactory::getUser($user_id);

		$session->set('user_api', $customer_id);

		/* @var EShopModelCheckout $model */
		$model = $this->getModel('Checkout');
		$cart  = new EshopCart();

		//$user_id = 0;
		$cartAPI = array();
		// print_r($cartAPI);
		// echo "---";
		if(count($products) > 0){
			foreach($products as $key=>$product){
				if($key % 2 == 0){
					$k = $product['product_id'];
					$qty = $products[$key+1]['qty'];
					$cartAPI[$k] = $qty;
				}
			}
		}

		// $cartAPI[1] = 1;
		// $cartAPI[2] = 1;
		// Get information for the order
		$model->getCosts2($cartAPI,$user_id);
		$totalData = $model->getTotalData2();
		//print_r($totalData); die;
		$total     = $model->getTotal2();

		$return    = '';

		//$total = 2800000;
		$data = array();
		if($payment_address_id > 0){
			$paymentAddress = EshopHelper::getAddress($payment_address_id);
			$data['payment_zone_name']    = $paymentAddress['zone_name'];
			$data['payment_country_name'] = $paymentAddress['country_name'];
		}else{
			$guest           = $session->get('guest');
			$paymentAddress = isset($guest['payment']) ? $guest['payment'] : '';
			$data['payment_address_1'] = $_POST['payment_address_1'];
			$data['payment_city'] = $_POST['payment_city'];
			$data['payment_country_id'] = $_POST['payment_country_id'];
			$data['payment_country_name'] = $_POST['payment_country_name'];
			$data['payment_email'] = $_POST['payment_email'];
			$data['payment_firstname'] = $_POST['payment_firstname'];
			$data['payment_lastname'] = $_POST['payment_lastname'];
			$data['payment_telephone'] = $_POST['payment_telephone'];
			$data['payment_zone_id'] = $_POST['payment_zone_id'];
			$data['payment_zone_name'] = $_POST['payment_zone_name'];
		}


		//$this->input->post->getArray();

		// Prepare customer data
		if ($user->get('id'))
		{
			$data['customer_id'] = $user->get('id');
			$data['email']       = $user->get('email');
			$customer            = EshopHelper::getCustomer($user->get('id'));

			if (is_object($customer))
			{
				$data['customergroup_id'] = $customer->customergroup_id;
				$data['firstname']        = $customer->firstname;
				$data['lastname']         = $customer->lastname;
				$data['telephone']        = $customer->telephone;
				$data['fax']              = $customer->fax;
			}
			else
			{
				$data['customergroup_id'] = '';
				$data['firstname']        = $_POST['payment_firstname'];
				$data['lastname']         = $_POST['payment_lastname'];
				$data['telephone']        = $_POST['payment_telephone'];
				$data['fax']              = '';
			}

		}

		foreach($paymentAddress as $key => $address){
			if( $key == 'id' ||
				$key == 'customer_id' ||
				$key == 'eu_vat_number' ||
				$key == 'created_date' ||
				$key == 'modified_date' ||
				$key == 'zone_code' ||
				$key == 'iso_code_2' ||
				$key == 'iso_code_3'
				){
				continue;
			}

			$data['payment_' . $key] = $paymentAddress[$key];
		}

		$data['payment_method']       = $payment_method;
		$data['payment_method_title'] = EshopHelper::getPaymentTitle($data['payment_method']);

		$data['shipping_method']       = $_POST['shipping_method'];
		$data['shipping_method_title'] = $_POST['shipping_method_title'];

		$data['totals']          = $totalData;
		$data['delivery_date']   = '';
		$data['comment']         = $comment;
		$data['order_status_id'] = EshopHelper::getConfigValue('order_status_id');
		$data['language']        = JFactory::getLanguage()->getTag();
		$currency                = new EshopCurrency();
		$data['currency_id']     = $currency->getCurrencyId();
		$data['currency_code']   = $currency->getCurrencyCode();

		$data['currency_exchanged_value'] = $currency->getExchangedValue();
		$data['total']                    = $total;
		$data['order_number']             = strtoupper(JUserHelper::genRandomPassword(10));
		$data['invoice_number']           = EshopHelper::getInvoiceNumber();
		// echo json_encode($data);
		// die;
		if($user_id > 0){
			$result = $model->processOrderAPI($data,$cartAPI);
			if($result && $user_id > 0){
				$dataReSult['ok'] = 1;
				echo json_encode($dataReSult);
			}else{
				$dataReSult['ok'] = 0;
				echo json_encode($dataReSult);
			}
		}

		exit();
	}

	/**
	 * Function to verify payment
	 */
	public function verifyPayment()
	{
		/* @var EShopModelCheckout $model */
		$model = $this->getModel('Checkout');
		$model->verifyPayment();
	}
}
