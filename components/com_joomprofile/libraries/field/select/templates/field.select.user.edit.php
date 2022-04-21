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
<select 
	class="<?php echo $class;?>"
	<?php echo ($fielddata->mapping->required  == true) ? ' required="required" data-validation-required-message="'.JText::_('COM_JOOMPROFILE_VALIDATION_REQUIRED').'"' : ''; ?>
	name="joomprofile-field[<?php echo $fielddata->id;?>]<?php echo !empty($fielddata->params['multiselect']) ? '[]' : '';?>"
	id="joomprofile-field-<?php echo $fielddata->id;?>"
	<?php echo !empty($fielddata->params['multiselect']) ? 'multiple="true"' : '';?>
	>
	<?php if(!empty($fielddata->params['select_option_text'])) :?> 
		<option value=""><?php echo JText::_($fielddata->params['select_option_text']);?></option>
	<?php endif;?>
	<?php foreach ($fielddata->options as $option) : ?>
		<option value="<?php echo $option->id;?>" <?php echo in_array($option->id, $data->value) ? 'selected="selected"' : '';?>><?php echo JText::_($option->title);?></option>
	<?php endforeach;?>	
</select>
<?php 
