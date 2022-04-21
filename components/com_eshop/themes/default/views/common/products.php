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
$span                   = intval(12 / $productsPerRow);
$rowFluidClass          = $bootstrapHelper->getClassMapping('row-fluid');
$spanClass              = $bootstrapHelper->getClassMapping('span' . $span);
$span3Class             = $bootstrapHelper->getClassMapping('span3');
$span9Class             = $bootstrapHelper->getClassMapping('span9');
$hiddenPhoneClass       = $bootstrapHelper->getClassMapping('hidden-phone');
$inputAppendClass       = $bootstrapHelper->getClassMapping('input-append');
$inputPrependClass      = $bootstrapHelper->getClassMapping('input-prepend');
$imgPolaroid            = $bootstrapHelper->getClassMapping('img-polaroid');
$btnClass				= $bootstrapHelper->getClassMapping('btn');
?>
<script src="<?php echo JUri::base(true); ?>/components/com_eshop/assets/colorbox/jquery.colorbox.js" type="text/javascript"></script>
<script src="<?php echo JUri::base(true); ?>/components/com_eshop/assets/js/jquery.cookie.js" type="text/javascript"></script>
<script src="<?php echo JUri::base(true); ?>/components/com_eshop/assets/js/layout.js" type="text/javascript"></script>
<script>
	Eshop.jQuery(function($){
		$(document).ready(function() {
			changeLayout('<?php echo EshopHelper::getConfigValue('default_products_layout', 'list'); ?>');
		});
	});
