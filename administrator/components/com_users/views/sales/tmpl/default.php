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

// $listOrder  = $this->escape($this->state->get('list.ordering'));
// $listDirn   = $this->escape($this->state->get('list.direction'));
// $loggeduser = JFactory::getUser();
// $debugUsers = $this->state->get('params')->get('debugUsers', 1);
$current_month = date("m");

if(!isset($_GET['month']) || $_GET['month'] <= 0 || $_GET['month'] > 12){
	$month = date("m");
}else{
	$month = $_GET['month'];
}

$current_year = (int)date("Y");

if(!isset($_GET['year']) || $_GET['year'] <= 0 || $_GET['year'] > $current_year ){
	$year = $current_year;
}else{
	$year = $_GET['year'];
}

if(isset($_GET['leader']) && $_GET['leader'] > 0){
	$g_leader = $_GET['leader'];
	$url_leader = '&leader='.$_GET['leader'];
}else{
	$g_leader = 0;
	$url_leader = '';
}

?>

<form action="<?php echo JRoute::_('index.php?option=com_users&view=sales'); ?>" method="post" name="adminForm" id="adminForm">


	<?php if (!empty( $this->sidebar)) : ?>
		<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
		</div>
		<div id="j-main-container" class="span10">
	<?php else : ?>
		<div id="j-main-container">
	<?php endif; ?>
		<?php
		// Search tools bar
		//echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));

		?>
		<select name="month" id="month">
		<?php for ($ik = 1; $ik <=12; $ik++) {
			$zero= '';
			?>
			<?php if($ik < 10){ $zero =  "0";} ?>
						<option <?php if($ik == $month){ echo 'selected="selected"';} ?> dir="<?php echo JRoute::_('index.php?option=com_users&view=sales&month='.$zero.$ik."&year=".$year.$url_leader); ?>" value="<?php echo $zero.$ik?>"><?php echo "Tháng ".$ik; ?></option>
		<?php } ?>
		</select>
		<select name="year" id="year">
		<?php for ($ik2 = $current_year-1; $ik2 <= $current_year; $ik2++) {
			$zero= '';
			?>

						<option <?php if($ik2 == $year){ echo 'selected="selected"';} ?> dir="<?php echo JRoute::_('index.php?option=com_users&view=sales&month='.$month."&year=".$ik2.$url_leader); ?>" value="<?php echo $ik2; ?>"><?php echo "Năm ".$ik2; ?></option>
		<?php } ?>
		</select>

		<select name="leader" id="leader">
			<option value="" dir="<?php echo JRoute::_('index.php?option=com_users&view=sales&month='.$month.'&year='.$year); ?>">Chọn Leader</option>
		<?php foreach ($this->listLeader as $leader) :

			?>

						<option <?php if($g_leader == $leader->id){ echo 'selected="selected"';} ?> dir="<?php echo JRoute::_('index.php?option=com_users&view=sales&month='.$month.'&year='.$year.'&leader='.$leader->id); ?>" value="<?php echo $leader->id; ?>"><?php echo $leader->username; ?></option>
		<?php endforeach; ?>
		</select>

		<input name="text_search" value="<?php echo $_GET['name']; ?>" style="margin-top:10px;" class="js-stools-search-string" placeholder="Họ tên hoặc username" type="text" id="text_search" />&nbsp;&nbsp;<button class="btn btn-primary" type="button" onclick="searchName('<?php echo JUri::base(); ?>index.php?option=com_users&view=sales&month=<?php echo $month; ?>&year=<?php echo $year; ?>')">Tìm</button>&nbsp;&nbsp;&nbsp;<button onclick="resetForm('<?php echo JUri::base(); ?>index.php?option=com_users&view=sales&month=<?php echo $month; ?>&year=<?php echo $year; ?>')" type="button" class="btn btn-danger">Xóa</button>
		<?php if (empty($this->items)) : ?>
			<div class="alert alert-no-items">
				<?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
			</div>
		<?php else : ?>
			<table class="table table-striped" id="userList">
				<thead>
					<tr>
						<!-- <th width="1%" class="nowrap center">
							<?php echo JHtml::_('grid.checkall'); ?>
						</th> -->
						<th width="5%" class="nowrap hidden-phone">
							<?php echo JHtml::_('searchtools.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
						</th>
						<th class="nowrap">
							<?php echo JHtml::_('searchtools.sort', 'Họ tên', 'a.name', $listDirn, $listOrder); ?>
						</th>
						<th width="20%" class="nowrap">
							<?php echo JHtml::_('searchtools.sort', 'Tên đăng nhập', 'a.username', $listDirn, $listOrder); ?>
						</th>
						<!-- <th width="10%" class="nowrap">
							<a href="#">Tổng BizXu</a>
						</th> -->
						<th width="10%" class="nowrap">
							<a href="#">Level</a>
						</th>
						<th width="10%" class="nowrap">
							<a href="#">Team Leader</a>
						</th>
						<th width="10%" class="nowrap center">
							<?php echo JHtml::_('searchtools.sort', 'Trạng thái', 'a.block', $listDirn, $listOrder); ?>
						</th>
						<!-- <th width="5%" class="nowrap center hidden-phone">
							<?php echo JHtml::_('searchtools.sort', 'COM_USERS_HEADING_ACTIVATED', 'a.activation', $listDirn, $listOrder); ?>
						</th> -->
						<!-- <th width="10%" class="nowrap">
							<?php echo JText::_('COM_USERS_HEADING_GROUPS'); ?>
						</th> -->
						<th width="20%" class="nowrap hidden-phone hidden-tablet">
							<?php echo JHtml::_('searchtools.sort', 'JGLOBAL_EMAIL', 'a.email', $listDirn, $listOrder); ?>
						</th>
						<!-- <th width="10%" class="nowrap hidden-phone hidden-tablet">
							<?php echo JHtml::_('searchtools.sort', 'COM_USERS_HEADING_LAST_VISIT_DATE', 'a.lastvisitDate', $listDirn, $listOrder); ?>
						</th>
						<th width="10%" class="nowrap hidden-phone hidden-tablet">
							<?php echo JHtml::_('searchtools.sort', 'COM_USERS_HEADING_REGISTRATION_DATE', 'a.registerDate', $listDirn, $listOrder); ?>
						</th> -->
						<th><a href="#">Xác nhận</a></th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<td colspan="10">
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
							<?php echo (int) $item->id; ?>
						</td>
						<td>
							<div class="name break-word">
							<?php if ($canEdit) : ?>
								<a href="<?php echo JRoute::_('index.php?option=com_users&task=user.edit&id=' . (int) $item->id); ?>" title="<?php echo JText::sprintf('COM_USERS_EDIT_USER', $this->escape($item->name)); ?>">
									<?php echo $this->escape($item->name); ?></a>
							<?php else : ?>
								<?php echo $this->escape($item->name); ?>
							<?php endif; ?>
							<div class="leader"><?php if($item->is_leader == 1){ echo 'Là Teamleader'; }else{ } ?></div>
							<?php if($item->banned == 0){ ?>
								<!-- <div><a href="#" onclick="banUser(<?php echo $item->id; ?>,'<?php echo $item->username; ?>')">Xóa vĩnh viễn</a></div> -->
							<?php } ?>

							</div>
							<div class="btn-group">
								<?php //echo JHtml::_('users.filterNotes', $item->note_count, $item->id); ?>
								<?php //echo JHtml::_('users.notes', $item->note_count, $item->id); ?>
								<?php //echo JHtml::_('users.addNote', $item->id); ?>
							</div>
							<?php //echo JHtml::_('users.notesModal', $item->note_count, $item->id); ?>
							<?php if ($item->requireReset == '1') : ?>
								<span class="label label-warning"><?php echo JText::_('COM_USERS_PASSWORD_RESET_REQUIRED'); ?></span>
							<?php endif; ?>
							<?php if ($debugUsers) : ?>
								<div class="small"><a href="<?php echo JRoute::_('index.php?option=com_users&view=debuguser&user_id=' . (int) $item->id); ?>">
								<?php echo JText::_('COM_USERS_DEBUG_USER'); ?></a></div>
							<?php endif; ?>
						</td>
						<td class="break-word">
							<?php echo $this->escape($item->username); ?>

						</td>
						<!-- <td class="break-word">
							<?php $dataOrder = $this->getMoneySaleByMonth($item->id,$month,$year); ?>
							<span class="price"><?php echo number_format($dataOrder->total,0,",","."); ?> đ</span>
						</td> -->
						<td class="break-word">

							<span><?php echo $item->level; ?></span>
							<!-- <div><a href="#" onclick="changeLeve(<?php echo $item->id; ?>)">
								Thay đổi Level
							</a>
						</div> -->
						</td>
						<td class="break-word">

							<span><?php echo JFactory::getUser($item->parent_id)->username; ?></span>

						</div>
						</td>
						<td class="center">
							<?php if ($canChange) : ?>
								<?php
								$self = $loggeduser->id == $item->id;
								//echo JHtml::_('jgrid.state', JHtmlUsers::blockStates($self), $item->block, $i, 'users.', !$self);
								?>
							<?php else : ?>
								<?php if($item->banned == 1){ ?><label class="btn btn-danger">Xóa vĩnh viễn</labe><?php }else{
									echo JText::_($item->block ? '<label class="btn-warning">Tạm khóa</lable>' : '<label class="btn-success">Hoạt động</labe>');
								} ?>

							<?php endif; ?>
						</td>
						<!-- <td class="center hidden-phone">
							<?php
							$activated = empty( $item->activation) ? 0 : 1;
							//echo JHtml::_('jgrid.state', JHtmlUsers::activateStates(), $activated, $i, 'users.', (boolean) $activated);
							?>
						</td> -->
						<!-- <td>
							<?php if (substr_count($item->group_names, "\n") > 1) : ?>
								<span class="hasTooltip" title="<?php echo JHtml::_('tooltipText', JText::_('COM_USERS_HEADING_GROUPS'), nl2br($item->group_names), 0); ?>"><?php echo JText::_('COM_USERS_USERS_MULTIPLE_GROUPS'); ?></span>
							<?php else : ?>
								<?php echo nl2br($item->group_names); ?>
							<?php endif; ?>
						</td> -->
						<td class="hidden-phone break-word hidden-tablet">
							<?php echo JStringPunycode::emailToUTF8($this->escape($item->email)); ?>
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
						<td>
							<?php if($current_month != $month && $dataOrder->total > 0){ ?>
							<?php $checkPayment = $this->checkPayment($item->id,$month."-".$year); ?>
							<?php if($checkPayment ==1){ ?><label class="btn btn-success" type="button">Đã thanh toán</label><?php } ?>
							<?php if($checkPayment ==0){ ?>
								<button class="btn btn-warning" type="button" onclick="confirmPayment(<?php echo $item->id; ?>,'<?php echo $item->username; ?>','<?php echo $month."-".$year ?>',<?php echo $dataOrder->total; ?>)">Chưa thanh toán</button>

							<?php } ?>
							<?php } ?>
							<div><?php if($item->block == 0){ ?><button class="btn btn-danger blockuser" type="button" onclick="blockUser(<?php echo $item->id; ?>,'<?php echo $item->username; ?>',1)">Khóa</button>&nbsp;<?php } ?><?php if($item->block == 1 && $item->banned == 0){ ?> <button class="btn btn-success blockuser" onclick="blockUser(<?php echo $item->id; ?>,'<?php echo $item->username; ?>',0)" type="button">Mở khóa</button> <?php } ?></div>
						</td>
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

<div class="modal hide fade" style="width:400px;" id="myModalTagLevel" role="dialog">
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
<script>
function updateLevel(){
	var userid = jQuery('#userid').val();
	var level = jQuery('#level_id').val();
	if(level == ''){
		alert("Vui lòng chọn Level!");
	}else{
		jQuery.ajax({url: "<?php echo JUri::base(); ?>index.php?option=com_users&view=sales&ajax=1&type=changeLevel&userid="+userid+"&level=" + level,
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

<script type="text/javascript">
function confirmPayment(userid,username,monthyear,total){
  if(userid != "" &&  monthyear != '' && total != ''){
		var r = confirm("Bạn có chắc muốn xác nhận đã thanh toán cho Sale: "+username+" với số tiền: "+numberWithCommas(total)+" đ");
		if (r == true) {
			jQuery.ajax({url: "<?php echo JUri::base(); ?>index.php?option=com_users&view=sales&ajax=1&type=confirmPayment&userid="+userid+"&total=" + total +"&monthyear="+monthyear,
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
			jQuery.ajax({url: "<?php echo JUri::base(); ?>index.php?option=com_users&view=sales&ajax=1&type=blockUser&userid="+userid+"&status="+status,
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
			jQuery.ajax({url: "<?php echo JUri::base(); ?>index.php?option=com_users&view=sales&ajax=1&type=banUser&userid="+userid,
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
		var search = jQuery('#text_search').val();
		if(search == ''){
			alert("Vui lòng nhập từ khóa cần tìm!");
		}else{
			window.location = url+"&name="+search;
		}

	}
	function resetForm(url){
		window.location = url;
	}
</script>
<style>
.blockuser{ margin-top:8px; }
.leader{color:orange; padding-top: 5px;}
</style>
