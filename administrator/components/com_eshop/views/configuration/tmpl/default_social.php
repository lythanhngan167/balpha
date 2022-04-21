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
		<?php echo EshopHtmlHelper::getFieldLabel('social_enable', JText::_('ESHOP_CONFIG_SOCIAL_ENABLE'), JText::_('ESHOP_CONFIG_SOCIAL_ENABLE_DESC')); ?>
	</div>
	<div class="controls">
		<?php echo $this->lists['social_enable']; ?>
	</div>
</div>
<div class="span6">
	<fieldset class="form-horizontal">
		<legend><?php echo JText::_('ESHOP_CONFIG_SOCIAL_FACEBOOK'); ?></legend>
		<div class="control-group">
			<div class="control-label">
				<?php echo EshopHtmlHelper::getFieldLabel('app_id', JText::_('ESHOP_CONFIG_SOCIAL_FACEBOOK_APPLICATION_ID'), JText::_('ESHOP_CONFIG_SOCIAL_FACEBOOK_APPLICATION_ID_DESC')); ?>
			</div>
			<div class="controls">
				<input class="input-large" type="text" name="app_id" id="app_id"  value="<?php echo $this->config->app_id; ?>" />
			</div>
		</div>
		<div class="control-group">
			<div class="control-label">
				<?php echo EshopHtmlHelper::getFieldLabel('button_font', JText::_('ESHOP_CONFIG_SOCIAL_BUTTON_FONT'), JText::_('ESHOP_CONFIG_SOCIAL_BUTTON_FONT_DESC')); ?>
			</div>
			<div class="controls">
				<?php echo $this->lists['button_font']; ?>
			</div>
		</div>
		<div class="control-group">
			<div class="control-label">
				<?php echo EshopHtmlHelper::getFieldLabel('button_theme', JText::_('ESHOP_CONFIG_SOCIAL_BUTTON_THEME'), JText::_('ESHOP_CONFIG_SOCIAL_BUTTON_THEME_DESC')); ?>
			</div>
			<div class="controls">
				<?php echo $this->lists['button_theme']; ?>
			</div>
		</div>
		<div class="control-group">
			<div class="control-label">
				<?php echo EshopHtmlHelper::getFieldLabel('button_language', JText::_('ESHOP_CONFIG_SOCIAL_BUTTON_LANGUAGE'), JText::_('ESHOP_CONFIG_SOCIAL_BUTTON_LANGUAGE_DESC')); ?>
			</div>
			<div class="controls">
				<?php echo $this->lists['button_language']; ?>
			</div>
		</div>
		<div class="control-group">
			<div class="control-label">
				<?php echo EshopHtmlHelper::getFieldLabel('show_facebook_button', JText::_('ESHOP_CONFIG_SOCIAL_SHOW_FACEBOOK_BUTTON'), JText::_('ESHOP_CONFIG_SOCIAL_SHOW_FACEBOOK_BUTTON_DESC')); ?>
			</div>
			<div class="controls">
				<?php echo $this->lists['show_facebook_button']; ?>
			</div>
		</div>
		<div class="control-group">
			<div class="control-label">
				<?php echo EshopHtmlHelper::getFieldLabel('button_layout', JText::_('ESHOP_CONFIG_SOCIAL_LIKE_BUTTON_LAYOUT'), JText::_('ESHOP_CONFIG_SOCIAL_LIKE_BUTTON_LAYOUT_DESC')); ?>
			</div>
			<div class="controls">
				<?php echo $this->lists['button_layout']; ?>
			</div>
		</div>
		<div class="control-group">
			<div class="control-label">
				<?php echo EshopHtmlHelper::getFieldLabel('show_faces', JText::_('ESHOP_CONFIG_SOCIAL_SHOW_FACES'), JText::_('ESHOP_CONFIG_SOCIAL_SHOW_FACES_DESC')); ?>
			</div>
			<div class="controls">
				<?php echo $this->lists['show_faces']; ?>
			</div>
		</div>
		<div class="control-group">
			<div class="control-label">
				<?php echo EshopHtmlHelper::getFieldLabel('button_width', JText::_('ESHOP_CONFIG_SOCIAL_BUTTON_WIDTH'), JText::_('ESHOP_CONFIG_SOCIAL_BUTTON_WIDTH_DESC')); ?>
			</div>
			<div class="controls">
				<input class="input-mini" type="text" name="button_width" id="button_width"  value="<?php echo $this->config->button_width; ?>" />
			</div>
		</div>
		<div class="control-group">
			<div class="control-label">
				<?php echo EshopHtmlHelper::getFieldLabel('show_facebook_comment', JText::_('ESHOP_CONFIG_SOCIAL_SHOW_FACEBOOK_COMMENT'), JText::_('ESHOP_CONFIG_SOCIAL_SHOW_FACEBOOK_COMMENT_DESC')); ?>
			</div>
			<div class="controls">
				<?php echo $this->lists['show_facebook_comment']; ?>
			</div>
		</div>
		<div class="control-group">
			<div class="control-label">
				<?php echo EshopHtmlHelper::getFieldLabel('num_posts', JText::_('ESHOP_CONFIG_SOCIAL_NUMBER_OF_POSTS'), JText::_('ESHOP_CONFIG_SOCIAL_NUMBER_OF_POSTS_DESC')); ?>
			</div>
			<div class="controls">
				<input class="input-mini" type="text" name="num_posts" id="num_posts"  value="<?php echo $this->config->num_posts; ?>" />
			</div>
		</div>
		<div class="control-group">
			<div class="control-label">
				<?php echo EshopHtmlHelper::getFieldLabel('comment_width', JText::_('ESHOP_CONFIG_SOCIAL_COMMENT_WIDTH'), JText::_('ESHOP_CONFIG_SOCIAL_COMMENT_WIDTH_DESC')); ?>
			</div>
			<div class="controls">
				<input class="input-mini" type="text" name="comment_width" id="comment_width"  value="<?php echo $this->config->comment_width; ?>" />
			</div>
		</div>
	</fieldset>
