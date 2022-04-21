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
<?php if(!empty($data->item->usergroup_id)) :?>
<script>
    var jpFormToken = '<?php echo JSession::getFormToken();?>';
(function($){
	$(document).ready(function(){
		joomprofile.profile.usergroup.loadSearchFields(<?php echo $data->item->usergroup_id;?>);		
	});
})(joomprofile.jQuery);
</script>
<?php endif;?>

<div class="row-fluid joomprofile-grid-header">
	<div class="row-fluid">
		<h3><i class="fa fa-user"></i> <?php echo JText::_('COM_JOOMPROFILE_PROFILE');?> > <?php echo JText::_('COM_JOOMPROFILE_USERGROUP');?> > <span class="btn-link"><?php echo $data->item->title;?></span></h3>
	</div>
	
	<div class="row-fluid joomprofile-filter-block">
			<div class="pull-right">		
			<?php 
				// TODO : use in base code
				$this->set('ext_name', 'usergroup');
				echo $this->render('admin.profile.menu.edit');
			?>
		</div>
	</div>
</div>

<div class="row-fluid">
	<form class="form-horizontal f90-validate-form" id="joomprofile-edit-form" action="index.php?option=com_joomprofile&view=profile" method="POST">

		<div class="row-fluid">				
			<fieldset class="form-horizontal">
				<h3><?php echo JText::_('COM_JOOMPROFILE_CORE_DETAILS');?></h3>
				<hr>
					
				<?php $fieldSet = $data->form->getFieldset('params'); ?>
			 	<div class="control-group">
					<div class="control-label"><?php echo $fieldSet['joomprofile_form_params_not_searchable']->label; ?> </div>
					<div class="controls">
						<input type="hidden" nanme="joomprofile_form[params][not_searchable]" value="" />
					<?php 
						echo $fieldSet['joomprofile_form_params_not_searchable']->input;
						echo $data->form->getInput('usergroup_id');
					?>
					</div>                                                                
				</div>
			</fieldset>
		</div>

		<ul class="nav nav-tabs">
			<li class="active"><a href="#jpSearchfields" data-toggle="tab"><?php echo JText::_('COM_JOOMPROFILE_SEARCH_FIELDS');?></a></li>
		</ul>		
		
		<div class="tab-content">
		
		<div class="tab-pane active" id="jpSearchfields"> 
			<div class="joomprofile-grid">
				<div id="joomprofile-search-fields">
				</div>
			</div>
		</div>
		</div>
		<!-- Task-->
		<input 	id="form_joomprofile_task" name="task" type="hidden"	value="" />
		<!-- Field Id-->
		<input name="id" type="hidden" value="<?php echo !empty($data->item->usergroup_id) ? $data->item->usergroup_id : 0;?>">		
		<?php echo JHtml::_('form.token'); ?>
		
	</form>
</div>
<?php 
