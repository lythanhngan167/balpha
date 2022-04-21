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
<fieldset class="form-horizontal">
	<legend><?php echo JText::_('ESHOP_CONFIG_LAYOUT_GENERAL'); ?></legend>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('theme', JText::_('ESHOP_CONFIG_THEME')); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['theme']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('theme', JText::_('ESHOP_CONFIG_PRODUCTS_FILTER_LAYOUT'), JText::_('ESHOP_CONFIG_PRODUCTS_FILTER_LAYOUT_HELP')); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['products_filter_layout']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('theme', JText::_('ESHOP_CONFIG_CART_POPOUT'), JText::_('ESHOP_CONFIG_CART_POPOUT_HELP')); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['cart_popout']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('load_bootstrap_css', JText::_('ESHOP_CONFIG_LOAD_BOOTSTRAP_CSS')); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['load_bootstrap_css']; ?>
		</div>
	</div>
	<div class="control-group">
			<div class="control-label">
				<?php echo EshopHtmlHelper::getFieldLabel('twitter_bootstrap_version', JText::_('ESHOP_TWITTER_BOOTSTRAP_VERSION'), JText::_('ESHOP_TWITTER_BOOTSTRAP_VERSION_EXPLAIN')); ?>
			</div>
			<div class="controls">
				<?php echo $this->lists['twitter_bootstrap_version'];?>
			</div>
		</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('date_format', JText::_('ESHOP_CONFIG_DATE_FORMAT'), JText::_('ESHOP_CONFIG_DATE_FORMAT_HELP')); ?>
		</div>
		<div class="controls">
			<input class="input-large" type="text" name="date_format" id="date_format"  value="<?php echo isset($this->config->date_format) ? $this->config->date_format : 'm-d-Y'; ?>" />
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('load_bootstrap_js', JText::_('ESHOP_CONFIG_LOAD_BOOTSTRAP_JAVASCRIPT')); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['load_bootstrap_js']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('show_categories_nav', JText::_('ESHOP_CONFIG_SHOW_CATEGORIES_NAVIGATION')); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['show_categories_nav']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('show_products_nav', JText::_('ESHOP_CONFIG_SHOW_PRODUCTS_NAVIGATION')); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['show_products_nav']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('shipping_address_format', JText::_('ESHOP_CONFIG_SHIPPING_ADDRESS_FORMAT'), JText::_('ESHOP_CONFIG_SHIPPING_ADDRESS_FORMAT_HELP')); ?>
		</div>
		<div class="controls">
			<?php echo $editor->display( 'shipping_address_format', isset($this->config->shipping_address_format) ? $this->config->shipping_address_format : '[SHIPPING_FIRSTNAME] [SHIPPING_LASTNAME]<br /> [SHIPPING_ADDRESS_1], [SHIPPING_ADDRESS_2]<br /> [SHIPPING_CITY], [SHIPPING_POSTCODE] [SHIPPING_ZONE_NAME]<br /> [SHIPPING_EMAIL]<br /> [SHIPPING_TELEPHONE]<br /> [SHIPPING_FAX]', '100%', '250', '75', '10' ); ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('payment_address_format', JText::_('ESHOP_CONFIG_PAYMENT_ADDRESS_FORMAT'), JText::_('ESHOP_CONFIG_PAYMENT_ADDRESS_FORMAT_HELP')); ?>
		</div>
		<div class="controls">
			<?php echo $editor->display( 'payment_address_format', isset($this->config->payment_address_format) ? $this->config->payment_address_format : '[PAYMENT_FIRSTNAME] [PAYMENT_LASTNAME]<br /> [PAYMENT_ADDRESS_1], [PAYMENT_ADDRESS_2]<br /> [PAYMENT_CITY], [PAYMENT_POSTCODE] [PAYMENT_ZONE_NAME]<br /> [PAYMENT_EMAIL]<br /> [PAYMENT_TELEPHONE]<br /> [PAYMENT_FAX]', '100%', '250', '75', '10' ); ?>
		</div>
	</div>
