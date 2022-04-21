<?php
/**
 * @version        3.1.0
 * @package        Joomla
 * @subpackage     EShop
 * @author         Giang Dinh Truong
 * @copyright      Copyright (C) 2012 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');
?>
<form action="index.php?option=com_eshop&view=discounts" method="post" name="adminForm" id="adminForm">
	<div id="j-main-container">
		<table class="adminlist table table-striped" id="recordsList">
			<thead>
				<tr>
					<th width="2%" class="text_center">
						<?php echo JHtml::_('grid.checkall'); ?>
					</th>
					<th width="10%" class="text_center" style="min-width:55px">
						<?php echo JHtml::_('grid.sort', JText::_('JSTATUS'), 'a.published', $this->lists['order_Dir'], $this->lists['order'] ); ?>
					</th>
					<th class="text_left" width="30%">
						<?php echo JHtml::_('grid.sort',  JText::_('ESHOP_DISCOUNT_VALUE'), 'a.discount_value', $this->lists['order_Dir'], $this->lists['order'] ); ?>				
					</th>
					<th class="text_left" width="30%">
						<?php echo JHtml::_('grid.sort',  JText::_('ESHOP_DISCOUNT_TYPE'), 'a.discount_value', $this->lists['order_Dir'], $this->lists['order'] ); ?>				
					</th>
					<th width="10%" class="text_center">
						<?php echo JHtml::_('grid.sort',  JText::_('ESHOP_PUBLISHED'), 'a.published', $this->lists['order_Dir'], $this->lists['order'] ); ?>
					</th>
					<th class="text_center" width="10%" >
						<?php echo JHtml::_('grid.sort',  JText::_('ESHOP_START_DATE'), 'a.discount_start_date', $this->lists['order_Dir'], $this->lists['order'] ); ?>				
					</th>
					<th class="text_center" width="10%">
						<?php echo JHtml::_('grid.sort',  JText::_('ESHOP_END_DATE'), 'a.discount_end_date', $this->lists['order_Dir'], $this->lists['order'] ); ?>				
					</th>
					<th width="5%" class="text_center">
						<?php echo JHtml::_('grid.sort',  JText::_('ESHOP_ID'), 'a.id', $this->lists['order_Dir'], $this->lists['order'] ); ?>
					</th>													
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="8">
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
				$link 	= JRoute::_( 'index.php?option=com_eshop&task=discount.edit&cid[]='. $row->id);
				$checked 	= JHtml::_('grid.id',   $i, $row->id );
				$published 	= JHtml::_('jgrid.published', $row->published, $i, 'discount.');
				?>
				<tr class="<?php echo "row$k"; ?>">
					<td class="text_center">
						<?php echo $checked; ?>
					</td>
					<td class="text_center">
						<div class="btn-group">
							<?php
							echo $published;
							echo $this->addDropdownList(JText::_('ESHOP_COPY'), 'copy', $i, 'discount.copy');
							echo $this->addDropdownList(JText::_('ESHOP_DELETE'), 'trash', $i, 'discount.remove');
							echo $this->renderDropdownList($this->escape($row->discount_value));
							?>
						</div>
					</td>
					<td>
						<a href="<?php echo $link; ?>"><?php echo number_format($row->discount_value, 2); ?></a>
					</td>
					<td>
						<?php
							if ($row->discount_type == 'P')
							{
								echo JText::_('ESHOP_PERCENTAGE');
							}
							else
							{
								echo JText::_('ESHOP_FIXED_AMOUNT');
							}
						?>
					</td>
					<td class="text_center">
							<?php echo $published; ?>
					</td>
					<td class="text_center">
						<?php 
							if ($row->discount_start_date != $this->nullDate)
							{
								echo JHtml::_('date', $row->discount_start_date, EshopHelper::getConfigValue('date_format', 'm-d-Y'), null);
							}
						?>				
					</td>
					<td class="text_center">
						<?php 
							if ($row->discount_end_date != $this->nullDate)
							{
								echo JHtml::_('date', $row->discount_end_date, EshopHelper::getConfigValue('date_format', 'm-d-Y'), null);
							}
						?>								
					</td>
					<td class="text_center">
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
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />	
	<?php echo JHtml::_( 'form.token' ); ?>			
</form>