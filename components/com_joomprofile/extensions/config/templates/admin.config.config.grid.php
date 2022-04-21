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
<div class="row-fluid joomprofile-grid-header">
	<div class="row-fluid">
		<h3><i class="fa-lg fa fa-cog"></i> <?php echo JText::_('COM_JOOMPROFILE_CONFIG');?></h3>
	</div>

	<div class="row-fluid joomprofile-filter-block">

			<div class="filter-search btn-group pull-left">

			</div>
			<div class="pull-right">
			<?php
				// TODO : use in base code
				$this->set('ext_name', 'config');
				echo $this->render('admin.config.menu.grid');
			?>
		</div>
	</div>
</div>

<div class="row-fluid">
	<form class="form-horizontal" id="joomprofile-edit-form" action="index.php?option=com_joomprofile&view=config" method="POST">

		<div class="row-fluid">
		<?php foreach($data->triggered as $html) :?>
			<div class="span6">
				<?php echo $html;?>
			</div>
		<?php endforeach;?>

		<?php $field_prefix = 'joomprofile_form_config_';?>
		<?php $fields = $data->form->getFieldset('joomprofile-config');?>
		<div class="span6">
			<h3><?php echo JText::_('COM_JOOMPROFILE_STYLING');?></h3>
			<hr />
			<fieldset class="form-horizontal">
				<?php $field = $fields[$field_prefix.'tmpl_load_jquery'];?>
				<div class="control-group">
					<div class="control-label"><?php echo $field->label; ?> </div>
					<div class="controls"><?php echo $field->input; ?></div>
				</div>
				<?php $field = $fields[$field_prefix.'tmpl_load_bootstrap'];?>
				<div class="control-group">
					<div class="control-label"><?php echo $field->label; ?> </div>
					<div class="controls"><?php echo $field->input; ?></div>
				</div>
			</fieldset>
		</div>
		<!-- Task-->
		<input 	id="form_joomprofile_task" name="task" type="hidden"	value="" />
		<?php echo JHtml::_('form.token'); ?>
	</form>
</div>
