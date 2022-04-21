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
// 
// $listOrder  = $this->escape($this->state->get('list.ordering'));
// $listDirn   = $this->escape($this->state->get('list.direction'));
//
// $month_selected =  $this->state->get('filter.month', date('m'));
// $year_selected =  $this->state->get('filter.year', date('Y'));
// $g_leader = $this->state->get('filter.leader', '');

// $loggeduser = JFactory::getUser();
// $debugUsers = $this->state->get('params')->get('debugUsers', 1);


?>
<div class="">
  <h4>
  Xuất Excel danh sách khách hàng
</h4>
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

		<select name="month" id="export-by-month">
      <option value="100">Tất cả</option>
		<?php for ($ik = 1; $ik <=12; $ik++) {
			$zero= '';
			?>
			<?php if($ik < 10){ $zero =  "0";} ?>
			<option <?php if($ik == $month_selected){ echo 'selected="selected"';} ?>  value="<?php echo $zero.$ik?>">
				<?php echo "Tháng ".$ik; ?> (10/<?php echo $ik-1 == 0?'':$zero; echo $ik-1 == 0?'12':$ik-1; ?> - 09/<?php echo $zero; echo $ik; ?>)</option>
		<?php } ?>
		</select>

		<select name="year" id="export-by-year">
		<?php $current_year = date("Y");
		for ($ik2 = $current_year-1; $ik2 <= $current_year+5; $ik2++) {
			$zero= '';
			?>
			<option <?php if($ik2 == $year_selected){ echo 'selected="selected"';} ?>  value="<?php echo $ik2; ?>"><?php echo "Năm ".$ik2; ?></option>
		<?php } ?>
		</select>

		<select name="type" id="export-type-user">
			<option value="">Loại</option>
      <option value="3" selected>Đại lý</option>
			<option value="2">Khách hàng</option>

		</select>

		</div>
		<div id="export">
			<br>
			<button class="btn btn-warning" onclick="exportExcel()" type="button">Xuất Excel</button>
		</div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>

<script>
function exportExcel(){
    if(jQuery('#export-type-user').val() == ''){
      alert("Vui lòng chọn loại khách hàng!");
    }
    else {
      var year = jQuery('#export-by-year').val();
      var month = jQuery('#export-by-month').val();
      var type_user = jQuery('#export-type-user').val();

      jQuery.ajax({
          url: "<?php echo JUri::base(); ?>index.php?option=com_users&task=sales.exportUsersExcel",
          type : "POST",
          dataType:"text",
          data : {
               month : month,
               year: year,
               type_user: type_user,
          },
          success: function (result) {
              if (result == '0') {
                  alert("Xuất Excel không thành công, vui lòng thử lại!");
              }
              if (result != '0') {
                alert("Xuất Excel thành công!");
              }
          }
      });

    }
  }

</script>
