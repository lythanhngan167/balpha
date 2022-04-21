<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Transaction_history
 * @author     nganly <lythanhngan167@gmail.com>
 * @copyright  2019 nganly
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

$canEdit = JFactory::getUser()->authorise('core.edit', 'com_transaction_history.' . $this->item->id);

if (!$canEdit && JFactory::getUser()->authorise('core.edit.own', 'com_transaction_history' . $this->item->id))
{
	$canEdit = JFactory::getUser()->id == $this->item->created_by;
}
?>
<h3>Chi tiết <?php echo $this->params->get('page_title'); ?> #<?php echo $this->item->id; ?></h3>
<div class="item_fields">

	<table class="table">


		<tr>
			<th><?php echo JText::_('COM_TRANSACTION_HISTORY_FORM_LBL_TRANSACTIONHISTORY_TITLE'); ?></th>
			<td><?php echo $this->item->title; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_TRANSACTION_HISTORY_FORM_LBL_TRANSACTIONHISTORY_AMOUNT'); ?></th>
			<td>
			  <span class="price"><?php echo number_format($this->item->amount,0,".","."); ?></span></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_TRANSACTION_HISTORY_FORM_LBL_TRANSACTIONHISTORY_CREATED_DATE'); ?></th>
			<td><?php echo $this->item->created_date; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_TRANSACTION_HISTORY_FORM_LBL_TRANSACTIONHISTORY_TYPE_TRANSACTION'); ?></th>
			<td><?php echo $this->item->type_transaction; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_TRANSACTION_HISTORY_FORM_LBL_TRANSACTIONHISTORY_STATUS'); ?></th>
			<td><?php echo $this->item->status; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_TRANSACTION_HISTORY_FORM_LBL_TRANSACTIONHISTORY_REFERENCE_ID'); ?></th>
			<td><?php echo $this->item->reference_id; ?></td>
		</tr>

	</table>

</div>

<?php if($canEdit && $this->item->checked_out == 0): ?>

	<!-- <a class="btn" href="<?php echo JRoute::_('index.php?option=com_transaction_history&task=transactionhistory.edit&id='.$this->item->id); ?>"><?php echo JText::_("COM_TRANSACTION_HISTORY_EDIT_ITEM"); ?></a> -->

<?php endif; ?>

<?php if (JFactory::getUser()->authorise('core.delete','com_transaction_history.transactionhistory.'.$this->item->id)) : ?>

	<a class="btn btn-danger" href="#deleteModal" role="button" data-toggle="modal">
		<?php echo JText::_("COM_TRANSACTION_HISTORY_DELETE_ITEM"); ?>
	</a>

	<div id="deleteModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="deleteModal" aria-hidden="true">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h3><?php echo JText::_('COM_TRANSACTION_HISTORY_DELETE_ITEM'); ?></h3>
		</div>
		<div class="modal-body">
			<p><?php echo JText::sprintf('COM_TRANSACTION_HISTORY_DELETE_CONFIRM', $this->item->id); ?></p>
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal">Close</button>
			<a href="<?php echo JRoute::_('index.php?option=com_transaction_history&task=transactionhistory.remove&id=' . $this->item->id, false, 2); ?>" class="btn btn-danger">
				<?php echo JText::_('COM_TRANSACTION_HISTORY_DELETE_ITEM'); ?>
			</a>
		</div>
	</div>

<?php endif; ?>
