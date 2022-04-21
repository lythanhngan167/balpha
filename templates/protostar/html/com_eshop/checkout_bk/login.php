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
$span5Class             = $bootstrapHelper->getClassMapping('span5');
$span6Class             = $bootstrapHelper->getClassMapping('span6');
$controlGroupClass      = $bootstrapHelper->getClassMapping('control-group');
$controlLabelClass      = $bootstrapHelper->getClassMapping('control-label');
$controlsClass          = $bootstrapHelper->getClassMapping('controls');
$pullLeftClass          = $bootstrapHelper->getClassMapping('pull-left');
$btnClass				= $bootstrapHelper->getClassMapping('btn');
?>
<div class="<?php echo $span6Class; ?> no_margin_left">
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
</div>
<?php
if (EshopHelper::getCheckoutType() != 'guest_only')
{
	?>
	<div id="login" class="<?php echo $span6Class; ?>">
		<h4><?php echo JText::_('ESHOP_REGISTERED_CUSTOMER'); ?></h4>
		<p><?php echo JText::_('ESHOP_REGISTERED_CUSTOMER_INTRO'); ?></p>
		<fieldset>
			<div class="<?php echo $controlGroupClass; ?> row">
				<label for="username" class="<?php echo $controlLabelClass; ?>"><?php echo JText::_('ESHOP_USERNAME'); ?></label>
				<div class="<?php echo $controlsClass; ?>">
					<input type="text" placeholder="<?php echo JText::_('ESHOP_USERNAME_INTRO'); ?>" id="username" name="username" class="form-control focused" />
				</div>
			</div>
			<div class="<?php echo $controlGroupClass; ?> row">
				<label for="password" class="<?php echo $controlLabelClass; ?>"><?php echo JText::_('ESHOP_PASSWORD'); ?></label>
				<div class="<?php echo $controlsClass; ?>">
					<input type="password" placeholder="<?php echo JText::_('ESHOP_PASSWORD_INTRO'); ?>" id="password" name="password" class="form-control" />
				</div>
			</div>
            <div class="<?php echo $controlGroupClass; ?> row">
                <label class="<?php echo $controlLabelClass; ?>" for="remember"> </label>
                <div class="<?php echo $controlsClass; ?>">
                    <label class="checkbox"><input type="checkbox" alt="<?php echo JText::_('ESHOP_REMEMBER_ME'); ?>" value="yes" class="inputbox" name="remember" id="remember" />
                        <?php echo JText::_('ESHOP_REMEMBER_ME'); ?>
                    </label>
                    <ul>
                        <li>
                            <a href="<?php echo JRoute::_('index.php?option=com_users&view=reset'); ?>"><?php echo JText::_('ESHOP_FORGOT_YOUR_PASSWORD'); ?></a>
                        </li>
                        <li>
                            <a href="<?php echo JRoute::_('index.php?option=com_users&view=remind'); ?>"><?php echo JText::_('ESHOP_FORGOT_YOUR_USERNAME'); ?></a>
                        </li>
                    </ul>
                </div>
            </div>
            <button type="button" class="btn btn-outline-success" id="button-login" ><?php echo JText::_('ESHOP_CONTINUE'); ?></button>
			<?php echo JHtml::_('form.token'); ?>
		</fieldset>
	</div>
	<?php
}
?>
<script type="text/javascript">
	//Script to change Payment Address heading when changing checkout options between Register and Guest
	Eshop.jQuery(document).ready(function($){
		$('#checkout-options .checkout-content input[name=\'account\']').click(function(){
			if ($(this).val() == 'register') {
				$('#payment-address .checkout-heading').html('<?php echo JText::_('ESHOP_CHECKOUT_STEP_2_REGISTER'); ?>');
			} else {
				$('#payment-address .checkout-heading').html('<?php echo JText::_('ESHOP_CHECKOUT_STEP_2_GUEST'); ?>');
			}
		});

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
						$('#payment-address .checkout-content').html(html);
						$('#checkout-options .checkout-content').slideUp('slow');
						$('#payment-address .checkout-content').slideDown('slow');
						$('.checkout-heading a').remove();
						$('#checkout-options .checkout-heading').append('<a><?php echo JText::_('ESHOP_EDIT'); ?></a>');
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
						$('#checkout-options .checkout-content').prepend('<div class="warning" style="display: none;">' + json['error']['warning'] + '</div>');
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
