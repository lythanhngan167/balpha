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

use \Joomla\CMS\HTML\HTMLHelper;
use \Joomla\CMS\Factory;
use \Joomla\CMS\Uri\Uri;
use \Joomla\CMS\Router\Route;
use \Joomla\CMS\Language\Text;
use \Joomla\CMS\Layout\LayoutHelper;

HTMLHelper::addIncludePath(JPATH_COMPONENT . '/helpers/html');
HTMLHelper::_('bootstrap.tooltip');
HTMLHelper::_('behavior.multiselect');
HTMLHelper::_('formbehavior.chosen', 'select');

$user       = Factory::getUser();
$userId     = $user->get('id');
$listOrder  = $this->state->get('list.ordering');
$listDirn   = $this->state->get('list.direction');
$canCreate  = $user->authorise('core.create', 'com_notifications_user') && file_exists(JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . 'notificationuserform.xml');
$canEdit    = $user->authorise('core.edit', 'com_notifications_user') && file_exists(JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . 'notificationuserform.xml');
$canCheckin = $user->authorise('core.manage', 'com_notifications_user');
$canChange  = $user->authorise('core.edit.state', 'com_notifications_user');
$canDelete  = $user->authorise('core.delete', 'com_notifications_user');

// Import CSS
$document = Factory::getDocument();
$document->addStyleSheet(Uri::root() . 'media/com_notifications_user/css/list.css');
?>

<div class="group-tabs" id="selectResetType">
	<!-- Nav tabs -->
	<ul class="nav nav-tabs" role="tablist" id="noti-tab">
		<li id="li-noti-all" ><a href="#"><?php echo JText::_('COM_NOTIFICATION_ALL'); ?></a></li>
		<li role="presentation" class="active"><a href="#noti-personal" aria-controls="noti-personal" role="tab" data-toggle="tab"><?php echo JText::_('COM_NOTIFICATION_PERSONAL'); ?></a></li>
	</ul>
</div>

