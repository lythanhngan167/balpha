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
		if (pressbutton == 'voucher.cancel') {
			Joomla.submitform(pressbutton, form);
			return;				
		} else {
			//Validate the entered data before submitting
			if (form.voucher_code.value == '') {
				alert("<?php echo JText::_('ESHOP_ENTER_VOUCHER_CODE'); ?>");
				form.voucher_code.focus();
				return;
			}
			if (form.voucher_start_date.value > form.voucher_end_date.value) {
				alert("<?php echo JText::_('ESHOP_DATE_VALIDATE'); ?>");
				form.voucher_start_date.focus();
				return;
			}
			Joomla.submitform(pressbutton, form);
		}
	}
</script>
<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data" class="form form-horizontal">
	<?php
	echo JHtml::_('bootstrap.startTabSet', 'voucher', array('active' => 'general-page'));
	echo JHtml::_('bootstrap.addTab', 'voucher', 'general-page', JText::_('ESHOP_GENERAL', true));
	?>
	<div class="control-group">
		<div class="control-label">
			<?php echo  JText::_('ESHOP_CODE'); ?>
		</div>
		<div class="controls">
			<input class="input-large" type="text" name="voucher_code" id="voucher_code" maxlength="250" value="<?php echo $this->item->voucher_code; ?>" />
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo  JText::_('ESHOP_AMOUNT'); ?>
		</div>
		<div class="controls">
			<input class="input-small" type="text" name="voucher_amount" id="voucher_amount" maxlength="250" value="<?php echo number_format($this->item->voucher_amount, 2); ?>" />
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo  JText::_('ESHOP_START_DATE'); ?>
		</div>
		<div class="controls" style="margin-left:370px;">
			<?php echo JHtml::_('calendar', (($this->item->voucher_start_date == $this->nullDate) ||  !$this->item->voucher_start_date) ? '' : JHtml::_('date', $this->item->voucher_start_date, 'Y-m-d H:i', null), 'voucher_start_date', 'voucher_start_date', '%Y-%m-%d %H:%M', array('style' => 'width: 100px;', 'showTime' => true)); ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo  JText::_('ESHOP_END_DATE'); ?>
		</div>
		<div class="controls" style="margin-left:370px;">
			<?php echo JHtml::_('calendar', (($this->item->voucher_end_date == $this->nullDate) ||  !$this->item->voucher_end_date) ? '' : JHtml::_('date', $this->item->voucher_end_date, 'Y-m-d H:i', null), 'voucher_end_date', 'voucher_end_date', '%Y-%m-%d %H:%M', array('style' => 'width: 100px;', 'showTime' => true)); ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo JText::_('ESHOP_PUBLISHED'); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['published']; ?>
		</div>
	</div>
	<?php
	echo JHtml::_('bootstrap.endTab');
	echo JHtml::_('bootstrap.addTab', 'voucher', 'history-page', JText::_('ESHOP_VOUCHER_HISTORY', true));
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
			$voucherHistories = $this->voucherHistories;
			if (count($voucherHistories) == 0)
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
				for ($i = 0; $i< count($voucherHistories); $i++)
				{
					$voucherHistory = $voucherHistories[$i];
					?>
					<tr>
						<td align="center">
							<?php echo $voucherHistory->order_id; ?>
						</td>
						<td align="center">
							<?php echo number_format($voucherHistory->amount, 2); ?>
						</td>
						<td align="center">
							<?php echo JHtml::_('date', $voucherHistory->created_date, EshopHelper::getConfigValue('date_format', 'm-d-Y'), null); ?>
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