<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Registration
 * @author     nganly <lythanhngan167@gmail.com>
 * @copyright  2020 nganly
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

$canEdit = JFactory::getUser()->authorise('core.edit', 'com_registration.' . $this->item->id);

if (!$canEdit && JFactory::getUser()->authorise('core.edit.own', 'com_registration' . $this->item->id))
{
	$canEdit = JFactory::getUser()->id == $this->item->created_by;
}
?>
<h3><?php echo $this->item->name; ?> - <?php echo $this->item->phone; ?></h3>
<div class="item_fields">

	<table class="table">


		<tr>
			<th><?php echo JText::_('COM_REGISTRATION_FORM_LBL_REGISTRATION_NAME'); ?></th>
			<td><?php echo $this->item->name; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_REGISTRATION_FORM_LBL_REGISTRATION_EMAIL'); ?></th>
			<td><?php echo $this->item->email; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_REGISTRATION_FORM_LBL_REGISTRATION_PHONE'); ?></th>
			<td><?php echo $this->item->phone; ?></td>
		</tr>

		<!-- <tr>
			<th><?php echo JText::_('COM_REGISTRATION_FORM_LBL_REGISTRATION_JOB'); ?></th>
			<td><?php echo $this->item->job; ?></td>
		</tr> -->

		<tr>
			<th><?php echo JText::_('COM_REGISTRATION_FORM_LBL_REGISTRATION_ADDRESS'); ?></th>
			<td><?php echo $this->item->address; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_REGISTRATION_FORM_LBL_REGISTRATION_NOTE'); ?></th>
			<td><?php echo nl2br($this->item->note); ?></td>
		</tr>

		<!-- <tr>
			<th><?php echo JText::_('COM_REGISTRATION_FORM_LBL_REGISTRATION_PROVINCE'); ?></th>
			<td><?php echo $this->item->province; ?></td>
		</tr> -->

		<tr>
			<th><?php echo JText::_('COM_REGISTRATION_FORM_LBL_REGISTRATION_STATUS'); ?></th>
			<td><?php echo $this->item->status; ?></td>
		</tr>

	</table>

</div>

<?php if($canEdit && $this->item->checked_out == 0): ?>

	<!-- <a class="btn" href="<?php echo JRoute::_('index.php?option=com_registration&task=registration.edit&id='.$this->item->id); ?>"><?php echo JText::_("COM_REGISTRATION_EDIT_ITEM"); ?></a> -->

<?php endif; ?>

<?php if (JFactory::getUser()->authorise('core.delete','com_registration.registration.'.$this->item->id)) : ?>

	<a class="btn btn-danger" href="#deleteModal" role="button" data-toggle="modal">
		<?php echo JText::_("COM_REGISTRATION_DELETE_ITEM"); ?>
	</a>

	<div id="deleteModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="deleteModal" aria-hidden="true">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h3><?php echo JText::_('COM_REGISTRATION_DELETE_ITEM'); ?></h3>
		</div>
		<div class="modal-body">
			<p><?php echo JText::sprintf('COM_REGISTRATION_DELETE_CONFIRM', $this->item->id); ?></p>
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal">Close</button>
			<a href="<?php echo JRoute::_('index.php?option=com_registration&task=registration.remove&id=' . $this->item->id, false, 2); ?>" class="btn btn-danger">
				<?php echo JText::_('COM_REGISTRATION_DELETE_ITEM'); ?>
			</a>
		</div>
	</div>

<?php endif; ?>
