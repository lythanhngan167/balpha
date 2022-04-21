<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

$listOrder  = $this->escape($this->state->get('list.ordering'));
$listDirn   = $this->escape($this->state->get('list.direction'));

$month_selected =  $this->state->get('filter.month', date('m'));
$year_selected =  $this->state->get('filter.year', date('Y'));
$g_leader = $this->state->get('filter.leader', '');

// $loggeduser = JFactory::getUser();
// $debugUsers = $this->state->get('params')->get('debugUsers', 1);


?>
<style>
#j-main-container{with:100%!important;}
#j-sidebar-container{
	display: none;
}
.row-fluid .span10{
	width:100%;
}
.blockuser{ margin-top:8px; }
.leader{color:orange; padding-top: 5px;}
.text-orange{
	color:orange;
	font-size: 15px;
}
.text-red{
	color:red;
	font-size: 15px;
}
.text-price-red{
	color:red;
}
.text-black{
	color:black;
	font-size: 15px;
}
.subhead-collapse{
	height: 5px;
	display: none;
}
#j-main-container {
    padding-left: 0;
}
.js-stools-container-filters{
	display: block;
}
.js-stools-container-bar .js-stools-btn-clear{
	display: none;
}
#filter_leader_chzn .chzn-search{
	position: relative!important;
	left: 0!important;
}

</style>

<br>
<div class="text-red">Tiền hoa hồng được trả cho Đại lý vào ngày 10 hàng tháng.</div>
<div class="text-orange"><b>Lưu ý</b>: Những đơn hàng đã được Kế toán duyệt <b>"Đã thanh toán"</b> mới được tính hoa hồng cho Đại lý, những đơn hàng nào chưa được xác nhận <b>"Đã thanh toán"</b> sẽ được tính dồn cho tháng sau.</div>
<div class="text-black">
	 Hoa hồng được tính với đơn hàng đã được Kế toán duyệt <b>"Đã thanh toán"</b> từ <span class="text-red">00:00 ngày 10/<?php echo date("m") == 1?'12': date("m") - 1; ?>/<?php echo date("m") == 1?date('Y')-1: date("Y"); ?> <b>đến</b> 23:59 ngày 09/<?php echo date("m"); ?>/<?php echo date("Y"); ?></span>.

</div>
<br>

