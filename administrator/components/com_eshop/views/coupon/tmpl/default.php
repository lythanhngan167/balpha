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
EshopHelper::chosen(); 
?>
<script type="text/javascript">	
	Joomla.submitbutton = function(pressbutton)
	{
		var form = document.adminForm;
		if (pressbutton == 'coupon.cancel') {
			Joomla.submitform(pressbutton, form);
			return;				
		} else {
			//Validate the entered data before submitting
			if (form.coupon_name.value == '') {
				alert("<?php echo JText::_('ESHOP_ENTER_NAME'); ?>");
				form.coupon_name.focus();
				return;
			}
			if (form.coupon_start_date.value > form.coupon_end_date.value) {
				alert("<?php echo JText::_('ESHOP_DATE_VALIDATE'); ?>");
				form.coupon_start_date.focus();
				return;
			}
			Joomla.submitform(pressbutton, form);
		}
	}
</script>
<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data" class="form form-horizontal">
	<?php
	echo JHtml::_('bootstrap.startTabSet', 'coupon', array('active' => 'general-page'));
	echo JHtml::_('bootstrap.addTab', 'coupon', 'general-page', JText::_('ESHOP_GENERAL', true));
	?>
	<div class="control-group">
		<div class="control-label">
			<span class="required">*</span>
			<?php echo  JText::_('ESHOP_COUPON_NAME'); ?>
		</div>
		<div class="controls" style="margin-left:370px;">
			<input class="input-xlarge" type="text" name="coupon_name" id="coupon_name" maxlength="250" value="<?php echo $this->item->coupon_name; ?>" />
			<br />
			<small><?php echo JText::_('ESHOP_COUPON_NAME_HELP'); ?></small>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo  JText::_('ESHOP_CODE'); ?>
		</div>
		<div class="controls" style="margin-left:370px;">
			<input class="input-large" type="text" name="coupon_code" id="coupon_code" maxlength="250" value="<?php echo $this->item->coupon_code; ?>" />
			<br />
			<small><?php echo JText::_('ESHOP_CODE_HELP'); ?></small>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo  JText::_('ESHOP_TYPE'); ?>
		</div>
		<div class="controls" style="margin-left:370px;">
			<?php echo $this->lists['coupon_type']; ?>
			<br />
			<small><?php echo JText::_('ESHOP_TYPE_HELP'); ?></small>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo  JText::_('ESHOP_VALUE'); ?>
		</div>
		<div class="controls" style="margin-left:370px;">
			<input class="input-small" type="text" name="coupon_value" id="coupon_value" maxlength="250" value="<?php echo number_format($this->item->coupon_value, 2); ?>" />
			<br />
			<small><?php echo JText::_('ESHOP_VALUE_HELP'); ?></small>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo  JText::_('ESHOP_MIN_TOTAL'); ?>
		</div>
		<div class="controls" style="margin-left:370px;">
			<input class="input-small" type="text" name="coupon_min_total" id="coupon_min_total" maxlength="250" value="<?php echo number_format($this->item->coupon_min_total, 2); ?>" />
			<br />
			<small><?php echo JText::_('ESHOP_MIN_TOTAL_HELP'); ?></small>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo JText::_('ESHOP_SELECT_PRODUCTS'); ?>
		</div>
		<div class="controls" style="margin-left:370px;">
			<?php echo $this->lists['product_id']; ?>
			<br />
			<small><?php echo JText::_('ESHOP_SELECT_PRODUCTS_HELP'); ?></small>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo JText::_('ESHOP_SELECT_CATEGORIES'); ?>
		</div>
		<div class="controls" style="margin-left:370px;">
			<?php echo $this->lists['category_id']; ?>
			<br />
			<small><?php echo JText::_('ESHOP_SELECT_CATEGORIES_HELP'); ?></small>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo JText::_('ESHOP_SELECT_CUSTOMER_GROUPS'); ?>
		</div>
		<div class="controls" style="margin-left:370px;">
			<?php echo $this->lists['customergroup_id']; ?>
			<br />
			<small><?php echo JText::_('ESHOP_SELECT_CUSTOMER_GROUPS_HELP'); ?></small>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo  JText::_('ESHOP_START_DATE'); ?>
		</div>
		<div class="controls" style="margin-left:370px;">
			<?php echo JHtml::_('calendar', (($this->item->coupon_start_date == $this->nullDate) ||  !$this->item->coupon_start_date) ? '' : JHtml::_('date', $this->item->coupon_start_date, 'Y-m-d H:i', null), 'coupon_start_date', 'coupon_start_date', '%Y-%m-%d %H:%M', array('style' => 'width: 100px;', 'showTime' => true)); ?>
			<br />
			<small><?php echo JText::_('ESHOP_COUPON_START_DATE_HELP'); ?></small>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo  JText::_('ESHOP_END_DATE'); ?>
		</div>
		<div class="controls" style="margin-left:370px;">
			<?php echo JHtml::_('calendar', (($this->item->coupon_end_date == $this->nullDate) ||  !$this->item->coupon_end_date) ? '' : JHtml::_('date', $this->item->coupon_end_date, 'Y-m-d H:i', null), 'coupon_end_date', 'coupon_end_date', '%Y-%m-%d %H:%M', array('style' => 'width: 100px;', 'showTime' => true)); ?>
			<br />
			<small><?php echo JText::_('ESHOP_COUPON_END_DATE_HELP'); ?></small>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo JText::_('ESHOP_COUPON_SHIPPING'); ?>
		</div>
		<div class="controls" style="margin-left:370px;">
			<?php echo $this->lists['coupon_shipping']; ?>
			<br />
			<small><?php echo JText::_('ESHOP_COUPON_SHIPPING_HELP'); ?></small>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo  JText::_('ESHOP_COUPON_TIME'); ?>
		</div>
		<div class="controls" style="margin-left:370px;">
			<input class="input-small" type="text" name="coupon_times" id="coupon_times" maxlength="250" value="<?php echo $this->item->coupon_times; ?>" />
			<br />
			<small><?php echo JText::_('ESHOP_COUPON_TIME_HELP'); ?></small>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo  JText::_('ESHOP_COUPON_USED'); ?>
		</div>
		<div class="controls" style="margin-left:370px;">
			<input class="input-small" type="text" name="coupon_used" id="coupon_used" maxlength="250" value="<?php echo $this->item->coupon_used; ?>" />
			<br />
			<small><?php echo JText::_('ESHOP_COUPON_USED_HELP'); ?></small>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo  JText::_('ESHOP_COUPON_PER_CUSTOMER'); ?>
		</div>
		<div class="controls" style="margin-left:370px;">
			<input class="input-small" type="text" name="coupon_per_customer" id="coupon_per_customer" maxlength="250" value="<?php echo $this->item->coupon_per_customer; ?>" />
			<br />
			<small><?php echo JText::_('ESHOP_COUPON_PER_CUSTOMER_HELP'); ?></small>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo JText::_('ESHOP_PUBLISHED'); ?>
		</div>
		<div class="controls" style="margin-left:370px;">
			<?php echo $this->lists['published']; ?>
		</div>
	</div>
	<?php
	echo JHtml::_('bootstrap.endTab');
	echo JHtml::_('bootstrap.addTab', 'coupon', 'history-page', JText::_('ESHOP_COUPON_HISTORY', true));
	?>
	<table class="adminlist" style="text-align: center;">
		<thead>
			<tr>
				<th class="title" width="10%"><?php echo JText::_('ESHOP_ORDER_ID')?></th>
				<th class="title" width="30%"><?php echo JText::_('ESHOP_AMOUNT')?></th>
				<th class="title" width="20%"><?php echo JText::_('ESHOP_CREATED_DATE')?></th>
			</tr>
		</thead>
		<tbody>
			<?php
			$couponHistories = $this->couponHistories;
			if (count($couponHistories) == 0)
			{
				?>
				<tr>
					<td colspan="3" style="text-align: center;">
						<?php echo JText::_('ESHOP_NO_RESULTS'); ?>
					</td>
				</tr>
				<?php
			}
			else
			{
				for ($i = 0; $i< count($couponHistories); $i++)
				{
					$couponHistory = $couponHistories[$i];
					?>
					<tr>
						<td align="center">
							<?php echo $couponHistory->order_id; ?>
						</td>
						<td align="center">
							<?php echo number_format($couponHistory->amount, 2); ?>
						</td>
						<td align="center">
							<?php echo JHtml::_('date', $couponHistory->created_date, EshopHelper::getConfigValue('date_format', 'm-d-Y'), null); ?>
						</td>
					</tr>
					<?php
				}
			}
			?>
		</tbody>
	</table>
	<?php
	echo JHtml::_('bootstrap.endTab');
	echo JHtml::_('bootstrap.endTabSet');
	?>
	<?php echo JHtml::_( 'form.token' ); ?>
	<input type="hidden" name="option" value="com_eshop" />
	<input type="hidden" name="cid[]" value="<?php echo intval($this->item->id); ?>" />
	<input type="hidden" name="task" value="" />	
</form>