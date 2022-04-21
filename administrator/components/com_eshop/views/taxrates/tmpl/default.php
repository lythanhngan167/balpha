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
JHtml::_('formbehavior.chosen', 'select');
?>
<form action="index.php?option=com_eshop&view=taxrates" method="post" name="adminForm" id="adminForm">
	<div id="j-main-container">
		<div id="filter-bar" class="btn-toolbar">
			<div class="filter-search btn-group pull-left">
				<label for="filter_search" class="element-invisible"><?php echo JText::_('ESHOP_FILTER_SEARCH_TAXRATES_DESC');?></label>
				<input type="text" name="search" id="filter_search" placeholder="<?php echo JText::_('JSEARCH_FILTER'); ?>" value="<?php echo $this->escape($this->state->search); ?>" class="hasTooltip" title="<?php echo JHtml::tooltipText('ESHOP_SEARCH_TAXRATES_DESC'); ?>" />
			</div>
			<div class="btn-group pull-left">
				<button type="submit" class="btn hasTooltip" title="<?php echo JHtml::tooltipText('JSEARCH_FILTER_SUBMIT'); ?>"><span class="icon-search"></span></button>
				<button type="button" class="btn hasTooltip" title="<?php echo JHtml::tooltipText('JSEARCH_FILTER_CLEAR'); ?>" onclick="document.getElementById('filter_search').value='';this.form.submit();"><span class="icon-remove"></span></button>
			</div>
			<div class="btn-group pull-right hidden-phone">
				<?php
				echo $this->lists['filter_state'];
				echo $this->pagination->getLimitBox();
				?>
			</div>
		</div>
		<div class="clearfix"></div>
		<table class="adminlist table table-striped">
			<thead>
			<tr>
				<th width="2%" class="text_center">
					<?php echo JHtml::_('grid.checkall'); ?>
				</th>
				<th width="1%" class="text_center" style="min-width:55px">
					<?php echo JHtml::_('grid.sort', JText::_('JSTATUS'), 'a.published', $this->lists['order_Dir'], $this->lists['order'] ); ?>
				</th>
				<th class="text_left" width="20%">
					<?php echo JHtml::_('grid.sort',  JText::_('ESHOP_TAX_NAME'), 'a.tax_name', $this->lists['order_Dir'], $this->lists['order'] ); ?>
				</th>
				<th class="text_center" width="10%">
					<?php echo JHtml::_('grid.sort',  JText::_('ESHOP_TAX_RATE'), 'a.tax_rate', $this->lists['order_Dir'], $this->lists['order'] ); ?>
				</th>
				<th class="text_center" width="10%">
					<?php echo JHtml::_('grid.sort',  JText::_('ESHOP_TAX_TYPE'), 'a.tax_type', $this->lists['order_Dir'], $this->lists['order'] ); ?>
				</th>
				<th class="text_center" width="15%">
					<?php echo JHtml::_('grid.sort',  JText::_('ESHOP_GEO_ZONE'), 'b.geozone_name', $this->lists['order_Dir'], $this->lists['order'] ); ?>
				</th>
				<th class="text_center" width="10%">
					<?php echo JHtml::_('grid.sort',  JText::_('ESHOP_DATE_ADDED'), 'a.created_date', $this->lists['order_Dir'], $this->lists['order'] ); ?>
				</th>
				<th class="text_center" width="10%">
					<?php echo JHtml::_('grid.sort',  JText::_('ESHOP_DATE_MODIFIED'), 'a.modified_date', $this->lists['order_Dir'], $this->lists['order'] ); ?>
				</th>
				<th width="5%" class="text_center">
					<?php echo JHtml::_('grid.sort',  JText::_('ESHOP_PUBLISHED'), 'a.published', $this->lists['order_Dir'], $this->lists['order'] ); ?>
				</th>
				<th width="5%" class="text_center">
					<?php echo JHtml::_('grid.sort',  JText::_('ESHOP_ID'), 'a.id', $this->lists['order_Dir'], $this->lists['order'] ); ?>
				</th>
			</tr>
			</thead>
			<tfoot>
			<tr>
				<td colspan="10">
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
				$link 	= JRoute::_( 'index.php?option=com_eshop&task=taxrate.edit&cid[]='. $row->id);
				$checked 	= JHtml::_('grid.id',   $i, $row->id );
				$published 	= JHtml::_('jgrid.published', $row->published, $i, 'taxrate.');
				?>
				<tr class="<?php echo "row$k"; ?>">
					<td class="text_center">
						<?php echo $checked; ?>
					</td>
					<td class="text_center">
						<div class="btn-group">
							<?php
							echo $published;
							echo $this->addDropdownList(JText::_('ESHOP_COPY'), 'copy', $i, 'taxrate.copy');
							echo $this->addDropdownList(JText::_('ESHOP_DELETE'), 'trash', $i, 'taxrate.remove');
							echo $this->renderDropdownList($this->escape($row->tax_name));
							?>
						</div>
					</td>
					<td>
						<a href="<?php echo $link; ?>"><?php echo $row->tax_name; ?></a>
					</td>
					<td class="text_center">
						<?php echo number_format($row->tax_rate, 2); ?>
					</td>
					<td class="text_center">
						<?php
						if ($row->tax_type=='P')
						{
							echo JText::_('ESHOP_PERCENTAGE');
						}
						elseif ($row->tax_type=='F')
						{
							echo JText::_('ESHOP_FIXED_AMOUNT');
						}
						?>
					</td>
					<td class="text_center">
						<?php echo $row->geozone_name; ?>
					</td>
					<td class="text_center">
						<?php echo JHtml::_('date', $row->created_date, EshopHelper::getConfigValue('date_format', 'm-d-Y'), null); ?>
					</td>
					<td class="text_center">
						<?php echo JHtml::_('date', $row->modified_date, EshopHelper::getConfigValue('date_format', 'm-d-Y'), null); ?>
					</td>
					<td class="text_center">
						<?php echo $published; ?>
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