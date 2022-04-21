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
?>
<br>
<form action="<?php echo JRoute::_('index.php?option=com_users&view=search'); ?>" method="post" name="adminForm" id="adminFormSearch">

	<?php if (!empty( $this->sidebar)) : ?>
		<div id="j-sidebar-container" class="span0">
		<?php echo $this->sidebar; ?>
		</div>
		<div id="j-main-container" class="span12">
	<?php else : ?>
		<div id="j-main-container">
	<?php endif; ?>
		<div id="filter-all">
		<input name="phone_biznet_id" value="<?php echo $_REQUEST['keyword']; ?>"  class="js-stools-search-string" placeholder="Số Điện thoại/ID Biznet" type="text" id="phone-biznet-id" />
		<button id="search-agent" class="btn btn-warning" onclick="searchAgent()" type="button">Tìm Đại lý</button>
		</div>
		<div class="user-data">
			<?php //print_r($this->userActive); ?>
			<br>
			<?php if($this->userActive->id > 0){ ?>
				<?php if($this->groupBDM == 15 && $this->isBDM == 0){ ?>
						Đại lý (TVV) này không thuộc BDM với thông tin: <span class="no-data"><?php echo $_REQUEST['keyword']; ?></span>
				<?php }else{ ?>
				<div class="user-item">
					<div class="user-item-left">
						<b>ID</b>
					</div>
					<div class="user-item-right">
						<?php echo $this->userActive->id;  ?>
					</div>
				</div>
			<div class="user-item">
				<div class="user-item-left">
					<b>Họ tên</b>
				</div>
				<div class="user-item-right">
					<?php echo $this->userActive->name;  ?>
				</div>
			</div>
			<div class="user-item">
				<div class="user-item-left">
					<b>Số điện thoại</b>
				</div>
				<div class="user-item-right">
					<?php echo $this->userActive->username;  ?>
				</div>
			</div>
			<div class="user-item">
				<div class="user-item-left">
					<b>ID Biznet</b>
				</div>
				<div class="user-item-right">
					<?php echo $this->userActive->id_biznet;  ?>
				</div>
			</div>

			<div class="user-item">
				<div class="user-item-left">
					<b>Email</b>
				</div>
				<div class="user-item-right">
					<?php echo $this->userActive->email;  ?>
				</div>
			</div>

			<div class="user-item">
				<div class="user-item-left">
					<b>Số dư BizXu</b>
				</div>
				<div class="user-item-right">
					<span class="price"><?php echo number_format($this->userActive->money,0,'.','.');  ?> BizXu</span>
				</div>
			</div>

			<div class="user-item">
				<div class="user-item-left">
					<b>Level DataCenter</b>
				</div>
				<div class="user-item-right">
					<?php echo $this->userActive->level;  ?>
					&nbsp; &nbsp; &nbsp;<button id="close-page" class="btn btn-warning" onclick="changeLeve(<?php echo $this->userActive->id;  ?>)" type="button">Nâng Level</button>&nbsp; &nbsp; ( Từ Level 1,2,3 lên 4,5 )
				</div>
			</div>

			<div class="user-item">
				<div class="user-item-left">
					<b>Level Đại lý BH</b>
				</div>
				<div class="user-item-right">
					<?php echo $this->userActive->level_tree;  ?>
				</div>
			</div>

			<div class="user-item">
				<div class="user-item-left">
					<b>Người giới thiệu</b>
				</div>
				<div class="user-item-right">
					<?php
					if($this->userActive->invited_id > 0){
						$userInvitation = JFactory::getUser($this->userActive->invited_id);
						echo $userInvitation->name." ( ".$userInvitation->username." )";
					}
					?>
				</div>
			</div>
			<div class="user-item">
				<div class="user-item-left">
					<b>Tổng số DATA đã mua</b>
				</div>
				<div class="user-item-right">
					<?php echo $this->userActive->buyall;  ?>
				</div>
			</div>

			<div class="user-item">
				<div class="user-item-left">
					<b>Tình trạng Landingpage</b>
				</div>
				<div class="user-item-right">
					<?php
					if($this->userActive->block_landingpage == 1){
						echo '<span class="landingpage-blocked">Tạm khóa</span>';
					}else{
						echo '<span class="landingpage-active">Đang mở</span>';
					}
					?> &nbsp; &nbsp; &nbsp;
					<?php
					if($this->userActive->block_landingpage == 1){ ?>
					<button id="open-page" class="btn btn-success" onclick="openLandingpage(<?php echo $this->userActive->id;  ?>)" type="button">Mở Landingpage</button>
					<?php
				}else{
					?>
					<button id="close-page" class="btn btn-warning" onclick="closeLandingpage(<?php echo $this->userActive->id;  ?>)" type="button">Đóng Landingpage</button>
					<?php
					}
					?>
					<br>
					<br>
					Link Landingpage: <a target="_blank" href="<?php echo $config->landingpage_link."/agent/".$this->userActive->id.".html" ; ?>"><?php echo $config->landingpage_link."/agent/".$this->userActive->id.".html" ; ?></a>
				</div>
			</div>
		<?php } ?>

		<?php
		}else{ ?>
			<?php if($_REQUEST['keyword'] != ''){ ?>
				Không tồn tại Đại lý (TVV) với thông tin: <span class="no-data"><?php echo $_REQUEST['keyword']; ?></span>
			<?php } ?>
		<?php
		}
		?>

		</div>
	</div>
