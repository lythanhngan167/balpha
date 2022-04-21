<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$fielddata = $data->fielddata;
// TODO :
?>
<?php $class = $fielddata->css_class;?>
<div class="control-group">
	<div class="">
	<textarea 		
		placeholder="<?php echo JText::_('COM_JOOMPROFILE_ADDRESS_LINE');?>"
		<?php echo ($fielddata->mapping->required  == true) ? ' required="required" data-validation-required-message="'.JText::_('COM_JOOMPROFILE_VALIDATION_REQUIRED').'"' : ''; ?>		
		class="<?php echo $class;?>"
		name="joomprofile-field[<?php echo $fielddata->id;?>][line]"
		id="joomprofile-field-<?php echo $fielddata->id;?>-line"><?php echo @$data->value->line;?></textarea>
		<p class="help-block"> </p>
	</div>
</div>

<div class="control-group">
	<div class="">
	<input 
		type="text"	
		placeholder="<?php echo JText::_('COM_JOOMPROFILE_CITY');?>"
		<?php echo ($fielddata->mapping->required  == true) ? ' required="required" data-validation-required-message="'.JText::_('COM_JOOMPROFILE_VALIDATION_REQUIRED').'"' : ''; ?>
		value="<?php echo @$data->value->city;?>"
		class="<?php echo $class;?>"
		name="joomprofile-field[<?php echo $fielddata->id;?>][city]"
		id="joomprofile-field-<?php echo $fielddata->id;?>-city"
		/>
		<p class="help-block"> </p>
	</div>
</div>

<div class="control-group">
	<div class="">
	<input 
		type="text"	
		placeholder="<?php echo JText::_('COM_JOOMPROFILE_ZIPCODE');?>"
		<?php echo ($fielddata->mapping->required  == true) ? ' required="required" data-validation-required-message="'.JText::_('COM_JOOMPROFILE_VALIDATION_REQUIRED').'"' : ''; ?>
		value="<?php echo @$data->value->zipcode;?>"
		name="joomprofile-field[<?php echo $fielddata->id;?>][zipcode]"
		id="joomprofile-field-<?php echo $fielddata->id;?>-zipcode"
		/>
		<p class="help-block"> </p>
	</div>
</div>

<div class="control-group">
	<div class="">
	<input 
		type="text"	
		placeholder="<?php echo JText::_('COM_JOOMPROFILE_STATE');?>"
		<?php echo ($fielddata->mapping->required  == true) ? ' required="required" data-validation-required-message="'.JText::_('COM_JOOMPROFILE_VALIDATION_REQUIRED').'"' : ''; ?>
		value="<?php echo @$data->value->state;?>"
		name="joomprofile-field[<?php echo $fielddata->id;?>][state]"
		id="joomprofile-field-<?php echo $fielddata->id;?>-state"
		/>
		<p class="help-block"> </p>
	</div>
</div>

<div class="control-group">
	<div class="">
	<input 
		type="text"	
		placeholder="<?php echo JText::_('COM_JOOMPROFILE_COUNTRY');?>"
		<?php echo ($fielddata->mapping->required  == true) ? ' required="required" data-validation-required-message="'.JText::_('COM_JOOMPROFILE_VALIDATION_REQUIRED').'"' : ''; ?>
		value="<?php echo @$data->value->country;?>"
		name="joomprofile-field[<?php echo $fielddata->id;?>][country]"
		id="joomprofile-field-<?php echo $fielddata->id;?>-country"
		/>
		<p class="help-block"> </p>
	</div>
</div>
<?php 
