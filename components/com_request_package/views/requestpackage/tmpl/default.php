<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Request_package
 * @author     nganly <lythanhngan167@gmail.com>
 * @copyright  2020 nganly
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

$canEdit = JFactory::getUser()->authorise('core.edit', 'com_request_package.' . $this->item->id);

if (!$canEdit && JFactory::getUser()->authorise('core.edit.own', 'com_request_package' . $this->item->id))
{
	$canEdit = JFactory::getUser()->id == $this->item->created_by;
}
?>

<div class="item_fields">

	<table class="table">
		

		<tr>
			<th><?php echo JText::_('COM_REQUEST_PACKAGE_FORM_LBL_REQUESTPACKAGE_NAME'); ?></th>
			<td><?php echo $this->item->name; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_REQUEST_PACKAGE_FORM_LBL_REQUESTPACKAGE_EMAIL'); ?></th>
			<td><?php echo $this->item->email; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_REQUEST_PACKAGE_FORM_LBL_REQUESTPACKAGE_PHONE'); ?></th>
			<td><?php echo $this->item->phone; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_REQUEST_PACKAGE_FORM_LBL_REQUESTPACKAGE_JOB'); ?></th>
			<td><?php echo $this->item->job; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_REQUEST_PACKAGE_FORM_LBL_REQUESTPACKAGE_ADDRESS'); ?></th>
			<td><?php echo $this->item->address; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_REQUEST_PACKAGE_FORM_LBL_REQUESTPACKAGE_NOTE'); ?></th>
			<td><?php echo nl2br($this->item->note); ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_REQUEST_PACKAGE_FORM_LBL_REQUESTPACKAGE_PROVINCE'); ?></th>
			<td><?php echo $this->item->province; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_REQUEST_PACKAGE_FORM_LBL_REQUESTPACKAGE_STATUS'); ?></th>
			<td><?php echo $this->item->status; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_REQUEST_PACKAGE_FORM_LBL_REQUESTPACKAGE_COMPANY'); ?></th>
			<td><?php echo $this->item->company; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_REQUEST_PACKAGE_FORM_LBL_REQUESTPACKAGE_SERVICES'); ?></th>
			<td><?php echo $this->item->services; ?></td>
		</tr>

	</table>

</div>

<?php if($canEdit && $this->item->checked_out == 0): ?>

	<a class="btn" href="<?php echo JRoute::_('index.php?option=com_request_package&task=requestpackage.edit&id='.$this->item->id); ?>"><?php echo JText::_("COM_REQUEST_PACKAGE_EDIT_ITEM"); ?></a>

<?php endif; ?>

<?php if (JFactory::getUser()->authorise('core.delete','com_request_package.requestpackage.'.$this->item->id)) : ?>

	<a class="btn btn-danger" href="#deleteModal" role="button" data-toggle="modal">
		<?php echo JText::_("COM_REQUEST_PACKAGE_DELETE_ITEM"); ?>
	</a>

	<div id="deleteModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="deleteModal" aria-hidden="true">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h3><?php echo JText::_('COM_REQUEST_PACKAGE_DELETE_ITEM'); ?></h3>
		</div>
		<div class="modal-body">
			<p><?php echo JText::sprintf('COM_REQUEST_PACKAGE_DELETE_CONFIRM', $this->item->id); ?></p>
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal">Close</button>
			<a href="<?php echo JRoute::_('index.php?option=com_request_package&task=requestpackage.remove&id=' . $this->item->id, false, 2); ?>" class="btn btn-danger">
				<?php echo JText::_('COM_REQUEST_PACKAGE_DELETE_ITEM'); ?>
			</a>
		</div>
	</div>

<?php endif; ?>