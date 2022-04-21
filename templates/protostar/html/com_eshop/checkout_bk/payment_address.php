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
$pullRightClass         = $bootstrapHelper->getClassMapping('pull-right');
$btnClass				= $bootstrapHelper->getClassMapping('btn');
$this->shipping_required = 1;
if (isset($this->lists['address_id']))
{
	?>
	<label class="radio">
		<input type="radio" value="existing" name="payment_address" checked="checked"> <?php echo JText::_('ESHOP_EXISTING_ADDRESS'); ?>
	</label>
	<div id="payment-existing">
		<?php echo $this->lists['address_id']; ?>
	</div>
	<label class="radio">
		<input type="radio" value="new" name="payment_address"> <?php echo JText::_('ESHOP_NEW_ADDRESS'); ?>
	</label>
	<?php
}
else
{
	?>
	<input type="hidden" name="payment_address" value="new" />
	<?php
}
?>
<div id="payment-new" style="display: <?php echo (isset($this->lists['address_id']) ? 'none' : 'block'); ?>;" class="form-horizontal">
	<?php
		echo $this->form->render();
	?>
</div>

<button type="button" class="btn btn-outline-warning" id="button-payment-address" >Lưu địa chỉ <?php //echo JText::_('ESHOP_CONTINUE'); ?></button>
<?php $user = JFactory::getUser();  ?>
<script type="text/javascript"><!--
	// Payment Address
	Eshop.jQuery(function($){

		$("#payment-new #firstname").val('<?php echo $user->name; ?>');
		$("#payment-new #telephone").val('<?php echo $user->username; ?>');
		$("#payment-new #email").val('<?php echo $user->email; ?>');
		// tung edit
        $('#payment-existing select').removeAttr("size");
        // end tung edit
        // $(function () {
        //     $("#country_id").val(230).change();
        //     $("label[for='country_id']").parent('.form-group').hide();
        // });
		$('#payment-existing select').on('change', function() {
			$("#button-payment-address").click();
		});

		$('input[type=radio][name=payment_address]').change(function() {
        if (this.value == 'new') {
            $("#button-payment-address").css('display','block');
        }

    });
		// $("#payment-existing select").click(function(){
		// })
		// $('select').on('change', function() {
		//   alert( this.value );
		// });
		$('#button-payment-address').click(function(){
			var siteUrl = '<?php echo EshopHelper::getSiteUrl(); ?>';
			$.ajax({
				url: siteUrl + 'index.php?option=com_eshop&task=checkout.processPaymentAddress<?php echo EshopHelper::getAttachedLangLink(); ?>',
				type: 'post',
				data: $('#payment-address input[type=\'text\'], #payment-address input[type=\'password\'], #payment-address input[type=\'checkbox\']:checked, #payment-address input[type=\'radio\']:checked, #payment-address input[type=\'hidden\'], #payment-address select'),
				dataType: 'json',
				beforeSend: function() {
					$('#button-payment-address').attr('disabled', true);
					$('#button-payment-address').after('<span class="wait">&nbsp;<img src="components/com_eshop/assets/images/loading.gif" alt="" /></span>');
				},
				complete: function() {
					$('#button-payment-address').attr('disabled', false);
					$('.wait').remove();
				},
				success: function(json) {
					$('.warning, .error').remove();

					if (json['return']) {
						window.location.href = json['return'];
					} else if (json['error']) {
						if (json['error']['warning']) {
							$('#payment-address .checkout-content').prepend('<div class="warning" style="display: none;">' + json['error']['warning'] + '</div>');
							$('.warning').fadeIn('slow');
						}
						var errors = json['error'];
						for (var field in errors)
						{
							errorMessage = errors[field];
							$('#payment-address #' + field).after('<span class="error">' + errorMessage + '</span>');
						}
					} else {
						<?php
						if ($this->shipping_required)
						{
							?>
							var siteUrl = '<?php echo EshopHelper::getSiteUrl(); ?>';
							<?php
							if (EshopHelper::getConfigValue('require_shipping_address' , 1))
							{
								?>
								$.ajax({
									url: siteUrl + 'index.php?option=com_eshop&view=checkout&layout=shipping_address&format=raw<?php echo EshopHelper::getAttachedLangLink(); ?>',
									dataType: 'html',
									success: function(html) {
										 $('#shipping-address .checkout-content').html(html);
										$('#payment-address .checkout-content').slideUp('slow');
										$('#shipping-address .checkout-content').slideDown('slow');
										$('#payment-address .checkout-heading a').remove();
										$('#shipping-address .checkout-heading a').remove();
										$('#shipping-method .checkout-heading a').remove();
										$('#payment-method .checkout-heading a').remove();
										// $('#payment-address .checkout-heading').append('<a><?php echo JText::_('ESHOP_EDIT'); ?></a>');
									},
									error: function(xhr, ajaxOptions, thrownError) {
										alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
									}
								});
								<?php
							}
							else
							{
								?>
								$.ajax({
									url: siteUrl + 'index.php?option=com_eshop&view=checkout&layout=shipping_method&format=raw<?php echo EshopHelper::getAttachedLangLink(); ?>',
									dataType: 'html',
									success: function(html) {
										$('#shipping-method .checkout-content').html(html);
										$('#payment-address .checkout-content').slideUp('slow');
										$('#shipping-method .checkout-content').slideDown('slow');
										$('#payment-address .checkout-heading a').remove();
										$('#shipping-method .checkout-heading a').remove();
										$('#payment-method .checkout-heading a').remove();
										// $('#payment-address .checkout-heading').append('<a><?php echo JText::_('ESHOP_EDIT'); ?></a>');
									},
									error: function(xhr, ajaxOptions, thrownError) {
										alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
									}
								});
								<?php
							}
						}
						else
						{
							?>
							if (json['total'] > 0)
							{
								var siteUrl = '<?php echo EshopHelper::getSiteUrl(); ?>';
								$.ajax({
									url: siteUrl + 'index.php?option=com_eshop&view=checkout&layout=payment_method&format=raw<?php echo EshopHelper::getAttachedLangLink(); ?>',
									dataType: 'html',
									success: function(html) {
										// $('#payment-method .checkout-content').html(html);
										// $('#payment-address .checkout-content').slideUp('slow');
										// $('#payment-method .checkout-content').slideDown('slow');
										// $('#payment-address .checkout-heading a').remove();
										// $('#payment-method .checkout-heading a').remove();
										// $('#payment-address .checkout-heading').append('<a><?php echo JText::_('ESHOP_EDIT'); ?></a>');
									},
									error: function(xhr, ajaxOptions, thrownError) {
										alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
									}
								});
							}
							else
							{
								var siteUrl = '<?php echo EshopHelper::getSiteUrl(); ?>';
								$.ajax({
									url: siteUrl + 'index.php?option=com_eshop&view=checkout&layout=confirm&format=raw<?php echo EshopHelper::getAttachedLangLink(); ?>',
									dataType: 'html',
									success: function(html) {
										//$('#confirm .checkout-content').html(html);
										// $('#payment-address .checkout-content').slideUp('slow');
										// $('#confirm .checkout-content').slideDown('slow');
										// $('#payment-address .checkout-heading a').remove();
										// $('#payment-method .checkout-heading a').remove();
										// $('#payment-address .checkout-heading').append('<a><?php echo JText::_('ESHOP_EDIT'); ?></a>');
									},
									error: function(xhr, ajaxOptions, thrownError) {
										alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
									}
								});
							}
							<?php
						}
						?>
						var siteUrl = '<?php echo EshopHelper::getSiteUrl(); ?>';
						$.ajax({
							url: siteUrl + 'index.php?option=com_eshop&view=checkout&layout=payment_address&format=raw<?php echo EshopHelper::getAttachedLangLink(); ?>',
							dataType: 'html',
							success: function(html) {
								$('#payment-address .checkout-content').html(html);
							},
							error: function(xhr, ajaxOptions, thrownError) {
								alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
							}
						});
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		});

		$('#payment-address input[name=\'payment_address\']').change(function(){
			if (this.value == 'new') {
				$('#payment-existing').hide();
				$('#payment-new').show();
			} else {
				$('#payment-existing').show();
				$('#payment-new').hide();
			}
		});
		<?php
		if (EshopHelper::isFieldPublished('zone_id'))
		{
			?>
			$('#payment-address select[name=\'country_id\']').bind('change', function() {
				var siteUrl = '<?php echo EshopHelper::getSiteUrl(); ?>';
				$.ajax({
					url: siteUrl + 'index.php?option=com_eshop&task=cart.getZones<?php echo EshopHelper::getAttachedLangLink(); ?>&country_id=' + this.value,
					dataType: 'json',
					beforeSend: function() {
						$('.wait').remove();
						$('#payment-address select[name=\'country_id\']').after('<span class="wait">&nbsp;<img src="components/com_eshop/assets/images/loading.gif" alt="" /></span>');
					},
					complete: function() {
						$('.wait').remove();
					},
					success: function(json) {
						html = '<option value=""><?php echo JText::_('ESHOP_PLEASE_SELECT'); ?></option>';
						if (json['zones'] != '')
						{
							for (var i = 0; i < json['zones'].length; i++)
							{
			        			html += '<option value="' + json['zones'][i]['id'] + '"';
							//	if (json['zones'][i]['id'] == '<?php $this->payment_zone_id; ?>')
								if (json['zones'][i]['id'] == 3780)
								{
				      				html += ' selected="selected"';
				    			}
				    			html += '>' + json['zones'][i]['zone_name'] + '</option>';
							}
						}
						$('select[name=\'zone_id\']').html(html);
					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});
			});
			<?php
		}
		?>
	});
//--></script>
