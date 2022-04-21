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
$rowFluidClass          = $bootstrapHelper->getClassMapping('row-fluid');
$span6Class             = $bootstrapHelper->getClassMapping('span6');
$pullRightClass         = $bootstrapHelper->getClassMapping('pull-right');
$controlGroupClass      = $bootstrapHelper->getClassMapping('control-group');
$controlLabelClass      = $bootstrapHelper->getClassMapping('control-label');
$controlsClass          = $bootstrapHelper->getClassMapping('controls');
$btnClass				= $bootstrapHelper->getClassMapping('btn');
?>
<div class="<?php echo $rowFluidClass; ?> clearfix">
	<div class="<?php echo $span6Class; ?> no_margin_left">
		<legend><?php echo JText::_('ESHOP_YOUR_PERSONAL_DETAILS'); ?></legend>
		<?php
			$personalFields = array(
				'firstname',
				'lastname',
				'email',
				'telephone',
				'fax'
			);
			$fields = $this->form->getFields();
			foreach ($fields as $field)
			{
				if (in_array($field->name, $personalFields))
				{
					echo $field->getControlGroup();
				}
			}
		?>
	</div>
	<div class="<?php echo $span6Class; ?>">
		<legend><?php echo JText::_('ESHOP_YOUR_ADDRESS'); ?></legend>
		<?php
		if (isset($this->lists['customergroup_id']))
		{
			?>
			<div class="<?php echo $controlGroupClass; ?>">
				<label class="<?php echo $controlLabelClass; ?>" for="customergroup_id"><?php echo JText::_('ESHOP_CUSTOMER_GROUP'); ?></label>
				<div class="<?php echo $controlsClass; ?> docs-input-sizes">
					<?php echo $this->lists['customergroup_id']; ?>
				</div>
			</div>
			<?php
		}
		elseif (isset($this->lists['default_customergroup_id']))
		{
			?>
			<input type="hidden" name="customergroup_id" value="<?php echo $this->lists['default_customergroup_id']; ?>" />
			<?php
		}
		foreach ($fields as $field)
		{
			if (!in_array($field->name, $personalFields))
			{
				echo $field->getControlGroup();
			}
		}
		?>
	</div>
</div>
<?php
if ($this->shipping_required && EshopHelper::getConfigValue('require_shipping_address', 1))
{
?>
	<div class="no_margin_right">
		<label class="checkbox"><input type="checkbox" value="1" name="shipping_address"><?php echo JText::_('ESHOP_SHIPPING_ADDRESS_SAME'); ?></label>
	</div>
<?php
}
?>
<div class="no_margin_left">
	<input type="button" class="<?php echo $btnClass; ?> btn-primary <?php echo $pullRightClass; ?>" id="button-guest" value="<?php echo JText::_('ESHOP_CONTINUE'); ?>" />
