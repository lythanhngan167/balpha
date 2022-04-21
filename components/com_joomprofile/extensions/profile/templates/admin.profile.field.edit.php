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
<script>
(function($){
	$(document).ready(function(){
		joomprofile.profile.field.getParameters($('#joomprofile_form_type').val());		
	});
})(joomprofile.jQuery);
</script>


<div class="row-fluid joomprofile-grid-header">
	<div class="row-fluid">
		<h3><i class="fa fa-user"></i> <?php echo JText::_('COM_JOOMPROFILE_PROFILE');?> > <?php echo JText::_('COM_JOOMPROFILE_FIELDS');?> > <span class="btn-link"><?php echo @$data->item->title;?></span></h3>
	</div>
	
	<div class="row-fluid joomprofile-filter-block">
			<div class="pull-right">		
			<?php 
				// TODO : use in base code
				$this->set('ext_name', 'field');
				echo $this->render('admin.profile.menu.edit');
			?>
		</div>
	</div>
</div>
<div class="row-fluid">
	<form class="form-horizontal f90-validate-form" id="joomprofile-edit-form" action="index.php?option=com_joomprofile&view=profile" method="POST">
		<div class="span6">
			<fieldset class="form-horizontal">
			
			<!-- Form Name -->
			<h3><?php echo JText::_('COM_JOOMPROFILE_CORE_DETAILS');?></h3>
			<hr />			
			  			
			<div class="control-group">
				<div class="control-label"><?php echo $data->form->getLabel('title'); ?> </div>
				<div class="controls"><?php echo $data->form->getInput('title'); ?></div>                                                                
			</div>
		
			<div class="control-group">
				<div class="control-label"><?php echo $data->form->getLabel('published'); ?> </div>
				<div class="controls"><?php echo $data->form->getInput('published'); ?></div>                                                                
			</div>
			
			<div class="control-group">
				<div class="control-label"><?php echo $data->form->getLabel('css_class'); ?> </div>
				<div class="controls"><?php echo $data->form->getInput('css_class'); ?></div>                                                                
			</div>
			
			<div class="control-group">
				<div class="control-label"><?php echo $data->form->getLabel('tooltip'); ?> </div>
				<div class="controls"><?php echo $data->form->getInput('tooltip'); ?></div>                                                                
			</div>
			</fieldset>
		</div>
		
		<div class="span6">
			<fieldset class="form-horizontal">
				<h3><?php echo JText::_('COM_JOOMPROFILE_PARAMETERS');?></h3>
				<hr/>
				
				<div class="control-group">
					<div class="control-label"><?php echo $data->form->getLabel('type'); ?> </div>
					<div class="controls"><?php echo $data->form->getInput('type'); ?></div>                                                                
				</div>	
				
				<div id="joomprofile-field-params"></div>
					
			</fieldset>
		</div>
		
		<!-- Task-->
		<input 	id="form_joomprofile_task" name="task" type="hidden"	value="" />
		<!-- Field Id-->
		<input name="id" id="com_joomprofile_id" type="hidden" value="<?php echo !empty($data->item->id) ? $data->item->id : 0;?>">
		<input name="item_id" type="hidden" value="<?php echo !empty($data->item->id) ? $data->item->id : 0;?>">
		<?php echo JHtml::_('form.token'); ?>
	</form>
</div>
<?php 
