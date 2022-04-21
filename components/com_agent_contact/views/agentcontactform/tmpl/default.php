<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Agent_contact
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

HTMLHelper::_('behavior.keepalive');
HTMLHelper::_('behavior.tooltip');
HTMLHelper::_('behavior.formvalidation');
HTMLHelper::_('formbehavior.chosen', 'select');

// Load admin language file
$lang = Factory::getLanguage();
$lang->load('com_agent_contact', JPATH_SITE);
$doc = Factory::getDocument();
$doc->addScript(Uri::base() . '/media/com_agent_contact/js/form.js');

$user    = Factory::getUser();
$canEdit = Agent_contactHelpersAgent_contact::canUserEdit($this->item, $user);


?>
<style>
.control-group .controls input{
	width:60%;
}
</style>
<h3><?php
  $active = JFactory::getApplication()->getMenu()->getActive();
echo $active->title;
 ?></h3>
<div class="agentcontact-edit front-end-edit">
	<?php if (!$canEdit) : ?>
		<h3>
			<?php throw new Exception(Text::_('COM_AGENT_CONTACT_ERROR_MESSAGE_NOT_AUTHORISED'), 403); ?>
		</h3>
	<?php else : ?>
		<!-- <?php if (!empty($this->item->id)): ?>
			<h1><?php echo Text::sprintf('COM_AGENT_CONTACT_EDIT_ITEM_TITLE', $this->item->id); ?></h1>
		<?php else: ?>
			<h1><?php echo Text::_('COM_AGENT_CONTACT_ADD_ITEM_TITLE'); ?></h1>
		<?php endif; ?> -->

		<form id="form-agentcontact"
			  action="<?php echo Route::_('index.php?option=com_agent_contact&task=agentcontact.save'); ?>"
			  method="post" class="form-validate form-horizontal" enctype="multipart/form-data">

	<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />

	<input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />

	<input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />

	<input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />

	<input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />

				<?php echo $this->form->getInput('created_by'); ?>
				<?php echo $this->form->getInput('modified_by'); ?>
	<?php echo $this->form->renderField('phone'); ?>

	<?php echo $this->form->renderField('email'); ?>

	<?php echo $this->form->renderField('address'); ?>

	<?php echo $this->form->renderField('facebookpage'); ?>

	<?php echo $this->form->renderField('youtubepage'); ?>
				<div class="fltlft" <?php if (!JFactory::getUser()->authorise('core.admin','agent_contact')): ?> style="display:none;" <?php endif; ?> >
                <?php echo JHtml::_('sliders.start', 'permissions-sliders-'.$this->item->id, array('useCookie'=>1)); ?>
                <?php echo JHtml::_('sliders.panel', JText::_('ACL Configuration'), 'access-rules'); ?>
                <fieldset class="panelform">
                    <?php echo $this->form->getLabel('rules'); ?>
                    <?php echo $this->form->getInput('rules'); ?>
                </fieldset>
                <?php echo JHtml::_('sliders.end'); ?>
            </div>
				<?php if (!JFactory::getUser()->authorise('core.admin','agent_contact')): ?>
                <script type="text/javascript">
                    jQuery.noConflict();
                    jQuery('.tab-pane select').each(function(){
                       var option_selected = jQuery(this).find(':selected');
                       var input = document.createElement("input");
                       input.setAttribute("type", "hidden");
                       input.setAttribute("name", jQuery(this).attr('name'));
                       input.setAttribute("value", option_selected.val());
                       document.getElementById("form-agentcontact").appendChild(input);
                    });
                </script>
             <?php endif; ?>
			<div class="control-group agentcontactform">
				<div class="controls">

					<?php if ($this->canSave): ?>
						<button type="submit" class="validate btn btn-primary">
							<?php echo Text::_('JSUBMIT'); ?>
						</button>
					<?php endif; ?>
					<!-- <a class="btn"
					   href="<?php echo Route::_('index.php?option=com_agent_contact&task=agentcontactform.cancel'); ?>"
					   title="<?php echo Text::_('JCANCEL'); ?>">
						<?php echo Text::_('JCANCEL'); ?>
					</a> -->
				</div>
			</div>

			<input type="hidden" name="option" value="com_agent_contact"/>
			<input type="hidden" name="task"
				   value="agentcontactform.save"/>
			<?php echo HTMLHelper::_('form.token'); ?>
		</form>
	<?php endif; ?>
</div>
