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
<div class="reset<?php echo $this->pageclass_sfx; ?>">
	<?php if ($this->params->get('show_page_heading')) : ?>
		<div class="page-header">
			<h1>
				<?php echo $this->escape($this->params->get('page_heading')); ?>
			</h1>
		</div>
	<?php endif; ?>

	<div class="group-tabs" id="selectResetType">
		<!-- Nav tabs -->
		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation" class="active"><a href="#resetByPhone" aria-controls="resetByPhone" role="tab" data-toggle="tab">Xác minh qua số điện thoại</a></li>
			<li role="presentation"><a href="#resetByEmail" aria-controls="resetByEmail" role="tab" data-toggle="tab">Xác minh qua Email</a></li>
		</ul>
	</div>

	<div class="tab-content">
		<div role="tabpanel" class="tab-pane active" id="resetByPhone">
			<form id="user-registration" action="<?php echo JRoute::_('index.php?option=com_users&task=reset.requestCode'); ?>" method="post" class="form-validate form-horizontal well">
				<label for="">Vui lòng nhập số điện thoại phù hợp với tài khoản của bạn. Một mã xác minh sẽ được gửi đến bạn. Khi nhận được mã này, bạn  sẽ có thể chọn một mật khẩu mới cho tài khoản của mình.</label>
					<div class="control-group">
						<div class="control-label">
							<label for="phone" class="hasPopover required">
								Số điện thoại:<span class="star">&nbsp;*</span></label>
						</div>
						<div class="controls">
							<input type="text" name="phoneNumber" id="phoneNumber"/>
						</div>
					</div>
					<div class="control-group">
						<div class="controls">
							<button type="submit" class="btn btn-primary validate">
								<?php echo JText::_('JSUBMIT'); ?>
							</button>
						</div>
					</div>
					<?php echo JHtml::_('form.token'); ?>
			</form>
		</div>
		<div role="tabpanel" class="tab-pane" id="resetByEmail">
			<form id="user-registration" action="<?php echo JRoute::_('index.php?option=com_users&task=reset.request'); ?>" method="post" class="form-validate form-horizontal well">
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
		</div>
	</div>
	
	
</div>

<script>
	
	
</script>
