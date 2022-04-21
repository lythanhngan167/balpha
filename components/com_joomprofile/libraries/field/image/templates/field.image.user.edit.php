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
<script>
// TODO : move to common script
jQuery("#joomprofile-field-<?php echo $fielddata->id;?>").live('change',function(){
	if (this.files && this.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
        	jQuery('#joomprofile-field-<?php echo $fielddata->id;?>-img').attr('src', e.target.result);
        }

        reader.readAsDataURL(this.files[0]);
        return true;
    }
});
</script>
<?php $class = $fielddata->css_class;?>
<?php $src = !empty($data->value) ? $data->value : $fielddata->params['default_image'];?>
<div class="">
	<img 
		id="joomprofile-field-<?php echo $fielddata->id;?>-img" 
		src="<?php if (strpos($src, "http://") === 0 || strpos($src, "https://") === 0) 
		              echo $src;
		           else
	                  echo JUri::root().$src;?>" 
		class="img-polaroid" 
		style="	width: <?php echo $fielddata->params['default_width'];?>px;
				height: <?php echo $fielddata->params['default_height'];?>px;"/>
</div>
<div class="">
<input 
	type="file"
	<?php echo (empty($data->value) && $fielddata->mapping->required  == true) ? ' required="required" data-validation-required-message="'.JText::_('COM_JOOMPROFILE_VALIDATION_REQUIRED').'"' : ''; ?>
	class="<?php echo $class;?>"	
	name="joomprofile-field[<?php echo $fielddata->id;?>]"
	id="joomprofile-field-<?php echo $fielddata->id;?>"
	data-validation-ajax-ajax="<?php echo JUri::base();?>index.php?option=com_joomprofile&view=profile&task=field.validate&format=json&id=<?php echo $fielddata->id;?>&user_id=<?php echo $data->user_id;?>"/>
</div>
<?php 
