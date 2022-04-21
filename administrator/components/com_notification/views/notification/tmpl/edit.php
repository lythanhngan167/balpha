<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Notification
 * @author     tung hoang <tungvacc@gmail.com>
 * @copyright  2018 tung hoang
 * @license    bản quyền mã nguồn mở GNU phiên bản 2
 */
// No direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.keepalive');

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet(JUri::root() . 'media/com_notification/css/form.css');
?>
<script type="text/javascript">
	js = jQuery.noConflict();
	js(document).ready(function () {

	js('input:hidden.category').each(function(){
		var name = js(this).attr('name');
		if(name.indexOf('categoryhidden')){
			js('#jform_category option[value="'+js(this).val()+'"]').attr('selected',true);
		}
	});
	js("#jform_category").trigger("liszt:updated");


	js("#jform_category").change(function(){
		if(js(this).val() == '19'){
			js(".company_id").css("display","block");
		}else{
			js(".company_id").css("display","none");
		}
	});
	});



	Joomla.submitbutton = function (task) {
		if (task == 'notification.cancel') {
			Joomla.submitform(task, document.getElementById('notification-form'));
		}
		else {

			if (task != 'notification.cancel' && document.formvalidator.isValid(document.id('notification-form'))) {

				Joomla.submitform(task, document.getElementById('notification-form'));
			}
			else {
				alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
			}
		}
	}
</script>

<form
	action="<?php echo JRoute::_('index.php?option=com_notification&layout=edit&id=' . (int) $this->item->id); ?>"
	method="post" enctype="multipart/form-data" name="adminForm" id="notification-form" class="form-validate">

	<div class="form-horizontal">
		<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_NOTIFICATION_TITLE_NOTIFICATION', true)); ?>
		<div class="row-fluid">
			<div class="span10 form-horizontal">
				<fieldset class="adminform">

									<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />
				<?php echo $this->form->renderField('category'); ?>

			<?php
				foreach((array)$this->item->category as $value):
					if(!is_array($value)):
						echo '<input type="hidden" class="category" name="jform[categoryhidden]['.$value.']" value="'.$value.'" />';
					endif;
				endforeach;
			?>
				<!-- <div class="company_id"><?php echo $this->form->renderField('company_id'); ?></div> -->
				<?php echo $this->form->renderField('title'); ?>
				<?php echo $this->form->renderField('message'); ?>
				<?php echo $this->form->renderField('show_app'); ?>
				<?php echo $this->form->renderField('page_title_app'); ?>
				<?php echo $this->form->renderField('page_app'); ?>
				<?php echo $this->form->renderField('id_app'); ?>
				<?php echo $this->form->renderField('para_1'); ?>
				<?php echo $this->form->renderField('para_2'); ?>
				<?php echo $this->form->renderField('para_3'); ?>

				<?php //echo $this->form->renderField('view'); ?>
				<input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />
				<input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />
				<input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />
				<input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />

				<?php echo $this->form->renderField('created_by'); ?>
				<?php echo $this->form->renderField('modified_by'); ?>

					<?php if ($this->state->params->get('save_history', 1)) : ?>
					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('version_note'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('version_note'); ?></div>
					</div>
					<?php endif; ?>
				</fieldset>
			</div>
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>

		<?php if (JFactory::getUser()->authorise('core.admin','notification')) : ?>
	<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'permissions', JText::_('JGLOBAL_ACTION_PERMISSIONS_LABEL', true)); ?>
		<?php echo $this->form->getInput('rules'); ?>
	<?php echo JHtml::_('bootstrap.endTab'); ?>
<?php endif; ?>

		<?php echo JHtml::_('bootstrap.endTabSet'); ?>

		<input type="hidden" name="task" value=""/>
		<?php echo JHtml::_('form.token'); ?>

	</div>
</form>
<style>
#jform_message{
	height:200px;
	width:500px;
}
#jform_page_app{width:500px;}
.company_id{
	display:none;
}
</style>
