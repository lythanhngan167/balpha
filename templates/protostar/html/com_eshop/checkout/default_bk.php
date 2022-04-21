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
defined( '_JEXEC' ) or die();
$bootstrapHelper        = $this->bootstrapHelper;
$rowFuildClass          = $bootstrapHelper->getClassMapping('row');
$controlGroupClass      = $bootstrapHelper->getClassMapping('form-group');
$controlLabelClass      = $bootstrapHelper->getClassMapping('control-label');
$controlsClass          = $bootstrapHelper->getClassMapping('form-control');
$pullRightClass         = $bootstrapHelper->getClassMapping('pull-right');
$btnClass				= $bootstrapHelper->getClassMapping('btn');
$this->shipping_required = 1;
?>

<h1><?php echo JText::_('ESHOP_CHECKOUT'); ?></h1><br />
<div class="<?php echo $rowFuildClass; ?>">
    <div class="col-sm-12">
        <div class="checkout-box" id="checkout-options" style="display:none;">
            <div class="checkout-heading"><?php echo JText::_('ESHOP_CHECKOUT_STEP_1'); ?></div>
            <div class="checkout-content"></div>
        </div>
        <!--  tung edit  -->
        <div id="address-wrap">
            <div class="row">
                <div class="col-sm-6">
                    <div class="checkout-box" id="payment-address">
                        <div class="checkout-heading"><i class="fa fa-map-marker" aria-hidden="true"></i>
                            <?php
                            if (EshopHelper::getCheckoutType() == 'guest_only')
                            {
                                echo JText::_('ESHOP_CHECKOUT_STEP_2_GUEST');
                            }
                            else
                            {
                                echo JText::_('ESHOP_CHECKOUT_STEP_2_REGISTER');
                            }
                            ?>
                        </div>
                        <div class="checkout-content"></div>
                    </div>
                    <div class="checkout-box" id="payment-method">
                        <div class="checkout-heading"><i class="fa fa-credit-card" aria-hidden="true"></i>
                            <?php echo JText::_('ESHOP_CHECKOUT_STEP_5'); ?></div>
                        <div class="checkout-content form-horizontal"></div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <?php if ($this->shipping_required){
                        if (EshopHelper::getConfigValue('require_shipping_address', 1)){
                            ?>
                            <div class="checkout-box" id="shipping-address">
                                <div class="checkout-heading"><i class="fa fa-map-marker" aria-hidden="true"></i> <?php echo JText::_('ESHOP_CHECKOUT_STEP_3'); ?></div>
                                <div class="checkout-content"></div>
                            </div>
                        <?php }  ?>
                        <div class="checkout-box" id="shipping-method">
                            <div class="checkout-heading"><?php echo JText::_('ESHOP_CHECKOUT_STEP_4'); ?></div>
                            <div class="checkout-content form-horizontal"></div>
                        </div>
                    <?php  }  ?>
                </div>
            </div>
        </div>
        <!-- //end tung edit -->
        <div class="checkout-box" id="confirm">
            <div class="checkout-heading"><i class="fa fa-check-circle" aria-hidden="true"></i>
                <?php echo JText::_('ESHOP_CHECKOUT_STEP_6'); ?></div>
            <div class="checkout-content"></div>
            <?php
            $paymentMethods = os_payments::getPaymentMethods();
            foreach ($paymentMethods as $paymentMethod)
            {

                $params = $paymentMethod->getParams();
                $applicationId = $params->get('application_id');
                if ($paymentMethod->getName() == 'os_squareup')
                {
                    $currentYear = date('Y');
                    ?>
                    <div class="eshop-squareup-information" style="display: none;">
                        <script type="text/javascript">
                            <?php
                            if (EshopHelper::getConfigValue('enable_checkout_captcha'))
                            {
                            $captchaPlugin = JFactory::getApplication()->getParams()->get('captcha', JFactory::getConfig()->get('captcha'));
                            if ($captchaPlugin == 'recaptcha')
                            {
                            $recaptchaPlugin	= JPluginHelper::getPlugin('captcha', 'recaptcha');
                            $params				= $recaptchaPlugin->params;
                            $version			= $params->get('version', '1.0');
                            $pubkey				= $params->get('public_key', '');
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
                            Eshop.jQuery(document).ready(function($){
                                // Confirm button
                                $('#squareup-button-confirm').click(function(){
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
                                            // Do nothing
                                        },
                                        complete: function() {
                                            $('#squareup-button-confirm').attr('disabled', false);
                                            $('.wait').remove();
                                        },
                                        success: function(data) {
                                            if (data['error']) {
                                                alert(data['error']);
                                            }
                                            if (data['success']) {
                                                sqPaymentForm.requestCardNonce();
                                            }
                                        }
                                    });
                                    <?php
                                    }
                                    else
                                    {
                                    ?>
                                    sqPaymentForm.requestCardNonce();
                                    <?php
                                    }
                                    }
                                    else
                                    {
                                    ?>
                                    sqPaymentForm.requestCardNonce();
                                    <?php
                                    }
                                    ?>
                                })
                            })
                        </script>
                        <form action="<?php echo EshopHelper::getSiteUrl(); ?>index.php?option=com_eshop&task=checkout.processOrder&Itemid=<?php echo EshopRoute::getDefaultItemId(); ?>" method="post" name="payment_method_form" id="payment_method_form" class="form form-horizontal">
                            <div class="no_margin_left">
                                <div class="<?php echo $controlGroupClass; ?>">
                                    <div class="<?php echo $controlLabelClass; ?>">
                                        <?php echo  JText::_('ESHOP_SQUAREUP_ZIPCODE'); ?><span class="required">*</span>
                                    </div>
                                    <div class="<?php echo $controlsClass; ?>" id="field_zip_input"></div>
                                </div>
                                <div class="<?php echo $controlGroupClass; ?>">
                                    <div class="<?php echo $controlLabelClass; ?>">
                                        <?php echo  JText::_('ESHOP_CARD_NUMBER'); ?><span class="required">*</span>
                                    </div>
                                    <div class="<?php echo $controlsClass; ?>" id="sq-card-number"></div>
                                </div>
                                <div class="<?php echo $controlGroupClass; ?>">
                                    <div class="<?php echo $controlLabelClass; ?>">
                                        <?php echo  JText::_('ESHOP_CARD_EXPIRY_DATE'); ?><span class="required">*</span>
                                    </div>
                                    <div class="<?php echo $controlsClass; ?>" id="sq-expiration-date"></div>
                                </div>
                                <div class="<?php echo $controlGroupClass; ?>">
                                    <label class="<?php echo $controlLabelClass; ?>" for="cvv_code">
                                        <?php echo JText::_('ESHOP_CVV_CODE'); ?><span class="required">*</span>
                                    </label>
                                    <div class="<?php echo $controlsClass; ?>" id="sq-cvv"></div>
                                </div>
                                <?php
                                if (EshopHelper::getConfigValue('enable_checkout_captcha'))
                                {
                                    $captchaPlugin = JFactory::getApplication()->getParams()->get('captcha', JFactory::getConfig()->get('captcha'));
                                    if ($captchaPlugin)
                                    {
                                        ?>
                                        <div class="<?php echo $controlGroupClass; ?>">
                                            <label class="<?php echo $controlLabelClass; ?>" for="recaptcha_response_field">
                                                <?php echo JText::_('ESHOP_CAPTCHA'); ?><span class="required">*</span>
                                            </label>
                                            <div class="<?php echo $controlsClass; ?> docs-input-sizes">
                                                <?php echo JCaptcha::getInstance($captchaPlugin)->display('dynamic_recaptcha_1', 'dynamic_recaptcha_1', 'required'); ?>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                }
                                ?>
                                <button type="button" class="btn btn-warning" id="squareup-button-confirm" ><?php echo JText::_('ESHOP_CONFIRM_ORDER'); ?></button>
                            </div>
                            <input type="hidden" id="card-nonce" name="nonce" />
                            <?php echo JHtml::_('form.token'); ?>
                        </form>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
    </div>
</div>
<script type="text/javascript">
	Eshop.jQuery(function($){
		//Script to allow Edit step
		$('.checkout-heading').on('click', 'a', function() {
			$('.checkout-content').slideUp('slow');
			if ($('#confirm .eshop-squareup-information').length)
			{
				$('#confirm .eshop-squareup-information').css('display', 'none');
			}
			$(this).parent().parent().find('.checkout-content').slideDown('slow');
		});
		//If user is not logged in, then show login layout
		<?php
		if (!$this->user->get('id'))
		{
			if (EshopHelper::getConfigValue('checkout_type') == 'guest_only')
			{
				?>
				$(document).ready(function() {
					var siteUrl = '<?php echo EshopHelper::getSiteUrl(); ?>';
					$.ajax({
						url: siteUrl + 'index.php?option=com_eshop&view=checkout&layout=guest&format=raw<?php echo EshopHelper::getAttachedLangLink(); ?>',
						dataType: 'html',
						success: function(html) {
							$('#payment-address .checkout-content').html(html);
							$('#payment-address .checkout-content').slideDown('slow');
						},
						error: function(xhr, ajaxOptions, thrownError) {
							alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
						}
					});
				});
				<?php
			}
			else
			{
				?>
				$(document).ready(function() {
					var siteUrl = '<?php echo EshopHelper::getSiteUrl(); ?>';
					$.ajax({
						url: siteUrl + 'index.php?option=com_eshop&view=checkout&layout=login&format=raw<?php echo EshopHelper::getAttachedLangLink(); ?>',
						dataType: 'html',
						success: function(html) {
              $('#checkout-options').css("display","block");
							$('#checkout-options .checkout-content').html(html);
							$('#checkout-options .checkout-content').slideDown('slow');
						},
						error: function(xhr, ajaxOptions, thrownError) {
							alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
						}
					});
				});
				<?php
			}
		}
		//Else, show payment address layout
		else
		{
			?>
			$('#payment-address .checkout-heading').html('<i class="fa fa-map-marker" aria-hidden="true"></i>  <?php echo JText::_('ESHOP_CHECKOUT_STEP_2_GUEST'); ?>');

			$(document). ready(function() {

				var siteUrl = '<?php echo EshopHelper::getSiteUrl(); ?>';
        var confirmClick1;
        var confirmClick2;
        var confirmClick3;
        var confirmClick4;
				$.ajax({
					url: siteUrl + 'index.php?option=com_eshop&view=checkout&layout=payment_address&format=raw<?php echo EshopHelper::getAttachedLangLink(); ?>',
					dataType: 'html',
					success: function(html) {
						$('#payment-address .checkout-content').html(html);
						$('#payment-address .checkout-content').slideDown('fast');
            $('#shipping-address .checkout-content').slideDown('fast');
            $('#shipping-method .checkout-content').slideDown('fast');
            $('#shipping-method .checkout-content').slideDown('fast');
            $('#payment-method .checkout-content').slideDown('fast');
            $('#confirm .checkout-content').slideDown('fast');
            $.ajax({
              url: siteUrl + 'index.php?option=com_eshop&view=checkout&layout=shipping_address&format=raw<?php echo EshopHelper::getAttachedLangLink(); ?>',
              dataType: 'html',
              success: function(html) {
                $('#shipping-address .checkout-content').html(html);
                confirmClick1 = setTimeout(function(){
                  $.ajax({
                    url: siteUrl + 'index.php?option=com_eshop&task=checkout.processPaymentAddress<?php echo EshopHelper::getAttachedLangLink(); ?>',
                    type: 'post',
                    data: $('#payment-address input[type=\'text\'], #payment-address input[type=\'password\'], #payment-address input[type=\'checkbox\']:checked, #payment-address input[type=\'radio\']:checked, #payment-address input[type=\'hidden\'], #payment-address select'),
                    dataType: 'json',

                    success: function(json) {
                      console.log("payment address");
                      console.log(json);
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                      alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    }
                  });
                }, 10);



                $.ajax({
                  url: siteUrl + 'index.php?option=com_eshop&view=checkout&layout=shipping_method&format=raw<?php echo EshopHelper::getAttachedLangLink(); ?>',
                  dataType: 'html',
                  success: function(html) {
                    $('#shipping-method .checkout-content').html(html);
                    //clearTimeout(confirmClick1);
                    confirmClick2 = setTimeout(function(){
                      $.ajax({
                        url: siteUrl + 'index.php?option=com_eshop&task=checkout.processShippingAddress<?php echo EshopHelper::getAttachedLangLink(); ?>',
                        type: 'post',
                        data: $('#shipping-address input[type=\'text\'], #shipping-address input[type=\'password\'], #shipping-address input[type=\'checkbox\']:checked, #shipping-address input[type=\'radio\']:checked, #shipping-address select, #shipping-address textarea'),
                        dataType: 'json',

                        success: function(json) {
                          console.log("shipping address");
                          console.log(json);
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                          alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                        }
                      });
                    }, 100);

                    $.ajax({
                      url: siteUrl + 'index.php?option=com_eshop&view=checkout&layout=payment_method&format=raw<?php echo EshopHelper::getAttachedLangLink(); ?>',
                      dataType: 'html',
                      success: function(html) {
                        $('#payment-method .checkout-content').html(html);
                        //clearTimeout(confirmClick2);
                        confirmClick3 = setTimeout(function(){
                          $.ajax({
                            url: siteUrl + 'index.php?option=com_eshop&task=checkout.processShippingMethod<?php echo EshopHelper::getAttachedLangLink(); ?>',
                            type: 'post',
                            data: $('#shipping-method input[type=\'radio\']:checked, #shipping-method textarea, #shipping-method input[type=\'text\']'),
                            dataType: 'json',
                            success: function(json) {
                              console.log("shipping method");
                              console.log(json);
                            },
                            error: function(xhr, ajaxOptions, thrownError) {
                              alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                            }
                          });
                        }, 500);

                        $.ajax({
                          url: siteUrl + 'index.php?option=com_eshop&view=checkout&layout=confirm&format=raw<?php echo EshopHelper::getAttachedLangLink(); ?>',
                          dataType: 'html',
                          success: function(html) {
                            $('#confirm .checkout-content').html(html);

                            //clearTimeout(confirmClick3);
                            confirmClick4 = setTimeout(function(){
                              $.ajax({
                                url: siteUrl + 'index.php?option=com_eshop&task=checkout.processPaymentMethod<?php echo EshopHelper::getAttachedLangLink(); ?>',
                                type: 'post',
                                data: $('#payment-method input[type=\'radio\']:checked, #payment-method input[type=\'checkbox\']:checked, #payment-method input[type=\'text\'],  #payment-method textarea'),
                                dataType: 'json',

                                success: function(json) {
                                  console.log("payment method");
                                  console.log(json);
                                },
                                error: function(xhr, ajaxOptions, thrownError) {
                                  alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                                }
                              });
                            }, 1000);

                          },
                          error: function(xhr, ajaxOptions, thrownError) {
                            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                          }
                        });

                      },
                      error: function(xhr, ajaxOptions, thrownError) {
                        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                      }
                    });

                  },
                  error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                  }
                });

              },

              error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
              }
            });

					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});
        clearTimeout(confirmClick1);
        clearTimeout(confirmClick2);
        clearTimeout(confirmClick3);
        clearTimeout(confirmClick4);
			});
			<?php
		}
		?>
	});

</script>