<div class="tab-content">
	<div role="tabpanel" class="tab-pane" id="noti-all">
	</div>
	<div role="tabpanel" class="tab-pane active" id="noti-personal">
		<form action="<?php echo htmlspecialchars(Uri::getInstance()->toString()); ?>" method="post"
			name="adminForm" id="adminForm">

			<?php echo JLayoutHelper::render('default_filter', array('view' => $this), dirname(__FILE__)); ?>
				<div class="table-responsive">
			<table class="table table-striped" id="notificationuserList">
				<thead>
				<tr>
					<!-- <?php if (isset($this->items[0]->state)): ?>
						<th width="5%">
			<?php echo JHtml::_('grid.sort', 'JPUBLISHED', 'a.state', $listDirn, $listOrder); ?>
		</th>
					<?php endif; ?> -->

									<!-- <th class=''>
						<?php echo JHtml::_('grid.sort',  'COM_NOTIFICATIONS_USER_NOTIFICATIONUSERS_ID', 'a.id', $listDirn, $listOrder); ?>
						</th>
						<th class=''>
						<?php echo JHtml::_('grid.sort',  'COM_NOTIFICATIONS_USER_NOTIFICATIONUSERS_USER_ID', 'a.user_id', $listDirn, $listOrder); ?>
						</th>
						<th class=''>
						<?php echo JHtml::_('grid.sort',  'COM_NOTIFICATIONS_USER_NOTIFICATIONUSERS_SEEN_FLAG', 'a.seen_flag', $listDirn, $listOrder); ?>
						</th>
						<th class=''>
						<?php echo JHtml::_('grid.sort',  'COM_NOTIFICATIONS_USER_NOTIFICATIONUSERS_SEEN_AT', 'a.seen_at', $listDirn, $listOrder); ?>
						</th> -->
						<th class=''>
						<?php echo JHtml::_('grid.sort',  'COM_NOTIFICATIONS_USER_NOTIFICATIONUSERS_TITLE', 'a.title', $listDirn, $listOrder); ?>
						</th>
						<th class=''>
						<?php echo JHtml::_('grid.sort',  'COM_NOTIFICATIONS_USER_NOTIFICATIONUSERS_MESSAGE', 'a.message', $listDirn, $listOrder); ?>
						</th>
						<!-- <th class=''>
						<?php echo JHtml::_('grid.sort',  'COM_NOTIFICATIONS_USER_NOTIFICATIONUSERS_CREATED_DATE', 'a.created_date', $listDirn, $listOrder); ?>
						</th>
						<th class=''>
						<?php echo JHtml::_('grid.sort',  'COM_NOTIFICATIONS_USER_NOTIFICATIONUSERS_UPDATED_DATE', 'a.updated_date', $listDirn, $listOrder); ?>
						</th>
						<th class=''>
						<?php echo JHtml::_('grid.sort',  'COM_NOTIFICATIONS_USER_NOTIFICATIONUSERS_CATEGORY', 'a.category', $listDirn, $listOrder); ?>
						</th>
						<th class=''>
						<?php echo JHtml::_('grid.sort',  'COM_NOTIFICATIONS_USER_NOTIFICATIONUSERS_STATUS', 'a.status', $listDirn, $listOrder); ?>
						</th> -->


									<?php if ($canEdit || $canDelete): ?>
							<th class="center">
						<?php echo JText::_('COM_NOTIFICATIONS_USER_NOTIFICATIONUSERS_ACTIONS'); ?>
						</th>
						<?php endif; ?>
						<th></th>

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
					<?php $canEdit = $user->authorise('core.edit', 'com_notifications_user'); ?>

					
					<tr class="row<?php echo $i % 2; ?>">

						<!-- <?php if (isset($this->items[0]->state)) : ?>
							<?php $class = ($canChange) ? 'active' : 'disabled'; ?>
							<td class="center">
			<a class="btn btn-micro <?php echo $class; ?>" href="<?php echo ($canChange) ? JRoute::_('index.php?option=com_notifications_user&task=notificationuser.publish&id=' . $item->id . '&state=' . (($item->state + 1) % 2), false, 2) : '#'; ?>">
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

							<?php echo $item->user_id; ?>
						</td>
						<td>

							<?php echo $item->seen_flag; ?>
						</td>
						<td>

							<?php echo $item->seen_at; ?>
						</td> -->
						<td>
						<!-- <?php if (isset($item->checked_out) && $item->checked_out) : ?>
							<?php echo JHtml::_('jgrid.checkedout', $i, $item->uEditor, $item->checked_out_time, 'notificationusers.', $canCheckin); ?>
						<?php endif; ?> -->
						<a href="<?php echo JRoute::_('index.php?option=com_notifications_user&view=notificationuser&id='.(int) $item->id . '&catid=' . (int) $item->category); ?>">
						<?php echo $this->escape($item->title); ?></a>
						</td>
						<td>

							<?php echo $item->message; ?>
						</td>
						<!-- <td>

							<?php echo $item->created_date; ?>
						</td>
						<td>

							<?php echo $item->updated_date; ?>
						</td>
						<td>

							<?php echo $item->category_name; ?>
						</td>
						<td>

							<?php echo $item->status; ?>
						</td>


										<?php if ($canEdit || $canDelete): ?>
							<td class="center">
							</td>
						<?php endif; ?> -->
						<td>
							<?php if($item->seen_flag === '0') {?>
								<span class="badge badge-pill badge-danger">&nbsp</span>
							<?php } ?>
						</td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
				</div>
			<?php if ($canCreate) : ?>
				<a href="<?php echo Route::_('index.php?option=com_notifications_user&task=notificationuserform.edit&id=0', false, 0); ?>"
				class="btn btn-success btn-small"><i
						class="icon-plus"></i>
					<?php echo Text::_('COM_NOTIFICATIONS_USER_ADD_ITEM'); ?></a>
			<?php endif; ?>

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

				if (!confirm("<?php echo Text::_('COM_NOTIFICATIONS_USER_DELETE_MESSAGE'); ?>")) {
					return false;
				}
			}
		</script>
		<?php endif; ?>
	</div>
</div>
<script type="text/javascript">
	jQuery('#li-noti-all').click(function() {
		var userGroup = <?php echo $this->userGroup[0]?>;
		if(userGroup === 3){
			window.location.href = "<?php echo JRoute::_('index.php?option=com_notification&view=notifications&Itemid=444'); ?>";
		}
		if(userGroup === 2){
			window.location.href = "<?php echo JRoute::_('index.php?option=com_notification&view=notifications&Itemid=445'); ?>";
		}
		
	});
</script>


