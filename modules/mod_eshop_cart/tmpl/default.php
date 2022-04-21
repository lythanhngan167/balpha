<?php
/**
 * @version		1.3.1
 * @package		Joomla
 * @subpackage	EShop
 * @author  	Giang Dinh Truong
 * @copyright	Copyright (C) 2012 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined( '_JEXEC' ) or die();

if (!EshopHelper::getConfigValue('catalog_mode'))
{
	?>
	<a href="index.php?Itemid=<?php echo PAGE_CART; ?>">
	<div id="eshop-cart" class="eshop-cart<?php echo $params->get( 'moduleclass_sfx' ); ?> ">
		<div class="eshop-items">
			<h4>

					<?php echo JText::_('ESHOP_SHOPPING_CART')?> <?php if ($countProducts > 0) {
						echo "(".$countProducts.")";
					}else {
						echo "";
					} ?></h4>

			<a style="display:none;">
				<span id="eshop-cart-total" >
					<?php
					//echo $countProducts; ?>
					<?php echo JText::_('ESHOP_ITEMS');
					if (EshopHelper::showPrice())
					{
						?>
						&nbsp;-&nbsp;<?php echo $totalPrice; ?>
						<?php
					}
					?>
				</span>
			</a>
		</div>
		<div class="eshop-content" style="display: none;">
		<?php
			if ($countProducts == 0)
			{
				echo JText::_('ESHOP_CART_EMPTY');
			}
			else
			{
			?>
			<div class="eshop-mini-cart-info">
				<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td colspan="5" style="border: 0px;"><span class="wait"></span></td>
					</tr>
					<?php
					foreach ($items as $key => $product)
					{
						$optionData = $product['option_data'];
						$viewProductUrl = JRoute::_(EshopRoute::getProductRoute($product['product_id'], EshopHelper::getProductCategory($product['product_id'])));
						?>
						<tr>
							<td class="eshop-image">
								<a href="<?php echo $viewProductUrl; ?>">
									<img src="<?php echo $product['image']; ?>" />
								</a>
							</td>
							<td class="eshop-name">
								<a href="<?php echo $viewProductUrl; ?>">
									<?php echo $product['product_name']; ?>
								</a>
								<div>
								<?php
								for ($i = 0; $n = count($optionData), $i < $n; $i++)
								{
									echo '<small>- ' . $optionData[$i]['option_name'] . ': ' . $optionData[$i]['option_value'] . (isset($optionData[$i]['sku']) && $optionData[$i]['sku'] != '' ? ' (' . $optionData[$i]['sku'] . ')' : '') . '</small><br />';
								}
								?>
								</div>
							</td>
							<td class="eshop-quantity">
								x&nbsp;<?php echo $product['quantity']; ?>
							</td>
							<?php
							if (EshopHelper::showPrice())
							{
								?>
								<td class="eshop-total">
									<?php echo $currency->format($product['total_price']); ?>
								</td>
								<?php
							}
							?>
							<td class="eshop-remove">
								<a class="eshop-remove-item" href="#" id="<?php echo $key; ?>">
									<img alt="<?php echo JText::_('ESHOP_REMOVE'); ?>" title="<?php echo JText::_('ESHOP_REMOVE'); ?>" src="<?php echo JURI::base(); ?>components/com_eshop/assets/images/remove.png" />
								</a>
							</td>
						</tr>
					<?php
					}
					?>
				</table>
			</div>
			<?php
			if (EshopHelper::showPrice())
			{
				?>
				<div class="mini-cart-total">
					<table cellpadding="0" cellspacing="0" width="100%">
						<?php
						foreach ($totalData as $data)
						{
							?>
							<tr>
								<td class="eshop-right"><strong><?php echo $data['title']; ?>:&nbsp;</strong></td>
								<td class="eshop-right"><?php echo $data['text']; ?></td>
							</tr>
							<?php
						}
						?>
					</table>
				</div>
				<?php
			}
			?>
			<div class="checkout">
				<a href="<?php echo JRoute::_(EshopRoute::getViewRoute('cart')); ?>"><?php echo JText::_('ESHOP_VIEW_CART'); ?></a>
				&nbsp;|&nbsp;
				<a href="<?php echo JRoute::_(EshopRoute::getViewRoute('checkout')); ?>"><?php echo JText::_('ESHOP_CHECKOUT'); ?></a>
			</div>
			<?php
			}
			?>
		</div>
	</div>
	</a>
	<script type="text/javascript">
		(function($) {
			$(document).ready(function() {
				$('.eshop-items a').click(function() {
					//$('.eshop-content').slideToggle('fast');
					$('.eshop-content').hide();
				});
				$('.eshop-content').mouseleave(function() {
					$('.eshop-content').hide();
				});
				//Ajax remove cart item
				$('.eshop-remove-item').bind('click', function() {
					var id = $(this).attr('id');
					$.ajax({
						type :'POST',
						url  : '<?php echo EshopHelper::getSiteUrl(); ?>index.php?option=com_eshop&task=cart.remove&key=' +  id + '&redirect=<?php echo ($view == 'cart' || $view == 'checkout') ? '1' : '0'; ?>',
						beforeSend: function() {
							$('.wait').html('<img src="components/com_eshop/assets/images/loading.gif" alt="" />');
						},
						success : function() {
							<?php
							if ($view == 'cart' || $view == 'checkout')
							{
								?>
								window.location.href = '<?php echo JRoute::_(EshopRoute::getViewRoute('cart')); ?>';
								<?php
							}
							else
							{
								?>
								$.ajax({
									url: '<?php echo EshopHelper::getSiteUrl(); ?>index.php?option=com_eshop&view=cart&layout=mini&format=raw',
									dataType: 'html',
									success: function(html) {
										$('#eshop-cart').html(html);
										$('.eshop-content').show();
									},
									error: function(xhr, ajaxOptions, thrownError) {
										alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
									}
								});
								<?php
							}
							?>
						},
						error: function(xhr, ajaxOptions, thrownError) {
							alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
						}
					});
				});
			});
		})(jQuery)
	</script>
	<?php
}
