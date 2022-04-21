<?php
/**
 * Part of the Ossolution Payment Package
 *
 * @copyright  Copyright (C) 2015 - 2016 Ossolution Team. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

require_once JPATH_LIBRARIES . '/omnipay/vendor/autoload.php';

use Ossolution\Payment\OmnipayPayment;
use Omnipay\Common\CreditCard;

/**
 * Payment class which use Omnipay payment class for processing payment
 *
 * @since 1.0
 */
class EShopOmnipayPayment extends OmnipayPayment
{

	/**
	 * Method to check whether we need to show card type on form for this payment method.
	 * Always return false as when use Omnipay, we don't need card type parameter. It can be detected automatically
	 * from given card number
	 *
	 * @return bool|int
	 */
	public function getCardType()
	{
		return 0;
	}

	/**
	 * Method to check whether we need to show card holder name in the form
	 *
	 * @return bool|int
	 */
	public function getCardHolderName()
	{
		return $this->type;
	}

	/**
	 * Method to check whether we need to show card cvv input on form
	 *
	 * @return bool|int
	 */
	public function getCardCvv()
	{
		return $this->type;
	}

	/**
	 * This method need to be implemented by the payment plugin class. It needs to set url which users will be
	 * redirected to after a successful payment. The url is stored in paymentSuccessUrl property
	 *
	 * @param JTable $row
	 * @param array  $data
	 *
	 * @return void
	 */
	protected function setPaymentSuccessUrl($id, $data = array())
	{
		$this->paymentSuccessUrl = JRoute::_('index.php?option=com_eshop&view=checkout&layout=complete', false, false);
	}


	/**
	 * This method need to be implemented by the payment plugin class. It needs to set url which users will be
	 * redirected to when the payment is not success for some reasons. The url is stored in paymentFailureUrl property
	 *
	 * @param int   $id
	 * @param array $data
	 *
	 * @return void
	 */
	protected function setPaymentFailureUrl($id, $data = array())
	{
		$this->paymentFailureUrl = JRoute::_('index.php?option=com_eshop&view=checkout&layout=failure', false, false);
	}

	/**
	 * This method need to be implemented by the payment plugin class. It is called when a payment success. Usually,
	 * this method will update status of the order to success, trigger onPaymentSuccess event and send notification emails
	 * to administrator(s) and customer
	 *
	 * @param JTable $row
	 * @param string $transactionId
	 *
	 * @return void
	 */
	protected function onPaymentSuccess($row, $transactionId)
	{
		$row->transaction_id = $transactionId;
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
	}

	/**
	 * This method need to be implemented by the payment gateway class. It needs to init the JTable order record,
	 * update it with transaction data and then call onPaymentSuccess method to complete the order.
	 *
	 * @param int    $id
	 * @param string $transactionId
	 *
	 * @return mixed
	 */
	protected function onVerifyPaymentSuccess($id, $transactionId)
	{
		$row = JTable::getInstance('Eshop', 'Order');
		$row->load($id);

		if (!$row->id)
		{
			return false;
		}

		if ($row->order_status_id == EshopHelper::getConfigValue('complete_status_id'))
		{
			return false;
		}

		$this->onPaymentSuccess($row, $transactionId);
	}

	/**
	 * This method is usually called by payment method class to add additional data
	 * to the request message before that message is actually sent to the payment gateway
	 *
	 * @param \Omnipay\Common\Message\AbstractRequest $request
	 * @param JTable                                  $row
	 * @param array                                   $data
	 */
	protected function beforeRequestSend($request, $row, $data)
	{
		parent::beforeRequestSend($request, $row, $data);
		// Set return, cancel and notify URL
		$siteUrl = JUri::base();
		
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
		
		//$request->setCancelUrl($siteUrl . 'index.php?option=com_eshop&task=cancel&id=' . $row->id);
		$request->setReturnUrl($siteUrl . 'index.php?option=com_eshop&task=checkout.verifyPayment&payment_method=' . $this->name . $langLink);
		$request->setNotifyUrl($siteUrl . 'index.php?option=com_eshop&task=checkout.verifyPayment&payment_method=' . $this->name . '&notify=1' . $langLink);
		if (EshopHelper::getConfigValue('default_currency_code') != $data['currency_code'])
		{
			$currency = new EshopCurrency();
			$amount = round($currency->convert($data['total'], EshopHelper::getConfigValue('default_currency_code'), $data['currency_code']), 2);
		}
		else
		{
			$amount = round($data['total'], 2);
		}
		$request->setAmount($amount);
		$request->setCurrency($data['currency_code']);
		$request->setDescription(JText::sprintf('ESHOP_PAYMENT_FOR_ORDER', $data['order_id']));
		if (empty($this->redirectHeading))
		{
			$language    = JFactory::getLanguage();
			$languageKey = 'ESHOP_WAIT_' . strtoupper(substr($this->name, 3));
			if ($language->hasKey($languageKey))
			{
				$redirectHeading = JText::_($languageKey);
			}
			else
			{
				$redirectHeading = JText::sprintf('ESHOP_REDIRECT_HEADING', $this->getTitle());
			}
			$this->setRedirectHeading($redirectHeading);
		}
	}
	
