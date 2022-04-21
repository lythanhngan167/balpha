<?php
/**
 * @version        3.1.0
 * @package        Joomla
 * @subpackage     EShop
 * @author         Giang Dinh Truong
 * @copyright      Copyright (C) 2012 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */

// no direct access
defined('_JEXEC') or die();

$options = array();
$options[] = JHtml::_('select.option', '1', Jtext::_('ESHOP_YES'));
$options[] = JHtml::_('select.option', '0', Jtext::_('ESHOP_NO'));

$baseUri = JUri::base();
$rootUri = JUri::root();
?>
<div class="span12">
	<fieldset class="admintable">
		<legend><?php echo JText::_('ESHOP_ASSIGN_OPTIONS_TO_PRODUCT'); ?></legend>
		<div class="tabbable tabs-left">
			<ul class="nav nav-tabs" id="nav-tabs">
				<li><?php echo $this->lists['options']; ?></li>
				<?php
				echo $this->lists['options_type'];
				for ($i = 0; $n = count($this->options), $i < $n; $i++) {
					echo $this->lists['option_values_'.$this->options[$i]->id];
				}
				echo $this->lists['price_sign'];
				echo $this->lists['price_type'];
				echo $this->lists['weight_sign'];
				?>
				<?php
				for ($i = 0; $n = count($this->productOptions), $i < $n; $i++) {
					$productOption = $this->productOptions[$i];
					?>
					<li <?php echo ($i == 0) ? 'class="active"' : 0; ?> id="product_option_<?php echo $productOption->id; ?>">
						<a href="#option-<?php echo $productOption->id; ?>-page" data-toggle="tab"><?php echo $productOption->option_name; ?>
							<img src="<?php echo $baseUri; ?>components/com_eshop/assets/images/remove.png" onclick="removeProductOption(<?php echo $productOption->id; ?>, '<?php echo $productOption->option_name; ?>');" />
						</a>
						<input type="hidden" name="productoption_id[]" value="<?php echo $productOption->id; ?>"/>
					</li>
					<?php
				}
				?>
			</ul>
			<div class="tab-content" id="tab-content">
				<?php
				for ($i = 0; $n = count($this->productOptions), $i < $n; $i++) {
					$productOption = $this->productOptions[$i];
					?>
					<div class="tab-pane<?php echo ($i == 0) ? ' active' : ''; ?>" id="option-<?php echo $productOption->id; ?>-page">
						<table style="width: 100%;" class="adminlist">
							<tbody>
							<tr>
								<td style="width: 150px;"><?php echo JText::_('ESHOP_REQUIRED'); ?></td>
								<td>
									<?php echo JHtml::_('select.genericlist', $options, 'required_'.$productOption->id, ' class="inputbox"', 'value', 'text', $productOption->required); ?>
								</td>
							</tr>
							</tbody>
						</table>
						<?php
						if ($productOption->option_type == 'Select' || $productOption->option_type == 'Radio' || $productOption->option_type == 'Checkbox')
						{
							?>
							<table class="adminlist table table-bordered" style="text-align: center;">
								<thead>
								<tr>
									<th class="title" width=""><?php echo JText::_('ESHOP_OPTION_VALUE'); ?></th>
									<th style="display: none;" class="title" width=""><?php echo JText::_('ESHOP_SKU'); ?></th>
									<th style="display: none;" class="title" width=""><?php echo JText::_('ESHOP_QUANTITY'); ?></th>
									<th class="title" width=""><?php echo JText::_('ESHOP_PRICE'); ?></th>
									<th class="title" width=""><?php echo JText::_('ESHOP_WEIGHT'); ?></th>
									<th class="title" width="" nowrap="nowrap"><?php echo JText::_('ESHOP_IMAGE'); ?></th>
									<th class="title" width="">&nbsp;</th>
								</tr>
								</thead>
								<tbody id="product_option_<?php echo $productOption->id; ?>_values_area">
								<?php
								$options = array();
								$options[] = JHtml::_('select.option', '1', Jtext::_('ESHOP_YES'));
								$options[] = JHtml::_('select.option', '0', Jtext::_('ESHOP_NO'));
								for ($j = 0; $m = count($this->productOptionValues[$i]), $j < $m; $j++) {
									$productOptionValue = $this->productOptionValues[$i][$j];
									?>
									<tr id="product_option_<?php echo $productOption->id; ?>_value_<?php echo $j; ?>">
										<td style="text-align: center;">
											<?php echo $this->lists['product_option_value_'.$productOptionValue->id]; ?>
										</td>
										<td style="text-align: center; display: none">
											<input class="input-small" type="text" name="optionvalue_<?php echo $productOption->id; ?>_sku[]" size="10" maxlength="255" value="<?php echo $productOptionValue->sku; ?>" />
										</td>
										<td style="text-align: center;display: none">
											<input class="input-small" type="text" name="optionvalue_<?php echo $productOption->id; ?>_quantity[]" size="10" maxlength="255" value="<?php echo $productOptionValue->quantity; ?>" />
											<input type="hidden" name="productoptionvalue_id" value="<?php echo $productOptionValue->id; ?>" />
										</td>
										<td style="text-align: center; ">
											<?php echo $this->lists['price_sign_'.$productOptionValue->id]; ?>
											<input class="input-small" type="text" name="optionvalue_<?php echo $productOption->id; ?>_price[]" size="10" maxlength="255" value="<?php echo $productOptionValue->price; ?>" />
											<?php echo $this->lists['price_type_'.$productOptionValue->id]; ?>
										</td>
										<td style="text-align: center;">
											<?php echo $this->lists['weight_sign_'.$productOptionValue->id]; ?>
											<input class="input-small" type="text" name="optionvalue_<?php echo $productOption->id; ?>_weight[]" size="10" maxlength="255" value="<?php echo $productOptionValue->weight; ?>" />
										</td>
										<td style="text-align: center; vertical-align: middle;" nowrap="nowrap">
											<?php
											if (JFile::exists(JPATH_ROOT.'/media/com_eshop/options/'.$productOptionValue->image))
											{
												$viewImage = JFile::stripExt($productOptionValue->image).'-100x100.'.JFile::getExt($productOptionValue->image);
												if (Jfile::exists(JPATH_ROOT.'/media/com_eshop/options/resized/'.$viewImage))
												{
													?>
													<img class="img-polaroid" width="50" src="<?php echo $rootUri . 'media/com_eshop/options/resized/' . $viewImage; ?>" /><br />
													<?php
												}
											}
											?>
											<input class="input-small" type="file" name="optionvalue_<?php echo $productOption->id; ?>_image[]" accept="image/*" />
											<input type="hidden" name="optionvalue_<?php echo $productOption->id; ?>_imageold[]" value="<?php echo $productOptionValue->image; ?>" />
										</td>
										<td style="text-align: center;">
											<input type="button" class="btn btn-small btn-primary" name="btnRemove" value="<?php echo JText::_('ESHOP_BTN_REMOVE'); ?>" onclick="removeProductOptionValue(<?php echo $productOption->id; ?>, <?php echo $j; ?>);" />
										</td>
									</tr>
									<?php
								}
								?>
								</tbody>
								<tfoot>
								<tr>
									<td colspan="7">
										<input type="button" class="btn btn-small btn-primary" name="btnAdd" value="<?php echo JText::_('ESHOP_BTN_ADD'); ?>" onclick="addProductOptionValue(<?php echo $productOption->id; ?>);" />
										<?php echo $this->lists['option_values_'.$productOption->id]; ?>
									</td>
								</tr>
								</tfoot>
							</table>
							<?php
						}
						if ($productOption->option_type == 'Text' || $productOption->option_type == 'Textarea')
						{
							$productOptionValue = $this->productOptionValues[$i][0];
							?>
							<table style="width: 100%;" class="adminlist">
								<tbody>
								<tr>
									<td style="width: 150px;"><?php echo JText::_('ESHOP_PRODUCT_PRICE_PER_CHAR'); ?></td>
									<td>
										<input type="hidden" name="optionvalue_<?php echo $productOption->id; ?>_id[]" id="optionvalue_<?php echo $productOption->id; ?>_id" value="null"/>
										<?php echo $this->lists['price_sign_t_'.$productOption->id]; ?>
										<input class="input-small" type="text" name="optionvalue_<?php echo $productOption->id; ?>_price[]" size="10" maxlength="255" value="<?php echo $productOptionValue->price; ?>" />
										<?php echo $this->lists['price_type_t_'.$productOption->id]; ?>
									</td>
								</tr>
								</tbody>
							</table>
							<?php
						}
						?>
					</div>
					<?php
				}
				?>
			</div>
		</div>
	</fieldset>
</div>
<?php
if (EshopHelper::getConfigValue('assign_same_options'))
{
	?>
	<div class="span12">
		<fieldset class="admintable">
			<legend><?php echo JText::_('ESHOP_ASSIGN_SAME_OPTIONS_TO_OTHER_PRODUCTS'); ?></legend>
			<table class="admintable adminform" style="width: 80%;">
				<tr>
					<td width="5%">
						<?php echo $this->lists['same_options_products']; ?>
					</td>
					<td><span class="help"><?php echo JText::_('ESHOP_ASSIGN_SAME_OPTIONS_TO_OTHER_PRODUCTS_HELP'); ?></span></td>
				</tr>
			</table>
		</fieldset>
	</div>
	<?php
}
