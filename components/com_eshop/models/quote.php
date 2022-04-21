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

class EShopModelQuote extends EShopModel
{
	/**
	 * Entity data
	 *
	 * @var array
	 */
	protected $quoteData = null;

	/**
	 * Constructor.
	 *
	 * @param array $config
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);
	}

	/**
	 *
	 * Function to get Quote Data
	 */
	public function getQuoteData()
	{
		if (!$this->quoteData)
		{
			$quote           = new EshopQuote();
			$this->quoteData = $quote->getQuoteData();
		}

		return $this->quoteData;
	}

	/**
	 *
	 * Function to process quote
	 *
	 * @param array $data
	 *
	 * @return  array
	 */
	public function processQuote($data)
	{
		$quote                            = new EshopQuote();
		$user                             = JFactory::getUser();
		$currency                         = new EshopCurrency();
		$data['currency_id']              = $currency->getCurrencyId();
		$data['currency_code']            = $currency->getCurrencyCode();
		$data['currency_exchanged_value'] = $currency->getExchangedValue();

		$json = array();

		// Validate products in the quote
		if (!$quote->hasProducts())
		{
			$json['return'] = JRoute::_(EshopRoute::getViewRoute('quote'));
		}

		if (!$json)
		{
			// Name validate
			if (strlen($data['name']) < 3 || strlen($data['name']) > 25)
			{
				$json['error']['name'] = JText::_('ESHOP_QUOTE_NAME_REQUIRED');
			}

			// Email validate
			if ((strlen($data['email']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $data['email']))
			{
				$json['error']['email'] = JText::_('ESHOP_QUOTE_EMAIL_REQUIRED');
			}

			// Message validate
			if (strlen($data['message']) < 25 || strlen($data['message']) > 1000)
			{
				$json['error']['message'] = JText::_('ESHOP_QUOTE_MESSAGE_REQUIRED');
			}

			if (EshopHelper::getConfigValue('enable_quote_captcha'))
			{
				$captchaPlugin = JFactory::getApplication()->getParams()->get('captcha', JFactory::getConfig()->get('captcha'));

				if ($captchaPlugin == 'recaptcha')
				{
					$input = JFactory::getApplication()->input;
					$res   = JCaptcha::getInstance($captchaPlugin)->checkAnswer($input->post->get('recaptcha_response_field', '', 'string'));

					if (!$res)
					{
						$json['error']['captcha'] = JText::_('ESHOP_INVALID_CAPTCHA');
					}
				}
			}

			if (!$json)
			{
				// Store Quote
				$row = JTable::getInstance('Eshop', 'Quote');
				$row->bind($data);
				$total = 0;

				foreach ($quote->getQuoteData() as $product)
				{
					$total += $product['total_price'];
				}

				$row->total            = $total;
				$row->customer_id      = $user->get('id');
				$row->created_date     = JFactory::getDate()->toSql();
				$row->modified_date    = JFactory::getDate()->toSql();
				$row->modified_by      = 0;
				$row->checked_out      = 0;
				$row->checked_out_time = '0000-00-00 00:00:00';
				$row->store();
				$quoteRow = $row;
				$quoteId  = $row->id;
				$quote    = new EshopQuote();

				// Store Quote Products, Quote Options
				foreach ($quote->getQuoteData() as $product)
				{
					// Quote Products
					$row               = JTable::getInstance('Eshop', 'Quoteproducts');
					$row->id           = '';
					$row->quote_id     = $quoteId;
					$row->product_id   = $product['product_id'];
					$row->product_name = $product['product_name'];
					$row->product_sku  = $product['product_sku'];
					$row->quantity     = $product['quantity'];
					$row->price        = $product['price'];
					$row->total_price  = $product['total_price'];
					$row->store();
					$quoteProductId = $row->id;

					// Quote Options
					foreach ($product['option_data'] as $option)
					{
						$row                          = JTable::getInstance('Eshop', 'Quoteoptions');
						$row->id                      = '';
						$row->quote_id                = $quoteId;
						$row->quote_product_id        = $quoteProductId;
						$row->product_option_id       = $option['product_option_id'];
						$row->product_option_value_id = $option['product_option_value_id'];
						$row->option_name             = $option['option_name'];
						$row->option_value            = $option['option_value'];
						$row->option_type             = $option['option_type'];
						$row->sku                     = $option['sku'];
						$row->store();
					}
				}

				//Send confirmation email here
				if (EshopHelper::getConfigValue('quote_alert_mail', 1))
				{
					EshopHelper::sendQuoteEmails($quoteRow);
				}
				
				//Newsetter integration
				if (isset($data['newsletter_interest']))
				{
					if (EshopHelper::getConfigValue('acymailing_integration') && JFile::exists(JPATH_ADMINISTRATOR . '/components/com_acymailing/helpers/helper.php'))
					{
						$acyMailingIntegration = true;
					}
					else
					{
						$acyMailingIntegration = false;
					}
					
					$mailchimpIntegration = EshopHelper::getConfigValue('mailchimp_integration');
					
					foreach ($quote->getQuoteData() as $product)
					{
						//Store customer to AcyMailing
						if ($acyMailingIntegration)
						{
							$params  = new JRegistry($product['params']);
							$listIds = $params->get('acymailing_list_ids', '');
							if ($listIds != '')
							{
								require_once JPATH_ADMINISTRATOR . '/components/com_acymailing/helpers/helper.php';
								$userClass = acymailing_get('class.subscriber');
								$subId     = $userClass->subid($row->email);
								if (!$subId)
								{
									$myUser         = new stdClass();
									$myUser->email  = $data['email'];
									$myUser->name   = $data['name'];
									$myUser->userid = '';
									$eventClass     = acymailing_get('class.subscriber');
									$subId          = $eventClass->save($myUser);
								}
								$listIds    = explode(',', $listIds);
								$newProduct = array();
								foreach ($listIds as $listId)
								{
									$newList             = array();
									$newList['status']   = 1;
									$newProduct[$listId] = $newList;
								}
								$userClass->saveSubscription($subId, $newProduct);
							}
						}
					
						//Store subscriber to MailChimp
						if ($mailchimpIntegration)
						{
							$params  = new Registry($product['params']);
							$listIds = $params->get('mailchimp_list_ids', '');
					
							if ($listIds != '')
							{
								$listIds = explode(',', $listIds);
					
								if (count($listIds))
								{
									require_once JPATH_SITE . '/components/com_eshop/helpers/MailChimp.php';
					
									$mailchimp = new MailChimp(EshopHelper::getConfigValue('api_key_mailchimp'));
					
									foreach ($listIds as $listId)
									{
										if ($listId)
										{
											$mailchimp->call('lists/subscribe', array(
													'id'                => $listId,
													'email'             => array('email' => $data['email']),
													'merge_vars'        => array('NAME' => $data['name']),
													'double_optin'      => false,
													'update_existing'   => true,
													'replace_interests' => false,
													'send_welcome'      => false,
											));
										}
									}
								}
							}
						}
					}
				}

				$json['success'] = JRoute::_(EshopRoute::getViewRoute('quote') . '&layout=complete');

				$quote->clear();
			}
		}

		return $json;
	}
}