</div>
<script type="text/javascript">
<!--
	Eshop.jQuery(document).ready(function($){
		<?php
		if (EshopHelper::isFieldPublished('zone_id'))
		{
			?>
			$('#payment-address select[name=\'country_id\']').change(function(){
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
								if (json['zones'][i]['id'] == '<?php $this->payment_zone_id; ?>')
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
		//Guest
		$('#button-guest').click(function(){
			var siteUrl = '<?php echo EshopHelper::getSiteUrl(); ?>';
			$.ajax({
				url: siteUrl + 'index.php?option=com_eshop&task=checkout.guest<?php echo EshopHelper::getAttachedLangLink(); ?>',
				type: 'post',
				data: $('#payment-address input[type=\'text\'], #payment-address textarea, #payment-address input[type=\'checkbox\']:checked, #payment-address input[type=\'radio\']:checked, #payment-address select, #payment-address input[type=\'hidden\']'),
				dataType: 'json',
				beforeSend: function() {
					$('#button-guest').attr('disabled', true);
					$('#button-guest').after('<span class="wait">&nbsp;<img src="components/com_eshop/assets/images/loading.gif" alt="" /></span>');
				},
				complete: function() {
					$('#button-guest').attr('disabled', false);
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
						//Firstname validate
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
							if (EshopHelper::getConfigValue('require_shipping_address' , 1))
							{
								?>
								var shipping_address = $('#payment-address input[name=\'shipping_address\']:checked').attr('value');
								if (shipping_address) {
									var siteUrl = '<?php echo EshopHelper::getSiteUrl(); ?>';
									$.ajax({
										url: siteUrl + 'index.php?option=com_eshop&view=checkout&layout=shipping_method&format=raw<?php echo EshopHelper::getAttachedLangLink(); ?>',
										dataType: 'html',
										success: function(html) {
											$('#shipping-method .checkout-content').html(html);
											$('#payment-address .checkout-content').slideUp('slow');
											$('#shipping-method .checkout-content').slideDown('slow');
											$('#payment-address .checkout-heading a').remove();
											$('#shipping-address .checkout-heading a').remove();
											$('#shipping-method .checkout-heading a').remove();
											$('#payment-method .checkout-heading a').remove();
											$('#payment-address .checkout-heading').append('<a><?php echo JText::_('ESHOP_EDIT'); ?></a>');
											$('#shipping-address .checkout-heading').append('<a><?php echo JText::_('ESHOP_EDIT'); ?></a>');
											$.ajax({
												url: siteUrl + 'index.php?option=com_eshop&view=checkout&layout=guest_shipping&format=raw<?php echo EshopHelper::getAttachedLangLink(); ?>',
												dataType: 'html',
												success: function(html) {
													$('#shipping-address .checkout-content').html(html);
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
								} else {
									var siteUrl = '<?php echo EshopHelper::getSiteUrl(); ?>';
									$.ajax({
										url: siteUrl + 'index.php?option=com_eshop&view=checkout&layout=guest_shipping&format=raw<?php echo EshopHelper::getAttachedLangLink(); ?>',
										dataType: 'html',
										success: function(html) {
											$('#shipping-address .checkout-content').html(html);
											$('#payment-address .checkout-content').slideUp('slow');
											$('#shipping-address .checkout-content').slideDown('slow');
											$('#payment-address .checkout-heading a').remove();
											$('#shipping-address .checkout-heading a').remove();
											$('#shipping-method .checkout-heading a').remove();
											$('#payment-method .checkout-heading a').remove();
											$('#payment-address .checkout-heading').append('<a><?php echo JText::_('ESHOP_EDIT'); ?></a>');
										},
										error: function(xhr, ajaxOptions, thrownError) {
											alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
										}
									});
								}
								<?php
							}
							else
							{
								?>
								var siteUrl = '<?php echo EshopHelper::getSiteUrl(); ?>';
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
										$('#payment-address .checkout-heading').append('<a><?php echo JText::_('ESHOP_EDIT'); ?></a>');
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
										$('#payment-method .checkout-content').html(html);
										$('#payment-address .checkout-content').slideUp('slow');
										$('#payment-method .checkout-content').slideDown('slow');
										$('#payment-address .checkout-heading a').remove();
										$('#payment-method .checkout-heading a').remove();
										$('#payment-address .checkout-heading').append('<a><?php echo JText::_('ESHOP_EDIT'); ?></a>');
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
										$('#confirm .checkout-content').html(html);
										$('#payment-address .checkout-content').slideUp('slow');
										$('#confirm .checkout-content').slideDown('slow');
										$('#payment-address .checkout-heading a').remove();
										$('#payment-method .checkout-heading a').remove();
										$('#payment-address .checkout-heading').append('<a><?php echo JText::_('ESHOP_EDIT'); ?></a>');
									},
									error: function(xhr, ajaxOptions, thrownError) {
										alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
									}
								});
							}
							<?php
						}
						?>
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		});
	})
	//-->
</script>
