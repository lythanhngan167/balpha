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
<h1><?php echo JText::_('ESHOP_PRODUCT_COMPARE'); ?></h1><br />
<?php
if (!count($this->products))
{
	?>
	<div class="no-content"><?php echo JText::_('ESHOP_COMPARE_EMPTY'); ?></div>
	<?php
}
else
{
	?>
	<table class="table table-bordered table-striped">
		<thead>
			<tr>
				<th colspan="<?php echo count($this->products) + 1; ?>"><?php echo JText::_('ESHOP_COMPARE_PRODUCT_DETAILS'); ?></th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td width="20%" style="text-align:right;"><b><?php echo JText::_('ESHOP_COMPARE_PRODUCT'); ?></b></td>
				<?php
				foreach ($this->products as $product)
				{
					$viewProductUrl = JRoute::_(EshopRoute::getProductRoute($product['product_id'], EshopHelper::getProductCategory($product['product_id'])));
					?>
					<td>
						<a href="<?php echo $viewProductUrl; ?>">
							<?php echo $product['product_name']; ?>
						</a>
					</td>
					<?php
				}
				?>
			</tr>
			<?php
			if (EshopHelper::getConfigValue('compare_image'))
			{
				?>
				<tr>
					<td style="text-align:right;"><b><?php echo JText::_('ESHOP_COMPARE_IMAGE'); ?></b></td>
					<?php
					foreach ($this->products as $product)
					{
						?>
						<td style="text-align:center;">
							<img class="<?php echo $imgPolaroid; ?>" src="<?php echo $product['image']; ?>" />
						</td>
						<?php
					}
					?>
				</tr>
				<?php
			}
			if (EshopHelper::showPrice() && EshopHelper::getConfigValue('compare_price'))
			{
				?>
				<tr>
					<td style="text-align:right;"><b><?php echo JText::_('ESHOP_COMPARE_PRICE'); ?></b></td>
					<?php
					foreach ($this->products as $product)
					{
						?>
						<td>
							<?php
							if (!$product['product_call_for_price'])
							{
								if ($product['sale_price'])
								{
									?>
									<span class="eshop-base-price"><?php echo $product['base_price']; ?></span>&nbsp;
									<span class="eshop-sale-price"><?php echo $product['sale_price']; ?></span>
									<?php
								}
								else 
								{
									?>
									<span class="price"><?php echo $product['base_price']; ?></span>
									<?php
								}
							}
							else
							{
								?>
								<span class="call-for-price"><?php echo JText::_('ESHOP_CALL_FOR_PRICE'); ?>: <?php echo EshopHelper::getConfigValue('telephone'); ?></span>
								<?php
							}
							?>
						</td>
						<?php
					}
					?>
				</tr>
				<?php
			}
			if (EshopHelper::getConfigValue('compare_sku'))
			{
				?>
				<tr>
					<td style="text-align:right;"><b><?php echo JText::_('ESHOP_COMPARE_MODEL'); ?></b></td>
					<?php
					foreach ($this->products as $product)
					{
						?>
						<td>
							<?php echo $product['product_sku']; ?>
						</td>
						<?php
					}
					?>
				</tr>
				<?php
			}
			if (EshopHelper::getConfigValue('compare_manufacturer'))
			{
				?>
				<tr>
					<td style="text-align:right;"><b><?php echo JText::_('ESHOP_COMPARE_BRAND'); ?></b></td>
					<?php
					foreach ($this->products as $product)
					{
						?>
						<td>
							<?php echo $product['manufacturer']; ?>
						</td>
						<?php
					}
					?>
				</tr>
				<?php
			}
			if (EshopHelper::getConfigValue('compare_availability'))
			{
				?>
				<tr>
					<td style="text-align:right;"><b><?php echo JText::_('ESHOP_COMPARE_AVAILABILITY'); ?></b></td>
					<?php
					foreach ($this->products as $product)
					{
						?>
						<td>
							<?php echo $product['availability']; ?>
						</td>
						<?php
					}
					?>
				</tr>
				<?php
			}
			if (EshopHelper::getConfigValue('compare_rating'))
			{
				?>
				<tr>
					<td style="text-align:right;"><b><?php echo JText::_('ESHOP_COMPARE_RATING'); ?></b></td>
					<?php
					foreach ($this->products as $product)
					{
						?>
						<td>
							<img src="components/com_eshop/assets/images/stars-<?php echo round($product['rating']); ?>.png" /><br />
							<?php echo sprintf(JText::_('ESHOP_COMPARE_NUM_REVIEWS'), $product['num_reviews']); ?>
						</td>
						<?php
					}
					?>
				</tr>
				<?php
			}
			if (EshopHelper::getConfigValue('compare_short_desc'))
			{
				?>
				<tr>
					<td style="text-align:right;"><b><?php echo JText::_('ESHOP_COMPARE_SHORT_DESCRIPTION'); ?></b></td>
					<?php
					foreach ($this->products as $product)
					{
						?>
						<td>
							<?php echo $product['product_short_desc']; ?>
						</td>
						<?php
					}
					?>
				</tr>
				<?php
			}
			if (EshopHelper::getConfigValue('compare_desc'))
			{
				?>
				<tr>
					<td style="text-align:right;"><b><?php echo JText::_('ESHOP_COMPARE_DESCRIPTION'); ?></b></td>
					<?php
					foreach ($this->products as $product)
					{
						?>
						<td>
							<?php echo $product['product_desc']; ?>
						</td>
						<?php
					}
					?>
				</tr>
				<?php
			}
			if (EshopHelper::getConfigValue('compare_weight'))
			{
				?>
				<tr>
					<td style="text-align:right;"><b><?php echo JText::_('ESHOP_COMPARE_WEIGHT'); ?></b></td>
					<?php
					foreach ($this->products as $product)
					{
						?>
						<td>
							<?php echo $product['weight']; ?>
						</td>
						<?php
					}
					?>
				</tr>
				<?php
			}
			if (EshopHelper::getConfigValue('compare_dimensions'))
			{
				?>
				<tr>
					<td style="text-align:right;"><b><?php echo JText::_('ESHOP_COMPARE_DIMENSIONS'); ?></b></td>
					<?php
					foreach ($this->products as $product)
					{
						?>
						<td>
							<?php echo $product['length'] . ' x ' . $product['width'] . ' x ' . $product['height']; ?>
						</td>
						<?php
					}
					?>
				</tr>
				<?php
			}
			
			// Product custom fields compare
			if (count($this->fieldTitle))
			{
			    $fieldTitle = $this->fieldTitle;
			    for ($i = 0; $n = count($fieldTitle), $i < $n; $i++)
			    {
			        ?>
    			    <tr>
    					<td style="text-align:right;">
    						<b><?php echo $fieldTitle[$i]; ?></b>
    					</td>
    					<?php
    					foreach ($this->products as $product)
    					{
    					    $productFieldValue = $product['productFieldValue'];
                            ?>
                            <td><?php echo $productFieldValue[$i]; ?></td>
                            <?php
    					}
    					?>
    			    </tr>
    			    <?php
			    }
			}
			
			if (count($this->visibleAttributeGroups) && EshopHelper::getConfigValue('compare_attributes'))
			{
				foreach ($this->visibleAttributeGroups as $key => $visibleAttributeGroup)
				{
					?>
					<tr>
						<th style="text-align: left;" colspan="<?php echo count($this->products) + 1; ?>"><?php echo $visibleAttributeGroup['attributegroup_name']; ?></th>
					</tr>
					<?php
					foreach ($visibleAttributeGroup['attribute_name'] as $attributeName)
					{
						?>
						<tr>
							<td style="text-align:right;"><?php echo $attributeName; ?></td>
							<?php
							foreach ($this->products as $product)
							{
								?>
								<td>
									<?php
										if (isset($product['attributes'][$visibleAttributeGroup['id']]['value'][$attributeName]))
										{
											echo $product['attributes'][$visibleAttributeGroup['id']]['value'][$attributeName];
										}
									?>
								</td>
								<?php
							}
							?>
						</tr>
						<?php
					}
				}
			}
			
			$showAddToCart = false;
			foreach ($this->products as $product)
			{
				if (!EshopHelper::getConfigValue('catalog_mode') && EshopHelper::showPrice() && !$product['product_call_for_price'])
				{
					$showAddToCart = true;
					break;
				}
			}
			if ($showAddToCart)
			{
				?>
				<tr>
					<td>&nbsp;</td>
					<?php
					foreach ($this->products as $product)
					{
						?>
						<td style="text-align:center;">
							<?php
							if (!EshopHelper::getConfigValue('catalog_mode') && EshopHelper::showPrice() && !$product['product_call_for_price'])
							{
								?>
								<input id="add-to-cart-<?php echo $product['product_id']; ?>" type="button" class="<?php echo $btnClass; ?> btn-primary" onclick="addToCart(<?php echo $product['product_id']; ?>, 1, '<?php echo EshopHelper::getSiteUrl(); ?>', '<?php echo EshopHelper::getAttachedLangLink(); ?>', '<?php echo EshopHelper::getConfigValue('cart_popout')?>', '<?php echo JRoute::_(EshopRoute::getViewRoute('cart')); ?>');" value="<?php echo JText::_('ESHOP_ADD_TO_CART'); ?>" />
								<?php
							}
							?>
						</td>
						<?php
					}
					?>
				</tr>
				<?php
			}
			?>
			<tr>
				<td>&nbsp;</td>
				<?php
				foreach ($this->products as $product)
				{
					?>
					<td style="text-align:center;">
						<input type="button" class="<?php echo $btnClass; ?> btn-primary" onclick="removeFromCompare(<?php echo $product['product_id']; ?>, '<?php echo EshopHelper::getSiteUrl(); ?>', '<?php echo EshopHelper::getAttachedLangLink(); ?>');" value="<?php echo JText::_('ESHOP_REMOVE'); ?>" />
					</td>
					<?php
				}
				?>
			</tr>
		</tbody>
	</table>	
	<?php
}