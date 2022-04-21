<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Userlogs
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
use \Joomla\CMS\Layout\LayoutHelper;
use \Joomla\CMS\Language\Text;

HTMLHelper::addIncludePath(JPATH_COMPONENT . '/helpers/');
HTMLHelper::_('bootstrap.tooltip');
HTMLHelper::_('behavior.multiselect');
HTMLHelper::_('formbehavior.chosen', 'select');

// Import CSS
$document = Factory::getDocument();
$document->addStyleSheet(Uri::root() . 'administrator/components/com_userlogs/assets/css/userlogs.css');
$document->addStyleSheet(Uri::root() . 'media/com_userlogs/css/list.css');

$user      = Factory::getUser();
$userId    = $user->get('id');
$listOrder = $this->state->get('list.ordering');
$listDirn  = $this->state->get('list.direction');
$canOrder  = $user->authorise('core.edit.state', 'com_userlogs');
$saveOrder = $listOrder == 'a.`ordering`';
$logType   = $this->state->get('list.type');

if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_userlogs&task=userlogs.saveOrderAjax&tmpl=component';
    HTMLHelper::_('sortablelist.sortable', 'userlogList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}

$sortFields = $this->getSortFields();
?>

<form action="<?php echo Route::_('index.php?option=com_userlogs&view=userlogs'); ?>" method="post"
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
			<table class="table table-striped" id="userlogList">
				<thead>
				<tr>
					<?php if (isset($this->items[0]->ordering)): ?>
						<th width="1%" class="nowrap center hidden-phone">
                            <?php echo HTMLHelper::_('searchtools.sort', '', 'a.`ordering`', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-menu-2'); ?>
                        </th>
					<?php endif; ?>
					<th width="1%" >
						<input type="checkbox" name="checkall-toggle" value=""
							   title="<?php echo Text::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)"/>
					</th>
					<?php if (isset($this->items[0]->state)): ?>
						<th width="1%" class="nowrap center">
								<?php echo JHtml::_('searchtools.sort', 'JSTATUS', 'a.`state`', $listDirn, $listOrder); ?>
</th>
					<?php endif; ?>

									<th class='left'>
				<?php echo JHtml::_('searchtools.sort',  'COM_USERLOGS_USERLOGS_ID', 'a.`id`', $listDirn, $listOrder); ?>
				</th>
				<?php if((int)$logType === TRANSFER_AGENT) {?>
				<th class='left'>
				<?php echo JHtml::_('searchtools.sort',  'COM_USERLOGS_USERLOGS_TRANSFER_ID', 'a.`transfer_id`', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('searchtools.sort',  'COM_USERLOGS_USERLOGS_AGENT_ID', 'a.`agent_id`', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('searchtools.sort',  'COM_USERLOGS_USERLOGS_CUSTOMER_ID', 'a.`customer_id`', $listDirn, $listOrder); ?>
				</th>
				<?php } ?>
				<?php if((int)$logType === LEVEL_UPDATE) {?>
				<th class='left'>
				<?php echo JHtml::_('searchtools.sort',  'COM_USERLOGS_USERLOGS_USER_ID', 'a.`user_id`', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('searchtools.sort',  'COM_USERLOGS_USERLOGS_OLD_LEVEL', 'a.`old_level`', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('searchtools.sort',  'COM_USERLOGS_USERLOGS_NEW_LEVEL', 'a.`new_level`', $listDirn, $listOrder); ?>
				</th>
				<?php } ?>
				<th class='left'>
				<?php echo JHtml::_('searchtools.sort',  'COM_USERLOGS_USERLOGS_CREATED_DATE', 'a.`created_date`', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('searchtools.sort',  'COM_USERLOGS_USERLOGS_TYPE', 'a.`type`', $listDirn, $listOrder); ?>
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
					$canCreate  = $user->authorise('core.create', 'com_userlogs');
					$canEdit    = $user->authorise('core.edit', 'com_userlogs');
					$canCheckin = $user->authorise('core.manage', 'com_userlogs');
					$canChange  = $user->authorise('core.edit.state', 'com_userlogs');
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
						<?php if (isset($this->items[0]->state)): ?>
							<td class="center">
								<?php echo JHtml::_('jgrid.published', $item->state, $i, 'userlogs.', $canChange, 'cb'); ?>
</td>
						<?php endif; ?>

										<td>
				<?php if (isset($item->checked_out) && $item->checked_out && ($canEdit || $canChange)) : ?>
					<?php echo JHtml::_('jgrid.checkedout', $i, $item->uEditor, $item->checked_out_time, 'userlogs.', $canCheckin); ?>
				<?php endif; ?>
				<?php if ($canEdit) : ?>
					<a href="<?php echo JRoute::_('index.php?option=com_userlogs&task=userlog.edit&id='.(int) $item->id); ?>">
					<?php echo $this->escape($item->id); ?></a>
				<?php else : ?>
					<?php echo $this->escape($item->id); ?>
				<?php endif; ?>

				</td>
				<?php if((int)$logType === TRANSFER_AGENT) {?>				
				<td>

					<?php echo $item->transfer_id. '<br/>'; ?>
					<?php echo $item->transferName. '<br/>'; ?>
					<?php echo $item->transferUsername; ?>
				</td>
				<td>

					<?php echo $item->agent_id . '<br/>'; ?>
					<?php echo $item->agentName. '<br/>'; ?>
					<?php echo $item->agentUsername; ?>
				</td>				
				<td>

					<?php echo $item->customer_id. '<br/>'; ?>
					<?php echo $item->customerName. '<br/>'; ?>
					<?php echo $item->customerPhone; ?>
				</td>
				<?php } ?>
				<?php if((int)$logType === LEVEL_UPDATE) {?>
				<td>

					<?php echo $item->user_id. '<br/>'; ?>
					<?php echo $item->name. '<br/>'; ?>
					<?php echo $item->username; ?>
				</td>				
				<td>

					<?php echo $item->old_level; ?>
				</td>				
				<td>

					<?php echo $item->new_level; ?>
				</td>
				<?php } ?>				
				<td>

					<?php echo $item->created_date; ?>
				</td>				
				<td>
					<?php echo $item->type; ?>
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
        window.submitform(task);

        return false;
    };
</script>