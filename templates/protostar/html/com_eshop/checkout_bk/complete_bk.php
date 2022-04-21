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

if (!is_object($this->orderInfor))
{
	?>
    <div class="alert alert-danger" role="alert">
        <p><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> <?php echo JText::_('ESHOP_ORDER_NOT_EXISTED'); ?></p>
    </div>
	<?php
}
else
{
	if ($this->conversionTrackingCode != '')
	{
		?>
		<script language="javascript">
			<?php echo $this->conversionTrackingCode; ?>
		</script>
		<?php
	}
	// iDevAffiliate integration
	if (EshopHelper::getConfigValue('idevaffiliate_integration') && file_exists( JPATH_SITE . "/" . EshopHelper::getConfigValue('idevaffiliate_path') . "/sale.php" ))
	{
		?>
		<img border="0" src="<?php echo self::getSiteUrl() . self::getConfigValue('idevaffiliate_path'); ?>/sale.php?profile=72198&idev_saleamt=<?php echo $this->orderInfor->total; ?>&idev_ordernum=<?php echo $this->orderInfor->order_number; ?>" width="1" height="1" />
		<?php
		EshopHelper::iDevAffiliate($this->orderInfor);
	}
	$hasShipping = $this->orderInfor->shipping_method;
	?>
	<div class="order-complete">
	<h1><?php echo sprintf(JText::_('ESHOP_ORDER_COMPLETED_TITLE'), $this->orderInfor->order_number); ?></h1>
        <?php /*
	<p><?php echo sprintf(JText::_('ESHOP_ORDER_COMPLETED_DESC'), $this->orderInfor->id); ?></p>
    */ ?>
	<table cellpadding="0" cellspacing="0" class="list table-responsive">
		<thead>
			<tr>
				<td colspan="2" >
					<?php echo JText::_('ESHOP_ORDER_DETAILS'); ?>
				</td>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td width="50%">
					<b><?php echo JText::_('ESHOP_ORDER_ID'); ?>: </b> <?php echo $this->orderInfor->order_number; ?><br />
					<b><?php echo JText::_('ESHOP_DATE_ADDED'); ?>: </b> <?php echo JHtml::date($this->orderInfor->created_date, EshopHelper::getConfigValue('date_format', 'm-d-Y H:i:s'), null); ?>
	         	</td>
				<td width="50%">
					<b><?php echo JText::_('ESHOP_PAYMENT_METHOD'); ?>: </b> <?php echo JText::_($this->orderInfor->payment_method_title); ?><br />
					<?php if($this->orderInfor->payment_method == 'os_bank_transfer'){ ?>
						<?php echo nl2br($this->orderInfor->bank_transfer); ?>
						<br/>
					<?php } ?>
					<b><?php echo JText::_('ESHOP_SHIPPING_METHOD'); ?>: </b> <?php echo JText::_($this->orderInfor->shipping_method_title); ?><br />
				</td>
			</tr>
		</tbody>
		</table>
		<table cellpadding="0" cellspacing="0" class="list table-responsive">
			<thead>
				<tr>
					<td width="50%">
						<?php echo JText::_('ESHOP_PAYMENT_ADDRESS'); ?>
				</td>
				<?php
				if ($hasShipping)
				{
					?>
					<td width="50%">
						<?php echo JText::_('ESHOP_SHIPPING_ADDRESS'); ?>
					</td>
					<?php
				}
				?>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td width="50%" data-content="<?php echo JText::_('ESHOP_PAYMENT_ADDRESS'); ?>">
					<?php
					echo EshopHelper::getPaymentAddress($this->orderInfor, true);
					$excludedFields = array('firstname', 'lastname', 'email', 'telephone', 'fax', 'company', 'company_id', 'address_1', 'address_2', 'city', 'postcode', 'country_id', 'zone_id');
					foreach ($this->paymentFields as $field)
					{
						$fieldName = $field->name;
						if (!in_array($fieldName, $excludedFields))
						{
							$fieldValue = $this->orderInfor->{'payment_'.$fieldName};
							if (is_string($fieldValue) && is_array(json_decode($fieldValue)))
							{
								$fieldValue = implode(', ', json_decode($fieldValue));
							}
							if ($fieldValue != '')
							{
								echo '<br />' . JText::_($field->title) . ': ' . $fieldValue;
							}
						}
					}
					?>
				</td>
				<?php
				if ($hasShipping)
				{
					?>
					<td width="50%"  data-content="<?php echo JText::_('ESHOP_SHIPPING_ADDRESS'); ?>">
						<?php
						echo EshopHelper::getShippingAddress($this->orderInfor, true);
						foreach ($this->shippingFields as $field)
						{
							$fieldName = $field->name;
							if (!in_array($fieldName, $excludedFields))
							{
								$fieldValue = $this->orderInfor->{'shipping_'.$fieldName};
								if (is_string($fieldValue) && is_array(json_decode($fieldValue)))
								{
									$fieldValue = implode(', ', json_decode($fieldValue));
								}
								if ($fieldValue != '')
								{
									echo '<br />' . JText::_($field->title) . ': ' . $fieldValue;
								}
							}
						}
						?>
					</td>
					<?php
				}
				?>
			</tr>
		</tbody>
	</table>
	<table cellpadding="0" cellspacing="0" class="list table-responsive">
		<thead>
			<tr>
				<td width="20%">
					<?php echo JText::_('ESHOP_PRODUCT_NAME'); ?>
				</td>
				<td width="20%">
					<?php echo JText::_('ESHOP_MODEL'); ?>
				</td>
				<td width="20%">
					<?php echo JText::_('ESHOP_QUANTITY'); ?>
				</td>
				<td width="20%">
					<?php echo JText::_('ESHOP_PRICE'); ?>
				</td>
				<td width="20%">
					<?php echo JText::_('ESHOP_TOTAL'); ?>
				</td>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach ($this->orderProducts as $product)
			{
				$options = $product->options;
				?>
				<tr>
					<td width="20%" data-content="<?php echo JText::_('ESHOP_PRODUCT_NAME'); ?>">
						<?php
						echo '<b>' . $product->product_name . '</b>';
						for ($i = 0; $n = count($options), $i < $n; $i++)
						{
							$option = $options[$i];
							if ($option->option_type == 'File' && $option->option_value != '')
							{
								echo '<br />- ' . $option->option_name . ': <a href="index.php?option=com_eshop&task=downloadOptionFile&id=' . $option->id . '">' . $option->option_value . '</a>';
							}
							else
							{
								echo '<br />- ' . $option->option_name . ': ' . $option->option_value . (isset($option->sku) && $option->sku != '' ? ' (' . $option->sku . ')' : '');
							}
						}
						?>
					</td>
					<td data-content="<?php echo JText::_('ESHOP_MODEL'); ?>"><?php echo $product->product_sku; ?></td>
					<td  data-content="<?php echo JText::_('ESHOP_QUANTITY'); ?>"><?php echo $product->quantity; ?></td>
					<td  data-content="<?php echo JText::_('ESHOP_PRICE'); ?>"><?php echo $product->price; ?></td>
					<td  data-content="<?php echo JText::_('ESHOP_TOTAL'); ?>"><?php echo $product->total_price; ?></td>
				</tr>
				<?php
			}
			?>
		</tbody>
		<tfoot>
			<?php
				foreach ($this->orderTotals as $ordertotal)
				{
			?>
			<tr>
				<td colspan="3"></td>
				<td>
					<b><?php echo $ordertotal->title?>: </b>
				</td>
				<td>
					<?php echo $ordertotal->text?>
				</td>
			</tr>
			<?php
				}
			?>
		</tfoot>
	</table>
	</div>
	<?php
}
?>
