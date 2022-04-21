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
?>
<fieldset class="form-horizontal">
	<legend><?php echo JText::_('ESHOP_CONFIG_ITEMS'); ?></legend>
	<div class="control-group">
		<div class="control-label">
			<span class="required">*</span><?php echo JText::_('ESHOP_CONFIG_DEFAULT_ITEMS_PER_PAGE'); ?><br />
			<span class="help"><?php echo JText::_('ESHOP_CONFIG_DEFAULT_ITEMS_PER_PAGE_HELP'); ?></span>
		</div>
		<div class="controls">
			<input class="input-mini" type="text" name="catalog_limit" id="catalog_limit"  value="<?php echo isset($this->config->catalog_limit) ? $this->config->catalog_limit : ''; ?>" />
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<span class="required">*</span><?php echo JText::_('ESHOP_CONFIG_DEFAULT_ITEMS_PER_ROW'); ?><br />
			<span class="help"><?php echo JText::_('ESHOP_CONFIG_DEFAULT_ITEMS_PER_ROW_HELP'); ?></span>
		</div>
		<div class="controls">
			<input class="input-mini" type="text" name="items_per_row" id="items_per_row"  value="<?php echo isset($this->config->items_per_row) ? $this->config->items_per_row : ''; ?>" />
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('start_quantity_number', JText::_('ESHOP_CONFIG_START_QUANTITY_NUMBER'), JText::_('ESHOP_CONFIG_START_QUANTITY_NUMBER_HELP')); ?>
		</div>
		<div class="controls">
			<input class="input-mini" type="text" name="start_quantity_number" id="start_quantity_number"  value="<?php echo isset($this->config->start_quantity_number) ? $this->config->start_quantity_number : '1'; ?>" />
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('quantity_step', JText::_('ESHOP_CONFIG_QUANTITY_STEP'), JText::_('ESHOP_CONFIG_QUANTITY_STEP_HELP')); ?>
		</div>
		<div class="controls">
			<input class="input-mini" type="text" name="quantity_step" id="quantity_step"  value="<?php echo isset($this->config->quantity_step) ? $this->config->quantity_step : '1'; ?>" />
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('catalog_mode', JText::_('ESHOP_CONFIG_CATALOG_MODE'), JText::_('ESHOP_CONFIG_CATALOG_MODE_HELP')); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['catalog_mode']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('quote_cart_mode', JText::_('ESHOP_CONFIG_QUOTE_CART_MODE'), JText::_('ESHOP_CONFIG_QUOTE_CART_MODE_HELP')); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['quote_cart_mode']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('add_category_path', JText::_('ESHOP_CONFIG_ADD_CATEGORY_PATH'), JText::_('ESHOP_CONFIG_ADD_CATEGORY_PATH_HELP')); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['add_category_path']; ?>
		</div>
	</div>
	<?php
	if (version_compare(JVERSION, '3.0', 'ge') && JLanguageMultilang::isEnabled() && count(EshopHelper::getLanguages()) > 1)
	{
		$languages = $this->languages;
		for ($i = 0; $i < count($languages); $i++)
		{
			
			?>
			<div class="control-group">
				<div class="control-label">
					<?php echo EshopHtmlHelper::getFieldLabel('', JText::_('ESHOP_CONFIG_DEFAULT_MENU_ITEM') . ' (' . $languages[$i]->title . ')', JText::_('ESHOP_CONFIG_DEFAULT_MENU_ITEM_HELP') . ' (' . $languages[$i]->title . ')'); ?>
				</div>
				<div class="controls">
					<?php echo $this->lists['default_menu_item_'.$languages[$i]->lang_code]; ?>
				</div>
			</div>
			<?php
		}
	}
	else 
	{
		?>
		<div class="control-group">
			<div class="control-label">
				<?php echo EshopHtmlHelper::getFieldLabel('default_menu_item', JText::_('ESHOP_CONFIG_DEFAULT_MENU_ITEM'), JText::_('ESHOP_CONFIG_DEFAULT_MENU_ITEM_HELP')); ?>
			</div>
			<div class="controls">
				<?php echo $this->lists['default_menu_item']; ?>
			</div>
		</div>
		<?php
	}
	?>
