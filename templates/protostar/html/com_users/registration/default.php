<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidator');
$itemid = $_REQUEST['Itemid'];

?>


<div class="registration<?php echo $this->pageclass_sfx; ?>">
	<?php if ($this->params->get('show_page_heading')) : ?>
		<div class="page-header">
			<h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
		</div>
	<?php endif; ?>
	<div class="row">
		<div class = "col-lg-6 col-md-6">

		<form id="member-registration" action="<?php echo JRoute::_('index.php?option=com_users&task=registration.register'); ?>" method="post" class="form-validate form-horizontal well" enctype="multipart/form-data">
		<?php // Iterate through the form fieldsets and display each one. ?>

		<?php foreach ($this->form->getFieldsets() as $fieldset) : ?>
			<?php $fields = $this->form->getFieldset($fieldset->name); ?>

			<?php if (count($fields)) : ?>
				<fieldset>
					<?php // If the fieldset has a label set, display it as the legend. ?>
					<i class="fa fa-life-ring" aria-hidden="true" style="font-size:24px;color:#EE7D30"></i>
					<?php if ($itemid == PAGE_REGISTRATION_AGENT) { ?>
						<h3 class="tit-regis">Đăng kí tư vấn viên</h3>
					<?php } elseif($itemid == PAGE_REGISTRATION_CUSTOMER){ ?>
						<h3 class="tit-regis">Đăng kí khách hàng</h3>
					<?php }

					?>

					<h6 class="description-login-form">Chào mừng bạn quay trở lại!</h6>
					<?php echo $this->form->renderFieldset($fieldset->name); ?>

				</fieldset>
			<?php endif; ?>
		<?php endforeach; ?>
		<div id="confirm-rules">
			<span>
					<input id="yes-rule" type="checkbox" checked="checked" name="rule" class="inputbox" value="yes" />
					<?php echo "Tôi đồng ý với điều khoản của Biznet"; ?>
			</span>
		</div>
		<div class="control-group">
			<div class="controls">
				<button type="button" class="btn btn-primary validate" onclick="resgisterSubmit()">
					<?php echo JText::_('JREGISTER'); ?>
				</button>
				<input type="hidden" name="option" value="com_users" />
				<input type="hidden" name="task" value="registration.register" />
			</div>

		</div>
		<span>
				Bạn đã là thành viên?
					<b><a href="<?php echo JRoute::_('index.php?option=com_users&view=login'); ?>">
					<?php echo "Đăng nhập"; ?>
					</a></b>
				</span>
		<?php echo JHtml::_('form.token'); ?>
	</form>
		</div>
			<?php
			if ($itemid == PAGE_REGISTRATION_AGENT) {?>
				<div class = "col-xl-6" id="content-regis-right">
					<div id="content-bg">
						<div id="content-box">
						<h2>Lợi ích x4 cùng <?php echo SITE_NAME ?></h2>
						<ul>
							<li class="regis-item-1"><span>Được nhân viên <?php echo SITE_NAME ?> hỗ trợ giải pháp trong việc tìm kiếm nguồn khách hàng</span></li>
							<li class="regis-item-2"><span>Tiếp cận được với lượng khách hàng tiềm năng khổng lồ đăng ký qua <?php echo SITE_NAME ?></span></li>
							<li class="regis-item-3"><span>Giúp tăng doanh số và thu nhập như mong muốn</span></li>
							<li class="regis-item-4"><span>Sử dụng tài khoản để nghiên cứu các thông tin về các sản phẩm dịch vụ về tài chính</span></li>
						</ul>
						</div>
					</div>
				</div>
			<?php }
			elseif ($itemid == PAGE_REGISTRATION_CUSTOMER) {?>
			<div class = "col-lg-6 col-md-6" id="content-regis-right">
				<div id="content-bg">
					<div id="content-box">
					<h2>TIỆN ÍCH X5</h2>
					<ul>
						<li class="regis-item-1"><span>Được đọc tin tài chính, bảo hiểm miễn phí</span></li>
						<li class="regis-item-2"><span>Được tư vấn bảo hiểm, tài chính miễn phí</span></li>
						<li class="regis-item-3"><span>Hỏi đáp 24/24</span></li>
						<li class="regis-item-4"><span>Quà tặng tri ân thành viên</span></li>
					</ul>
					</div>
				</div>
			</div>
			<?php } ?>


	</div>

</div>

<!--Thai modal for SMS verification-->
<div class="modal" id="registerVerifyModal" role="dialog">
	<div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Mã xác thực</h4>
			</div>
			<div class="modal-body">
				<form name="verifyForm" id="verifyForm">
					<div class="form-group">
						<input type="text" id="verifyCode" name="verifyCode" value="">
						<div id="verifySuccessAlert" class="alert alert-success" role="alert" style="display:none">Xác thực thành công, chuyển đến trang hoàn thành...</div>
						<div id="verifyPhoneLabel2" class="alert alert-danger" role="alert" style="display:none">Mã xác thực không đúng.</div>
						<div id="verifyPhoneLabel3" class="alert alert-warning" role="alert">Mã xác thực đã được gửi qua số điện thoại.</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<div class="btn-group1">
					<button type="button" class="btn btn-default" id="cancel_bt" data-dismiss="modal">Huỷ</button>
					<button type="button" class="btn btn-primary" id="verifyBtn">Xác thực</button>
				</div>
			</div>
		</div>

	</div>
</div>

