<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Notifications_user
 * @author     Minh Thái Thi <thiminhthaichoigame@gmail.com>
 * @copyright  2020 Minh Thái Thi
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;


?>
<h3><?php echo JText::_('COM_NOTIFICATION_ALL'); ?></h3>
<div class="item_fields">

	<table class="table">
		

		<!-- <tr>
			<th><?php echo JText::_('COM_NOTIFICATIONS_USER_FORM_LBL_NOTIFICATIONUSER_USER_ID'); ?></th>
			<td><?php echo $this->item->user_id; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_NOTIFICATIONS_USER_FORM_LBL_NOTIFICATIONUSER_SEEN_FLAG'); ?></th>
			<td><?php echo $this->item->seen_flag; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_NOTIFICATIONS_USER_FORM_LBL_NOTIFICATIONUSER_SEEN_AT'); ?></th>
			<td><?php echo $this->item->seen_at; ?></td>
		</tr> -->

		<tr>
			<th><?php echo JText::_('COM_NOTIFICATIONS_USER_FORM_LBL_NOTIFICATIONUSER_TITLE'); ?></th>
			<td><?php echo $this->item->title; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_NOTIFICATIONS_USER_FORM_LBL_NOTIFICATIONUSER_MESSAGE'); ?></th>
			<td><?php echo $this->item->message; ?></td>
		</tr>

		<!-- <tr>
			<th><?php echo JText::_('COM_NOTIFICATIONS_USER_FORM_LBL_NOTIFICATIONUSER_CREATED_DATE'); ?></th>
			<td><?php echo $this->item->created_date; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_NOTIFICATIONS_USER_FORM_LBL_NOTIFICATIONUSER_UPDATED_DATE'); ?></th>
			<td><?php echo $this->item->updated_date; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_NOTIFICATIONS_USER_FORM_LBL_NOTIFICATIONUSER_CATEGORY'); ?></th>
			<td><?php echo $this->item->category; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_NOTIFICATIONS_USER_FORM_LBL_NOTIFICATIONUSER_STATUS'); ?></th>
			<td><?php echo $this->item->status; ?></td>
		</tr> -->

	</table>

</div>

