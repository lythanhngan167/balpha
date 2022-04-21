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

$rootUri =  JUri::root();
?>
<table class="adminlist table table-bordered" style="text-align: center;">
	<thead>
	<tr>
		<th class="title" width="40%"><?php echo JText::_('ESHOP_ATTACHMENTS'); ?></th>
		<th class="title" width="20%"><?php echo JText::_('ESHOP_ORDERING'); ?></th>
		<th class="title" width="20%"><?php echo JText::_('ESHOP_PUBLISHED'); ?></th>
		<th class="title" width="20%">&nbsp;</th>
	</tr>
	</thead>
	<tbody id="product_attachments_area">
	<?php
	$options = array();
	$options[] = JHtml::_('select.option', '1', Jtext::_('ESHOP_YES'));
	$options[] = JHtml::_('select.option', '0', Jtext::_('ESHOP_NO'));
	for ($i = 0; $n = count($this->productAttachments), $i < $n; $i++) {
		$productAttachment = $this->productAttachments[$i];
		?>
		<tr id="product_attachment_<?php echo $i; ?>">
			<td style="text-align: center; vertical-align: middle;">
				<?php
				if (JFile::exists(JPATH_ROOT.'/media/com_eshop/attachments/'.$productAttachment->file_name))
				{ ?>
					<a href="<?php echo $rootUri . 'media/com_eshop/attachments/' . $productAttachment->file_name; ?>"><?php echo $productAttachment->file_name; ?></a>
					<input type="hidden" class="inputbox" name="productattachment_id[]" value="<?php echo $productAttachment->id; ?>" />
				<?php } ?>
			</td>
			<td style="text-align: center; vertical-align: middle;">
				<input class="input-small" type="text" name="productattachment_ordering[]" size="5" maxlength="10" value="<?php echo $productAttachment->ordering; ?>" />
			</td>
			<td style="text-align: center; vertical-align: middle;">
				<?php echo JHtml::_('select.genericlist', $options, 'productattachment_published[]', ' class="inputbox"', 'value', 'text', $productAttachment->published); ?>
			</td>
			<td style="text-align: center; vertical-align: middle;">
				<input type="button" class="btn btn-small btn-primary" name="btnRemove" value="<?php echo JText::_('ESHOP_BTN_REMOVE'); ?>" onclick="removeProductAttachment(<?php echo $i; ?>);" />
			</td>
		</tr>
		<?php
	}
	?>
	</tbody>
	<tfoot>
	<tr>
		<td colspan="4">
			<input type="button" class="btn btn-small btn-primary" name="btnAdd" value="<?php echo JText::_('ESHOP_BTN_ADD'); ?>" onclick="addProductAttachment();" />
		</td>
	</tr>
	</tfoot>
</table>
