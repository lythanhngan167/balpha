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

?>
<div class="login<?php echo $this->pageclass_sfx; ?>">
	<?php if ($this->params->get('show_page_heading')) : ?>
		<div class="page-header">
			<h1>
				<?php echo $this->escape($this->params->get('page_heading')); ?>
			</h1>
		</div>
	<?php endif; ?>
	<?php if (($this->params->get('logindescription_show') == 1 && str_replace(' ', '', $this->params->get('login_description')) != '') || $this->params->get('login_image') != '') : ?>
		<div class="login-description">
	<?php endif; ?>
	<?php if ($this->params->get('logindescription_show') == 1) : ?>
		<?php echo $this->params->get('login_description'); ?>
	<?php endif; ?>
	<?php if ($this->params->get('login_image') != '') : ?>
		<img src="<?php echo $this->escape($this->params->get('login_image')); ?>" class="login-image" alt="<?php echo JText::_('COM_USERS_LOGIN_IMAGE_ALT'); ?>" />
	<?php endif; ?>
	<?php if (($this->params->get('logindescription_show') == 1 && str_replace(' ', '', $this->params->get('login_description')) != '') || $this->params->get('login_image') != '') : ?>
		</div>
	<?php endif; ?>
	<div class="row login-mobile" id="content-login">
	<div class="col-xs-12 col-lg-6 col-md-6" id="content-left">
	<form action="<?php echo JRoute::_('index.php?option=com_users&task=user.login'); ?>" method="post" class="form-validate form-horizontal well">
	<div class="login-header">
		<i class="fa fa-life-ring" aria-hidden="true" style="font-size:24px;color:#EE7D30"></i>
		<h3 class="tit-login">Đăng nhập</h3>
		<h6 class="description-login-form">Chào mừng bạn quay trở lại!</h6>
	</div>
	<div class="deny-users">
	<?php if($_REQUEST['deny'] == 1){ ?>
		<p>Bạn không có quyền truy cập, nếu cần vào Datacenter vui lòng bấm vào đây <a href="https://biznet.com.vn/dang-nhap.html">Datacenter</a>.</p>
	<?php } ?>
	</div>
		<fieldset>
			<?php echo $this->form->renderFieldset('credentials'); ?>
			<?php if ($this->tfa) : ?>
				<?php echo $this->form->renderField('secretkey'); ?>
			<?php endif; ?>
			<?php if (JPluginHelper::isEnabled('system', 'remember')) : ?>
				<div class="remember-login">

					<div class="left-remember-login">
						<input id="remember" type="checkbox" name="remember" class="inputbox" value="yes" />
						<label for="remember" id="remember-log">
							<?php echo "Ghi nhớ đăng nhập"; ?>
						</label>
					</div>
					<div class="right-remember-login">
						<a id="forgot-pass" href="<?php echo JRoute::_('index.php?option=com_users&view=reset'); ?>">
							<?php echo JText::_('COM_USERS_LOGIN_RESET'); ?>
						</a>
					</div>
			</div>
			<?php endif; ?>
			<div class="control-group">
				<div class="controls">
					<button type="submit" class="btn btn-primary">
						<?php echo JText::_('JLOGIN'); ?>
					</button>
				</div>
			</div>
			<!-- Button trigger modal -->


				<!-- Modal -->
				<div id="exampleModalCenter" class="modal">
				<div class="modal-dialog">

				<div class="modal-content">
				<div class="modal-header"><button class="close" type="button" data-dismiss="modal">×</button>
				<h4 class="modal-title">Thông báo</h4>
				</div>
				<div class="modal-body">Tính năng đang được cập nhật!</div>
				<div class="modal-footer">
				<div class="btn-group1"><button id="save_bt" class="btn btn-primary" type="button" data-dismiss="modal">Đóng</button></div>
				</div>
				</div>

			</div>
				</div>
				<!-- <div class="btn-mxh">
				<span>Hoặc đăng nhập bằng</span><br>
				<button data-toggle="modal" data-target="#exampleModalCenter" type="button" class ="login-fb"><i class="fa fa-facebook" style="font-size:18px"></i> Login with Facebook</button>
				<button data-toggle="modal" data-target="#exampleModalCenter" type="button" class ="login-google"><i class="fa fa-google-plus" style="font-size:18px"></i> Signin with Google+</button>
				</div> -->

				<!-- <div class="has-acount">
				<span class="no-account-question">Bạn chưa có tài khoản?</span>
				<br>
					<b><a href="<?php echo JRoute::_('index.php?Itemid=275'); ?>">
					<?php echo"&nbsp;Đăng kí tài khoản Khách hàng"; ?>
				</a></b><br>
					<b><a href="<?php echo JRoute::_('index.php?Itemid=300'); ?>">
					<?php echo "Đăng kí tài khoản Tư vấn viên"; ?>
					</a></b>

				</div> -->

			</div>
			<?php $return = $this->form->getValue('return', '', $this->params->get('login_redirect_url', $this->params->get('login_redirect_menuitem'))); ?>
			<input type="hidden" name="return" value="<?php echo base64_encode($return); ?>" />
			<?php echo JHtml::_('form.token'); ?>
				</fieldset>
			</form>

			<div class="col-xs-12 col-lg-6 col-md-6" id="content-right">
				<div class="bg-login">
				<img src="images/bg-login-bca.png">
				</div>
			</div>
			</div>
	</div>

</div>
<!-- <div>
	<ul class="nav nav-tabs nav-stacked">
		<li>
			<a href="<?php echo JRoute::_('index.php?option=com_users&view=reset'); ?>">
				<?php echo JText::_('COM_USERS_LOGIN_RESET'); ?>
			</a>
		</li>
		<li>
			<a href="<?php echo JRoute::_('index.php?option=com_users&view=remind'); ?>">
				<?php echo JText::_('COM_USERS_LOGIN_REMIND'); ?>
			</a>
		</li>
		<?php $usersConfig = JComponentHelper::getParams('com_users'); ?>
		<?php if ($usersConfig->get('allowUserRegistration')) : ?>
			<li>
				<a href="<?php echo JRoute::_('index.php?option=com_users&view=registration'); ?>">
					<?php echo JText::_('COM_USERS_LOGIN_REGISTER'); ?>
				</a>
			</li>
		<?php endif; ?>
	</ul>
</div> -->
<style>
.deny-users{color:orange;}
</style>
