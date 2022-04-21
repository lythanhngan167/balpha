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
		<th class="title" width="10%"><?php echo JText::_('ESHOP_CUSTOMER_GROUP'); ?></th>
		<th class="title" width="10%"><?php echo JText::_('ESHOP_PRIORITY'); ?></th>
		<th class="title" width="10%"><?php echo JText::_('ESHOP_PRICE'); ?></th>
		<th class="title" width="15%"><?php echo JText::_('ESHOP_START_DATE'); ?></th>
		<th class="title" width="15%"><?php echo JText::_('ESHOP_END_DATE'); ?></th>
		<th class="title" width="10%"><?php echo JText::_('ESHOP_PUBLISHED'); ?></th>
		<th class="title" width="10%">&nbsp;</th>
	</tr>
	</thead>
	<tbody id="product_specials_area">
	<?php
	$options = array();
	$options[] = JHtml::_('select.option', '1', Jtext::_('ESHOP_YES'));
	$options[] = JHtml::_('select.option', '0', Jtext::_('ESHOP_NO'));
	for ($i = 0; $n = count($this->productSpecials), $i < $n; $i++) {
		$productSpecial = $this->productSpecials[$i];
		?>
		<tr id="product_special_<?php echo $i; ?>">
			<td style="text-align: center;">
				<?php echo $this->lists['special_customer_group_'.$productSpecial->id]; ?>
			</td>
			<td style="text-align: center;">
				<input class="input-small" type="text" name="special_priority[]" maxlength="10" value="<?php echo $productSpecial->priority; ?>" />
			</td>
			<td style="text-align: center;">
				<input class="input-small" type="text" name="special_price[]" maxlength="10" value="<?php echo $productSpecial->price; ?>" />
			</td>
			<td style="text-align: center;">
				<?php echo JHtml::_('calendar', (($productSpecial->date_start == $this->nullDate) || !$productSpecial->date_start) ? '' : JHtml::_('date', $productSpecial->date_start, 'Y-m-d', null), 'special_date_start[]', 'special_date_start_'.$i, '%Y-%m-%d', array('class' => 'input-small')); ?>
			</td>
			<td style="text-align: center;">
				<?php echo JHtml::_('calendar', (($productSpecial->date_end == $this->nullDate) || !$productSpecial->date_end) ? '' : JHtml::_('date', $productSpecial->date_end, 'Y-m-d', null), 'special_date_end[]', 'special_date_end_'.$i, '%Y-%m-%d', array('class' => 'input-small')); ?>
			</td>
			<td style="text-align: center;">
				<?php echo JHtml::_('select.genericlist', $options, 'special_published[]', ' class="inputbox"', 'value', 'text', $productSpecial->published); ?>
			</td>
			<td style="text-align: center;">
				<input type="button" class="btn btn-small btn-primary" name="btnRemove" value="<?php echo JText::_('ESHOP_BTN_REMOVE'); ?>" onclick="removeProductSpecial(<?php echo $i; ?>);" />
			</td>
		</tr>
		<?php
	}
	?>
	</tbody>
	<tfoot>
	<tr>
		<td colspan="7">
			<input type="button" class="btn btn-small btn-primary" name="btnAdd" value="<?php echo JText::_('ESHOP_BTN_ADD'); ?>" onclick="addProductSpecial();" />
		</td>
	</tr>
	</tfoot>
</table>
