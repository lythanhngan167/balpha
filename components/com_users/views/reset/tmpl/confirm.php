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
<div class="reset-confirm<?php echo $this->pageclass_sfx; ?>">
	<?php if ($this->params->get('show_page_heading')) : ?>
		<div class="page-header">
			<h1>
				<?php echo $this->escape($this->params->get('page_heading')); ?>
			</h1>
		</div>
	<?php endif; ?>
	<?php if(!$this->type) {?>
		<form action="<?php echo JRoute::_('index.php?option=com_users&task=reset.confirm'); ?>" method="post" class="form-validate form-horizontal well">
			<?php foreach ($this->form->getFieldsets() as $fieldset) : ?>
				<fieldset>
					<?php if (isset($fieldset->label)) : ?>
						<p><?php echo JText::_($fieldset->label); ?></p>
					<?php endif; ?>
					<?php echo $this->form->renderFieldset($fieldset->name); ?>
				</fieldset>
			<?php endforeach; ?>
			<div class="control-group">
				<div class="controls">
					<button type="submit" class="btn btn-primary validate">
						<?php echo JText::_('JSUBMIT'); ?>
					</button>
				</div>
			</div>
			<?php echo JHtml::_('form.token'); ?>
		</form>
	<?php }?>
	<?php if($this->type == 'phone') {?>
		<form id="user-registration" action="<?php echo JRoute::_('index.php?option=com_users&task=reset.comfirmCode'); ?>" method="post" class="form-validate form-horizontal well">
				<label for="">Một tin nhắn đã được gửi đến số điện thoại của bạn. Tin nhắn này có chứa mã xác thực, vui lòng dán (paste) mã xác minh này vào ô dưới đây để chứng minh rằng bạn là chủ nhân của tài khoản này.</label>
					<div class="control-group">
						<div class="control-label">
							<label for="verifyCodeForReset" class="hasPopover required">
								Mã xác thực:<span class="star">&nbsp;*</span></label>
						</div>
						<div class="controls">
							<input type="text" name="verifyCodeForReset" id="verifyCodeForReset"/>
						</div>
					</div>
					<div class="control-group">
						<div class="control-label">
							<label for="verifyCodeForReset" class="hasPopover required">
								Mật khẩu:<span class="star">&nbsp;*</span></label>
						</div>
						<div class="controls">
							<input type="password" name="newPassword1" id="newPassword1"/>
						</div>
					</div>
					<div class="control-group">
						<div class="control-label">
							<label for="verifyCodeForReset" class="hasPopover required">
								Xác nhận mật khẩu:<span class="star">&nbsp;*</span></label>
						</div>
						<div class="controls">
							<input type="password" name="newPassword2" id="newPassword2"/>
						</div>
					</div>
					<div class="control-group">
						<div class="controls">
							<button type="submit" class="btn btn-primary validate">
								<?php echo JText::_('JSUBMIT'); ?>
							</button>
							<input type="hidden" name="tokens" value="<?php echo $this->tokens?>">
							<input type="hidden" name="phoneNumber" value="<?php echo $this->phoneNumber?>">
						</div>
					</div>
					<?php echo JHtml::_('form.token'); ?>
			</form>
	<?php }?>
</div>