</fieldset>
<fieldset class="form-horizontal">
	<legend><?php echo JText::_('ESHOP_CONFIG_PRODUCTS'); ?></legend>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('product_count', JText::_('ESHOP_CONFIG_CATEGORY_PRODUCT_COUNT'), JText::_('ESHOP_CONFIG_CATEGORY_PRODUCT_COUNT_HELP')); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['product_count']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('rich_snippets', JText::_('ESHOP_CONFIG_RICH_SNIPPETS'), JText::_('ESHOP_CONFIG_RICH_SNIPPETS_HELP')); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['rich_snippets']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('allow_reviews', JText::_('ESHOP_CONFIG_ALLOW_REVIEWS'), JText::_('ESHOP_CONFIG_ALLOW_REVIEWS_HELP')); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['allow_reviews']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('enable_reviews_captcha', JText::_('ESHOP_CONFIG_ENABLE_REVIEWS_CAPTCHA'), JText::_('ESHOP_CONFIG_ENABLE_REVIEWS_CAPTCHA_HELP')); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['enable_reviews_captcha']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('enable_register_account_captcha', JText::_('ESHOP_CONFIG_ENABLE_REGISTER_ACCOUNT_CAPTCHA'), JText::_('ESHOP_CONFIG_ENABLE_REGISTER_ACCOUNT_CAPTCHA_HELP')); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['enable_register_account_captcha']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('enable_checkout_captcha', JText::_('ESHOP_CONFIG_ENABLE_CHECKOUT_CAPTCHA'), JText::_('ESHOP_CONFIG_ENABLE_CHECKOUT_CAPTCHA_HELP')); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['enable_checkout_captcha']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('enable_quote_captcha', JText::_('ESHOP_CONFIG_ENABLE_QUOTE_CAPTCHA'), JText::_('ESHOP_CONFIG_ENABLE_QUOTE_CAPTCHA_HELP')); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['enable_quote_captcha']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('allow_notify', JText::_('ESHOP_CONFIG_ALLOW_NOTIFY'), JText::_('ESHOP_CONFIG_ALLOW_NOTIFY_HELP')); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['allow_notify']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('allow_wishlist', JText::_('ESHOP_CONFIG_ALLOW_WISHLIST'), JText::_('ESHOP_CONFIG_ALLOW_WISHLIST_HELP')); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['allow_wishlist']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('allow_compare', JText::_('ESHOP_CONFIG_ALLOW_COMPARE'), JText::_('ESHOP_CONFIG_ALLOW_COMPARE_HELP')); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['allow_compare']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('allow_ask_question', JText::_('ESHOP_CONFIG_ALLOW_ASK_QUESTION'), JText::_('ESHOP_CONFIG_ALLOW_ASK_QUESTION_HELP')); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['allow_ask_question']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('allow_email_to_a_friend', JText::_('ESHOP_CONFIG_ALLOW_EMAIL_TO_A_FRIEND'), JText::_('ESHOP_CONFIG_ALLOW_EMAIL_TO_A_FRIEND_HELP')); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['allow_email_to_a_friend']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('allow_download_pdf_product', JText::_('ESHOP_CONFIG_ALLOW_DOWNLOAD_PDF_PRODUCT'), JText::_('ESHOP_CONFIG_ALLOW_DOWNLOAD_PDF_PRODUCT_HELP')); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['allow_download_pdf_product']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('dynamic_price', JText::_('ESHOP_CONFIG_DYNAMIC_PRICE'), JText::_('ESHOP_CONFIG_DYNAMIC_PRICE_HELP')); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['dynamic_price']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('hide_out_of_stock_products', JText::_('ESHOP_CONFIG_HIDE_OUT_OF_STOCK_PRODUCTS'), JText::_('ESHOP_CONFIG_HIDE_OUT_OF_STOCK_PRODUCTS_HELP')); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['hide_out_of_stock_products']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('display_price', JText::_('ESHOP_CONFIG_DISPLAY_PRICE'), JText::_('ESHOP_CONFIG_DISPLAY_PRICE_HELP')); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['display_price']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('display_option_price', JText::_('ESHOP_CONFIG_DISPLAY_OPTION_PRICE'), JText::_('ESHOP_CONFIG_DISPLAY_OPTION_PRICE_HELP')); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['display_option_price']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('product_custom_fields', JText::_('ESHOP_CONFIG_PRODUCT_CUSTOM_FIELDS'), JText::_('ESHOP_CONFIG_PRODUCT_CUSTOM_FIELDS_HELP')); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['product_custom_fields']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('assign_same_options', JText::_('ESHOP_CONFIG_ASSIGN_SAME_OPTIONS'), JText::_('ESHOP_CONFIG_ASSIGN_SAME_OPTIONS_HELP')); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['assign_same_options']; ?>
		</div>
	</div>
