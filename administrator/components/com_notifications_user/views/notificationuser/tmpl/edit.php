<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Notifications_user
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
$document->addStyleSheet(Uri::root() . 'media/com_notifications_user/css/form.css');
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
	});

	Joomla.submitbutton = function (task) {
		if (task == 'notificationuser.cancel') {
			Joomla.submitform(task, document.getElementById('notificationuser-form'));
		}
		else {
			
			if (task != 'notificationuser.cancel' && document.formvalidator.isValid(document.id('notificationuser-form'))) {
				
				Joomla.submitform(task, document.getElementById('notificationuser-form'));
			}
			else {
				alert('<?php echo $this->escape(Text::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
			}
		}
	}
</script>

<form
	action="<?php echo JRoute::_('index.php?option=com_notifications_user&layout=edit&id=' . (int) $this->item->id); ?>"
	method="post" enctype="multipart/form-data" name="adminForm" id="notificationuser-form" class="form-validate form-horizontal">

	
	<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'notificationuser')); ?>
	<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'notificationuser', JText::_('COM_NOTIFICATIONS_USER_TAB_NOTIFICATIONUSER', true)); ?>
	<div class="row-fluid">
		<div class="span10 form-horizontal">
			<fieldset class="adminform">
				<legend><?php echo JText::_('COM_NOTIFICATIONS_USER_FIELDSET_NOTIFICATIONUSER'); ?></legend>
				<?php echo $this->form->renderField('user_id'); ?>
				<?php echo $this->form->renderField('seen_flag'); ?>
				<?php echo $this->form->renderField('seen_at'); ?>
				<?php echo $this->form->renderField('title'); ?>
				<?php echo $this->form->renderField('message'); ?>
				<?php echo $this->form->renderField('created_date'); ?>
				<?php echo $this->form->renderField('updated_date'); ?>
				<?php echo $this->form->renderField('category'); ?>
			<?php
				foreach((array)$this->item->category as $value): 
					if(!is_array($value)):
						echo '<input type="hidden" class="category" name="jform[categoryhidden]['.$value.']" value="'.$value.'" />';
					endif;
				endforeach;
			?>
				<?php echo $this->form->renderField('status'); ?>
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
	<input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />
	<input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />
	<input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />
	<?php echo $this->form->renderField('created_by'); ?>
	<?php echo $this->form->renderField('modified_by'); ?>

	
	<?php echo JHtml::_('bootstrap.endTabSet'); ?>

	<input type="hidden" name="task" value=""/>
	<?php echo JHtml::_('form.token'); ?>

</form>
