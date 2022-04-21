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

JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');

// Load admin language file
$lang = JFactory::getLanguage();
$lang->load('com_customer', JPATH_SITE);
$doc = JFactory::getDocument();
$doc->addScript(JUri::base() . '/media/com_customer/js/form.js');

$user    = JFactory::getUser();
$canEdit = CustomerHelpersCustomer::canUserEdit($this->item, $user);


?>

<div class="customer-edit front-end-edit">
	<?php if (!$canEdit) : ?>
		<h3>
			<?php throw new Exception(JText::_('COM_CUSTOMER_ERROR_MESSAGE_NOT_AUTHORISED'), 403); ?>
		</h3>
	<?php else : ?>
		<?php if (!empty($this->item->id)): ?>
			<h1><?php echo JText::sprintf('COM_CUSTOMER_EDIT_ITEM_TITLE', $this->item->id); ?></h1>
		<?php else: ?>
			<h1><?php echo JText::_('COM_CUSTOMER_ADD_ITEM_TITLE'); ?></h1>
		<?php endif; ?>

		<form id="form-customer"
			  action="<?php echo JRoute::_('index.php?option=com_customer&task=customer.save'); ?>"
			  method="post" class="form-validate form-horizontal" enctype="multipart/form-data">

	<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />

	<?php echo $this->form->renderField('name'); ?>

	<?php echo $this->form->renderField('phone'); ?>

	<?php echo $this->form->renderField('email'); ?>

	<?php echo $this->form->renderField('place'); ?>

	<input type="hidden" name="jform[sale_id]" value="<?php echo $this->item->sale_id; ?>" />

	<?php echo $this->form->renderField('category_id'); ?>

	<?php echo $this->form->renderField('project_id'); ?>

	<?php foreach((array)$this->item->project_id as $value): ?>
		<?php if(!is_array($value)): ?>
			<input type="hidden" class="project_id" name="jform[project_idhidden][<?php echo $value; ?>]" value="<?php echo $value; ?>" />';
		<?php endif; ?>
	<?php endforeach; ?>
	<div class="hidden_field" style="display:none;"><?php echo $this->form->renderField('status_id'); ?></div>

	<input type="hidden" name="jform[total_revenue]" value="<?php echo $this->item->total_revenue; ?>" />

	<input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />

	<input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />

	<input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />

	<input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />

				<?php echo $this->form->getInput('modified_date'); ?>
	<?php echo $this->form->renderField('created_by'); ?>

	<?php echo $this->form->renderField('modified_by'); ?>

				<?php echo $this->form->getInput('create_date'); ?>				<div class="fltlft" <?php if (!JFactory::getUser()->authorise('core.admin','customer')): ?> style="display:none;" <?php endif; ?> >
                <?php echo JHtml::_('sliders.start', 'permissions-sliders-'.$this->item->id, array('useCookie'=>1)); ?>
                <?php echo JHtml::_('sliders.panel', JText::_('ACL Configuration'), 'access-rules'); ?>
                <fieldset class="panelform">
                    <?php echo $this->form->getLabel('rules'); ?>
                    <?php echo $this->form->getInput('rules'); ?>
                </fieldset>
                <?php echo JHtml::_('sliders.end'); ?>
            </div>
				<?php if (!JFactory::getUser()->authorise('core.admin','customer')): ?>
                <script type="text/javascript">
                    jQuery.noConflict();
                    jQuery('.tab-pane select').each(function(){
                       var option_selected = jQuery(this).find(':selected');
                       var input = document.createElement("input");
                       input.setAttribute("type", "hidden");
                       input.setAttribute("name", jQuery(this).attr('name'));
                       input.setAttribute("value", option_selected.val());
                       document.getElementById("form-customer").appendChild(input);
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
					   href="<?php echo JRoute::_('index.php?option=com_customer&task=customerform.cancel'); ?>"
					   title="<?php echo JText::_('JCANCEL'); ?>">
						<?php echo JText::_('JCANCEL'); ?>
					</a>
				</div>
			</div>

			<input type="hidden" name="option" value="com_customer"/>
			<input type="hidden" name="task"
				   value="customerform.save"/>
			<?php echo JHtml::_('form.token'); ?>
		</form>
	<?php endif; ?>
</div>
<script>
jQuery( document ).ready(function() {

});
</script>