	/**
	 * Get Omnipay Creditcard object use for processing payment
	 *
	 * @param $data
	 *
	 * @return CreditCard
	 */
	public function getOmnipayCard($data)
	{
		$cardData      = array();
		$fieldMappings = array(
				'payment_firstname'			=> 'billingFirstName',
				'payment_lastname'			=> 'billingLastName',
				'payment_company'			=> 'billingCompany',
				'payment_address_1'			=> 'billingAddress1',
				'payment_address_2'			=> 'billingAddress2',
				'payment_city'				=> 'billingCity',
				'payment_postcode'			=> 'billingPostcode',
				'payment_zone_name'			=> 'billingState',
				'payment_country_id'		=> 'billingCountry',
				'payment_telephone'			=> 'billingPhone',
				'payment_fax'				=> 'billingFax',
				'shipping_firstname'		=> 'shippingFirstName',
				'shipping_lastname'			=> 'shippingLastName',
				'shipping_company'			=> 'shippingCompany',
				'shipping_address_1'		=> 'shippingAddress1',
				'shipping_address_2'		=> 'shippingAddress2',
				'shipping_city'				=> 'shippingCity',
				'shipping_postcode'			=> 'shippingPostcode',
				'shipping_zone_name'		=> 'shippingState',
				'shipping_country_id'		=> 'shippingCountry',
				'shipping_telephone'		=> 'shippingPhone',
				'shipping_fax'				=> 'shippingFax',
				'email'            			=> 'email',
				'card_number'				=> 'number',
				'exp_month'					=> 'expiryMonth',
				'exp_year'					=> 'expiryYear',
				'cvv_code'					=> 'cvv',
				'card_holder_name'			=> 'name'
		);
	
		foreach ($fieldMappings as $field => $omnipayField)
		{
			if ($field == 'payment_country_id' || $field == 'shipping_country_id')
			{
				$countryInfo = EshopHelper::getCountry($data[$field]);
				$cardData[$omnipayField] = $countryInfo->iso_code_2;
			}
			elseif (isset($data[$field]))
			{
				$cardData[$omnipayField] = $data[$field];
			}
		}
	
		return new CreditCard($cardData);
	}
		
