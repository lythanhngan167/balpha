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
//$editor = JFactory::getEditor();
// TODO :
?>
<?php $class = $fielddata->css_class;?>
	<textarea 
		<?php echo ($fielddata->mapping->required  == true) ? ' required="required" data-validation-required-message="'.JText::_('COM_JOOMPROFILE_VALIDATION_REQUIRED').'"' : ''; ?>		
		class="<?php echo $class;?>"
		name="joomprofile-field[<?php echo $fielddata->id;?>][0]"
		id="joomprofile-field-<?php echo $fielddata->id;?>-content"><?php echo @$data->value[0];?></textarea>
		
	<input 
		type="hidden"	
		<?php echo ($fielddata->mapping->required  == true) ? ' required="required" data-validation-required-message="'.JText::_('COM_JOOMPROFILE_VALIDATION_REQUIRED').'"' : ''; ?>
		value="<?php echo isset($data->value) && !empty($data->value[1]) ? $data->value[1] : 0;?>"
		class="<?php echo $class;?>"
		name="joomprofile-field[<?php echo $fielddata->id;?>][1]"
		id="joomprofile-field-<?php echo $fielddata->id;?>-id"
		/>		
<?php 
