<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

?>
<div class="profile<?php echo $this->pageclass_sfx; ?>">
	<?php if ($this->params->get('show_page_heading')) : ?>
		<div class="page-header">
			<h1>
				<?php echo $this->escape($this->params->get('page_heading')); ?>
			</h1>
		</div>
	<?php endif; ?>
	<?php if (JFactory::getUser()->id == $this->data->id) : ?>
		<!-- <ul class="btn-toolbar pull-right">
			<li class="btn-group">
				<a class="btn" href="<?php echo JRoute::_('index.php?option=com_users&task=profile.edit&user_id=' . (int) $this->data->id); ?>">
					<span class="icon-user"></span>
					<?php echo JText::_('COM_USERS_EDIT_PROFILE'); ?>
				</a>
			</li>
		</ul> -->
	<?php endif; ?>
	<?php echo $this->loadTemplate('core'); ?>
	<?php //echo $this->loadTemplate('params'); ?>
	<?php //echo $this->loadTemplate('point'); ?>
	<?php echo $this->loadTemplate('custom'); ?>
</div>
<div class="modal" id="verifyModel" role="dialog">
	<div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
				<h4 class="modal-title">Cập nhật số điện thoại</h4>
			</div>
			<div class="modal-body">
				<form name="verifyForm" id="verifyForm">
					<div class="form-group">
						<input type="text" id="verifyPhone" name="verifyPhone" value="" placeholder="Nhập số điện thoại"
							style="margin:0px">
						<label id="verifyPhoneLabel" style="color:red;display:none">Vui lòng nhập số điện thoại</label>
						<label id="verifyPhoneLabel1" style="color:red;display:none">Số điện thoại không hợp lệ</label>
						<label id="verifyPhoneLabel4" style="color:red;display:none">Số điện thoại đã tồn tại</label>
					</div>
					<div class="form-group">
						<button type="button" class="btn btn-primary" id="getCodeBtn">Gửi mã xác thực
						</button>
						<span id="verifyTime" style="margin:0; padding:0"><small>(Chỉ được gửi 2 lần trong 1 ngày)</small></span>
					</div>
					<div class="form-group">
						<input type="text" id="verifyCode" name="verifyCode" value="" style="display:none" style="margin:0px" placeholder="Mã xác thực">
						<div id="verifySuccessAlert" class="alert alert-success" role="alert" style="display:none">Cập nhật thành công.</div>
						<label id="verifyPhoneLabel2" style="color:red;display:none">Mã xác thực không chính xác</label>
						<div id="verifyPhoneLabel3" class="alert alert-warning" role="alert" style="display:none">Mã xác thực đã được gửi qua số điện thoại.</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<div class="btn-group1">
					<button type="button" class="btn btn-default" id="cancel_bt" data-dismiss="modal">Huỷ</button>
					<button type="button" class="btn btn-primary" onclick="doVerify()" id="verifyBtn" disabled>Xác thực</button>
				</div>
			</div>
		</div>

	</div>
</div>

<script>

var token = '';

jQuery('#verifyModel').on('hide', function() {
	logoutClose();

})

jQuery('#cancel_bt').click('hidden', function () {
	logoutClose();
});

jQuery('#getCodeBtn').click(function () {

	var phone = jQuery('#verifyPhone').val();
	var data = 'phone=' + phone;

	jQuery.ajax({
		type: "POST",
		url: "<?php echo JUri::base(); ?>index.php?option=com_users&task=profile.checkPhones",
		data: data,
		format: "json",
		success: function (res) {
			res = JSON.parse(res);
			jQuery('#verifyPhoneLabel1').css('display', 'none');
			jQuery('#verifyPhoneLabel').css('display', 'none');
			if (res.data['isPhone'] === 'true' && res.data['isPhoneInUse'] === 'false') {
				isDisabled(false);
				getVerifyCode(phone);
				showHideError('all');
			} else {
				if (!phone) {
					showHideError('label');
				} else {
					if(res.data['isPhoneInUse'] === 'true') {
						showHideError('label4');
					}else{
						showHideError('label1');
						isDisabled(true);
					}
				}
				isDisabled(true);
			}
		}
	});

});

