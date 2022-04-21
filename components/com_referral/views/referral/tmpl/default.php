<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Referral
 * @author     Truyền Đặng Minh <minhtruyen.ut@gmail.com>
 * @copyright  2021 Truyền Đặng Minh
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;
use \Joomla\CMS\HTML\HTMLHelper;
use \Joomla\CMS\Factory;
use \Joomla\CMS\Uri\Uri;
use \Joomla\CMS\Router\Route;
use \Joomla\CMS\Language\Text;
use \Joomla\CMS\Layout\LayoutHelper;

$canEdit = JFactory::getUser()->authorise('core.edit', 'com_referral.' . $this->item->id);

if (!$canEdit && JFactory::getUser()->authorise('core.edit.own', 'com_referral' . $this->item->id))
{
	$canEdit = JFactory::getUser()->id == $this->item->created_by;
}
?>

<div class="item_fields">

	<table class="table">
		

		<tr>
			<th><?php echo Text::_('COM_REFERRAL_FORM_LBL_REFERRAL_CAT_ID'); ?></th>
			<td><?php echo $this->item->cat_id; ?></td>
		</tr>

		<tr>
			<th><?php echo Text::_('COM_REFERRAL_FORM_LBL_REFERRAL_STATUS_ID'); ?></th>
			<td>
			<?php

			if (!empty($this->item->status_id) || $this->item->status_id === 0)
			{
				echo Text::_('COM_REFERRAL_REFERRALS_STATUS_ID_OPTION_' . $this->item->status_id);
			}
			?></td>
		</tr>

		<tr>
			<th><?php echo Text::_('COM_REFERRAL_FORM_LBL_REFERRAL_NAME'); ?></th>
			<td><?php echo $this->item->name; ?></td>
		</tr>

		<tr>
			<th><?php echo Text::_('COM_REFERRAL_FORM_LBL_REFERRAL_PHONE'); ?></th>
			<td><?php echo $this->item->phone; ?></td>
		</tr>

		<tr>
			<th><?php echo Text::_('COM_REFERRAL_FORM_LBL_REFERRAL_IS_EXIST'); ?></th>
			<td><?php echo $this->item->is_exist; ?></td>
		</tr>

		<tr>
			<th><?php echo Text::_('COM_REFERRAL_FORM_LBL_REFERRAL_USER_ID'); ?></th>
			<td><?php echo $this->item->user_id; ?></td>
		</tr>

		<tr>
			<th><?php echo Text::_('COM_REFERRAL_FORM_LBL_REFERRAL_REFERRAL_CODE'); ?></th>
			<td><?php echo $this->item->referral_code; ?></td>
		</tr>

		<tr>
			<th><?php echo Text::_('COM_REFERRAL_FORM_LBL_REFERRAL_EMAIL'); ?></th>
			<td><?php echo $this->item->email; ?></td>
		</tr>

		<tr>
			<th><?php echo Text::_('COM_REFERRAL_FORM_LBL_REFERRAL_IS_EXIST_AGENT'); ?></th>
			<td><?php echo $this->item->is_exist_agent; ?></td>
		</tr>

	</table>

</div>

<?php if($canEdit && $this->item->checked_out == 0): ?>

	<a class="btn" href="<?php echo JRoute::_('index.php?option=com_referral&task=referral.edit&id='.$this->item->id); ?>"><?php echo Text::_("COM_REFERRAL_EDIT_ITEM"); ?></a>

<?php endif; ?>

<?php if (JFactory::getUser()->authorise('core.delete','com_referral.referral.'.$this->item->id)) : ?>

	<a class="btn btn-danger" href="#deleteModal" role="button" data-toggle="modal">
		<?php echo Text::_("COM_REFERRAL_DELETE_ITEM"); ?>
	</a>

	<div id="deleteModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="deleteModal" aria-hidden="true">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h3><?php echo Text::_('COM_REFERRAL_DELETE_ITEM'); ?></h3>
		</div>
		<div class="modal-body">
			<p><?php echo Text::sprintf('COM_REFERRAL_DELETE_CONFIRM', $this->item->id); ?></p>
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal">Close</button>
			<a href="<?php echo JRoute::_('index.php?option=com_referral&task=referral.remove&id=' . $this->item->id, false, 2); ?>" class="btn btn-danger">
				<?php echo Text::_('COM_REFERRAL_DELETE_ITEM'); ?>
			</a>
		</div>
	</div>

<?php endif; ?>