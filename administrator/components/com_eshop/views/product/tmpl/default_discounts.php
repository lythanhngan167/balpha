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
?>
<table class="adminlist table table-bordered" style="text-align: center;">
	<thead>
	<tr>
		<th class="title" width="20%"><?php echo JText::_('ESHOP_CUSTOMER_GROUP'); ?></th>
		<th class="title" width="10%"><?php echo JText::_('ESHOP_QUANTITY'); ?></th>
		<th class="title" width="10%"><?php echo JText::_('ESHOP_PRIORITY'); ?></th>
		<th class="title" width="10%"><?php echo JText::_('ESHOP_PRICE'); ?></th>
		<th class="title" width="15%"><?php echo JText::_('ESHOP_START_DATE'); ?></th>
		<th class="title" width="15%"><?php echo JText::_('ESHOP_END_DATE'); ?></th>
		<th class="title" width="10%"><?php echo JText::_('ESHOP_PUBLISHED'); ?></th>
		<th class="title" width="10%">&nbsp;</th>
	</tr>
	</thead>
	<tbody id="product_discounts_area">
	<?php
	$options = array();
	$options[] = JHtml::_('select.option', '1', Jtext::_('ESHOP_YES'));
	$options[] = JHtml::_('select.option', '0', Jtext::_('ESHOP_NO'));
	for ($i = 0; $n = count($this->productDiscounts), $i < $n; $i++) {
		$productDiscount = $this->productDiscounts[$i];
		?>
		<tr id="product_discount_<?php echo $i; ?>">
			<td style="text-align: center;">
				<?php echo $this->lists['discount_customer_group_'.$productDiscount->id]; ?>
				<input type="hidden" class="inputbox" name="productdiscount_id[]" value="<?php echo $productDiscount->id; ?>" />
			</td>
			<td style="text-align: center;">
				<input class="input-mini" type="text" name="discount_quantity[]" maxlength="10" value="<?php echo $productDiscount->quantity; ?>" />
			</td>
			<td style="text-align: center;">
				<input class="input-small" type="text" name="discount_priority[]" maxlength="10" value="<?php echo $productDiscount->priority; ?>" />
			</td>
			<td style="text-align: center;">
				<input class="input-small" type="text" name="discount_price[]" maxlength="10" value="<?php echo $productDiscount->price; ?>" />
			</td>
			<td style="text-align: center;">
				<?php echo JHtml::_('calendar', (($productDiscount->date_start == $this->nullDate) || !$productDiscount->date_start) ? '' : JHtml::_('date', $productDiscount->date_start, 'Y-m-d', null), 'discount_date_start[]', 'discount_date_start_'.$i, '%Y-%m-%d', array('class' => 'input-small')); ?>
			</td>
			<td style="text-align: center;">
				<?php echo JHtml::_('calendar', (($productDiscount->date_end == $this->nullDate) || !$productDiscount->date_end) ? '' : JHtml::_('date', $productDiscount->date_end, 'Y-m-d', null), 'discount_date_end[]', 'discount_date_end_'.$i, '%Y-%m-%d', array('class' => 'input-small')); ?>
			</td>
			<td style="text-align: center;">
				<?php echo JHtml::_('select.genericlist', $options, 'discount_published[]', ' class="inputbox"', 'value', 'text', $productDiscount->published); ?>
			</td>
			<td style="text-align: center;">
				<input type="button" class="btn btn-small btn-primary" name="btnRemove" value="<?php echo JText::_('ESHOP_BTN_REMOVE'); ?>" onclick="removeProductDiscount(<?php echo $i; ?>);" />
			</td>
		</tr>
		<?php
	}
	?>
	</tbody>
	<tfoot>
	<tr>
		<td colspan="9">
			<input type="button" class="btn btn-small btn-primary" name="btnAdd" value="<?php echo JText::_('ESHOP_BTN_ADD'); ?>" onclick="addProductDiscount();" />
		</td>
	</tr>
	</tfoot>
</table>
