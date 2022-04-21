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

$rowFluidClass          = $bootstrapHelper->getClassMapping('row-fluid');
$inputAppendClass       = $bootstrapHelper->getClassMapping('input-append');
$inputPrependClass      = $bootstrapHelper->getClassMapping('input-prepend');
$imgPolaroid            = $bootstrapHelper->getClassMapping('img-polaroid');
$btnClass				= $bootstrapHelper->getClassMapping('btn');

$elementsArr = array('image', 'short_description', 'category', 'manufacturer', 'price', 'availability', 'quantity_box', 'actions');
$countElements = 0;
foreach ($elementsArr as $element)
{
	if (EshopHelper::getConfigValue('table_show_' . $element, 1))
	{
		$countElements++;
	}
}
if ($countElements > 0)
{
	$columnWidth = intval(80 / $countElements);
	$productWidth = 100 - $columnWidth * $countElements;
}
else 
{
	$columnWidth = 0;
	$productWidth = 100;
}
?>
<script src="<?php echo JUri::base(true); ?>/components/com_eshop/assets/colorbox/jquery.colorbox.js" type="text/javascript"></script>
<table class="table table-striped table-responsive" id="eshop-list">
	<thead>
		<tr>
			<th width="<?php echo $productWidth; ?>%" class="nowrap">
				<?php echo JText::_('ESHOP_HEADER_PRODUCT'); ?>
			</th>
			<?php
			if (EshopHelper::getConfigValue('table_show_image', 1))
			{
				?>
				<th width="<?php echo $columnWidth; ?>%" class="nowrap">
					<?php echo JText::_('ESHOP_HEADER_IMAGE'); ?>
				</th>
				<?php
			}
			if (EshopHelper::getConfigValue('table_show_short_description', 1))
			{
				?>
				<th width="<?php echo $columnWidth; ?>%" class="nowrap">
					<?php echo JText::_('ESHOP_HEADER_SHORT_DESCRIPTION'); ?>
				</th>
				<?php
			}
			if (EshopHelper::getConfigValue('table_show_category', 1))
			{
				?>
				<th width="<?php echo $columnWidth; ?>%" class="nowrap">
					<?php echo JText::_('ESHOP_HEADER_CATEGORY'); ?>
				</th>
				<?php
			}
			if (EshopHelper::getConfigValue('table_show_manufacturer', 1))
			{
				?>
				<th width="<?php echo $columnWidth; ?>%" class="nowrap">
					<?php echo JText::_('ESHOP_HEADER_MANUFACTURER'); ?>
				</th>
				<?php
			}
			if (EshopHelper::getConfigValue('table_show_price', 1))
			{
				?>
				<th width="<?php echo $columnWidth; ?>%" class="nowrap">
					<?php echo JText::_('ESHOP_HEADER_PRICE'); ?>
				</th>
				<?php
			}
			if (EshopHelper::getConfigValue('table_show_availability', 1))
			{
				?>
				<th width="<?php echo $columnWidth; ?>%" class="nowrap">
					<?php echo JText::_('ESHOP_HEADER_AVAILABILITY'); ?>
				</th>
				<?php
			}
			if (EshopHelper::getConfigValue('table_show_quantity_box', 1))
			{
				?>
				<th width="<?php echo $columnWidth; ?>%" class="nowrap">
					<?php echo JText::_('ESHOP_HEADER_QUANTITY'); ?>
				</th>
				<?php
			}
			if (EshopHelper::getConfigValue('table_show_actions', 1))
			{
				?>
				<th width="<?php echo $columnWidth; ?>%" class="nowrap"></th>
				<?php
			}
			?>
		</tr>
	</thead>
	<tbody>
		<?php
		$count = 0;
		foreach ($products as $product)
		{
			$productUrl = JRoute::_(EshopRoute::getProductRoute($product->id, ($catId && EshopHelper::isProductCategory($product->id, $catId)) ? $catId : EshopHelper::getProductCategory($product->id)));
			?>
			<tr class="row<?php echo $count % 2; ?>">
				<td>
					<a href="<?php echo $productUrl; ?>" title="<?php echo $product->product_name; ?>"><?php echo $product->product_name; ?></a>
				</td>
				<?php
				if (EshopHelper::getConfigValue('table_show_image', 1))
				{
					?>
					<td>
						<div class="eshop-image-block">
							<div class="image <?php echo $imgPolaroid; ?>">
								<a href="<?php echo $productUrl; ?>" class="product-image">
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
					</td>
					<?php
				}
				if (EshopHelper::getConfigValue('table_show_short_description', 1))
				{
					?>
					<td><?php echo $product->product_short_desc; ?></td>
					<?php
				}
				if (EshopHelper::getConfigValue('table_show_category', 1))
				{
					?>
					<td>
						<?php
						$categoryId = ($catId && EshopHelper::isProductCategory($product->id, $catId)) ? $catId : EshopHelper::getProductCategory($product->id);
						$category = EshopHelper::getCategory($categoryId);
						$categoryUrl = EshopRoute::getCategoryRoute($categoryId);
						?>
						<a href="<?php echo $categoryUrl; ?>" title="<?php echo $category->category_name; ?>"><?php echo $category->category_name; ?></a>
					</td>
					<?php
				}
				if (EshopHelper::getConfigValue('table_show_manufacturer', 1))
				{
					?>
					<td>
						<?php
						$manufacturer = EshopHelper::getManufacturer($product->manufacturer_id);
						$manufacturerUrl = EshopRoute::getManufacturerRoute($manufacturer->id);
						?>
						<a href="<?php echo $manufacturerUrl?>" title="<?php echo $manufacturer->manufacturer_name; ?>"><?php echo $manufacturer->manufacturer_name; ?></a>
					</td>
					<?php
				}
				if (EshopHelper::getConfigValue('table_show_price', 1))
				{
					?>
					<td>
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
					</td>
					<?php
				}
				if (EshopHelper::getConfigValue('table_show_availability', 1))
				{
					?>
					<td><?php echo $product->availability; ?></td>
					<?php
				}
				if (EshopHelper::getConfigValue('table_show_quantity_box', 1))
				{
					?>
					<td>
						<?php
						if (!EshopHelper::isRequiredOptionProduct($product->id))
						{
							?>
							<div class="<?php echo $inputAppendClass; ?> <?php echo $inputPrependClass; ?>">
								<span class="eshop-quantity">
									<a class="<?php echo $btnClass; ?> btn-default button-minus" id="<?php echo $product->id; ?>" data="down">-</a>
									<input type="text" class="eshop-quantity-value" id="quantity_<?php echo $product->id; ?>" name="quantity_<?php echo $product->id; ?>" value="<?php echo EshopHelper::getConfigValue('start_quantity_number', '1'); ?>" />
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
						?>
					</td>
					<?php
				}
				if (EshopHelper::getConfigValue('table_show_actions', 1))
				{
					?>
					<td>
						<div class="eshop-buttons">                            
							<?php
							if (!EshopHelper::isRequiredOptionProduct($product->id))
							{
								if (EshopHelper::isCartMode($product) || EshopHelper::isQuoteMode($product))
								{
									?>
									<div class="eshop-cart-area">
										<?php
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
							?>
						</div>
					</td>
					<?php
				}
				?>
			</tr>
			<?php
			$count++;
		}
		?>
	</tbody>
</table>
<?php
if (EshopHelper::getConfigValue('table_show_quantity_box') && EshopHelper::getConfigValue('one_add_to_cart_button', '0'))
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