</fieldset>
<fieldset class="form-horizontal">
	<legend><?php echo JText::_('ESHOP_CONFIG_TAXES'); ?></legend>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('tax', JText::_('ESHOP_CONFIG_TAX_CLASS')); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['tax_class']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('tax', JText::_('ESHOP_CONFIG_ENABLE_TAX'), JText::_('ESHOP_CONFIG_ENABLE_TAX_HELP')); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['tax']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('display_ex_tax', JText::_('ESHOP_CONFIG_DISPLAY_EX_TAX'), JText::_('ESHOP_CONFIG_DISPLAY_EX_TAX_HELP')); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['display_ex_tax']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('include_tax_anywhere', JText::_('ESHOP_CONFIG_INCLUDE_TAX_ANYWHERE'), JText::_('ESHOP_CONFIG_INCLUDE_TAX_ANYWHERE_HELP')); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['include_tax_anywhere']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('enable_eu_vat_rules', JText::_('ESHOP_CONFIG_ENABLE_EU_VAT_RULES')); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['enable_eu_vat_rules']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('eu_vat_rules_based_on', JText::_('ESHOP_CONFIG_EU_VAT_RULES_BASED_ON')); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['eu_vat_rules_based_on']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('tax_default', JText::_('ESHOP_CONFIG_USE_STORE_TAX_ADDRESS'), JText::_('ESHOP_CONFIG_USE_STORE_TAX_ADDRESS_HELP')); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['tax_default']; ?>
		</div>
	</div>
</fieldset>
<fieldset class="form-horizontal">
	<legend><?php echo JText::_('ESHOP_CONFIG_ACCOUNT'); ?></legend>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('customergroup_id', JText::_('ESHOP_CONFIG_CUSTOMER_GROUP'), JText::_('ESHOP_CONFIG_CUSTOMER_GROUP_HELP')); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['customergroup_id']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('customer_group_display', JText::_('ESHOP_CONFIG_CUSTOMER_GROUPS'), JText::_('ESHOP_CONFIG_CUSTOMER_GROUPS_HELP')); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['customer_group_display']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('account_terms', JText::_('ESHOP_CONFIG_ACCOUNT_TERMS'), JText::_('ESHOP_CONFIG_ACCOUNT_TERMS_HELP')); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['account_terms']; ?>
		</div>
	</div>
