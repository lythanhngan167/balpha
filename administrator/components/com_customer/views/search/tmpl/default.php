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
<form action="<?php echo JRoute::_('index.php?option=com_customer&view=search'); ?>" method="post" name="adminForm" id="adminFormSearch">

	<?php if (!empty( $this->sidebar)) : ?>
		<div id="j-sidebar-container" class="span0">
		<?php echo $this->sidebar; ?>
		</div>
		<div id="j-main-container" class="span12">
	<?php else : ?>
		<div id="j-main-container">
	<?php endif; ?>
		<div id="filter-all">
		<input name="phone_biznet_id" value="<?php echo $_REQUEST['keyword']; ?>"  class="js-stools-search-string" placeholder="Số Điện thoại" type="text" id="phone-biznet-id" />
		<button id="search-agent" class="btn btn-warning" onclick="searchCustomer()" type="button">Tìm Khách hàng</button>
		</div>
		<div class="user-data">
			<?php //print_r($this->customerActive); ?>
			<br>
			<?php if($this->customerActive->id > 0){ ?>
				<div class="user-item">
					<div class="user-item-left">
						<b>ID</b>
					</div>
					<div class="user-item-right">
						<?php echo $this->customerActive->id;  ?>
					</div>
				</div>
			<div class="user-item">
				<div class="user-item-left">
					<b>Họ tên</b>
				</div>
				<div class="user-item-right">
					<?php echo $this->customerActive->name;  ?>
				</div>
			</div>
			<div class="user-item">
				<div class="user-item-left">
					<b>Số điện thoại</b>
				</div>
				<div class="user-item-right">
					<?php echo $this->customerActive->phone;  ?>
				</div>
			</div>


			<div class="user-item">
				<div class="user-item-left">
					<b>Email</b>
				</div>
				<div class="user-item-right">
					<?php echo $this->customerActive->email;  ?>
				</div>
			</div>

			<div class="user-item">
				<div class="user-item-left">
					<b>Địa chỉ</b>
				</div>
				<div class="user-item-right">
					<?php echo $this->customerActive->place;  ?>
				</div>
			</div>

			<div class="user-item">
				<div class="user-item-left">
					<b>Tỉnh/TP</b>
				</div>
				<div class="user-item-right">
					<?php
					echo $province_text = JText::_('COM_CUSTOMER_FORM_PROVINCE_' . strtoupper($this->customerActive->province));
					?>
				</div>
			</div>
			<?php
			if($this->customerActive->sale_id > 0){
			?>
			<div class="user-item">
				<div class="user-item-left">
					<b>Thuộc Đại lý (TVV)</b>
				</div>
				<div class="user-item-right">
					<span class="price"><?php
					if($this->customerActive->sale_id > 0){
						$userSale = JFactory::getUser($this->customerActive->sale_id);
						echo $userSale->name." - ".$userSale->id_biznet." ( ".$userSale->username." )";
					}
					?>
				</span>
				</div>
			</div>
			<?php
				}
			?>


			<?php
			if($this->customerActive->from_landingpage > 0){
			?>
			<div class="user-item">
				<div class="user-item-left">
					<b>Từ Landingpage Cá nhân</b>
				</div>
				<div class="user-item-right">
					<span class="price"><?php
					if($this->customerActive->from_landingpage > 0){
						$userLandingpage = JFactory::getUser($this->customerActive->from_landingpage);
						echo $userLandingpage->name." ( ".$userLandingpage->username." )";
					}
					?>
				</span>
				</div>
			</div>
		<?php
			}
		?>
		<?php
		}else{ ?>
			<?php if($_REQUEST['keyword'] != ''){ ?>
			Không tồn tại Khách hàng với thông tin: <span class="no-data"><?php echo $_REQUEST['keyword']; ?></span>
			<?php } ?>
		<?php
		}
		?>

		</div>
	</div>
</form>

<script type="text/javascript">

jQuery(document).ready(function(){

});

function searchCustomer(){
	var phone_biznet_id = jQuery('#phone-biznet-id').val();
	if(phone_biznet_id == ''){
		alert('Vui lòng nhập Số điện thoại!');
	}else{
		window.location = '<?php echo JURI::root()."administrator/index.php?option=com_customer&view=search&keyword="; ?>'+phone_biznet_id;
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
