<?php
/**
 * @version        3.1.0
 * @package        Joomla
 * @subpackage     EShop
 * @author         Giang Dinh Truong
 * @copyright      Copyright (C) 2012 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die();

$editor       = JEditor::getInstance(JFactory::getConfig()->get('editor'));;
$translatable = JLanguageMultilang::isEnabled() && count($this->languages) > 1;
$rootUri      = JUri::root();
?>
<table class="adminlist table table-bordered" style="text-align: center;">
	<thead>
	<tr>
		<th class="title" width="30%"><?php echo JText::_('ESHOP_ATTRIBUTE'); ?></th>
		<th class="title" width="45%"><?php echo JText::_('ESHOP_VALUE'); ?></th>
		<th class="title" width="15%"><?php echo JText::_('ESHOP_PUBLISHED'); ?></th>
		<th class="title" width="10%">&nbsp;</th>
	</tr>
	</thead>
	<tbody id="product_attributes_area">
	<?php
	$options = array();
	$options[] = JHtml::_('select.option', '1', Jtext::_('ESHOP_YES'));
	$options[] = JHtml::_('select.option', '0', Jtext::_('ESHOP_NO'));

	for ($i = 0; $n = count($this->productAttributes), $i < $n; $i++)
	{
		$productAttribute = $this->productAttributes[$i];
	?>
		<tr id="product_attribute_<?php echo $i; ?>">
			<td style="text-align: center; vertical-align: middle;">
				<?php echo $this->lists['attributes_'.$productAttribute->id]; ?>
			</td>
			<td style="text-align: center; vertical-align: middle;">
				<?php
				if ($translatable)
				{
					foreach ($this->languages as $language)
					{
						$langCode = $language->lang_code;
					?>
						<input class="input-large" type="text" name="attribute_value_<?php echo $langCode; ?>[]" maxlength="255" value="<?php echo isset($productAttribute->{'value_'.$langCode}) ? $productAttribute->{'value_'.$langCode} : ''; ?>" />
						<img src="<?php echo $rootUri; ?>media/com_eshop/flags/<?php echo $this->languageData['flag'][$langCode]; ?>" />
						<input type="hidden" class="inputbox" name="productattributedetails_id_<?php echo $langCode; ?>[]" value="<?php echo isset($productAttribute->{'productattributedetails_id_'.$langCode}) ? htmlentities($productAttribute->{'productattributedetails_id_'.$langCode}) : ''; ?>" />
						<br />
					<?php
					}
				}
				else
				{
				?>
					<input class="input-medium" type="text" name="attribute_value[]" maxlength="255" value="<?php echo htmlentities($productAttribute->value); ?>" />
					<input type="hidden" class="inputbox" name="productattributedetails_id[]" value="<?php echo $productAttribute->productattributedetails_id; ?>" />
				<?php
				}
				?>
				<input type="hidden" class="inputbox" name="productattribute_id[]" value="<?php echo $productAttribute->productattribute_id; ?>" />
			</td>
			<td style="text-align: center; vertical-align: middle;">
				<?php echo JHtml::_('select.genericlist', $options, 'attribute_published[]', ' class="inputbox"', 'value', 'text', $productAttribute->published); ?>
			</td>
			<td style="text-align: center; vertical-align: middle;">
				<input type="button" class="btn btn-small btn-primary" name="btnRemove" value="<?php echo JText::_('ESHOP_BTN_REMOVE'); ?>" onclick="removeProductAttribute(<?php echo $i; ?>);" />
			</td>
		</tr>
	<?php
	}
	?>
	</tbody>
	<tfoot>
	<tr>
		<td colspan="4">
			<input type="button" class="btn btn-small btn-primary" name="btnAdd" value="<?php echo JText::_('ESHOP_BTN_ADD'); ?>" onclick="addProductAttribute();" />
		</td>
	</tr>
	</tfoot>
</table>
