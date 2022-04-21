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
<?php $class = 'checkbox '.$fielddata->css_class;?>

<?php foreach ($fielddata->options as $option) : ?>
		<label class="<?php echo $class;?>">
		<input 
			type="radio"
			<?php echo ($fielddata->mapping->required == true) ? 'data-validation-minchecked-minchecked="1" data-validation-minchecked-message="'.JText::_('COM_JOOMPROFILE_VALIDATION_CHECKONE').'"' : '';?> 
			name="joomprofile-field[<?php echo $fielddata->id;?>]"
			id="joomprofile-field-<?php echo $fielddata->id;?>"
			value="<?php echo $option->id;?>"
			<?php echo in_array($option->id, $data->value) ? 'checked="checked"' : '';?>/>
			<?php echo ' '. JText::_($option->title);?>
		</label>
	<?php endforeach;?>
<?php 
