<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Registration
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
$document->addStyleSheet(Uri::root() . 'media/com_registration/css/form.css');
?>
<script type="text/javascript">
	js = jQuery.noConflict();
	js(document).ready(function () {

	});

	Joomla.submitbutton = function (task) {
		if (task == 'registration.cancel') {
			Joomla.submitform(task, document.getElementById('registration-form'));
		}
		else {

			if (task != 'registration.cancel' && document.formvalidator.isValid(document.id('registration-form'))) {

				Joomla.submitform(task, document.getElementById('registration-form'));
			}
			else {
				alert('<?php echo $this->escape(Text::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
			}
		}
	}
</script>

<form
	action="<?php echo JRoute::_('index.php?option=com_registration&layout=edit&id=' . (int) $this->item->id); ?>"
	method="post" enctype="multipart/form-data" name="adminForm" id="registration-form" class="form-validate form-horizontal">

	<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />
	<input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />
	<input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />
	<input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />
	<input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />
	<?php echo $this->form->renderField('created_by'); ?>
	<?php echo $this->form->renderField('modified_by'); ?>
	<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'registration')); ?>
	<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'registration', JText::_('COM_REGISTRATION_TAB_REGISTRATION', true)); ?>
	<div class="row-fluid">
		<div class="span10 form-horizontal">
			<fieldset class="adminform">
				<legend><?php echo JText::_('COM_REGISTRATION_FIELDSET_REGISTRATION'); ?></legend>
				<?php if($this->item->created_by > 0) { ?>
				<div class="control-group">
						<div class="control-label"><label id="jform_name-lbl" for="jform_name" class="required">
					Tạo bởi</label>
						</div>
						<div class="controls">
							<?php $createdByUser = JFactory::getUser($this->item->created_by);
							echo "<a href='".Uri::base()."index.php?option=com_users&view=user&layout=edit&id=".$createdByUser->id."'>".$createdByUser->username."</a>"; echo " ( ".$createdByUser->name." )" ;
							?>
						</div>
				</div>
				<?php } ?>
				<?php echo $this->form->renderField('name'); ?>
				<?php echo $this->form->renderField('email'); ?>
				<?php echo $this->form->renderField('phone'); ?>
				<?php echo $this->form->renderField('job'); ?>
				<?php echo $this->form->renderField('address'); ?>
				<?php echo $this->form->renderField('note'); ?>
				<?php echo $this->form->renderField('province'); ?>

				<?php echo $this->form->renderField('utm_source'); ?>
				<?php echo $this->form->renderField('utm_sourceonly'); ?>
			  <?php echo $this->form->renderField('utm_mediumonly'); ?>
				<?php echo $this->form->renderField('utm_compainonly'); ?>
				<?php echo $this->form->renderField('from_landingpage'); ?>

				<?php echo $this->form->renderField('status'); ?>
				<?php echo $this->form->renderField('is_exist'); ?>
				<?php echo $this->form->renderField('againt_registration'); ?>
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

	<?php if (JFactory::getUser()->authorise('core.admin','registration')) : ?>
	<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'permissions', JText::_('JGLOBAL_ACTION_PERMISSIONS_LABEL', true)); ?>
		<?php echo $this->form->getInput('rules'); ?>
	<?php echo JHtml::_('bootstrap.endTab'); ?>
<?php endif; ?>
	<?php echo JHtml::_('bootstrap.endTabSet'); ?>

	<input type="hidden" name="task" value=""/>
	<?php echo JHtml::_('form.token'); ?>

</form>

<style>
<?php

$user   = JFactory::getUser();
$groups = $user->get('groups');
//print_r($groups); die;
$is_supper_admin = 0;
$is_admin = 0;
$is_admin_landingpage = 0;
$is_admin_user_importdata = 0;
foreach ($groups as $group)
{
		if($group == 8){
			$is_supper_admin = 1;
		}else{
			if($group == 7){
				$is_admin = 1;
			}else{
				if($group == 10){
					$is_admin_landingpage = 1;
				}else{
					if($group == 11){
						$is_admin_user_importdata = 1;
					}
				}
			}
		}
}
 ?>
 <?php if($is_admin_landingpage == 1 || $is_admin_user_importdata == 1){ ?>
#toolbar-apply,#toolbar-save{
  display: none;
}
<?php } ?>
</style>
