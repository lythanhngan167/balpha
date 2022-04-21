<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
JHtml::script("components/com_joomprofile/extensions/profile/templates/js/profile.js");
?>
<?php if(!empty($data->item->id)) :?>
<script>
    var jpFormToken = '<?php echo JSession::getFormToken();?>';
(function($){
	$(document).ready(function(){
		joomprofile.profile.fieldgroup.loadfields(<?php echo $data->item->id;?>);		
	});
})(joomprofile.jQuery);
</script>
<?php endif;?>

<div class="row-fluid joomprofile-grid-header">
	<div class="row-fluid">
		<h3><i class="fa fa-user"></i> <?php echo JText::_('COM_JOOMPROFILE_PROFILE');?> > <?php echo JText::_('COM_JOOMPROFILE_FIELDGROUPS');?> > <span class="btn-link"><?php echo @$data->item->title;?></span></h3>
	</div>
	
	<div class="row-fluid joomprofile-filter-block">
			<div class="pull-right">		
			<?php 
				// TODO : use in base code
				$this->set('ext_name', 'fieldgroup');
				echo $this->render('admin.profile.menu.edit');
			?>
		</div>
	</div>
</div>

<div class="row-fluid">
	<form class="form-horizontal f90-validate-form" id="joomprofile-edit-form" action="index.php?option=com_joomprofile&view=profile" method="POST">
	
		<ul class="nav nav-tabs">
			<li class="active"><a href="#details" data-toggle="tab"><?php echo JText::_('COM_JOOMPROFILE_DETAILS');?></a></li>
			<li><a href="#fields" data-toggle="tab"><?php echo JText::_('COM_JOOMPROFILE_FIELDS');?></a></li>
		</ul>		
		
		<div class="tab-content">
			<div class="tab-pane active" id="details"> 	

		<div class="row-fluid">	
			<div class="span6">		
			<fieldset class="form">
				<h3><?php echo JText::_('COM_JOOMPROFILE_CORE_DETAILS');?></h3>
				<hr>
					
			 	<div class="control-group">
					<div class="control-label"><?php echo $data->form->getLabel('title'); ?> </div>
					<div class="controls"><?php echo $data->form->getInput('title'); ?></div>                                                                
				</div>
				
				<div class="control-group">
					<div class="control-label"><?php echo $data->form->getLabel('published'); ?> </div>
					<div class="controls"><?php echo $data->form->getInput('published'); ?></div>                                                                
				</div>

				<div class="control-group">
					<div class="control-label"><?php echo $data->form->getLabel('registration'); ?> </div>
					<div class="controls"><?php echo $data->form->getInput('registration'); ?></div>                                                                
				</div>
					
				<div class="control-group">
					<div class="control-label"><?php echo $data->form->getLabel('description'); ?> </div>
					<div class="controls"><?php echo $data->form->getInput('description'); ?></div>                                                                
				</div>	
				
				<div class="control-group">
						<div class="control-label"><?php echo $data->form->getLabel('jusergroups'); ?> </div>
						<div class="controls"><?php echo $data->form->getInput('jusergroups'); ?></div>                                                                
				</div>
			</fieldset>
			</div>
			<div class="span6">
			<fieldset class="form">		
				<h3><?php echo JText::_('COM_JOOMPROFILE_PARAMETERS');?></h3>
				<hr>
				
				<?php $fields = $data->form->getFieldset('fieldgroup_params'); ?>
				<?php foreach ($fields as $field):?>
					<div class="control-group">
						<div class="control-label"><?php echo $field->label;?></div>
						<div class="controls"><?php echo $field->input; ?></div>
					</div>
				<?php endforeach;?>	
			</fieldset>
			</div>
			</div>
		</div>
		
		<div class="tab-pane" id="fields"> 
			<div class="joomprofile-grid">
				<div id="joomprofile-fieldgroup-fields">
				</div>
			</div>
		</div>
		</div>
		<!-- Task-->
		<input 	id="form_joomprofile_task" name="task" type="hidden"	value="" />
		<!-- Field Id-->
		<?php echo $data->form->getInput('ordering'); ?>
		<input name="id" type="hidden" value="<?php echo !empty($data->item->id) ? $data->item->id : 0;?>">
		<input name="item_id" type="hidden" value="<?php echo !empty($data->item->id) ? $data->item->id : 0;?>">
		<?php echo JHtml::_('form.token'); ?>
	</form>
</div>
<?php 
