<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Customer
 * @author     tung hoang <tungvacc@gmail.com>
 * @copyright  2017 tung hoang
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
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
$document->addStyleSheet(JUri::root() . 'media/com_customer/css/form.css');
?>
<script type="text/javascript">
	js = jQuery.noConflict();
	js(document).ready(function () {

	js('input:hidden.project_id').each(function(){
		var name = js(this).attr('name');
		if(name.indexOf('project_idhidden')){
			js('#jform_project_id option[value="'+js(this).val()+'"]').attr('selected',true);
		}
	});
	js("#jform_project_id").trigger("liszt:updated");
	});

	Joomla.submitbutton = function (task) {
		if (task == 'customer.cancel') {
			Joomla.submitform(task, document.getElementById('customer-form'));
		}
		else {

			if (task != 'customer.cancel' && document.formvalidator.isValid(document.id('customer-form'))) {

				Joomla.submitform(task, document.getElementById('customer-form'));
			}
			else {
				alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
			}
		}
	}
</script>

<form
	action="<?php echo JRoute::_('index.php?option=com_customer&layout=edit&id=' . (int) $this->item->id); ?>"
	method="post" enctype="multipart/form-data" name="adminForm" id="customer-form" class="form-validate">

	<div class="form-horizontal">
		<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_CUSTOMER_TITLE_CUSTOMER', true)); ?>
		<div class="row-fluid">
			<div class="span10 form-horizontal">
				<fieldset class="adminform">

				<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />
				<?php echo $this->form->renderField('name'); ?>
				<?php echo $this->form->renderField('phone'); ?>
				<?php echo $this->form->renderField('email'); ?>
				<?php echo $this->form->renderField('place'); ?>
				<?php echo $this->form->renderField('reference_id');?>
				<?php echo $this->form->renderField('reference_type');?>
				<?php echo $this->form->renderField('province'); ?>
				<input type="hidden" name="jform[sale_id]" value="<?php echo $this->item->sale_id; ?>" />
				<?php echo $this->form->renderField('category_id'); ?>
				<?php echo $this->form->renderField('project_id'); ?>
				<?php echo $this->form->renderField('total_revenue'); ?>

			<?php
				foreach((array)$this->item->project_id as $value):
					if(!is_array($value)):
						echo '<input type="hidden" class="project_id" name="jform[project_idhidden]['.$value.']" value="'.$value.'" />';
					endif;
				endforeach;
			?>				<?php echo $this->form->renderField('status_id'); ?>
				<!-- <input type="hidden" name="jform[total_revenue]" value="<?php echo $this->item->total_revenue; ?>" /> -->
				<input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />
				<input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />
				<input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />
				<input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />

				<?php echo $this->form->renderField('modified_date'); ?>			<?php echo $this->form->renderField('created_by'); ?>
				<?php echo $this->form->renderField('modified_by'); ?>

				<?php echo $this->form->renderField('create_date'); ?>

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

		<?php if (JFactory::getUser()->authorise('core.admin','customer')) : ?>
	<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'permissions', JText::_('JGLOBAL_ACTION_PERMISSIONS_LABEL', true)); ?>
		<?php echo $this->form->getInput('rules'); ?>
	<?php echo JHtml::_('bootstrap.endTab'); ?>
<?php endif; ?>

		<?php echo JHtml::_('bootstrap.endTabSet'); ?>

		<input type="hidden" name="task" value=""/>
		<?php echo JHtml::_('form.token'); ?>

	</div>
</form>
