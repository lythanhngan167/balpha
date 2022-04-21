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
if (isset($this->warning))
{
	?>
	<div class="warning"><?php echo $this->warning; ?></div>
	<?php
}
?>
<h1>
	<?php echo JText::_('ESHOP_SHOPPING_CART'); ?>
	<?php
	if ($this->weight)
	{
		echo '&nbsp;(' . $this->weight . ')';
	}
	?>
</h1><br />
<?php
if (!count($this->cartData))
{
	?>
	<div class="no-content"><?php echo JText::_('ESHOP_CART_EMPTY'); ?></div>
	<?php
}
else
{
	?>
	<div class="cart-info">
		<?php
		$countProducts = 0;
		?>
		<table class="table table-responsive table-bordered table-striped">
			<thead>
				<tr>
					<th style="text-align: center;"><?php echo JText::_('ESHOP_REMOVE'); ?></th>
					<th style="text-align: center;"><?php echo JText::_('ESHOP_IMAGE'); ?></th>
					<th><?php echo JText::_('ESHOP_PRODUCT_NAME'); ?></th>
					<th><?php echo JText::_('ESHOP_MODEL'); ?></th>
					<th><?php echo JText::_('ESHOP_QUANTITY'); ?></th>
					<th><?php echo JText::_('ESHOP_UNIT_PRICE'); ?></th>
					<th><?php echo JText::_('ESHOP_TOTAL'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php
				foreach ($this->cartData as $key => $product)
				{
					$countProducts++;
					$optionData = $product['option_data'];
					$viewProductUrl = JRoute::_(EshopRoute::getProductRoute($product['product_id'], EshopHelper::getProductCategory($product['product_id'])));
					?>
					<tr>
						<td class="eshop-center-text" style="vertical-align: middle;" data-content="<?php echo JText::_('ESHOP_REMOVE'); ?>">
							<a class="eshop-remove-item-cart" id="<?php echo $key; ?>" style="cursor: pointer;">
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
							<?php
							if (EshopHelper::getConfigValue('stock_warning') && !$product['stock'])
							{
								?>
								<span class="stock">***</span>
								<?php
							}
							?>
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
						<td style="vertical-align: middle;" data-content="<?php echo JText::_('ESHOP_UNIT_PRICE'); ?>">
							<?php
							if (EshopHelper::showPrice())
							{
								if (EshopHelper::getConfigValue('include_tax_anywhere', '0'))
								{
									echo $this->currency->format($this->tax->calculate($product['price'], $product['product_taxclass_id'], EshopHelper::getConfigValue('tax')));
								}
								else
								{
									echo $this->currency->format($product['price']);
								}
							}
							?>
						</td>
						<td style="vertical-align: middle;" data-content="<?php echo JText::_('ESHOP_TOTAL'); ?>">
							<?php
							if (EshopHelper::showPrice())
							{
								if (EshopHelper::getConfigValue('include_tax_anywhere', '0'))
								{
									echo $this->currency->format($this->tax->calculate($product['total_price'], $product['product_taxclass_id'], EshopHelper::getConfigValue('tax')));
								}
								else
								{
									echo $this->currency->format($product['total_price']);
								}
							}
							?>
						</td>
					</tr>
					<?php
				}
				if (EshopHelper::showPrice())
				{
					foreach ($this->totalData as $data)
					{
						?>
						<tr>
							<td colspan="6" style="text-align: right;"><?php echo $data['title']; ?>:</td>
							<td><strong><?php echo $data['text']; ?></strong></td>
						</tr>
						<?php
					}
				}
				?>
			</tbody>
		</table>
    </div>
    <div class="<?php echo $controlGroupClass; ?>" style="text-align: center;">
		<div class="<?php echo $controlsClass; ?>">
			<button type="button" class="<?php echo $btnClass; ?> btn-primary" onclick="updateCart();" id="update-cart"><?php echo JText::_('ESHOP_UPDATE_CART'); ?></button>
		</div>
	</div>
    <?php
	if (EshopHelper::getConfigValue('allow_coupon'))
	{
		?>
		<table class="table table-bordered table-striped">
			<tbody>
				<tr>
					<td class="form-horizontal">
						<div class="<?php echo $controlGroupClass; ?>">
							<label for="coupon_code" class="<?php echo $controlLabelClass; ?>"><strong><?php echo JText::_('ESHOP_COUPON_TEXT'); ?>: </strong></label>
							<div class="<?php echo $controlsClass; ?>">
								<input type="text" id="coupon_code" name="coupon_code" class="input-large" value="<?php echo htmlspecialchars($this->coupon_code, ENT_COMPAT, 'UTF-8'); ?>">
								<button type="button" class="<?php echo $btnClass; ?> btn-primary" onclick="applyCoupon();" id="apply-coupon"><?php echo JText::_('ESHOP_COUPON_APPLY'); ?></button>
							</div>
						</div>
					</td>
				</tr>
			</tbody>
		</table>
		<?php
	}
	if (EshopHelper::getConfigValue('allow_voucher'))
	{
		?>
		<table class="table table-bordered table-striped">
			<tbody>
				<tr>
					<td class="form-horizontal">
						<div class="<?php echo $controlGroupClass; ?>">
							<label for="voucher_code" class="<?php echo $controlLabelClass; ?>"><strong><?php echo JText::_('ESHOP_VOUCHER_TEXT'); ?>: </strong></label>
							<div class="<?php echo $controlsClass; ?>">
								<input type="text" id="voucher_code" name="voucher_code" class="input-large" value="<?php echo htmlspecialchars($this->voucher_code, ENT_COMPAT, 'UTF-8'); ?>">
								<button type="button" class="<?php echo $btnClass; ?> btn-primary" onclick="applyVoucher();" id="apply-voucher"><?php echo JText::_('ESHOP_VOUCHER_APPLY'); ?></button>
							</div>
						</div>
					</td>
				</tr>
			</tbody>
		</table>
		<?php
	}
	if (EshopHelper::getConfigValue('shipping_estimate') && $this->shipping_required)
	{
		?>
		<table class="table table-bordered table-striped">
			<tbody>
				<tr>
					<th><?php echo JText::_('ESHOP_SHIPPING_ESTIMATE_TEXT'); ?></th>
				</tr>
				<tr>
					<td class="form-horizontal">
						<div class="<?php echo $controlGroupClass; ?>">
							<label for="country_id" class="<?php echo $controlLabelClass; ?>"><span class="required">*</span><strong><?php echo JText::_('ESHOP_COUNTRY'); ?>:</strong></label>
							<div class="<?php echo $controlsClass; ?>">
								<?php echo $this->lists['country_id']; ?>
							</div>
						</div>
						<div class="<?php echo $controlGroupClass; ?>">
							<label for="zone_id" class="<?php echo $controlLabelClass; ?>"><span class="required">*</span><strong><?php echo JText::_('ESHOP_REGION_STATE'); ?>:</strong></label>
							<div class="<?php echo $controlsClass; ?>">
								<?php echo $this->lists['zone_id']; ?>
							</div>
						</div>
						<div class="<?php echo $controlGroupClass; ?>">
							<label for="postcode" class="<?php echo $controlLabelClass; ?>"><span class="required" id="postcode-required" style="display: none;">*</span><strong><?php echo JText::_('ESHOP_POST_CODE'); ?>:</strong></label>
							<div class="<?php echo $controlsClass; ?>">
								<input class="input-small" name="postcode" id="postcode" value="<?php echo $this->postcode; ?>" />
							</div>
						</div>
						<div class="<?php echo $controlGroupClass; ?>">
							<div class="<?php echo $controlsClass; ?>">
								<button type="button" id="get-quotes" class="<?php echo $btnClass; ?> btn-primary"><?php echo JText::_('ESHOP_GET_QUOTES'); ?></button>
							</div>
						</div>
					</td>
				</tr>
			</tbody>
		</table>
		<?php
	}
	?>
	<a class="<?php echo $btnClass; ?> btn-primary" href="<?php echo JRoute::_(EshopHelper::getContinueShopingUrl()); ?>"><?php echo JText::_('ESHOP_CONTINUE_SHOPPING'); ?></a>
	<?php
	if (EshopHelper::getConfigValue('active_https'))
	{
		$checkoutUrl = JRoute::_(EshopRoute::getViewRoute('checkout'), true, 1);
	}
	else
	{
		$checkoutUrl = JRoute::_(EshopRoute::getViewRoute('checkout'));
	}
	?>
	<a class="<?php echo $btnClass; ?> btn-primary <?php echo $pullRightClass; ?>" href="<?php echo $checkoutUrl; ?>"><?php echo JText::_('ESHOP_CHECKOUT'); ?></a>

	<script type="text/javascript">
		//Function to update cart
		function updateCart()
		{
			Eshop.jQuery(function($){
				var siteUrl = '<?php echo EshopHelper::getSiteUrl(); ?>';
				$.ajax({
					type: 'POST',
					url: siteUrl + 'index.php?option=com_eshop&task=cart.updates<?php echo EshopHelper::getAttachedLangLink(); ?>',
					data: $('.cart-info input[type=\'text\'], .cart-info input[type=\'hidden\']'),
					beforeSend: function() {
						$('#update-cart').attr('disabled', true);
						$('#update-cart').after('<span class="wait">&nbsp;<img src="components/com_eshop/assets/images/loading.gif" alt="" /></span>');
					},
					complete: function() {
						$('#update-cart').attr('disabled', false);
						$('.wait').remove();
					},
					success: function() {
						window.location.href = "<?php echo JRoute::_(EshopRoute::getViewRoute('cart')); ?>";
				  	}
				});
			})
		}

		Eshop.jQuery(function($) {
			//Ajax remove cart item
			$('.eshop-remove-item-cart').bind('click', function() {
				var aTag = $(this);
				var id = aTag.attr('id');
				var siteUrl = '<?php echo EshopHelper::getSiteUrl(); ?>';
				$.ajax({
					type :'POST',
					url: siteUrl + 'index.php?option=com_eshop&task=cart.remove&key=' +  id + '&redirect=1<?php echo EshopHelper::getAttachedLangLink(); ?>',
					beforeSend: function() {
						aTag.attr('disabled', true);
						aTag.after('<span class="wait">&nbsp;<img src="components/com_eshop/assets/images/loading.gif" alt="" /></span>');
					},
					complete: function() {
						aTag.attr('disabled', false);
						$('.wait').remove();
					},
					success : function() {
						window.location.href = '<?php echo JRoute::_(EshopRoute::getViewRoute('cart')); ?>';
					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});
			});
		});
		<?php
		if (EshopHelper::getConfigValue('allow_coupon'))
		{
			?>
			//Function to apply coupon
			function applyCoupon()
			{
				Eshop.jQuery(function($) {
					var siteUrl = '<?php echo EshopHelper::getSiteUrl(); ?>';
					$.ajax({
						type: 'POST',
						url: siteUrl + 'index.php?option=com_eshop&task=cart.applyCoupon<?php echo EshopHelper::getAttachedLangLink(); ?>',
						data: 'coupon_code=' + document.getElementById('coupon_code').value,
						beforeSend: function() {
							$('#apply-coupon').attr('disabled', true);
							$('#apply-coupon').after('<span class="wait">&nbsp;<img src="components/com_eshop/assets/images/loading.gif" alt="" /></span>');
						},
						complete: function() {
							$('#apply-coupon').attr('disabled', false);
							$('.wait').remove();
						},
						success: function() {
							window.location.href = "<?php echo JRoute::_(EshopRoute::getViewRoute('cart')); ?>";
					  	}
					});
				});
			}
			<?php
		}
		if (EshopHelper::getConfigValue('allow_voucher'))
		{
			?>
			//Function to apply voucher
			function applyVoucher()
			{
				Eshop.jQuery(function($) {
					var siteUrl = '<?php echo EshopHelper::getSiteUrl(); ?>';
					$.ajax({
						type: 'POST',
						url: siteUrl + 'index.php?option=com_eshop&task=cart.applyVoucher<?php echo EshopHelper::getAttachedLangLink(); ?>',
						data: 'voucher_code=' + document.getElementById('voucher_code').value,
						beforeSend: function() {
							$('#apply-voucher').attr('disabled', true);
							$('#apply-voucher').after('<span class="wait">&nbsp;<img src="components/com_eshop/assets/images/loading.gif" alt="" /></span>');
						},
						complete: function() {
							$('#apply-voucher').attr('disabled', false);
							$('.wait').remove();
						},
						success: function() {
							window.location.href = "<?php echo JRoute::_(EshopRoute::getViewRoute('cart')); ?>";
					  	}
					});
				});
			}
			<?php
		}
		if ($this->shipping_required)
		{
			?>
			Eshop.jQuery(function($){
				$('select[name=\'country_id\']').bind('change', function() {
					var siteUrl = '<?php echo EshopHelper::getSiteUrl(); ?>';
					$.ajax({
						url: siteUrl + 'index.php?option=com_eshop&task=cart.getZones<?php echo EshopHelper::getAttachedLangLink(); ?>&country_id=' + this.value,
						dataType: 'json',
						beforeSend: function() {
							$('.wait').remove();
							$('select[name=\'country_id\']').after('<span class="wait">&nbsp;<img src="components/com_eshop/assets/images/loading.gif" alt="" /></span>');
						},
						complete: function() {
							$('.wait').remove();
						},
						success: function(json) {
							if (json['postcode_required'] == '1')
							{
								$('#postcode-required').show();
							}
							else
							{
								$('#postcode-required').hide();
							}
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
						}
					});
				});
			});

			//Function to apply shipping
			function applyShipping()
			{
				Eshop.jQuery(function($){
					var shippingMethod = document.getElementsByName('shipping_method');
					var validated = false;
					var selectedShippingMethod = '';
					for (var i = 0, length = shippingMethod.length; i < length; i++)
					{
						if (shippingMethod[i].checked)
						{
							validated = true;
							selectedShippingMethod = shippingMethod[i].value;
							break;
					    }
					}
					if (!validated)
					{
						alert('<?php echo JText::_('ESHOP_ERROR_SHIPPING_METHOD'); ?>');
						return;
					}
					else
					{
						var siteUrl = '<?php echo EshopHelper::getSiteUrl(); ?>';
						$.ajax({
							type: 'POST',
							url: siteUrl + 'index.php?option=com_eshop&task=cart.applyShipping<?php echo EshopHelper::getAttachedLangLink(); ?>',
							data: 'shipping_method=' + selectedShippingMethod,
							beforeSend: function() {
								$('#apply-shipping').attr('disabled', true);
								$('#apply-shipping').after('<span class="wait">&nbsp;<img src="components/com_eshop/assets/images/loading.gif" alt="" /></span>');
							},
							complete: function() {
								$('#apply-shipping').attr('disabled', false);
								$('.wait').remove();
							},
							success: function() {
								window.location.href = "<?php echo JRoute::_(EshopRoute::getViewRoute('cart')); ?>";
						  	}
						});
					}
				});
			}

			//Function to get quotes
			Eshop.jQuery(function($){
				$('#get-quotes').bind('click', function() {
					var siteUrl = '<?php echo EshopHelper::getSiteUrl(); ?>';
					var dataString = 'country_id=' + $('select[name=\'country_id\']').val() + '&zone_id=' + $('select[name=\'zone_id\']').val() + '&postcode=' + encodeURIComponent($('input[name=\'postcode\']').val());
					$.ajax({
						type: 'POST',
						url: siteUrl + 'index.php?option=com_eshop&task=cart.getQuote<?php echo EshopHelper::getAttachedLangLink(); ?>',
						data: dataString,
						dataType: 'json',
						beforeSend: function() {
							$('#get-quotes').attr('disabled', true);
							$('#get-quotes').after('<span class="wait">&nbsp;<img src="components/com_eshop/assets/images/loading.gif" alt="" /></span>');
						},
						complete: function() {
							$('#get-quotes').attr('disabled', false);
							$('.wait').remove();
						},
						success: function(json) {
							$(' .error').remove();
							if (json['error'])
							{
								if (json['error']['warning'])
								{
									$.colorbox({
										overlayClose: true,
										opacity: 0.5,
										href: false,
										html: '<h1>' + json['error']['warning'] + '</h1>' + '<div class="no-shipping-method">' + '<?php echo JText::_('ESHOP_NO_SHIPPING_METHOD_AVAILABLE'); ?>' + '</div>'
									});
								}
								if (json['error']['country'])
								{
									$('select[name=\'country_id\']').after('<span class="error">' + json['error']['country'] + '</span>');
								}
								if (json['error']['zone'])
								{
									$('select[name=\'zone_id\']').after('<span class="error">' + json['error']['zone'] + '</span>');
								}
								if (json['error']['postcode'])
								{
									$('input[name=\'postcode\']').after('<span class="error">' + json['error']['postcode'] + '</span>');
								}
							}
							if (json['shipping_methods'])
							{
								//Prepare html for shipping methods list here
								html = '<div>';
								html += '<h1><?php echo JText::_('ESHOP_SHIPPING_METHOD_TITLE'); ?></h1>';
								html += '<form action="" method="post" enctype="multipart/form-data" name="shipping_form">';
								var firstShippingOption = true;
								for (i in json['shipping_methods'])
								{
									html += '<div>';
									html += '<strong>' + json['shipping_methods'][i]['title'] + '</strong><br />';
									if (!json['shipping_methods'][i]['error'])
									{
										for (j in json['shipping_methods'][i]['quote'])
										{
											var checkedStr = ' ';
											<?php
											if ($this->shipping_method != '')
											{
												?>
												if (json['shipping_methods'][i]['quote'][j]['name'] == '<?php echo $this->shipping_method; ?>')
												{
													checkedStr = " checked = 'checked' ";
												}
												<?php
											}
											else
											{
												?>
												if (firstShippingOption)
												{
													checkedStr = " checked = 'checked' ";
												}
												<?php
											}
											?>
											firstShippingOption = false;
											html += '<label class="radio">';
											html += '<input type="radio" value="' + json['shipping_methods'][i]['quote'][j]['name'] + '" name="shipping_method"' + checkedStr +'/>';
											html += json['shipping_methods'][i]['quote'][j]['title'];
											if (json['shipping_methods'][i]['quote'][j]['text']) {
												html += ' (';
    											html += json['shipping_methods'][i]['quote'][j]['text'];
    											html += ')';
											}
											html += '</label>';
										}
									}
									else
									{
										html += json['shipping_methods'][i]['error'];
									}
									html += '</div>';
								}
								html += '<input class="<?php echo $btnClass; ?> btn-primary" type="button" onclick="applyShipping();" id="apply-shipping" value="<?php echo JText::_('ESHOP_SHIPPING_APPLY'); ?>">';
								html += '</form>';
								html += '</div>';
								$.colorbox({
									overlayClose: true,
									opacity: 0.5,
									href: false,
									html: html
								});
							}
					  	}
					});
				});
			});
			<?php
		}
		?>
	</script>
	<?php
}
