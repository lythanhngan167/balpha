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
	type="text"
	<?php if(isset($fielddata->params['placeholder']) && !empty($fielddata->params['placeholder'])): ?>
		placeholder="<?php echo JText::_($fielddata->params['placeholder']);?>"
	<?php endif;?>
	
	<?php echo ($fielddata->mapping->required  == true) ? ' required="required" data-validation-required-message="'.JText::_('COM_JOOMPROFILE_VALIDATION_REQUIRED').'"' : ''; ?>
	
	<?php if(isset($fielddata->params['size']) && !empty($fielddata->params['size'])): ?>
		size="<?php echo $fielddata->params['size'];?>"
	<?php endif;?>
	
	<?php $value = $data->value;?>
	<?php if(trim($data->value) == '' && isset($fielddata->params['default_value']) && !empty($fielddata->params['default_value'])): ?>
		<?php $value = $fielddata->params['default_value'];?>
	<?php endif;?>
	value="<?php echo $value;?>"

	data-validation-regex-regex="(http|ftp|https)://[\w-]+(\.[\w-]+)+([\w.,@?^=%&amp;:/~+#-]*[\w@?^=%&amp;/~+#-])?" 
    data-validation-regex-message="<?php echo JText::_('COM_JOOMPROFILE_VALIDATION_URL');?>"	
	
	class="<?php echo $class;?>"
	name="joomprofile-field[<?php echo $fielddata->id;?>]"
	id="joomprofile-field-<?php echo $fielddata->id;?>"
	/>
<?php 
