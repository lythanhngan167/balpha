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
$controlsClass          = $bootstrapHelper->getClassMapping('controls');
$inputAppendClass       = $bootstrapHelper->getClassMapping('input-append');
$inputPrependClass      = $bootstrapHelper->getClassMapping('input-prepend');
$imgPolaroid            = $bootstrapHelper->getClassMapping('img-polaroid');
$btnClass				= $bootstrapHelper->getClassMapping('btn');
?>
<script src="<?php echo JUri::base(true); ?>/components/com_eshop/assets/colorbox/jquery.colorbox.js" type="text/javascript"></script>
<h1>
	<?php echo JText::_('ESHOP_SHOPPING_CART'); ?>
	<?php
	if ($this->weight)
	{
		echo '&nbsp;(' . $this->weight . ')';
	}
	?>
</h1>
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
					<th><?php echo JText::_('ESHOP_QUANTITY'); ?></th>
					<th nowrap="nowrap"><?php echo JText::_('ESHOP_UNIT_PRICE'); ?></th>
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
						<td style="vertical-align: middle;" data-content="<?php echo JText::_('ESHOP_QUANTITY'); ?>">
							<div class="<?php echo $inputAppendClass; ?> <?php echo $inputPrependClass; ?>">
								<span class="eshop-quantity">
									<input type="hidden" name="key[]" value="<?php echo $key; ?>" />
									<a onclick="quantityUpdate('+', <?php echo $countProducts; ?>)" class="<?php echo $btnClass; ?> btn-default button-plus" id="popout_<?php echo $countProducts; ?>">+</a>
										<input type="text" class="eshop-quantity-value" value="<?php echo htmlspecialchars($product['quantity'], ENT_COMPAT, 'UTF-8'); ?>" name="quantity[]" id="quantity_popout_<?php echo $countProducts; ?>" />
									<a onclick="quantityUpdate('-', <?php echo $countProducts; ?>)" class="<?php echo $btnClass; ?> btn-default button-minus" id="popout_<?php echo $countProducts; ?>">-</a>
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
				?>
			</tbody>
		</table>
		<?php
		if (EshopHelper::showPrice())
		{
			?>
			<div class="totals text-center" style="text-align: center">
			<?php
				foreach ($this->totalData as $data)
				{
					?>
						<div>
							<?php echo $data['title']; ?>:
							<?php echo $data['text']; ?>
						</div>
					<?php	
				} ?>
			</div>
			<?php
		}
	    ?>
    </div>
    <div class="bottom <?php echo $controlGroupClass; ?>" style="text-align: center;">
		<div class="<?php echo $controlsClass; ?>">
			<a class="<?php echo $btnClass; ?> btn-danger" href="<?php echo JRoute::_(EshopHelper::getContinueShopingUrl()); ?>"><?php echo JText::_('ESHOP_CONTINUE_SHOPPING'); ?></a>
			<button type="button" class="<?php echo $btnClass; ?> btn-info" onclick="updateCart();" id="update-cart"><?php echo JText::_('ESHOP_UPDATE_CART'); ?></button>
			<a class="<?php echo $btnClass; ?> btn-success" href="<?php echo JRoute::_(EshopRoute::getViewRoute('cart')); ?>"><?php echo JText::_('ESHOP_SHOPPING_CART'); ?></a>
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
			<a class="<?php echo $btnClass; ?> btn-primary" href="<?php echo $checkoutUrl; ?>"><?php echo JText::_('ESHOP_CHECKOUT'); ?></a>
		</div>
	</div>
	
	<script type="text/javascript">
		Eshop.jQuery(function($){
			$(document).ready(function(){
				quantityUpdate = (function(sign, productId){
					var oldQuantity = $('#quantity_popout_' + productId).val();
					if(sign == '+')
					{
						oldQuantity++;
						$('#quantity_popout_' + productId).val(oldQuantity);
					}
					else if (sign == '-')
					{
						oldQuantity--;
						if (oldQuantity > 0)
						{
							$('#quantity_popout_' + productId).val(oldQuantity);
						}
					}
				})
			})
		})
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
						$.ajax({
							url: siteUrl + 'index.php?option=com_eshop&view=cart&layout=popout&format=raw<?php echo EshopHelper::getAttachedLangLink(); ?>&pt=<?php echo time(); ?>',
							dataType: 'html',
							success: function(html) {
								$.colorbox({
									overlayClose: true,
									opacity: 0.5,
									href: false,
									html: html
								});
								$.ajax({
									url: siteUrl + 'index.php?option=com_eshop&view=cart&layout=mini&format=raw<?php echo EshopHelper::getAttachedLangLink(); ?>&pt=<?php echo time(); ?>',
									dataType: 'html',
									success: function(html) {
										$('#eshop-cart').html(html);
										$('.eshop-content').hide();
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
						$.ajax({
							url: siteUrl + 'index.php?option=com_eshop&view=cart&layout=popout&format=raw<?php echo EshopHelper::getAttachedLangLink(); ?>&pt=<?php echo time(); ?>',
							dataType: 'html',
							success: function(html) {
								$.colorbox({
									overlayClose: true,
									opacity: 0.5,
									href: false,
									html: html
								});
								$.ajax({
									url: siteUrl + 'index.php?option=com_eshop&view=cart&layout=mini&format=raw<?php echo EshopHelper::getAttachedLangLink(); ?>&pt=<?php echo time(); ?>',
									dataType: 'html',
									success: function(html) {
										$('#eshop-cart').html(html);
										$('.eshop-content').hide();
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
			});
		});
	</script>
	<?php
}