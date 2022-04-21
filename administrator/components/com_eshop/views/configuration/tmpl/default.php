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

JHtml::_('bootstrap.tooltip');
$document = JFactory::getDocument();
$document->addStyleDeclaration(".hasTip{display:block !important}");
JToolbarHelper::title(JText::_('ESHOP_CONFIGURATION'), 'generic.png');
JToolbarHelper::apply('configuration.save');
JToolbarHelper::cancel('configuration.cancel');

JHtml::_('behavior.tabstate');

$canDo	= EshopHelper::getActions();

if ($canDo->get('core.admin'))
{
	JToolbarHelper::preferences('com_eshop');
}

$editor = JEditor::getInstance(JFactory::getConfig()->get('editor'));;
?>
<script type="text/javascript">
	Joomla.submitbutton = function(pressbutton)
	{
		var form = document.adminForm;
		if (pressbutton == 'configuration.cancel') {
			Joomla.submitform(pressbutton, form);
			return;
		} else {
			//Validate the entered data before submittings
			if (form.catalog_limit.value == '') {
				alert("<?php echo JText::_('ESHOP_CONFIG_ENTER_DEFAULT_ITEMS_PER_PAGE'); ?>");
				form.catalog_limit.focus();
				return;
			}
			if (form.items_per_row.value == '') {
				alert("<?php echo JText::_('ESHOP_CONFIG_ENTER_DEFAULT_ITEMS_PER_ROW'); ?>");
				form.items_per_row.focus();
				return;
			}
			if (form.image_category_width.value == '') {
				alert("<?php echo JText::_('ESHOP_CONFIG_ENTER_CATEGORY_IMAGE_WIDTH'); ?>");
				form.image_category_width.focus();
				return;
			}
			if (form.image_category_height.value == '') {
				alert("<?php echo JText::_('ESHOP_CONFIG_ENTER_CATEGORY_IMAGE_HEIGHT'); ?>");
				form.image_category_height.focus();
				return;
			}
			if (form.image_thumb_width.value == '') {
				alert("<?php echo JText::_('ESHOP_CONFIG_ENTER_PRODUCT_IMAGE_THUMB_WIDTH'); ?>");
				form.image_thumb_width.focus();
				return;
			}
			if (form.image_thumb_height.value == '') {
				alert("<?php echo JText::_('ESHOP_CONFIG_ENTER_PRODUCT_IMAGE_THUMB_HEIGHT'); ?>");
				form.image_thumb_height.focus();
				return;
			}
			if (form.image_popup_width.value == '') {
				alert("<?php echo JText::_('ESHOP_CONFIG_ENTER_PRODUCT_IMAGE_POPUP_WIDTH'); ?>");
				form.image_popup_width.focus();
				return;
			}
			if (form.image_popup_height.value == '') {
				alert("<?php echo JText::_('ESHOP_CONFIG_ENTER_PRODUCT_IMAGE_POPUP_HEIGHT'); ?>");
				form.image_popup_height.focus();
				return;
			}
			if (form.image_list_width.value == '') {
				alert("<?php echo JText::_('ESHOP_CONFIG_ENTER_PRODUCT_IMAGE_LIST_WIDTH'); ?>");
				form.image_list_width.focus();
				return;
			}
			if (form.image_list_height.value == '') {
				alert("<?php echo JText::_('ESHOP_CONFIG_ENTER_PRODUCT_IMAGE_LIST_HEIGHT'); ?>");
				form.image_list_height.focus();
				return;
			}
			if (form.image_additional_width.value == '') {
				alert("<?php echo JText::_('ESHOP_CONFIG_ENTER_ADDITIONAL_PRODUCT_IMAGE_WIDTH'); ?>");
				form.image_additional_width.focus();
				return;
			}
			if (form.image_additional_height.value == '') {
				alert("<?php echo JText::_('ESHOP_CONFIG_ENTER_ADDITIONAL_PRODUCT_IMAGE_HEIGHT'); ?>");
				form.image_additional_height.focus();
				return;
			}
			if (form.image_related_width.value == '') {
				alert("<?php echo JText::_('ESHOP_CONFIG_ENTER_RELATED_PRODUCT_IMAGE_WIDTH'); ?>");
				form.image_related_width.focus();
				return;
			}
			if (form.image_related_height.value == '') {
				alert("<?php echo JText::_('ESHOP_CONFIG_ENTER_RELATED_PRODUCT_IMAGE_HEIGHT'); ?>");
				form.image_related_height.focus();
				return;
			}
			if (form.image_compare_width.value == '') {
				alert("<?php echo JText::_('ESHOP_CONFIG_ENTER_COMPARE_IMAGE_WIDTH'); ?>");
				form.image_compare_width.focus();
				return;
			}
			if (form.image_compare_height.value == '') {
				alert("<?php echo JText::_('ESHOP_CONFIG_ENTER_COMPARE_IMAGE_HEIGHT'); ?>");
				form.image_compare_height.focus();
				return;
			}
			if (form.image_wishlist_width.value == '') {
				alert("<?php echo JText::_('ESHOP_CONFIG_ENTER_WISH_LIST_IMAGE_WIDTH'); ?>");
				form.image_wishlist_width.focus();
				return;
			}
			if (form.image_wishlist_height.value == '') {
				alert("<?php echo JText::_('ESHOP_CONFIG_ENTER_WISH_LIST_IMAGE_HEIGHT'); ?>");
				form.image_wishlist_height.focus();
				return;
			}
			if (form.image_cart_width.value == '') {
				alert("<?php echo JText::_('ESHOP_CONFIG_ENTER_CART_IMAGE_WIDTH'); ?>");
				form.image_cart_width.focus();
				return;
			}
			if (form.image_cart_height.value == '') {
				alert("<?php echo JText::_('ESHOP_CONFIG_ENTER_CART_IMAGE_HEIGHT'); ?>");
				form.image_cart_height.focus();
				return;
			}
			Joomla.submitform(pressbutton, form);
		}
	}
