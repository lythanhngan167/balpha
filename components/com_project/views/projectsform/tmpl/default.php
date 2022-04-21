<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Project
 * @author     tung hoang <tungvacc@gmail.com>
 * @copyright  2017 tung hoang
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');

// Load admin language file
$lang = JFactory::getLanguage();
$lang->load('com_project', JPATH_SITE);
$doc = JFactory::getDocument();
$doc->addScript(JUri::base() . '/media/com_project/js/form.js');

$user    = JFactory::getUser();
$canEdit = ProjectHelpersProject::canUserEdit($this->item, $user);


?>

<div class="projects-edit front-end-edit">
	<?php if (!$canEdit) : ?>
		<h3>
			<?php throw new Exception(JText::_('COM_PROJECT_ERROR_MESSAGE_NOT_AUTHORISED'), 403); ?>
		</h3>
	<?php else : ?>
		<?php if (!empty($this->item->id)): ?>
			<h1><?php echo JText::sprintf('COM_PROJECT_EDIT_ITEM_TITLE', $this->item->id); ?></h1>
		<?php else: ?>
			<h1><?php echo JText::_('COM_PROJECT_ADD_ITEM_TITLE'); ?></h1>
		<?php endif; ?>

		<form id="form-projects"
			  action="<?php echo JRoute::_('index.php?option=com_project&task=projects.save'); ?>"
			  method="post" class="form-validate form-horizontal" enctype="multipart/form-data">

	<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />

	<?php echo $this->form->renderField('title'); ?>

	<?php echo $this->form->renderField('short_description'); ?>

	<?php echo $this->form->renderField('description'); ?>

	<?php echo $this->form->renderField('file_1'); ?>

				<?php if (!empty($this->item->file_1)) : ?>
					<?php $file_1Files = array(); ?>
					<?php foreach ((array)$this->item->file_1 as $fileSingle) : ?>
						<?php if (!is_array($fileSingle)) : ?>
							<a href="<?php echo JRoute::_(JUri::root() . 'media/upload' . DIRECTORY_SEPARATOR . $fileSingle, false);?>"><?php echo $fileSingle; ?></a> |
							<?php $file_1Files[] = $fileSingle; ?>
						<?php endif; ?>
					<?php endforeach; ?>
				<?php endif; ?>
				<input type="hidden" name="jform[file_1_hidden]" id="jform_file_1_hidden" value="<?php echo implode(',', $file_1Files); ?>" />
	<?php echo $this->form->renderField('file_2'); ?>

				<?php if (!empty($this->item->file_2)) : ?>
					<?php $file_2Files = array(); ?>
					<?php foreach ((array)$this->item->file_2 as $fileSingle) : ?>
						<?php if (!is_array($fileSingle)) : ?>
							<a href="<?php echo JRoute::_(JUri::root() . 'media/upload' . DIRECTORY_SEPARATOR . $fileSingle, false);?>"><?php echo $fileSingle; ?></a> |
							<?php $file_2Files[] = $fileSingle; ?>
						<?php endif; ?>
					<?php endforeach; ?>
				<?php endif; ?>
				<input type="hidden" name="jform[file_2_hidden]" id="jform_file_2_hidden" value="<?php echo implode(',', $file_2Files); ?>" />
	<?php echo $this->form->renderField('file_3'); ?>

				<?php if (!empty($this->item->file_3)) : ?>
					<?php $file_3Files = array(); ?>
					<?php foreach ((array)$this->item->file_3 as $fileSingle) : ?>
						<?php if (!is_array($fileSingle)) : ?>
							<a href="<?php echo JRoute::_(JUri::root() . 'media/upload' . DIRECTORY_SEPARATOR . $fileSingle, false);?>"><?php echo $fileSingle; ?></a> |
							<?php $file_3Files[] = $fileSingle; ?>
						<?php endif; ?>
					<?php endforeach; ?>
				<?php endif; ?>
				<input type="hidden" name="jform[file_3_hidden]" id="jform_file_3_hidden" value="<?php echo implode(',', $file_3Files); ?>" />
	<?php echo $this->form->renderField('file_4'); ?>

				<?php if (!empty($this->item->file_4)) : ?>
					<?php $file_4Files = array(); ?>
					<?php foreach ((array)$this->item->file_4 as $fileSingle) : ?>
						<?php if (!is_array($fileSingle)) : ?>
							<a href="<?php echo JRoute::_(JUri::root() . 'media/upload' . DIRECTORY_SEPARATOR . $fileSingle, false);?>"><?php echo $fileSingle; ?></a> |
							<?php $file_4Files[] = $fileSingle; ?>
						<?php endif; ?>
					<?php endforeach; ?>
				<?php endif; ?>
				<input type="hidden" name="jform[file_4_hidden]" id="jform_file_4_hidden" value="<?php echo implode(',', $file_4Files); ?>" />
	<?php echo $this->form->renderField('file_5'); ?>

				<?php if (!empty($this->item->file_5)) : ?>
					<?php $file_5Files = array(); ?>
					<?php foreach ((array)$this->item->file_5 as $fileSingle) : ?>
						<?php if (!is_array($fileSingle)) : ?>
							<a href="<?php echo JRoute::_(JUri::root() . 'media/upload' . DIRECTORY_SEPARATOR . $fileSingle, false);?>"><?php echo $fileSingle; ?></a> |
							<?php $file_5Files[] = $fileSingle; ?>
						<?php endif; ?>
					<?php endforeach; ?>
				<?php endif; ?>
				<input type="hidden" name="jform[file_5_hidden]" id="jform_file_5_hidden" value="<?php echo implode(',', $file_5Files); ?>" />
	<input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />

	<input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />

	<input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />

	<input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />

				<?php echo $this->form->getInput('created_by'); ?>
				<?php echo $this->form->getInput('modified_by'); ?>
				<?php echo $this->form->getInput('create_date'); ?>
				<?php echo $this->form->getInput('modified_date'); ?>				<div class="fltlft" <?php if (!JFactory::getUser()->authorise('core.admin','project')): ?> style="display:none;" <?php endif; ?> >
                <?php echo JHtml::_('sliders.start', 'permissions-sliders-'.$this->item->id, array('useCookie'=>1)); ?>
                <?php echo JHtml::_('sliders.panel', JText::_('ACL Configuration'), 'access-rules'); ?>
                <fieldset class="panelform">
                    <?php echo $this->form->getLabel('rules'); ?>
                    <?php echo $this->form->getInput('rules'); ?>
                </fieldset>
                <?php echo JHtml::_('sliders.end'); ?>
            </div>
				<?php if (!JFactory::getUser()->authorise('core.admin','project')): ?>
                <script type="text/javascript">
                    jQuery.noConflict();
                    jQuery('.tab-pane select').each(function(){
                       var option_selected = jQuery(this).find(':selected');
                       var input = document.createElement("input");
                       input.setAttribute("type", "hidden");
                       input.setAttribute("name", jQuery(this).attr('name'));
                       input.setAttribute("value", option_selected.val());
                       document.getElementById("form-projects").appendChild(input);
                    });
                </script>
             <?php endif; ?>
			<div class="control-group">
				<div class="controls">

					<?php if ($this->canSave): ?>
						<button type="submit" class="validate btn btn-primary">
							<?php echo JText::_('JSUBMIT'); ?>
						</button>
					<?php endif; ?>
					<a class="btn"
					   href="<?php echo JRoute::_('index.php?option=com_project&task=projectsform.cancel'); ?>"
					   title="<?php echo JText::_('JCANCEL'); ?>">
						<?php echo JText::_('JCANCEL'); ?>
					</a>
				</div>
			</div>

			<input type="hidden" name="option" value="com_project"/>
			<input type="hidden" name="task"
				   value="projectsform.save"/>
			<?php echo JHtml::_('form.token'); ?>
		</form>
	<?php endif; ?>
</div>
