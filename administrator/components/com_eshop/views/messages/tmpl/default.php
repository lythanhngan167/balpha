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
<form action="index.php?option=com_eshop&view=messages" method="post" name="adminForm" id="adminForm">
	<div id="j-main-container">
		<table class="adminlist table table-striped">
			<thead>
				<tr>
					<th width="2%" class="text_center">
						<?php echo JHtml::_('grid.checkall'); ?>
					</th>
					<th class="text_left" width="40%">
						<?php echo JHtml::_('grid.sort',  JText::_('ESHOP_TITLE'), 'message_title', $this->lists['order_Dir'], $this->lists['order'] ); ?>
					</th>	
					<th class="text_left" width="40%">
						<?php echo JHtml::_('grid.sort',  JText::_('ESHOP_NAME'), 'message_name', $this->lists['order_Dir'], $this->lists['order'] ); ?>				
					</th>
					<th width="5%" class="text_center">
						<?php echo JHtml::_('grid.sort',  JText::_('ESHOP_ID'), 'id', $this->lists['order_Dir'], $this->lists['order'] ); ?>
					</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="4">
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
				$link 	= JRoute::_( 'index.php?option=com_eshop&task=message.edit&cid[]='. $row->id);
				$checked 	= JHtml::_('grid.id',   $i, $row->id );
				?>
				<tr class="<?php echo "row$k"; ?>">
					<td class="text_center">
						<?php echo $checked; ?>
					</td>	
					<td>
						<a href="<?php echo $link; ?>"><?php echo $row->message_title; ?></a>				
					</td>
					<td>
						<?php echo $row->message_name; ?>
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