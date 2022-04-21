<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Whitelist
 * @author     nganly <lythanhngan167@gmail.com>
 * @copyright  2020 nganly
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

$canEdit = JFactory::getUser()->authorise('core.edit', 'com_whitelist.' . $this->item->id);

if (!$canEdit && JFactory::getUser()->authorise('core.edit.own', 'com_whitelist' . $this->item->id))
{
	$canEdit = JFactory::getUser()->id == $this->item->created_by;
}
?>

<div class="item_fields">

	<table class="table">
		

		<tr>
			<th><?php echo JText::_('COM_WHITELIST_FORM_LBL_WHITELIST_NAME_PAGE'); ?></th>
			<td><?php echo $this->item->name_page; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_WHITELIST_FORM_LBL_WHITELIST_URL_PAGE'); ?></th>
			<td><?php echo $this->item->url_page; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_WHITELIST_FORM_LBL_WHITELIST_PROJECT_ID'); ?></th>
			<td><?php echo $this->item->project_id; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_WHITELIST_FORM_LBL_WHITELIST_CREATED_TIME'); ?></th>
			<td><?php echo $this->item->created_time; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_WHITELIST_FORM_LBL_WHITELIST_UPDATED_TIME'); ?></th>
			<td><?php echo $this->item->updated_time; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_WHITELIST_FORM_LBL_WHITELIST_LANDINGPAGE_NAME'); ?></th>
			<td><?php echo $this->item->landingpage_name; ?></td>
		</tr>

	</table>

</div>

<?php if($canEdit && $this->item->checked_out == 0): ?>

	<a class="btn" href="<?php echo JRoute::_('index.php?option=com_whitelist&task=whitelist.edit&id='.$this->item->id); ?>"><?php echo JText::_("COM_WHITELIST_EDIT_ITEM"); ?></a>

<?php endif; ?>

<?php if (JFactory::getUser()->authorise('core.delete','com_whitelist.whitelist.'.$this->item->id)) : ?>

	<a class="btn btn-danger" href="#deleteModal" role="button" data-toggle="modal">
		<?php echo JText::_("COM_WHITELIST_DELETE_ITEM"); ?>
	</a>

	<div id="deleteModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="deleteModal" aria-hidden="true">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h3><?php echo JText::_('COM_WHITELIST_DELETE_ITEM'); ?></h3>
		</div>
		<div class="modal-body">
			<p><?php echo JText::sprintf('COM_WHITELIST_DELETE_CONFIRM', $this->item->id); ?></p>
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal">Close</button>
			<a href="<?php echo JRoute::_('index.php?option=com_whitelist&task=whitelist.remove&id=' . $this->item->id, false, 2); ?>" class="btn btn-danger">
				<?php echo JText::_('COM_WHITELIST_DELETE_ITEM'); ?>
			</a>
		</div>
	</div>

<?php endif; ?>