<form action="<?php echo JRoute::_('index.php?option=com_users&view=commission'); ?>" method="post" name="adminForm" id="adminForm">

	<?php if (!empty( $this->sidebar)) : ?>
		<div id="j-sidebar-container" class="span0">
		<?php echo $this->sidebar; ?>
		</div>
		<div id="j-main-container" class="span12">
	<?php else : ?>
		<div id="j-main-container">
	<?php endif; ?>
		<div id="filter-all">
		<?php
		//Search tools bar
		//echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));
		?>

		<select name="filter[month]" id="filter_month" onchange="this.form.submit();">
		<?php for ($ik = 1; $ik <=12; $ik++) {
			$zero= '';
			?>
			<?php if($ik < 10){ $zero =  "0";} ?>
			<option <?php if($ik == $month_selected){ echo 'selected="selected"';} ?>  value="<?php echo $zero.$ik?>">
				<?php echo "Tháng ".$ik; ?> (10/<?php echo $ik-1 == 0?'':$zero; echo $ik-1 == 0?'12':$ik-1; ?> - 09/<?php echo $zero; echo $ik; ?>)</option>
		<?php } ?>
		</select>

		<select name="filter[year]" id="filter_year" onchange="this.form.submit();">
		<?php $current_year = date("Y");
		for ($ik2 = $current_year-1; $ik2 <= $current_year+5; $ik2++) {
			$zero= '';
			?>
			<option <?php if($ik2 == $year_selected){ echo 'selected="selected"';} ?>  value="<?php echo $ik2; ?>"><?php echo "Năm ".$ik2; ?></option>
		<?php } ?>
		</select>

		<select name="filter[leader]" id="filter_leader" onchange="this.form.submit();">
			<option value="">Chọn Đại lý</option>
		<?php foreach ($this->listLeader as $leader) :
			?>
			<option <?php if($g_leader == $leader->id){ echo 'selected="selected"';} ?> value="<?php echo $leader->id; ?>"><?php echo $leader->name; ?> (<?php echo $leader->username; ?>)</option>
		<?php endforeach; ?>
		</select>


		&nbsp;&nbsp;<button class="btn btn-primary" type="submit">Tìm</button>&nbsp;&nbsp;
		<a href="<?php echo JRoute::_('index.php?option=com_users&view=commission&clear=1'); ?>"><button  type="button" class="btn btn-danger btn hasTooltip js-stools-btn-clear">Xóa</button></a>

		</div>
		<div id="export">
			<br>
			<button class="btn btn-warning" onclick="exportExcel()" type="button">Xuất Excel</button>
		</div>
		<?php
		if (empty($this->items)) : ?>
			<div class="alert alert-no-items">
				<?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
			</div>
		<?php else : ?>
			<br><br>
			<table class="table table-striped" id="userList">
				<thead>
					<tr>
						<!-- <th width="1%" class="nowrap center">
							<?php echo JHtml::_('grid.checkall'); ?>
						</th> -->
						<th width="5%" class="nowrap hidden-phone">
							<?php echo JHtml::_('searchtools.sort', 'Mã ĐL', 'a.id', $listDirn, $listOrder); ?>
						</th>
						<th width="15%" class="nowrap">
							<?php echo JHtml::_('searchtools.sort', 'Họ tên', 'a.name', $listDirn, $listOrder); ?>
						</th>
						<th width="15%" class="nowrap">
							<?php echo JHtml::_('searchtools.sort', 'Số ĐT', 'a.username', $listDirn, $listOrder); ?>
						</th>
						<th width="15%" class="nowrap">
							<a href="#">Cấp ĐL</a>
						</th>
						<th width="15%" class="nowrap">
							<a href="#">Tổng tiền</a>
						</th>

						<th width="15%" class="nowrap">
							<a href="#">Bảo trợ</a>
						</th>
						<!-- <th width="10%" class="nowrap">
							<a href="#">Tên và CN Ngân hàng</a>
						</th>
						<th width="10%" class="nowrap">
							<a href="#">Tên tài khoản</a>
						</th>
						<th width="10%" class="nowrap">
							<a href="#">Số tài khoản</a>
						</th> -->
						<th width="15%" class="nowrap">
							<a href="#">Tháng</a>
						</th>
						<th width="15%" class="nowrap center">
							<?php echo JHtml::_('searchtools.sort', 'Trạng thái', 'a.block', $listDirn, $listOrder); ?>
						</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<td colspan="11">
							<?php echo $this->pagination->getListFooter(); ?>
						</td>
					</tr>
				</tfoot>
				<tbody>
				<?php foreach ($this->items as $i => $item) :
					//$canEdit   = $this->canDo->get('core.edit');
					//$canChange = $loggeduser->authorise('core.edit.state',	'com_users');

					// If this group is super admin and this user is not super admin, $canEdit is false
					// if ((!$loggeduser->authorise('core.admin')) && JAccess::check($item->id, 'core.admin'))
					// {
					// 	$canEdit   = false;
					// 	$canChange = false;
					// }
				?>
					<tr class="row<?php echo $i % 2; ?>">
						<!-- <td class="center">
							<?php if ($canEdit || $canChange) : ?>
								<?php echo JHtml::_('grid.id', $i, $item->id); ?>
							<?php endif; ?>
						</td> -->
						<td class="hidden-phone">
							<?php //echo (int) $item->id; ?>
							<a target="_blank" href="<?php echo JRoute::_('index.php?option=com_users&task=user.edit&id=' . (int) $item->id); ?>" title="<?php echo JText::sprintf('COM_USERS_EDIT_USER', $this->escape($item->name)); ?>"><?php
									echo $item->level_tree.str_pad($item->id,6,"0",STR_PAD_LEFT);
							?>
							</a>
						</td>
						<td>
							<div class="name ">
							<?php $canEdit = 1;
							if ($canEdit) : ?>
								<a target="_blank" href="<?php echo JRoute::_('index.php?option=com_users&task=user.edit&id=' . (int) $item->id); ?>" title="<?php echo JText::sprintf('COM_USERS_EDIT_USER', $this->escape($item->name)); ?>">
									<?php echo $this->escape($item->name); ?></a>
							<?php else : ?>
								<?php echo $this->escape($item->name); ?>
							<?php endif; ?>
							</div>


							<?php if ($item->requireReset == '1') : ?>
								<span class="label label-warning"><?php echo JText::_('COM_USERS_PASSWORD_RESET_REQUIRED'); ?></span>
							<?php endif; ?>
							<?php if ($debugUsers > 0) : ?>
								<div class="small"><a href="<?php echo JRoute::_('index.php?option=com_users&view=debuguser&user_id=' . (int) $item->id); ?>">
								<?php echo JText::_('COM_USERS_DEBUG_USER'); ?></a></div>
							<?php endif; ?>
						</td>
						<td class="break-word">
							<?php echo $this->escape($item->username); ?>

						</td>
						<td class="break-word">

						<span>C<?php echo $item->level_tree; ?></span>
							<!-- <div>
								<a href="#" onclick="changeLeve(<?php echo $item->id; ?>)">
								Thay đổi Level
							</a>
							</div> -->
						</td>
						<td class="break-word">
							<?php //$dataOrder = $this->getMoneySaleByMonth($item->id,$month,$year); ?>
							<span class="text-price-red">
								<?php //echo number_format($dataOrder->total,0,",","."); ?>

								<?php
								//$type = 'individual';
								$type = 'group';
								$money = EshopHelper::getCommissionAmount($item->id,$month_selected,$year_selected,$type);
								echo number_format($money,0,".",".");
								?> đ</span>
						</td>

						<td class="break-word">

							<span><?php //echo JFactory::getUser($item->invited_id)->username; ?></span>
							<a target="_blank" href="<?php echo JRoute::_('index.php?option=com_users&task=user.edit&id=' . (int) $item->invited_id); ?>" >
								<?php
									$user_invition = JFactory::getUser($item->invited_id);
									if($item->invited_id > 0){
										echo $user_invition->level_tree.str_pad($item->invited_id,6,"0",STR_PAD_LEFT);
									}

									?>
							</a>

						</div>
						</td>





						<!-- <td class="hidden-phone hidden-tablet">
							<?php if ($item->lastvisitDate != $this->db->getNullDate()) : ?>
								<?php echo JHtml::_('date', $item->lastvisitDate, 'Y-m-d H:i:s'); ?>
							<?php else : ?>
								<?php echo JText::_('JNEVER'); ?>
							<?php endif; ?>
						</td>
						<td class="hidden-phone hidden-tablet">
							<?php echo JHtml::_('date', $item->registerDate, 'Y-m-d H:i:s'); ?>
						</td> -->
						<!-- <td>
							<?php if($current_month != $month && $dataOrder->total > 0){ ?>
							<?php $checkPayment = $this->checkPayment($item->id,$month."-".$year); ?>
							<?php if($checkPayment ==1){ ?><label class="btn btn-success" type="button">Đã thanh toán</label><?php } ?>
							<?php if($checkPayment ==0){ ?>
								<button class="btn btn-warning" type="button" onclick="confirmPayment(<?php echo $item->id; ?>,'<?php echo $item->username; ?>','<?php echo $month."-".$year ?>',<?php echo $dataOrder->total; ?>)">Chưa thanh toán</button>

							<?php } ?>
							<?php } ?>
							<div><?php if($item->block == 0){ ?><button class="btn btn-danger blockuser" type="button" onclick="blockUser(<?php echo $item->id; ?>,'<?php echo $item->username; ?>',1)">Khóa</button>&nbsp;<?php } ?><?php if($item->block == 1 && $item->banned == 0){ ?> <button class="btn btn-success blockuser" onclick="blockUser(<?php echo $item->id; ?>,'<?php echo $item->username; ?>',0)" type="button">Mở khóa</button> <?php } ?></div>
						</td> -->
						<!-- <td><?php echo $this->escape($item->bank_name); ?></td>
						<td><?php echo $this->escape($item->bank_account_name); ?></td>
						<td><?php echo $this->escape($item->bank_account_number); ?></td> -->
						<td><?php echo $month_selected."/".$year_selected; ?></td>
						<td><?php echo $item->block == 1?'<span class="btn-warning publish-status">Tạm khóa</span>':'<span class="btn-success publish-status">Kích hoạt</span>'; ?></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			<?php // Load the batch processing form if user is allowed ?>

		<?php endif; ?>

		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
