<?php
/**
 * @version		3.1.0
 * @package		Joomla
 * @subpackage	EShop
 * @author  	Giang Dinh Truong
 * @copyright	Copyright (C) 2012 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined( '_JEXEC' ) or die();
?>
<div class="control-group" style="margin-left: 15px;">
	<div class="control-label">
		<?php echo EshopHtmlHelper::getFieldLabel('send_from', JText::_('ESHOP_CONFIG_SEND_FROM'), JText::_('ESHOP_CONFIG_SEND_FROM_HELP')); ?>
	</div>
	<div class="controls">
		<?php echo $this->lists['send_from']; ?>
	</div>
</div>
<div class="span12">
	<fieldset class="form-horizontal">
		<legend><?php echo JText::_('ESHOP_CONFIG_ORDER_NOTIFICATION'); ?></legend>
		<div class="control-group">
			<div class="control-label">
				<?php echo EshopHtmlHelper::getFieldLabel('order_alert_mail', JText::_('ESHOP_CONFIG_ORDER_ALERT_MAIL_ENABLE'), JText::_('ESHOP_CONFIG_ORDER_ALERT_MAIL_ENABLE_HELP')); ?>
			</div>
			<div class="controls">
				<?php echo $this->lists['order_alert_mail']; ?>
			</div>
		</div>
		<div class="control-group">
			<div class="control-label">
				<?php echo EshopHtmlHelper::getFieldLabel('order_alert_mail_admin', JText::_('ESHOP_CONFIG_ORDER_ALERT_MAIL_ADMIN'), JText::_('ESHOP_CONFIG_ORDER_ALERT_MAIL_ADMIN_HELP')); ?>
			</div>
			<div class="controls">
				<?php echo $this->lists['order_alert_mail_admin']; ?>
			</div>
		</div>
		<div class="control-group">
			<div class="control-label">
				<?php echo EshopHtmlHelper::getFieldLabel('order_alert_mail_manufacturer', JText::_('ESHOP_CONFIG_ORDER_ALERT_MAIL_MANUFACTURER'), JText::_('ESHOP_CONFIG_ORDER_ALERT_MAIL_MANUFACTURER_HELP')); ?>
			</div>
			<div class="controls">
				<?php echo $this->lists['order_alert_mail_manufacturer']; ?>
			</div>
		</div>
		<div class="control-group">
			<div class="control-label">
				<?php echo EshopHtmlHelper::getFieldLabel('order_alert_mail_customer', JText::_('ESHOP_CONFIG_ORDER_ALERT_MAIL_CUSTOMER'), JText::_('ESHOP_CONFIG_ORDER_ALERT_MAIL_CUSTOMER_HELP')); ?>
			</div>
			<div class="controls">
				<?php echo $this->lists['order_alert_mail_customer']; ?>
			</div>
		</div>
		<div class="control-group">
			<div class="control-label">
				<?php echo EshopHtmlHelper::getFieldLabel('alert_emails', JText::_('ESHOP_CONFIG_ALERT_MAILS'), JText::_('ESHOP_CONFIG_ALERT_MAILS_HELP')); ?>
			</div>
			<div class="controls">
				<input class="text_area" type="text" name="alert_emails" id="alert_emails" size="100" maxlength="250" value="<?php echo isset($this->config->alert_emails) ? $this->config->alert_emails : ''; ?>" />
			</div>
		</div>
	</fieldset>
</div>
<div class="span12">
	<fieldset class="form-horizontal">
		<legend><?php echo JText::_('ESHOP_CONFIG_QUOTE_NOTIFICATION'); ?></legend>
		<div class="control-group">
			<div class="control-label">
				<?php echo EshopHtmlHelper::getFieldLabel('quote_alert_mail', JText::_('ESHOP_CONFIG_QUOTE_ALERT_MAIL_ENABLE'), JText::_('ESHOP_CONFIG_QUOTE_ALERT_MAIL_ENABLE_HELP')); ?>
			</div>
			<div class="controls">
				<?php echo $this->lists['quote_alert_mail']; ?>
			</div>
		</div>
		<div class="control-group">
			<div class="control-label">
				<?php echo EshopHtmlHelper::getFieldLabel('quote_alert_mail_admin', JText::_('ESHOP_CONFIG_QUOTE_ALERT_MAIL_ADMIN'), JText::_('ESHOP_CONFIG_QUOTE_ALERT_MAIL_ADMIN_HELP')); ?>
			</div>
			<div class="controls">
				<?php echo $this->lists['quote_alert_mail_admin']; ?>
			</div>
		</div>
		<div class="control-group">
			<div class="control-label">
				<?php echo EshopHtmlHelper::getFieldLabel('quote_alert_mail_customer', JText::_('ESHOP_CONFIG_QUOTE_ALERT_MAIL_CUSTOMER'), JText::_('ESHOP_CONFIG_QUOTE_ALERT_MAIL_CUSTOMER_HELP')); ?>
			</div>
			<div class="controls">
				<?php echo $this->lists['quote_alert_mail_customer']; ?>
			</div>
		</div>
		<div class="control-group">
			<div class="control-label">
				<?php echo EshopHtmlHelper::getFieldLabel('quote_alert_emails', JText::_('ESHOP_CONFIG_QUOTE_ALERT_MAILS'), JText::_('ESHOP_CONFIG_QUOTE_ALERT_MAILS_HELP')); ?>
			</div>
			<div class="controls">
				<input class="text_area" type="text" name="quote_alert_emails" id="quote_alert_emails" size="100" maxlength="250" value="<?php echo isset($this->config->quote_alert_emails) ? $this->config->quote_alert_emails : ''; ?>" />
			</div>
		</div>
	</fieldset>
</div>
<div class="span12">
	<fieldset class="form-horizontal">
		<legend><?php echo JText::_('ESHOP_CONFIG_PRODUCT_NOTIFICATION'); ?></legend>
		<div class="control-group">
			<div class="control-label">
				<?php echo EshopHtmlHelper::getFieldLabel('product_alert_ask_question', JText::_('ESHOP_CONFIG_PRODUCT_ALERT_ASK_QUESTION'), JText::_('ESHOP_CONFIG_PRODUCT_ALERT_ASK_QUESTION_HELP')); ?>
			</div>
			<div class="controls">
				<?php echo $this->lists['product_alert_ask_question']; ?>
			</div>
		</div>
		<div class="control-group">
			<div class="control-label">
				<?php echo EshopHtmlHelper::getFieldLabel('product_alert_review', JText::_('ESHOP_CONFIG_PRODUCT_ALERT_REVIEW'), JText::_('ESHOP_CONFIG_PRODUCT_ALERT_REVIEW_HELP')); ?>
			</div>
			<div class="controls">
				<?php echo $this->lists['product_alert_review']; ?>
			</div>
		</div>
	</fieldset>
</div>