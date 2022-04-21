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
<h1><?php echo JText::_('ESHOP_MY_WISHLIST'); ?></h1><br />
<?php
if (!count($this->products))
{
	?>
	<div class="no-content"><?php echo JText::_('ESHOP_WISHLIST_EMPTY'); ?></div>
	<?php
}
else
{
	?>
	<table class="table table-responsive table-bordered table-striped">
		<thead>
			<tr>
				<th><?php echo JText::_('ESHOP_IMAGE'); ?></th>
				<th><?php echo JText::_('ESHOP_PRODUCT_NAME'); ?></th>
				<th><?php echo JText::_('ESHOP_MODEL'); ?></th>
				<!-- <th><?php echo JText::_('ESHOP_AVAILABILITY'); ?></th> -->
				<th><?php echo JText::_('ESHOP_UNIT_PRICE'); ?></th>
				<th><?php echo JText::_('ESHOP_ACTION'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach ($this->products as $product)
			{
				$viewProductUrl = JRoute::_(EshopRoute::getProductRoute($product->id, EshopHelper::getProductCategory($product->id)));
				?>
				<tr>
					<td class="muted eshop-center-text" data-content="<?php echo JText::_('ESHOP_IMAGE'); ?>">
						<a href="<?php echo $viewProductUrl; ?>">
							<img class="<?php echo $imgPolaroid; ?>" src="<?php echo $product->image; ?>" />
						</a>
					</td>
					<td data-content="<?php echo JText::_('ESHOP_PRODUCT_NAME'); ?>">
						<a href="<?php echo $viewProductUrl; ?>">
							<?php echo $product->product_name; ?>
						</a>
					</td>
					<td data-content="<?php echo JText::_('ESHOP_MODEL'); ?>"><?php echo $product->product_sku; ?></td>
					<!-- <td data-content="<?php echo JText::_('ESHOP_AVAILABILITY'); ?>">
						<?php echo $product->availability; ?>
					</td> -->
					<td data-content="<?php echo JText::_('ESHOP_UNIT_PRICE'); ?>">
						<?php
						if (EshopHelper::showPrice())
						{
							if (!$product->product_call_for_price)
							{
								if ($product->sale_price)
								{
									?>
									<span class="eshop-base-price"><?php echo $product->base_price; ?></span>&nbsp;
									<span class="eshop-sale-price"><?php echo $product->sale_price; ?></span>
									<?php
								}
								else
								{
									?>
									<span class="price"><?php echo $product->base_price; ?><span class="weight"> /  <?php  echo EshopHelper::getWeightUnitName($product->product_weight_id, JFactory::getLanguage()->getTag()) ; ?></span></span>
									<?php
								}
							}
							else
							{
								?>
								<span class="call-for-price"><?php echo JText::_('ESHOP_CALL_FOR_PRICE'); ?>: <?php echo EshopHelper::getConfigValue('telephone'); ?></span>
								<?php
							}
						}
						?>
					</td>
					<td data-content="<?php echo JText::_('ESHOP_ACTION'); ?>">
						<?php
						if (EshopHelper::isCartMode($product))
						{
							?>
							<input id="add-to-cart-<?php echo $product->id; ?>" type="button" class="<?php echo $btnClass; ?> btn-primary" onclick="addToCart(<?php echo $product->id; ?>, 1, '<?php echo EshopHelper::getSiteUrl(); ?>', '<?php echo EshopHelper::getAttachedLangLink(); ?>', '<?php echo EshopHelper::getConfigValue('cart_popout')?>', '<?php echo JRoute::_(EshopRoute::getViewRoute('cart')); ?>');" value="<?php echo JText::_('ESHOP_ADD_TO_CART'); ?>" />
							<?php
						}
						if (EshopHelper::isQuoteMode($product))
						{
							?>
							<input id="add-to-quote-<?php echo $product->id; ?>" type="button" class="<?php echo $btnClass; ?> btn-primary" onclick="addToQuote(<?php echo $product->id; ?>, 1, '<?php echo EshopHelper::getSiteUrl(); ?>', '<?php echo EshopHelper::getAttachedLangLink(); ?>');" value="<?php echo JText::_('ESHOP_ADD_TO_QUOTE'); ?>" />
							<?php
						}
						?>
						<input type="button" class="<?php echo $btnClass; ?> btn-primary" onclick="removeFromWishlist(<?php echo $product->id; ?>, '<?php echo EshopHelper::getSiteUrl(); ?>', '<?php echo EshopHelper::getAttachedLangLink(); ?>');" value="<?php echo JText::_('ESHOP_REMOVE'); ?>" />
					</td>
				</tr>
				<?php
			}
			?>
		</tbody>
	</table>
	<?php
}
