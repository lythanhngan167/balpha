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

$month_selected =  $this->state->get('filter.month', date('m'));
$year_selected =  $this->state->get('filter.year', date('Y'));


$to_date = '';
$from_date = '';
$from_date = $_REQUEST['jform']['from_date'];
$to_date = $_REQUEST['jform']['to_date'];

?>


<br>

<br>

<form action="<?php echo JRoute::_('index.php?option=com_users&view=exportbizxu'); ?>" method="post" name="adminForm" id="adminForm">

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
		<div class="filter-form">
		<select name="filter[year]" id="filter_year" >
			<!-- onchange="this.form.submit();" -->
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
	</div>
	<div class="filter-form">
		<select name="filter[month]" id="filter_month">
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
	</div>
		<div class="filter-date">

			<div class="filter-form ">
				<?php echo $this->form->renderField('from_date'); ?>
			</div>
			<div class="filter-form">
				<?php echo $this->form->renderField('to_date'); ?>
			</div>
		</div>
		<div class="filter-clear">
			<a href="<?php echo JRoute::_('index.php?option=com_users&view=exportbizxu&clear=1'); ?>">Bỏ lọc</a>
		</div>
		<div class="filter-date">
				<!-- <button id="search-report" class="btn btn-warning" type="submit">Tìm & Thống kê</button> -->
				<div id="export">
					<br>
					<button class="btn btn-warning" onclick="exportRestBizXuExcel()" type="button">Xuất Excel</button>
					<div id="loading-export" style="display:none;">Đang Xuất Excel ... Vui lòng chờ trong giây lát!</div>
				</div>
		</div>

		</div>

		<?php
		if (empty($this->items)) : ?>
			<!-- <div class="alert alert-no-items">
				<?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
			</div> -->
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
});

function exportRestBizXuExcel(){

	jQuery('#loading-export').css('display','block');
	var okForm = false;
	var from_date = jQuery('#jform_from_date').val();
	var to_date = jQuery('#jform_to_date').val();
	var year = jQuery('#filter_year').val();
	var month = jQuery('#filter_month').val();

	if(from_date == '' || to_date == ''){
		if(jQuery('#filter_year').val() == '0'){
			alert('Vui lòng chọn Năm!');
			okForm = false;
			return;
		}else if(jQuery('#filter_month').val() == ''){
			alert('Vui lòng chọn Tháng!');
			okForm = false;
			return;
		}else{
			if(to_date == '' && from_date == ''){
				okForm = true;
			}else{
				if(to_date == '' || from_date == ''){
					okForm = false;
					alert("Vui lòng nhập Ngày bắt đầu và Ngày kết thúc!");
					return;
				}else{
					okForm = true;
				}
			}
		}
	}else{
		okForm = true;
	}

	if(okForm){
		jQuery.ajax({
				url: "<?php echo JUri::base(); ?>index.php?option=com_users&task=exportbizxu.exportRestBizXuExcel",
				type : "POST",
          dataType:"text",
				data : {
						 month : month,
						 year: year,
						 from_date: from_date,
						 to_date: to_date,
				},
				success: function (result) {
						if (result == '1') {
								alert("Xuất Excel thành công, tải về ngay!");
								location.href = "<?php echo JUri::base(); ?>export/sodubizxu.xlsx";

						}
						if (result == '0') {
								alert("Xuất Excel không thành công, vui lòng thử lại!");
								location.reload();
								return;
						}
						jQuery('#loading-export').css('display','none');
				}
		});
	}




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

#search-report{
	margin-bottom: 9px;
}
.filter-form{
	float: left;
	padding-right: 10px;
}
.filter-date{
	clear: both;
}
.filter-date{
	padding-top: 10px;
}
.filter-clear{
	padding-top: 25px;
}
#loading-export{
	padding-top: 10px;
	color:orange;
}
</style>
