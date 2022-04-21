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

if (isset($this->lists['address_id']))
{
	?>
	<label class="radio">
		<input type="radio" value="existing" name="shipping_address" checked="checked"> <?php echo JText::_('ESHOP_EXISTING_ADDRESS'); ?>
	</label>
	<div id="shipping-existing">
		<?php echo $this->lists['address_id']; ?>
	</div>
	<label class="radio">
		<input type="radio" value="new" name="shipping_address"> <?php echo JText::_('ESHOP_NEW_ADDRESS'); ?>
	</label>
	<?php
}
?>
<div id="shipping-new" style="display: <?php echo (isset($this->lists['address_id']) ? 'none' : 'block'); ?>;" class="form-horizontal">
	<?php
		echo $this->form->render();
	?>
    <button type="button" class="btn btn-outline-warning" id="button-shipping-address" >Lưu địa chỉ <?php //echo JText::_('ESHOP_CONTINUE'); ?></button>
</div>
<?php $user = JFactory::getUser();  ?>

<script type="text/javascript">
	// Shipping Address
	Eshop.jQuery(function($){
	    const siteUrl = '<?php echo EshopHelper::getSiteUrl(); ?>';
      //  $('#shipping-existing select').hide();
		$('#shipping-existing select').on('change', function() {
			$("#button-shipping-address").click();
		});

		$("#shipping-new #firstname").val('<?php echo $user->name; ?>');
		$("#shipping-new #telephone").val('<?php echo $user->username; ?>');
		$("#shipping-new #email").val('<?php echo $user->email; ?>');

		$('#button-shipping-address').click(function(){
			$.ajax({
				url: siteUrl + 'index.php?option=com_eshop&task=checkout.processShippingAddress<?php echo EshopHelper::getAttachedLangLink(); ?>',
				type: 'post',
				data: $('#shipping-address input[type=\'text\'], #shipping-address input[type=\'password\'], #shipping-address input[type=\'checkbox\']:checked, #shipping-address input[type=\'radio\']:checked, #shipping-address select, #shipping-address textarea'),
				dataType: 'json',
				beforeSend: function() {
					$('#button-shipping-address').attr('disabled', true);
					$('#button-shipping-address').after('<span class="wait">&nbsp;<img src="components/com_eshop/assets/images/loading.gif" alt="" /></span>');
				},
				complete: function() {
					$('#button-shipping-address').attr('disabled', false);
					$('.wait').remove();
				},
				success: function(json) {
					$('.warning, .error').remove();

					if (json['return']) {
						window.location.href = json['return'];
					} else if (json['error']) {
						if (json['error']['warning']) {
							$('#shipping-address .box__content-body').prepend('<div class="warning" style="display: none;">' + json['error']['warning'] + '</div>');
							$('.warning').fadeIn('slow');
						}
						var errors = json['error'];
						for (var field in errors)
						{
							errorMessage = errors[field];
							$('#shipping-address #' + field).after('<span class="error">' + errorMessage + '</span>');
						}
					} else {
						$.ajax({
							url: siteUrl + 'index.php?option=com_eshop&view=checkout&layout=shipping_method&format=raw<?php echo EshopHelper::getAttachedLangLink(); ?>',
							dataType: 'html',
							success: function(html) {
                                $('#shipping-method .box__content-body').html(html);
                                $.ajax({
                                    url: siteUrl + 'index.php?option=com_eshop&view=checkout&layout=shipping_address&format=raw<?php echo EshopHelper::getAttachedLangLink(); ?>',
                                    dataType: 'html',
                                    success: function(html) {
                                        $('#shipping-address .box__content-body').html(html);
                                        $('#button-shipping-method').click();
                                    },
                                    error: function(xhr, ajaxOptions, thrownError) {
                                        console.log(xhr.responseText)
                                    }
                                });
							},
							error: function(xhr, ajaxOptions, thrownError) {
								console.log(xhr.responseText)
							}
						});
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
					console.log(xhr.responseText)
				}
			});
		});

		$('#shipping-address input[name=\'shipping_address\']').change(function(){
			if (this.value == 'new') {
				$('#shipping-existing').hide();
				$('#shipping-new').show();
			} else {
				$('#shipping-existing').show();
				$('#shipping-new').hide();
			}
		});
		<?php
		if (EshopHelper::isFieldPublished('zone_id'))
		{
			?>
			$('#shipping-address select[name=\'country_id\']').bind('change', function() {
				$.ajax({
					url: siteUrl + 'index.php?option=com_eshop&task=cart.getZones<?php echo EshopHelper::getAttachedLangLink(); ?>&country_id=' + this.value,
					dataType: 'json',
					beforeSend: function() {
						$('.wait').remove();
						$('#shipping-address select[name=\'country_id\']').after('<span class="wait">&nbsp;<img src="components/com_eshop/assets/images/loading.gif" alt="" /></span>');
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
								if (json['zones'][i]['id'] == '<?php $this->shipping_zone_id; ?>')
								{
				      				html += ' selected="selected"';
				    			}
				    			html += '>' + json['zones'][i]['zone_name'] + '</option>';
							}
						}
						$('select[name=\'zone_id\']').html(html);
					},
					error: function(xhr, ajaxOptions, thrownError) {
						console.log(thrownError)
					}
				});
			});
			<?php
		}
		?>
	});
</script>