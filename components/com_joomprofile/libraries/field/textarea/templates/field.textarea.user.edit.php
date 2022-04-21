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
<?php $value = $data->value;?>
<?php if(trim($data->value) == '' && isset($fielddata->params['default_value']) && !empty($fielddata->params['default_value'])): ?>
	<?php $value = $fielddata->params['default_value'];?>
<?php endif;?>
	
<textarea
	<?php if(isset($fielddata->params['placeholder']) && !empty($fielddata->params['placeholder'])): ?>
		placeholder="<?php echo JText::_($fielddata->params['placeholder']);?>"
	<?php endif;?>
	
	<?php echo ($fielddata->mapping->required  == true) ? ' required="required" data-validation-required-message="'.JText::_('COM_JOOMPROFILE_VALIDATION_REQUIRED').'"' : ''; ?>
	
	<?php if(isset($fielddata->params['max_length']) && !empty($fielddata->params['max_length'])): ?>
		maxLength="<?php echo $fielddata->params['max_length'];?>"
		data-validation-maxlength-message="<?php echo JText::sprintf('COM_JOOMPROFILE_VALIDATION_TOO_LONG', $fielddata->params['max_length']);?>"
	<?php endif;?>
	
	<?php if(isset($fielddata->params['min_length']) && !empty($fielddata->params['min_length'])): ?>
		minlength="<?php echo $fielddata->params['min_length'];?>"
		data-validation-minlength-message="<?php echo JText::sprintf('COM_JOOMPROFILE_VALIDATION_TOO_SHORT', $fielddata->params['min_length']);?>"
	<?php endif;?>

	<?php if(isset($fielddata->params['pattern']) && !empty($fielddata->params['pattern'])): ?>
		pattern="<?php echo $fielddata->params['pattern'];?>"
		data-validation-pattern-message="<?php echo !empty($fielddata->params['pattern_message']) ? JText::_($fielddata->params['pattern_message']) : JText::_('COM_JOOMPROFILE_VALIDATION_NOT_IN_EXPECTED_FORMAT');?>"
	<?php endif;?>

	class="<?php echo $class;?>"
	name="joomprofile-field[<?php echo $fielddata->id;?>]"
	id="joomprofile-field-<?php echo $fielddata->id;?>"
	><?php echo $value;?></textarea>
<?php 