	/**
	 * Default function to render payment information, the child class can override it if needed
	 */
	public function renderPaymentInformation()
	{
	    $bootstrapHelper = new EshopHelperBootstrap(EshopHelper::getConfigValue('twitter_bootstrap_version'));
		?>
	        <script type="text/javascript">
	        	<?php
	        	if (EshopHelper::getConfigValue('enable_checkout_captcha'))
	        	{
	        		$captchaPlugin = JFactory::getApplication()->getParams()->get('captcha', JFactory::getConfig()->get('captcha'));
	        		if ($captchaPlugin == 'recaptcha')
	        		{
	        			$recaptchaPlugin = JPluginHelper::getPlugin('captcha', 'recaptcha');
	        			$params = new JRegistry($recaptchaPlugin->params);
	        			$version	= $params->get('version', '1.0');
	        			$pubkey		= $params->get('public_key', '');
	        			?>
	        			(function($) {
							$(document).ready(function() {
								<?php
								if ($version == '1.0')
								{
									$theme		= $params->get('theme', 'clean');
									?>
									Recaptcha.create("<?php echo $pubkey; ?>", "dynamic_recaptcha_1", {theme: "<?php echo $theme; ?>"});	
									<?php
								}
								else 
								{
									if (version_compare(JVERSION, '3.5.0', 'ge'))
									{
									?>
										JoomlaInitReCaptcha2();
									<?php
									}
									else
									{
										$theme = $params->get('theme2', 'light');
										$langTag = JFactory::getLanguage()->getTag();
										$file = 'https://www.google.com/recaptcha/api.js?hl=' . $langTag . '&onload=onloadCallback&render=explicit';
										JHtml::_('script', $file, true, true);
										?>
											grecaptcha.render("dynamic_recaptcha_1", {sitekey: "' . <?php echo $pubkey;?> . '", theme: "' . <?php echo $theme; ?> . '"});
										<?php
									}
								}
								?>
							})
						})(jQuery);
	        			<?php
	        		}
	        	}
	        	?>
	            function checkNumber(input)
	            {
	                var num = input.value
	                if(isNaN(num))
	                {
	                    alert("<?php echo JText::_('ESHOP_ONLY_NUMBER_IS_ACCEPTED'); ?>");
	                    input.value = "";
	                    input.focus();
	                }
	            }
	            function checkPaymentData()
	            {
	            <?php
	                if ($this->type)
	                {
	                ?>
	                    form = document.getElementById('payment_method_form');
	                    if (form.card_number.value == "")
	                    {
	                        alert("<?php echo  JText::_('ESHOP_ENTER_CARD_NUMBER'); ?>");
	                        form.card_number.focus();
	                        return false;
	                    }
	                    if (form.cvv_code.value == "")
	                    {
	                        alert("<?php echo JText::_('ESHOP_ENTER_CARD_CVV_CODE'); ?>");
	                        form.cvv_code.focus();
	                        return false;
	                    }
	                    if (form.card_holder_name.value == '')
						{
							alert("<?php echo JText::_('ESHOP_ENTER_CARD_HOLDER_NAME') ; ?>");
							form.card_holder_name.focus();
							return false;
						}
	                    return true;
	                <?php
	                }
	                else
	                {
	                ?>
	                    return true;
	                <?php
	                }
	            ?>
	            }
	            Eshop.jQuery(document).ready(function($){
	        		// Confirm button
	        		$('#button-confirm').click(function(){
	            		if (checkPaymentData())
	            		{
	            			<?php
							if (EshopHelper::getConfigValue('enable_checkout_captcha'))
							{
								$captchaPlugin = JFactory::getApplication()->getParams()->get('captcha', JFactory::getConfig()->get('captcha'));
								if ($captchaPlugin == 'recaptcha')
								{
									?>
									var siteUrl = '<?php echo EshopHelper::getSiteUrl(); ?>';
									jQuery.ajax({
			            				url: siteUrl + 'index.php?option=com_eshop&task=checkout.validateCaptcha',
			            				type: 'post',
			            				dataType: 'json',
			            				//data: jQuery('#payment_method_form input[type=\'text\'], #payment_method_form input[type=\'radio\']:checked, #payment_method_form input[type=\'hidden\']'),
										data: jQuery('#payment_method_form').serialize(),
			            				beforeSend: function() {
			            					$('#button-confirm').attr('disabled', true);
			            					$('#button-confirm').after('<span class="wait">&nbsp;<img src="components/com_eshop/assets/images/loading.gif" alt="" /></span>');
			            				},
			            				complete: function() {
			            					$('#button-confirm').attr('disabled', false);
			            					$('.wait').remove();
			            				},
			            				success: function(data) {
			            					if (data['error']) {
			            						alert(data['error']);
			            					}
			            					if (data['success']) {
			            						<?php
	            					            if ($this->name == 'os_stripe')
	            								{
	            					            	?>
	            					            	Stripe.card.createToken({
	            			                            number: $('input[name^=card_number]').val(),
	            			                            cvc: $('input[name^=cvv_code]').val(),
	            			                            exp_month: $('select[name^=exp_month]').val(),
	            			                            exp_year: $('select[name^=exp_year]').val(),
	            			                            name: $('input[name^=card_holder_name]').val()
	            			                        }, stripeResponseHandler);
	            					            	<?php
	            					            }
	            					            else 
	            					            {
		            					            ?>
				            						$('#payment_method_form').submit();
				            						<?php
												} 
												?>
			            					}
			            				}
		            				});
		            				<?php
								}
								else 
								{
									?>
									$('#button-confirm').attr('disabled', true);
	            					$('#button-confirm').after('<span class="wait">&nbsp;<img src="components/com_eshop/assets/images/loading.gif" alt="" /></span>');
	            					<?php
									if ($this->name == 'os_stripe')
									{
										?>
            				            Stripe.card.createToken({
										number: $('input[name^=card_number]').val(),
										cvc: $('input[name^=cvv_code]').val(),
										exp_month: $('select[name^=exp_month]').val(),
										exp_year: $('select[name^=exp_year]').val(),
										name: $('input[name^=card_holder_name]').val()
										}, stripeResponseHandler);
            				            <?php
									}
									else 
									{
										?>
										$('#payment_method_form').submit();
										<?php
									}
								}
							}
							else 
							{
								?>
								$('#button-confirm').attr('disabled', true);
            					$('#button-confirm').after('<span class="wait">&nbsp;<img src="components/com_eshop/assets/images/loading.gif" alt="" /></span>');
            					<?php
								if ($this->name == 'os_stripe')
								{
									?>
									Stripe.card.createToken({
									number: $('input[name^=card_number]').val(),
									cvc: $('input[name^=cvv_code]').val(),
									exp_month: $('select[name^=exp_month]').val(),
									exp_year: $('select[name^=exp_year]').val(),
									name: $('input[name^=card_holder_name]').val()
									}, stripeResponseHandler);
									<?php
								}
								else 
								{
									?>
									$('#payment_method_form').submit();
									<?php
								}
							}
		            		?>
	            		}
	        		})
	            })
	            var stripeResponseHandler = function(status, response) {
	            	Eshop.jQuery(function($) {
	                    var $form = $('#payment_method_form');
	                    if (response.error) {
	                        // Show the errors on the form
	                        //$form.find('.payment-errors').text(response.error.message);
	                        alert(response.error.message);
	                        $('.wait').remove();
	                        $form.find('#button-confirm').prop('disabled', false);
	                    } else {
	                        // token contains id, last4, and card type
	                        var token = response.id;
	                        // Empty card data since we now have token
	                        $('#card_number').val('');
	                        $('#cvv_code').val('');
	                        $('#card_holder_name').val('');
	                        // Insert the token into the form so it gets submitted to the server
	                        $form.append($('<input type="hidden" name="stripeToken" />').val(token));
	                        // and re-submit
	                        $form.get(0).submit();
	                    }
	                });
	            };
	        </script>
	        <form action="<?php echo EshopHelper::getSiteUrl(); ?>index.php?option=com_eshop&task=checkout.processOrder&Itemid=<?php echo EshopRoute::getDefaultItemId(); ?>" method="post" name="payment_method_form" id="payment_method_form" class="form form-horizontal">
	            <div class="no_margin_left">
	                <?php
	                    if ($this->type)
	                    {
	                        $currentYear = date('Y');
	                    ?>
	                        <div class="control-group">
	                            <div class="control-label">
	                                <?php echo  JText::_('ESHOP_CARD_NUMBER'); ?><span class="required">*</span>
	                            </div>
	                            <div class="controls">
	                                <input type="text" id="card_number" name="card_number" class="inputbox" onkeyup="checkNumber(this)" value="" class="input-large" />
	                            </div>
	                        </div>
	                        <div class="control-group">
	                            <div class="control-label">
	                                <?php echo  JText::_('ESHOP_CARD_EXPIRY_DATE'); ?><span class="required">*</span>
	                            </div>
	                            <div class="controls">
	                                <?php echo  JHtml::_('select.integerlist', 1, 12, 1, 'exp_month', ' class="input-small" ', date('m'), '%02d').'  /  '.JHtml::_('select.integerlist', $currentYear, $currentYear + 10, 1, 'exp_year', ' class="input-small"'); ?>
	                            </div>
	                        </div>
	                        <div class="control-group">
	                            <label class="control-label" for="cvv_code">
	                                <?php echo JText::_('ESHOP_CVV_CODE'); ?><span class="required">*</span>
	                            </label>
	                            <div class="controls">
	                                <input type="text" id="cvv_code" name="cvv_code" class="input-small" onKeyUp="checkNumber(this)" value="" />
	                            </div>
	                        </div>
	                        <div class="control-group">
								<label class="control-label" for="card_holder_name">
									<?php echo JText::_('ESHOP_CARD_HOLDER_NAME'); ?><span class="required">*</span>
								</label>
								<div class="controls">
									<input type="text" id="card_holder_name" name="card_holder_name" class="input-large"  value=""/>
								</div>
	                        </div>
	                    <?php
	                    }
	                    if (EshopHelper::getConfigValue('enable_checkout_captcha'))
	                    {
	                    	$captchaPlugin = JFactory::getApplication()->getParams()->get('captcha', JFactory::getConfig()->get('captcha'));
	                    	if ($captchaPlugin)
	                    	{
	                    		?>
	                    		<div class="control-group">
									<label class="control-label" for="recaptcha_response_field">
										<?php echo JText::_('ESHOP_CAPTCHA'); ?><span class="required">*</span>
									</label>
									<div class="controls docs-input-sizes">
										<?php echo JCaptcha::getInstance($captchaPlugin)->display('dynamic_recaptcha_1', 'dynamic_recaptcha_1', 'required'); ?>
									</div>
								</div>
	                    		<?php
	                    	}
	                    }
	                ?>
	                <div class="no_margin_left">
	                	<input id="button-confirm" type="button" class="<?php echo $bootstrapHelper->getClassMapping('btn'); ?> btn-primary pull-right" value="<?php echo JText::_('ESHOP_CONFIRM_ORDER'); ?>" />
	                </div>
	            </div>
	            <?php echo JHtml::_('form.token'); ?>
	        </form>
	    <?php
		}
}