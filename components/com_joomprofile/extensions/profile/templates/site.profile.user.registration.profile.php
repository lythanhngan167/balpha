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
<div class="">
	<?php if(isset($data->allowed_jusergroups) && $data->allowed_jusergroups != false) : ?>
		<fieldset class="form-horizontal">
			<legend><?php echo JText::_('COM_JOOMPROFILE_USERGROUP_SELECTION');?></legend>
			<div class="control-group" id="joomprofile-registration-usergroup">
				<label class="control-label"><?php echo JText::_('COM_JOOMPROFILE_SELECT_USERGROUP');?></label>
				<?php if($data->config['registration_multi_jusergroups']) :?>
				<div class="controls">
					<?php foreach ($data->allowed_jusergroups as $usergroup_id) : ?>
						<?php if(empty($usergroup_id)):?>
							<?php continue;?>
						<?php endif;?>
						<label class="checkbox stacked">
							<input type="checkbox" name="joomprofile-usergroups[]" value="<?php echo $usergroup_id;?>"  <?php echo (is_array($data->reg_data->usergroups) && in_array($usergroup_id, $data->reg_data->usergroups)) ? 'checked="checked"' : '';?> minchecked="1" data-validation-minchecked-message="<?php echo JText::_('COM_JOOMPROFILE_VALIDATION_CHECKONE');?>">
							<?php echo $data->all_jusergroups[$usergroup_id]->title;?>
						</label>
					<?php endforeach;?>
				</div>
				<?php else:?>
					<div class="controls">
						<select name="joomprofile-usergroups[]" class="required" data-validation-required-message="<?php echo JText::_('COM_JOOMPROFILE_VALIDATION_REQUIRED'); ?>">
							<option value="">- Select -</option>
							<?php foreach ($data->allowed_jusergroups as $usergroup_id) : ?>
								<option value="<?php echo $usergroup_id?>" <?php echo in_array($usergroup_id, $data->reg_data->usergroups) ? 'selected="selected"' : '';?>><?php echo $data->all_jusergroups[$usergroup_id]->title;?></option>
							<?php endforeach;?>
						</select>
					</div>
				<?php endif;?>
			</div>
		</fieldset>
	<?php endif;?>

	<fieldset class="form-horizontal">
		<?php if($data->fieldgroup !== false) :?>
			<legend><?php echo JText::_($data->fieldgroup->title);?></legend>
			<?php if(!empty($data->fieldgroup_fields)):?>
				<?php foreach($data->fieldgroup_fields as $key => $field):?>
					<?php if(!$field->getPublished() || (isset($data->fieldmapping[$key]) && !$data->fieldmapping[$key]->registration)):?>
						<?php continue;?>
					<?php endif;?>

					<?php $field = $field->toObject();?>
					<?php $field->mapping = $data->fieldmapping[$key];?>
					<?php $field_instance = JoomprofileLibField::get($field->type);?>
					<div class="control-group">
		      			<label class="control-label hasTooltip <?php echo ($field->mapping->required == 1)? ' jprequire ' : '';?>"
		      				<?php echo !empty($field->tooltip) ? 'data-toggle="tooltip" data-original-title="<strong>'.JText::_($field->title).'</strong><br/>'.JText::_($field->tooltip).'"' : '';?>>
		      				<?php echo JText::_($field->title);?>
		      			</label>
						<div class="controls">
							<?php echo $field_instance->getUserEditHtml($field, isset($data->reg_data->values[$field->id]) ? $data->reg_data->values[$field->id] : '', 0);?>

							<!-- PRIVACY -->
							<!-- <select name="joomprofile-field-privacy[<?php echo $field->id;?>]">
							<?php foreach($data->privacy_options as $key => $value) :?>
								<option value="<?php echo $key; ?>"
										<?php echo (isset($data->reg_data->values[$field->id]['privacy'])
													&& $data->reg_data->values[$field->id]['privacy'] == $key)
													? 'selected="selected"'
													: '';?>>
									<?php echo $value;?>
								</option>
							<?php endforeach;?>
							</select>
							 -->
						</div>
					</div>
				<?php endforeach;?>
			<?php else:?>
				<?php echo JText::_('COM_JOOMPROFILE_NO_FIELDS');?>
			<?php endif;?>
		<?php else:?>
			<legend><?php echo JText::_('COM_JOOMPROFILE_ERROR');?></legend>
			<?php echo JText::_('COM_JOOMPROFILE_NO_FIELDGRUOPS');?>
		<?php endif;?>

	</fieldset>
</div>

<input 	id="form_joomprofile_task" name="task" type="hidden"	value="user.registration_save" />
<?php if($data->fieldgroup !== false) :?>
	<!-- Task-->
	<?php if($data->final_step):?>
		<?php $button_text = JText::_('COM_JOOMPROFILE_FINISH');?>
		<input 	id="form_joomprofile_finish" name="finish" type="hidden"	value="1" />
		<?php ?>
	<?php else:?>
		<?php $button_text = JText::_('COM_JOOMPROFILE_NEXT');?>
		<input 	id="form_joomprofile_step" name="step" type="hidden"	value="<?php echo $data->next_step;?>" />
	<?php endif;?>

	<div class="form-actions">
		<?php if($data->next_step > 2):?>
			<div class="pull-left">
				<button type="button" class="btn btn-danger jp-btn-back" onClick="joomprofile.registration.back(<?php echo ($data->next_step - 2);?>); return false;"><?php echo JText::_('COM_JOOMPROFILE_BACK')?></button>
			</div>
		<?php endif;?>

		<div class="pull-right">
			<button type="button" class="btn btn-success btn-medium jp-btn-next" onClick="joomprofile.registration.next();return false;"><?php echo $button_text;?> </button>
		</div>
	</div>
<?php endif;?>
<?php echo JHtml::_('form.token'); ?>
<?php
