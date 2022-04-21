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
?>
<?php $class = $fielddata->css_class;?>
<input 
	type="email"
	data-validation-email-message="<?php echo JText::_('COM_JOOMPROFILE_VALIDATION_VALIDEMAIL');?>"
	<?php if(isset($fielddata->params['placeholder']) && !empty($fielddata->params['placeholder'])): ?>
		placeholder="<?php echo JText::_($fielddata->params['placeholder']);?>"
	<?php endif;?>
	
	<?php echo ($fielddata->mapping->required  == true) ? ' required="required" data-validation-required-message="'.JText::_('COM_JOOMPROFILE_VALIDATION_REQUIRED').'"' : ''; ?>
	<?php $value = $data->value;?>
	<?php if(trim($value) == '' && isset($fielddata->params['default_value']) && !empty($fielddata->params['default_value'])): ?>
		<?php $value = $fielddata->params['default_value'];?>
	<?php endif;?>
	value="<?php echo $value;?>"

	<?php if(isset($fielddata->params['pattern']) && !empty($fielddata->params['pattern'])): ?>
		pattern="<?php echo $fielddata->params['pattern'];?>"
		data-validation-pattern-message="<?php echo !empty($fielddata->params['pattern_message']) ? JText::_($fielddata->params['pattern_message']) : JText::_('COM_JOOMPROFILE_VALIDATION_NOT_IN_EXPECTED_FORMAT');?>"
	<?php endif;?>

	class="<?php echo $class;?>"
	name="joomprofile-field[<?php echo $fielddata->id;?>]"
	id="joomprofile-field-<?php echo $fielddata->id;?>"
	/>
<?php 
