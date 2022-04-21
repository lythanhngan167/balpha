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
$ordering = ($this->lists['order'] == 'a.ordering');

JHtml::_('behavior.tooltip');
JHtml::_('behavior.modal');
?>
<form action="index.php?option=com_eshop&view=notify" method="post" name="adminForm" id="adminForm">
	<table width="100%">
		<tr>
			<td align="left">
				<?php echo JText::_( 'ESHOP_FILTER' ); ?>:
				<input type="text" name="search" id="search" value="<?php echo $this->state->search; ?>" class="text_area search-query" onchange="document.adminForm.submit();" />		
				<button onclick="this.form.submit();" class="btn"><?php echo JText::_( 'ESHOP_GO' ); ?></button>
				<button onclick="document.getElementById('search').value='';this.form.submit();" class="btn"><?php echo JText::_( 'ESHOP_RESET' ); ?></button>		
			</td>			
		</tr>
	</table>
	<div id="editcell">
		<table class="adminlist table table-striped">
			<thead>
				<tr>
					<th width="2%" class="text_center">
						<?php echo JText::_( 'ESHOP_NUM' ); ?>
					</th>
					<th class="text_left" width="40%">
						<?php echo JHtml::_('grid.sort',  JText::_('ESHOP_NAME'), 'b.product_name', $this->lists['order_Dir'], $this->lists['order'] ); ?>				
					</th>											
					<th width="20%" class="text_left">
						<?php echo JHtml::_('grid.sort',  JText::_('ESHOP_EMAIL'), 'a.notify_email', $this->lists['order_Dir'], $this->lists['order'] ); ?>
					</th>
					<th width="10%" class="text_center">
						<?php echo JHtml::_('grid.sort',  JText::_('ESHOP_NOTIFY_SENT'), 'a.sent_email', $this->lists['order_Dir'], $this->lists['order'] ); ?>
					</th>
					<th width="10%" class="text_center">
						<?php echo JHtml::_('grid.sort',  JText::_('ESHOP_NOTIFY_SENT_DATE'), 'a.sent_date', $this->lists['order_Dir'], $this->lists['order'] ); ?>
					</th>
					<th width="5%" class="text_center">
						<?php echo JHtml::_('grid.sort',  JText::_('ESHOP_ID'), 'a.id', $this->lists['order_Dir'], $this->lists['order'] ); ?>
					</th>													
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="6">
						<?php echo $this->pagination->getListFooter(); ?>
					</td>
				</tr>
			</tfoot>
			<tbody>
			<?php
			$k = 0;
			for ($i=0, $n=count( $this->items ); $i < $n; $i++)
			{
				$row = &$this->items[$i];
				$link 	= JRoute::_( 'index.php?option=com_eshop&task=product.edit&cid[]='. $row->product_id);
				?>
				<tr class="<?php echo "row$k"; ?>">
					<td class="text_center">
						<?php echo $this->pagination->getRowOffset( $i ); ?>
					</td>
					<td>																			
						<a href="<?php echo $link; ?>"><?php echo $row->product_name; ?></a>				
					</td>			
					<td class="text_left">
						<a href="mailto:<?php echo $row->notify_email; ?>"><?php echo $row->notify_email; ?></a>
					</td>
					<td class="text_center" >
					   <?php echo $row->sent_email? '<span class="icon-publish"></span>':'<span class="icon-unpublish"></span>'; ?>
					</td>
					<td class="text_center">
						<?php echo $row->sent_email ? JHtml::_('date', $row->sent_date, EshopHelper::getConfigValue('date_format', 'm-d-Y'), null) : ''; ?>
					</td>
					<td class="text_center" width="5%">
						<?php echo $row->id; ?>
					</td>
				</tr>		
				<?php
				$k = 1 - $k;
			}
			?>
			</tbody>
		</table>
	</div>
	<input type="hidden" name="option" value="com_eshop" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />	
	<?php echo JHtml::_( 'form.token' ); ?>			
</form>