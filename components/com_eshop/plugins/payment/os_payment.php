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

class os_payment
{

    /**
     * @var String payment method name
     */
    public $name = null;
	/**
	 * Title of payment method
	 *
	 * @var string
	 */

	public  $title = null;

    /**
     * Payment Mode
     * @var bool
     */
    protected $mode = null;

    /**
     * The payment gateway url
     *
     * @var string
     */
    protected $url = null;

    /**
     * Redirect Heading
     *
     * @var null
     */
    protected $redirectHeading = null;

    /**
     * @var JRegistry Payment method data
     */
    protected $params = null;

    /**
     * @var Int of the payment method. 0 : Redirect, 1 : Creditcard
     */
    protected $type = 0;

    /**
     * @var bool Show Cardtype or not
     */
    protected $showCardType = false;

    /**
     * @var bool Show card holder name or not
     */
    protected $showCardHolderName = false;

    /**
     * @var Array Data which will be posted to the payment gateway
     */
    protected $data = null;

    /**
     * @var Array Data posted from the payment gateway back to server
     */
    protected $postData = null;


    /**
     * Constructor function, init the payment method data
     *
     * @param $params
     */

    public function __construct($params, $config = array())
    {
        $this->name = get_class($this);
        if (isset($config['type']))
        {
            $this->type = $config['type'];
        }
        else
        {
            $this->type = 0;
        }

        if (isset($config['show_card_type']))
        {
            $this->showCardType = $config['show_card_type'];
        }
        else
        {
            $this->showCardType = false;
        }

        if (isset($config['show_card_holder_name']))
        {
            $this->showCardHolderName = $config['show_card_holder_name'];
        }
        else
        {
            $this->showCardHolderName = false;
        }

        $this->params = $params;
        $this->loadLanguage();
    }
	/**
	 * Getter method for name property
	 *
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Setter method for name property
	 *
	 * @param string $value
	 */
	public function setName($value)
	{
		$this->name = $value;
	}

    /**
     * @return String Get title of the payment method
     */
    public function getTitle()
	{
		return $this->title;
	}

    /**
     * @param $title String title of the payment method
     */
    public function setTitle($title)
	{
		$this->title = $title;
	}

    /**
     *
     * Set data for a variable which will be passed to server
     *
     * @param $name
     * @param $value
     */
    public function setData($name, $value)
    {
        $this->data[$name] = $value;
    }

    /**
     * Get data for a variable
     * @param $name
     * @param null $default
     * @return null
     */
    public function getData($name, $default = null)
    {
        return isset($this->data[$name]) ? $this->data[$name] : $default;
    }

    /**
     *
     * Function to get params
     * @return JRegistry
     */
    public function getParams()
    {
    	return $this->params;
    }


    /**
     * Build the parameters in key=value format used to send to the payment gateway
     *
     * @return string
     */
    public function buildParameters()
    {
        $fields = '';
        foreach ($this->data as $key => $value)
        {
            $fields .= "$key=" . urlencode($value) . "&";
        }
        return $fields;
    }

	/**
	 * Load language file for this payment plugin
	 */
	protected function loadLanguage()
	{
		$pluginName = $this->getName();
		$lang = JFactory::getLanguage();
		$tag = $lang->getTag();
		if (!$tag)
        {
            $tag = 'en-GB';
        }
		$lang->load($pluginName, JPATH_ROOT, $tag);
	}

