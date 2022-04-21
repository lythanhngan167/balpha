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
<form action="index.php?option=com_eshop&view=quotes" method="post" name="adminForm" id="adminForm">
	<div id="j-main-container">
		<div id="filter-bar" class="btn-toolbar">
			<div class="filter-search btn-group pull-left">
				<label for="filter_search" class="element-invisible"><?php echo JText::_('ESHOP_FILTER_SEARCH_QUOTES_DESC');?></label>
				<input type="text" name="search" id="filter_search" placeholder="<?php echo JText::_('JSEARCH_FILTER'); ?>" value="<?php echo $this->escape($this->state->search); ?>" class="hasTooltip" title="<?php echo JHtml::tooltipText('ESHOP_SEARCH_QUOTES_DESC'); ?>" />
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
					<th class="text_left" width="15%">
						<?php echo JHtml::_('grid.sort',  JText::_('ESHOP_CUSTOMER'), 'a.name', $this->lists['order_Dir'], $this->lists['order'] ); ?>
					</th>
					<th class="text_left" width="15%">
						<?php echo JHtml::_('grid.sort',  JText::_('ESHOP_EMAIL'), 'a.email', $this->lists['order_Dir'], $this->lists['order'] ); ?>
					</th>
					<th class="text_left" width="15%">
						<?php echo JHtml::_('grid.sort',  JText::_('ESHOP_COMPANY'), 'a.company', $this->lists['order_Dir'], $this->lists['order'] ); ?>
					</th>
					<th class="text_left" width="15%">
						<?php echo JHtml::_('grid.sort',  JText::_('ESHOP_TELEPHONE'), 'a.telephone', $this->lists['order_Dir'], $this->lists['order'] ); ?>
					</th>
					<th class="text_center" width="10%">
						<?php echo JHtml::_('grid.sort',  JText::_('ESHOP_CREATED_DATE'), 'a.created_date', $this->lists['order_Dir'], $this->lists['order'] ); ?>
					</th>
					<th class="text_center" width="10%">
						<?php echo JHtml::_('grid.sort',  JText::_('ESHOP_MODIFIED_DATE'), 'a.modified_date', $this->lists['order_Dir'], $this->lists['order'] ); ?>
					</th>
					<th width="5%" class="text_center">
						<?php echo JHtml::_('grid.sort',  JText::_('ESHOP_ID'), 'a.id', $this->lists['order_Dir'], $this->lists['order'] ); ?>
					</th>
					<th width="15%" class="text_center">
						<?php echo JText::_('ESHOP_ACTION'); ?>
					</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="9">
						<?php echo $this->pagination->getListFooter(); ?>
					</td>
				</tr>
			</tfoot>
			<tbody>
			<?php
			$k = 0;
			$dateFormat = EshopHelper::getConfigValue('date_format', 'm-d-Y');
			for ($i=0, $n=count( $this->items ); $i < $n; $i++)
			{
				$row = &$this->items[$i];
				$link 	= JRoute::_('index.php?option=com_eshop&task=quote.edit&cid[]='. $row->id);
				$checked 	= JHtml::_('grid.id',   $i, $row->id );
				?>
				<tr class="<?php echo "row$k"; ?>">
					<td class="text_center">
						<?php echo $checked; ?>
					</td>
					<td class="text_left">
						<?php echo $row->name; ?>
					</td>
					<td class="text_left">
						<a href="mailto:<?php echo $row->email; ?>"><?php echo $row->email; ?></a>
					</td>
					<td class="text_left">
						<?php echo $row->company; ?>
					</td>
					<td class="text_left">
						<?php echo $row->telephone; ?>
					</td>
					<td class="text_center">
						<?php
						if ($row->created_date != $this->nullDate)
						{
							echo JHtml::_('date', $row->created_date, $dateFormat, null);
						}
						?>
					</td>
					<td class="text_center">
						<?php
						if ($row->modified_date != $this->nullDate)
						{
							echo JHtml::_('date', $row->modified_date, $dateFormat, null);
						}
						?>
					</td>
					<td class="text_center">
						<?php echo $row->id; ?>
					</td>
					<td class="text_center">
						<a href="<?php echo $link; ?>"><?php echo JText::_('ESHOP_VIEW'); ?></a>
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