<?php
$rand_email = rand(10000000000000,99999999999999);
$rand_email = "0".$rand_email."@biznet.com.vn";
if( $itemid == PAGE_REGISTRATION_CUSTOMER){ ?>
	<style>
	.registration #jform_email1{
		display: none;
	}
	.registration #jform_email2{
		display: none;
	}
	</style>

	<script>

	// Required mail
	// jQuery( document ).ready(function() {
	// 	if (jQuery('#jform_email1').attr('required') == 'required') {
	// 		jQuery('#jform_email1').removeAttr('required');
	// 		jQuery('#jform_email1').removeAttr('aria-required');
	// 		jQuery('#jform_email1').removeClass('required');
	// 	}
	// 	if (jQuery('#jform_email2').attr('required') == 'required') {
	// 		jQuery('#jform_email2').removeAttr('required');
	// 		jQuery('#jform_email2').removeAttr('aria-required');
	// 		jQuery('#jform_email2').removeClass('required');
	// 	}
	// });

	jQuery( document ).ready(function() {
			jQuery('#jform_email1').val("<?php echo $rand_email; ?>");
			jQuery('#jform_email2').val("<?php echo $rand_email; ?>");
	});

	</script>

<?php }
if ($itemid == PAGE_REGISTRATION_AGENT) {?>
	<style>
	.registration #jform_favorite_service{
		display: none;
	}
	#content-bg #content-box {
    margin-top: 35%;
    padding-left: 0%;
	}
	</style>
	<script>
		jQuery(document).ready(function(){
			jQuery('#jform_favorite_service').val('15');
		});
	</script>
<?php } ?>

<script>
	jQuery(document).ready(function(){
		jQuery('#jform_username').off('blur');
		document.formvalidator.setHandler('phonevalidate', function(value) {
			regex=/^0[0-9]{9}$/;
			let case1 = regex.test(value);
			let data = 'phone=' + '' + value + '';
			let case2;
			jQuery.ajax({
				type: "POST",
				url: "<?php echo JUri::base(); ?>index.php?option=com_users&task=registration.checkPhoneInUser",
				data: data,
				format: "json",
				async: false,
				success: function (res) {
					res = JSON.parse(res);
					case2 = res.data;
				}
			});

			if(case2 === 'false' && case1) {
				return true;
			}
			return false;

		});

		jQuery('#jform_email1').off('blur');
		jQuery('#jform_email2').off('blur');
		document.formvalidator.setHandler('emailvalidate', function(value) {
			let email1 = jQuery('#jform_email1').val();
			let email2 = jQuery('#jform_email2').val();
			let data = 'email=' + value;
			let case2;
			jQuery.ajax({
				type: "POST",
				url: "<?php echo JUri::base(); ?>index.php?option=com_users&task=registration.checkEmailInUser",
				data: data,
				format: "json",
				async: false,
				success: function (res) {
					res = JSON.parse(res);
					case2 = res.data;
				}
			});

			let isEmail = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(String(value).toLowerCase());
			console.log(isEmail);

			if(email1 === email2 && case2 === 'false' && isEmail) {
				return true;
			}
			return false;

		});

		document.formvalidator.setHandler('passwordVer', function(value) {
			let pass1 = jQuery('#jform_password1').val(),
				pass2 = jQuery('#jform_password2').val();
			if(pass1 == pass2 && /^(?=.*[0-9])(?=.*[a-z]).{6,32}$/.test(pass1) && /^(?=.*[0-9])(?=.*[a-z]).{6,32}$/.test(pass2)){
				return true;
			}
			return false;
		});
	});

	resgisterSubmit = function () {
		let form = document.getElementById('member-registration'),
			task = 'registration.register';
		let valid = document.formvalidator.isValid(form);
		// alert(valid);
		if (valid) {
			jQuery('#system-message-container').css('display', 'none');
			let smsError = <?php echo ERROR_SMS ?>;
			console.log(smsError);
			if(parseInt(smsError) === 0) {
				Joomla.submitform(task, form);
			}else{
				jQuery('#registerVerifyModal').modal('show');
				let phone = jQuery('#jform_username').val();
				let data = 'phone=' + phone + '&socialType=phone';
				jQuery.ajax({
					type: "POST",
					url: "<?php echo JUri::base(); ?>index.php?option=com_users&task=registration.getVerifyCode",
					data: data,
					format: "json",
					success: function (res) {
						res = JSON.parse(res);
						jQuery('#verifyBtn').click(function(){
							doVerify(res.data['token'], phone);
						});
					}

				});
			}


		}
	}

	function doVerify(token, phone) {
		let code = jQuery('#verifyCode').val();
		let form = document.getElementById('member-registration'),
			task = 'registration.register';
		if (code !== null || code !== undefined) {
			let data = 'code=' + code + '&tokenCode=' + token + '&username=' + phone;
			jQuery.ajax({
				type: "POST",
				url: "<?php echo JUri::base(); ?>index.php?option=com_users&task=registration.verifyPhone",
				data: data,
				format: "json",
				success: function (res) {
					res = JSON.parse(res);
					if (res.data === 'true') {
						jQuery('#verifyPhoneLabel2').css('display', 'none');
						jQuery('#verifyPhoneLabel3').css('display', 'none');
						jQuery('#verifySuccessAlert').css('display', 'block');
						setTimeout(function () {
							jQuery('#verifyModel').modal('hide');

						}, 2000);
						Joomla.submitform(task, form);
						// location.reload();
					} else {
						jQuery('#verifyPhoneLabel2').css('display', 'block');
						jQuery('#verifyPhoneLabel3').css('display', 'none');
					}
				}
			});
		}
	}

</script>
