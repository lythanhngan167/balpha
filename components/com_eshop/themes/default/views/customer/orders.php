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

$language = JFactory::getLanguage();
$tag = $language->getTag();
$bootstrapHelper        = $this->bootstrapHelper;
$rowFluidClass          = $bootstrapHelper->getClassMapping('row-fluid');
$span2Class             = $bootstrapHelper->getClassMapping('span2');
$pullLeftClass          = $bootstrapHelper->getClassMapping('pull-left');
$btnClass				= $bootstrapHelper->getClassMapping('btn');

if (!$tag)
{
	$tag = 'en-GB';
}

if (isset($this->warning))
{
	?>
	<div class="warning"><?php echo $this->warning; ?></div>
	<?php
}
?>
<h1><?php echo JText::_('ESHOP_ORDER_HISTORY'); ?></h1><br />
<?php
if (!count($this->orders))
{
	?>
	<div class="no-content"><?php echo JText::_('ESHOP_NO_ORDERS'); ?></div>
	<?php
}
else
{
	?>
	<div class="<?php echo $rowFluidClass; ?>">
		<form id="adminForm" class="order-list">
			<?php
			foreach ($this->orders as $order)
			{
				?>
				<div class="order-id"><b><?php echo JText::_('ESHOP_ORDER_ID'); ?>: </b>#<?php echo $order->id; ?></div>
				<div class="order-status"><b><?php echo JText::_('ESHOP_STATUS'); ?>: </b><?php echo EshopHelper::getOrderStatusName($order->order_status_id, $tag); ?></div>
				<div class="order-content">
					<div>
						<b><?php echo JText::_('ESHOP_DATE_ADDED'); ?>: </b><?php echo JHtml::date($order->created_date, EshopHelper::getConfigValue('date_format', 'm-d-Y'), null); ?><br />
						<b><?php echo JText::_('ESHOP_PRODUCT'); ?>: </b><?php echo EshopHelper::getNumberProduct($order->id); ?>
					</div>
					<div>
						<b><?php echo JText::_('ESHOP_CUSTOMER'); ?>: </b><?php echo $order->firstname . ' ' . $order->lastname; ?><br />
						<b><?php echo JText::_('ESHOP_TOTAL'); ?>: </b> <?php echo $order->total; ?>
					</div>
					<div class="order-info" align="right">
						<a href="<?php echo JRoute::_(EshopRoute::getViewRoute('customer').'&layout=order&order_id='.(int)$order->id); ?>"><?php echo JText::_('ESHOP_VIEW'); ?></a>
						<?php
						if (EshopHelper::getConfigValue('allow_re_order'))
						{
							?>
							&nbsp;|&nbsp;
							<a href="<?php echo JRoute::_('index.php?option=com_eshop&task=cart.reOrder&order_id='.(int)$order->id); ?>"><?php echo JText::_('ESHOP_RE_ORDER'); ?></a>
							<?php
						}
						if (EshopHelper::getConfigValue('invoice_enable') && $order->order_status_id == EshopHelper::getConfigValue('complete_status_id'))
						{
							?>
							&nbsp;|&nbsp;
							<a href="<?php echo JRoute::_('index.php?option=com_eshop&task=customer.downloadInvoice&order_id='.(int)$order->id); ?>"><?php echo JText::_('ESHOP_DOWNLOAD_INVOICE'); ?></a>
							<?php
						}
						?>
					</div>
				</div>
				<?php
			}
			?>
		</form>
	</div>
	<?php
}
?>
<div class="<?php echo $rowFluidClass; ?>">
	<div class="<?php echo $span2Class; ?>">
		<input type="button" value="<?php echo JText::_('ESHOP_BACK'); ?>" id="button-back-order" class="<?php echo $btnClass; ?> btn-primary <?php echo $pullLeftClass; ?>" />
	</div>
</div>
<script type="text/javascript">
	Eshop.jQuery(function($){
		$(document).ready(function(){
			$('#button-back-order').click(function() {
				var url = '<?php echo JRoute::_(EshopRoute::getViewRoute('customer')); ?>';
				$(location).attr('href', url);
			});
		})
	});
</script>