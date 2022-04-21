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
use \Joomla\CMS\Layout\LayoutHelper;
use \Joomla\CMS\Language\Text;

HTMLHelper::addIncludePath(JPATH_COMPONENT . '/helpers/');
HTMLHelper::_('bootstrap.tooltip');
HTMLHelper::_('behavior.multiselect');
HTMLHelper::_('formbehavior.chosen', 'select');

// Import CSS
$document = Factory::getDocument();
$document->addStyleSheet(Uri::root() . 'administrator/components/com_referral/assets/css/referral.css');
$document->addStyleSheet(Uri::root() . 'media/com_referral/css/list.css');

$user      = Factory::getUser();
$userId    = $user->get('id');
$listOrder = $this->state->get('list.ordering');
$listDirn  = $this->state->get('list.direction');
$canOrder  = $user->authorise('core.edit.state', 'com_referral');
$saveOrder = $listOrder == 'a.ordering';

if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_referral&task=referrals.saveOrderAjax&tmpl=component';
    HTMLHelper::_('sortablelist.sortable', 'referralList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}

$sortFields = $this->getSortFields();
?>

<form action="<?php echo Route::_('index.php?option=com_referral&view=referrals'); ?>" method="post"
	  name="adminForm" id="adminForm">
	<?php if (!empty($this->sidebar)): ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
		<?php else : ?>
		<div id="j-main-container">
			<?php endif; ?>

			<?php echo LayoutHelper::render('joomla.searchtools.default', array('view' => $this)); ?>

			<div class="clearfix"></div>
			<table class="table table-striped" id="referralList">
				<thead>
				<tr>
					<?php if (isset($this->items[0]->ordering)): ?>
					<th width="1%" class="nowrap center hidden-phone">
						<?php echo HTMLHelper::_('searchtools.sort', '', 'a.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-menu-2'); ?>
					</th>
					<?php endif; ?>
					<th width="1%" >
						<input type="checkbox" name="checkall-toggle" value="" title="<?php echo Text::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)"/>
					</th>

					<th class='left'>
						<?php echo JHtml::_('searchtools.sort',  'JGLOBAL_FIELD_ID_LABEL', 'a.`id`', $listDirn, $listOrder); ?>
					</th>
					<th width="5%" class="nowrap center">
						<?php echo JHtml::_('searchtools.sort', 'JSTATUS', 'a.state', $listDirn, $listOrder); ?>
					</th>
					<th class='left'>
						<?php echo JHtml::_('searchtools.sort',  'COM_REFERRAL_REFERRALS_NAME', 'a.`name`', $listDirn, $listOrder); ?>
					</th>
					<th class='left'>
						<?php echo JHtml::_('searchtools.sort',  'COM_REFERRAL_REFERRALS_PHONE', 'a.`phone`', $listDirn, $listOrder); ?>
					</th>
					<th class='left'>
						<?php echo JHtml::_('searchtools.sort',  'COM_REFERRAL_REFERRALS_EMAIL', 'a.`email`', $listDirn, $listOrder); ?>
					</th>
					<th class='left'>
						<?php echo JHtml::_('searchtools.sort',  'COM_REFERRAL_REFERRALS_IS_EXIST', 'a.`is_exist`', $listDirn, $listOrder); ?>
					</th>
					<th class='left'>
						<?php echo JHtml::_('searchtools.sort',  'COM_REFERRAL_REFERRALS_IS_EXIST_AGENT', 'a.`is_exist_agent`', $listDirn, $listOrder); ?>
					</th>
					<th class='left'>
						<?php echo JHtml::_('searchtools.sort',  'COM_REFERRAL_REFERRALS_REFERRAL_CODE', 'a.`referral_code`', $listDirn, $listOrder); ?>
					</th>
					<th class='left'>
						<?php echo JHtml::_('searchtools.sort',  'COM_REFERRAL_REFERRALS_USER_ID', 'a.`user_id`', $listDirn, $listOrder); ?>
					</th>
					<th class='left'>
						<?php echo JHtml::_('searchtools.sort',  'COM_REFERRAL_REFERRALS_CAT_ID', 'a.`cat_id`', $listDirn, $listOrder); ?>
					</th>
					<th class='left'>
						<?php echo JHtml::_('searchtools.sort',  'COM_REFERRAL_REFERRALS_STATUS_ID', 'a.`status_id`', $listDirn, $listOrder); ?>
					</th>
					<th class='left'>
						<?php echo JHtml::_('searchtools.sort',  'IP', 'a.`ip`', $listDirn, $listOrder); ?>
					</th>
					<th class='left'>
						<?php echo JHtml::_('searchtools.sort',  'Thông tin thiết bị', 'a.`http_user_agent`', $listDirn, $listOrder); ?>
					</th>
					<th class='left'>
						<?php echo JHtml::_('searchtools.sort',  'COM_REFERRAL_REFERRALS_CREATED_DATE', 'a.`created_date`', $listDirn, $listOrder); ?>
					</th>
					<th class='left'>
						<?php echo JHtml::_('searchtools.sort',  'COM_REFERRAL_REFERRALS_MODIFIED_DATE', 'a.`modified_date`', $listDirn, $listOrder); ?>
					</th>

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
				<?php foreach ($this->items as $i => $item) :
					$ordering   = ($listOrder == 'a.ordering');
					$canCreate  = $user->authorise('core.create', 'com_referral');
					$canEdit    = $user->authorise('core.edit', 'com_referral');
					$canCheckin = $user->authorise('core.manage', 'com_referral');
					$canChange  = $user->authorise('core.edit.state', 'com_referral');
					?>
					<tr class="row<?php echo $i % 2; ?>">

						<?php if (isset($this->items[0]->ordering)) : ?>
							<td class="order nowrap center hidden-phone">
								<?php if ($canChange) :
									$disableClassName = '';
									$disabledLabel    = '';

									if (!$saveOrder) :
										$disabledLabel    = Text::_('JORDERINGDISABLED');
										$disableClassName = 'inactive tip-top';
									endif; ?>
									<span class="sortable-handler hasTooltip <?php echo $disableClassName ?>"
										  title="<?php echo $disabledLabel ?>">
							<i class="icon-menu"></i>
						</span>
									<input type="text" style="display:none" name="order[]" size="5"
										   value="<?php echo $item->ordering; ?>" class="width-20 text-area-order "/>
								<?php else : ?>
									<span class="sortable-handler inactive">
							<i class="icon-menu"></i>
						</span>
								<?php endif; ?>
							</td>
						<?php endif; ?>
						<td >
							<?php echo HTMLHelper::_('grid.id', $i, $item->id); ?>
						</td>


						<td>
							<?php echo $item->id; ?>
						</td>
						<td>
							<div class="btn-group">
								<?php echo HTMLHelper::_('jgrid.published', $item->state, $i, 'referrals.', $canChange, 'cb'); ?>
								<?php if ($canChange) : ?>
									<?php  HTMLHelper::_('actionsdropdown.' . ((int) $item->state === 2 ? 'un' : '') . 'archive', 'cb' . $i, 'referrals'); ?>
									<?php HTMLHelper::_('actionsdropdown.' . ((int) $item->state === -2 ? 'un' : '') . 'trash', 'cb' . $i, 'referrals'); ?>
									<?php echo HTMLHelper::_('actionsdropdown.render', $this->escape($item->state)); ?>
								<?php endif; ?>
							</div>
						</td>
						<td>
							<?php if (isset($item->checked_out) && $item->checked_out && ($canEdit || $canChange)) : ?>
								<?php echo JHtml::_('jgrid.checkedout', $i, $item->uEditor, $item->checked_out_time, 'referrals.', $canCheckin); ?>
							<?php endif; ?>
							<?php if ($canEdit) : ?>
								<a href="<?php echo JRoute::_('index.php?option=com_referral&task=referral.edit&id='.(int) $item->id); ?>">
								<?php echo $this->escape($item->name); ?></a>
							<?php else : ?>
								<?php echo $this->escape($item->name); ?>
							<?php endif; ?>
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
							<?php echo $item->cat_id; ?>
						</td>
						<td>
							<?php echo $item->status_id; ?>
						</td>
						<td>
							<?php echo $item->ip; ?>
						</td>
						<td>
							<?php echo $item->http_user_agent; ?>
						</td>

						<td>
							<?php
								$date = $item->created_date;
								echo $date > 0 ? HTMLHelper::_('date', $date, JText::_('DATE_FORMAT_LC6')) : '-';
							?>
						</td>
						<td>
							<?php
								$date = $item->modified_date;
								echo $date > 0 ? HTMLHelper::_('date', $date, JText::_('DATE_FORMAT_LC6')) : '-';
							?>
						</td>

					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>

			<input type="hidden" name="task" value=""/>
			<input type="hidden" name="boxchecked" value="0"/>
            <input type="hidden" name="list[fullorder]" value="<?php echo $listOrder; ?> <?php echo $listDirn; ?>"/>
			<?php echo HTMLHelper::_('form.token'); ?>
		</div>
</form>
<script>
    window.toggleField = function (id, task, field) {

        var f = document.adminForm, i = 0, cbx, cb = f[ id ];

        if (!cb) return false;

        while (true) {
            cbx = f[ 'cb' + i ];

            if (!cbx) break;

            cbx.checked = false;
            i++;
        }

        var inputField   = document.createElement('input');

        inputField.type  = 'hidden';
        inputField.name  = 'field';
        inputField.value = field;
        f.appendChild(inputField);

        cb.checked = true;
        f.boxchecked.value = 1;
        Joomla.submitform(task);

        return false;
    };
</script>
