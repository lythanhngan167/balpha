<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Notification
 * @author     nganly <lythanhngan167@gmail.com>
 * @copyright  2019 nganly
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

use \Joomla\CMS\HTML\HTMLHelper;
use \Joomla\CMS\Factory;
use \Joomla\CMS\Uri\Uri;
use \Joomla\CMS\Router\Route;
use \Joomla\CMS\Language\Text;

HTMLHelper::addIncludePath(JPATH_COMPONENT . '/helpers/html');
HTMLHelper::_('bootstrap.tooltip');
HTMLHelper::_('behavior.multiselect');
HTMLHelper::_('formbehavior.chosen', 'select');

$user       = Factory::getUser();
$userId     = $user->get('id');
$listOrder  = $this->state->get('list.ordering');
$listDirn   = $this->state->get('list.direction');
$canCreate  = $user->authorise('core.create', 'com_notification') && file_exists(JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . 'notificationform.xml');
$canEdit    = $user->authorise('core.edit', 'com_notification') && file_exists(JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . 'notificationform.xml');
$canCheckin = $user->authorise('core.manage', 'com_notification');
$canChange  = $user->authorise('core.edit.state', 'com_notification');
$canDelete  = $user->authorise('core.delete', 'com_notification');
?>

<div class="group-tabs" id="selectResetType">
	<!-- Nav tabs -->
	<ul class="nav nav-tabs" role="tablist" id="noti-tab">
		<li role="presentation" class="active"><a href="#noti-all" aria-controls="noti-all" role="tab" data-toggle="tab"><?php echo JText::_('COM_NOTIFICATION_ALL'); ?></a></li>
		<li id="li-noti-personal"><a href="#" ><?php echo JText::_('COM_NOTIFICATION_PERSONAL'); ?></a></li>
	</ul>
</div>

<div id="end"></div>

