<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Customer
 * @author     tung hoang <tungvacc@gmail.com>
 * @copyright  2017 tung hoang
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/');

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');
//echo date('Y-m-d H:i:s'); die;
// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet(JUri::root() . 'administrator/components/com_customer/assets/css/customer.css');
$document->addStyleSheet(JUri::root() . 'media/com_customer/css/list.css');

$user      = JFactory::getUser();
$userId    = $user->get('id');
$listOrder = $this->state->get('list.ordering');
$listDirn  = $this->state->get('list.direction');
$canOrder  = $user->authorise('core.edit.state', 'com_customer');
$saveOrder = $listOrder == 'a.`ordering`';
$orderTrash = $this->state->get('filter.status_id', false);
$filter_type = $this->state->get('filter.type', '');
$config = new JConfig();
if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_customer&task=customers.saveOrderAjax&tmpl=component';
	JHtml::_('sortablelist.sortable', 'customerList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}

$sortFields = $this->getSortFields();
?>
<style>
#toolbar-trash, #toolbar-checkin,#toolbar-archive,#toolbar-unpublish,#toolbar-publish,#toolbar-edit,#toolbar-copy{
display:none;
}
</style>
<form action="<?php echo JRoute::_('index.php?option=com_customer&view=customers'); ?>" method="post"
	  name="adminForm" id="adminForm">
	<?php if (!empty($this->sidebar)): ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
		<?php else : ?>
		<div id="j-main-container">
			<?php endif; ?>

            <?php echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this)); ?>

			<div class="clearfix"></div>
			<table class="table table-striped" id="customerList">
				<thead>
				<tr>
					<?php if (isset($this->items[0]->ordering)): ?>
						<th width="1%" class="nowrap center hidden-phone">
                            <?php echo JHtml::_('searchtools.sort', '', 'a.`ordering`', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-menu-2'); ?>
                        </th>
					<?php endif; ?>
					<th width="1%" class="hidden-phone">
						<input type="checkbox" name="checkall-toggle" value=""
							   title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)"/>
					</th>
					<?php if (isset($this->items[0]->state)): ?>
						<th width="1%" class="nowrap center">
								<?php echo JHtml::_('searchtools.sort', 'JSTATUS', 'a.`state`', $listDirn, $listOrder); ?>