</form>


<div class="modal fade" style="width:400px; display:none;" id="myModalTagLevel" role="dialog">
  <div class="modal-dialog">
    <form name="reasonFrom" id="reasonFormLevel">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Nâng Level</h4>
      </div>
      <div class="modal-body">
				<select id="level_id" name="level_id">
				<option value="">--Chọn Level--</option>
				<!-- <option value="1">Level 1</option>
				<option value="2">Level 2</option>
				<option value="3">Level 3</option> -->
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

function changeLeve(userid){
		 jQuery('#userid').val(userid);
	   jQuery('#myModalTagLevel').modal('toggle');
}

function updateLevel(){
	var userid = jQuery('#userid').val();
	var level = jQuery('#level_id').val();
	if(level == ''){
		alert("Vui lòng chọn Level!");
	}else{
		if(userid != '' && level != ''){
			jQuery.ajax({
					url: "<?php echo JUri::base(); ?>index.php?option=com_users&task=search.changeLevel",
					type : "POST",
	        dataType:"text",
					data : {
							 userid : userid,
							 level : level
					},
					success: function (result) {
							if (result == '1') {
									alert("Thay đổi Level cho Đại lý (TVV) thành công!");
									location.reload();
									return;
							}
							if (result == '0') {
									alert("Thay đổi Level cho Đại lý (TVV) không thành công, vui lòng thử lại!");
									location.reload();
									return;
							}

					}
			});
		}else{
			alert("Có lỗi. Vui lòng thử lại!");
		}
	}
}

function searchAgent(){
	var phone_biznet_id = jQuery('#phone-biznet-id').val();
	if(phone_biznet_id == ''){
		alert('Vui lòng nhập Số điện thoại hoặc ID Biznet!');
	}else{
		window.location = '<?php echo JURI::root()."administrator/index.php?option=com_users&view=search&keyword="; ?>'+phone_biznet_id;
	}
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
	searchAgent();
});

function openLandingpage(user_id){
	if(user_id > 0){
		var r = confirm("Bạn có chắc muốn Mở Landingpage cho Đại lý này?");
		if (r == true) {
			jQuery.ajax({
					url: "<?php echo JUri::base(); ?>index.php?option=com_users&task=search.openLandingpage",
					type : "POST",
	        dataType:"text",
					data : {
							 user_id : user_id,
					},
					success: function (result) {
							if (result == '1') {
									alert("Mở Landingpage cho Đại lý thành công!");
									location.reload();
									return;
							}
							if (result == '-1') {
									alert("Vui lòng đăng nhập!");
									location.reload();
									return;
							}
							if (result == '-2') {
									alert("Đại lý Không tồn tại, vui lòng thử lại!");
									location.reload();
									return;
							}
							if (result == '0') {
									alert("Mở Landingpage cho Đại lý không thành công, vui lòng thử lại!");
									location.reload();
									return;
							}

					}
			});
		}
	}else{
		alert('Đại lý không tồn tại. Vui lòng thử lại!');
	}

}

function closeLandingpage(user_id){
	if(user_id > 0){
		var r = confirm("Bạn có chắc muốn Đóng Landingpage của Đại lý này?");
		if (r == true) {
			jQuery.ajax({
					url: "<?php echo JUri::base(); ?>index.php?option=com_users&task=search.closeLandingpage",
					type : "POST",
	        dataType:"text",
					data : {
							 user_id : user_id,
					},
					success: function (result) {
							if (result == '1') {
									alert("Đóng Landingpage của Đại lý thành công!");
									location.reload();
									return;
							}
							if (result == '-1') {
									alert("Vui lòng đăng nhập!");
									location.reload();
									return;
							}
							if (result == '-2') {
									alert("Đại lý Không tồn tại, vui lòng thử lại!");
									location.reload();
									return;
							}
							if (result == '0') {
									alert("Đóng Landingpage của Đại lý không thành công, vui lòng thử lại!");
									location.reload();
									return;
							}

					}
			});
		}
	}else{
		alert('Đại lý không tồn tại. Vui lòng thử lại!');
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
	width:200px;
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
</style>