<div class="tab-content">
	<div role="tabpanel" class="tab-pane active" id="noti-all">
		<form action="<?php echo htmlspecialchars(Uri::getInstance()->toString()); ?>" method="post"
		name="adminForm" id="adminForm">

		<?php echo JLayoutHelper::render('default_filter', array('view' => $this), dirname(__FILE__)); ?>
		<table class="table table-striped" id="notificationList">
			<thead>
			<tr><!--
				<?php if (isset($this->items[0]->state)): ?>
					<th width="5%">
		<?php echo JHtml::_('grid.sort', 'JPUBLISHED', 'a.state', $listDirn, $listOrder); ?>
	</th>
				<?php endif; ?>

								<th class=''>
					<?php echo JHtml::_('grid.sort',  'COM_NOTIFICATION_NOTIFICATIONS_ID', 'a.id', $listDirn, $listOrder); ?>
					</th>
					<th class=''>
					<?php echo JHtml::_('grid.sort',  'COM_NOTIFICATION_NOTIFICATIONS_CATEGORY', 'a.category', $listDirn, $listOrder); ?>
					</th> -->
					<th class=''>
					<?php echo JHtml::_('grid.sort',  'COM_NOTIFICATION_NOTIFICATIONS_TITLE', 'a.title', $listDirn, $listOrder); ?>
					</th>
					<th class=''>
					<?php echo JHtml::_('grid.sort',  'COM_NOTIFICATION_NOTIFICATIONS_CONTENT', 'a.content', $listDirn, $listOrder); ?>
					</th>


								<?php if ($canEdit || $canDelete): ?>
						<th class="center">
					<?php echo JText::_('COM_NOTIFICATION_NOTIFICATIONS_ACTIONS'); ?>
					</th>
					<?php endif; ?>

			</tr>
			</thead>
			<tfoot>
			<tr>
				<td colspan="<?php echo isset($this->items[0]) ? count(get_object_vars($this->items[0])) : 10; ?>">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
			</tfoot>
			<tbody>
			<?php foreach ($this->items as $i => $item) : ?>
				<?php $canEdit = $user->authorise('core.edit', 'com_notification'); ?>

								<?php if (!$canEdit && $user->authorise('core.edit.own', 'com_notification')): ?>
						<?php $canEdit = JFactory::getUser()->id == $item->created_by; ?>
					<?php endif; ?>

				<tr class="row<?php echo $i % 2; ?>">

					<!-- <?php if (isset($this->items[0]->state)) : ?>
						<?php $class = ($canChange) ? 'active' : 'disabled'; ?>
						<td class="center">
		<a class="btn btn-micro <?php echo $class; ?>" href="<?php echo ($canChange) ? JRoute::_('index.php?option=com_notification&task=notification.publish&id=' . $item->id . '&state=' . (($item->state + 1) % 2), false, 2) : '#'; ?>">
		<?php if ($item->state == 1): ?>
			<i class="icon-publish"></i>
		<?php else: ?>
			<i class="icon-unpublish"></i>
		<?php endif; ?>
		</a>
	</td>
					<?php endif; ?>

									<td>

						<?php echo $item->id; ?>
					</td>
					<td>
					<?php if (isset($item->checked_out) && $item->checked_out) : ?>
						<?php echo JHtml::_('jgrid.checkedout', $i, $item->uEditor, $item->checked_out_time, 'notifications.', $canCheckin); ?>
					<?php endif; ?>
					<a href="<?php echo JRoute::_('index.php?option=com_notification&view=notification&id='.(int) $item->id); ?>">
					<?php echo $this->escape($item->category); ?></a>
					</td> -->
					<td>
						<a href="<?php echo JRoute::_('index.php?option=com_notification&view=notification&id='.(int) $item->id); ?>">
							<?php echo $item->title; ?>
						</a>
					</td>
					<td>

						<?php echo $item->message; ?>
					</td>


									<?php if ($canEdit || $canDelete): ?>
						<td class="center">
							<?php if ($canEdit): ?>
								<a href="<?php echo JRoute::_('index.php?option=com_notification&task=notificationform.edit&id=' . $item->id, false, 2); ?>" class="btn btn-mini" type="button"><i class="icon-edit" ></i></a>
							<?php endif; ?>
							<?php if ($canDelete): ?>
								<a href="<?php echo JRoute::_('index.php?option=com_notification&task=notificationform.remove&id=' . $item->id, false, 2); ?>" class="btn btn-mini delete-button" type="button"><i class="icon-trash" ></i></a>
							<?php endif; ?>
						</td>
					<?php endif; ?>

				</tr>
			<?php endforeach; ?>
			<?php if(count($this->items) === 0){?>
				<tr>
					<td colspan="6">
						<?php echo JText::_('COM_NOTIFICATION_NO_DATA'); ?>
					</td>
				</tr>
			<?php }?>
			</tbody>
		</table>

		<!-- <?php if ($canCreate) : ?>
			<a href="<?php echo Route::_('index.php?option=com_notification&task=notificationform.edit&id=0', false, 0); ?>"
			class="btn btn-success btn-small"><i
					class="icon-plus"></i>
				<?php echo Text::_('COM_NOTIFICATION_ADD_ITEM'); ?></a>
		<?php endif; ?> -->

		<input type="hidden" name="task" value=""/>
		<input type="hidden" name="boxchecked" value="0"/>
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
		<?php echo HTMLHelper::_('form.token'); ?>
	</form>

	<?php if($canDelete) : ?>
	<script type="text/javascript">

		jQuery(document).ready(function () {
			jQuery('.delete-button').click(deleteItem);
		});

		function deleteItem() {

			if (!confirm("<?php echo Text::_('COM_NOTIFICATION_DELETE_MESSAGE'); ?>")) {
				return false;
			}
		}
	</script>
	<?php endif; ?>

	</div>
	<div role="tabpanel" class="tab-pane" id="noti-personal">
		
	</div>
</div>

<script type="text/javascript">
	jQuery('#li-noti-personal').click(function() {
		window.location.href = "<?php echo JRoute::_('index.php?option=com_notifications_user&view=notificationusers&Itemid=449'); ?>";
	});
</script>

