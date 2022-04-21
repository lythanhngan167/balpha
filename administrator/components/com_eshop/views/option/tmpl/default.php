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
$translatable = JLanguageMultilang::isEnabled() && count($this->languages) > 1;
?>
<script type="text/javascript">	
	Joomla.submitbutton = function(pressbutton)
	{
		var form = document.adminForm;
		if (pressbutton == 'option.cancel') {
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
					if (document.getElementById('option_name_<?php echo $langId; ?>').value == '') {
						alert("<?php echo JText::_('ESHOP_ENTER_NAME'); ?>");
						document.getElementById('option_name_<?php echo $langId; ?>').focus();
						return;
					}
					<?php
				}
			}
			else
			{
				?>
				if (form.option_name.value == '') {
					alert("<?php echo JText::_('ESHOP_ENTER_NAME'); ?>");
					form.option_name.focus();
					return;
				}
				<?php
			}
			?>
			Joomla.submitform(pressbutton, form);
		}								
	}
	var countOptionValues = '<?php echo count($this->lists['option_values']); ?>';
	function addOptionValue() {
		var html = '<tr id="option_value_' + countOptionValues + '">'
		// Option Value column
		html += '<td style="text-align: center; vertical-align: middle;">';
		<?php
		if ($translatable)
		{
			foreach ($this->languages as $language)
			{
				$langCode = $language->lang_code;
				?>
				html += '<input class="input-large" type="text" name="value_<?php echo $langCode; ?>[]" maxlength="255" value="" />';
				html += '<img src="<?php echo JURI::root(); ?>media/com_eshop/flags/<?php echo $this->languageData['flag'][$langCode]; ?>" /><br />';
				<?php
			}	
		}
		else 
		{
			?>
			html += '<input class="input-large" type="text" name="value[]" maxlength="255" value="" />';
			<?php
		}
		?>
		html += '</td>';
		// Ordering column
		html += '<td style="text-align: center; vertical-align: middle;"><input class="input-small" type="text" name="ordering[]" maxlength="10" value="" /></td>';
		// Published column
		html += '<td style="text-align: center; vertical-align: middle;"><select class="inputbox" name="optionvalue_published[]" id="published">';
		html += '<option selected="selected" value="1"><?php echo JText::_('ESHOP_YES'); ?></option>';
		html += '<option value="0"><?php echo JText::_('ESHOP_NO'); ?></option>';
		html += '</select></td>';
		// Remove button column
		html += '<td style="text-align: center; vertical-align: middle;"><input type="button" class="btn btn-small btn-primary" name="btnRemove" value="<?php echo JText::_('ESHOP_BTN_REMOVE'); ?>" onclick="removeOptionValue('+countOptionValues+');" /></td>';
		html += '</tr>';
		jQuery('#option_values_area').append(html);
		countOptionValues++;
	}
	function removeOptionValue(rowIndex) {
		jQuery('#option_value_' + rowIndex).remove();
	}
