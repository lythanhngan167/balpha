<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Request_package
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

//fix searching
HTMLHelper::_('formbehavior.chosen', 'select');

// Load admin language file
$lang = Factory::getLanguage();
$lang->load('com_request_package', JPATH_SITE);
$doc = Factory::getDocument();
$doc->addScript(Uri::base() . '/media/com_request_package/js/form.js');

$user    = Factory::getUser();
$canEdit = Request_packageHelpersRequest_package::canUserEdit($this->item, $user);


?>

<div class="requestpackage-edit front-end-edit">
	<div class="requestpackage-icon">
	<i class="fa fa-life-ring" aria-hidden="true" style="font-size:24px;color:#EE7D30"></i>
	</div>
	<div class="requestpackage-title">
	<?php if (!$canEdit) : ?>
		<h3>
			<?php throw new Exception(Text::_('COM_REQUEST_PACKAGE_ERROR_MESSAGE_NOT_AUTHORISED'), 403); ?>
		</h3>
	<?php else : ?>
		<?php if (!empty($this->item->id)): ?>
			<h1><?php echo Text::sprintf('COM_REQUEST_PACKAGE_EDIT_ITEM_TITLE', $this->item->id); ?></h1>
		<?php else: ?>
			<h1><?php echo Text::_('COM_REQUEST_PACKAGE_ADD_ITEM_TITLE'); ?></h1>
		<?php endif; ?>
	</div>
	<div class="requestpackage-shortdesc">Đã có 5000+ khách hàng gửi yêu cầu tư vấn và đã được chúng tôi phục vụ.</div>
	<form id="form-requestpackage"
			  action="<?php echo Route::_('index.php?option=com_request_package&task=requestpackage.save'); ?>"
			  method="post" class="form-validate form-horizontal" enctype="multipart/form-data">

	<input type="hidden" name="jform[id]" value="<?php echo isset($this->item->id) ? $this->item->id : ''; ?>" />

	<input type="hidden" name="jform[ordering]" value="<?php echo isset($this->item->ordering) ? $this->item->ordering : ''; ?>" />

	<input type="hidden" name="jform[state]" value="<?php echo isset($this->item->state) ? $this->item->state : ''; ?>" />

	<input type="hidden" name="jform[checked_out]" value="<?php echo isset($this->item->checked_out) ? $this->item->checked_out : ''; ?>" />

	<input type="hidden" name="jform[checked_out_time]" value="<?php echo isset($this->item->checked_out_time) ? $this->item->checked_out_time : ''; ?>" />

				<?php echo $this->form->getInput('created_by'); ?>
				<?php echo $this->form->getInput('modified_by'); ?>

	<div class="requestpackage-col form-service"><?php echo $this->form->renderField('services'); ?></div>
	<div class="requestpackage-col form-company"><?php echo $this->form->renderField('company'); ?></div>
	<div class="requestpackage-col form-name"><?php echo $this->form->renderField('name'); ?></div>

	<div class="requestpackage-col form-email"><?php echo $this->form->renderField('email'); ?></div>

	<div class="requestpackage-col form-phone"><?php echo $this->form->renderField('phone'); ?></div>

	<div class="hidden-request">
		<?php echo $this->form->renderField('job'); ?>
	</div>

	<div class="requestpackage-col form-address"><?php echo $this->form->renderField('address'); ?></div>

	<div class="requestpackage-col form-note"><?php echo $this->form->renderField('note'); ?></div>

	<div class="requestpackage-col form-province"><?php echo $this->form->renderField('province'); ?></div>
<div class="hidden-request">
	<?php echo $this->form->renderField('status'); ?>
</div>



				<div class="fltlft" <?php if (!JFactory::getUser()->authorise('core.admin','request_package')): ?> style="display:none;" <?php endif; ?> >
                <?php echo JHtml::_('sliders.start', 'permissions-sliders-'.$this->item->id, array('useCookie'=>1)); ?>
                <?php echo JHtml::_('sliders.panel', JText::_('ACL Configuration'), 'access-rules'); ?>
                <fieldset class="panelform">
                    <?php echo $this->form->getLabel('rules'); ?>
                    <?php echo $this->form->getInput('rules'); ?>
                </fieldset>
                <?php echo JHtml::_('sliders.end'); ?>
            </div>
				<?php if (!JFactory::getUser()->authorise('core.admin','request_package')): ?>
                <script type="text/javascript">
                    jQuery.noConflict();
                    jQuery('.tab-pane select').each(function(){
                       var option_selected = jQuery(this).find(':selected');
                       var input = document.createElement("input");
                       input.setAttribute("type", "hidden");
                       input.setAttribute("name", jQuery(this).attr('name'));
                       input.setAttribute("value", option_selected.val());
                       document.getElementById("form-requestpackage").appendChild(input);
                    });
                </script>
             <?php endif; ?>
			<div class="control-group requestpackage-submit">
				<div class="controls">

					<?php if ($this->canSave): ?>
						<button type="submit" class="validate btn btn-primary">
							<?php echo Text::_('JSUBMIT'); ?>
						</button>
					<?php endif; ?>
					<a class="btn"
					   href="<?php echo Route::_('index.php?option=com_request_package&task=requestpackageform.cancel'); ?>"
					   title="<?php echo Text::_('JCANCEL'); ?>">
						<?php echo Text::_('JCANCEL'); ?>
					</a>
				</div>
			</div>

			<input type="hidden" name="option" value="com_request_package"/>
			<input type="hidden" name="task"
				   value="requestpackageform.save"/>
			<?php echo HTMLHelper::_('form.token'); ?>
		</form>
	<?php endif; ?>
</div>
<style>
.hidden-request{
	display:none;
}
</style>
