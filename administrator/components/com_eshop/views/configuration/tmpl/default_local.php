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
        <?php echo EshopHtmlHelper::getFieldLabel('ghtk_api_key', JText::_('ESHOP_CONFIG_API_GHTK_KEY')); ?>
    </div>
    <div class="controls">
        <input type="text" name="ghtk_api_key" id="ghtk_api_key" size="15" maxlength="128" value="<?php echo isset($this->config->ghtk_api_key) ? $this->config->ghtk_api_key : ''; ?>" />
    </div>
</div>
<div class="control-group">
	<div class="control-label">
		<?php echo EshopHtmlHelper::getFieldLabel('country_id', JText::_('ESHOP_CONFIG_COUNTRY')); ?>
	</div>
	<div class="controls">
		<?php echo $this->lists['country_id']; ?>
	</div>
</div>
<div class="control-group">
	<div class="control-label">
		<?php echo EshopHtmlHelper::getFieldLabel('zone_id', JText::_('ESHOP_CONFIG_REGION_STATE')); ?>
	</div>
	<div class="controls">
		<?php echo $this->lists['zone_id']; ?>
	</div>
</div>
<div class="control-group">
	<div class="control-label">
		<?php echo EshopHtmlHelper::getFieldLabel('postcode', JText::_('ESHOP_CONFIG_POSTCODE')); ?>
	</div>
	<div class="controls">
		<input class="text_area" type="text" name="postcode" id="postcode" size="15" maxlength="128" value="<?php echo isset($this->config->postcode) ? $this->config->postcode : ''; ?>" />
	</div>
</div>
<div class="control-group">
	<div class="control-label">
		<?php echo EshopHtmlHelper::getFieldLabel('default_currency_code', JText::_('ESHOP_CONFIG_DEFAULT_CURRENCY')); ?>
	</div>
	<div class="controls">
		<?php echo $this->lists['default_currency_code']; ?>
	</div>
</div>
<div class="control-group">
	<div class="control-label">
		<?php echo EshopHtmlHelper::getFieldLabel('auto_update_currency', JText::_('ESHOP_CONFIG_AUTO_UPDATE_CURRENCY')); ?>
	</div>
	<div class="controls">
		<?php echo $this->lists['auto_update_currency']; ?>
	</div>
</div>
<div class="control-group">
	<div class="control-label">
		<?php echo EshopHtmlHelper::getFieldLabel('show_eshop_update', JText::_('ESHOP_SHOW_ESHOP_UPDATE'), JText::_('ESHOP_SHOW_ESHOP_UPDATE_HELP')); ?>
	</div>
	<div class="controls">
		<?php echo $this->lists['show_eshop_update']; ?>
	</div>
</div>
<div class="control-group">
	<div class="control-label">
		<?php echo EshopHtmlHelper::getFieldLabel('length_id', JText::_('ESHOP_CONFIG_LENGTH')); ?>
	</div>
	<div class="controls">
		<?php echo $this->lists['length_id']; ?>
	</div>
</div>
<div class="control-group">
	<div class="control-label">
		<?php echo EshopHtmlHelper::getFieldLabel('weight_id', JText::_('ESHOP_CONFIG_WEIGHT')); ?>
	</div>
	<div class="controls">
		<?php echo $this->lists['weight_id']; ?>
	</div>
</div>
