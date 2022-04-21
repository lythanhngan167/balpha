<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$config = new JConfig();
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.keepalive');

$month_selected =  $this->state->get('filter.month', date('m'));
$year_selected =  $this->state->get('filter.year', date('Y'));
$project_id = $this->state->get('filter.project_id', AT_PROJECT);
$project_id_selected = $project_id;

$to_date = '';
$from_date = '';
$from_date = $_REQUEST['jform']['from_date'];
$to_date = $_REQUEST['jform']['to_date'];

?>
<br>
<form action="<?php echo JRoute::_('index.php?option=com_customer&view=report'); ?>" method="post" name="adminForm" id="adminFormSearch">

	<?php if (!empty( $this->sidebar)) : ?>
		<div id="j-sidebar-container" class="span0">
		<?php echo $this->sidebar; ?>
		</div>
		<div id="j-main-container" class="span12">
	<?php else : ?>
		<div id="j-main-container">
	<?php endif; ?>
		<div id="filter-all">
			<div class="filter-form">
			<select name="filter[project_id]" id="filter_project_id" onchange="this.form.submit();">
	    <option <?php if($project_id_selected == ''){ echo 'selected="selected"';} ?> value="" >
	      Tất cả Dự án
	    </option>
			<?php foreach ($this->listProjects as $key => $project): ?>
				<option <?php if($project->id == $project_id_selected){ echo 'selected="selected"';} ?>  value="<?php echo $project->id; ?>">
					<?php echo $project->title; ?> </option>
			<?php endforeach; ?>

			</select>
			</div>
			<div class="filter-form">
			<select name="filter[year]" id="filter_year" onchange="this.form.submit();">
			<?php $current_year = date("Y");
			for ($ik2 = $current_year-2; $ik2 <= $current_year+3; $ik2++) {
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
				<a href="<?php echo JRoute::_('index.php?option=com_customer&view=report&clear=1'); ?>">Bỏ lọc</a>
			</div>
			<div class="filter-date">

		<!-- <input name="phone_biznet_id" value="<?php echo $_REQUEST['keyword']; ?>"  class="js-stools-search-string" placeholder="Số Điện thoại" type="text" id="phone-biznet-id" /> -->
		<button id="search-report" class="btn btn-warning" type="submit">Tìm & Thống kê</button>

		</div>

		</div>
		<div class="user-data">
			<?php //print_r($this->customerActive); ?>
			<br>
				<div class="user-item">
					<div class="user-item-left">
						<b>Data mới ( đã bán cho TVV )</b>
					</div>
					<div class="user-item-right">
						<?php echo $this->newData;  ?>
					</div>
				</div>
			<div class="user-item">
				<div class="user-item-left">
					<b>Data Đăng ký lại ( trùng với BCA )</b>
				</div>
				<div class="user-item-right">
					<?php echo $this->regisAgainData;  ?>
				</div>
			</div>
			<div class="user-item">
				<div class="user-item-left">
					<b>Data Trùng</b>
				</div>
				<div class="user-item-right">
					<?php echo $this->existData;  ?>
				</div>
			</div>
			<div class="user-item">
				<div class="user-item-left">
					<b>Data cho vào Sọt rác</b>
				</div>
				<div class="user-item-right">
					<?php echo $this->trashData;  ?>
				</div>
			</div>
			<div class="user-item">
				<div class="user-item-left">
					<b>Data cho vào Sọt rác đã hoàn tiền</b>
				</div>
				<div class="user-item-right">
					<?php echo $this->trashDataReturnMoney;  ?>
				</div>
			</div>
			<div class="user-item">
				<div class="user-item-left">
					<b>Data cho vào Sọt rác đã hủy ( Rejected AT )</b>
				</div>
				<div class="user-item-right">
					<?php echo $this->rejectedData;  ?>
					<?php if($project_id_selected == AT_PROJECT){ ?>
					( <a href="#" onclick="exportExcel(<?php echo $project_id_selected; ?>,<?php echo $month_selected; ?>,<?php echo $year_selected; ?>,)">Xuất Excel <?php echo $month_selected."/".$year_selected ?></a>)
				<?php } ?>
				</div>
			</div>
			<div class="user-item">
				<div class="user-item-left">
					<b>Data Đã xác nhận ( Approved AT )</b>
				</div>
				<div class="user-item-right">
					<?php echo $this->confirmedData;  ?>
				</div>
			</div>
			<div class="user-item">
				<div class="user-item-left">
					<b>Data Đang xác minh</b>
				</div>
				<div class="user-item-right">
					<?php echo $this->pendingData;  ?>
				</div>
			</div>

		</div>
	</div>
</form>

<script type="text/javascript">

jQuery(document).ready(function(){
	jQuery('#jform_to_date').val('<?php echo $to_date != '' ? $to_date : ''; ?>');
	jQuery("#jform_to_date").attr("data-alt-value","<?php echo $to_date != '' ? $to_date : ''; ?>");
	jQuery("#jform_to_date").attr("data-local-value","<?php echo $to_date != '' ? $to_date : ''; ?>");

	jQuery('#jform_from_date').val('<?php echo $from_date != '' ? $from_date : ''; ?>');
	jQuery("#jform_from_date").attr("data-alt-value","<?php echo $from_date != '' ? $from_date : ''; ?>");
	jQuery("#jform_from_date").attr("data-local-value","<?php echo $from_date != '' ? $from_date : ''; ?>");

});

function exportExcel(project_id, month, year){
	if(project_id == '' || month == '' || year == ''){
		alert("Vui lòng nhập đầy đủ Dự án, Tháng, Năm!");
	}else{
    jQuery.ajax({
        url: "<?php echo JUri::base(); ?>index.php?option=com_customer&task=report.exportExcelRejectAT",
        type : "POST",
        dataType:"text",
        data : {
             month : month,
             year: year,
             project_id: project_id
        },
        success: function (result) {
            if (result == '1') {
                alert("Xuất Excel thành công, tải về ngay!");
                location.href = "<?php echo JUri::base(); ?>export/rejectAT.xlsx";
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

function searchReport(){
// 	if(jQuery('#filter_search').val() != '' && jQuery('#filter_search').val() != undefined){
// 		alert("Vui lòng xoá lọc Tìm kiếm để xuất Excel, chỉ chọn Tháng, Năm và Loại Thành viên !");
// 	}else{
//     if(jQuery('#filter_gr_id').val() == ''){
//       alert("Vui lòng chọn Loại Thành viên!");
//     }else{
//     jQuery.ajax({
//         url: "<?php echo JUri::base(); ?>index.php?option=com_users&task=exportusers.exportExcel",
//         type : "POST",
//           dataType:"text",
//         data : {
//              month : '<?php echo $this->state->get('filter.month', '') ?>',
//              year: '<?php echo $this->state->get('filter.year', '') ?>',
//              gr_id: '<?php echo $this->state->get('filter.gr_id', 3) ?>'
//         },
//         success: function (result) {
//             if (result == '1') {
//                 alert("Xuất Excel thành công, tải về ngay!");
//                 location.href = "<?php echo JUri::base(); ?>export/thanhvienbiznet.xlsx";
//             }
//             if (result == '0') {
//                 alert("Xuất Excel không thành công, vui lòng thử lại!");
//                 location.reload();
//                 return;
//             }
//
//         }
//     });
//     }
// 	}
}


jQuery.fn.pressEnter = function(fn) {
    return this.each(function() {
        jQuery(this).bind('enterPress', fn);
        jQuery(this).keyup(function(e){
            if(e.keyCode == 13)
            {
              jQuery(this).trigger("enterPress");
            }
        })
    });
 };

jQuery('#phone-biznet-id').pressEnter(function(){
	searchCustomer();
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
.reports{
	display:block;
}
.reports-item{
	float:left;
	display:block;
	width: 20%;
	min-height	:100px
}
.reports-item-data{
	display:block;
	font-weight:bold;
	padding-top:10px;
}
.reports *{
	font-size:12px;
}

input[type="text"]{
	margin-bottom: 0px;
}
#adminFormSearch{
	padding-left:40px;
}
.user-item-left{
	float:left;
	width:350px;
}
.user-item-right{
	float:left;
	width:1000px;
}
.user-item{
	clear:both;
	padding-bottom:35px;
}
.no-data{
	color:red;
}
.price{
	color:red;
}
.landingpage-active{
	color:green;
}
.landingpage-blocked{
	color:orange;
}
.modal-header .close{
	line-height: 10px;
}
.modal-body{
	padding-top: 15px;
	padding-left: 15px;
	padding-bottom: 15px;
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
</style>
