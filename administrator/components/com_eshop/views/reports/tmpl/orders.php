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
JToolBarHelper::title(JText::_('ESHOP_ORDERS_REPORT'), 'generic.png');
JToolBarHelper::custom('exports.process', 'download', 'download', Jtext::_('ESHOP_EXPORTS'), false);
JToolBarHelper::custom('order.downloadInvoice', 'print', 'print', JText::_('ESHOP_DOWNLOAD_INVOICE'), false);
JToolBarHelper::cancel('reports.cancel');
$input = JFactory::getApplication()->input;
?>
<form action="index.php?option=com_eshop&view=reports&layout=orders" method="post" name="adminForm" id="adminForm">
	<table width="100%">
		<tr>
			<td align="left" width="5%">
				<?php echo JText::_('ESHOP_START_DATE')?>:
			</td>
			<td align="left" width="15%">
				<?php echo JHtml::_('calendar', $input->getString('date_start', date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'))), 'date_start', 'date_start', '%Y-%m-%d', array('class' => 'input-small')); ?>
			</td>
			<td align="left" width="5%">
				<?php echo JText::_('ESHOP_END_DATE')?>:
			</td>
			<td align="left" width="15%">
				<?php echo JHtml::_('calendar', $input->getString('date_end', date('Y-m-d')), 'date_end', 'date_end', '%Y-%m-%d', array('class' => 'input-small')); ?>
			</td>
			<td>	
				<?php echo JText::_('ESHOP_GROUP_BY'); ?>:&nbsp;
				<?php echo $this->lists['group_by']; ?>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo JText::_('ESHOP_ORDERSTATUS'); ?>:&nbsp;
				<?php echo $this->lists['order_status_id']; ?>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button onclick="this.form.submit();" class="btn"><?php echo JText::_( 'ESHOP_GO' ); ?></button>
			</td>
		</tr>
	</table>
	<table class="adminlist table table-striped">
		<thead>
			<tr>
				<th width="20%" class="text_left"><?php echo JText::_('ESHOP_START_DATE'); ?></th>
				<th width="20%" class="text_left"><?php echo JText::_('ESHOP_END_DATE'); ?></th>
				<th width="10%" class="text_center"><?php echo JText::_('ESHOP_NUMBER_ORDERS'); ?></th>
				<th width="10%" class="text_center"><?php echo JText::_('ESHOP_NUMBER_PRODUCTS'); ?></th>
				<th width="10%" class="text_center"><?php echo JText::_('ESHOP_UNIT_PRICE'); ?></th>
				<th width="10%" class="text_center"><?php echo JText::_('ESHOP_SHIPPING'); ?></th>
				<th width="10%" class="text_center"><?php echo JText::_('ESHOP_TAX'); ?></th>
				<th width="10%" class="text_center"><?php echo JText::_('ESHOP_TOTAL'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php
			$k = 0;
			for ($i=0, $n=count( $this->items ); $i < $n; $i++)
			{
				$row = &$this->items[$i];
				?>
				<tr class="<?php echo "row$k"; ?>">
					<td class="text_left">
						<?php echo JHtml::_('date', $row->date_start, EshopHelper::getConfigValue('date_format', 'm-d-Y'), null); ?>
					</td>																			
					<td class="text_left">
						<?php echo JHtml::_('date', $row->date_end, EshopHelper::getConfigValue('date_format', 'm-d-Y'), null); ?>
					</td>
					<td class="text_center">
						<?php echo $row->orders; ?>
					</td>
					<td class="text_center">
						<?php echo $row->products; ?>
					</td>
					<td class="text_center">
						<?php echo $this->currency->format($row->sub_total ? $row->sub_total : 0, EshopHelper::getConfigValue('default_currency_code')); ?>
					</td>
					<td class="text_center">
						<?php echo $this->currency->format($row->shipping ? $row->shipping : 0, EshopHelper::getConfigValue('default_currency_code')); ?>
					</td>
					<td class="text_center">
						<?php echo $this->currency->format($row->tax ? $row->tax : 0, EshopHelper::getConfigValue('default_currency_code')); ?>
					</td>
					<td class="text_center">
						<?php echo $this->currency->format($row->total ? $row->total : 0, EshopHelper::getConfigValue('default_currency_code')); ?>
					</td>
				</tr>
				<?php
				$k = 1 - $k;
			}
			?>
		</tbody>
	</table>
	<?php echo JHtml::_( 'form.token' ); ?>
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="export_type" value="orders" />
	<input type="hidden" name="from_exports" value="1" />
</form>