function doVerify() {
	jQuery('#verifyPhone').prop('disabled', true);
	let phone = jQuery('#verifyPhone').val();
	let code = jQuery('#verifyCode').val();
	let data = 'code=' + code + '&tokenCode=' + token + '&username=' + phone;
	jQuery.ajax({
		type: "POST",
		url: "<?php echo JUri::base(); ?>index.php?option=com_users&task=profile.verifyPhone",
		data: data,
		format: "json",
		success: function(res) {
			res= JSON.parse(res);
			if(res.data === 'true') {
				jQuery('#verifyPhoneLabel2').css('display', 'none');
				jQuery('#verifyPhoneLabel3').css('display', 'none');
				jQuery('#verifySuccessAlert').css('display', 'block');
				setTimeout(function(){
					jQuery('#verifyModel').modal('hide');
				}, 5000);

				// location.reload();
			} else {
				jQuery('#verifyPhoneLabel2').css('display', 'block');
				jQuery('#verifyPhoneLabel3').css('display', 'none');
			}
		}
	});
}

isDisabled(true);

jQuery(document).ready(function () {
	<?php
	$user = JFactory::getUser();
	$groups = JAccess::getGroupsByUser($user->id, false);
	$group_id = $groups[0];
	?>
	<?php if( $group_id == 2 || $group_id == 3){ ?>
	var isPhone = <?php echo $this->isPhone ?>;
	let isErrorSMS = <?php echo ERROR_SMS?>;
	if (!isPhone && parseInt(isErrorSMS) === 1) {
		jQuery('#verifyModel').modal('show');
		jQuery('#verifyBtn').prop('disabled', true);
	}
	<?php } ?>

});

function isDisabled(flag) {
	jQuery('#verifyBtn').prop('disabled', !flag);
}

function getVerifyCode(phone) {

		var data = 'phone=' + phone + "&socialType=phone";

		jQuery.ajax({
			type: "POST",
			url: "<?php echo JUri::base(); ?>index.php?option=com_users&task=profile.getVerifyCode",
			data: data,
			format: "json",
			async: false,
			success: function (res) {
				res = JSON.parse(res);
				token = res.data['token'];
				jQuery('#verifyTime small').text('(Chỉ được gửi 2 lần trong 1 ngày, ' +res.data['time']+ ' hôm nay)');
				if (res.data['time'] <= 2) {
					jQuery('#verifyCode').css('display', 'block');
					isDisabled(true);
					jQuery('#getCodeBtn').prop('disabled', true);
					jQuery('#verifyPhoneLabel3').css('display', 'block');
				} else {
					jQuery('#verifyTime small').text('(Chỉ được gửi 2 lần trong 1 ngày, 2 hôm nay)');
				}
			}
		});
}

function logoutClose() {
	jQuery.ajax({
			type: "POST",
			url: "<?php echo JUri::base(); ?>index.php?option=com_users&task=user.logout&<?php echo JSession::getFormToken();?>=1&return=<?php echo base64_encode(JUri::base());?>",
			success: function (result) {
				window.location.href = "<?php echo JUri::base(); ?>";
			}
		});
}

function showHideError(type) {
	switch(type){
		case 'all':
			jQuery('#verifyPhoneLabel').css('display', 'none');
			jQuery('#verifyPhoneLabel1').css('display', 'none');
			jQuery('#verifyPhoneLabel4').css('display', 'none');
			break;
		case 'label':
		console.log('label');
			jQuery('#verifyPhoneLabel').css('display', 'block');
			jQuery('#verifyPhoneLabel1').css('display', 'none');
			jQuery('#verifyPhoneLabel4').css('display', 'none');
			break;
		case 'label1':
			jQuery('#verifyPhoneLabel').css('display', 'none');
			jQuery('#verifyPhoneLabel1').css('display', 'block');
			jQuery('#verifyPhoneLabel4').css('display', 'none');
			break;
		case 'label4':
			jQuery('#verifyPhoneLabel').css('display', 'none');
			jQuery('#verifyPhoneLabel1').css('display', 'none');
			jQuery('#verifyPhoneLabel4').css('display', 'block');
			break;
	}
}
</script>
