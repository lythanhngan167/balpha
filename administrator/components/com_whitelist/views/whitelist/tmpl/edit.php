<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Whitelist
 * @author     nganly <lythanhngan167@gmail.com>
 * @copyright  2020 nganly
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
$document->addStyleSheet(Uri::root() . 'media/com_whitelist/css/form.css');
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
		if (task == 'whitelist.cancel') {
			Joomla.submitform(task, document.getElementById('whitelist-form'));
		}
		else {
			
			if (task != 'whitelist.cancel' && document.formvalidator.isValid(document.id('whitelist-form'))) {
				
				Joomla.submitform(task, document.getElementById('whitelist-form'));
			}
			else {
				alert('<?php echo $this->escape(Text::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
			}
		}
	}
</script>

<form
	action="<?php echo JRoute::_('index.php?option=com_whitelist&layout=edit&id=' . (int) $this->item->id); ?>"
	method="post" enctype="multipart/form-data" name="adminForm" id="whitelist-form" class="form-validate form-horizontal">

	
	<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'whitelist')); ?>
	<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'whitelist', JText::_('COM_WHITELIST_TAB_WHITELIST', true)); ?>
	<div class="row-fluid">
		<div class="span10 form-horizontal">
			<fieldset class="adminform">
				<legend><?php echo JText::_('COM_WHITELIST_FIELDSET_WHITELIST'); ?></legend>
				<?php echo $this->form->renderField('name_page'); ?>
				<?php echo $this->form->renderField('url_page'); ?>
				<?php echo $this->form->renderField('project_id'); ?>
			<?php
				foreach((array)$this->item->project_id as $value): 
					if(!is_array($value)):
						echo '<input type="hidden" class="project_id" name="jform[project_idhidden]['.$value.']" value="'.$value.'" />';
					endif;
				endforeach;
			?>
				<?php echo $this->form->renderField('created_time'); ?>
				<?php echo $this->form->renderField('updated_time'); ?>
				<?php echo $this->form->renderField('landingpage_name'); ?>
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

	<?php if (JFactory::getUser()->authorise('core.admin','whitelist')) : ?>
	<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'permissions', JText::_('JGLOBAL_ACTION_PERMISSIONS_LABEL', true)); ?>
		<?php echo $this->form->getInput('rules'); ?>
	<?php echo JHtml::_('bootstrap.endTab'); ?>
	<input type="hidden" value="" id="jform_title"/>
<?php endif; ?>
	<?php echo JHtml::_('bootstrap.endTabSet'); ?>

	<input type="hidden" name="task" value=""/>
	<?php echo JHtml::_('form.token'); ?>

</form>
