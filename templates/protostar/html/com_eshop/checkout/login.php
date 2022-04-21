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
$span5Class             = $bootstrapHelper->getClassMapping('col-md-5');
$span6Class             = $bootstrapHelper->getClassMapping('col-md-6');
$controlGroupClass      = $bootstrapHelper->getClassMapping('form-group');
$controlLabelClass      = $bootstrapHelper->getClassMapping('col-md-3 col-xs-12');
$controlsClass          = $bootstrapHelper->getClassMapping('form-control');
$pullLeftClass          = $bootstrapHelper->getClassMapping('pull-left');
$btnClass				= $bootstrapHelper->getClassMapping('btn');
?>
<div class="row">
    <div class="col-md-6 col-md-offset-3 box-login">
        <div class="tabset">
            <!-- Tab 1 -->
            <input type="radio" name="tabset" id="tab1" aria-controls="account_exist" checked>
            <label for="tab1"><?php echo JText::_('ESHOP_REGISTERED_CUSTOMER'); ?></label>
            <!-- Tab 2 -->
            <input type="radio" name="tabset" id="tab2" aria-controls="account_new">
            <label for="tab2">Khách hàng mới</label>
            <div class="tab-panels">
                <section id="account_exist" class="tab-panel">
                    <?php
                    if (EshopHelper::getCheckoutType() != 'guest_only')
                    {
                        ?>
                        <div id="login" class="account-box exit-account" >
                            <!--                <h4>--><?php //echo JText::_('ESHOP_REGISTERED_CUSTOMER'); ?><!--</h4>-->
                            <!--                <p>--><?php ////echo JText::_('ESHOP_REGISTERED_CUSTOMER_INTRO'); ?><!--</p>-->
                            <fieldset class="form-horizontal">
                                <div class="<?php echo $controlGroupClass; ?>">
                                    <label for="username" class="<?php echo $controlLabelClass; ?>"><?php echo JText::_('ESHOP_USERNAME'); ?></label>
                                    <div class="col-md-9 col-xs-12">
                                        <input type="text" placeholder="<?php echo JText::_('ESHOP_USERNAME_INTRO'); ?>" id="username" name="username" class="<?php echo $controlsClass; ?> focused" required />
                                    </div>
                                </div>
                                <div class="<?php echo $controlGroupClass; ?>">
                                    <label for="password" class="<?php echo $controlLabelClass; ?>"><?php echo JText::_('ESHOP_PASSWORD'); ?></label>
                                    <div class="col-md-9 col-xs-12">
                                        <input type="password" placeholder="<?php echo JText::_('ESHOP_PASSWORD_INTRO'); ?>" id="password" name="password" class="<?php echo $controlsClass; ?>" required />
                                    </div>
                                </div>
                                <div class="<?php echo $controlGroupClass; ?>">
                                    <label class="<?php echo $controlLabelClass; ?>" for="remember"> </label>
                                    <div class="col-md-9 col-xs-12">
                                        <label class="checkbox"><input type="checkbox" alt="<?php echo JText::_('ESHOP_REMEMBER_ME'); ?>" value="yes" class="inputbox" name="remember" id="remember" />
                                            <?php echo JText::_('ESHOP_REMEMBER_ME'); ?>
                                        </label>
                                        <ul class="checkout-login">
                                            <li>
                                                <a href="<?php echo JRoute::_('index.php?option=com_users&view=reset'); ?>"><?php echo JText::_('ESHOP_FORGOT_YOUR_PASSWORD'); ?></a>
                                            </li>
                                            <!-- <li>
                                                <a href="<?php echo JRoute::_('index.php?option=com_users&view=remind'); ?>"><?php echo JText::_('ESHOP_FORGOT_YOUR_USERNAME'); ?></a>
                                            </li> -->
                                        </ul>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-outline-success" id="button-login" >Đăng nhập</button>
                                <?php echo JHtml::_('form.token'); ?>
                            </fieldset>
                        </div>
                        <?php
                    }
                    ?>
                </section>
                <section id="account_new" class="tab-panel">
                    <?php
                    if (EshopHelper::getCheckoutType() != 'guest_only')
                    {
                        ?>
                        <h4><?php echo JText::_('ESHOP_CHECKOUT_NEW_CUSTOMER'); ?></h4>
                        <p><?php echo JText::_('ESHOP_CHECKOUT_NEW_CUSTOMER_INTRO'); ?></p>
                        <label class="radio"><input type="radio" value="register" name="account" checked="checked" /> <?php echo JText::_('ESHOP_REGISTER_ACCOUNT'); ?></label>
                        <?php
                    }
                    if (EshopHelper::getCheckoutType() != 'registered_only')
                    {
                        ?>
                        <label class="radio"><input type="radio" value="guest" name="account" <?php if (EshopHelper::getCheckoutType() == 'guest_only') echo 'checked="checked"'; ?> /> <?php echo JText::_('ESHOP_GUEST_CHECKOUT'); ?></label>
                        <?php
                    }
                    ?>
                    <button type="button" class="btn btn-outline-success" id="button-account" ><?php echo JText::_('ESHOP_CONTINUE'); ?></button>
                </section>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
	//Script to change Payment Address heading when changing checkout options between Register and Guest
	Eshop.jQuery(document).ready(function($){

		//Checkout options - will run if user choose Register Account or Guest Checkout
		$('#button-account').click(function(){
			var siteUrl = '<?php echo EshopHelper::getSiteUrl(); ?>';
			if($('input[name=\'account\']:checked').attr('value') =='register'){
					window.location.href = siteUrl+"index.php?option=com_users&view=registration&Itemid=101";
			}else{
				$.ajax({
					url: siteUrl + 'index.php?option=com_eshop&view=checkout&layout=' + $('input[name=\'account\']:checked').attr('value') + '&format=raw<?php echo EshopHelper::getAttachedLangLink(); ?>',
					dataType: 'html',
					beforeSend: function() {
						$('#button-account').attr('disabled', true);
						$('#button-account').after('<span class="wait">&nbsp;<img src="components/com_eshop/assets/images/loading.gif" alt="" /></span>');
					},
					complete: function() {
						$('#button-account').attr('disabled', false);
						$('.wait').remove();
					},
					success: function(html) {
                        $('#payment-address .box__content-body').html(html);
                        $('#smartwizard').smartWizard("next");
						$("input[name='shipping_address']:checkbox").prop('checked',true);
					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});
		}

		});

		//Login - will run if user choose login with an existed account
		$('#button-login').click(function(){
			var siteUrl = '<?php echo EshopHelper::getSiteUrl(); ?>';
			$.ajax({
				url: siteUrl + 'index.php?option=com_eshop&task=checkout.login<?php echo EshopHelper::getAttachedLangLink(); ?>',
				type: 'post',
				data: $('#checkout-options #login :input'),
				dataType: 'json',
				beforeSend: function() {
					$('#button-login').attr('disabled', true);
					$('#button-login').after('<span class="wait">&nbsp;<img src="components/com_eshop/assets/images/loading.gif" alt="" /></span>');
				},
				complete: function() {
					$('#button-login').attr('disabled', false);
					$('.wait').remove();
				},
				success: function(json) {
					$('.warning, .error').remove();
					if (json['return']) {
						window.location.href = json['return'];
					} else if (json['error']) {
						$('#checkout-options .box__content-body').prepend('<div class="warning" style="display: none;">' + json['error']['warning'] + '</div>');
						$('.warning').fadeIn('slow');
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		});
	});
</script>
