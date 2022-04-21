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

$month_selected =  $this->state->get('filter.month', '');
$year_selected =  $this->state->get('filter.year', '');
$gr_id_selected =  $this->state->get('filter.gr_id', 3);

//$g_leader = $this->state->get('filter.leader', '');

// $loggeduser = JFactory::getUser();
// $debugUsers = $this->state->get('params')->get('debugUsers', 1);

?>


<br>

<br>

<form action="<?php echo JRoute::_('index.php?option=com_users&view=exportusers'); ?>" method="post" name="adminForm" id="adminForm">

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

		<select name="filter[year]" id="filter_year" onchange="this.form.submit();">
		<?php $current_year = date("Y");
		for ($ik2 = $current_year-2; $ik2 <= $current_year+5; $ik2++) {
			$zero= '';
			?>
      <?php
      if ($ik2 == $current_year-2) { ?>
        <option <?php if($ik2 == $year_selected){ echo 'selected="selected"';} ?>  value="0">
          <?php echo "Tất cả Năm"; ?> </option>
      <?php }
        else{ ?>
          <option <?php if($ik2 == $year_selected){ echo 'selected="selected"';} ?>  value="<?php echo $ik2; ?>">
    				<?php echo "Năm ".$ik2; ?> </option>
      <?php  }
    }
       ?>

		</select>

		<select name="filter[month]" id="filter_month" onchange="this.form.submit();">
    <option <?php if($month_selected == ''){ echo 'selected="selected"';} ?> value="" >
      Tất cả Tháng
    </option>
    <?php for ($ik = 1; $ik <=12; $ik++) {
			$zero= '';
			?>
  <?php if($ik < 10){ $zero =  "0";} ?>
       <option <?php if($ik == $month_selected){ echo 'selected="selected"';} ?>  value="<?php echo $zero.$ik?>">
         <?php echo "Tháng ".$ik; ?> </option>
		<?php } ?>
		</select>



		<select name="filter[gr_id]" id="filter_gr_id" onchange="this.form.submit();">
      <!-- <option value="">Loại Thành viên</option> -->
      <option value="3" <?php if($gr_id_selected == 3): ?> selected <?php endif; ?>>Đại lý</option>
			<option value="2"<?php if($gr_id_selected == 2): ?> selected <?php endif; ?>>Khách hàng</option>
		</select>

		&nbsp;&nbsp;
		<a href="<?php echo JRoute::_('index.php?option=com_users&view=exportusers&clear=1'); ?>"><button  type="button" class="btn btn-danger btn hasTooltip js-stools-btn-clear">Bỏ lọc</button></a>

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
  jQuery('#filter_gr_id_chzn input').prop('readonly', false);
});

function exportExcel(){
	if(jQuery('#filter_search').val() != '' && jQuery('#filter_search').val() != undefined){
		alert("Vui lòng xoá lọc Tìm kiếm để xuất Excel, chỉ chọn Tháng, Năm và Loại Thành viên !");
	}else{
    if(jQuery('#filter_gr_id').val() == ''){
      alert("Vui lòng chọn Loại Thành viên!");
    }else{
    jQuery.ajax({
        url: "<?php echo JUri::base(); ?>index.php?option=com_users&task=exportusers.exportExcel",
        type : "POST",
          dataType:"text",
        data : {
             month : '<?php echo $this->state->get('filter.month', '') ?>',
             year: '<?php echo $this->state->get('filter.year', '') ?>',
             gr_id: '<?php echo $this->state->get('filter.gr_id', 3) ?>'
        },
        success: function (result) {
            if (result == '1') {
                alert("Xuất Excel thành công, tải về ngay!");
                location.href = "<?php echo JUri::base(); ?>export/thanhvienbiznet.xlsx";
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

</style>
