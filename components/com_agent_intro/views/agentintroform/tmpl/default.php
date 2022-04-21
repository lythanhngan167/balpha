<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Agent_intro
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
$lang->load('com_agent_intro', JPATH_SITE);
$doc = Factory::getDocument();
$doc->addScript(Uri::base() . '/media/com_agent_intro/js/form.js');

$user    = Factory::getUser();
$canEdit = Agent_introHelpersAgent_intro::canUserEdit($this->item, $user);


?>
<style>
#jform_intro_text{
	width:60%;
	height: 200px;
}
#jform_title,#jform_youtube_video_url{
	width:60%;
}
</style>
<h3><?php
  $active = JFactory::getApplication()->getMenu()->getActive();
echo $active->title;
 ?></h3>
<div class="agentintro-edit front-end-edit">
	<?php if (!$canEdit) : ?>
		<h3>
			<?php throw new Exception(Text::_('COM_AGENT_INTRO_ERROR_MESSAGE_NOT_AUTHORISED'), 403); ?>
		</h3>
	<?php else : ?>
		<!-- <?php if (!empty($this->item->id)): ?>
			<h1><?php echo Text::sprintf('COM_AGENT_INTRO_EDIT_ITEM_TITLE', $this->item->id); ?></h1>
		<?php else: ?>
			<h1><?php echo Text::_('COM_AGENT_INTRO_ADD_ITEM_TITLE'); ?></h1>
		<?php endif; ?> -->

		<form id="form-agentintro"
			  action="<?php echo Route::_('index.php?option=com_agent_intro&task=agentintro.save'); ?>"
			  method="post" class="form-validate form-horizontal" enctype="multipart/form-data">

	<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />

	<input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />

	<input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />

	<input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />

	<input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />

				<?php echo $this->form->getInput('created_by'); ?>
				<?php echo $this->form->getInput('modified_by'); ?>
	<?php echo $this->form->renderField('title'); ?>

	<?php echo $this->form->renderField('intro_text'); ?>
	<?php echo $this->form->renderField('youtube_video_url'); ?>
	<div id="imageintro">
		<?php echo $this->form->renderField('image'); ?>
	</div>
	<div class="control-group">
			<div class="control-label">

		</div>
		<div class="controls">
			<div class="image-intro">
					<?php if (!empty($this->item->image)) : ?>
						<?php $imageFiles = array(); ?>
						<?php foreach ((array)$this->item->image as $fileSingle) : ?>
							<?php if (!is_array($fileSingle)) : ?>
								<a target="_blank" href="<?php echo JRoute::_(JUri::root() . 'images/landingpage' . DIRECTORY_SEPARATOR . $fileSingle, false);?>">
									<img width="200" src="<?php echo JUri::root() . 'images/landingpage' . DIRECTORY_SEPARATOR . $fileSingle; ?>" />
									<?php //echo $fileSingle; ?></a>
									<!-- <input type="checkbox" value="1" id="delete_image" name="delete_image" />  -->

									<button type="button" onclick="deleteImage(<?php echo $this->item->id; ?>);" class="btn ">
										<i class="fa fa-trash" aria-hidden="true"></i> Xoá ảnh
									</button>
								<?php $imageFiles[] = $fileSingle; ?>

							<?php endif; ?>
						<?php endforeach; ?>
					<input type="hidden" name="jform[image_hidden]" id="jform_image_hidden" value="<?php echo implode(',', $imageFiles); ?>" />
					<?php endif; ?>
				</div>
		</div>
</div>

				<div class="fltlft" <?php if (!JFactory::getUser()->authorise('core.admin','agent_intro')): ?> style="display:none;" <?php endif; ?> >
                <?php echo JHtml::_('sliders.start', 'permissions-sliders-'.$this->item->id, array('useCookie'=>1)); ?>
                <?php echo JHtml::_('sliders.panel', JText::_('ACL Configuration'), 'access-rules'); ?>
                <fieldset class="panelform">
                    <?php echo $this->form->getLabel('rules'); ?>
                    <?php echo $this->form->getInput('rules'); ?>
                </fieldset>
                <?php echo JHtml::_('sliders.end'); ?>
            </div>
				<?php if (!JFactory::getUser()->authorise('core.admin','agent_intro')): ?>
                <script type="text/javascript">
                    jQuery.noConflict();
                    jQuery('.tab-pane select').each(function(){
                       var option_selected = jQuery(this).find(':selected');
                       var input = document.createElement("input");
                       input.setAttribute("type", "hidden");
                       input.setAttribute("name", jQuery(this).attr('name'));
                       input.setAttribute("value", option_selected.val());
                       document.getElementById("form-agentintro").appendChild(input);
                    });
                </script>
             <?php endif; ?>
			<div class="control-group agentintroform">
				<div class="controls">

					<?php if ($this->canSave): ?>
						<button type="submit" class="validate btn btn-primary">
							<?php
							echo Text::_('JSUBMIT'); ?>
						</button>
					<?php endif; ?>
					<!-- <a class="btn"
					   href="<?php echo Route::_('index.php?option=com_agent_intro&task=agentintroform.cancel'); ?>"
					   title="<?php echo Text::_('JCANCEL'); ?>">
						<?php echo Text::_('JCANCEL'); ?>
					</a> -->
				</div>
			</div>

			<input type="hidden" name="option" value="com_agent_intro"/>
			<input type="hidden" name="task"
				   value="agentintroform.save"/>
			<?php echo HTMLHelper::_('form.token'); ?>
		</form>
	<?php endif; ?>
</div>
<script>

jQuery(document).ready(function(){
	jQuery('#imageintro input').removeAttr('multiple');
});

function deleteImage(id){
	var r = confirm("Bạn có chắc muốn xóa ảnh này?");
	if (r == true) {
		jQuery.ajax({url: "<?php echo JUri::base(); ?>index.php?option=com_agent_intro&task=agentintroform.deleteImage&id=" + id, success: function (result) {
					if(result == '1'){
						alert("Xoá ảnh thành công!");
						location.reload();
					}
					if(result == '0'){
						alert("Xoá ảnh thất bại, vui lòng thử lại.");
						location.reload();
					}
			 }
		});
	} else {
	}

}

</script>
