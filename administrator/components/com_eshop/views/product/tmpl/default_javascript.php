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
?>
<script type="text/javascript">
	Joomla.submitbutton = function(pressbutton)
	{
		var form = document.adminForm;
		if (pressbutton == 'product.cancel') {
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
			if (document.getElementById('product_name_<?php echo $langId; ?>').value == '') {
				alert("<?php echo addcslashes(JText::_('ESHOP_ENTER_NAME'), '"'); ?>");
				document.getElementById('product_name_<?php echo $langId; ?>').focus();
				return;
			}
			<?php
			}
			}
			else
			{
			?>
			if (form.product_name.value == '') {
				alert("<?php echo addcslashes(JText::_('ESHOP_ENTER_NAME'), '"'); ?>");
				form.product_name.focus();
				return;
			}
			<?php
			}
			?>
			if (form.product_sku.value == '') {
				alert("<?php echo addcslashes(JText::_('ESHOP_ENTER_PRODUCT_SKU'), '"'); ?>");
				form.product_sku.focus();
				return;
			}

			if (form.product_quantity.value == '') {
					alert("<?php echo addcslashes('Vui lòng nhập Số lượng!', '"'); ?>");
					form.product_sku.focus();
					return;
			}

			if (form.number_pv.value == '' || form.number_pv.value == 0) {
					alert("<?php echo addcslashes('Vui lòng nhập PV lớn hơn 0!', '"'); ?>");
					form.number_pv.focus();
					return;
			}

			if (form.main_category_id.value == '0') {
				alert("<?php echo addcslashes(JText::_('ESHOP_SELECT_CATEGORY_PROMPT'), '"'); ?>");
				form.main_category_id.focus();
				return;
			}
			Joomla.submitform(pressbutton, form);
		}
	}
	//Add or Remove product images
	var countProductImages = '<?php echo count($this->productImages); ?>';
	function addProductImage() {
		var html = '<tr id="product_image_' + countProductImages + '" style="height: 100px;">'
		//Image column
		html += '<td style="text-align: center; vertical-align: middle;"><input type="file" class="input" size="20" accept="image/*" name="image[]" /></td>';
		//Ordering column
		html += '<td style="text-align: center; vertical-align: middle;"><input class="input-small" type="text" name="image_ordering[]" maxlength="10" value="" /></td>';
		//Published column
		html += '<td style="text-align: center; vertical-align: middle;"><select class="inputbox" name="image_published[]">';
		html += '<option selected="selected" value="1"><?php echo addcslashes(JText::_('ESHOP_YES'), "'"); ?></option>';
		html += '<option value="0"><?php echo addcslashes(JText::_('ESHOP_NO'), "'"); ?></option>';
		html += '</select></td>';
		// Remove button column
		html += '<td style="text-align: center; vertical-align: middle;"><input type="button" class="btn btn-small btn-primary" name="btnRemove" value="<?php echo addcslashes(JText::_('ESHOP_BTN_REMOVE'), "'"); ?>" onclick="removeProductImage('+countProductImages+');" /></td>';
		html += '</tr>';
		jQuery('#product_images_area').append(html);
		countProductImages++;
	}
	function removeProductImage(rowIndex) {
		jQuery('#product_image_' + rowIndex).remove();
	}
	//Add or Remove product attachments
	var countProductAttachments = '<?php echo count($this->productAttachments); ?>';
	function addProductAttachment() {
		var html = '<tr id="product_attachment_' + countProductAttachments + '" style="height: 100px;">'
		//Image column
		html += '<td style="text-align: center; vertical-align: middle;"><input type="file" class="input" size="20" name="attachment[]" /></td>';
		//Ordering column
		html += '<td style="text-align: center; vertical-align: middle;"><input class="input-small" type="text" name="attachment_ordering[]" maxlength="10" value="" /></td>';
		//Published column
		html += '<td style="text-align: center; vertical-align: middle;"><select class="inputbox" name="attachment_published[]">';
		html += '<option selected="selected" value="1"><?php echo addcslashes(JText::_('ESHOP_YES'), "'"); ?></option>';
		html += '<option value="0"><?php echo addcslashes(JText::_('ESHOP_NO'), "'"); ?></option>';
		html += '</select></td>';
		// Remove button column
		html += '<td style="text-align: center; vertical-align: middle;"><input type="button" class="btn btn-small btn-primary" name="btnRemove" value="<?php echo addcslashes(JText::_('ESHOP_BTN_REMOVE'), "'"); ?>" onclick="removeProductAttachment('+countProductAttachments+');" /></td>';
		html += '</tr>';
		jQuery('#product_attachments_area').append(html);
		countProductAttachments++;
	}
	function removeProductAttachment(rowIndex) {
		jQuery('#product_attachment_' + rowIndex).remove();
	}
	//Add or Remove product attributes
	var countProductAttributes = '<?php echo count($this->productAttributes); ?>';
	function addProductAttribute() {
		var html = '<tr id="product_attribute_' + countProductAttributes + '">'
		//Attribute column
		html += '<td style="text-align: center; vertical-align: middle;"><?php echo preg_replace(array('/\r/', '/\n/'), '', $this->lists['attributes']); ?></td>';
		//Value column
		html += '<td style="text-align: center;">';
		<?php
		if ($translatable)
		{
		?>

		<?php
		foreach ($this->languages as $language) {
		$langCode = $language->lang_code;
		?>
		html += '<input class="input-large" type="text" name="attribute_value_<?php echo $langCode; ?>[]" maxlength="255" value="" />';
		html += '<img src="<?php echo JURI::root(); ?>media/com_eshop/flags/<?php echo $this->languageData['flag'][$langCode]; ?>" /><br />';
		<?php
		}
		}
		else
		{
		?>
		html += '<input class="input-large" type="text" name="attribute_value[]" maxlength="255" value="" />';
		<?php
		}
		?>
		html += '</td>';
		//Published column
		html += '<td style="text-align: center; vertical-align: middle;"><select class="inputbox" name="attribute_published[]">';
		html += '<option selected="selected" value="1"><?php echo addcslashes(JText::_('ESHOP_YES'), "'"); ?></option>';
		html += '<option value="0"><?php echo addcslashes(JText::_('ESHOP_NO'), "'"); ?></option>';
		html += '</select></td>';
		// Remove button column
		html += '<td style="text-align: center; vertical-align: middle;"><input type="button" class="btn btn-small btn-primary" name="btnRemove" value="<?php echo addcslashes(JText::_('ESHOP_BTN_REMOVE'), "'"); ?>" onclick="removeProductAttribute('+countProductAttributes+');" /></td>';
		html += '</tr>';
		jQuery('#product_attributes_area').append(html);
		countProductAttributes++;
	}
	function removeProductAttribute(rowIndex) {
		jQuery('#product_attribute_' + rowIndex).remove();
	}
	//Options
	<?php
	$addedOptions = array();
	for ($i = 0; $n = count($this->productOptions), $i < $n; $i++) {
		$addedOptions[] = '"'.$this->productOptions[$i]->id.'"';
	}
	?>
	var addedOptions = new Array(<?php echo implode($addedOptions, ','); ?>);
	function addProductOption() {
		//Change active tab
		for (var i = 0; i < addedOptions.length; i++) {
			jQuery('#product_option_'+addedOptions[i]).attr('class', '');
			jQuery('#option-'+addedOptions[i]+'-page').attr('class', 'tab-pane');
		}
		var optionSel = document.getElementById('option_id');
		//Find option type
		var optionTypeSel = document.getElementById('option_type_id');
		var optionType = 'Select';
		for (var i = 0; i < optionTypeSel.length; i++) {
			if (optionTypeSel.options[i].value == optionSel.value) {
				var optionType = optionTypeSel.options[i].text;
			}
		}
		//working
		var htmlTab = '<li id="product_option_'+optionSel.value+'" class="active">';
		htmlTab += '<a data-toggle="tab" href="#option-'+optionSel.value+'-page">'+optionSel.options[optionSel.selectedIndex].text;
		htmlTab += '<img onclick="removeProductOption('+optionSel.value+', \''+optionSel.options[optionSel.selectedIndex].text+'\');" src="<?php echo JURI::base(); ?>components/com_eshop/assets/images/remove.png" />';
		htmlTab += '<input type="hidden" value="'+optionSel.value+'" name="productoption_id[]">';
		htmlTab += '</a></li>';
		jQuery('#nav-tabs').append(htmlTab);
		var htmlContent = '<div id="option-'+optionSel.value+'-page" class="tab-pane active">';
		htmlContent += '<table class="adminlist" style="width: 100%;">';
		htmlContent += '<tbody>';
		htmlContent += '<tr>';
		htmlContent += '<td style="width: 150px;"><?php echo addcslashes(JText::_('ESHOP_REQUIRED'), "'"); ?></td>';
		htmlContent += '<td><select class="inputbox" name="required_'+optionSel.value+'" id="required">';
		htmlContent += '<option selected="selected" value="1"><?php echo addcslashes(JText::_('ESHOP_YES'), "'"); ?></option>';
		htmlContent += '<option value="0"><?php echo addcslashes(JText::_('ESHOP_NO'), "'"); ?></option></select></td>';
		htmlContent += '</tr>';
		htmlContent += '</tbody>';
		htmlContent += '</table>';
		if (optionType == 'Select' || optionType == 'Radio' || optionType == 'Checkbox')
		{
			htmlContent += '<table style="text-align: center;" class="adminlist table table-bordered">';
			htmlContent += '<thead>';
			htmlContent += '<tr><th width="" class="title"><?php echo addcslashes(JText::_('ESHOP_OPTION_VALUE'), "'"); ?></th><th class="title" style="display:none;" width=""><?php echo addcslashes(JText::_('ESHOP_SKU'), "'"); ?></th><th width="" style="display:none;" class="title"><?php echo addcslashes(JText::_('ESHOP_QUANTITY'), "'"); ?></th><th width="" class="title"><?php echo addcslashes(JText::_('ESHOP_PRICE'), "'"); ?></th><th width="" class="title"><?php echo addcslashes(JText::_('ESHOP_WEIGHT'), "'"); ?></th><th class="title" width="" nowrap="nowrap"><?php echo addcslashes(JText::_('ESHOP_IMAGE'), "'"); ?></th><th width="" class="title">&nbsp;</th></tr>';
			htmlContent += '</thead>';
			htmlContent += '<tbody id="product_option_'+optionSel.value+'_values_area"></tbody>';
			htmlContent += '<tfoot>';
			htmlContent += '<tr><td colspan="7"><input type="button" onclick="addProductOptionValue('+optionSel.value+');" value="Thêm" name="btnAdd" class="btn btn-small btn-primary"></td></tr>';
			htmlContent += '</tfoot>';
			htmlContent += '</table>';
		}
		else if(optionType == 'Text' || optionType == 'Textarea')
		{
			htmlContent += '<table class="adminlist" style="width: 100%;">';
			htmlContent += '<tbody>';
			htmlContent += '<tr>';
			htmlContent += '<td style="width: 150px;"><?php echo addcslashes(JText::_('ESHOP_PRODUCT_PRICE_PER_CHAR'), "'"); ?></td>';
			htmlContent += '<td>';
			htmlContent += '<input type="hidden" value="null" id="optionvalue_'+optionSel.value+'_id" name="optionvalue_'+optionSel.value+'_id[]">';
			htmlContent += '<select style="width:auto;" class="inputbox" name="optionvalue_'+optionSel.value+'_price_sign[]" id="optionvalue_'+optionSel.value+'_price_sign">';
			htmlContent +=	jQuery('#price_sign').html();
			htmlContent += '</select>&nbsp;';
			htmlContent += '<input type="text" value="" maxlength="255" size="10" name="optionvalue_'+optionSel.value+'_price[]" class="input-small">';
			htmlContent += '<select style="width:auto;" class="inputbox" name="optionvalue_'+optionSel.value+'_price_type[]" id="optionvalue_'+optionSel.value+'_price_type">';
			htmlContent +=	jQuery('#price_type').html();
			htmlContent += '</select>&nbsp;';
			htmlContent += '</td>';
			htmlContent += '</tr>';
			htmlContent += '</tbody>';
			htmlContent += '</table>';
		}
		htmlContent += '</div>';
		jQuery('#tab-content').append(htmlContent);
		addedOptions[addedOptions.length] = optionSel.value;
		for (var i = optionSel.length - 1; i>=0; i--) {
			if (optionSel.options[i].selected) {
				optionSel.remove(i);
				break;
			}
		}
	}
	function removeProductOption(optionId, optionName) {
		var optionHtml = '<option value="'+optionId+'">'+optionName+'</option>';
		jQuery('#option_id').append(optionHtml);
		jQuery('#product_option_'+optionId).remove();
		jQuery('#option-'+optionId+'-page').remove();
		addedOptions.splice( addedOptions.indexOf(optionId), 1);
	}
	//Option Values
	var countProductOptionValues = new Array();
	<?php
	for ($i = 0; $n = count($this->productOptions), $i < $n; $i++) {
	$productOption = $this->productOptions[$i];
	?>
	countProductOptionValues['<?php echo $productOption->id ?>'] = '<?php echo count($this->productOptionValues[$i]); ?>';
	<?php
	}
	for ($i = 0; $n = count($this->options), $i < $n; $i++) {
	?>
	if (countProductOptionValues['<?php echo $this->options[$i]->id; ?>'] === undefined) {
		countProductOptionValues['<?php echo $this->options[$i]->id; ?>'] = 0;
	}
	<?php
	}
	?>
	function addProductOptionValue(optionId) {
		var html = '<tr id="product_option_'+optionId+'_value_'+countProductOptionValues[optionId]+'">';
		//Option Value column
		html += '<td style="text-align: center;">';
		html += '<select class="inputbox" name="optionvalue_'+optionId+'_id[]">';
		html +=	jQuery('#option_values_'+optionId).html();
		html += '</select>'
		html += '</td>';
		//SKU column
		html += '<td style="text-align: center; display: none;">';
		html += '<input type="text" value="" maxlength="255" name="optionvalue_'+optionId+'_sku[]" class="input-small">';
		html += '</td>';
		//Quantity column
		html += '<td style="text-align: center;  display: none;">';
		html += '<input type="text" value="1000" maxlength="255" name="optionvalue_'+optionId+'_quantity[]" class="input-small">';
		html += '</td>';
		//Price column
		html += '<td style="text-align: center;">';
		html += '<select class="inputbox" name="optionvalue_'+optionId+'_price_sign[]">';
		html +=	jQuery('#price_sign').html();
		html += '</select>';
		html += '<input type="text" value="" maxlength="255" name="optionvalue_'+optionId+'_price[]" class="input-small">';
		html += '<select class="inputbox" name="optionvalue_'+optionId+'_price_type[]">';
		html +=	jQuery('#price_type').html();
		html += '</select>';
		html += '</td>';
		//Weight column
		html += '<td style="text-align: center;">';
		html += '<select class="inputbox" name="optionvalue_'+optionId+'_weight_sign[]">';
		html +=	jQuery('#weight_sign').html();
		html += '</select>'
		html += '<input type="text" value="" maxlength="255" name="optionvalue_'+optionId+'_weight[]" class="input-small">';
		html += '</td>';
		//Image column
		html += '<td style="text-align: center;">';
		html += '<input type="file" name="optionvalue_'+optionId+'_image[]" accept="image/*" class="input-small">';
		html += '</td>';
		//Remove button column
		html += '<td style="text-align: center;">';
		html += '<input type="button" onclick="removeProductOptionValue('+optionId+', '+countProductOptionValues[optionId]+');" value="Xóa" name="btnRemove" class="btn btn-small btn-primary">';
		html += '</td>';
		html += '</tr>';
		jQuery('#product_option_'+optionId+'_values_area').append(html);
		countProductOptionValues[optionId]++;
	}
	function removeProductOptionValue(optionId, rowIndex) {
		jQuery('#product_option_'+optionId+'_value_'+rowIndex).remove();
	}
	var countProductDiscounts = '<?php echo count($this->productDiscounts); ?>';
	function addProductDiscount() {
		var html = '<tr id="product_discount_' + countProductDiscounts + '">';
		//Customer group column
		html += '<td style="text-align: center;"><?php echo preg_replace(array('/\r/', '/\n/'), '', $this->lists['discount_customer_group']); ?></td>';
		//Quantity column
		html += '<td style="text-align: center;">';
		html += '<input type="text" value="" maxlength="10" name="discount_quantity[]" class="input-mini" />';
		html += '</td>';
		//Priority column
		html += '<td style="text-align: center;">';
		html += '<input type="text" value="" maxlength="10" name="discount_priority[]" class="input-small" />';
		html += '</td>';
		//Price column
		html += '<td style="text-align: center;">';
		html += '<input type="text" value="" maxlength="10" name="discount_price[]" class="input-small" />';
		html += '</td>';
		//Start date column
		html += '<td style="text-align: center;">';
		<?php
		if (version_compare(JVERSION, '3.6.9', 'ge'))
		{
		?>
			var datePicker = jQuery('#date_html_container').html();
			datePicker = datePicker.replace(/tmp_date_picker_id/g, "discount_date_start_" + countProductDiscounts);
			datePicker = datePicker.replace(/tmp_date_picker_name/g, "discount_date_start[]");
			html += datePicker;
		<?php
		}
		else
		{
		?>
			html += '<input type="text" style="width: 100px;" class="input-medium hasTooltip" value="" id="discount_date_start_'+countProductDiscounts+'" name="discount_date_start[]">';
			html += '<button id="discount_date_start_'+countProductDiscounts+'_img" class="btn" type="button"><i class="icon-calendar"></i></button>';
		<?php
		}
		?>
		html += '</td>';
		//End date column
		html += '<td style="text-align: center;">';
    	<?php
		if (version_compare(JVERSION, '3.6.9', 'ge'))
		{
		?>
			var datePicker = jQuery('#date_html_container').html();
			datePicker = datePicker.replace(/tmp_date_picker_id/g, "discount_date_end_" + countProductDiscounts);
			datePicker = datePicker.replace(/tmp_date_picker_name/g, "discount_date_end[]");
			html += datePicker;
		<?php
		}
		else
		{
		?>
    		html += '<input type="text" style="width: 100px;" class="input-medium hasTooltip" value="" id="discount_date_end_'+countProductDiscounts+'" name="discount_date_end[]">';
    		html += '<button id="discount_date_end_'+countProductDiscounts+'_img" class="btn" type="button"><i class="icon-calendar"></i></button>';
		<?php
		}
    	?>
    	html += '</td>';
		//Published column
		html += '<td style="text-align: center;"><select class="inputbox" name="discount_published[]">';
		html += '<option selected="selected" value="1"><?php echo addcslashes(JText::_('ESHOP_YES'), "'"); ?></option>';
		html += '<option value="0"><?php echo addcslashes(JText::_('ESHOP_NO'), "'"); ?></option>';
		html += '</select></td>';
		// Remove button column
		html += '<td style="text-align: center;"><input type="button" class="btn btn-small btn-primary" name="btnRemove" value="<?php echo addcslashes(JText::_('ESHOP_BTN_REMOVE'), "'"); ?>" onclick="removeProductDiscount('+countProductDiscounts+');" /></td>';
		html += '</tr>';
		jQuery('#product_discounts_area').append(html);
		Calendar.setup({
			// Id of the input field
			inputField: "discount_date_start_"+countProductDiscounts,
			// Format of the input field
			ifFormat: "%Y-%m-%d",
			// Trigger for the calendar (button ID)
			button: "discount_date_start_"+countProductDiscounts+"_img",
			// Alignment (defaults to "Bl")
			align: "Tl",
			singleClick: true,
			firstDay: 0
		});
		Calendar.setup({
			// Id of the input field
			inputField: "discount_date_end_"+countProductDiscounts,
			// Format of the input field
			ifFormat: "%Y-%m-%d",
			// Trigger for the calendar (button ID)
			button: "discount_date_end_"+countProductDiscounts+"_img",
			// Alignment (defaults to "Bl")
			align: "Tl",
			singleClick: true,
			firstDay: 0
		});
		countProductDiscounts++;
	}
	function removeProductDiscount(rowIndex) {
		jQuery('#product_discount_' + rowIndex).remove();
	}
	var countProductSpecials = '<?php echo count($this->productSpecials); ?>';
	function addProductSpecial() {
		var html = '<tr id="product_special_' + countProductSpecials + '">';
		//Customer group column
		html += '<td style="text-align: center;"><?php echo preg_replace(array('/\r/', '/\n/'), '', $this->lists['special_customer_group']); ?></td>';
		//Priority column
		html += '<td style="text-align: center;">';
		html += '<input type="text" value="" maxlength="10" name="special_priority[]" class="input-small" />';
		html += '</td>';
		//Price column
		html += '<td style="text-align: center;">';
		html += '<input type="text" value="" maxlength="10" name="special_price[]" class="input-small" />';
		html += '</td>';
		//Start date column
		html += '<td style="text-align: center;">';
		<?php
		if (version_compare(JVERSION, '3.6.9', 'ge'))
		{
		?>
			var datePicker = jQuery('#date_html_container').html();
			datePicker = datePicker.replace(/tmp_date_picker_id/g, "special_date_start_" + countProductSpecials);
			datePicker = datePicker.replace(/tmp_date_picker_name/g, "special_date_start[]");
			html += datePicker;
		<?php
		}
		else
		{
		?>
			html += '<input type="text" style="width: 100px; " value="" id="special_date_start_'+countProductSpecials+'" name="special_date_start[]">';
			html += '<button id="special_date_start_'+countProductSpecials+'_img" class="btn" type="button"><i class="icon-calendar"></i></button>';
		<?php
		}
		?>
		html += '</td>';
		//End date column
		html += '<td style="text-align: center;">';
		<?php
		if (version_compare(JVERSION, '3.6.9', 'ge'))
		{
		?>
			var datePicker = jQuery('#date_html_container').html();
			datePicker = datePicker.replace(/tmp_date_picker_id/g, "special_date_end_" + countProductSpecials);
			datePicker = datePicker.replace(/tmp_date_picker_name/g, "special_date_end[]");
			html += datePicker;
		<?php
		}
		else
		{
		?>
			html += '<input type="text" style="width: 100px; " value="" id="special_date_end_'+countProductSpecials+'" name="special_date_end[]">';
			html += '<button id="special_date_end_'+countProductSpecials+'_img" class="btn" type="button"><i class="icon-calendar"></i></button>';
		<?php
		}
		?>
		html += '</td>';
		//Published column
		html += '<td style="text-align: center;"><select class="inputbox" name="special_published[]">';
		html += '<option selected="selected" value="1"><?php echo addcslashes(JText::_('ESHOP_YES'), "'"); ?></option>';
		html += '<option value="0"><?php echo addcslashes(JText::_('ESHOP_NO'), "'"); ?></option>';
		html += '</select></td>';
		// Remove button column
		html += '<td style="text-align: center;"><input type="button" class="btn btn-small btn-primary" name="btnRemove" value="<?php echo addcslashes(JText::_('ESHOP_BTN_REMOVE'), "'"); ?>" onclick="removeProductSpecial('+countProductSpecials+');" /></td>';
		html += '</tr>';
		jQuery('#product_specials_area').append(html);
		Calendar.setup({
			// Id of the input field
			inputField: "special_date_start_"+countProductSpecials,
			// Format of the input field
			ifFormat: "%Y-%m-%d",
			// Trigger for the calendar (button ID)
			button: "special_date_start_"+countProductSpecials+"_img",
			// Alignment (defaults to "Bl")
			align: "Tl",
			singleClick: true,
			firstDay: 0
		});
		Calendar.setup({
			// Id of the input field
			inputField: "special_date_end_"+countProductSpecials,
			// Format of the input field
			ifFormat: "%Y-%m-%d",
			// Trigger for the calendar (button ID)
			button: "special_date_end_"+countProductSpecials+"_img",
			// Alignment (defaults to "Bl")
			align: "Tl",
			singleClick: true,
			firstDay: 0
		});
		countProductSpecials++;
	}
	function removeProductSpecial(rowIndex) {
		jQuery('#product_special_' + rowIndex).remove();
	}
</script>
