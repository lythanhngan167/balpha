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
$controlGroupClass      = $bootstrapHelper->getClassMapping('control-group');
$controlLabelClass      = $bootstrapHelper->getClassMapping('control-label');
$controlsClass          = $bootstrapHelper->getClassMapping('controls');
$pullRightClass         = $bootstrapHelper->getClassMapping('pull-right');
$btnClass				= $bootstrapHelper->getClassMapping('btn');

if (isset($this->shipping_methods))
{
	?>
	<div>
		<p><?php echo JText::_('ESHOP_SHIPPING_METHOD_TITLE'); ?></p>
		<?php
		$firstShippingOption = true;
		foreach ($this->shipping_methods as $shippingMethod)
		{
			?>
			<div>
				<strong><?php echo $shippingMethod['title']; ?></strong><br />
				<?php
				foreach ($shippingMethod['quote'] as $quote)
				{
					$checkedStr = ' ';
					if ($quote['name'] == $this->shipping_method)
					{
						$checkedStr = ' checked = "checked" ';
					}
					else
					{
						if ($firstShippingOption)
						{
							$checkedStr = ' checked = "checked" ';
						}
					}
					$firstShippingOption = false;
					?>
					<label class="radio">
						<input type="radio" value="<?php echo $quote['name']; ?>" name="shipping_method" <?php echo $checkedStr; ?>/>
						<?php echo $quote['title'] . ($quote['text'] != '' ? ' (' . $quote['text'] . ')' : ''); ?>
					</label>
					<?php
				}
				?>
			</div>
			<?php
		}
		?>
	</div>
	<?php
}
else
{
	?>
	<div class="no-shipping-method"><?php echo JText::_('ESHOP_NO_SHIPPING_METHOD_AVAILABLE'); ?></div>
	<?php
}
if (EshopHelper::getConfigValue('delivery_date'))
{
	?>
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
	<br />
	<div class="<?php echo $controlGroupClass; ?>">
		<label for="textarea" class="<?php echo $controlLabelClass; ?>"><?php echo JText::_('ESHOP_DELIVERY_DATE'); ?></label>
		<div class="<?php echo $controlsClass; ?>">
			<?php echo JHtml::_('calendar', $this->delivery_date ? $this->delivery_date : '', 'delivery_date', 'delivery_date', '%Y-%m-%d'); ?>
		</div>
	</div>
	<?php
}
?>
<div class="<?php echo $controlGroupClass; ?>">
	<label for="textarea" class="<?php echo $controlLabelClass; ?>"><?php echo JText::_('ESHOP_COMMENT_ORDER'); ?></label>
	<div class="<?php echo $controlsClass; ?>">
		<textarea rows="8" id="textarea" class="input-xlarge" name="comment"><?php echo $this->comment; ?></textarea>
	</div>
</div>
<button type="button" class="btn btn-outline-warning" id="button-shipping-method" ><?php echo JText::_('ESHOP_CONTINUE'); ?></button>
<script type="text/javascript">
	//Shipping Method
	Eshop.jQuery(function($){
		$('#button-shipping-method').click(function(){
			var siteUrl = '<?php echo EshopHelper::getSiteUrl(); ?>';
			$.ajax({
				url: siteUrl + 'index.php?option=com_eshop&task=checkout.processShippingMethod<?php echo EshopHelper::getAttachedLangLink(); ?>',
				type: 'post',
				data: $('#shipping-method input[type=\'radio\']:checked, #shipping-method textarea, #shipping-method input[type=\'text\']'),
				dataType: 'json',
				beforeSend: function() {
					$('#button-shipping-method').attr('disabled', true);
					$('#button-shipping-method').after('<span class="wait">&nbsp;<img src="components/com_eshop/assets/images/loading.gif" alt="" /></span>');
				},
				complete: function() {
					$('#button-shipping-method').attr('disabled', false);
					$('.wait').remove();
				},
				success: function(json) {
					 $('.warning, .error').remove();
					 if (json['return']) {
					 	window.location.href = json['return'];
					 } else if (json['error']) {
					 	if (json['error']['warning']) {
					 		$('#shipping-method .checkout-content').prepend('<div class="warning" style="display: none;">' + json['error']['warning'] + '</div>');
					 		$('.warning').fadeIn('slow');
					 	}
					 } else if (json['total'] > 0) {
					 	$.ajax({
					 		url: siteUrl + 'index.php?option=com_eshop&view=checkout&layout=payment_method&format=raw<?php echo EshopHelper::getAttachedLangLink(); ?>',
					 		dataType: 'html',
					 		success: function(html) {
					 			//$('#payment-method .checkout-content').html(html);
					 			// $('#shipping-method .checkout-content').slideUp('slow');
					 			// $('#payment-method .checkout-content').slideDown('slow');
					 			// $('#shipping-method .checkout-heading a').remove();
					 			// $('#payment-method .checkout-heading a').remove();
					 			// $('#shipping-method .checkout-heading').append('<a><?php echo JText::_('ESHOP_EDIT'); ?></a>');
					 		},
					 		error: function(xhr, ajaxOptions, thrownError) {
					 			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					 		}
					 	});
					 } else {
					 	$.ajax({
					 		url: siteUrl + 'index.php?option=com_eshop&view=checkout&layout=confirm&format=raw<?php echo EshopHelper::getAttachedLangLink(); ?>',
					 		dataType: 'html',
					 		success: function(html) {
					 			$('#confirm .checkout-content').html(html);
					 			// $('#shipping-method .checkout-content').slideUp('slow');
					 			// $('#confirm .checkout-content').slideDown('slow');
					 			// $('#shipping-method .checkout-heading a').remove();
					 			// $('#payment-method .checkout-heading a').remove();
					 			// $('#shipping-method .checkout-heading').append('<a><?php echo JText::_('ESHOP_EDIT'); ?></a>');
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
	})
</script>
