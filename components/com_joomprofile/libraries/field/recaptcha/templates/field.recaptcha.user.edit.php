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
	type="text"		
	name="nojoomprofile-field[<?php echo $fielddata->id;?>]"
	id="joomprofile-field-<?php echo $fielddata->id;?>"
	required="required"
	data-validation-required-message="<?php echo JText::_('COM_JOOMPROFILE_VALIDATION_INVALID_VALUE');?>"
	style="display : none;"
/>

<div id="conjoomprofile-field-<?php echo $fielddata->id;?>">

</div>
<div class="help-block" />



<script src="<?php echo $data->script_url;?>&onload=onloadRecaptchaCallback" ></script>
<script type="text/javascript">
function onloadRecaptchaCallback(){
	grecaptcha.render("conjoomprofile-field-<?php echo $fielddata->id;?>", 
	{
		sitekey: "<?php echo $data->pubkey;?>", 
		theme: "<?php echo $data->theme;?>",
		callback : function(response) {
			joomprofile.jQuery.ajax({
				url: "<?php echo JUri::base();?>index.php?option=com_joomprofile&view=profile&task=field.validate&format=json&id=<?php echo $fielddata->id;?>&user_id=<?php echo $data->user_id;?>&_="+(new Date()).getTime(),
				data : { 'value' : response},
				type: "POST"
			}).done(function(data) {
				data = joomprofile.jQuery.parseJSON(data);
				var $field = joomprofile.jQuery('#joomprofile-field-<?php echo $fielddata->id;?>');
				if(typeof(data.valid) != 'undefined' && data.valid){					
		            $field.val(data.value);		                    
		            $field.parents(".control-group").first().removeClass('error');
		            $field.parent().find('.help-block').html('');
		        } else {
		            $field.val("");
		            $field.parents(".control-group").first().addClass('error');
		            $field.parent().find('.help-block').html('<ul role="alert"><li>'+data.message+'</li></ul>');
		        }		        
			}).fail(function() {
				// TODO : model popup
				alert('Error in connection.');
			});	
		}
	});		
}

</script>


<?php 
