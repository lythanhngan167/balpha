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

if (EshopHelper::getConfigValue('quote_cart_mode'))
{
	?>
	<div id="eshop-quote" class="eshop-quote<?php echo $params->get( 'moduleclass_sfx' ); ?>">
		<div class="eshop-items">
			<h4><?php echo JText::_('ESHOP_QUOTE_CART')?></h4>
			<a>
				<span id="eshop-quote-total">
					<?php echo $countProducts; ?>&nbsp;<?php echo JText::_('ESHOP_ITEMS'); ?>
				</span>
			</a>
		</div>
		<div class="eshop-content">
		<?php
			if ($countProducts == 0)
			{
				echo JText::_('ESHOP_QUOTE_EMPTY');
			}
			else
			{
			?>
			<div class="eshop-mini-quote-info">
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
									echo '<small>- ' . $optionData[$i]['option_name'] . ': ' . $optionData[$i]['option_value'] . '</small><br />';
								}
								?>
								</div>
							</td>
							<td class="eshop-quantity">
								x&nbsp;<?php echo $product['quantity']; ?>
							</td>
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
			<div class="checkout">
				<a href="<?php echo JRoute::_(EshopRoute::getViewRoute('quote')); ?>"><?php echo JText::_('ESHOP_VIEW_QUOTE'); ?></a>
			</div>
			<?php
			}
			?>
		</div>
	</div>
	<script type="text/javascript">
		Eshop.jQuery(function($) {
			$(document).ready(function() {
				$('.eshop-content').hide();
				$('.eshop-items a').click(function() {
					$('.eshop-content').slideToggle('fast');
				});
				$('.eshop-content').mouseleave(function() {
					$('.eshop-content').hide();
				});
				//Ajax remove quote item
				$('.eshop-remove-item').bind('click', function() {
					var id = $(this).attr('id');
					$.ajax({
						type :'POST',
						url  : 'index.php?option=com_eshop&task=quote.remove&key=' +  id + '&redirect=<?php echo ($view == 'quote') ? '1' : '0'; ?>',
						beforeSend: function() {
							$('.wait').html('<img src="components/com_eshop/assets/images/loading.gif" alt="" />');
						},
						success : function() {
							<?php
							if ($view == 'quote')
							{
								?>
								window.location.href = '<?php echo JRoute::_(EshopRoute::getViewRoute('quote')); ?>';
								<?php
							}
							else 
							{
								?>
								$.ajax({
									url: 'index.php?option=com_eshop&view=quote&layout=mini&format=raw',
									dataType: 'html',
									success: function(html) {
										$('#eshop-quote').html(html);
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
		})
	</script>
	<?php
}