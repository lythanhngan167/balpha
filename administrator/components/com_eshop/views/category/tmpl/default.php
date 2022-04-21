<?php
/**
 * @version		3.1.0
 * @package		Joomla
 * @subpackage	ESshop
 * @author  	Giang Dinh Truong
 * @copyright	Copyright (C) 2012 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined( '_JEXEC' ) or die();
EshopHelper::chosen();
$editor = JEditor::getInstance(JFactory::getConfig()->get('editor'));;
$translatable = JLanguageMultilang::isEnabled() && count($this->languages) > 1;
?>
<script type="text/javascript">
	Joomla.submitbutton = function(pressbutton)
	{
		var form = document.adminForm;
		if (pressbutton == 'category.cancel') {
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
					if (document.getElementById('category_name_<?php echo $langId; ?>').value == '') {
						alert("<?php echo JText::_('ESHOP_ENTER_NAME'); ?>");
						document.getElementById('category_name_<?php echo $langId; ?>').focus();
						return;
					}
					<?php
				}
			}
			else
			{
				?>
				if (form.category_name.value == '') {
					alert("<?php echo JText::_('ESHOP_ENTER_NAME'); ?>");
					form.category_name.focus();
					return;
				}
				<?php
			}
			?>
			Joomla.submitform(pressbutton, form);
		}
	}
</script>
<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data" class="form form-horizontal">
	<?php
	echo JHtml::_('bootstrap.startTabSet', 'category', array('active' => 'general-page'));
	echo JHtml::_('bootstrap.addTab', 'category', 'general-page', JText::_('ESHOP_GENERAL', true));

	if ($translatable)
	{
        $rootUri = JUri::root();
        echo JHtml::_('bootstrap.startTabSet', 'category-translation', array('active' => 'translation-page-'.$this->languages[0]->sef));

		foreach ($this->languages as $language)
		{
			$langId = $language->lang_id;
			$langCode = $language->lang_code;
			$sef = $language->sef;
			echo JHtml::_('bootstrap.addTab', 'category-translation', 'translation-page-' . $sef, $language->title . ' <img src="' . $rootUri . 'media/com_eshop/flags/' . $sef . '.gif" />');
            ?>
			<div class="control-group">
				<div class="control-label">
					<span class="required">*</span>
					<?php echo  JText::_('ESHOP_NAME'); ?>
				</div>
				<div class="controls">
					<input class="input-xlarge" type="text" name="category_name_<?php echo $langCode; ?>" id="category_name_<?php echo $langId; ?>" size="" maxlength="250" value="<?php echo isset($this->item->{'category_name_'.$langCode}) ? $this->item->{'category_name_'.$langCode} : ''; ?>" />
				</div>
			</div>
			<div class="control-group">
				<div class="control-label">
					<?php echo  JText::_('ESHOP_ALIAS'); ?>
				</div>
				<div class="controls">
					<input class="input-xlarge" type="text" name="category_alias_<?php echo $langCode; ?>" id="category_alias_<?php echo $langId; ?>" size="" maxlength="250" value="<?php echo isset($this->item->{'category_alias_'.$langCode}) ? $this->item->{'category_alias_'.$langCode} : ''; ?>" />
				</div>
			</div>
			<div class="control-group">
				<div class="control-label">
					<?php echo  JText::_('ESHOP_PAGE_TITLE'); ?>
				</div>
				<div class="controls">
					<input class="input-xlarge" type="text" name="category_page_title_<?php echo $langCode; ?>" id="category_page_title_<?php echo $langId; ?>" size="" maxlength="250" value="<?php echo isset($this->item->{'category_page_title_'.$langCode}) ? $this->item->{'category_page_title_'.$langCode} : ''; ?>" />
				</div>
			</div>
			<div class="control-group">
				<div class="control-label">
					<?php echo  JText::_('ESHOP_PAGE_HEADING'); ?>
				</div>
				<div class="controls">
					<input class="input-xlarge" type="text" name="category_page_heading_<?php echo $langCode; ?>" id="category_page_heading_<?php echo $langId; ?>" size="" maxlength="250" value="<?php echo isset($this->item->{'category_page_heading_'.$langCode}) ? $this->item->{'category_page_heading_'.$langCode} : ''; ?>" />
				</div>
			</div>
			<div class="control-group">
				<div class="control-label">
					<?php echo  JText::_('ESHOP_ALT_IMAGE'); ?>
				</div>
				<div class="controls">
					<input class="input-xlarge" type="text" name="alt_image_<?php echo $langCode; ?>" id="alt_image_<?php echo $langId; ?>" size="" maxlength="250" value="<?php echo isset($this->item->{'alt_image_'.$langCode}) ? $this->item->{'alt_image_'.$langCode} : ''; ?>" />
				</div>
			</div>
			<div class="control-group">
				<div class="control-label">
					<?php echo JText::_('ESHOP_DESCRIPTION'); ?>
				</div>
				<div class="controls">
					<?php echo $editor->display( 'category_desc_'.$langCode,  isset($this->item->{'category_desc_'.$langCode}) ? $this->item->{'category_desc_'.$langCode} : '' , '100%', '250', '75', '10' ); ?>
				</div>
			</div>
			<div class="control-group">
				<div class="control-label">
					<?php echo JText::_('ESHOP_META_KEYS'); ?>
				</div>
				<div class="controls">
					<textarea rows="5" cols="30" name="meta_key_<?php echo $langCode; ?>"><?php echo $this->item->{'meta_key_'.$langCode}; ?></textarea>
				</div>
			</div>
			<div class="control-group">
				<div class="control-label">
					<?php echo JText::_('ESHOP_META_DESC'); ?>
				</div>
				<div class="controls">
					<textarea rows="5" cols="30" name="meta_desc_<?php echo $langCode; ?>"><?php echo $this->item->{'meta_desc_'.$langCode}; ?></textarea>
				</div>
			</div>
			<?php
            echo JHtml::_('bootstrap.endTab');
		}
		echo JHtml::_('bootstrap.endTabSet');
	}
	else
	{
		?>
		<div class="control-group">
			<div class="control-label">
				<span class="required">*</span>
				<?php echo  JText::_('ESHOP_NAME'); ?>
			</div>
			<div class="controls">
				<input class="input-xlarge" type="text" name="category_name" id="category_name" size="" maxlength="250" value="<?php echo $this->item->category_name; ?>" />
			</div>
		</div>
		<div class="control-group">
			<div class="control-label">
				<?php echo  JText::_('ESHOP_PARENT_CATEGORY'); ?>
			</div>
			<div class="controls">
				<?php echo $this->lists['category_parent_id']; ?>
			</div>
		</div>
		<div class="control-group">
			<div class="control-label">
				<?php echo  JText::_('ESHOP_CATEGORY_IMAGE'); ?>
			</div>
			<div class="controls">
				<input type="file" class="input-large" accept="image/*" name="category_image" />
				<?php
					if (JFile::exists(JPATH_ROOT.'/media/com_eshop/categories/'.$this->item->category_image))
					{
						$viewImage = JFile::stripExt($this->item->category_image).'-100x100.'.JFile::getExt($this->item->category_image);
						if (Jfile::exists(JPATH_ROOT.'/media/com_eshop/categories/resized/'.$viewImage))
						{
							?>
							<img src="<?php echo JURI::root().'media/com_eshop/categories/resized/'.$viewImage; ?>" />
							<?php
						}
						else
						{
							?>
							<img src="<?php echo JURI::root().'media/com_eshop/categories/'.$this->item->category_image; ?>" height="100" />
							<?php
						}
						?>
						<input type="checkbox" name="remove_image" value="1" />
						<?php echo JText::_('ESHOP_REMOVE_IMAGE'); ?>
						<?php
					}
				?>
			</div>
		</div>
		<div class="control-group">
			<div class="control-label">
				Icon
			</div>
			<div class="controls">
				<input type="file" class="input-large" accept="image/*" name="category_image_icon" />
				<?php
					if (JFile::exists(JPATH_ROOT.'/media/com_eshop/categories/'.$this->item->category_image_icon))
					{
						$viewImage = JFile::stripExt($this->item->category_image_icon).'-100x100.'.JFile::getExt($this->item->category_image_icon);
						if (Jfile::exists(JPATH_ROOT.'/media/com_eshop/categories/resized/'.$viewImage))
						{
							?>
							<img src="<?php echo JURI::root().'media/com_eshop/categories/resized/'.$viewImage; ?>" />
							<?php
						}
						else
						{
							?>
							<img src="<?php echo JURI::root().'media/com_eshop/categories/'.$this->item->category_image_icon; ?>" height="100" />
							<?php
						}
						?>
						<input type="checkbox" name="remove_image_icon" value="1" />
						<?php echo JText::_('ESHOP_REMOVE_IMAGE'); ?>
						<?php
					}
				?>
			</div>
		</div>
		<div class="control-group">
			<div class="control-label">
				<?php echo  JText::_('ESHOP_ALIAS'); ?>
			</div>
			<div class="controls">
				<input class="input-xlarge" type="text" name="category_alias" id="category_alias" size="" maxlength="250" value="<?php echo $this->item->category_alias; ?>" />
			</div>
		</div>
		<div class="control-group display">
			<div class="control-label">
				<?php echo  JText::_('ESHOP_PAGE_TITLE'); ?>
			</div>
			<div class="controls">
				<input class="input-xlarge" type="text" name="category_page_title" id="category_page_title" size="" maxlength="250" value="<?php echo $this->item->category_page_title; ?>" />
			</div>
		</div>
		<div class="control-group display">
			<div class="control-label">
				<?php echo  JText::_('ESHOP_PAGE_HEADING'); ?>
			</div>
			<div class="controls">
				<input class="input-xlarge" type="text" name="category_page_heading" id="category_page_heading" size="" maxlength="250" value="<?php echo $this->item->category_page_heading; ?>" />
			</div>
		</div>
		<div class="control-group display">
			<div class="control-label">
				<?php echo  JText::_('ESHOP_ALT_IMAGE'); ?>
			</div>
			<div class="controls">
				<input class="input-xlarge" type="text" name="category_alt_image" id="category_alt_image" size="" maxlength="250" value="<?php echo $this->item->category_alt_image; ?>" />
			</div>
		</div>
		<div class="control-group">
			<div class="control-label">
				<?php echo JText::_('ESHOP_DESCRIPTION'); ?>
			</div>
			<div class="controls">
				<?php echo $editor->display( 'category_desc',  $this->item->category_desc , '100%', '250', '75', '10' ); ?>
			</div>
		</div>
		<div class="control-group display" >
			<div class="control-label">
				<?php echo JText::_('ESHOP_META_KEYS'); ?>
			</div>
			<div class="controls">
				<textarea rows="5" cols="30" name="meta_key"><?php echo $this->item->meta_key; ?></textarea>
			</div>
		</div>
		<div class="control-group display">
			<div class="control-label">
				<?php echo JText::_('ESHOP_META_DESC'); ?>
			</div>
			<div class="controls">
				<textarea rows="5" cols="30" name="meta_desc"><?php echo $this->item->meta_desc; ?></textarea>
			</div>
		</div>
		<?php
	}

	echo JHtml::_('bootstrap.endTab');
	echo JHtml::_('bootstrap.addTab', 'category', 'data-page', JText::_('ESHOP_DATA', true));
	?>

	<div class="control-group">
		<div class="control-label">
			<?php echo JText::_('ESHOP_CATEGORY_LAYOUT'); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['category_layout']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo JText::_('ESHOP_CUSTOMERGROUPS'); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['category_customergroups']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo  JText::_('ESHOP_PRODUCTS_PER_PAGE'); ?>
		</div>
		<div class="controls">
			<input class="input-small" type="text" name="products_per_page" id="products_per_page" size="" maxlength="250" value="<?php echo $this->item->products_per_page; ?>" />
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo  JText::_('ESHOP_PRODUCTS_PER_ROW'); ?>
		</div>
		<div class="controls">
			<input class="input-small" type="text" name="products_per_row" id="products_per_row" size="" maxlength="250" value="<?php echo $this->item->products_per_row; ?>" />
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
		<input type="hidden" name="details_id" value="<?php echo intval(isset($this->item->{'details_id'}) ? $this->item->{'details_id'} : ''); ?>" />
		<?php
	}
	?>
	<input type="hidden" name="task" value="" />
</form>

<style>
.display{display:none;}
a[href^="#data-page"] {
   display: none!important;
}
</style>