</script>
<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data" class="form form-horizontal">
	<?php
	echo JHtml::_('bootstrap.startTabSet', 'option', array('active' => 'option-details-page'));
	echo JHtml::_('bootstrap.addTab', 'option', 'option-details-page', JText::_('ESHOP_OPTION_DETAILS', true));
	?>
	<div class="control-group">
		<div class="control-label">
			<span class="required">*</span>
			<?php echo  JText::_('ESHOP_NAME'); ?>
		</div>
		<div class="controls">
			<?php
			if ($translatable)
			{
				foreach ($this->languages as $language)
				{
					$langId = $language->lang_id;
					$langCode = $language->lang_code;
					?>
					<input class="input-xlarge" type="text" name="option_name_<?php echo $langCode; ?>" id="option_name_<?php echo $langId; ?>" size="" maxlength="255" value="<?php echo isset($this->item->{'option_name_'.$langCode}) ? $this->item->{'option_name_'.$langCode} : ''; ?>" />
					<img src="<?php echo JURI::root(); ?>media/com_eshop/flags/<?php echo $this->languageData['flag'][$langCode]; ?>" />
					<br />
					<?php
				}
			}
			else 
			{
				?>
				<input class="input-xlarge" type="text" name="option_name" id="option_name" maxlength="255" value="<?php echo $this->item->option_name; ?>" />
				<?php
			}
			?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo  JText::_('ESHOP_OPTION_TYPE'); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['option_type']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo  JText::_('ESHOP_OPTION_IMAGE'); ?>
		</div>
		<div class="controls">
			<input type="file" class="input-large" accept="image/*" name="option_image" /><br />
			<?php
				if (JFile::exists(JPATH_ROOT.'/media/com_eshop/options/'.$this->item->option_image))
				{
					$viewImage = JFile::stripExt($this->item->option_image).'-100x100.'.JFile::getExt($this->item->option_image);
					if (Jfile::exists(JPATH_ROOT.'/media/com_eshop/options/resized/'.$viewImage))
					{
						?>
						<img src="<?php echo JURI::root().'media/com_eshop/options/resized/'.$viewImage; ?>" />
						<?php
					}
					else 
					{
						?>
						<img src="<?php echo JURI::root().'media/com_eshop/options/'.$this->item->option_image; ?>" height="100" />
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
			<?php echo  JText::_('ESHOP_OPTION_DESCRIPTION'); ?>
		</div>
		<div class="controls">
			<?php
			if ($translatable)
			{
				?>
				<div class="row-fluid">
				<?php
				foreach ($this->languages as $language)
				{
					$langId = $language->lang_id;
					$langCode = $language->lang_code;
					?>
					<div class="span12">
						<img src="<?php echo JURI::root(); ?>media/com_eshop/flags/<?php echo $this->languageData['flag'][$langCode]; ?>" />
						<?php
						echo $editor->display( 'option_desc_'.$langCode,  isset($this->item->{'option_desc_'.$langCode}) ? $this->item->{'option_desc_'.$langCode} : '' , '80%', '250', '75', '10' );
						?>
					</div>
					<br />
					<?php
				}
				?>
				</div>
				<?php
			}
			else 
			{
				echo $editor->display( 'option_desc',  $this->item->option_desc , '80%', '250', '75', '10' );
			}
			?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo  JText::_('ESHOP_PUBLISHED'); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['published']; ?>
		</div>
	</div>
	<?php
	echo JHtml::_('bootstrap.endTab');
	echo JHtml::_('bootstrap.addTab', 'option', 'option-values-page', JText::_('ESHOP_OPTION_VALUES', true));
	?>
	<table class="adminlist table table-bordered" style="text-align: center;">
		<thead>
			<tr>
				<th class="title" width="50%"><?php echo JText::_('ESHOP_OPTION_VALUE'); ?></th>
				<th class="title" width="20%"><?php echo JText::_('ESHOP_ORDERING'); ?></th>
				<th class="title" width="15%"><?php echo JText::_('ESHOP_PUBLISHED'); ?></th>
				<th class="title" width="15%">&nbsp;</th>
			</tr>
		</thead>
		<tbody id="option_values_area">
			<?php
			$options = array();
			$options[] = JHtml::_('select.option', '1', Jtext::_('ESHOP_YES'));
			$options[] = JHtml::_('select.option', '0', Jtext::_('ESHOP_NO'));
			$optionValues = $this->lists['option_values'];
			for ($i = 0; $n = count($optionValues), $i < $n; $i++)
			{
				$optionValue = $optionValues[$i];
				?>
				<tr id="option_value_<?php echo $i; ?>">
					<td style="text-align: center; vertical-align: middle;">
						<?php
						if ($translatable)
						{
							foreach ($this->languages as $language)
							{
								$langCode = $language->lang_code;
								?>
								<input class="input-large" type="text" name="value_<?php echo $langCode; ?>[]" maxlength="255" value="<?php echo isset($optionValue->{'value_'.$langCode}) ? htmlentities($optionValue->{'value_'.$langCode}) : ''; ?>" />
								<img src="<?php echo JURI::root(); ?>media/com_eshop/flags/<?php echo $this->languageData['flag'][$langCode]; ?>" />
								<input type="hidden" class="inputbox" name="optionvaluedetails_id_<?php echo $langCode; ?>[]" value="<?php echo isset($optionValue->{'optionvaluedetails_id_'.$langCode}) ? $optionValue->{'optionvaluedetails_id_'.$langCode} : ''; ?>" />
								<br />
								<?php
							}
						}
						else
						{
							?>
							<input class="input-large" type="text" name="value[]" maxlength="255" value="<?php echo htmlentities($optionValue->value); ?>" />
							<input type="hidden" class="inputbox" name="optionvaluedetails_id[]" value="<?php echo $optionValue->optionvaluedetails_id; ?>" />
							<?php
						}
						?>
						<input type="hidden" class="inputbox" name="optionvalue_id[]" value="<?php echo $optionValue->id; ?>" />
					</td>
					<td style="text-align: center; vertical-align: middle;">
						<input class="input-small" type="text" name="ordering[]" maxlength="10" value="<?php echo $optionValue->ordering; ?>" />
					</td>
					<td style="text-align: center; vertical-align: middle;">
						<?php echo JHtml::_('select.genericlist', $options, 'optionvalue_published[]', ' class="inputbox"', 'value', 'text', $optionValue->published); ?>
					</td>
					<td style="text-align: center; vertical-align: middle;">
						<input type="button" class="btn btn-small btn-primary" name="btnRemove" value="<?php echo JText::_('ESHOP_BTN_REMOVE'); ?>" onclick="removeOptionValue(<?php echo $i; ?>);" />
					</td>
				</tr>
				<?php
			}
			?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="4">
					<input type="button" class="btn btn-small btn-primary" name="btnAdd" value="<?php echo JText::_('ESHOP_BTN_ADD'); ?>" onclick="addOptionValue();" />
				</td>
			</tr>
		</tfoot>
	</table>
	<?php
	echo JHtml::_('bootstrap.endTab');
	echo JHtml::_('bootstrap.endTabSet');
	?>
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