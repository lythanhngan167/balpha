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
<div class="control-group">
	<div class="control-label">
		<?php echo EshopHtmlHelper::getFieldLabel('invoice_enable', JText::_('ESHOP_CONFIG_INVOICE_ENABLE'), JText::_('ESHOP_CONFIG_INVOICE_ENABLE_HELP')); ?>
	</div>
	<div class="controls">
		<?php echo $this->lists['invoice_enable']; ?>
	</div>
</div>
<div class="control-group">
	<div class="control-label">
		<?php echo EshopHtmlHelper::getFieldLabel('invoice_start_number', JText::_('ESHOP_CONFIG_INVOICE_START_NUMBER'), JText::_('ESHOP_CONFIG_INVOICE_START_NUMBER_HELP')); ?>
	</div>
	<div class="controls">
		<input class="text_area" type="text" name="invoice_start_number" id="invoice_start_number" size="15" value="<?php echo $this->config->invoice_start_number; ?>" />
	</div>
</div>
<div class="control-group">
	<div class="control-label">
		<?php echo EshopHtmlHelper::getFieldLabel('reset_invoice_number', JText::_('ESHOP_CONFIG_RESET_INVOICE_NUMBER'), JText::_('ESHOP_CONFIG_RESET_INVOICE_NUMBER_HELP')); ?>
	</div>
	<div class="controls">
		<?php echo $this->lists['reset_invoice_number']; ?>
	</div>
</div>
<div class="control-group">
	<div class="control-label">
		<?php echo EshopHtmlHelper::getFieldLabel('invoice_prefix', JText::_('ESHOP_CONFIG_INVOICE_PREFIX'), JText::_('ESHOP_CONFIG_INVOICE_PREFIX_HELP')); ?>
	</div>
	<div class="controls">
		<input class="text_area" type="text" name="invoice_prefix" id="invoice_prefix" size="15" value="<?php echo $this->config->invoice_prefix; ?>" />
	</div>
</div>
<div class="control-group">
	<div class="control-label">
		<?php echo EshopHtmlHelper::getFieldLabel('invoice_number_length', JText::_('ESHOP_CONFIG_INVOICE_NUMBER_LENGTH'), JText::_('ESHOP_CONFIG_INVOICE_NUMBER_LENGTH_HELP')); ?>
	</div>
	<div class="controls">
		<input class="text_area" type="text" name="invoice_number_length" id="invoice_number_length" size="15" value="<?php echo $this->config->invoice_number_length; ?>" />
	</div>
</div>
<div class="control-group">
	<div class="control-label">
		<?php echo EshopHtmlHelper::getFieldLabel('send_invoice_to_customer', JText::_('ESHOP_CONFIG_INVOICE_SEND_TO_CUSTOMER'), JText::_('ESHOP_CONFIG_INVOICE_SEND_TO_CUSTOMER_HELP')); ?>
	</div>
	<div class="controls">
		<?php echo $this->lists['send_invoice_to_customer']; ?>
	</div>
</div>
<div class="control-group">
	<div class="control-label">
		<?php echo EshopHtmlHelper::getFieldLabel('send_invoice_to_admin', JText::_('ESHOP_CONFIG_INVOICE_SEND_TO_ADMIN'), JText::_('ESHOP_CONFIG_INVOICE_SEND_TO_ADMIN_HELP')); ?>
	</div>
	<div class="controls">
		<?php echo $this->lists['send_invoice_to_admin']; ?>
	</div>
</div>
<div class="control-group">
	<div class="control-label">
		<?php echo EshopHtmlHelper::getFieldLabel('pdf_font', JText::_('ESHOP_CONFIG_INVOICE_PDF_FONT'), JText::_('ESHOP_CONFIG_INVOICE_PDF_FONT_HELP')); ?>
	</div>
	<div class="controls">
		<?php echo $this->lists['pdf_font']; ?>
	</div>
</div>
	