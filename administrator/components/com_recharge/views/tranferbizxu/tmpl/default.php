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


?>


<br>

<br>

<form action="<?php echo JRoute::_('index.php?option=com_recharge&view=exportrechargesbdm'); ?>" method="post" name="adminForm" id="adminForm">

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





		<!--Filter date start-->
		<select name="filter[agent_from]" id="agent_from" >
			<option value="" <?php if($sbdm_user_selected == ''): ?> selected <?php endif; ?>>Tất cả Đại lý - Người chuyển</option>
			<?php
				foreach($this->users as $user){
			?>
				<option dir="<?php echo  $user->name . " - ". $user->username." - ".$user->id_biznet; ?>" <?php if($user->id == $sbdm_user_selected){ echo 'selected="selected"';} ?> value="<?php echo $user->id?>" ><?php echo  $user->name . " - ". $user->username." - ".$user->id_biznet; ?></option>
			<?php
				}
			?>
		</select>
		<!--Filter date end-->

		<!--Filter date start-->
		<select name="filter[agent_to]" id="agent_to">
			<option value="" <?php if($sbdm_user_selected == ''): ?> selected <?php endif; ?>>Tất cả Đại lý - Người nhận</option>
			<?php
				foreach($this->users as $user){
			?>
				<option dir="<?php echo  $user->name . " - ". $user->username." - ".$user->id_biznet; ?>" <?php if($user->id == $sbdm_user_selected){ echo 'selected="selected"';} ?> value="<?php echo $user->id?>" ><?php echo  $user->name . " - ". $user->username." - ".$user->id_biznet; ?></option>
			<?php
				}
			?>
		</select>
		<br><br>
		&nbsp;&nbsp;Số tiền:&nbsp;&nbsp;<input type="number" id="money" name="money" > <span id="full-bizxu"></span>
		<!--Filter date end-->
		&nbsp;&nbsp;<br><br><button class="btn btn-warning" onclick="tranferBizXu()" type="button">Chuyển BizXu</button>


		</div>
		<!-- <div id="export">
			<br>
			<button class="btn btn-warning" onclick="exportExcel()" type="button">Xuất Excel</button>
		</div> -->

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

});

jQuery('#money').keyup(function() {
	var bizxucommas = addCommas(jQuery('#money').val());
	jQuery('#full-bizxu').html(bizxucommas+' BizXu');
});

function tranferBizXu(){
	var agent_from = jQuery('#agent_from').val();
	var agent_to = jQuery('#agent_to').val();
	var money = jQuery('#money').val();
	if(agent_from == '' || agent_to == ''){
		alert("Vui lòng chọn Người chuyển hoặc Người nhận!");
	}else{
		if(money < 100000){
			alert("Vui lòng nhập số BizXu cần chuyển, ít nhất 100.000 BizXu!");
		}else{
			var bizxucommas = addCommas(jQuery('#money').val());
			var agent_from_dir = jQuery("#agent_from option[value='" + agent_from + "']").attr('dir');
			var agent_to_dir = jQuery("#agent_to option[value='" + agent_to + "']").attr('dir');
			var r = confirm("Bạn có chắc muốn chuyển "+bizxucommas+" BizXu \nTừ "+agent_from_dir+" \nĐến "+agent_to_dir+" ?");
			if (r == true) {
				jQuery.ajax({
		        url: "<?php echo JUri::base(); ?>index.php?option=com_recharge&task=tranferbizxu.tranferBizXu",
		        type : "POST",
		          dataType:"text",
		        	data : {
		             agent_from : agent_from,
		             agent_to: agent_to,
		             money: money
		        },
		        success: function (result) {
		            if (result == '1') {
		                alert("Chuyển BizXu thành công!");
		            }
								if (result == '2') {
		                alert("Không đủ BizXu để chuyển!");
		            }
		            if (result == '0') {
		                alert("Chuyển BizXu không thành công, vui lòng thử lại.");
		                location.reload();
		                return;
		            }

		        }
		    });
			}

		}

	}
}

function addCommas(nStr)
{
    nStr += '';
    x = nStr.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return x1 + x2;
}

</script>



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
#filter_gr_id_chzn .chzn-search{
	position: relative!important;
	left: 0!important;
}

#full-bizxu{
	color:red;
	font-weight: bold;
	padding-left: 15px;
}
#agent_from_chzn{
	width: 310px!important;
}
#agent_to_chzn{
	width: 310px!important;
}

</style>
