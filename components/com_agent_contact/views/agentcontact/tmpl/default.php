<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Agent_contact
 * @author     nganly <lythanhngan167@gmail.com>
 * @copyright  2020 nganly
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

$canEdit = JFactory::getUser()->authorise('core.edit', 'com_agent_contact.' . $this->item->id);

if (!$canEdit && JFactory::getUser()->authorise('core.edit.own', 'com_agent_contact' . $this->item->id))
{
	$canEdit = JFactory::getUser()->id == $this->item->created_by;
}
?>

<div class="item_fields">

	<table class="table">
		

		<tr>
			<th><?php echo JText::_('COM_AGENT_CONTACT_FORM_LBL_AGENTCONTACT_PHONE'); ?></th>
			<td><?php echo $this->item->phone; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_AGENT_CONTACT_FORM_LBL_AGENTCONTACT_EMAIL'); ?></th>
			<td><?php echo $this->item->email; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_AGENT_CONTACT_FORM_LBL_AGENTCONTACT_ADDRESS'); ?></th>
			<td><?php echo $this->item->address; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_AGENT_CONTACT_FORM_LBL_AGENTCONTACT_FACEBOOKPAGE'); ?></th>
			<td><?php echo $this->item->facebookpage; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_AGENT_CONTACT_FORM_LBL_AGENTCONTACT_YOUTUBEPAGE'); ?></th>
			<td><?php echo $this->item->youtubepage; ?></td>
		</tr>

	</table>

</div>

<?php if($canEdit && $this->item->checked_out == 0): ?>

	<a class="btn" href="<?php echo JRoute::_('index.php?option=com_agent_contact&task=agentcontact.edit&id='.$this->item->id); ?>"><?php echo JText::_("COM_AGENT_CONTACT_EDIT_ITEM"); ?></a>

<?php endif; ?>

<?php if (JFactory::getUser()->authorise('core.delete','com_agent_contact.agentcontact.'.$this->item->id)) : ?>

	<a class="btn btn-danger" href="#deleteModal" role="button" data-toggle="modal">
		<?php echo JText::_("COM_AGENT_CONTACT_DELETE_ITEM"); ?>
	</a>

	<div id="deleteModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="deleteModal" aria-hidden="true">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h3><?php echo JText::_('COM_AGENT_CONTACT_DELETE_ITEM'); ?></h3>
		</div>
		<div class="modal-body">
			<p><?php echo JText::sprintf('COM_AGENT_CONTACT_DELETE_CONFIRM', $this->item->id); ?></p>
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal">Close</button>
			<a href="<?php echo JRoute::_('index.php?option=com_agent_contact&task=agentcontact.remove&id=' . $this->item->id, false, 2); ?>" class="btn btn-danger">
				<?php echo JText::_('COM_AGENT_CONTACT_DELETE_ITEM'); ?>
			</a>
		</div>
	</div>

<?php endif; ?>