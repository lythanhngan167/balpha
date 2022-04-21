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

HTMLHelper::addIncludePath(JPATH_COMPONENT . '/helpers/html');
HTMLHelper::_('bootstrap.tooltip');
HTMLHelper::_('behavior.multiselect');
HTMLHelper::_('formbehavior.chosen', 'select');

$user       = Factory::getUser();
$userId     = $user->get('id');
$listOrder  = $this->state->get('list.ordering');
$listDirn   = $this->state->get('list.direction');
$canCreate  = $user->authorise('core.create', 'com_referral') && file_exists(JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . 'referralform.xml');
$canEdit    = $user->authorise('core.edit', 'com_referral') && file_exists(JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . 'referralform.xml');
$canCheckin = $user->authorise('core.manage', 'com_referral');
$canChange  = $user->authorise('core.edit.state', 'com_referral');
$canDelete  = $user->authorise('core.delete', 'com_referral');

// Import CSS
$document = Factory::getDocument();
$document->addStyleSheet(Uri::root() . 'media/com_referral/css/list.css');
?>

<form action="<?php echo htmlspecialchars(Uri::getInstance()->toString()); ?>" method="post"
      name="adminForm" id="adminForm">

	<?php echo JLayoutHelper::render('default_filter', array('view' => $this), dirname(__FILE__)); ?>
        <div class="table-responsive">
	<table class="table table-striped" id="referralList">
		<thead>
		<tr>

							<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_REFERRAL_REFERRALS_ID', 'a.id', $listDirn, $listOrder); ?>
				</th>
				<th >
					<?php echo JHtml::_('grid.sort', 'JPUBLISHED', 'a.state', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_REFERRAL_REFERRALS_NAME', 'a.name', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_REFERRAL_REFERRALS_PHONE', 'a.phone', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_REFERRAL_REFERRALS_EMAIL', 'a.email', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_REFERRAL_REFERRALS_IS_EXIST', 'a.is_exist', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_REFERRAL_REFERRALS_IS_EXIST_AGENT', 'a.is_exist_agent', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_REFERRAL_REFERRALS_REFERRAL_CODE', 'a.referral_code', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_REFERRAL_REFERRALS_USER_ID', 'a.user_id', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_REFERRAL_REFERRALS_CAT_ID', 'a.cat_id', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_REFERRAL_REFERRALS_STATUS_ID', 'a.status_id', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_REFERRAL_REFERRALS_CREATED_DATE', 'a.created_date', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_REFERRAL_REFERRALS_MODIFIED_DATE', 'a.modified_date', $listDirn, $listOrder); ?>
				</th>


				<?php if ($canEdit || $canDelete): ?>
				<th class="center">
					<?php echo JText::_('COM_REFERRAL_REFERRALS_ACTIONS'); ?>
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
			<?php $canEdit = $user->authorise('core.edit', 'com_referral'); ?>

							<?php if (!$canEdit && $user->authorise('core.edit.own', 'com_referral')): ?>
					<?php $canEdit = JFactory::getUser()->id == $item->created_by; ?>
				<?php endif; ?>

			<tr class="row<?php echo $i % 2; ?>">

				
				<td>

					<?php echo $item->id; ?>
				</td>
				<td>

					<?php $class = ($canChange) ? 'active' : 'disabled'; ?>
					<a class="btn btn-micro <?php echo $class; ?>" href="<?php echo ($canChange) ? JRoute::_('index.php?option=com_referral&task=referral.publish&id=' . $item->id . '&state=' . (($item->state + 1) % 2), false, 2) : '#'; ?>">
					<?php if ($item->state == 1): ?>
						<i class="icon-publish"></i>
					<?php else: ?>
						<i class="icon-unpublish"></i>
					<?php endif; ?>
					</a>				</td>
				<td>
				<?php if (isset($item->checked_out) && $item->checked_out) : ?>
					<?php echo JHtml::_('jgrid.checkedout', $i, $item->uEditor, $item->checked_out_time, 'referrals.', $canCheckin); ?>
				<?php endif; ?>
				<a href="<?php echo JRoute::_('index.php?option=com_referral&view=referral&id='.(int) $item->id . '&catid=' . (int) $item->cat_id); ?>">
				<?php echo $this->escape($item->name); ?></a>
				</td>
				<td>

					<?php echo $item->phone; ?>
				</td>
				<td>

					<?php echo $item->email; ?>
				</td>
				<td>

					<?php echo $item->is_exist; ?>
				</td>
				<td>

					<?php echo $item->is_exist_agent; ?>
				</td>
				<td>

					<?php echo $item->referral_code; ?>
				</td>
				<td>

					<?php echo $item->user_id; ?>
				</td>
				<td>

					<?php echo $item->cat_id_name; ?>
				</td>
				<td>

					<?php echo $item->status_id; ?>
				</td>
				<td>

					<?php echo $item->created_date; ?>
				</td>
				<td>

					<?php echo $item->modified_date; ?>
				</td>

				<?php if ($canEdit || $canDelete): ?>
					<td class="center">
						<?php if ($canEdit): ?>
							<a href="<?php echo JRoute::_('index.php?option=com_referral&task=referral.edit&id=' . $item->id, false, 2); ?>" class="btn btn-mini" type="button"><i class="icon-edit" ></i></a>
						<?php endif; ?>
						<?php if ($canDelete): ?>
							<a href="<?php echo JRoute::_('index.php?option=com_referral&task=referralform.remove&id=' . $item->id, false, 2); ?>" class="btn btn-mini delete-button" type="button"><i class="icon-trash" ></i></a>
						<?php endif; ?>
					</td>
				<?php endif; ?>

			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
        </div>
	<?php if ($canCreate) : ?>
		<a href="<?php echo Route::_('index.php?option=com_referral&task=referralform.edit&id=0', false, 0); ?>"
		   class="btn btn-success btn-small"><i
				class="icon-plus"></i>
			<?php echo Text::_('COM_REFERRAL_ADD_ITEM'); ?></a>
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

		if (!confirm("<?php echo Text::_('COM_REFERRAL_DELETE_MESSAGE'); ?>")) {
			return false;
		}
	}
</script>
<?php endif; ?>
