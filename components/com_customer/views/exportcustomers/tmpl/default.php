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

$customerStatus_selected =  $this->state->get('filter.customerStatus', '');

//$g_leader = $this->state->get('filter.leader', '');

// $loggeduser = JFactory::getUser();
// $debugUsers = $this->state->get('params')->get('debugUsers', 1);

?>
<h3>Xuất ra Excel</h3>
<form action="<?php echo JRoute::_('index.php?option=com_customer&view=exportcustomers'); ?>" method="post" name="adminForm" id="adminForm">


		<!--Filter year start-->
		<select name="filter[customerStatus]" id="filter_customerStatus" onchange="this.form.submit();">
			<option value="0" <?php if($customerStatus_selected == 0) {echo 'selected="selected"';}?>>--Tất cả--</option>
			<option value="1" <?php if($customerStatus_selected == 1) {echo 'selected="selected"';}?>>Khách hàng đang chờ</option>
			<option value="2" <?php if($customerStatus_selected == 2) {echo 'selected="selected"';}?>>Khách hàng lưỡng lự</option>
			<option value="3" <?php if($customerStatus_selected == 3) {echo 'selected="selected"';}?>>Khách hàng quan tâm</option>
			<option value="8" <?php if($customerStatus_selected == 8) {echo 'selected="selected"';}?>>Khách hàng đang chăm sóc</option>
			<option value="7" <?php if($customerStatus_selected == 7) {echo 'selected="selected"';}?>>Hoàn tất</option>
			<option value="6" <?php if($customerStatus_selected == 6) {echo 'selected="selected"';}?>>Khách hàng trả lại</option>
		</select>
		<!--Filter year end-->
		<br/>
		<button class="btn btn-warning export-customer-status" onclick="exportExcel()" type="button">Xuất Excel</button>


		</div>
		<!-- <div id="export">
			<br>
			<button class="btn btn-warning" onclick="exportExcel()" type="button">Xuất Excel</button>
		</div> -->

</form>

<script type="text/javascript">

jQuery(document).ready(function(){
  jQuery('#filter_gr_id_chzn input').prop('readonly', false);
});

function exportExcel(){
	if(jQuery('#filter_search').val() != '' && jQuery('#filter_search').val() != undefined){
		alert("Vui lòng xoá lọc để xuất Excel!");
	}else{

    jQuery.ajax({
        url: "<?php echo JUri::base(); ?>index.php?option=com_customer&task=exportcustomers.exportExcel",
        type : "POST",
          dataType:"text",
        data : {
             status : '<?php echo $this->state->get('filter.customerStatus', '') ?>',
        },
        success: function (result) {
            if (result == '1') {
                alert("Xuất Excel thành công, tải về ngay!");
                location.href = "<?php echo JUri::base(); ?>export/danhsachkhachhang.xlsx";
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

jQuery('#customerStatus').change(function () {
	window.location = jQuery('option:selected', this).attr('dir');
});

</script>

