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

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.keepalive');

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet(JUri::root() . 'media/com_project/css/form.css');
?>
<script type="text/javascript">
    js = jQuery.noConflict();
    js(document).ready(function () {

    });

    Joomla.submitbutton = function (task) {
        if (task == 'projects.cancel') {
            Joomla.submitform(task, document.getElementById('projects-form'));
        } else {

            if (task != 'projects.cancel' && document.formvalidator.isValid(document.id('projects-form'))) {

                Joomla.submitform(task, document.getElementById('projects-form'));
            } else {
                alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
            }
        }
    }
</script>

<form
        action="<?php echo JRoute::_('index.php?option=com_project&layout=edit&id=' . (int)$this->item->id); ?>"
        method="post" enctype="multipart/form-data" name="adminForm" id="projects-form" class="form-validate">

    <div class="form-horizontal">
        <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>

        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_PROJECT_TITLE_PROJECTS', true)); ?>
        <div class="row-fluid">
            <div class="span10 form-horizontal">
                <fieldset class="adminform">

                    <input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>"/>
                    <?php echo $this->form->renderField('title'); ?>
                    <?php echo $this->form->renderField('price'); ?>
                    <?php echo $this->form->renderField('short_description'); ?>
                    <?php echo $this->form->renderField('description'); ?>
                    <?php echo $this->form->renderField('is_recruitment'); ?>
                    <?php echo $this->form->renderField('file_1'); ?>



                    <?php if (!empty($this->item->file_1)) : ?>
                        <?php $file_1Files = array(); ?>
                        <?php foreach ((array)$this->item->file_1 as $fileSingle) : ?>
                            <?php if (!is_array($fileSingle)) : ?>
                                <a href="<?php echo JRoute::_(JUri::root() . 'media/upload' . DIRECTORY_SEPARATOR . $fileSingle, false); ?>"><?php echo $fileSingle; ?></a> |
                                <?php $file_1Files[] = $fileSingle; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <input type="hidden" name="jform[file_1_hidden]" id="jform_file_1_hidden"
                               value="<?php echo implode(',', $file_1Files); ?>"/>
                    <?php endif; ?>                <?php echo $this->form->renderField('file_2'); ?>

                    <?php if (!empty($this->item->file_2)) : ?>
                        <?php $file_2Files = array(); ?>
                        <?php foreach ((array)$this->item->file_2 as $fileSingle) : ?>
                            <?php if (!is_array($fileSingle)) : ?>
                                <a href="<?php echo JRoute::_(JUri::root() . 'media/upload' . DIRECTORY_SEPARATOR . $fileSingle, false); ?>"><?php echo $fileSingle; ?></a> |
                                <?php $file_2Files[] = $fileSingle; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <input type="hidden" name="jform[file_2_hidden]" id="jform_file_2_hidden"
                               value="<?php echo implode(',', $file_2Files); ?>"/>
                    <?php endif; ?>                <?php echo $this->form->renderField('file_3'); ?>

                    <?php if (!empty($this->item->file_3)) : ?>
                        <?php $file_3Files = array(); ?>
                        <?php foreach ((array)$this->item->file_3 as $fileSingle) : ?>
                            <?php if (!is_array($fileSingle)) : ?>
                                <a href="<?php echo JRoute::_(JUri::root() . 'media/upload' . DIRECTORY_SEPARATOR . $fileSingle, false); ?>"><?php echo $fileSingle; ?></a> |
                                <?php $file_3Files[] = $fileSingle; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <input type="hidden" name="jform[file_3_hidden]" id="jform_file_3_hidden"
                               value="<?php echo implode(',', $file_3Files); ?>"/>
                    <?php endif; ?>                <?php echo $this->form->renderField('file_4'); ?>

                    <?php if (!empty($this->item->file_4)) : ?>
                        <?php $file_4Files = array(); ?>
                        <?php foreach ((array)$this->item->file_4 as $fileSingle) : ?>
                            <?php if (!is_array($fileSingle)) : ?>
                                <a href="<?php echo JRoute::_(JUri::root() . 'media/upload' . DIRECTORY_SEPARATOR . $fileSingle, false); ?>"><?php echo $fileSingle; ?></a> |
                                <?php $file_4Files[] = $fileSingle; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <input type="hidden" name="jform[file_4_hidden]" id="jform_file_4_hidden"
                               value="<?php echo implode(',', $file_4Files); ?>"/>
                    <?php endif; ?>                <?php echo $this->form->renderField('file_5'); ?>

                    <?php if (!empty($this->item->file_5)) : ?>
                        <?php $file_5Files = array(); ?>
                        <?php foreach ((array)$this->item->file_5 as $fileSingle) : ?>
                            <?php if (!is_array($fileSingle)) : ?>
                                <a href="<?php echo JRoute::_(JUri::root() . 'media/upload' . DIRECTORY_SEPARATOR . $fileSingle, false); ?>"><?php echo $fileSingle; ?></a> |
                                <?php $file_5Files[] = $fileSingle; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <input type="hidden" name="jform[file_5_hidden]" id="jform_file_5_hidden"
                               value="<?php echo implode(',', $file_5Files); ?>"/>
                    <?php endif; ?> <input type="hidden" name="jform[ordering]"
                                           value="<?php echo $this->item->ordering; ?>"/>
                    <input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>"/>
                    <input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>"/>
                    <input type="hidden" name="jform[checked_out_time]"
                           value="<?php echo $this->item->checked_out_time; ?>"/>

                    <?php echo $this->form->renderField('created_by'); ?>
                    <?php echo $this->form->renderField('modified_by'); ?>
                    <?php echo $this->form->renderField('create_date'); ?>
                    <?php echo $this->form->renderField('modified_date'); ?>

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

        <?php if (JFactory::getUser()->authorise('core.admin', 'project')) : ?>
            <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'permissions', JText::_('JGLOBAL_ACTION_PERMISSIONS_LABEL', true)); ?>
            <?php echo $this->form->getInput('rules'); ?>
            <?php echo JHtml::_('bootstrap.endTab'); ?>
        <?php endif; ?>

        <?php echo JHtml::_('bootstrap.endTabSet'); ?>

        <input type="hidden" name="task" value=""/>
        <?php echo JHtml::_('form.token'); ?>

    </div>
</form>