</script>
<div id="products-list-container" class="products-list-container block list">
	<div class="sortPagiBar <?php echo $rowFluidClass; ?> clearfix">
		<div class="<?php echo $span3Class; ?>">
			<div class="btn-group <?php echo $hiddenPhoneClass; ?>">
				<?php
				if (EshopHelper::getConfigValue('default_products_layout') == 'grid')
				{
					?>
					<a rel="grid" href="#" class="<?php echo $btnClass; ?>"><i class="icon-th-large"></i></a>
					<a rel="list" href="#" class="<?php echo $btnClass; ?>"><i class="icon-th-list"></i></a>
					<?php
				}
				else
				{
					?>
					<a rel="list" href="#" class="<?php echo $btnClass; ?>"><i class="icon-th-list"></i></a>
					<a rel="grid" href="#" class="<?php echo $btnClass; ?>"><i class="icon-th-large"></i></a>
					<?php
				}
				?>
			</div>
		</div>
		<?php
		if ($showSortOptions)
		{
			?>
			<div class="<?php echo $span9Class; ?>">
				<form method="post" name="adminForm" id="adminForm" action="<?php echo $actionUrl; ?>">
					<div class="clearfix">
						<div class="eshop-product-show">
							<b><?php echo JText::_('ESHOP_SHOW'); ?>: </b>
							<?php echo $pagination->getLimitBox(); ?>
						</div>
						<?php
						if ($sort_options)
						{
							?>
							<div class="eshop-product-sorting">
								<b><?php echo JText::_('ESHOP_SORTING_BY'); ?>: </b>
								<?php echo $sort_options; ?>
							</div>
							<?php
						}
	                    ?>
					</div>
				</form>
			</div>
		<?php
		}
		?>
	</div>
	<div id="products-list" class="clearfix">
		<div class="<?php echo $rowFluidClass; ?> clearfix">
			<?php
				$count = 0;
				foreach ($products as $product)
				{
					$productUrl = JRoute::_(EshopRoute::getProductRoute($product->id, ($catId && EshopHelper::isProductCategory($product->id, $catId)) ? $catId : EshopHelper::getProductCategory($product->id)));
					?>
					<div class="<?php echo $spanClass; ?> ajax-block-product spanbox clearfix">
						<div class="eshop-image-block">
							<div class="image <?php echo $imgPolaroid; ?>">
								<a href="<?php echo $productUrl; ?>" title="<?php echo $product->product_name; ?>" class="product-image">
									<?php
									if (count($product->labels))
									{
										for ($i = 0; $n = count($product->labels), $i < $n; $i++)
										{
											$label = $product->labels[$i];
											if ($label->label_style == 'rotated' && !($label->enable_image && $label->label_image))
											{
												?>
												<div class="cut-rotated">
												<?php
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
												?>
												</div>
												<?php
											}
										}
									}
									?>
									<img src="<?php echo $product->image; ?>" title="<?php echo $product->product_page_title != '' ? $product->product_page_title : $product->product_name; ?>" alt="<?php echo $product->product_alt_image != '' ? $product->product_alt_image : $product->product_name; ?>" />
								</a>
							</div>
						</div>
						<div class="eshop-info-block">
							<h5><a href="<?php echo $productUrl; ?>" title="<?php echo $product->product_name; ?>"><?php echo $product->product_name;?></a></h5>
							<p class="eshop-product-desc"><?php echo $product->product_short_desc;?></p>
							<div class="eshop-product-price">
								<?php
								if (EshopHelper::showPrice() && !$product->product_call_for_price)
								{
									?>
									<p>
										<?php
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
										?>
									</p>
									<?php
								}
								if ($product->product_call_for_price)
								{
									?>
									<p><?php echo JText::_('ESHOP_CALL_FOR_PRICE'); ?>: <?php echo EshopHelper::getConfigValue('telephone'); ?></p>
									<?php
								}
								?>
							</div>
						</div>
						<div class="eshop-buttons">
							<?php
							if (!EshopHelper::isRequiredOptionProduct($product->id))
							{
								if (EshopHelper::isCartMode($product) || EshopHelper::isQuoteMode($product))
								{
									?>
									<div class="eshop-cart-area">
										<?php
										if (EshopHelper::getConfigValue('show_quantity_box'))
										{
											?>
											<div class="<?php echo $inputAppendClass; ?> <?php echo $inputPrependClass; ?>">
												<span class="eshop-quantity">
													<a class="<?php echo $btnClass; ?> btn-default button-minus" id="<?php echo $product->id; ?>" data="down">-</a>
													<input type="text" class="eshop-quantity-value" id="quantity_<?php echo $product->id; ?>" name="quantity[]" value="<?php echo EshopHelper::getConfigValue('start_quantity_number', '1'); ?>" />
													<input type="hidden" id="quantity_step_<?php echo $product->id; ?>" name="quantity_step_<?php echo $product->id; ?>" value="<?php echo EshopHelper::getConfigValue('quantity_step', '1'); ?>" />
													<?php
													if (EshopHelper::getConfigValue('one_add_to_cart_button', '0'))
													{
														?>
														<input type="hidden" name="product_id[]" value="<?php echo $product->id; ?>" />
														<?php
													}
													?>
													<a class="<?php echo $btnClass; ?> btn-default button-plus" id="<?php echo $product->id; ?>" data="up">+</a>
												</span>
											</div>
											<?php
										}
										if (EshopHelper::isCartMode($product) && !EshopHelper::getConfigValue('one_add_to_cart_button', '0'))
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
									</div>
									<?php
								}
							}
							else
							{
								?>
								<div class="eshop-cart-area">
									<a class="<?php echo $btnClass; ?> btn-primary" href="<?php echo $productUrl; ?>" title="<?php echo $product->product_name; ?>"><?php echo JText::_('ESHOP_PRODUCT_VIEW_DETAILS'); ?></a>
								</div>
								<?php
							}

							if (($product->product_quantity <= 0 && EshopHelper::getConfigValue('allow_notify') && !EshopHelper::getConfigValue('stock_checkout')) || EshopHelper::getConfigValue('allow_wishlist') || EshopHelper::getConfigValue('allow_compare'))
							{
								?>
								<p>
									<?php
									if ($product->product_quantity <= 0 && EshopHelper::getConfigValue('allow_notify')  && !EshopHelper::getConfigValue('stock_checkout'))
									{
										?>
										<a class="<?php echo $btnClass; ?> button" onclick="makeNotify(<?php echo $product->id; ?>, '<?php echo EshopHelper::getSiteUrl();?>', '<?php echo EshopHelper::getAttachedLangLink(); ?>')" ><?php echo JText::_('ESHOP_PRODUCT_NOTIFY');?></a>
										<?php
									}
									if (EshopHelper::getConfigValue('allow_wishlist'))
									{
										?>
										<a class="<?php echo $btnClass; ?> button" style="cursor: pointer;" onclick="addToWishList(<?php echo $product->id; ?>, '<?php echo EshopHelper::getSiteUrl(); ?>', '<?php echo EshopHelper::getAttachedLangLink(); ?>')" title="<?php echo JText::_('ESHOP_ADD_TO_WISH_LIST'); ?>"><?php echo JText::_('ESHOP_ADD_TO_WISH_LIST'); ?></a>
										<?php
									}
									if (EshopHelper::getConfigValue('allow_compare'))
									{
										?>
										<a class="<?php echo $btnClass; ?> button" style="cursor: pointer;" onclick="addToCompare(<?php echo $product->id; ?>, '<?php echo EshopHelper::getSiteUrl(); ?>', '<?php echo EshopHelper::getAttachedLangLink(); ?>')" title="<?php echo JText::_('ESHOP_ADD_TO_COMPARE'); ?>"><?php echo JText::_('ESHOP_ADD_TO_COMPARE'); ?></a>
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
				$count++;
				if ($count % $productsPerRow == 0 && $count < count($products))
				{
					?>
					</div><div class="<?php echo $rowFluidClass; ?> clearfix">
					<?php
				}
			}
			?>
		</div>
		<?php
		if (EshopHelper::getConfigValue('show_quantity_box') && EshopHelper::getConfigValue('one_add_to_cart_button', '0'))
		{
			?>
			<div class="<?php echo $rowFluidClass; ?>">
				<input id="multiple-products-add-to-cart" type="button" class="<?php echo $btnClass; ?> btn-primary" onclick="multipleProductsAddToCart('<?php echo EshopHelper::getSiteUrl(); ?>', '<?php echo EshopHelper::getAttachedLangLink(); ?>', '<?php echo EshopHelper::getConfigValue('cart_popout')?>', '<?php echo JRoute::_(EshopRoute::getViewRoute('cart')); ?>');" value="<?php echo JText::_('ESHOP_MULTIPLE_PRODUCTS_ADD_TO_CART'); ?>" />
			</div>
			<?php
		}

		if (isset($pagination) && ($pagination->total > $pagination->limit))
		{
			?>
			<div class="<?php echo $rowFluidClass; ?>">
				<div class="pagination">
					<?php echo $pagination->getPagesLinks(); ?>
				</div>
			</div>
			<?php
		}
		?>
	</div>
</div>
