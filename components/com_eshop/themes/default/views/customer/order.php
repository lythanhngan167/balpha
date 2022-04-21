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
$pullRightClass         = $bootstrapHelper->getClassMapping('pull-right');
$btnClass				= $bootstrapHelper->getClassMapping('btn');

$user = JFactory::getUser();
$language = JFactory::getLanguage();
$tag = $language->getTag();

if (!$tag)
{
	$tag = 'en-GB';
}

if(!$this->orderProducts)
{
	?>
	<div class="warning"><?php echo JText::_('ESHOP_ORDER_DOES_NOT_EXITS'); ?></div>
	<?php
}
else
{
	$hasShipping = $this->orderInfor->shipping_method;
	?>
	<form id="adminForm">
		<table cellpadding="0" cellspacing="0" class="list">
			<thead>
				<tr>
					<td colspan="2" class="left">
						<?php echo JText::_('ESHOP_ORDER_DETAILS'); ?>
					</td>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td style="width: 50%;" class="left">
						 <b><?php echo JText::_('ESHOP_ORDER_ID'); ?>: </b>#<?php echo $this->orderInfor->id; ?><br />
	         			 <b><?php echo JText::_('ESHOP_DATE_ADDED'); ?>: </b> <?php echo JHtml::date($this->orderInfor->created_date, EshopHelper::getConfigValue('date_format', 'm-d-Y'), null); ?>
	         		</td>
					<td style="width: 50%;" class="left">
					    <b><?php echo JText::_('ESHOP_PAYMENT_METHOD'); ?>: </b> <?php echo JText::_($this->orderInfor->payment_method_title); ?><br />
					    <b><?php echo JText::_('ESHOP_SHIPPING_METHOD'); ?>: </b> <?php echo JText::_($this->orderInfor->shipping_method_title); ?><br />
	                </td>
				</tr>
			</tbody>
		</table>
		<table cellpadding="0" cellspacing="0" class="list">
			<thead>
				<tr>
					<td class="left">
						<?php echo JText::_('ESHOP_PAYMENT_ADDRESS'); ?>
					</td>
					<?php
					if ($hasShipping)
					{
						?>
						<td class="left">
							<?php echo JText::_('ESHOP_SHIPPING_ADDRESS'); ?>
						</td>
						<?php
					}
					?>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td class="left">
						<?php
						echo EshopHelper::getPaymentAddress($this->orderInfor);
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
						<td class="left">
							<?php
							echo EshopHelper::getShippingAddress($this->orderInfor);
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
		<table cellpadding="0" cellspacing="0" class="list">
			<thead>
				<tr>
					<td class="left">
						<?php echo JText::_('ESHOP_PRODUCT_NAME'); ?>
					</td>
					<td class="left">
						<?php echo JText::_('ESHOP_MODEL'); ?>
					</td>
					<td class="left">
						<?php echo JText::_('ESHOP_QUANTITY'); ?>
					</td>
					<td class="left">
						<?php echo JText::_('ESHOP_PRICE'); ?>
					</td>
					<td class="left">
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
						<td class="left">
							<?php
							echo '<b>' . $product->product_name . '</b>';
							for ($i = 0; $n = count($options), $i < $n; $i++)
							{
								echo '<br />- ' . $options[$i]->option_name . ': ' . $options[$i]->option_value . (isset($options[$i]->sku) && $options[$i]->sku != '' ? ' (' . $options[$i]->sku . ')' : '');
							}
							?>
						</td>
						<td class="left"><?php echo $product->product_sku; ?></td>
						<td class="left"><?php echo $product->quantity; ?></td>
						<td class="right"><?php echo $product->price; ?></td>
						<td class="right"><?php echo $product->total_price; ?></td>
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
					<td class="right">
						<b><?php echo $ordertotal->title?>: </b>
					</td>
					<td class="right">
						<?php echo $ordertotal->text?>
					</td>
				</tr>
				<?php
					} 
				?>
			</tfoot>
		</table>
		
		<h2><?php echo JText::_('ESHOP_ORDER_HISTORY'); ?></h2>
		<table cellpadding="0" cellspacing="0" class="list">
			<thead>
				<tr>
					<td class="left">
						<?php echo JText::_('ESHOP_DATE_ADDED'); ?>
					</td>
					<td class="left">
						<?php echo JText::_('ESHOP_STATUS'); ?>
					</td>
					<td class="left">
						<?php echo JText::_('ESHOP_COMMENT'); ?>
					</td>
					<?php
					if (EshopHelper::getConfigValue('delivery_date'))
					{
						?>
						<td class="left">
							<?php echo JText::_('ESHOP_DELIVERY_DATE'); ?>
						</td>
						<?php
					}
					?>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td class="left">
						<?php echo JHtml::date($this->orderInfor->created_date, EshopHelper::getConfigValue('date_format', 'm-d-Y'), null);?>
					</td>
					<td class="left">
						<?php echo EshopHelper::getOrderStatusName($this->orderInfor->order_status_id, $tag); ?>
					</td>
					<td class="left">
						<?php echo nl2br($this->orderInfor->comment); ?>
					</td>
					<?php
					if (EshopHelper::getConfigValue('delivery_date'))
					{
						?>
						<td class="left">
							<?php echo JHtml::date($this->orderInfor->delivery_date, EshopHelper::getConfigValue('date_format', 'm-d-Y'), null); ?>
						</td>
						<?php
					}
					?>
				</tr>
			</tbody>
		</table>
	</form>
	<div class="no_margin_left">
		<input type="button" value="<?php echo JText::_('ESHOP_BACK'); ?>" id="button-user-orderinfor" class="<?php echo $btnClass; ?> btn-primary <?php echo $pullRightClass; ?>">
	</div>
	<?php
}
?>
<script type="text/javascript">
	Eshop.jQuery(function($){
		$(document).ready(function(){
			$('#button-user-orderinfor').click(function(){
				var url = '<?php echo JRoute::_(EshopRoute::getViewRoute('customer') . '&layout=orders'); ?>';
				$(location).attr('href',url);
			});
		})
	});
</script>