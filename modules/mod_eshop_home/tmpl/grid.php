<?php
/**
 * @version		1.3.1
 * @package		Joomla
 * @subpackage	EShop
 * @author  	Giang Dinh Truong
 * @copyright	Copyright (C) 2011 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die;
?>
<div class="eshop-product<?php echo $params->get( 'moduleclass_sfx' ); ?> list">
	<?php
	if($headerText)
	{
		?><div class="eshopheader"><?php echo $headerText; ?></div>
	<?php
	}
	?>
	<div id="products-list" class="row-fluid clearfix">
		<?php
		foreach ($items as $key => $product)
		{
			$tooltip = $product->product_short_desc;
			if ($tooltipLength > 0 && $tooltipLength < strlen($tooltip))
			{
				$tooltip = substr($tooltip, 0, $tooltipLength) . '...';
			}
			$viewProductUrl = JRoute::_(EshopRoute::getProductRoute($product->id, EshopHelper::getProductCategory($product->id)));
			// Image
			$imageSizeFunction = $params->get('image_resize_function', 'resizeImage');
			if ($product->product_image && JFile::exists(JPATH_ROOT.'/media/com_eshop/products/' . $product->product_image))
			{
				$image = call_user_func_array(array('EshopHelper', $imageSizeFunction), array($product->product_image, JPATH_ROOT . '/media/com_eshop/products/', $thumbnailWidth, $thumbnailHeight));
			}
			else
			{
				$image = call_user_func_array(array('EshopHelper', $imageSizeFunction), array('no-image.png', JPATH_ROOT . '/media/com_eshop/products/', $thumbnailWidth, $thumbnailHeight));
			}
			$image = JURI::base() . 'media/com_eshop/products/resized/' . $image;
			$labels = EshopHelper::getProductLabels($product->id);
	 		?>
	 		<div class="span<?php echo (int) 12/$productsPerRow; ?>">
				<div class="eshop-image-block image">
					<div class="image img-polaroid">
						<a href="<?php echo $viewProductUrl; ?>">
							<?php
							if (count($labels) && $params->get('enable_labels'))
							{
								for ($i = 0; $n = count($labels), $i < $n; $i++)
								{
									$label = $labels[$i];
									if ($label->label_style == 'rotated' && !($label->enable_image && $label->label_image))
									{
										?><div class="cut_rotated"><?php
									}
									if ($label->enable_image && $label->label_image)
									{
										$imageWidth = $label->label_image_width > 0 ? $label->label_image_width : EshopHelper::getConfigValue('label_image_width');
										if (!$imageWidth)
											$imageWidth = 50;
										$imageHeight = $label->label_image_height > 0 ? $label->label_image_height : EshopHelper::getConfigValue('label_image_height');
										if (!$imageHeight)
											$imageHeight = 50;
										?>
										<span class="horizontal <?php echo $label->label_position; ?> small-db" style="opacity: <?php echo $label->label_opacity; ?>;<?php echo 'background-image: url(' . $label->label_image . ')'; ?>; background-repeat: no-repeat; width: <?php echo $imageWidth; ?>px; height: <?php echo $imageHeight; ?>px; box-shadow: none;"></span>
										<?php
									}
									else 
									{
										?>
										<span class="<?php echo $label->label_style; ?> <?php echo $label->label_position; ?> small-db" style="background-color: <?php echo '#'.$label->label_background_color; ?>; color: <?php echo '#'.$label->label_foreground_color; ?>; opacity: <?php echo $label->label_opacity; ?>;<?php if ($label->label_bold) echo 'font-weight: bold;'; ?>">
											<?php echo $label->label_name; ?>
										</span>
										<?php
									}
									if ($label->label_style == 'rotated' && !($label->enable_image && $label->label_image))
									{
										?></div><?php
									}
								}
							}
							?>
							<img alt="<?php echo $product->product_name; ?>" src="<?php echo $image; ?>">
						</a>
					</div>
				</div>
				<div class="product-infor-block eshop-info-block">
					<a href="<?php echo $viewProductUrl; ?>" title="<?php echo $product->product_name; ?>">
						<?php echo $product->product_name; ?>
					</a>
					<div class="clear"></div>
					<?php
					if ($showPrice == 1 && EshopHelper::showPrice() && !$product->product_call_for_price)
					{
						$productPriceArray = EshopHelper::getProductPriceArray($product->id, $product->product_price); 
						if ($productPriceArray['salePrice'] >= 0)
						{
							?>
							<span class="eshop-base-price"><?php echo $currency->format($tax->calculate($productPriceArray['basePrice'], $product->product_taxclass_id, EshopHelper::getConfigValue('tax'))); ?></span>&nbsp;
							<span class="eshop-sale-price"><?php echo $currency->format($tax->calculate($productPriceArray['salePrice'], $product->product_taxclass_id, EshopHelper::getConfigValue('tax'))); ?></span>
							<?php
						}
						else
						{
							?>
							<span class="price"><?php echo $currency->format($tax->calculate($productPriceArray['basePrice'], $product->product_taxclass_id, EshopHelper::getConfigValue('tax'))); ?></span>
							<?php
						}
						if (EshopHelper::getConfigValue('tax') && EshopHelper::getConfigValue('display_ex_tax'))
						{
							?>
							<small>
								<?php echo JText::_('ESHOP_EX_TAX'); ?>:
								<?php
								if ($productPriceArray['salePrice'] >= 0)
								{
									echo $currency->format($productPriceArray['salePrice']);
								}
								else
								{
									echo $currency->format($productPriceArray['basePrice']);
								}
								?>
							</small>
						<?php
						}
					}
					if ($product->product_call_for_price)
					{
						?>
						<span class="call-for-price"><?php echo JText::_('ESHOP_CALL_FOR_PRICE'); ?>: <?php echo EshopHelper::getConfigValue('telephone'); ?></span>
						<?php
					}
					if ($showRating)
					{
						?>
						<div class="product-review">
							<p>
								<img src="components/com_eshop/assets/images/stars-<?php echo round(EshopHelper::getProductRating($product->id)); ?>.png" />
							</p>
						</div>
						<?php
					}
					if ($showTooltip)
					{
						?><p class="eshop-product-desc"><?php echo $tooltip; ?></p><?php
					}
					?>
				</div>
				<div class="eshop-buttons">
					<?php 
					if ($showAddcart == 1 && EshopHelper::isCartMode($product))
					{
						?>
						<div class="eshop-cart-area">
							<input id="add-to-cart-<?php echo $product->id; ?>" type="button" class="btn btn-primary" onclick="addToCart(<?php echo $product->id; ?>, 1, '<?php echo EshopHelper::getSiteUrl(); ?>', '<?php echo EshopHelper::getAttachedLangLink(); ?>', '<?php echo EshopHelper::getConfigValue('cart_popout')?>', '<?php echo JRoute::_(EshopRoute::getViewRoute('cart')); ?>');" value="<?php echo JText::_('ESHOP_ADD_TO_CART'); ?>" />
						</div>
						<?php
					}
					if ((EshopHelper::getConfigValue('allow_wishlist') && $showAddToWishlist) || (EshopHelper::getConfigValue('allow_compare') && $showAddToCompare))
					{
						?>
						<p>
							<?php
							if (EshopHelper::getConfigValue('allow_wishlist') && $showAddToWishlist)
							{
								?>
								<a class="btn button" style="cursor: pointer;" onclick="addToWishList(<?php echo $product->id; ?>, '<?php echo EshopHelper::getSiteUrl(); ?>')" title="<?php echo JText::_('ESHOP_ADD_TO_WISH_LIST'); ?>"><?php echo JText::_('ESHOP_ADD_TO_WISH_LIST'); ?></a>
								<?php
							}
							if (EshopHelper::getConfigValue('allow_compare') && $showAddToCompare)
							{
								?>
								<a class="btn button" style="cursor: pointer;" onclick="addToCompare(<?php echo $product->id; ?>, '<?php echo EshopHelper::getSiteUrl(); ?>')" title="<?php echo JText::_('ESHOP_ADD_TO_COMPARE'); ?>"><?php echo JText::_('ESHOP_ADD_TO_COMPARE'); ?></a>
								<?php
							}
							?>
						</p>
						<?php
					}
					?>
				</div>
			</div>
			<?php
			if (($key + 1) % $productsPerRow == 0 && $key < (count($items) - 1)) echo '</div><div id="products-list" class="row-fluid clearfix">';
		}
	?>
	</div>
	<?php
	if($footerText)
	{
		?>
		<div class="eshopfooter"><?php echo $footerText; ?></div>
		<?php
	}
	?>
</div>
<script type="text/javascript">
	Eshop.jQuery(document).ready(function($){
		<?php
		if(!$showAddcart && !EshopHelper::getConfigValue('catalog_mode') && !$product->product_call_for_price)
		{
			?>
			$(".product-infor-block").css("width","60%"); 
			<?php
		}
		?>
	})
</script>