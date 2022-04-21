<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>
<script>
(function($){
	$(document).ready(function(){

		if($('#joomprofile_form_profile_show_avatar_from').val() == 1){
			$('#joomprofile_form_profile_avatar_field').parent().parent().removeClass('hide');
		}
		else{
			$('#joomprofile_form_profile_avatar_field').parent().parent().addClass('hide');
		}

		$('#joomprofile_form_profile_show_avatar_from').click(function(){
			if(parseInt($(this).val()) == 1){
				$('#joomprofile_form_profile_avatar_field').parent().parent().removeClass('hide');
				return true;
			}

			$('#joomprofile_form_profile_avatar_field').parent().parent().addClass('hide');
		});	
	});
})(joomprofile.jQuery);
</script>

<div class="row-fluid">	
		<!-- If usergroups is not selected then it didn't get post so add a hidden element with the same name -->
		<input type="hidden" name="joomprofile_form[profile][registration_allowed_jusergroups]" value="" />
		<?php $field_prefix = 'joomprofile_form_profile_';?>
		<?php $fields = $data->form->getFieldset('joomprofile-config');?>
		
		<h3><?php echo JText::_('COM_JOOMPROFILE_PROFILE');?></h3>
		<hr />
			<fieldset class="form-horizontal">
				<?php $field = $fields[$field_prefix.'profile_editor'];?>
				<div class="control-group">
					<div class="control-label"><?php echo $field->label; ?> </div>
					<div class="controls"><?php echo $field->input; ?></div>								
				</div>

				<?php $field = $fields[$field_prefix.'registration_jusergroups_selection'];?>
				<div class="control-group">
					<div class="control-label"><?php echo $field->label; ?> </div>
					<div class="controls"><?php echo $field->input; ?></div>								
				</div>
				
				<?php $field = $fields[$field_prefix.'registration_multi_jusergroups'];?>
				<div class="control-group">
					<div class="control-label"><?php echo $field->label; ?> </div>
					<div class="controls"><?php echo $field->input; ?></div>								
				</div>
				
				<?php $field = $fields[$field_prefix.'registration_allowed_jusergroups'];?>
				<div class="control-group">
					<div class="control-label"><?php echo $field->label; ?> </div>
					<div class="controls"><?php echo $field->input; ?></div>								
				</div>
				
				<?php $field = $fields[$field_prefix.'registration_field_email'];?>
				<div class="control-group">
					<div class="control-label"><?php echo $field->label; ?> </div>
					<div class="controls"><?php echo $field->input; ?></div>								
				</div>
				
				<?php $field = $fields[$field_prefix.'registration_field_username'];?>
				<div class="control-group">
					<div class="control-label"><?php echo $field->label; ?> </div>
					<div class="controls"><?php echo $field->input; ?></div>								
				</div>
				
				<?php $field = $fields[$field_prefix.'registration_field_name'];?>
				<div class="control-group">
					<div class="control-label"><?php echo $field->label; ?> </div>
					<div class="controls"><?php echo $field->input; ?></div>								
				</div>
				
				<?php $field = $fields[$field_prefix.'registration_field_password'];?>
				<div class="control-group">
					<div class="control-label"><?php echo $field->label; ?> </div>
					<div class="controls"><?php echo $field->input; ?></div>								
				</div>
				
				<?php $field = $fields[$field_prefix.'show_avatar_from'];?>
				<div class="control-group">
					<div class="control-label"><?php echo $field->label; ?> </div>
					<div class="controls"><?php echo $field->input; ?></div>								
				</div>
				
				<?php $field = $fields[$field_prefix.'avatar_field'];?>
				<div class="control-group">
					<div class="control-label"><?php echo $field->label; ?> </div>
					<div class="controls"><?php echo $field->input; ?></div>								
				</div>

				<?php $field = $fields[$field_prefix.'show_blank_field'];?>
				<div class="control-group">
					<div class="control-label"><?php echo $field->label; ?> </div>
					<div class="controls"><?php echo $field->input; ?></div>								
				</div>
			</fieldset>

			<h3><?php echo JText::_('COM_JOOMPROFILE_SEARCH');?></h3>
			<hr />
			<fieldset class="form-horizontal">
			
				<?php $field = $fields[$field_prefix.'search_show_all'];?>
				<div class="control-group">
					<div class="control-label"><?php echo $field->label; ?> </div>
					<div class="controls"><?php echo $field->input; ?></div>								
				</div>

				<?php $field = $fields[$field_prefix.'search_orderby'];?>
				<div class="control-group">
					<div class="control-label"><?php echo $field->label; ?> </div>
					<div class="controls"><?php echo $field->input; ?></div>								
				</div>
				
				<?php $field = $fields[$field_prefix.'search_ordering'];?>
				<div class="control-group">
					<div class="control-label"><?php echo $field->label; ?> </div>
					<div class="controls"><?php echo $field->input; ?></div>								
				</div>
				
				<?php $field = $fields[$field_prefix.'search_result_counter'];?>
				<div class="control-group">
					<div class="control-label"><?php echo $field->label; ?> </div>
					<div class="controls"><?php echo $field->input; ?></div>								
				</div>
				
				<?php $field = $fields[$field_prefix.'allow_keyword_search'];?>
				<div class="control-group">
					<div class="control-label"><?php echo $field->label; ?> </div>
					<div class="controls"><?php echo $field->input; ?></div>								
				</div>
				<?php $field = $fields[$field_prefix.'keyword_search_in'];?>
				<div class="control-group">
					<div class="control-label"><?php echo $field->label; ?> </div>
					<div class="controls"><?php echo $field->input; ?></div>								
				</div>				
			</fieldset>			
</div>
<?php 