</fieldset>
<div class="span6">
	<fieldset class="form-horizontal">
		<legend><?php echo JText::_('ESHOP_CONFIG_PRODUCT_PAGE'); ?></legend>	
		<div class="control-group">
			<div class="control-label">
				<?php echo EshopHtmlHelper::getFieldLabel('show_manufacturer', JText::_('ESHOP_CONFIG_SHOW_MANUFACTURER'), JText::_('ESHOP_CONFIG_SHOW_MANUFACTURER_HELP')); ?>
			</div>
			<div class="controls">
				<?php echo $this->lists['show_manufacturer']; ?>
			</div>
		</div>
		<div class="control-group">
			<div class="control-label">
				<?php echo EshopHtmlHelper::getFieldLabel('show_sku', JText::_('ESHOP_CONFIG_SHOW_SKU'), JText::_('ESHOP_CONFIG_SHOW_SKU_HELP')); ?>
			</div>
			<div class="controls">
				<?php echo $this->lists['show_sku']; ?>
			</div>
		</div>
		<div class="control-group">
			<div class="control-label">
				<?php echo EshopHtmlHelper::getFieldLabel('show_availability', JText::_('ESHOP_CONFIG_SHOW_AVAILABILITY'), JText::_('ESHOP_CONFIG_SHOW_AVAILABILITY_HELP')); ?>
			</div>
			<div class="controls">
				<?php echo $this->lists['show_availability']; ?>
			</div>
		</div>
		<div class="control-group">
			<div class="control-label">
				<?php echo EshopHtmlHelper::getFieldLabel('show_product_weight', JText::_('ESHOP_CONFIG_SHOW_PRODUCT_WEIGHT'), JText::_('ESHOP_CONFIG_SHOW_PRODUCT_WEIGHT_HELP')); ?>
			</div>
			<div class="controls">
				<?php echo $this->lists['show_product_weight']; ?>
			</div>
		</div>
		<div class="control-group">
			<div class="control-label">
				<?php echo EshopHtmlHelper::getFieldLabel('show_product_dimensions', JText::_('ESHOP_CONFIG_SHOW_PRODUCT_DIMENSIONS'), JText::_('ESHOP_CONFIG_SHOW_PRODUCT_DIMENSIONS_HELP')); ?>
			</div>
			<div class="controls">
				<?php echo $this->lists['show_product_dimensions']; ?>
			</div>
		</div>
		<div class="control-group">
			<div class="control-label">
				<?php echo EshopHtmlHelper::getFieldLabel('show_product_tags', JText::_('ESHOP_CONFIG_SHOW_PRODUCT_TAGS'), JText::_('ESHOP_CONFIG_SHOW_PRODUCT_TAGS_HELP')); ?>
			</div>
			<div class="controls">
				<?php echo $this->lists['show_product_tags']; ?>
			</div>
		</div>
		<div class="control-group">
			<div class="control-label">
				<?php echo EshopHtmlHelper::getFieldLabel('show_product_attachments', JText::_('ESHOP_CONFIG_SHOW_PRODUCT_ATTACHMENTS'), JText::_('ESHOP_CONFIG_SHOW_PRODUCT_ATTACHMENTS_HELP')); ?>
			</div>
			<div class="controls">
				<?php echo $this->lists['show_product_attachments']; ?>
			</div>
		</div>
		<div class="control-group">
			<div class="control-label">
				<?php echo EshopHtmlHelper::getFieldLabel('show_specification', JText::_('ESHOP_CONFIG_SHOW_SPECIFICATION'), JText::_('ESHOP_CONFIG_SHOW_SPECIFICATION_HELP')); ?>
			</div>
			<div class="controls">
				<?php echo $this->lists['show_specification']; ?>
			</div>
		</div>
		<div class="control-group">
			<div class="control-label">
				<?php echo EshopHtmlHelper::getFieldLabel('show_related_products', JText::_('ESHOP_CONFIG_SHOW_RELATED_PRODUCTS'), JText::_('ESHOP_CONFIG_SHOW_RELATED_PRODUCTS_HELP')); ?>
			</div>
			<div class="controls">
				<?php echo $this->lists['show_related_products']; ?>
			</div>
		</div>
		<div class="control-group">
			<div class="control-label">
				<?php echo EshopHtmlHelper::getFieldLabel('show_quantity_box_in_product_page', JText::_('ESHOP_CONFIG_SHOW_QUANTITY_BOX_IN_PRODUCT_PAGE'), JText::_('ESHOP_CONFIG_SHOW_QUANTITY_BOX_IN_PRODUCT_PAGE_HELP')); ?>
			</div>
			<div class="controls">
				<?php echo $this->lists['show_quantity_box_in_product_page']; ?>
			</div>
		</div>
	</fieldset>
	<fieldset class="form-horizontal">
		<legend><?php echo JText::_('ESHOP_CONFIG_CATEGORY_PAGE'); ?></legend>	
		<div class="control-group">
			<div class="control-label">
				<?php echo EshopHtmlHelper::getFieldLabel('show_category_image', JText::_('ESHOP_CONFIG_SHOW_CATEGORY_IMAGE'), JText::_('ESHOP_CONFIG_SHOW_CATEGORY_IMAGE_HELP')); ?>
			</div>
			<div class="controls">
				<?php echo $this->lists['show_category_image']; ?>
			</div>
		</div>
		<div class="control-group">
			<div class="control-label">
				<?php echo EshopHtmlHelper::getFieldLabel('show_category_desc', JText::_('ESHOP_CONFIG_SHOW_CATEGORY_DESC'), JText::_('ESHOP_CONFIG_SHOW_CATEGORY_DESC_HELP')); ?>
			</div>
			<div class="controls">
				<?php echo $this->lists['show_category_desc']; ?>
			</div>
		</div>
		<div class="control-group">
			<div class="control-label">
				<?php echo EshopHtmlHelper::getFieldLabel('show_products_in_all_levels', JText::_('ESHOP_CONFIG_SHOW_PRODUCTS_IN_ALL_LEVELS'), JText::_('ESHOP_CONFIG_SHOW_PRODUCTS_IN_ALL_LEVELS_HELP')); ?>
			</div>
			<div class="controls">
				<?php echo $this->lists['show_products_in_all_levels']; ?>
			</div>
		</div>
		<b><?php echo JText::_('ESHOP_CONFIG_CATEGORY_DEFAULT_LAYOUT'); ?></b>
		<div class="control-group">
			<div class="control-label">
				<?php echo EshopHtmlHelper::getFieldLabel('show_sub_categories', JText::_('ESHOP_CONFIG_SHOW_SUB_CATEGORIES'), JText::_('ESHOP_CONFIG_SHOW_SUB_CATEGORIES_HELP')); ?>
			</div>
			<div class="controls">
				<?php echo $this->lists['show_sub_categories']; ?>
			</div>
		</div>
		<div class="control-group">
			<div class="control-label">
				<?php echo EshopHtmlHelper::getFieldLabel('sub_categories_layout', JText::_('ESHOP_CONFIG_SUB_CATEGORIES_LAYOUT'), JText::_('ESHOP_CONFIG_SUB_CATEGORIES_LAYOUT_HELP')); ?>
			</div>
			<div class="controls">
				<?php echo $this->lists['sub_categories_layout']; ?>
			</div>
		</div>
		<div class="control-group">
			<div class="control-label">
				<?php echo EshopHtmlHelper::getFieldLabel('default_products_layout', JText::_('ESHOP_CONFIG_DEFAULT_PRODUCTS_LAYOUT'), JText::_('ESHOP_CONFIG_DEFAULT_PRODUCTS_LAYOUT_HELP')); ?>
			</div>
			<div class="controls">
				<?php echo $this->lists['default_products_layout']; ?>
			</div>
		</div>
		<div class="control-group">
			<div class="control-label">
				<?php echo EshopHtmlHelper::getFieldLabel('show_quantity_box', JText::_('ESHOP_CONFIG_SHOW_QUANTITY_BOX'), JText::_('ESHOP_CONFIG_SHOW_QUANTITY_BOX_HELP')); ?>
			</div>
			<div class="controls">
				<?php echo $this->lists['show_quantity_box']; ?>
			</div>
		</div>
		<b><?php echo JText::_('ESHOP_CONFIG_CATEGORY_TABLE_LAYOUT'); ?></b>
		<div class="control-group">
			<div class="control-label">
				<?php echo EshopHtmlHelper::getFieldLabel('table_show_image', JText::_('ESHOP_CONFIG_TABLE_SHOW_IMAGE'), JText::_('ESHOP_CONFIG_TABLE_SHOW_IMAGE_HELP')); ?>
			</div>
			<div class="controls">
				<?php echo $this->lists['table_show_image']; ?>
			</div>
		</div>
		<div class="control-group">
			<div class="control-label">
				<?php echo EshopHtmlHelper::getFieldLabel('table_show_short_description', JText::_('ESHOP_CONFIG_TABLE_SHOW_SHORT_DESCRIPTION'), JText::_('ESHOP_CONFIG_TABLE_SHOW_SHORT_DESCRIPTION_HELP')); ?>
			</div>
			<div class="controls">
				<?php echo $this->lists['table_show_short_description']; ?>
			</div>
		</div>
		<div class="control-group">
			<div class="control-label">
				<?php echo EshopHtmlHelper::getFieldLabel('table_show_category', JText::_('ESHOP_CONFIG_TABLE_SHOW_CATEGORY'), JText::_('ESHOP_CONFIG_TABLE_SHOW_CATEGORY_HELP')); ?>
			</div>
			<div class="controls">
				<?php echo $this->lists['table_show_category']; ?>
			</div>
		</div>
		<div class="control-group">
			<div class="control-label">
				<?php echo EshopHtmlHelper::getFieldLabel('table_show_manufacturer', JText::_('ESHOP_CONFIG_TABLE_SHOW_MANUFACTURER'), JText::_('ESHOP_CONFIG_TABLE_SHOW_MANUFACTURER_HELP')); ?>
			</div>
			<div class="controls">
				<?php echo $this->lists['table_show_manufacturer']; ?>
			</div>
		</div>
		<div class="control-group">
			<div class="control-label">
				<?php echo EshopHtmlHelper::getFieldLabel('table_show_price', JText::_('ESHOP_CONFIG_TABLE_SHOW_PRICE'), JText::_('ESHOP_CONFIG_TABLE_SHOW_PRICE_HELP')); ?>
			</div>
			<div class="controls">
				<?php echo $this->lists['table_show_price']; ?>
			</div>
		</div>
		<div class="control-group">
			<div class="control-label">
				<?php echo EshopHtmlHelper::getFieldLabel('table_show_availability', JText::_('ESHOP_CONFIG_TABLE_SHOW_AVAILABILITY'), JText::_('ESHOP_CONFIG_TABLE_SHOW_AVAILABILITY_HELP')); ?>
			</div>
			<div class="controls">
				<?php echo $this->lists['table_show_availability']; ?>
			</div>
		</div>
		<div class="control-group">
			<div class="control-label">
				<?php echo EshopHtmlHelper::getFieldLabel('table_show_quantity_box', JText::_('ESHOP_CONFIG_TABLE_SHOW_QUANTITY_BOX'), JText::_('ESHOP_CONFIG_TABLE_SHOW_QUANTITY_BOX_HELP')); ?>
			</div>
			<div class="controls">
				<?php echo $this->lists['table_show_quantity_box']; ?>
			</div>
		</div>
		<div class="control-group">
			<div class="control-label">
				<?php echo EshopHtmlHelper::getFieldLabel('table_show_actions', JText::_('ESHOP_CONFIG_TABLE_SHOW_ACTIONS'), JText::_('ESHOP_CONFIG_TABLE_SHOW_ACTIONS_HELP')); ?>
			</div>
			<div class="controls">
				<?php echo $this->lists['table_show_actions']; ?>
			</div>
		</div>
	</fieldset>
