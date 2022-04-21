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
			<?php
			if (EshopHelper::showPrice())
			{
				?>
				<td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: right; padding: 7px; color: #222222;">
				<?php echo JText::_('ESHOP_UNIT_PRICE'); ?>
				</td>
				<td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: right; padding: 7px; color: #222222;">
					<?php echo JText::_('ESHOP_TOTAL'); ?>
				</td>
				<?php
			}
			?>
		</tr>
	</thead>
	<tbody>
		<?php
		foreach ($quoteProducts as $product)
		{
			?>
			<tr>
				<td style="font-size: 12px;	border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;">
				<?php echo $product->product_name; ?>
				<?php
				foreach ($product->quoteOptions as $option)
				{
				?>
				<br />
				&nbsp;<small> - <?php echo $option->option_name; ?>: <?php echo $option->option_value; ?></small>
				<?php
				}
				?>
			</td>
			<td style="font-size: 12px;	border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;">
				<?php echo $product->product_sku; ?>
			</td>
			<td style="font-size: 12px;	border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: right; padding: 7px;">
				<?php echo $product->quantity; ?>
			</td>
			<?php
			if (EshopHelper::showPrice())
			{
				?>
				<td style="font-size: 12px;	border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: right; padding: 7px;">
					<?php
					if (!$product->product_call_for_price)
					{
						echo $product->price;
					}
					?>
				</td>
				<td style="font-size: 12px;	border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: right; padding: 7px;">
					<?php
					if (!$product->product_call_for_price)
					{
						echo $product->total_price;
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
	</tbody>
	<?php
	if (EshopHelper::showPrice())
	{
		?>
		<tfoot>
			<tr>
				<td style="font-size: 12px;	border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: right; padding: 7px;" colspan="4">
					<b><?php echo JText::_('ESHOP_TOTAL'); ?></b>
				</td>
				<td style="font-size: 12px;	border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: right; padding: 7px;">
					<?php echo $row->total_price; ?>
				</td>
			</tr>
		</tfoot>
		<?php
	}
	?>
</table>