<div class="modal fade" style="width:400px;" id="myModalTagLevel" role="dialog">
  <div class="modal-dialog">
    <form name="reasonFrom" id="reasonFormLevel">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Thay đổi Level</h4>
      </div>
      <div class="modal-body">
				<select id="level_id" name="level_id">
				<option value="">--Vui lòng chọn--</option>
				<option value="1">Level 1</option>
				<option value="2">Level 2</option>
				<option value="3">Level 3</option>
				<option value="4">Level 4</option>
				<option value="5">Level 5</option>
			</select>
			<input type="hidden" name="userid" id="userid" value="">
			  </div>
      <div class="modal-footer">
        <div class="btn-group1" >
        <button type="button" class="btn btn-default" id="change_cancel_status_bt" data-dismiss="modal">Huỷ</button>
        <button type="button" class="btn btn-primary" id="change_save_status_bt" onclick="updateLevel()" >Lưu</button>
        </div>
      </div>
    </div>
    </form>
  </div>
</div>


<script type="text/javascript">

jQuery(document).ready(function(){
  jQuery('#filter_leader_chzn input').prop('readonly', false);

});

function exportExcel(){
	if(jQuery('#filter_search').val() != '' && jQuery('#filter_search').val() != undefined){
		alert("Vui lòng xoá lọc Tìm kiếm để xuất Excel, chỉ chọn Tháng và Năm!");
	}else{
		if(jQuery('#filter_leader').val() != ''){
			alert("Vui lòng xoá lọc Đại lý, chỉ chọn Tháng và Năm!");
		}else{
			jQuery.ajax({
					url: "<?php echo JUri::base(); ?>index.php?option=com_users&task=commission.exportExcel",
					type : "POST",
          dataType:"text",
					data : {
							 month : '<?php echo $this->state->get('filter.month', date('m')) ?>',
							 year: '<?php echo $this->state->get('filter.year', date('Y')) ?>'
					},
					success: function (result) {
							if (result == '1') {
									alert("Xuất Excel thành công, tải về ngay!");
									location.href = "<?php echo JUri::base(); ?>export/hoahongdaily.xlsx";

							}
							if (result == '0') {
									alert("Xuất Excel không thành công, vui lòng thử lại!");
									location.reload();
									return;
							}

					}
			});
		}
	}
	// var r = confirm("Bạn có chắc muốn xuất Excel");
	// if (r == true) {
	//
	// } else {
	//
	// }
}

