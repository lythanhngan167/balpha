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
<?php if($fielddata->params['dom_type'] == 'selectbox' && 
		isset($fielddata->params['min_value']) && !empty($fielddata->params['min_value']) && 
		isset($fielddata->params['min_value']) && !empty($fielddata->params['min_value'])) :?>
			
			<?php if(trim($data->value) == '' && isset($fielddata->params['default_value']) && !empty($fielddata->params['default_value'])): ?>
				<?php $value = $fielddata->params['default_value'];?>
			<?php endif;?>
			<?php $min = $fielddata->params['min_value'];?>
			<?php $max = $fielddata->params['max_value'];?>
			<?php if($min > $max): ?>
				<?php $temp = $min; ?>
				<?php $min  = $max; ?>
				<?php $max  = $temp;?>
			<?php endif;?>			
		
	<select 
		class="<?php echo $class;?>"
		<?php echo ($fielddata->mapping->required  == true) ? ' required="required" data-validation-required-message="'.JText::_('COM_JOOMPROFILE_VALIDATION_REQUIRED').'"' : ''; ?>
		name="joomprofile-field[<?php echo $fielddata->id;?>]"
		id="joomprofile-field-<?php echo $fielddata->id;?>"
		>
		<option value=""><?php echo JText::_('COM_JOOMPROFILE_SELECT_OPTION');?></option>
		<?php for(; $min <= $max; $min++):?>
			<option value="<?php echo $min;?>" <?php echo ($min == $data->value) ? 'selected="selected"' : '';?>><?php echo $min;?></option>
		<?php endfor;?>	
	</select>
<?php else:?>
	<input 
		type="number"
		data-validation-number-message="<?php echo JText::_('COM_JOOMPROFILE_VALIDATION_NUMBER');?>"
		<?php if(isset($fielddata->params['placeholder']) && !empty($fielddata->params['placeholder'])): ?>
			placeholder="<?php echo JText::_($fielddata->params['placeholder']);?>"
		<?php endif;?>
		
		<?php echo ($fielddata->mapping->required  == true) ? ' required="required" data-validation-required-message="'.JText::_('COM_JOOMPROFILE_VALIDATION_REQUIRED').'"' : ''; ?>
		
		<?php $value = $data->value;?>
		<?php if(trim($data->value) == '' && isset($fielddata->params['default_value']) && !empty($fielddata->params['default_value'])): ?>
			<?php $value = $fielddata->params['default_value'];?>
		<?php endif;?>
		value="<?php echo $value;?>"
		
		<?php if(isset($fielddata->params['max_length']) && !empty($fielddata->params['max_length'])): ?>
			maxLength="<?php echo $fielddata->params['max_length'];?>"
			data-validation-maxlength-message="<?php echo JText::sprintf('COM_JOOMPROFILE_VALIDATION_TOO_LONG', $fielddata->params['max_length']);?>"
		<?php endif;?>
		
		<?php if(isset($fielddata->params['min_length']) && !empty($fielddata->params['min_length'])): ?>
			minlength="<?php echo $fielddata->params['min_length'];?>"
			data-validation-minlength-message="<?php echo JText::sprintf('COM_JOOMPROFILE_VALIDATION_TOO_SHORT', $fielddata->params['min_length']);?>"
		<?php endif;?>
		
		<?php if(isset($fielddata->params['min_value']) && !empty($fielddata->params['min_value'])): ?>
			min="<?php echo $fielddata->params['min_value'];?>"
			data-validation-min-message="<?php echo JText::sprintf('COM_JOOMPROFILE_VALIDATION_TOO_LOW', $fielddata->params['min_value']);?>"
		<?php endif;?>
		
		<?php if(isset($fielddata->params['max_value']) && !empty($fielddata->params['max_value'])): ?>
			max="<?php echo $fielddata->params['max_value'];?>"
			data-validation-max-message="<?php echo JText::sprintf('COM_JOOMPROFILE_VALIDATION_TOO_HIGH', $fielddata->params['max_value']);?>"
		<?php endif;?>
		
	
		class="<?php echo $class;?>"
		name="joomprofile-field[<?php echo $fielddata->id;?>]"
		id="joomprofile-field-<?php echo $fielddata->id;?>"
		/>
	<?php endif;?>
<?php 