</th>
					<?php endif; ?>
									<th class='left'>
				<?php echo JHtml::_('searchtools.sort',  'COM_CUSTOMER_CUSTOMERS_ID', 'a.`id`', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('searchtools.sort',  'COM_CUSTOMER_CUSTOMERS_NAME', 'a.`name`', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('searchtools.sort',  'COM_CUSTOMER_CUSTOMERS_PHONE', 'a.`phone`', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('searchtools.sort',  'COM_CUSTOMER_CUSTOMERS_EMAIL', 'a.`email`', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('searchtools.sort',  'COM_CUSTOMER_CUSTOMERS_PROJECT_ID', 'a.`project_id`', $listDirn, $listOrder); ?>
				</th>
				<?php if(isset($orderTrash) && $orderTrash) {?>
					<th class='left'>
						<?php echo JHtml::_('searchtools.sort', 'COM_CUSTOMER_CUSTOMERS_APPROVE', 'a.`trash_approve`', $listDirn, $listOrder); ?>
					</th>
				<?php } ?>

				<?php if($filter_type == 'accesstrade') {?>
					<th class='left'>
						<?php echo JHtml::_('searchtools.sort', 'Duyệt', '', $listDirn, $listOrder); ?>
					</th>
				<?php } ?>

				<th class='left'>
				<?php echo JHtml::_('searchtools.sort',  'Lý do', 'a.`rating_note`', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('searchtools.sort',  'COM_CUSTOMER_CUSTOMERS_REFERENCE', 'a.`place`', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('searchtools.sort',  'COM_CUSTOMER_CUSTOMERS_PLACE', 'a.`place`', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('searchtools.sort',  'COM_CUSTOMER_FORM_PROVINCE', 'a.`province`', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('searchtools.sort',  'COM_CUSTOMER_CUSTOMERS_SALE_ID', 'a.`sale_id`', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('searchtools.sort',  'COM_CUSTOMER_CUSTOMERS_CATEGORY_ID', 'a.`category_id`', $listDirn, $listOrder); ?>
				</th>

				<th class='left'>
				<?php echo JHtml::_('searchtools.sort',  'COM_CUSTOMER_CUSTOMERS_STATUS_ID', 'a.`status_id`', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('searchtools.sort',  'COM_CUSTOMER_CUSTOMERS_TOTAL_REVENUE', 'a.`total_revenue`', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
					Ngày tạo
				</th>
				<th class='left'>
					Ngày bán
				</th>
				<th class='left'>
					Ngày cập nhật
				</th>

				<th class='left'>
					AccessTrade
				</th>

				<th class='left'>
					Trạng thái AT
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
					$canCreate  = $user->authorise('core.create', 'com_customer');
					$canEdit    = $user->authorise('core.edit', 'com_customer');
					$canCheckin = $user->authorise('core.manage', 'com_customer');
					$canChange  = $user->authorise('core.edit.state', 'com_customer');
					?>
					<tr class="row<?php echo $i % 2; ?>">

						<?php if (isset($this->items[0]->ordering)) : ?>
							<td class="order nowrap center hidden-phone">
								<?php if ($canChange) :
									$disableClassName = '';
									$disabledLabel    = '';

									if (!$saveOrder) :
										$disabledLabel    = JText::_('JORDERINGDISABLED');
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
							<?php echo JHtml::_('grid.id', $i, $item->id); ?>
						</td>
						<?php if (isset($this->items[0]->state)): ?>
							<td class="center">
								<?php echo JHtml::_('jgrid.published', $item->state, $i, 'customers.', $canChange, 'cb'); ?>
</td>
						<?php endif; ?>
										<td>
											<a href="<?php echo JRoute::_('index.php?option=com_customer&task=customer.edit&id='.(int) $item->id); ?>">
					<?php echo $item->id; ?>
				</a>
				</td>				<td>
				<?php if (isset($item->checked_out) && $item->checked_out && ($canEdit || $canChange)) : ?>
					<?php echo JHtml::_('jgrid.checkedout', $i, $item->uEditor, $item->checked_out_time, 'customers.', $canCheckin); ?>
				<?php endif; ?>
				<?php if ($canEdit) : ?>
					<a href="<?php echo JRoute::_('index.php?option=com_customer&task=customer.edit&id='.(int) $item->id); ?>">
					<?php echo $this->escape($item->name); ?></a>
				<?php else : ?>
					<?php echo $this->escape($item->name); ?>
				<?php endif; ?>

				</td>				<td>

					<?php echo $item->phone; ?>
				</td>
				<td style="word-break: break-all;">

					<?php echo $item->email; ?>
				</td>
				<td>

					<?php echo $item->project_id; ?>
				</td>

					<?php if(isset($orderTrash) && $orderTrash) {?>

						<td>
						<?php if((int)$item->trash_approve === 0){ ?>
							<?php
							if((int)$item->project_id_int === 32 && $item->status_id_int == 99) {  ?>
							<div id="<?php echo 'trash-'. $i?>">
								<button type="button" class="btn btn-success" onclick="trashApprove(<?php echo $item->id?>, <?php echo $i?>)">Duyệt</button>

								<button type="button" class="btn btn-warning" onclick="trashReject(<?php echo $item->id?>, <?php echo $i?>)">Từ chối</button>
							</div>
							<?php } ?>

							<?php
							if((int)$item->project_id_int != 32 && $item->status_id_int == 99) {

								?>
							<div id="<?php echo 'trash-'. $i?>">
								<?php $isShowApproveBiznet = $this->checkConfirmNumberDayIsOk($item->buy_date); ?>
								<?php if($isShowApproveBiznet == 1){ ?>
								<button type="button" class="btn btn-success" onclick="trashBiznetApprove(<?php echo $item->id?>, <?php echo $i?>)">Duyệt</button>
								<button type="button" class="btn btn-warning" onclick="trashBiznetReject(<?php echo $item->id?>, <?php echo $i?>)">Từ chối</button>
							<?php }else{
								echo "Quá ".$config->numberDayToConfirmDataBiznet." ngày";
							} ?>

							</div>
							<?php } ?>
						<?php }else { ?>
							<?php if((int)$item->project_id_int === 32) {?>
							<?php if((int)$item->trash_approve === 1){ ?>
								<span id="trashStatus" class="badge badge-success">Đã duyệt</span>
							<?php } ?>
							<?php if((int)$item->trash_approve === 2){ ?>
								<span id="trashStatus" class="badge badge-warning">Từ chối</span>
							<?php } ?>
							<?php } ?>

							<?php if((int)$item->project_id_int != 32) {?>
							<?php if((int)$item->trash_approve === 1){ ?>
								<span id="trashStatus" class="badge badge-success">Đã duyệt</span>
							<?php } ?>
							<?php if((int)$item->trash_approve === 2){ ?>
								<span id="trashStatus" class="badge badge-warning">Từ chối</span>
							<?php } ?>
							<?php } ?>

						<?php } ?>
							<div id="<?php echo 'trash-text-'. $i?>" hidden>

							</div>
						</td>
					<?php } ?>


					<?php if($filter_type == 'accesstrade') {?>

						<td>
							<?php
							if((int)$item->project_id_int === 32) {?>

							<?php

							$countDate = 0;
							$now = time();

							if($item->buy_date != '0000-00-00 00:00:00'){
								$your_date = strtotime($item->buy_date);
							}else{
								$your_date = strtotime($item->create_date);
							}

							$datediff = $now - $your_date;
							$countDate = round($datediff / (60 * 60 * 24));

							$classCountDate = '';
							if($countDate >= $config->numberDayConfirmSuccessAT){
								$classCountDate = 'orange';
							}
							if($countDate >=4 && $countDate < $config->numberDayConfirmSuccessAT){
								$classCountDate = 'gray';
							}
							if($countDate < 4){
								$classCountDate = 'green';
							}
							?>
							<?php if($countDate >= $config->numberDayConfirmSuccessAT){ ?>
							<div id="<?php echo 'successAT-'. $i?>">
								<button type="button" class="btn btn-success" onclick="successApprove(<?php echo $item->id?>, <?php echo $i?>)">Duyệt</button>
							</div>
							<?php } ?>
							<div class="count-date <?php echo $classCountDate; ?>">
							<?php
							echo $countDate;
							?> ngày
							</div>
						<?php }else { ?>
							<?php if((int)$item->project_id_int === 32) {?>
							<?php if((int)$item->trash_approve === 1){ ?>
								<span id="trashStatus" class="badge badge-success">Đã duyệt</span>
							<?php } ?>

							<?php } ?>
						<?php } ?>

						<div id="<?php echo 'successAT-text-'. $i?>" hidden>

						</div>
						</td>
					<?php } ?>

					<td>

							<?php
							if(isset($item->rating_id)) {
								switch((int)$item->rating_id){
									case 3:
										$item->rating_text = 'Khác';
									break;
									case 1:
										$item->rating_text = 'Sai thông tin';
									break;
									case 2:
										$item->rating_text = 'Không có nhu cầu';
									break;
								}
							}
							if((int)$item->rating_id === 3){?>

									<?php //echo $item->rating_note?>
									<?php echo $item->rating_text; ?>
									<?php if($item->trash_confirmed_by_dm != ''){ ?>
									<br>
				          <b>DM</b>: <?php echo $item->trash_confirmed_by_dm; ?>
									<?php if($item->rating_note != ''){ ?>
				          <br><b>Ghi chú</b>: <?php echo $item->rating_note; ?>
				          <?php } ?>
									<?php } ?>

							<?php } else {?>

									<?php echo $item->rating_text?>
									<?php if($item->trash_confirmed_by_dm != ''){ ?>
									<br>
				          <b>DM</b>: <?php echo $item->trash_confirmed_by_dm; ?>
									<?php if($item->rating_note != ''){ ?>
				          <br><b>Ghi chú</b>: <?php echo $item->rating_note; ?>
				          <?php } ?>
									<?php } ?>

							<?php }?>

					</td>
				<td>
					<?php
					if ($item->reference_id == 0) {
						echo "";
					}else {
								echo "ID: ". $item->reference_id;
						} ?><br>
					<?php
					if ($item->reference_type == "") {
						echo "";
					}
					else {
						echo "Loại: ".$item->reference_type;
					}
					?>
				</td>
				<td style="word-break: break-all;">
					<?php echo $item->place; ?>
				</td>
				<td>
					<?php echo $item->province_text; ?>
				</td>
				<td>

					<?php
					echo  $item->sale_id > 0 ? $this->getNameUser($item->sale_id) : $this->getNameUser($item->from_landingpage);
					?>
				</td>				<td>

					<?php echo $item->category_id; ?>
				</td>

				<td>

					<?php echo $item->status_id; ?>
				</td>				<td>

					<span class="price"><?php echo number_format($item->total_revenue,0,",","."); ?> BizXu</span>
				</td>
				<td>
					<?php echo JFactory::getDate($item->create_date)->format("d-m-Y H:i"); ?>
				</td>
				<td>

					<?php echo $item->buy_date != '0000-00-00 00:00:00'? JFactory::getDate($item->buy_date)->format("d-m-Y H:i") : '#'; ?>
				</td>
				<td>
					<?php echo JFactory::getDate($item->modified_date)->format("d-m-Y H:i"); ?>
				</td>
				<td>

					<?php
					if($item->project_id_int == AT_PROJECT){
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
					?>
					<br>
					<?php echo $item->transaction_id; ?>
				</td>
				<td>

					<?php
					if($item->project_id_int == AT_PROJECT){
						switch ($item->transaction_status) {
						  case "0":
						    echo 'Mới';
						    break;
						  case "1":
						    echo 'Đã duyệt';
						    break;
						  case "2":
						    echo 'Hủy';
						    break;
						  default:
						    echo "";
						}
					}
					?>
				</td>
				</tr>
				<?php endforeach; ?>
				</tbody>
			</table>

			<input type="hidden" name="task" value=""/>
			<input type="hidden" name="boxchecked" value="0"/>
            <input type="hidden" name="list[fullorder]" value="<?php echo $listOrder; ?> <?php echo $listDirn; ?>"/>
			<?php echo JHtml::_('form.token'); ?>
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

	function trashApprove(customerId, i) {
		var result = confirm("Xác nhận Duyệt?");
		if(result === true) {
			jQuery.ajax({
				url: "<?php echo JUri::base(); ?>index.php?option=com_customer&view=customers&task=customers.trashApprove",
				type: "POST",
				dataType:"text",
				data : {
					customerId : customerId
				},
				success: function (result) {
					if(result == '-1'){
						alert("Duyệt thất bại!");
					} else {
						jQuery('#trash-' + i).attr('hidden', true);
						jQuery('#trash-text-' + i).attr('hidden', false);
						jQuery('#trash-text-' + i).append('<span id="trashStatus" class="badge badge-success">Đã duyệt</span>');
					}

				}
			});
		}

	};

	function trashReject(customerId, i) {
		var result = confirm("Xác nhận Từ chối?");
		if(result === true) {
			jQuery.ajax({
				url: "<?php echo JUri::base(); ?>index.php?option=com_customer&view=customers&task=customers.trashReject",
				type: "POST",
				dataType:"text",
				data : {
					customerId : customerId
				},
				success: function (result) {
					if(result == '-1'){
						alert("Duyệt thất bại!");
					} else {
						jQuery('#trash-' + i).attr('hidden', true);
						jQuery('#trash-text-' + i).attr('hidden', false);
						jQuery('#trash-text-' + i).append('<span id="trashStatus" class="badge badge-warning">Từ chối</span>');
					}

				}
			});
		}
	};

	function trashBiznetApprove(customerId, i) {
		var result = confirm("Xác nhận Duyệt?");
		if(result === true) {
			jQuery.ajax({
				url: "<?php echo JUri::base(); ?>index.php?option=com_customer&task=customers.trashBiznetApprove",
				type: "POST",
				dataType:"text",
				data : {
					customerId : customerId
				},
				success: function (result) {
					if(result == '-1'){
						alert("Duyệt thất bại!");
					} else {
						if(result == '-2'){
							alert("Thời gian đã vượt quá <?php echo $config->numberDayToConfirmDataBiznet; ?> ngày!");
						}else{
							jQuery('#trash-' + i).attr('hidden', true);
							jQuery('#trash-text-' + i).attr('hidden', false);
							jQuery('#trash-text-' + i).append('<span id="trashStatus" class="badge badge-success">Đã duyệt</span>');
						}

					}

				}
			});
		}

	};

	function trashBiznetReject(customerId, i) {
		var result = confirm("Xác nhận Từ chối?");
		if(result === true) {
			jQuery.ajax({
				url: "<?php echo JUri::base(); ?>index.php?option=com_customer&task=customers.trashBiznetReject",
				type: "POST",
				dataType:"text",
				data : {
					customerId : customerId
				},
				success: function (result) {
					if(result == '-1'){
						alert("Duyệt thất bại!");
					} else {
						jQuery('#trash-' + i).attr('hidden', true);
						jQuery('#trash-text-' + i).attr('hidden', false);
						jQuery('#trash-text-' + i).append('<span id="trashStatus" class="badge badge-warning">Từ chối</span>');
					}

				}
			});
		}
	};

	function successApprove(customerId, i) {
		var result = confirm("Xác nhận Duyệt Thành công Dự án AT?");
		if(result === true) {
			jQuery.ajax({
				url: "<?php echo JUri::base(); ?>index.php?option=com_customer&view=customers&task=customers.successApprove",
				type: "POST",
				dataType:"text",
				data : {
					customerId : customerId
				},
				success: function (result) {
					if(result == '-1'){
						alert("Duyệt Thành công AT thất bại!");
					} else {
						jQuery('#successAT-' + i).attr('hidden', true);
						jQuery('#successAT-text-' + i).attr('hidden', false);
						jQuery('#successAT-text-' + i).append('<span id="trashStatus" class="badge badge-success">Đã duyệt</span>');
					}

				}
			});
		}

	};
</script>
<style>
.price{ color:#FF0000; font-weight:bold;  }
#trash-approve {
	margin-bottom:10px;
}

.btn.btn-success{
	padding:2px 5px 2px 5px;
}
.btn.btn-warning{
	padding:2px 0px 2px 0px;
	margin-top:10px;
}
.count-date{
	padding-top:10px;
}
.count-date.orange{
	color:orange;
}
.count-date.gray{
	color:gray;
}
.count-date.green{
	color:green;
}
</style>
