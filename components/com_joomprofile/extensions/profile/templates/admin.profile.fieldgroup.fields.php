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
<?php if(!empty($data->all_fields)):?>
	<div class="row-fluid">
		<div class="pull-right">
			<select id="joomprofile-fieldgroup-field-id">
				<?php foreach ($data->all_fields as $field):?>
					<option value="<?php echo $field->id; ?>"><?php echo $field->title;?></option>
				<?php endforeach;?>
			</select>
		
			<button class="btn btn-primary" onclick="joomprofile.profile.fieldgroup.addField(<?php echo $data->itemid;?>, <?php echo $field->id;?>); return false;">
				<?php echo JText::_('COM_JOOMPROFILE_ADD_FIELD');?>
			</button>
		</div>
	</div>
<?php endif;?>

<p>
</p>
<?php if(!empty($data->fields)):?>
<table class="table table-hover">
	<thead>
		<tr>
			<th>
			</th>
			<th>
                <?php echo JText::_('COM_JOOMPROFILE_TITLE');?>
            </th>
            <th><?php echo JText::_('COM_JOOMPROFILE_VISIBLE');?></th>
            <th><?php echo JText::_('COM_JOOMPROFILE_EDITABLE');?></th>
            <th><?php echo JText::_('COM_JOOMPROFILE_REQUIRED');?></th>
            <th><?php echo JText::_('COM_JOOMPROFILE_REGISTRATION');?></th>
            <th>
                <div class="text-center"><?php echo JText::_('COM_JOOMPROFILE_ORDERING');?></div>
            </th>
            <th class="pull-right">
                <?php echo JText::_('COM_JOOMPROFILE_STATUS');?>
            </th>
		</tr>
	</thead>
	<tbody>
		<?php $counter = 0; ?>
		<?php $field_fieldgroup_ids = array_keys($data->fields);?>
		<?php foreach($field_fieldgroup_ids as $field_fieldgroup_id):?>
		<tr>
			<td>
				<a class="red" href="#" onclick="joomprofile.profile.fieldgroup.removeField(<?php echo $data->itemid;?>, <?php echo $field_fieldgroup_id;?>);return false;">
  					<i class="fa fa-trash-o"></i> 
  				</a>
  			</td>
			<td>
				<a target="_blank" href="index.php?option=com_joomprofile&view=profile&task=field.edit&id=<?php echo $data->fields[$field_fieldgroup_id]->getId();?>">
				<?php echo $data->fields[$field_fieldgroup_id]->getTitle();?></a>
			</td>
			
			<td class="center">	
				<?php if($data->mappings[$field_fieldgroup_id]->visible):?>
					<a href="#" onclick="joomprofile.profile.fieldgroup.fieldboolean(<?php echo $data->itemid;?>, <?php echo $field_fieldgroup_id;?>, 'visible'); return false;">
						<i class="icon-ok"></i>
					</a> 
				<?php else : ?>
					<a href="#" onclick="joomprofile.profile.fieldgroup.fieldboolean(<?php echo $data->itemid;?>, <?php echo $field_fieldgroup_id;?>, 'visible'); return false;">
						<i class="icon-remove text-error"></i>
					</a>
				<?php endif;?>
			</td>	
			
			<td class="center">	
				<?php if($data->mappings[$field_fieldgroup_id]->editable):?>
					<a href="#" onclick="joomprofile.profile.fieldgroup.fieldboolean(<?php echo $data->itemid;?>, <?php echo $field_fieldgroup_id;?>, 'editable'); return false;">
						<i class="icon-ok"></i>
					</a> 
				<?php else : ?>
					<a href="#" onclick="joomprofile.profile.fieldgroup.fieldboolean(<?php echo $data->itemid;?>, <?php echo $field_fieldgroup_id;?>, 'editable'); return false;">
						<i class="icon-remove text-error"></i>
					</a>
				<?php endif;?>
			</td>	
			
			<td class="center">	
				<?php if($data->mappings[$field_fieldgroup_id]->required):?>
					<a href="#" onclick="joomprofile.profile.fieldgroup.fieldboolean(<?php echo $data->itemid;?>, <?php echo $field_fieldgroup_id;?>, 'required'); return false;">
						<i class="icon-ok"></i>
					</a> 
				<?php else : ?>
					<a href="#" onclick="joomprofile.profile.fieldgroup.fieldboolean(<?php echo $data->itemid;?>, <?php echo $field_fieldgroup_id;?>, 'required'); return false;">
						<i class="icon-remove text-error"></i>
					</a>
				<?php endif;?>
			</td>	
			
			<td class="center">	
				<?php if($data->mappings[$field_fieldgroup_id]->registration):?>
					<a href="#" onclick="joomprofile.profile.fieldgroup.fieldboolean(<?php echo $data->itemid;?>, <?php echo $field_fieldgroup_id;?>, 'registration'); return false;">
						<i class="icon-ok"></i>
					</a> 
				<?php else : ?>
					<a href="#" onclick="joomprofile.profile.fieldgroup.fieldboolean(<?php echo $data->itemid;?>, <?php echo $field_fieldgroup_id;?>, 'registration'); return false;">
						<i class="icon-remove text-error"></i>
					</a>
				<?php endif;?>
			</td>	
			
			
			<td>
				<div class="text-center">
					<?php if($counter != 0):?>							
						<a href="#" onclick="joomprofile.profile.fieldgroup.changeFieldOrder(<?php echo $data->itemid;?>, <?php echo $field_fieldgroup_id;?>, <?php echo $field_fieldgroup_ids[$counter-1];?>); return false;"><i class="fa fa-caret-up"></i></a>
					<?php endif;?>
					<?php if($counter != (count($field_fieldgroup_ids) -1)):?>
					<a href="#" onclick="joomprofile.profile.fieldgroup.changeFieldOrder(<?php echo $data->itemid;?>, <?php echo $field_fieldgroup_id;?>, <?php echo $field_fieldgroup_ids[$counter+1];?>); return false;"><i class="fa fa-caret-down"></i></a>
					<?php endif;?>
				</div>
			</td>
			<td>
				<div class="pull-right">
					<?php if($data->fields[$field_fieldgroup_id]->getPublished()):?>
						<span class="badge badge-success"><?php echo JText::_('COM_JOOMPROFILE_PUBLISHED');?></span>
					<?php else:?>
						<span class="badge badge-important"><?php echo JText::_('COM_JOOMPROFILE_UNPUBLISHED');?></span>
					<?php endif;?>
				</div>
			</td>						
		</tr>
		<?php $counter++;?>
		<?php endforeach;?>
	</tbody>
</table>
<?php endif;?>
<?php 
