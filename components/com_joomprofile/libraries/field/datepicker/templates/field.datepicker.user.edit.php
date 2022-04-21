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
if(!function_exists('joomprofiler_datepicker_loaded')){
	function joomprofiler_datepicker_loaded(){
		static $dp_flag = false;
		if($dp_flag == false){
			?>
			<script type="text/javascript" src="<?php echo JHtml::script("com_joomprofile/datepicker.js", false, true, true);?>"></script>
			<link rel="stylesheet" type="text/css" href="<?php echo JHtml::stylesheet("com_joomprofile/datepicker.css", array(), true, true);?>" />
			<?php
			$dp_flag = true;
		}
	}
}
joomprofiler_datepicker_loaded();
?>
<script>
jQuery(document).ready(function(){
jQuery('#joomprofile-dp-<?php echo $fielddata->id;?> .input-append.date').datepicker({
	container: '.jp-wrap'
});
});
// MooTools
if(window.addEvent !== undefined){
	window.addEvent('domready',function() {

	    Element.prototype.hide = function() {
	        // Do nothing
	    };
	});
}
</script>
<?php $class = $fielddata->css_class;?>
<div id="joomprofile-dp-<?php echo $fielddata->id;?>" class="joomprofile-datepicker">
 <div class="input-append date"
	data-date-format="<?php echo isset($fielddata->params["format"]) ? $fielddata->params["format"] : "yyyy-mm-dd";?>"
	data-date="<?php echo $data->value;?>">
    <input
    	size="16"
    	type="text"
    	value="<?php echo $data->value;?>"
    	readonly="true"
    	<?php echo ($fielddata->mapping->required  == true) ? ' required="required" data-validation-required-message="'.JText::_('COM_JOOMPROFILE_VALIDATION_REQUIRED').'"' : ''; ?>
    	<?php if(isset($fielddata->params['placeholder']) && !empty($fielddata->params['placeholder'])): ?>
			placeholder="<?php echo JText::_($fielddata->params['placeholder']);?>"
		<?php endif;?>
		class="input-medium <?php echo $class;?>"
		name="joomprofile-field[<?php echo $fielddata->id;?>]"
		id="joomprofile-field-<?php echo $fielddata->id;?>"
	/>
    <span class="add-on"><i class="fa fa-th"></i></span>
</div>
</div>
<?php