</fieldset>
<fieldset class="form-horizontal">
	<legend><?php echo JText::_('ESHOP_CONFIG_PRIVACY_POLICY'); ?></legend>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('show_privacy_policy_checkbox', JText::_('ESHOP_CONFIG_SHOW_PRIVACY_POLICY_CHECKBOX'), JText::_('ESHOP_CONFIG_SHOW_PRIVACY_POLICY_CHECKBOX_HELP')); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['show_privacy_policy_checkbox']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('privacy_policy_article', JText::_('ESHOP_CONFIG_PRIVACY_POLICY_ARTICLE'), JText::_('ESHOP_CONFIG_PRIVACY_POLICY_ARTICLE_HELP')); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['privacy_policy_article']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('acymailing_integration', JText::_('ESHOP_CONFIG_ACYMAILING_INTEGRATION'), JText::_('ESHOP_CONFIG_ACYMAILING_INTEGRATION_HELP')); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['acymailing_integration']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('mailchimp_integration', JText::_('ESHOP_CONFIG_MAILCHIMP_INTEGRATION'), JText::_('ESHOP_CONFIG_MAILCHIMP_INTEGRATION_HELP')); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['mailchimp_integration']; ?>
		</div>
	</div>
</fieldset>
<fieldset class="form-horizontal">
	<legend><?php echo JText::_('ESHOP_CONFIG_CHECKOUT'); ?></legend>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('start_order_id', JText::_('ESHOP_CONFIG_START_ORDER_ID'), JText::_('ESHOP_CONFIG_START_ORDER_ID_HELP')); ?>
		</div>
		<div class="controls">
			<input class="text_area" type="text" name="start_order_id" id="start_order_id" size="3" value="<?php echo isset($this->config->start_order_id) ? $this->config->start_order_id : '0'; ?>" />
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('min_sub_total', JText::_('ESHOP_CONFIG_MIN_SUB_TOTAL'), JText::_('ESHOP_CONFIG_MIN_SUB_TOTAL_HELP')); ?>
		</div>
		<div class="controls">
			<input class="text_area" type="text" name="min_sub_total" id="min_sub_total" size="3" value="<?php echo isset($this->config->min_sub_total) ? $this->config->min_sub_total : ''; ?>" />
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('min_quantity', JText::_('ESHOP_CONFIG_MIN_QUANTITY'), JText::_('ESHOP_CONFIG_MIN_QUANTITY_HELP')); ?>
		</div>
		<div class="controls">
			<input class="text_area" type="text" name="min_quantity" id="min_quantity" size="3" value="<?php echo isset($this->config->min_quantity) ? $this->config->min_quantity : '0'; ?>" />
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('store_cart', JText::_('ESHOP_CONFIG_STORE_CART'), JText::_('ESHOP_CONFIG_STORE_CART_HELP')); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['store_cart']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('only_free_shipping', JText::_('ESHOP_CONFIG_ONLY_FREE_SHIPPING'), JText::_('ESHOP_CONFIG_ONLY_FREE_SHIPPING_HELP')); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['only_free_shipping']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('one_add_to_cart_button', JText::_('ESHOP_CONFIG_ONE_ADD_TO_CART_BUTTON'), JText::_('ESHOP_CONFIG_ONE_ADD_TO_CART_BUTTON_HELP')); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['one_add_to_cart_button']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('active_https', JText::_('ESHOP_CONFIG_ACTIVE_HTTPS'), JText::_('ESHOP_CONFIG_ACTIVE_HTTPS_HELP')); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['active_https']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('allow_re_order', JText::_('ESHOP_CONFIG_ALLOW_RE_ORDER'), JText::_('ESHOP_CONFIG_ALLOW_RE_ORDER_HELP')); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['allow_re_order']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('allow_coupon', JText::_('ESHOP_CONFIG_ALLOW_COUPON'), JText::_('ESHOP_CONFIG_ALLOW_COUPON_HELP')); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['allow_coupon']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('allow_voucher', JText::_('ESHOP_CONFIG_ALLOW_VOUCHER'), JText::_('ESHOP_CONFIG_ALLOW_VOUCHER_HELP')); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['allow_voucher']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('cart_weight', JText::_('ESHOP_CONFIG_DISPLAY_WEIGHT_ON_CART_PAGE'), JText::_('ESHOP_CONFIG_DISPLAY_WEIGHT_ON_CART_PAGE_HELP')); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['cart_weight']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('require_shipping', JText::_('ESHOP_CONFIG_REQUIRE_SHIPPING'), JText::_('ESHOP_CONFIG_REQUIRE_SHIPPING_HELP')); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['require_shipping']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('require_shipping_address', JText::_('ESHOP_CONFIG_REQUIRE_SHIPPING_ADDRESS'), JText::_('ESHOP_CONFIG_REQUIRE_SHIPPING_ADDRESS_HELP')); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['require_shipping_address']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('shipping_estimate', JText::_('ESHOP_CONFIG_SHIPPING_ESTIMATE'), JText::_('ESHOP_CONFIG_SHIPPING_ESTIMATE_HELP')); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['shipping_estimate']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('checkout_type', JText::_('ESHOP_CONFIG_CHECKOUT_TYPE'), JText::_('ESHOP_CONFIG_CHECKOUT_TYPE_HELP')); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['checkout_type']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('checkout_terms', JText::_('ESHOP_CONFIG_CHECKOUT_TERMS'), JText::_('ESHOP_CONFIG_CHECKOUT_TERMS_HELP')); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['checkout_terms']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('order_edit', JText::_('ESHOP_CONFIG_ORDER_EDITING'), JText::_('ESHOP_CONFIG_ORDER_EDITING_HELP')); ?>
		</div>
		<div class="controls">
			<input class="text_area" type="text" name="order_edit" id="order_edit" size="3" value="<?php echo isset($this->config->order_edit) ? $this->config->order_edit : ''; ?>" />
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('order_status_id', JText::_('ESHOP_CONFIG_ORDER_STATUS'), JText::_('ESHOP_CONFIG_ORDER_STATUS_HELP')); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['order_status_id']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('complete_status_id', JText::_('ESHOP_CONFIG_COMPLETE_ORDER_STATUS'), JText::_('ESHOP_CONFIG_COMPLETE_ORDER_STATUS_HELP')); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['complete_status_id']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('canceled_status_id', JText::_('ESHOP_CONFIG_CANCELED_ORDER_STATUS'), JText::_('ESHOP_CONFIG_CANCELED_ORDER_STATUS_HELP')); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['canceled_status_id']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('delivery_date', JText::_('ESHOP_CONFIG_DELIVERY_DATE'), JText::_('ESHOP_CONFIG_DELIVERY_DATE_HELP')); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['delivery_date']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('completed_url', JText::_('ESHOP_CONFIG_COMPLETED_URL'), JText::_('ESHOP_CONFIG_COMPLETED_URL_HELP')); ?>
		</div>
		<div class="controls">
			<input class="input-xxlarge" type="text" name="completed_url" id="completed_url"  value="<?php echo isset($this->config->completed_url) ? $this->config->completed_url : ''; ?>" />
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('continue_shopping_url', JText::_('ESHOP_CONFIG_CONTINUE_SHOPPING_URL'), JText::_('ESHOP_CONFIG_CONTINUE_SHOPPING_URL_HELP')); ?>
		</div>
		<div class="controls">
			<input class="input-xxlarge" type="text" name="continue_shopping_url" id="continue_shopping_url"  value="<?php echo isset($this->config->continue_shopping_url) ? $this->config->continue_shopping_url : ''; ?>" />
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('idevaffiliate_integration', JText::_('ESHOP_CONFIG_IDEVAFFILIATE_INTEGRATION'), JText::_('ESHOP_CONFIG_IDEVAFFILIATE_INTEGRATION_HELP')); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['idevaffiliate_integration']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('idevaffiliate_path', JText::_('ESHOP_CONFIG_IDEVAFFILIATE_PATH'), JText::_('ESHOP_CONFIG_IDEVAFFILIATE_PATH_HELP')); ?>
		</div>
		<div class="controls">
			<input class="input-xxlarge" type="text" name="idevaffiliate_path" id="idevaffiliate_path"  value="<?php echo isset($this->config->idevaffiliate_path) ? $this->config->idevaffiliate_path : ''; ?>" />
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('acymailing_integration', JText::_('ESHOP_CONFIG_ACYMAILING_INTEGRATION'), JText::_('ESHOP_CONFIG_ACYMAILING_INTEGRATION_HELP')); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['acymailing_integration']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('mailchimp_integration', JText::_('ESHOP_CONFIG_MAILCHIMP_INTEGRATION'), JText::_('ESHOP_CONFIG_MAILCHIMP_INTEGRATION_HELP')); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['mailchimp_integration']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('conversion_tracking_code', JText::_('ESHOP_CONFIG_CONVERSION_TRACKING_CODE'), JText::_('ESHOP_CONFIG_CONVERSION_TRACKING_CODE_HELP')); ?>
		</div>
		<div class="controls">
			<textarea name="conversion_tracking_code" id="donate_amounts" rows="5" cols="50"><?php echo isset($this->config->conversion_tracking_code) ? $this->config->conversion_tracking_code : ''; ?></textarea>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('api_key_mailchimp', JText::_('ESHOP_CONFIG_API_KEY_MAILCHIMP'), JText::_('ESHOP_CONFIG_API_KEY_MAILCHIMP_HELP')); ?>
		</div>
		<div class="controls">
			<input class="input-xlarge" type="text" name="api_key_mailchimp" id="api_key_mailchimp" size="3" value="<?php echo isset($this->config->api_key_mailchimp) ? $this->config->api_key_mailchimp : ''; ?>" />
		</div>
	</div>
