<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Userlogs
 * @author     Minh Thái Thi <thiminhthaichoigame@gmail.com>
 * @copyright  2020 Minh Thái Thi
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

use \Joomla\CMS\HTML\HTMLHelper;
use \Joomla\CMS\Factory;
use \Joomla\CMS\Uri\Uri;
use \Joomla\CMS\Router\Route;
use \Joomla\CMS\Language\Text;


HTMLHelper::addIncludePath(JPATH_COMPONENT . '/helpers/html');
HTMLHelper::_('behavior.tooltip');
HTMLHelper::_('behavior.formvalidation');
HTMLHelper::_('formbehavior.chosen', 'select');
HTMLHelper::_('behavior.keepalive');

// Import CSS
$document = Factory::getDocument();
$document->addStyleSheet(Uri::root() . 'media/com_userlogs/css/form.css');
?>
<script type="text/javascript">
	js = jQuery.noConflict();
	js(document).ready(function () {
		
	js('input:hidden.type').each(function(){
		var name = js(this).attr('name');
		if(name.indexOf('typehidden')){
			js('#jform_type option[value="'+js(this).val()+'"]').attr('selected',true);
		}
	});
	js("#jform_type").trigger("liszt:updated");
	});

	Joomla.submitbutton = function (task) {
		if (task == 'userlog.cancel') {
			Joomla.submitform(task, document.getElementById('userlog-form'));
		}
		else {
			
			if (task != 'userlog.cancel' && document.formvalidator.isValid(document.id('userlog-form'))) {
				
				Joomla.submitform(task, document.getElementById('userlog-form'));
			}
			else {
				alert('<?php echo $this->escape(Text::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
			}
		}
	}
</script>

<form
	action="<?php echo JRoute::_('index.php?option=com_userlogs&layout=edit&id=' . (int) $this->item->id); ?>"
	method="post" enctype="multipart/form-data" name="adminForm" id="userlog-form" class="form-validate form-horizontal">

	
				
	<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'userlog')); ?>
	<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'userlog', JText::_('COM_USERLOGS_TAB_USERLOG', true)); ?>
	<div class="row-fluid">
		<div class="span10 form-horizontal">
			<fieldset class="adminform">
				<legend><?php echo JText::_('COM_USERLOGS_FIELDSET_USERLOG'); ?></legend>
				
			<?php
				foreach((array)$this->item->type as $value): 
					if(!is_array($value)):
						echo '<input type="hidden" class="type" name="jform[typehidden]['.$value.']" value="'.$value.'" />';
					endif;
				endforeach;
			?>
				<?php echo $this->form->renderField('status'); ?>
				<?php echo $this->form->renderField('type'); ?>
				<?php echo $this->form->renderField('transfer_id'); ?>
				<?php echo $this->form->renderField('agent_id'); ?>
				<?php echo $this->form->renderField('customer_id'); ?>
				<?php echo $this->form->renderField('user_id'); ?>
				<?php echo $this->form->renderField('old_level'); ?>
				<?php echo $this->form->renderField('new_level'); ?>
				<?php echo $this->form->renderField('created_date'); ?>

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
	<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />
	<input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />
	<input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />
	<input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />
	<?php echo $this->form->renderField('created_by'); ?>
	<?php echo $this->form->renderField('modified_by'); ?>
	<input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />

	
	<?php echo JHtml::_('bootstrap.endTabSet'); ?>

	<input type="hidden" name="task" value=""/>
	<?php echo JHtml::_('form.token'); ?>

</form>
