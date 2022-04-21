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

$membersLevel_selected =  $this->state->get('filter.membersLevel', '');

//$g_leader = $this->state->get('filter.leader', '');

// $loggeduser = JFactory::getUser();
// $debugUsers = $this->state->get('params')->get('debugUsers', 1);

if($this->user_id) {
    $action = JRoute::_('index.php?option=com_users&view=members&id='. $this->user_id);
} else {
    $action = JRoute::_('index.php?option=com_users&view=members');
}
?>
<h3>Tuyến dưới</h3>
<form action="<?php echo $action; ?>" method="post" name="form" id="form">
		<!--Filter member level start-->
		<select name="filter[membersLevel]" id="filter_membersLevel" onchange="this.form.submit();">
			<option value="0" <?php if($membersLevel_selected == 0) {echo 'selected="selected"';}?>>--Tất cả ĐL--</option>
			<option value="1" <?php if($membersLevel_selected == 1) {echo 'selected="selected"';}?>>Cấp 1</option>
			<option value="2" <?php if($membersLevel_selected == 2) {echo 'selected="selected"';}?>>Cấp 2</option>
			<option value="3" <?php if($membersLevel_selected == 3) {echo 'selected="selected"';}?>>Cấp 3</option>
			<option value="4" <?php if($membersLevel_selected == 4) {echo 'selected="selected"';}?>>Cấp 4</option>
			<option value="5" <?php if($membersLevel_selected == 5) {echo 'selected="selected"';}?>>Cấp 5</option>
			<option value="6" <?php if($membersLevel_selected == 6) {echo 'selected="selected"';}?>>Cấp 6</option>
            <option value="7" <?php if($membersLevel_selected == 7) {echo 'selected="selected"';}?>>Cấp 7</option>
		</select>
		<!--Filter member level start-->
</form>

<table class="table">
    <thead>
        <tr>
            <th>
                Họ tên
            </th>
            <th>
                Mã TV
            </th>
            <th>
                Cấp ĐL
            </th>
            <th>
                Tỉnh T/P
            </th>
            <th>
                Doanh số cá nhân
            </th>
            <th>
                Ngày tạo
            </th>
            <th>
                Trạng thái
            </th>
            <th>
               
            </th>
            <th>
           
            </th>
    </thead>
    <tbody>
        <?php
            foreach($this->items as $item) {
        ?>
            <tr>
                <td>
                    <?php echo $this->escape($item->name)?>
                </td>
                <td>
                    <?php echo $this->escape($item->m_id)?>
                </td>
                <td>
                    <?php echo $this->escape($item->level)?>
                </td>
                <td>
                    <?php echo $this->escape($item->province)?>
                </td>
                <td style="color:red;">
                    <?php echo number_format($this->escape($item->am_revenue),0,".   ","."). ' Bizxu'?>
                </td>
                <td>
                    <?php echo $item->registerDate?>
                </td>
                <td>
                    <?php if((int)$item->approved === 9) {echo  '<span class="badge badge-primary">Đã phê duyệt</span>';} ?>
                    <?php if((int)$item->approved === 0) {echo '<span class="badge badge-info">Chưa cập nhật thông tin</span>';} ?>
                    <?php if((int)$item->approved === 1) {echo '<span class="badge badge-danger">Đã cập nhật thông tin</span>';} ?>
                </td>
                <td>
                    <a href="<?php echo JRoute::_('index.php?option=com_users&view=membersinfo&id='.$item->id); ?>">Xem chi tiết</a>
                </td>
                <td>
                    <a id="navToMembers" href="<?php echo JRoute::_('index.php?option=com_users&view=members&id='.$item->id.'&clear=0'); ?>" >Đại lý tuyến dưới</a>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<script type="text/javascript">

jQuery(document).ready(function(){
  jQuery('#filter_gr_id_chzn input').prop('readonly', false);
});

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

jQuery('#membersLevel').change(function () {
	window.location = jQuery('option:selected', this).attr('dir');
});


</script>
<style>
.price{ font-weight:bold;}
@media only screen and (max-width: 760px), (min-device-width: 768px) and (max-device-width: 1024px)  {
        /* Force table to not be like tables anymore */
        table, thead, tbody, th, td, tr {
            display: block;
        }
        /* Hide table headers (but not display: none;, for accessibility) */
        thead tr {
            position: absolute;
            top: -9999px;
            left: -9999px;
        }
        tr { border: 1px solid #eee; margin-bottom: 10px}
        td {
            /* Behave  like a "row" */
            border: none;
            border-bottom: 1px solid #eee;
            position: relative;
            padding-left: 50%;
            border-top: 0 !important;
        }
        td:last-of-type{
            border: none;
        }
        td:before {
            width: 45%;
            padding-right: 10px;
            white-space: nowrap;
            color: #0b0b0b;
            font-weight: 600;
        }
        /*
		Label the data
		*/
        td:nth-of-type(1):before { content: "Họ tên:"; }
        td:nth-of-type(2):before { content: "Mã TV:"; }
        td:nth-of-type(3):before { content: "Cấp ĐL:"; }
        td:nth-of-type(4):before { content: "Tỉnh T/P:"; }
        td:nth-of-type(5):before { content: "Doanh số cá nhân:"; }
        td:nth-of-type(6):before { content: "Ngày tạo:"; }
        td:nth-of-type(7):before { content: "Trạng thái:"; }
        /* td:nth-of-type(8):before { content: "Trạng thái:"; } */
        <?php if($this->status_id != 7){ ?>
            /*td:nth-of-type(9):before { content: "Ngày mua:"; }*/
            td:nth-of-type(9):before { content: "Ghi chú:"; }
        <?php } ?>
        <?php if($this->status_id == 7){ ?>
          td:nth-of-type(9):before { content: "Ngày hoàn thành:";}
          td:nth-of-type(10):before { content: "Doanh thu:";}
        <?php } ?>
        .note{
            display: inline-block;
        }
        .btn{
            width: auto;
        }
        .pagination{margin: 0px;}
    }
    .color.green{
      background-color:#e2fbe2!important;
    }
    .color.black{
      background-color:#e6e6e6!important;
    }
    .color.red{
      background-color:#f9e8e8!important;
    }
    .color.yellow{
      background-color:#fbfbe7!important;
    }
    .color.blue{
      background-color:#e3e3fb!important;
    }
</style>