</div>
<div class="span6">	
	<fieldset class="form-horizontal">
		<legend><?php echo JText::_('ESHOP_CONFIG_SOCIAL_TWITTER'); ?></legend>	
		<div class="control-group">
			<div class="control-label">
				<?php echo EshopHtmlHelper::getFieldLabel('show_twitter_button', JText::_('ESHOP_CONFIG_SOCIAL_SHOW_TWITTER_BUTTON'), JText::_('ESHOP_CONFIG_SOCIAL_SHOW_TWITTER_BUTTON_DESC')); ?>
			</div>
			<div class="controls">
				<?php echo $this->lists['show_twitter_button']; ?>
			</div>
		</div>
	</fieldset>
	<fieldset class="form-horizontal">
		<legend><?php echo JText::_('ESHOP_CONFIG_SOCIAL_PINTEREST'); ?></legend>	
		<div class="control-group">
			<div class="control-label">
				<?php echo EshopHtmlHelper::getFieldLabel('show_pinit_button', JText::_('ESHOP_CONFIG_SOCIAL_SHOW_PINIT_BUTTON'), JText::_('ESHOP_CONFIG_SOCIAL_SHOW_PINIT_BUTTON_DESC')); ?>
			</div>
			<div class="controls">
				<?php echo $this->lists['show_pinit_button']; ?>
			</div>
		</div>
	</fieldset>
	<fieldset class="form-horizontal">
		<legend><?php echo JText::_('ESHOP_CONFIG_SOCIAL_GOOGLE'); ?></legend>	
		<div class="control-group">
			<div class="control-label">
				<?php echo EshopHtmlHelper::getFieldLabel('show_google_button', JText::_('ESHOP_CONFIG_SOCIAL_SHOW_GOOGLE_BUTTON'), JText::_('ESHOP_CONFIG_SOCIAL_SHOW_GOOGLE_BUTTON_DESC')); ?>
			</div>
			<div class="controls">
				<?php echo $this->lists['show_google_button']; ?>
			</div>
		</div>
	</fieldset>
	<fieldset class="form-horizontal">
		<legend><?php echo JText::_('ESHOP_CONFIG_SOCIAL_LINKEDIN'); ?></legend>
		<div class="control-group">
			<div class="control-label">
				<?php echo EshopHtmlHelper::getFieldLabel('show_linkedin_button', JText::_('ESHOP_CONFIG_SOCIAL_SHOW_LINKEDIN_BUTTON'), JText::_('ESHOP_CONFIG_SOCIAL_SHOW_LINKEDIN_BUTTON_DESC')); ?>
			</div>
			<div class="controls">
				<?php echo $this->lists['show_linkedin_button']; ?>
			</div>
		</div>
		<div class="control-group">
			<div class="control-label">
				<?php echo EshopHtmlHelper::getFieldLabel('linkedin_layout', JText::_('ESHOP_CONFIG_SOCIAL_LINKEDIN_LAYOUT'), JText::_('ESHOP_CONFIG_SOCIAL_LINKEDIN_LAYOUT_DESC')); ?>
			</div>
			<div class="controls">
				<?php echo $this->lists['linkedin_layout']; ?>
			</div>
		</div>
	</fieldset>
</div>	