function confirmPayment(userid,username,monthyear,total){
  if(userid != "" &&  monthyear != '' && total != ''){
		var r = confirm("Bạn có chắc muốn xác nhận đã thanh toán cho Sale: "+username+" với số tiền: "+numberWithCommas(total)+" đ");
		if (r == true) {
			jQuery.ajax({url: "<?php echo JUri::base(); ?>index.php?option=com_users&view=commission&ajax=1&type=confirmPayment&userid="+userid+"&total=" + total +"&monthyear="+monthyear,
			success: function (result) {
						if(result == '2'){
							alert("Bạn đã xác nhận thanh toán này rồi!");
							location.reload();
						}
						if(result == '1'){
							alert("Xác nhận thành công!");
							location.reload();
						}

						if(result == '0'){
							alert("Xác nhận thất bại, vui lòng thử lại!");
							location.reload();
						}

				 }
			});
		} else {

		}

  }else{
    alert("Có lỗi, vui lòng kiểm tra và thử lại!");
  }

}

function blockUser(userid,username,status){
  if(userid != ""){
		var r = confirm("Bạn có chắc muốn khóa Sale: "+username+"?");
		if (r == true) {
			jQuery.ajax({url: "<?php echo JUri::base(); ?>index.php?option=com_users&view=commission&ajax=1&type=blockUser&userid="+userid+"&status="+status,
			success: function (result) {
						if(result == '1'){
							if(status == '1'){
								alert("Đã khóa thành công!");
							}else{
								alert("Đã mở khóa thành công!");
							}
							location.reload();
						}

						if(result == '0'){
							if(status == '1'){
								alert("Khóa Sale thất bại, vui lòng thử lại!");
							}else{
								alert("Mở khóa Sale thất bại, vui lòng thử lại!");
							}

							location.reload();
						}

				 }
			});
		} else {

		}

  }else{
    alert("Có lỗi, vui lòng kiểm tra và thử lại!");
  }

}

