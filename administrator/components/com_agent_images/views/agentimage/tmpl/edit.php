<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Agent_images
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
$document->addStyleSheet(Uri::root() . 'media/com_agent_images/css/form.css');
?>
<script type="text/javascript">
	js = jQuery.noConflict();
	js(document).ready(function () {
		
	});

	Joomla.submitbutton = function (task) {
		if (task == 'agentimage.cancel') {
			Joomla.submitform(task, document.getElementById('agentimage-form'));
		}
		else {
			
			if (task != 'agentimage.cancel' && document.formvalidator.isValid(document.id('agentimage-form'))) {
				
				Joomla.submitform(task, document.getElementById('agentimage-form'));
			}
			else {
				alert('<?php echo $this->escape(Text::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
			}
		}
	}
</script>

<form
	action="<?php echo JRoute::_('index.php?option=com_agent_images&layout=edit&id=' . (int) $this->item->id); ?>"
	method="post" enctype="multipart/form-data" name="adminForm" id="agentimage-form" class="form-validate form-horizontal">

	
	<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />
	<input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />
	<input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />
	<input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />
	<input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />
	<?php echo $this->form->renderField('created_by'); ?>
	<?php echo $this->form->renderField('modified_by'); ?>
	<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'salerimage')); ?>
	<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'salerimage', JText::_('COM_AGENT_IMAGES_TAB_SALERIMAGE', true)); ?>
	<div class="row-fluid">
		<div class="span10 form-horizontal">
			<fieldset class="adminform">
				<legend><?php echo JText::_('COM_AGENT_IMAGES_FIELDSET_SALERIMAGE'); ?></legend>
				<?php echo $this->form->renderField('image1'); ?>
				<?php if (!empty($this->item->image1)) : ?>
					<?php $image1Files = array(); ?>
					<?php foreach ((array)$this->item->image1 as $fileSingle) : ?>
						<?php if (!is_array($fileSingle)) : ?>
							<a href="<?php echo JRoute::_(JUri::root() . 'images/landingpage' . DIRECTORY_SEPARATOR . $fileSingle, false);?>"><?php echo $fileSingle; ?></a> | 
							<?php $image1Files[] = $fileSingle; ?>
						<?php endif; ?>
					<?php endforeach; ?>
					<input type="hidden" name="jform[image1_hidden]" id="jform_image1_hidden" value="<?php echo implode(',', $image1Files); ?>" />
				<?php endif; ?>
				<?php echo $this->form->renderField('image2'); ?>
				<?php if (!empty($this->item->image2)) : ?>
					<?php $image2Files = array(); ?>
					<?php foreach ((array)$this->item->image2 as $fileSingle) : ?>
						<?php if (!is_array($fileSingle)) : ?>
							<a href="<?php echo JRoute::_(JUri::root() . 'images/landingpage' . DIRECTORY_SEPARATOR . $fileSingle, false);?>"><?php echo $fileSingle; ?></a> | 
							<?php $image2Files[] = $fileSingle; ?>
						<?php endif; ?>
					<?php endforeach; ?>
					<input type="hidden" name="jform[image2_hidden]" id="jform_image2_hidden" value="<?php echo implode(',', $image2Files); ?>" />
				<?php endif; ?>
				<?php echo $this->form->renderField('image3'); ?>
				<?php if (!empty($this->item->image3)) : ?>
					<?php $image3Files = array(); ?>
					<?php foreach ((array)$this->item->image3 as $fileSingle) : ?>
						<?php if (!is_array($fileSingle)) : ?>
							<a href="<?php echo JRoute::_(JUri::root() . 'images/landingpage' . DIRECTORY_SEPARATOR . $fileSingle, false);?>"><?php echo $fileSingle; ?></a> | 
							<?php $image3Files[] = $fileSingle; ?>
						<?php endif; ?>
					<?php endforeach; ?>
					<input type="hidden" name="jform[image3_hidden]" id="jform_image3_hidden" value="<?php echo implode(',', $image3Files); ?>" />
				<?php endif; ?>
				<?php echo $this->form->renderField('image4'); ?>
				<?php if (!empty($this->item->image4)) : ?>
					<?php $image4Files = array(); ?>
					<?php foreach ((array)$this->item->image4 as $fileSingle) : ?>
						<?php if (!is_array($fileSingle)) : ?>
							<a href="<?php echo JRoute::_(JUri::root() . 'images/landingpage' . DIRECTORY_SEPARATOR . $fileSingle, false);?>"><?php echo $fileSingle; ?></a> | 
							<?php $image4Files[] = $fileSingle; ?>
						<?php endif; ?>
					<?php endforeach; ?>
					<input type="hidden" name="jform[image4_hidden]" id="jform_image4_hidden" value="<?php echo implode(',', $image4Files); ?>" />
				<?php endif; ?>
				<?php echo $this->form->renderField('image5'); ?>
				<?php if (!empty($this->item->image5)) : ?>
					<?php $image5Files = array(); ?>
					<?php foreach ((array)$this->item->image5 as $fileSingle) : ?>
						<?php if (!is_array($fileSingle)) : ?>
							<a href="<?php echo JRoute::_(JUri::root() . 'images/landingpage' . DIRECTORY_SEPARATOR . $fileSingle, false);?>"><?php echo $fileSingle; ?></a> | 
							<?php $image5Files[] = $fileSingle; ?>
						<?php endif; ?>
					<?php endforeach; ?>
					<input type="hidden" name="jform[image5_hidden]" id="jform_image5_hidden" value="<?php echo implode(',', $image5Files); ?>" />
				<?php endif; ?>
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

	<?php if (JFactory::getUser()->authorise('core.admin','agent_images')) : ?>
	<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'permissions', JText::_('JGLOBAL_ACTION_PERMISSIONS_LABEL', true)); ?>
		<?php echo $this->form->getInput('rules'); ?>
	<?php echo JHtml::_('bootstrap.endTab'); ?>
<?php endif; ?>
	<?php echo JHtml::_('bootstrap.endTabSet'); ?>

	<input type="hidden" name="task" value=""/>
	<?php echo JHtml::_('form.token'); ?>

</form>