</fieldset>
<fieldset class="form-horizontal">
	<legend><?php echo JText::_('ESHOP_CONFIG_STOCK'); ?></legend>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('stock_display', JText::_('ESHOP_CONFIG_DISPLAY_STOCK'), JText::_('ESHOP_CONFIG_DISPLAY_STOCK_HELP')); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['stock_display']; ?>
		</div>
	</div>	
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('stock_warning', JText::_('ESHOP_CONFIG_STOCK_WARNING'), JText::_('ESHOP_CONFIG_STOCK_WARNING_HELP')); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['stock_warning']; ?>
		</div>
	</div>	
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('stock_checkout', JText::_('ESHOP_CONFIG_STOCK_CHECKOUT'), JText::_('ESHOP_CONFIG_STOCK_CHECKOUT_HELP')); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['stock_checkout']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('stock_status_id', JText::_('ESHOP_CONFIG_STOCK_STATUS'), JText::_('ESHOP_CONFIG_STOCK_STATUS_HELP')); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['stock_status_id']; ?>
		</div>
	</div>
</fieldset>
<fieldset class="form-horizontal">
	<legend><?php echo JText::_('ESHOP_CONFIG_FILE'); ?></legend>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('file_extensions_allowed', JText::_('ESHOP_CONFIG_FILE_EXTENSIONS_ALLOWED'), JText::_('ESHOP_CONFIG_FILE_EXTENSIONS_ALLOWED_HELP')); ?>
		</div>
		<div class="controls">
			<textarea name="file_extensions_allowed" id="file_extensions_allowed" rows="5" cols="50"><?php echo isset($this->config->file_extensions_allowed) ? $this->config->file_extensions_allowed : ''; ?></textarea>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('file_mime_types_allowed', JText::_('ESHOP_CONFIG_FILE_MIME_TYPES_ALLOWED'), JText::_('ESHOP_CONFIG_FILE_MIME_TYPES_ALLOWED_HELP')); ?>
		</div>
		<div class="controls">
			<textarea name="file_mime_types_allowed" id="file_mime_types_allowed" rows="5" cols="50"><?php echo isset($this->config->file_mime_types_allowed) ? $this->config->file_mime_types_allowed : ''; ?></textarea>
		</div>
	</div>
