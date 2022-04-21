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
$translatable = JLanguageMultilang::isEnabled() && count($this->languages) > 1;
EshopHelper::chosen();
?>
<script type="text/javascript">	
	Joomla.submitbutton = function(pressbutton)
	{
		var form = document.adminForm;
		if (pressbutton == 'label.cancel') {
			Joomla.submitform(pressbutton, form);
			return;
		} else {
			//Validate the entered data before submitting
			<?php
			if ($translatable)
			{
				foreach ($this->languages as $language)
				{
					$langId = $language->lang_id;
					?>
					if (document.getElementById('label_name_<?php echo $langId; ?>').value == '') {
						alert("<?php echo JText::_('ESHOP_ENTER_NAME'); ?>");
						document.getElementById('label_name_<?php echo $langId; ?>').focus();
						return;
					}
					<?php
				}
			}
			else
			{
				?>
				if (form.label_name.value == '') {
					alert("<?php echo JText::_('ESHOP_ENTER_NAME'); ?>");
					form.label_name.focus();
					return;
				}
				<?php
			}
			?>
			if (form.label_start_date.value > form.label_end_date.value) {
				alert("<?php echo JText::_('ESHOP_DATE_VALIDATE'); ?>");
				form.label_start_date.focus();
				return;
			}
			Joomla.submitform(pressbutton, form);
		}
	}
</script>
<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data" class="form form-horizontal">
	<div class="control-group">
		<div class="control-label">
			<span class="required">*</span>
			<?php echo  JText::_('ESHOP_NAME'); ?>
		</div>
		<div class="controls" style="margin-left:372px;">
			<?php
			if ($translatable)
			{
				foreach ($this->languages as $language)
				{
					$langId = $language->lang_id;
					$langCode = $language->lang_code;
					?>
					<input class="input-xlarge" type="text" name="label_name_<?php echo $langCode; ?>" id="label_name_<?php echo $langId; ?>" size="" maxlength="255" value="<?php echo isset($this->item->{'label_name_'.$langCode}) ? $this->item->{'label_name_'.$langCode} : ''; ?>" />
					<img src="<?php echo JURI::root(); ?>media/com_eshop/flags/<?php echo $this->languageData['flag'][$langCode]; ?>" />
					<br />
					<?php
				}
			}
			else 
			{
				?>
				<input class="input-xlarge" type="text" name="label_name" id="label_name" maxlength="255" value="<?php echo $this->item->label_name; ?>" />
				<?php
			}
			?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo JText::_('ESHOP_LABEL_STYLE'); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['label_style']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo JText::_('ESHOP_LABEL_POSITION'); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['label_position']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo JText::_('ESHOP_LABEL_BOLD'); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['label_bold']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo JText::_('ESHOP_LABEL_BACKGROUND_COLOR'); ?>
		</div>
		<div class="controls">
			<input type="text" name="label_background_color" class="inputbox color {required:false}" value="<?php echo $this->item->label_background_color; ?>" size="5" />
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo JText::_('ESHOP_LABEL_FOREGROUND_COLOR'); ?>
		</div>
		<div class="controls">
			<input type="text" name="label_foreground_color" class="inputbox color {required:false}" value="<?php echo $this->item->label_foreground_color; ?>" size="5" />
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo JText::_('ESHOP_LABEL_OPACITY'); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['label_opacity']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo JText::_('ESHOP_ENABLE_IMAGE'); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['enable_image']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo JText::_('ESHOP_LABEL_IMAGE'); ?>
		</div>
		<div class="controls">
			<input type="file" class="input-large" accept="image/*" name="label_image" />								
			<?php
			if (JFile::exists(JPATH_ROOT.'/media/com_eshop/labels/'.$this->item->label_image))
			{
				$imageWidth = $this->item->label_image_width > 0 ? $this->item->label_image_width : EshopHelper::getConfigValue('label_image_width');
				if (!$imageWidth)
					$imageWidth = 40;
				$imageHeight = $this->item->label_image_height > 0 ? $this->item->label_image_height : EshopHelper::getConfigValue('label_image_height');
				if (!$imageHeight)
					$imageHeight = 40;
				$viewImage = JFile::stripExt($this->item->label_image).'-'.$imageWidth.'x'.$imageHeight.'.'.JFile::getExt($this->item->label_image);
				if (Jfile::exists(JPATH_ROOT.'/media/com_eshop/labels/resized/'.$viewImage))
				{
					?>
					<img src="<?php echo JURI::root().'media/com_eshop/labels/resized/'.$viewImage; ?>" />
					<?php
				}
				else 
				{
					?>
					<img src="<?php echo JURI::root().'media/com_eshop/labels/'.$this->item->label_image; ?>" height="100" />
					<?php
				}
				?>
				<label class="checkbox">
					<input type="checkbox" name="remove_image" value="1" />
					<?php echo JText::_('ESHOP_REMOVE_IMAGE'); ?>
				</label>
				<?php
			}
			?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo JText::_('ESHOP_LABEL_IMAGE_WIDTH'); ?>
		</div>
		<div class="controls">
			<input type="text" class="input-large" name="label_image_width" id="label_image_width" value="<?php echo $this->item->label_image_width; ?>" />
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo JText::_('ESHOP_LABEL_IMAGE_HEIGHT'); ?>
		</div>
		<div class="controls">
			<input type="text" class="input-large" name="label_image_height" id="label_image_height" value="<?php echo $this->item->label_image_height; ?>" />
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo JText::_('ESHOP_SELECT_PRODUCTS'); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['products']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo JText::_('ESHOP_SELECT_MANUFACTURERS'); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['manufacturers']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo JText::_('ESHOP_CATEGORIES'); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['categories']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo  JText::_('ESHOP_START_DATE'); ?>
		</div>
		<div class="controls" style="margin-left:370px;">
			<?php echo JHtml::_('calendar', (($this->item->label_start_date == $this->nullDate) ||  !$this->item->label_start_date) ? '' : JHtml::_('date', $this->item->label_start_date, 'Y-m-d', null), 'label_start_date', 'label_start_date', '%Y-%m-%d', array('style' => 'width: 100px;')); ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo  JText::_('ESHOP_END_DATE'); ?>
		</div>
		<div class="controls" style="margin-left:370px;">
			<?php echo JHtml::_('calendar', (($this->item->label_end_date == $this->nullDate) ||  !$this->item->label_end_date) ? '' : JHtml::_('date', $this->item->label_end_date, 'Y-m-d', null), 'label_end_date', 'label_end_date', '%Y-%m-%d', array('style' => 'width: 100px;')); ?>
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
	<?php echo JHtml::_( 'form.token' ); ?>
	<input type="hidden" name="option" value="com_eshop" />
	<input type="hidden" name="cid[]" value="<?php echo intval($this->item->id); ?>" />
	<?php
	if ($translatable)
	{
		foreach ($this->languages as $language)
		{
			$langCode = $language->lang_code;
			?>
			<input type="hidden" name="details_id_<?php echo $langCode; ?>" value="<?php echo intval(isset($this->item->{'details_id_' . $langCode}) ? $this->item->{'details_id_' . $langCode} : ''); ?>" />
			<?php
		}
	}
	elseif ($this->translatable)
	{
	?>
		<input type="hidden" name="details_id" value="<?php echo isset($this->item->{'details_id'}) ? $this->item->{'details_id'} : ''; ?>" />
		<?php
	}
	?>
	<input type="hidden" name="task" value="" />
</form>