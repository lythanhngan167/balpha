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
//EshopHelper::chosen();
?>
<script type="text/javascript">
	Joomla.submitbutton = function(pressbutton)
	{
		var form = document.adminForm;
		if (pressbutton == 'quote.cancel') {
			Joomla.submitform(pressbutton, form);
			return;
		} else {
			Joomla.submitform(pressbutton, form);
		}
	}
</script>
<form action="index.php" method="post" name="adminForm" id="adminForm">
	<div class="row-fluid">
		<p><?php echo sprintf(JText::_('ESHOP_QUOTE_INTRO'), $this->item->name, JHtml::_('date', $this->item->created_date, EshopHelper::getConfigValue('date_format', 'm-d-Y'))); ?></p>
		<br />
		<div class="span12" style="margin-left: 0px;">
			<b><?php echo JText::_('ESHOP_QUOTE_CUSTOMER_DETAILS'); ?></b>
			<table class="adminlist table table-bordered" style="text-align: center;">
				<thead>
					<tr>
						<th class="text_left" width="15%"><?php echo JText::_('ESHOP_NAME'); ?></th>
						<th class="text_left" width="15%"><?php echo JText::_('ESHOP_EMAIL'); ?></th>
						<th class="text_left" width="15%"><?php echo JText::_('ESHOP_COMPANY'); ?></th>
						<th class="text_left" width="15%"><?php echo JText::_('ESHOP_TELEPHONE'); ?></th>
						<th class="text_left" width="40%"><?php echo JText::_('ESHOP_MESSAGE'); ?></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class="text_left"><?php echo $this->item->name; ?></td>
						<td class="text_left"><a href="mailto: <?php echo $this->item->email; ?>"><?php echo $this->item->email; ?></a></td>
						<td class="text_left"><?php echo $this->item->company; ?></td>
						<td class="text_left"><?php echo $this->item->telephone; ?></td>
						<td class="text_left"><?php echo $this->item->message; ?></td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="span12" style="margin-left: 0px;">
			<b><?php echo JText::_('ESHOP_QUOTE_QUOTATION_PRODUCTS'); ?></b>
			<table class="adminlist table table-bordered" style="text-align: center;">
				<thead>
					<tr>
						<th class="text_left" width="40%"><?php echo JText::_('ESHOP_PRODUCT_NAME'); ?></th>
						<th class="text_left" width="15%"><?php echo JText::_('ESHOP_MODEL'); ?></th>
						<th class="text_right" width="15%"><?php echo JText::_('ESHOP_QUANTITY'); ?></th>
						<?php
						if (EshopHelper::showPrice())
						{
							?>
							<th class="text_right" width="15%"><?php echo JText::_('ESHOP_UNIT_PRICE'); ?></th>
							<th class="text_right" width="15%"><?php echo JText::_('ESHOP_TOTAL'); ?></th>
							<?php
						}
						?>
					</tr>
				</thead>
				<tbody>
				<?php
				foreach ($this->lists['quote_products'] as $product)
				{
					$options = $product->options;
					?>
					<tr>
						<td class="text_left">
							<?php
							echo '<b>' . $product->product_name . '</b>';
							for ($i = 0; $n = count($options), $i < $n; $i++)
							{
								if ($options[$i]->option_type == 'File' && $options[$i]->option_value != '')
								{
									echo '<br />- ' . $options[$i]->option_name . ': <a href="index.php?option=com_eshop&task=quote.downloadFile&id=' . $options[$i]->id . '">' . $options[$i]->option_value . '</a>';
								}
								else
								{
									echo '<br />- ' . $options[$i]->option_name . ': ' . $options[$i]->option_value . (isset($options[$i]->sku) && $options[$i]->sku != '' ? ' (' . $options[$i]->sku . ')' : '');
								}
							}
							?>
						</td>
						<td class="text_left"><?php echo $product->product_sku; ?></td>
						<td class="text_right"><?php echo $product->quantity; ?></td>
						<?php
						if (EshopHelper::showPrice())
						{
							?>
							<td class="text_right">
								<?php
								if (!$product->product_call_for_price)
								{
									echo $this->currency->format($product->price, $this->item->currency_code, $this->item->currency_exchanged_value);
								}
								?>
							</td>
							<td class="text_right">
								<?php
								if (!$product->product_call_for_price)
								{
									echo $this->currency->format($product->total_price, $this->item->currency_code, $this->item->currency_exchanged_value);
								}
								?>
							</td>
							<?php
						}
						?>
					</tr>
					<?php
				}
				if (EshopHelper::showPrice())
				{
					?>
					<tr>
						<td colspan="4" class="text_right"><?php echo JText::_('ESHOP_TOTAL'); ?>:</td>
						<td class="text_right"><?php echo $this->currency->format($this->item->total, $this->item->currency_code, $this->item->currency_exchanged_value); ?></td>
					</tr>
					<?php
				}
				?>
				</tbody>
			</table>
		</div>
	</div>
	<div class="clearfix"></div>
	<?php echo JHtml::_( 'form.token' ); ?>
	<input type="hidden" name="option" value="com_eshop" />
	<input type="hidden" name="cid[]" value="<?php echo intval($this->item->id); ?>" />
	<input type="hidden" name="task" value="" />
</form>