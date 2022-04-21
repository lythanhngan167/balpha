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
$controlGroupClass      = $bootstrapHelper->getClassMapping('control-group');
$controlLabelClass      = $bootstrapHelper->getClassMapping('control-label');
$controlsClass          = $bootstrapHelper->getClassMapping('controls');
$pullLeftClass          = $bootstrapHelper->getClassMapping('pull-left');
$inputAppendClass       = $bootstrapHelper->getClassMapping('input-append');
$inputPrependClass      = $bootstrapHelper->getClassMapping('input-prepend');
$imgPolaroid            = $bootstrapHelper->getClassMapping('img-polaroid');
$btnClass				= $bootstrapHelper->getClassMapping('btn');
?>
<script src="<?php echo JUri::base(true); ?>/components/com_eshop/assets/colorbox/jquery.colorbox.js" type="text/javascript"></script>
<?php
if (isset($this->success))
{
	?>
	<div class="success"><?php echo $this->success; ?></div>
	<?php
}
?>
<h1><?php echo JText::_('ESHOP_QUOTE_CART'); ?></h1><br />
<?php
if (!count($this->quoteData))
{
	?>
	<div class="no-content"><?php echo JText::_('ESHOP_QUOTE_EMPTY'); ?></div>
	<?php
}
else
{
	?>
	<div class="quote-info">
		<table class="table table-responsive table-bordered table-striped">
			<thead>
				<tr>
					<th style="text-align: center;"><?php echo JText::_('ESHOP_REMOVE'); ?></th>
						<th style="text-align: center;"><?php echo JText::_('ESHOP_IMAGE'); ?></th>
					<th><?php echo JText::_('ESHOP_PRODUCT_NAME'); ?></th>
					<th><?php echo JText::_('ESHOP_MODEL'); ?></th>
					<th><?php echo JText::_('ESHOP_QUANTITY'); ?></th>
					<?php
					if (EshopHelper::showPrice())
					{
						?>
						<th><?php echo JText::_('ESHOP_UNIT_PRICE'); ?></th>
						<th><?php echo JText::_('ESHOP_TOTAL'); ?></th>
						<?php
					}
					?>
				</tr>
			</thead>
			<tbody>
				<?php
				$totalPrice = 0;
				$countProducts = 0;
				foreach ($this->quoteData as $key => $product)
				{
					$countProducts++;
					$optionData = $product['option_data'];
					$viewProductUrl = JRoute::_(EshopRoute::getProductRoute($product['product_id'], EshopHelper::getProductCategory($product['product_id'])));
					if (EshopHelper::showPrice() && !$product['product_call_for_price'])
					{
						$totalPrice += $product['total_price'];
					}
					?>
					<tr>
						<td class="eshop-center-text" style="vertical-align: middle;" data-content="<?php echo JText::_('ESHOP_REMOVE'); ?>">
							<a class="eshop-remove-item-quote" id="<?php echo $key; ?>" style="cursor: pointer;">
								<img alt="<?php echo JText::_('ESHOP_REMOVE'); ?>" title="<?php echo JText::_('ESHOP_REMOVE'); ?>" src="<?php echo JUri::base(true); ?>/components/com_eshop/assets/images/remove.png" />
							</a>
						</td>
						<td class="muted eshop-center-text" style="vertical-align: middle;" data-content="<?php echo JText::_('ESHOP_IMAGE'); ?>">
							<a href="<?php echo $viewProductUrl; ?>">
								<img class="<?php echo $imgPolaroid; ?>" src="<?php echo $product['image']; ?>" />
							</a>
						</td>
						<td style="vertical-align: middle;" data-content="<?php echo JText::_('ESHOP_PRODUCT_NAME'); ?>">
							<a href="<?php echo $viewProductUrl; ?>">
								<?php echo $product['product_name']; ?>
							</a>
							<br />	
							<?php
							for ($i = 0; $n = count($optionData), $i < $n; $i++)
							{
								echo '- ' . $optionData[$i]['option_name'] . ': ' . $optionData[$i]['option_value'] . (isset($optionData[$i]['sku']) && $optionData[$i]['sku'] != '' ? ' (' . $optionData[$i]['sku'] . ')' : '') . '<br />';
							}
							?>
						</td>
						<td style="vertical-align: middle;" data-content="<?php echo JText::_('ESHOP_MODEL'); ?>"><?php echo $product['product_sku']; ?></td>
						<td style="vertical-align: middle;" data-content="<?php echo JText::_('ESHOP_QUANTITY'); ?>">
							<div class="<?php echo $inputAppendClass; ?> <?php echo $inputPrependClass; ?>">
								<span class="eshop-quantity">
									<input type="hidden" name="key[]" value="<?php echo $key; ?>" />
									<a class="<?php echo $btnClass; ?> btn-default button-plus" id="<?php echo $countProducts; ?>" data="up">+</a>
										<input type="text" class="eshop-quantity-value" value="<?php echo htmlspecialchars($product['quantity'], ENT_COMPAT, 'UTF-8'); ?>" name="quantity[]" id="quantity_<?php echo $countProducts; ?>" />
									<a class="<?php echo $btnClass; ?> btn-default button-minus" id="<?php echo $countProducts; ?>" data="down">-</a>	
								</span>
							</div>
						</td>
						<?php
						if (EshopHelper::showPrice())
						{
							?>
							<td style="vertical-align: middle;" data-content="<?php echo JText::_('ESHOP_UNIT_PRICE'); ?>">
								<?php
								if (!$product['product_call_for_price'])
								{
									echo $this->currency->format($product['price']);
								}
								?>
							</td>
							<td style="vertical-align: middle;" data-content="<?php echo JText::_('ESHOP_TOTAL'); ?>">
								<?php
								if (!$product['product_call_for_price'])
								{
									echo $this->currency->format($product['total_price']);
								}	
								?>
							</td>
							<?php
						}
						?>
					</tr>
					<?php
				}
				if (EshopHelper::showPrice())
				{
					?>
					<tr>
						<td colspan="6" style="text-align: right;"><?php echo JText::_('ESHOP_TOTAL'); ?>:</td>
						<td><strong><?php echo $this->currency->format($totalPrice); ?></strong></td>
					</tr>
					<?php
				}
				?>
			</tbody>
		</table>
	</div>
	<div class="<?php echo $controlGroupClass; ?>" style="text-align: center;">
		<div class="<?php echo $controlsClass; ?>">
			<button type="button" class="<?php echo $btnClass; ?> btn-primary" onclick="updateQuote();" id="update-quote"><?php echo JText::_('ESHOP_UPDATE_QUOTE'); ?></button>
		</div>
	</div>
	<div class="<?php echo $rowFluidClass; ?>">
		<legend id="quote-form-title"><?php echo JText::_('ESHOP_QUOTE_FORM'); ?></legend>
		<div id="quote-form-area">
			<form method="post" name="adminForm" id="adminForm" action="index.php" class="form form-horizontal">
				<div class="<?php echo $controlGroupClass; ?>">
					<label class="<?php echo $controlLabelClass; ?>" for="name"><span class="required">*</span><?php echo JText::_('ESHOP_QUOTE_NAME'); ?>:</label>
					<div class="<?php echo $controlsClass; ?> docs-input-sizes">
						<input type="text" class="input-large" name="name" id="name" value="" />
						<span style="display: none;" class="error name-required"><?php echo JText::_('ESHOP_QUOTE_NAME_REQUIRED'); ?></span>
					</div>
				</div>
				<div class="<?php echo $controlGroupClass; ?>">
					<label class="<?php echo $controlLabelClass; ?>" for="email"><span class="required">*</span><?php echo JText::_('ESHOP_QUOTE_EMAIL'); ?>:</label>
					<div class="<?php echo $controlsClass; ?> docs-input-sizes">
						<input type="text" class="input-large" name="email" id="email" value="" />
						<span style="display: none;" class="error email-required"><?php echo JText::_('ESHOP_QUOTE_EMAIL_REQUIRED'); ?></span>
						<span style="display: none;" class="error email-invalid"><?php echo JText::_('ESHOP_QUOTE_EMAIL_INVALID'); ?></span>
					</div>
				</div>
				<div class="<?php echo $controlGroupClass; ?>">
					<label class="<?php echo $controlLabelClass; ?>" for="company"><?php echo JText::_('ESHOP_QUOTE_COMPANY'); ?>:</label>
					<div class="<?php echo $controlsClass; ?> docs-input-sizes">
						<input type="text" class="input-large" name="company" id="company" value="" />
					</div>
				</div>
				<div class="<?php echo $controlGroupClass; ?>">
					<label class="<?php echo $controlLabelClass; ?>" for="phone"><?php echo JText::_('ESHOP_QUOTE_TELEPHONE'); ?>:</label>
					<div class="<?php echo $controlsClass; ?> docs-input-sizes">
						<input type="text" class="input-large" name="telephone" id="telephone" value="" />
					</div>
				</div>
				<div class="<?php echo $controlGroupClass; ?>">
					<label class="<?php echo $controlLabelClass; ?>" for="message"><span class="required">*</span><?php echo JText::_('ESHOP_QUOTE_MESSAGE'); ?>:</label>
					<div class="<?php echo $controlsClass; ?> docs-input-sizes">
						<textarea rows="5" cols="5" name="message" id="message"></textarea>
						<span style="display: none;" class="error message-required"><?php echo JText::_('ESHOP_QUOTE_MESSAGE_REQUIRED'); ?></span>
					</div>
				</div>
				<?php
				if (EshopHelper::getConfigValue('acymailing_integration') || EshopHelper::getConfigValue('mailchimp_integration'))
				{
					?>
					<div class="<?php echo $controlGroupClass; ?>">
						<span class="newsletter-interest">
							<input type="checkbox" value="1" name="newsletter_interest" /><?php echo JText::_('ESHOP_NEWSLETTER_INTEREST'); ?>
						</span>
					</div>	
					<?php
				}
				
				if ($this->showCaptcha)
				{
					?>
					<div class="<?php echo $controlGroupClass; ?>">
						<label class="<?php echo $controlLabelClass; ?>" for="recaptcha_response_field">
							<?php echo JText::_('ESHOP_CAPTCHA'); ?><span class="required">*</span>
						</label>
						<div class="<?php echo $controlsClass; ?> docs-input-sizes">
							<?php echo $this->captcha; ?>
						</div>
					</div>
					<?php
				}
				?>
				<input type="button" class="<?php echo $btnClass; ?> btn-primary <?php echo $pullLeftClass; ?>" id="button-ask-quote" value="<?php echo JText::_('ESHOP_QUOTE_REQUEST_QUOTE'); ?>" />
				<span class="wait"></span>
			</form>
		</div>
	</div>
	<script type="text/javascript">
		//Function to update quote
		function updateQuote(key)
		{
			Eshop.jQuery(function($){
				var siteUrl = '<?php echo EshopHelper::getSiteUrl(); ?>';
				$.ajax({
					type: 'POST',
					url: siteUrl + 'index.php?option=com_eshop&task=quote.updates<?php echo EshopHelper::getAttachedLangLink(); ?>',
					data: $('.quote-info input[type=\'text\'], .quote-info input[type=\'hidden\']'),
					beforeSend: function() {
						$('#update-quote').attr('disabled', true);
						$('#update-quote').after('<span class="wait">&nbsp;<img src="components/com_eshop/assets/images/loading.gif" alt="" /></span>');
					},
					complete: function() {
						$('#update-quote').attr('disabled', false);
						$('.wait').remove();
					},
					success: function() {
						window.location.href = "<?php echo JRoute::_(EshopRoute::getViewRoute('quote')); ?>";
				  	}
				});
			})
		}

		Eshop.jQuery(function($) {
			//Ajax remove quote item
			$('.eshop-remove-item-quote').bind('click', function() {
				var aTag = $(this);
				var id = aTag.attr('id');
				var siteUrl = '<?php echo EshopHelper::getSiteUrl(); ?>';
				$.ajax({
					type :'POST',
					url: siteUrl + 'index.php?option=com_eshop&task=quote.remove&key=' +  id + '&redirect=1<?php echo EshopHelper::getAttachedLangLink(); ?>',
					beforeSend: function() {
						aTag.attr('disabled', true);
						aTag.after('<span class="wait">&nbsp;<img src="components/com_eshop/assets/images/loading.gif" alt="" /></span>');
					},
					complete: function() {
						aTag.attr('disabled', false);
						$('.wait').remove();
					},
					success : function() {
						window.location.href = '<?php echo JRoute::_(EshopRoute::getViewRoute('quote')); ?>';
					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});
			});
		});

		Eshop.jQuery(function($){
			$('#button-ask-quote').click(function(){
				var siteUrl = '<?php echo EshopHelper::getSiteUrl(); ?>';
				$.ajax({
					url: siteUrl + 'index.php?option=com_eshop&task=quote.processQuote<?php echo EshopHelper::getAttachedLangLink(); ?>',
					type: 'post',
					data: $('#quote-form-area input[type=\'text\'], #quote-form-area input[type=\'hidden\'], #quote-form-area textarea, #quote-form-area input[type=\'checkbox\']:checked'),
					dataType: 'json',
					beforeSend: function() {
						$('#button-ask-quote').attr('disabled', true);
						$('#button-ask-quote').after('<span class="wait">&nbsp;<img src="components/com_eshop/assets/images/loading.gif" alt="" /></span>');
					},
					complete: function() {
						$('#button-ask-quote').attr('disabled', false);
						$('.wait').remove();
					},
					success: function(json) {
						$('.error').remove();
						if (json['return']) {
							window.location.href = json['return'];
						} else if (json['error']) {
							//name error
							if (json['error']['name']) {
								$('#quote-form-area input[name=\'name\']').after('<span class="error">' + json['error']['name'] + '</span>');
							}
							//name error
							if (json['error']['email']) {
								$('#quote-form-area input[name=\'email\']').after('<span class="error">' + json['error']['email'] + '</span>');
							}
							//message error
							if (json['error']['message']) {
								$('#message').after('<span class="error">' + json['error']['message'] + '</span>');
							}
							//captcha error
							if (json['error']['captcha']) {
								$('#dynamic_recaptcha_1').after('<span class="error">' + json['error']['captcha'] + '</span>');
							}
						} else {
							//redirect to complete page
							window.location.href = json['success'];
						}	  
					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});
			});
		});
	</script>
	<?php
}