function banUser(userid,username){
  if(userid != ""){
		var r = confirm("Bạn có chắc muốn xóa vĩnh viễn Sale: "+username+"?");
		if (r == true) {
			jQuery.ajax({url: "<?php echo JUri::base(); ?>index.php?option=com_users&view=commission&ajax=1&type=banUser&userid="+userid,
			success: function (result) {
						if(result == '1'){
							alert("Xóa vĩnh viễn thành công!");
							location.reload();
						}

						if(result == '0'){
							alert("Xóa vĩnh viễn Sale thất bại, vui lòng thử lại!");
							location.reload();
						}
				 }
			});
		} else {

		}

  }else{
    alert("Có lỗi, vui lòng kiểm tra và thử lại!");
  }

}

  function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
  }

	function searchName(url){
		// var search = jQuery('#text_search').val();
		// if(search == ''){
		// 	alert("Vui lòng nhập từ khóa cần tìm!");
		// }else{
		// 	window.location = url+"&name="+search;
		// }

	}
	function resetForm(url){
		window.location = url;
	}

function updateLevel(){
	var userid = jQuery('#userid').val();
	var level = jQuery('#level_id').val();
	if(level == ''){
		alert("Vui lòng chọn Level!");
	}else{
		jQuery.ajax({url: "<?php echo JUri::base(); ?>index.php?option=com_users&view=commission&ajax=1&type=changeLevel&userid="+userid+"&level=" + level,
		success: function (result) {

					if(result == '1'){
						alert("Thay đổi Level thành công!");
						location.reload();
					}

					if(result == '0'){
						alert("Thay đổi Level thất bại, vui lòng thử lại!");
						location.reload();
					}

			 }
		});
	}

}
function changeLeve(userid){
			jQuery('#userid').val(userid);
	   jQuery('#myModalTagLevel').modal('toggle');
}
jQuery('#month').change(function () {
	window.location = jQuery('option:selected', this).attr('dir');
});
jQuery('#leader').change(function () {
	window.location = jQuery('option:selected', this).attr('dir');
});
jQuery('#year').change(function () {
	window.location = jQuery('option:selected', this).attr('dir');
});
</script>