</fieldset>
<fieldset class="form-horizontal">
	<legend><?php echo JText::_('ESHOP_CONFIG_CHECKOUT_DONATE'); ?></legend>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('enable_checkout_donate', JText::_('ESHOP_CONFIG_CHECKOUT_DONATE_ENABLE')); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['enable_checkout_donate']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('donate_amounts', JText::_('ESHOP_CONFIG_CHECKOUT_DONATE_AMOUNTS'), JText::_('ESHOP_CONFIG_CHECKOUT_DONATE_AMOUNTS_HELP')); ?>
		</div>
		<div class="controls">
			<textarea name="donate_amounts" id="donate_amounts" rows="5" cols="50"><?php echo isset($this->config->donate_amounts) ? $this->config->donate_amounts : ''; ?></textarea>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('donate_explanations', JText::_('ESHOP_CONFIG_CHECKOUT_DONATE_EXPLANATIONS'), JText::_('ESHOP_CONFIG_CHECKOUT_DONATE_EXPLANATIONS_HELP')); ?>
		</div>
		<div class="controls">
			<textarea name="donate_explanations" id="donate_explanations" rows="5" cols="50"><?php echo isset($this->config->donate_explanations) ? $this->config->donate_explanations : ''; ?></textarea>
		</div>
	</div>