</div>
<div class="span6">	
	<fieldset class="form-horizontal">
		<legend><?php echo JText::_('ESHOP_CONFIG_COMPARE_PAGE'); ?></legend>	
		<div class="control-group">
			<div class="control-label">
				<?php echo EshopHtmlHelper::getFieldLabel('compare_image', JText::_('ESHOP_CONFIG_COMPARE_IMAGE')); ?>
			</div>
			<div class="controls">
				<?php echo $this->lists['compare_image']; ?>
			</div>
		</div>
		<div class="control-group">
			<div class="control-label">
				<?php echo EshopHtmlHelper::getFieldLabel('compare_price', JText::_('ESHOP_CONFIG_COMPARE_PRICE')); ?>
			</div>
			<div class="controls">
				<?php echo $this->lists['compare_price']; ?>
			</div>
		</div>
		<div class="control-group">
			<div class="control-label">
				<?php echo EshopHtmlHelper::getFieldLabel('compare_sku', JText::_('ESHOP_CONFIG_COMPARE_SKU')); ?>
			</div>
			<div class="controls">
				<?php echo $this->lists['compare_sku']; ?>
			</div>
		</div>
		<div class="control-group">
			<div class="control-label">
				<?php echo EshopHtmlHelper::getFieldLabel('compare_manufacturer', JText::_('ESHOP_CONFIG_COMPARE_MANUFACTURER')); ?>
			</div>
			<div class="controls">
				<?php echo $this->lists['compare_manufacturer']; ?>
			</div>
		</div>
		<div class="control-group">
			<div class="control-label">
				<?php echo EshopHtmlHelper::getFieldLabel('compare_availability', JText::_('ESHOP_CONFIG_COMPARE_AVAILABILITY')); ?>
			</div>
			<div class="controls">
				<?php echo $this->lists['compare_availability']; ?>
			</div>
		</div>
		<div class="control-group">
			<div class="control-label">
				<?php echo EshopHtmlHelper::getFieldLabel('compare_rating', JText::_('ESHOP_CONFIG_COMPARE_RATING')); ?>
			</div>
			<div class="controls">
				<?php echo $this->lists['compare_rating']; ?>
			</div>
		</div>
		<div class="control-group">
			<div class="control-label">
				<?php echo EshopHtmlHelper::getFieldLabel('compare_short_desc', JText::_('ESHOP_CONFIG_COMPARE_SHORT_DESC')); ?>
			</div>
			<div class="controls">
				<?php echo $this->lists['compare_short_desc']; ?>
			</div>
		</div>
		<div class="control-group">
			<div class="control-label">
				<?php echo EshopHtmlHelper::getFieldLabel('compare_desc', JText::_('ESHOP_CONFIG_COMPARE_DESC')); ?>
			</div>
			<div class="controls">
				<?php echo $this->lists['compare_desc']; ?>
			</div>
		</div>
		<div class="control-group">
			<div class="control-label">
				<?php echo EshopHtmlHelper::getFieldLabel('compare_weight', JText::_('ESHOP_CONFIG_COMPARE_WEIGHT')); ?>
			</div>
			<div class="controls">
				<?php echo $this->lists['compare_weight']; ?>
			</div>
		</div>
		<div class="control-group">
			<div class="control-label">
				<?php echo EshopHtmlHelper::getFieldLabel('compare_dimensions', JText::_('ESHOP_CONFIG_COMPARE_DIMENSIONS')); ?>
			</div>
			<div class="controls">
				<?php echo $this->lists['compare_dimensions']; ?>
			</div>
		</div>
		<div class="control-group">
			<div class="control-label">
				<?php echo EshopHtmlHelper::getFieldLabel('compare_attributes', JText::_('ESHOP_CONFIG_COMPARE_ATTRIBUTES')); ?>
			</div>
			<div class="controls">
				<?php echo $this->lists['compare_attributes']; ?>
			</div>
		</div>
	</fieldset>
	<fieldset class="form-horizontal">
		<legend><?php echo JText::_('ESHOP_CONFIG_CUSTOMER_PAGE'); ?></legend>	
		<div class="control-group">
			<div class="control-label">
				<?php echo EshopHtmlHelper::getFieldLabel('customer_manage_account', JText::_('ESHOP_CONFIG_CUSTOMER_MANAGE_ACCOUNT')); ?>
			</div>
			<div class="controls">
				<?php echo $this->lists['customer_manage_account']; ?>
			</div>
		</div>
		<div class="control-group">
			<div class="control-label">
				<?php echo EshopHtmlHelper::getFieldLabel('customer_manage_order', JText::_('ESHOP_CONFIG_CUSTOMER_MANAGE_ORDER')); ?>
			</div>
			<div class="controls">
				<?php echo $this->lists['customer_manage_order']; ?>
			</div>
		</div>
		<div class="control-group">
			<div class="control-label">
				<?php echo EshopHtmlHelper::getFieldLabel('customer_manage_download', JText::_('ESHOP_CONFIG_CUSTOMER_MANAGE_DOWNLOAD')); ?>
			</div>
			<div class="controls">
				<?php echo $this->lists['customer_manage_download']; ?>
			</div>
		</div>
		<div class="control-group">
			<div class="control-label">
				<?php echo EshopHtmlHelper::getFieldLabel('customer_manage_address', JText::_('ESHOP_CONFIG_CUSTOMER_MANAGE_ADDRESS')); ?>
			</div>
			<div class="controls">
				<?php echo $this->lists['customer_manage_address']; ?>
			</div>
		</div>
	</fieldset>
</div>	
