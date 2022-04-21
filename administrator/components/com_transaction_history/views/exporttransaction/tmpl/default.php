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


?>


<br>

<br>

<form action="<?php echo JRoute::_('index.php?option=com_transaction_history&view=exporttransaction'); ?>" method="post" name="adminForm" id="adminForm">

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

		<!--Filter year start-->
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
		<!--Filter year end-->

		<!--Filter month start-->
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
		<!--Filter month end-->

		<!--Filter date start-->
		<!-- <select name="filter[day]" id="day" onchange="this.form.submit();">
			<option value="" <?php if($day == ''): ?> selected <?php endif; ?>>Tất cả Ngày</option>
			<?php
				foreach($this->dates as $date){
			?>
				<option <?php if($date == $day){ echo 'selected="selected"';} ?> value="<?php echo $date?>" ><?php echo "Ngày " . $date?></option>
			<?php
				}
			?>
		</select> -->
		<!--Filter date end-->

		&nbsp;&nbsp;<a href="<?php echo JRoute::_('index.php?option=com_transaction_history&view=exporttransaction&clear=1'); ?>"><button  type="button" class="btn btn-danger btn hasTooltip js-stools-btn-clear">Xóa</button></a>
		&nbsp;&nbsp;<br><br><button class="btn btn-warning" onclick="exportExcel()" type="button">Xuất Excel</button>


		</div>


</form>

<script type="text/javascript">
jQuery(document).ready(function(){
  jQuery('#filter_gr_id_chzn input').prop('readonly', false);
});

function exportExcel(){
	if(jQuery('#filter_year').val() == '0'){
		alert("Vui lòng chọn Năm!");
	}else{
    jQuery.ajax({
        url: "<?php echo JUri::base(); ?>index.php?option=com_transaction_history&task=exporttransaction.exportExcel",
        type : "POST",
          dataType:"text",
        data : {
             month : '<?php echo $this->state->get('filter.month', '') ?>',
             year: '<?php echo $this->state->get('filter.year', '') ?>',
        },
        success: function (result) {
            if (result == '1') {
                alert("Xuất Excel thành công, tải về ngay!");
                location.href = "<?php echo JUri::base(); ?>export/lichsugiaodich.xlsx";
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


jQuery('#month').change(function () {
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
