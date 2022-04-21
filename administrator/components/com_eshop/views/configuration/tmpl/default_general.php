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
$editor = JEditor::getInstance(JFactory::getConfig()->get('editor'));;
?>
<div class="control-group">
	<div class="control-label">
		<?php echo EshopHtmlHelper::getFieldLabel('download_id', JText::_('ESHOP_DOWNLOAD_ID'), JText::_('ESHOP_DOWNLOAD_ID_HELP')); ?>
	</div>
	<div class="controls">
		<input class="text_area" type="text" name="download_id" id="download_id" size="15" maxlength="250" value="<?php echo isset($this->config->download_id) ? $this->config->download_id : ''; ?>" />
	</div>
</div>
<div class="control-group">
	<div class="control-label">
		<?php echo EshopHtmlHelper::getFieldLabel('store_name', JText::_('ESHOP_CONFIG_STORE_NAME')); ?>
	</div>
	<div class="controls">
		<input class="text_area" type="text" name="store_name" id="store_name" size="15" maxlength="250" value="<?php echo $this->config->store_name; ?>" />
	</div>
</div>
<div class="control-group">
	<div class="control-label">
		<?php echo EshopHtmlHelper::getFieldLabel('store_owner', JText::_('ESHOP_CONFIG_STORE_OWNER')); ?>
	</div>
	<div class="controls">
		<input class="text_area" type="text" name="store_owner" id="store_owner" size="15" maxlength="250" value="<?php echo $this->config->store_owner; ?>" />
	</div>
</div>
<div class="control-group">
	<div class="control-label">
		<?php echo EshopHtmlHelper::getFieldLabel('address', JText::_('ESHOP_CONFIG_ADDRESS')); ?>
	</div>
	<div class="controls">
		<textarea rows="5" cols="40" name="address" id="address"><?php echo $this->config->address; ?></textarea>					
	</div>
</div>
<div class="control-group">
	<div class="control-label">
		<?php echo EshopHtmlHelper::getFieldLabel('email', JText::_('ESHOP_CONFIG_EMAIL')); ?>
	</div>
	<div class="controls">
		<input class="text_area" type="text" name="email" id="email" size="15" maxlength="100" value="<?php echo $this->config->email; ?>" />
	</div>
</div>
<div class="control-group">
	<div class="control-label">
		<?php echo EshopHtmlHelper::getFieldLabel('telephone', JText::_('ESHOP_CONFIG_TELEPHONE')); ?>
	</div>
	<div class="controls">
		<input class="text_area" type="text" name="telephone" id="telephone" size="10" maxlength="15" value="<?php echo $this->config->telephone; ?>" />
	</div>
</div>
<div class="control-group">
	<div class="control-label">
		<?php echo EshopHtmlHelper::getFieldLabel('fax', JText::_('ESHOP_CONFIG_FAX')); ?>
	</div>
	<div class="controls">
		<input class="text_area" type="text" name="fax" id="fax" size="10" maxlength="15" value="<?php echo $this->config->fax; ?>" />
	</div>
</div>
<div class="control-group">
	<div class="control-label">
		<?php echo EshopHtmlHelper::getFieldLabel('introduction_display_on', JText::_('ESHOP_CONFIG_INTRODUCTION_DISPLAY_ON')); ?>
	</div>
	<div class="controls">
		<?php echo $this->lists['introduction_display_on']; ?>
	</div>
</div>