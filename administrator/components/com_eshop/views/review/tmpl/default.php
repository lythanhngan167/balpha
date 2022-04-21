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
EshopHelper::chosen();
$editor = JEditor::getInstance(JFactory::getConfig()->get('editor'));; 	
?>
<script type="text/javascript">	
	Joomla.submitbutton = function(pressbutton)
	{
		var form = document.adminForm;
		if (pressbutton == 'review.cancel') {
			Joomla.submitform(pressbutton, form);
			return;				
		} else {
			//Validate the entered data before submitting
			if (form.author.value.length < 3 || form.author.value.length > 25) {
				alert("<?php echo JText::_('ESHOP_ERROR_AUTHOR'); ?>");
				form.author.focus();
				return;
			}
			if (form.review.value.length < 3 || form.review.value.length > 1000) {
				alert("<?php echo JText::_('ESHOP_ERROR_REVIEW'); ?>");
				form.review.focus();
				return;
			}
			for (var i = 0; i < 5; i++) {
				if (form.rating[i].checked) {
					break;
				}
			}
			if (i == 5) {
				alert("<?php echo JText::_('ESHOP_ERROR_RATING'); ?>");
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
			<?php echo  JText::_('ESHOP_AUTHOR'); ?>
		</div>
		<div class="controls">
			<input class="text-xlarge" type="text" name="author" id="author" maxlength="128" value="<?php echo $this->item->author; ?>" />
		</div>
	</div>				
	<div class="control-group">
		<div class="control-label">
			<?php echo JText::_('ESHOP_PRODUCT'); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['products']; ?>
		</div>
	</div>				
	<div class="control-group">
		<div class="control-label">
			<span class="required">*</span>
			<?php echo JText::_('ESHOP_REVIEW'); ?>
		</div>
		<div class="controls">
			<textarea name="review" cols="40" rows="5"><?php echo $this->item->review; ?></textarea>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<span class="required">*</span>
			<?php echo JText::_('ESHOP_RATING'); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['rating']; ?>
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
</fieldset>
	<div class="clearfix"></div>
	<?php echo JHtml::_( 'form.token' ); ?>
	<input type="hidden" name="option" value="com_eshop" />
	<input type="hidden" name="cid[]" value="<?php echo intval($this->item->id); ?>" />
	<input type="hidden" name="task" value="" />
</form>