	/**
	 *  This method is called when payment for the order is success, it should be used by all payment class
	 *
	 * @param int    $orderId
	 * @param string $transactionId
	 */
	protected function onPaymentSuccess($orderId, $transactionId = null)
	{
		$row = JTable::getInstance('Eshop', 'Order');
		$row->load($orderId);
		$row->transaction_id  = $transactionId;
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
                    <?php
					if ($this->showCardHolderName)
					{
						?>
						if (form.card_holder_name.value == '')
						{
							alert("<?php echo JText::_('ESHOP_ENTER_CARD_HOLDER_NAME') ; ?>");
							form.card_holder_name.focus();
							return false;
						}
						<?php
					}
                    ?>
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
		            						$('#payment_method_form').submit();
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
								$('#payment_method_form').submit();
								<?php
							}
						}
						else
						{
							?>
							$('#button-confirm').attr('disabled', true);
        					$('#button-confirm').after('<span class="wait">&nbsp;<img src="components/com_eshop/assets/images/loading.gif" alt="" /></span>');
							$('#payment_method_form').submit();
							<?php
						}
	            		?>
            		}
        		})
            })
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
                    <?php
                        if ($this->showCardType)
                        {
                            $options = array();
                            $options[] = JHtml::_('select.option', 'Visa', 'Visa');
                            $options[] = JHtml::_('select.option', 'MasterCard', 'MasterCard');
                            $options[] = JHtml::_('select.option', 'Discover', 'Discover');
                            $options[] = JHtml::_('select.option', 'Amex', 'American Express');
                        ?>
                            <div class="control-group">
                                <label class="control-label" for="cvv_code">
                                    <?php echo JText::_('ESHOP_CARD_TYPE'); ?><span class="required">*</span>
                                </label>
                                <div class="controls">
                                    <?php echo JHtml::_('select.genericlist', $options, 'card_type', ' class="input-large" ', 'value', 'text'); ?>
                                </div>
                            </div>
                        <?php
                        }
                        if ($this->showCardHolderName)
                        {
                        ?>
                            <label class="control-label" for="card_holder_name">
                                <?php echo JText::_('ESHOP_CARD_HOLDER_NAME'); ?><span class="required">*</span>
                            </label>
                            <div class="controls">
                                <input type="text" id="card_holder_name" name="card_holder_name" class="input-large"  value=""/>
                            </div>
                        <?php
                        }
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
                <div class="">
                    <div class="delivery-day" style="padding-bottom:15px;">
                        <label for="textarea" ><?php echo JText::_('ESHOP_DELIVERY_DATE'); ?></label>
                        <div class="delivery-day-input">
                            <?php
                            $this->delivery_date = date('Y-m-d', strtotime('+1 day', time()));
                            echo JHtml::_('calendar', $this->delivery_date ? $this->delivery_date : '', 'delivery_date', 'delivery_date', '%Y-%m-%d', array('class'=>'payment-calendar')); ?>
                        </div>
                    </div>
                </div>
                <script language="JavaScript" type="text/javascript">
                    <?php
                    if (version_compare(JVERSION, '3.6.9', 'ge'))
                    {
                    ?>
                    elements = document.querySelectorAll(".field-calendar");
                    for (i = 0; i < elements.length; i++) {
                        JoomlaCalendar.init(elements[i]);
                    }
                    <?php
                    }
                    else
                    {
                    ?>
                    Calendar.setup({
                        // Id of the input field
                        inputField: "delivery_date",
                        // Format of the input field
                        ifFormat: "%Y-%m-%d",
                        // Trigger for the calendar (button ID)
                        button: "delivery_date_img",
                        // Alignment (defaults to "Bl")
                        align: "Tl",
                        singleClick: true,
                        firstDay: 0
                    });
                    <?php
                    }
                    ?>
                </script>
                <div class="">
                    <div class="delivery-hour" style="padding-bottom:15px;">
                        <label for="textarea" ><?php echo JText::_('ESHOP_DELIVERY_HOUR'); ?></label>
                        <div class="delivery-day-input">
                            <select name="delivery_hour" style="width:50%;" required="true">
                                <option value="" selected>Chọn giờ</option>
                                <option value="6 - 7 giờ" selected>6 - 7 giờ</option>
                                <option value="7 - 8 giờ">7 - 8 giờ</option>
                                <option value="8 - 9 giờ">8 - 9 giờ</option>
                                <option value="9 - 10 giờ">9 - 10 giờ</option>
                                <option value="10 - 11 giờ">10 - 11 giờ</option>
                                <option value="11 - 12 giờ" >11 - 12 giờ</option>
                                <option value="12 - 13 giờ" >12 - 13 giờ</option>
                                <option value="13 - 14 giờ" >13 - 14 giờ</option>
                                <option value="14 - 15 giờ" >14 - 15 giờ</option>
                                <option value="15 - 16 giờ" >15 - 16 giờ</option>
                                <option value="16 - 17 giờ" >16 - 17 giờ</option>
                            </select>
                        </div>
                    </div>
                	<input id="button-confirm" type="button" class="<?php echo $bootstrapHelper->getClassMapping('btn'); ?> btn-primary pull-right" value="<?php echo JText::_('ESHOP_CONFIRM_ORDER'); ?>" />
                </div>
            </div>
            <?php echo JHtml::_('form.token'); ?>
        </form>
    <?php
	}

    /**
     * Submit post to paypal server
     */
    public function submitPost()
    {
        if (!$this->redirectHeading)
        {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select('title')
                ->from('#__eshop_payments')
                ->where('name = "' . $this->name . '"');
            $db->setQuery($query);
            $this->redirectHeading = JText::sprintf('ESHOP_REDIRECT_HEADING', JText::_($db->loadResult()));
        }
    ?>
        <div class="eshop-heading"><?php echo  $this->redirectHeading; ?></div>
        <form method="post" action="<?php echo $this->url; ?>" name="eshop_order_form" id="eshop_order_form">
            <?php
            if (count($this->data))
            {
            	foreach ($this->data as $key => $val)
            	{
            		echo '<input type="hidden" name="' . $key . '" value="' . htmlspecialchars($val, ENT_COMPAT, 'UTF-8') . '" />';
            		echo "\n";
            	}
            }
            ?>
            <script type="text/javascript">
                function redirect() {
                    document.eshop_order_form.submit();
                }
                setTimeout('redirect()', 5000);
            </script>
        </form>
    <?php
    }

    /**
     * Log gateway data
     */
    public function logGatewayData($extraData = null)
    {
    	if (!$this->params->get('ipn_log'))
    	{
    		return;
    	}

        $text = '[' . date('m/d/Y g:i A') . '] - ';
        $text .= "Log Data From : ".$this->title." \n";
        foreach ($this->postData as $key => $value)
        {
            $text .= "$key=$value, ";
        }
        if (strlen($extraData))
        {
            $text .= $extraData;
        }
        $ipnLogFile = JPATH_COMPONENT . '/ipn_' . $this->getName() . '.txt';
        $fp = fopen($ipnLogFile, 'a');
        fwrite($fp, $text . "\n\n");
        fclose($fp);
    }
}
