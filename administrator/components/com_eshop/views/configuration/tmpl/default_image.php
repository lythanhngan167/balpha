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

<div class="control-group">
	<div class="control-label">
		<?php echo EshopHtmlHelper::getFieldLabel('view_image', JText::_('ESHOP_CONFIG_VIEW_IMAGE'), JText::_('ESHOP_CONFIG_VIEW_IMAGE_HELP')); ?>
	</div>
	<div class="controls">
		<?php echo $this->lists['view_image']; ?>
	</div>
</div>
<fieldset class="form-horizontal">
	<legend><?php echo JText::_('ESHOP_CONFIG_IMAGE_SIZE_FUNCTION'); ?></legend>
	<div class="control-group"><?php echo JText::_('ESHOP_CONFIG_IMAGE_SIZE_FUNCTION_HELP'); ?></div>
	<div class="control-group">
		<div class="control-label">
			<span class="required">*</span><?php echo  JText::_('ESHOP_CONFIG_CATEGORY_IMAGE_SIZE'); ?>
		</div>
		<div class="controls">
			<input type="text" value="<?php echo $this->config->image_category_width; ?>" name="image_category_width" class="input-mini" />
				x
			<input type="text" value="<?php echo $this->config->image_category_height; ?>" name="image_category_height" class="input-mini" />
			<?php echo $this->lists['category_image_size_function']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<span class="required">*</span><?php echo  JText::_('ESHOP_CONFIG_MANUFACTURER_IMAGE_SIZE'); ?>
		</div>
		<div class="controls">
			<input type="text" value="<?php echo $this->config->image_manufacturer_width; ?>" name="image_manufacturer_width" class="input-mini" />
				x
			<input type="text" value="<?php echo $this->config->image_manufacturer_height; ?>" name="image_manufacturer_height" class="input-mini" />
			<?php echo $this->lists['manufacturer_image_size_function']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<span class="required">*</span><?php echo  JText::_('ESHOP_CONFIG_PRODUCT_IMAGE_THUMB_SIZE'); ?>
		</div>
		<div class="controls">
			<input type="text" value="<?php echo $this->config->image_thumb_width; ?>" name="image_thumb_width" class="input-mini" />
				x
			<input type="text" value="<?php echo $this->config->image_thumb_height; ?>" name="image_thumb_height" class="input-mini" />
			<?php echo $this->lists['thumb_image_size_function']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<span class="required">*</span><?php echo  JText::_('ESHOP_CONFIG_PRODUCT_IMAGE_POPUP_SIZE'); ?>
		</div>
		<div class="controls">
			<input type="text" value="<?php echo $this->config->image_popup_width; ?>" name="image_popup_width" class="input-mini" />
				x
			<input type="text" value="<?php echo $this->config->image_popup_height; ?>" name="image_popup_height" class="input-mini" />
			<?php echo $this->lists['popup_image_size_function']; ?>
		</div>
	</div>	
	<div class="control-group">
		<div class="control-label">
			<span class="required">*</span><?php echo  JText::_('ESHOP_CONFIG_PRODUCT_IMAGE_LIST_SIZE'); ?>
		</div>
		<div class="controls">
			<input type="text" value="<?php echo $this->config->image_list_width; ?>" name="image_list_width" class="input-mini" />
				x
			<input type="text" value="<?php echo $this->config->image_list_height; ?>" name="image_list_height" class="input-mini" />
			<?php echo $this->lists['list_image_size_function']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<span class="required">*</span><?php echo  JText::_('ESHOP_CONFIG_ADDITIONAL_PRODUCT_IMAGE_SIZE'); ?>
		</div>
		<div class="controls">
			<input type="text" value="<?php echo $this->config->image_additional_width; ?>" name="image_additional_width" class="input-mini" />
				x
			<input type="text" value="<?php echo $this->config->image_additional_height; ?>" name="image_additional_height" class="input-mini" />
			<?php echo $this->lists['additional_image_size_function']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<span class="required">*</span><?php echo  JText::_('ESHOP_CONFIG_RELATED_PRODUCT_IMAGE_SIZE'); ?>
		</div>
		<div class="controls">
			<input type="text" value="<?php echo $this->config->image_related_width; ?>" name="image_related_width" class="input-mini" />
				x
			<input type="text" value="<?php echo $this->config->image_related_height; ?>" name="image_related_height" class="input-mini" />
			<?php echo $this->lists['related_image_size_function']; ?>
		</div>
	</div>	
	<div class="control-group">
		<div class="control-label">
			<span class="required">*</span><?php echo  JText::_('ESHOP_CONFIG_COMPARE_IMAGE_SIZE'); ?>
		</div>
		<div class="controls">
			<input type="text" value="<?php echo $this->config->image_compare_width; ?>" name="image_compare_width" class="input-mini" />
				x
			<input type="text" value="<?php echo $this->config->image_compare_height; ?>" name="image_compare_height" class="input-mini" />
			<?php echo $this->lists['compare_image_size_function']; ?>
		</div>
	</div>	
	<div class="control-group">
		<div class="control-label">
			<span class="required">*</span><?php echo  JText::_('ESHOP_CONFIG_WISH_LIST_IMAGE_SIZE'); ?>
		</div>
		<div class="controls">
			<input type="text" value="<?php echo $this->config->image_wishlist_width; ?>" name="image_wishlist_width" class="input-mini" />
				x
			<input type="text" value="<?php echo $this->config->image_wishlist_height; ?>" name="image_wishlist_height" class="input-mini" />
			<?php echo $this->lists['wishlist_image_size_function']; ?>
		</div>
	</div>	
	<div class="control-group">
		<div class="control-label">
			<span class="required">*</span><?php echo  JText::_('ESHOP_CONFIG_CART_IMAGE_SIZE'); ?>
		</div>
		<div class="controls">
			<input type="text" value="<?php echo $this->config->image_cart_width; ?>" name="image_cart_width" class="input-mini" />
				x
			<input type="text" value="<?php echo $this->config->image_cart_height; ?>" name="image_cart_height" class="input-mini" />
			<?php echo $this->lists['cart_image_size_function']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<span class="required">*</span><?php echo  JText::_('ESHOP_CONFIG_LABEL_IMAGE_SIZE'); ?>
		</div>
		<div class="controls">
			<input type="text" value="<?php echo $this->config->image_label_width; ?>" name="image_label_width" class="input-mini" />
				x
			<input type="text" value="<?php echo $this->config->image_label_height; ?>" name="image_label_height" class="input-mini" />
			<?php echo $this->lists['label_image_size_function']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<span class="required">*</span><?php echo  JText::_('ESHOP_CONFIG_OPTION_IMAGE_SIZE'); ?>
		</div>
		<div class="controls">
			<input type="text" value="<?php echo $this->config->image_option_width; ?>" name="image_option_width" class="input-mini" />
				x
			<input type="text" value="<?php echo $this->config->image_option_height; ?>" name="image_option_height" class="input-mini" />
			<?php echo $this->lists['option_image_size_function']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('recreate_watermark_images', JText::_('ESHOP_RECREATE_WATERMARK_IMAGES'), JText::_('ESHOP_RECREATE_WATERMARK_IMAGES_HELP')); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['recreate_watermark_images']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('product_use_image_watermarks', JText::_('ESHOP_PRODUCT_USE_WATERMARKS')); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['product_use_image_watermarks']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('category_use_image_watermarks', JText::_('ESHOP_CATEGORY_USE_WATERMARKS')); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['category_use_image_watermarks']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('manufacture_use_image_watermarks', JText::_('ESHOP_MANUFACTURE_USE_WATERMARKS')); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['manufacture_use_image_watermarks']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('images_opacity', JText::_('ESHOP_CONFIG_WATERMARK_IMAGES_OPACITY')); ?>
		</div>
		<div class="controls">
			<input type="text" value="<?php echo $this->config->images_opacity; ?>" name="images_opacity" id="images_opacity" class="input-mini" /> %
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('watermark_position', JText::_('ESHOP_CONFIG_WATERMARK_POSITION')); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['watermark_position']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('watermark_type', JText::_('ESHOP_CONFIG_WATERMARK_TYPE')); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['watermark_type']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('watermark_font', JText::_('ESHOP_CONFIG_WATERMARK_FONT')); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['watermark_font']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('watermark_fontsize', JText::_('ESHOP_CONFIG_WATERMARK_FONT_SIZE')); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['watermark_fontsize']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('watermark_color', JText::_('ESHOP_CONFIG_WATERMARK_COLOR')); ?>
		</div>
		<div class="controls">
			<?php echo $this->lists['watermark_color']; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('custom_text', JText::_('ESHOP_CONFIG_WATERMARK_CUSTOM_TEXT')); ?>
		</div>
		<div class="controls">
			<input type="text" value="<?php echo $this->config->custom_text; ?>" id="custom_text" name="custom_text" class="text-area-order" />
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<?php echo EshopHtmlHelper::getFieldLabel('watermark_photo_file', JText::_('ESHOP_CONFIG_WATERMARK_PHOTO')); ?>
		</div>
		<div class="controls">
			<?php
			if (isset($this->config->watermark_photo) && $this->config->watermark_photo != "")
			{
				if (file_exists(JPATH_ROOT . "/images/" . $this->config->watermark_photo))
				{
					?>
					<img src="<?php echo JURI::root(); ?>images/<?php echo $this->config->watermark_photo; ?>" />
					<?php
				}
				?>
				<div style="clear:both;"></div>
				<input type="hidden" name="watermark_photo" id="watermark_photo" value="<?php echo $this->config->watermark_photo; ?>" />
				<?php
			}
			?>
			<input type="file" name="watermark_photo_file" id="watermark_photo_file" />
		</div>
	</div>
</fieldset>