</script>
<div class="row-fluid">
	<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data" class="form-horizontal">
		<?php
		echo JHtml::_('bootstrap.startTabSet', 'configuration', array('active' => 'general-page'));

		echo JHtml::_('bootstrap.addTab', 'configuration', 'general-page', JText::_('ESHOP_CONFIG_GENERAL', true));
		echo $this->loadTemplate('general');
		echo JHtml::_('bootstrap.endTab');

		echo JHtml::_('bootstrap.addTab', 'configuration', 'local-page', JText::_('ESHOP_CONFIG_LOCAL', true));
		echo $this->loadTemplate('local');
		echo JHtml::_('bootstrap.endTab');

		echo JHtml::_('bootstrap.addTab', 'configuration', 'option-page', JText::_('ESHOP_CONFIG_OPTION', true));
		echo $this->loadTemplate('option');
		echo JHtml::_('bootstrap.endTab');

		echo JHtml::_('bootstrap.addTab', 'configuration', 'image-page', JText::_('ESHOP_CONFIG_IMAGE', true));
		echo $this->loadTemplate('image');
		echo JHtml::_('bootstrap.endTab');

		echo JHtml::_('bootstrap.addTab', 'configuration', 'layout-page', JText::_('ESHOP_CONFIG_LAYOUT', true));
		echo $this->loadTemplate('layout');
		echo JHtml::_('bootstrap.endTab');

		echo JHtml::_('bootstrap.addTab', 'configuration', 'invoice-page', JText::_('ESHOP_CONFIG_INVOICE', true));
		echo $this->loadTemplate('invoice');
		echo JHtml::_('bootstrap.endTab');

		echo JHtml::_('bootstrap.addTab', 'configuration', 'sorting-page', JText::_('ESHOP_CONFIG_SORTING', true));
		echo $this->loadTemplate('sorting');
		echo JHtml::_('bootstrap.endTab');

		echo JHtml::_('bootstrap.addTab', 'configuration', 'social-page', JText::_('ESHOP_CONFIG_SOCIAL', true));
		echo $this->loadTemplate('social');
		echo JHtml::_('bootstrap.endTab');

		echo JHtml::_('bootstrap.addTab', 'configuration', 'mail-page', JText::_('ESHOP_CONFIG_MAIL', true));
		echo $this->loadTemplate('mail');
		echo JHtml::_('bootstrap.endTab');
		
		echo $this->loadTemplate('custom_css');

		echo JHtml::_('bootstrap.endTabSet');
		?>
		<input type="hidden" name="option" value="com_eshop" />
		<input type="hidden" name="task" value="" />
		<div class="clearfix"></div>
	</form>
</div>