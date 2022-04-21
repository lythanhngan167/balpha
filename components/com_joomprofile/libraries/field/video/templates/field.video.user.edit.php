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
<input 
	type="URL"
	<?php echo (empty($data->value) && $fielddata->mapping->required  == true) ? ' required="required" data-validation-required-message="'.JText::_('COM_JOOMPROFILE_VALIDATION_REQUIRED').'"' : ''; ?>
	class="<?php echo $class;?>"	
	name="joomprofile-field[<?php echo $fielddata->id;?>]"
	id="joomprofile-field-<?php echo $fielddata->id;?>"
	data-validation-ajax-ajax="<?php echo JUri::base();?>index.php?option=com_joomprofile&view=profile&task=field.validate&format=json&id=<?php echo $fielddata->id;?>&user_id=<?php echo $data->user_id;?>"/>
<?php 