</fieldset>
<fieldset class="form-horizontal">
	<legend><?php echo JText::_('ESHOP_CONFIG_CHECKOUT_DISCOUNT'); ?></legend>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('enable_checkout_discount', JText::_('ESHOP_CONFIG_CHECKOUT_DISCOUNT_ENABLE')); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['enable_checkout_discount']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('checkout_discount_type', JText::_('ESHOP_CHECKOUT_DISCOUNT_TYPE')); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['checkout_discount_type']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('total_range', JText::_('ESHOP_CONFIG_CHECKOUT_DISCOUNT_TOTAL_RANGE')); ?>
		</div>
		<div class="controls">
			<input class="input-xxlarge" type="text" name="total_range" id="total_range"  value="<?php echo isset($this->config->total_range) ? $this->config->total_range : ''; ?>" />
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('quantity_range', JText::_('ESHOP_CONFIG_CHECKOUT_DISCOUNT_QUANTITY_RANGE')); ?>
		</div>
		<div class="controls">
			<input class="input-xxlarge" type="text" name="quantity_range" id="quantity_range"  value="<?php echo isset($this->config->quantity_range) ? $this->config->quantity_range : ''; ?>" />
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('discount_range', JText::_('ESHOP_CONFIG_CHECKOUT_DISCOUNT_DISCOUNT_RANGE')); ?>
		</div>
		<div class="controls">
			<input class="input-xxlarge" type="text" name="discount_range" id="discount_range"  value="<?php echo isset($this->config->discount_range) ? $this->config->discount_range : ''; ?>" />
		</div>
	</div>
</fieldset>
<div class="control-group">
	<span class="help"><?php echo JText::_('ESHOP_CONFIG_CHECKOUT_DISCOUNT_HELP'); ?></span>
</div>
<fieldset class="form-horizontal">
	<legend><?php echo JText::_('ESHOP_CONFIG_GOOGLE_ANALYTICS'); ?></legend>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('ga_tracking_id', JText::_('ESHOP_CONFIG_GOOGLE_ANALYTICS_TRACKING_ID'), JText::_('ESHOP_CONFIG_GOOGLE_ANALYTICS_TRACKING_ID_HELP')); ?>
		</div>
		<div class="controls">
			<input class="input-large" type="text" name="ga_tracking_id" id="ga_tracking_id" value="<?php echo isset($this->config->ga_tracking_id) ? $this->config->ga_tracking_id : ''; ?>" />
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('ga_js_type', JText::_('ESHOP_CONFIG_GOOGLE_ANALYTICS_TRACKING_TYPE'), JText::_('ESHOP_CONFIG_GOOGLE_ANALYTICS_TRACKING_TYPE_HELP')); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['ga_js_type']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('variation_type', JText::_('ESHOP_CONFIG_GOOGLE_ANALYTICS_VARIATION_TYPE'), JText::_('ESHOP_CONFIG_GOOGLE_ANALYTICS_VARIATION_TYPE_HELP')); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['variation_type']; ?>
		</div>
	</div>
</fieldset>