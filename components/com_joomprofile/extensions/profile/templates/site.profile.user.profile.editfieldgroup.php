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
<form>
<?php if(isset($data->fields) && count($data->fields) > 0):?>

	<div class="row-fluid clearfix ">
		<div class="pull-right f90pro-action">
      		<span class="f90pro-save">
      			<a class="btn-f90pro btn btn-success" href="#" onclick="joomprofile.profile.savefieldgroup(<?php echo $data->user->id;?>, <?php echo $data->fieldgroup_id;?>); return false;">
      				<i class="fa fa-save"></i> <?php echo JText::_('COM_JOOMPROFILE_SAVE');?>
      			</a>
      		</span>
      		<span class="f90pro-cancel">
      			<a class="btn-f90pro btn btn-default" href="#" onclick="joomprofile.profile.getfieldGroupViewHtml(<?php echo $data->user->id;?>, <?php echo $data->fieldgroup_id;?>); return false;">
      			<i class="fa fa-times-circle"></i> <?php echo JText::_('COM_JOOMPROFILE_CANCEL');?></a>
      		</span>
    	</div>
	</div>

<?php foreach($data->fields as $key => $field):?>
	<?php $field = $field->toObject();?>
	<?php if($field->published == false) : ?>
		<?php continue;?>
	<?php endif;?>

	<?php $field->mapping = $data->fieldmapping[$key];?>
	<?php $field_instance = JoomprofileLibField::get($field->type);?>

  <div class="<?php echo $field->css_class; ?>" id="<?php echo "f90pro-field-display-".$field->id; ?>">
		<div class="f90pro-field-display row-fluid clearfix control-group">
		     <?php if(!isset($field->params['show_label']) || intval($field->params['show_label'])) : ?>
                <div class="f90pro-field-name span3">
                    <h4 class="control-label">
                        <label class="control-label hasTooltip <?php echo ($field->mapping->required == 1)? ' jprequire ' : '';?>"
                            <?php echo !empty($field->tooltip) ? 'data-toggle="tooltip" data-original-title="<strong>'.JText::_($field->title).'</strong><br/>'.JText::_($field->tooltip).'"' : '';?>>
                            <?php echo JText::_($field->title);?>
                        </label>
                    </h4>
                </div>
                <div class="f90pro-field-content span9 controls">
                <?php if(isset($field->mapping) && $field->mapping->editable ==false && $data->user->id && !$data->isAdmin && !$data->isProfileEditor):?>
                    <?php echo $field_instance->getViewHtml($field, isset($data->field_values[$field->id]) ? $data->field_values[$field->id] : '', $data->user->id);?>
                <?php else: ?>
                    <?php echo $field_instance->getUserEditHtml($field, isset($data->field_values[$field->id]) ? $data->field_values[$field->id] : '', $data->user->id);?>
                <?php endif;?>
                </div>
            <?php else : ?>
                <div class="f90pro-field-content span12 controls">
                    <?php if(isset($field->mapping) && $field->mapping->editable ==false && $data->user->id && !$data->isAdmin && !$data->isProfileEditor):?>
                        <?php echo $field_instance->getViewHtml($field, isset($data->field_values[$field->id]) ? $data->field_values[$field->id] : '', $data->user->id);?>
                    <?php else: ?>
                        <?php echo $field_instance->getUserEditHtml($field, isset($data->field_values[$field->id]) ? $data->field_values[$field->id] : '', $data->user->id);?>
                    <?php endif;?>
                </div>
            <?php endif;?>
		</div>
		</div>

			<!-- <select name="joomprofile-field-privacy[<?php echo $field->id;?>]">
			<?php foreach($data->privacy_options as $key => $value) :?>
				<option value="<?php echo $key; ?>"
						<?php echo (isset($data->privacy_values[$field->id])
									&& $data->privacy_values[$field->id] == $key)
									? 'selected="selected"'
									: '';?>>
					<?php echo $value;?>
				</option>
			<?php endforeach;?>
			</select>
			 -->
<?php endforeach;?>
<?php else:?>
	There are no fields in this group.
<?php endif;?>

<?php echo JHtml::_('form.token'); ?>
</form>
<?php
