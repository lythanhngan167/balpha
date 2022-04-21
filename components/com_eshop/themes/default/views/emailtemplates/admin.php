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
?>
<table style="border-collapse: collapse; width: 100%; border-top: 1px solid #DDDDDD; border-left: 1px solid #DDDDDD; margin-bottom: 20px;">
	<thead>
		<tr>
			<td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: left; padding: 7px; color: #222222;">
				<?php echo JText::_('ESHOP_PRODUCT_NAME'); ?>
			</td>
			<td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: left; padding: 7px; color: #222222;">
				<?php echo JText::_('ESHOP_MODEL'); ?>
			</td>
			<td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: right; padding: 7px; color: #222222;">
				<?php echo JText::_('ESHOP_QUANTITY'); ?>
			</td>
			<td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: right; padding: 7px; color: #222222;">
				<?php echo JText::_('ESHOP_UNIT_PRICE'); ?>
			</td>
			<td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: right; padding: 7px; color: #222222;">
				<?php echo JText::_('ESHOP_TOTAL'); ?>
			</td>
		</tr>
	</thead>
	<tbody>
		<?php
		foreach ($orderProducts as $product)
		{
			?>
			<tr>
				<td style="font-size: 12px;	border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;">
				<?php echo $product->product_name; ?>
				<?php
				foreach ($product->orderOptions as $option)
				{
					if ($option->option_type == 'File' && $option->option_value != '')
					{
						echo '<br />- ' . $option->option_name . ': <a href="' . EshopHelper::getSiteUrl() . 'index.php?option=com_eshop&task=downloadOptionFile&id=' . $option->id . '">' . $option->option_value . '</a>';
					}
					else
					{
						echo '<br />- ' . $option->option_name . ': ' . $option->option_value . (isset($option->sku) && $option->sku != '' ? ' (' . $option->sku . ')' : '');
					}
				}
				?>
			</td>
			<td style="font-size: 12px;	border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;">
				<?php echo $product->product_sku; ?>
			</td>
			<td style="font-size: 12px;	border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: right; padding: 7px;">
				<?php echo $product->quantity; ?>
			</td>
			<td style="font-size: 12px;	border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: right; padding: 7px;">
				<?php echo $product->price; ?>
			</td>
			<td style="font-size: 12px;	border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: right; padding: 7px;">
				<?php echo $product->total_price; ?>
			</td>
		</tr>
		<?php
		}
		?>
	</tbody>
	<tfoot>
		<?php
		foreach ($orderTotals as $total)
		{
			?>
			<tr>
				<td style="font-size: 12px;	border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: right; padding: 7px;" colspan="4">
					<b><?php echo JText::_($total->title); ?></b>
				</td>
				<td style="font-size: 12px;	border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: right; padding: 7px;">
					<?php echo $total->text; ?>
				</td>
			</tr>
		<?php
		}
		?>
	</tfoot>
</table>