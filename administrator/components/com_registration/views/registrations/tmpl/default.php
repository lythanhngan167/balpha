<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Registration
 * @author     nganly <lythanhngan167@gmail.com>
 * @copyright  2020 nganly
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
$document->addStyleSheet(Uri::root() . 'administrator/components/com_registration/assets/css/registration.css');
$document->addStyleSheet(Uri::root() . 'media/com_registration/css/list.css');

$user      = Factory::getUser();
$userId    = $user->get('id');
$listOrder = $this->state->get('list.ordering');
$listDirn  = $this->state->get('list.direction');
$canOrder  = $user->authorise('core.edit.state', 'com_registration');
$saveOrder = $listOrder == 'a.`ordering`';
$isDuplicateStatus = $this->state->get('filter.duplicate_first_bca');

if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_registration&task=registrations.saveOrderAjax&tmpl=component';
    HTMLHelper::_('sortablelist.sortable', 'registrationList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}

$sortFields = $this->getSortFields();
?>

<form action="<?php echo Route::_('index.php?option=com_registration&view=registrations'); ?>" method="post"
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
			<table class="table table-striped" id="registrationList">
				<thead>
				<tr>
					<?php if (isset($this->items[0]->ordering)): ?>
						<th width="1%" class="nowrap center hidden-phone">
                            <?php echo HTMLHelper::_('searchtools.sort', '', 'a.`ordering`', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-menu-2'); ?>
                        </th>
					<?php endif; ?>
					<th width="1%" class="hidden-phone">
						<input type="checkbox" name="checkall-toggle" value=""
							   title="<?php echo Text::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)"/>
					</th>
					<?php if (isset($this->items[0]->state)): ?>
						<th width="1%" class="nowrap center">
								<?php echo JHtml::_('searchtools.sort', 'JSTATUS', 'a.`state`', $listDirn, $listOrder); ?>
</th>
					<?php endif; ?>
				<!-- <th>
					<?php echo JHtml::_('searchtools.sort', 'COM_REGISTRATION_REGISTRATIONS_APPROVE', 'a.`remarketing_status`', $listDirn, $listOrder); ?>
				</th> -->
									<th class='left'>
				<?php echo JHtml::_('searchtools.sort',  'COM_REGISTRATION_REGISTRATIONS_ID', 'a.`id`', $listDirn, $listOrder); ?>
				</th>

				<th class='left'>
				<?php echo JHtml::_('searchtools.sort',  'COM_REGISTRATION_REGISTRATIONS_NAME', 'a.`name`', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('searchtools.sort',  'COM_REGISTRATION_REGISTRATIONS_PHONE', 'a.`phone`', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('searchtools.sort',  'COM_REGISTRATION_REGISTRATIONS_ISEXIST', 'a.`is_exist`', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('searchtools.sort',  'COM_REGISTRATION_REGISTRATIONS_EMAIL', 'a.`email`', $listDirn, $listOrder); ?>
				</th>
				<?php if(isset($isDuplicateStatus) && (int)$isDuplicateStatus === 1 ){?>
				<th class='left'>
				<?php echo JHtml::_('searchtools.sort',  'Duyệt', 'a.`duplicate_status`', $listDirn, $listOrder); ?>
				</th>
				<?php }?>

				<th class='left'>
				<?php echo JHtml::_('searchtools.sort',  'Lý do', 'a.`duplicate_id`', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('searchtools.sort',  'Dự án', 'a.`project_id`', $listDirn, $listOrder); ?>
				</th>
				<!-- <th class='left'>
				<?php echo JHtml::_('searchtools.sort',  'COM_REGISTRATION_REGISTRATIONS_JOB', 'a.`job`', $listDirn, $listOrder); ?>
				</th> -->

				<th class='left'>
				<?php echo JHtml::_('searchtools.sort',  'COM_REGISTRATION_REGISTRATIONS_PROVINCE', 'a.`province`', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('searchtools.sort',  'UTM Source', 'a.`utm_source`', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('searchtools.sort',  'Source', 'a.`utm_sourceonly`', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('searchtools.sort',  'Medium', 'a.`utm_mediumonly`', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('searchtools.sort',  'Compain', 'a.`utm_compainonly`', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('searchtools.sort',  'Landingpage', 'a.`from_landingpage`', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('searchtools.sort',  'COM_REGISTRATION_REGISTRATIONS_NOTE', 'a.`note`', $listDirn, $listOrder); ?>
				</th>

				<th class='left'>
				<?php echo JHtml::_('searchtools.sort',  'Ngày tạo', 'a.`created_date`', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('searchtools.sort',  'COM_REGISTRATION_REGISTRATIONS_CREATED_BY', 'a.`created_by`', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('searchtools.sort',  'COM_REGISTRATION_REGISTRATIONS_STATUS', 'a.`status`', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('searchtools.sort',  'AccessTrade', 'a.`transaction_status`', $listDirn, $listOrder); ?>
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
					$canCreate  = $user->authorise('core.create', 'com_registration');
					$canEdit    = $user->authorise('core.edit', 'com_registration');
					$canCheckin = $user->authorise('core.manage', 'com_registration');
					$canChange  = $user->authorise('core.edit.state', 'com_registration');
					?>
					<tr class="row<?php echo $i % 2; ?> <?php echo $item->is_exist == 0?'':"is_exist"; ?>">

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
						<td class="hidden-phone">
							<?php echo HTMLHelper::_('grid.id', $i, $item->id); ?>
						</td>
						<?php if (isset($this->items[0]->state)): ?>
							<td class="center">
								<?php echo JHtml::_('jgrid.published', $item->state, $i, 'registrations.', $canChange, 'cb'); ?>
</td>
						<?php endif; ?>

										<td>

					<?php echo $item->id; ?>
				</td>

				<td>
				<?php if (isset($item->checked_out) && $item->checked_out && ($canEdit || $canChange)) : ?>
					<?php echo JHtml::_('jgrid.checkedout', $i, $item->uEditor, $item->checked_out_time, 'registrations.', $canCheckin); ?>
				<?php endif; ?>
				<?php if ($canEdit) : ?>
					<a href="<?php echo JRoute::_('index.php?option=com_registration&task=registration.edit&id='.(int) $item->id); ?>">
					<?php echo $this->escape($item->name); ?></a>
				<?php else : ?>
					<?php echo $this->escape($item->name); ?>
				<?php endif; ?>

				</td>
				<td>
					<?php

					echo $item->phone;
					?>
				</td>
				<td>
					<?php
					$createDate = date("d-m-Y", strtotime($item->created_date));

					$isDuplicate = $this->checkDateDuplicate($item->phone,$createDate);

					$againt_reg = '';
					if($item->againt_registration == 1 || $isDuplicate == 1){
						$againt_reg = '<span style="color:orange; font-weight:bold;">Đăng ký lại</span>';
					} ?>
					<?php
					$duplicate_str = '<span style="color:red; font-weight:bold;">Trùng</span>';
					if($againt_reg != ''){
						$duplicate_str = '';
					}
					echo $item->is_exist == 0 ? 'Không' : $duplicate_str.$againt_reg;
					if($item->duplicate_first_bca == 1){
						echo "<br><br><span style='color:red; '>Trùng BCA lần đầu</span>";
					}
					?>

				</td>
				<td style="word-break: break-all;">
					<?php echo $item->email; ?>
				</td>

				<?php if(isset($isDuplicateStatus) && (int)$isDuplicateStatus === 1 ){?>
					<td>
						<?php if((int)$item->duplicate_status === 1){ ?>
							<div id="<?php echo 'duplicate-'. $i?>">
								<button type="button" class="btn btn-success" onclick="duplicateApprove(<?php echo $item->id?>, <?php echo $i?>)">Duyệt</button>

								<button type="button" class="btn btn-warning" onclick="duplicateReject(<?php echo $item->id?>, <?php echo $i?>)">Từ chối</button>
							</div>
						<?php } ?>
						<?php if((int)$item->duplicate_status === 0){ ?>
							<div id="<?php echo 'duplicate-success-'. $i?>">
								<button type="button" class="btn btn-success" onclick="duplicateSuccess(<?php echo $item->id?>, <?php echo $i?>)">Thành công</button>
							</div>
						<?php } ?>
						<?php if((int)$item->duplicate_status === 2){ ?>
								<span id="duplicate-status" class="badge badge-success">Đã duyệt</span>
						<?php } ?>
						<?php if((int)$item->duplicate_status === 3){ ?>
								<span id="duplicate-status" class="badge badge-warning">Từ chối</span>
						<?php } ?>
							<div id="<?php echo 'duplicate-text-'. $i?>" hidden>

							</div>
						</td>
				<?php } ?>
				<td style="word-break: break-all;">
					<?php
					if($item->duplicate_id == 1){
						echo "Không nhận tư vấn";
					}else{
						echo $item->duplicate_note;
					}
	 				?>

				</td>
				<td style="word-break: break-all;">
					<?php echo $this->getProjectName($item->project_id); ?>
				</td>

				<!-- <td>

					<?php echo $item->job; ?>
				</td>				 -->

				<td>

					<?php echo $item->province; ?>
				</td>
				<td style="word-break: break-all;">
					<?php echo  mb_substr($item->utm_source,0,100, "utf-8");; ?>
				</td>

				<td style="word-break: break-all;">
					<?php echo $item->utm_sourceonly; ?>
				</td>

				<td style="word-break: break-all;">
					<?php echo $item->utm_mediumonly; ?>
				</td>

				<td style="word-break: break-all;">
					<?php echo $item->utm_compainonly; ?>
				</td>

				<td style="word-break: break-all;">
					<?php echo $item->from_landingpage; ?>
				</td>

				<td>

					<?php echo $item->note; ?>
				</td>

				<td>
						<?php echo date("d-m-Y H:i", strtotime($item->created_date)); ?>
				</td>
				<td>

					<?php echo $item->created_by_username; ?>
				</td>
				<td>
					<?php echo $item->status;
					?>
					<!-- <button class="btn btn-warning" id="delete-expired-data" onclick="callApiAccessTradeReject('uuid_key','registrationId')" type="button">Hủy Data</button> -->

				</td>

				<td style="word-break: break-all;">
					<?php
					if($item->project_id == AT_PROJECT){
						switch ($item->transaction_status) {
						  case "0":
						    echo '<span class="label label-default">Mới</span>';
						    break;
						  case "1":
						    echo '<span class="label label-success">Đã duyệt</span>';
						    break;
						  case "2":
						    echo '<span class="label label-danger">Hủy</span>';
						    break;
						  default:
						    echo "";
						}
					}
					//echo $item->transaction_status;
					?>
					<br>
					<?php echo $item->transaction_id; ?>

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

<script type="text/javascript">
function callApiAccessTradeReject(uuid_key,registrationId){
	var r = confirm("Bạn có chắc muốn Hủy Data?");
	if (r == true) {
		jQuery.ajax({
        url: "<?php echo JUri::base(); ?>index.php?option=com_registration&task=registrations.callApiAccessTradeReject",
        type : "POST",
        dataType:"text",
				data: { uuid_key: 'ksjflkdsfs', registrationId: '8734592347' },
        success: function (result) {
            if (result == '1') {
                alert("Hủy Data tháng thành công!");
								location.reload();
                return;
            }
            if (result == '0') {
                alert("Hủy Data không thành công, vui lòng thử lại!");
                location.reload();
                return;
            }

        }
    });
	}
}

function duplicateApprove(registrationId, i) {
		var result = confirm("Xác nhận Duyệt Data trùng AT?");
		if(result === true) {
			jQuery.ajax({
				url: "<?php echo JUri::base(); ?>index.php?option=com_registration&view=registrations&task=registrations.duplicateApprove",
				type: "POST",
				dataType:"text",
				data : {
					registrationId : registrationId
				},
				success: function (result) {
					console.log(result);
					if(result == '-1'){
						alert("Duyệt thất bại!");
					}
					if(result == '1') {
						jQuery('#duplicate-' + i).attr('hidden', true);
						jQuery('#duplicate-text-' + i).attr('hidden', false);
						jQuery('#duplicate-text-' + i).append('<span id="duplicate-status" class="badge badge-success">Đã duyệt</span>');
					}

				}
			});
		}

	};

	function duplicateReject(registrationId, i) {
		var result = confirm("Xác nhận Từ chối Data trùng AT?");
		if(result === true) {
			jQuery.ajax({
				url: "<?php echo JUri::base(); ?>index.php?option=com_registration&view=registrations&task=registrations.duplicateReject",
				type: "POST",
				dataType:"text",
				data : {
					registrationId : registrationId
				},
				success: function (result) {
					if(result === '-1'){
						alert("Duyệt thất bại!");
					}

					if(result === '1'){
						jQuery('#duplicate-' + i).attr('hidden', true);
						jQuery('#duplicate-text-' + i).attr('hidden', false);
						jQuery('#duplicate-text-' + i).append('<span id="duplicate-status" class="badge badge-warning">Từ chối</span>');
					}

				}
			});
		}
	};


	function duplicateSuccess(registrationId, i) {
		var result = confirm("Xác nhận Thành công Data trùng AT?");
		if(result === true) {
			jQuery.ajax({
				url: "<?php echo JUri::base(); ?>index.php?option=com_registration&view=registrations&task=registrations.duplicateSuccess",
				type: "POST",
				dataType:"text",
				data : {
					registrationId : registrationId
				},
				success: function (result) {
					if(result === '0'){
						alert("Xác nhận Thành công Data trùng AT thất bại!");
					}

					if(result === '1'){
						jQuery('#duplicate-success-' + i).attr('hidden', true);
						jQuery('#duplicate-text-' + i).attr('hidden', false);
						jQuery('#duplicate-text-' + i).append('<span id="duplicate-status" class="badge badge-success">Thành công</span>');
					}

				}
			});
		}
	};
</script>
<style>
.btn.btn-success{
	padding:2px 5px 2px 5px;
}
.btn.btn-warning{
	padding:2px 0px 2px 0px;
	margin-top:10px;
}
</style>
