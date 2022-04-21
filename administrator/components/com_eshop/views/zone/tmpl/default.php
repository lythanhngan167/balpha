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
<script type="text/javascript">	
	Joomla.submitbutton = function(pressbutton)
	{
		var form = document.adminForm;
		if (pressbutton == 'zone.cancel') {
			Joomla.submitform(pressbutton, form);
			return;				
		} else {
			//Validate the entered data before submitting
			if (form.zone_name.value == '') {
				alert("<?php echo JText::_('ESHOP_ENTER_NAME'); ?>");
				form.zone_name.focus();
				return;
			}
			if (form.country_id.value == '') {
				alert("<?php echo JText::_('ESHOP_SELECT_COUNTRY'); ?>");
				form.country_id.focus();
				return;
			}
			if (form.zone_code.value == '') {
				alert("<?php echo JText::_('ESHOP_ENTER_CODE'); ?>");
				form.zone_code.focus();
				return;
			}
			Joomla.submitform(pressbutton, form);
		}
	}
</script>
<form action="index.php" method="post" name="adminForm" id="adminForm" class="form form-horizontal">
	<div class="control-group">
		<div class="control-label">
			<span class="required">*</span>
			<?php echo  JText::_('ESHOP_NAME'); ?>
		</div>
		<div class="controls">
			<input class="text-xlarge" type="text" name="zone_name" id="zone_name" maxlength="128" value="<?php echo $this->item->zone_name; ?>" />
		</div>
	</div>				
	<div class="control-group">
		<div class="control-label">
			<span class="required">*</span>
			<?php echo  JText::_('ESHOP_COUNTRY'); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['countries']; ?>
		</div>
	</div>				
	<div class="control-group">
		<div class="control-label">
			<span class="required">*</span>
			<?php echo  JText::_('ESHOP_CODE'); ?>
		</div>
		<div class="controls">
			<input class="text-small" type="text" name="zone_code" id="zone_code" maxlength="32" value="<?php echo $this->item->zone_code; ?>" />
		</div>
	</div>				
	<div class="control-group">
		<div class="control-label">
			<?php echo JText::_('ESHOP_PUBLISHED'); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['published']; ?>
		</div>
	</div>
	<div class="clearfix"></div>
	<?php echo JHtml::_( 'form.token' ); ?>
	<input type="hidden" name="option" value="com_eshop" />
	<input type="hidden" name="cid[]" value="<?php echo intval($this->item->id); ?>" />
	<input type="hidden" name="task" value="" />
</form>