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

HTMLHelper::_('behavior.keepalive');
HTMLHelper::_('behavior.tooltip');
HTMLHelper::_('behavior.formvalidation');
HTMLHelper::_('formbehavior.chosen', 'select');

// Load admin language file
$lang = Factory::getLanguage();
$lang->load('com_agent_images', JPATH_SITE);
$doc = Factory::getDocument();
$doc->addScript(Uri::base() . '/media/com_agent_images/js/form.js');

$user    = Factory::getUser();
$canEdit = Agent_imagesHelpersAgent_images::canUserEdit($this->item, $user);


?>
<h3><?php
  $active = JFactory::getApplication()->getMenu()->getActive();
echo $active->title;
 ?></h3>
<div class="agentimage-edit front-end-edit">
	<?php if (!$canEdit) : ?>
		<h3>
			<?php throw new Exception(Text::_('COM_AGENT_IMAGES_ERROR_MESSAGE_NOT_AUTHORISED'), 403); ?>
		</h3>
	<?php else : ?>
		<!-- <?php if (!empty($this->item->id)): ?>
			<h1><?php echo Text::sprintf('COM_AGENT_IMAGES_EDIT_ITEM_TITLE', $this->item->id); ?></h1>
		<?php else: ?>
			<h1><?php echo Text::_('COM_AGENT_IMAGES_ADD_ITEM_TITLE'); ?></h1>
		<?php endif; ?> -->

		<form id="form-agentimage"
			  action="<?php echo Route::_('index.php?option=com_agent_images&task=agentimage.save'); ?>"
			  method="post" class="form-validate form-horizontal" enctype="multipart/form-data">

	<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />

	<input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />

	<input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />

	<input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />

	<input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />

				<?php echo $this->form->getInput('created_by'); ?>
				<?php echo $this->form->getInput('modified_by'); ?>
        <h4>Hình ảnh đội nhóm</h4>
	<div class="group-image"><?php echo $this->form->renderField('image1'); ?></div>
	<div class="control-group">
			<div class="control-label">
			</div>
			<div class="controls">
				<?php if (!empty($this->item->image1)) : ?>
					<?php $image1Files = array(); ?>
					<?php foreach ((array)$this->item->image1 as $fileSingle) : ?>
						<?php if (!is_array($fileSingle)) : ?>
							<a target="_blank" href="<?php echo JRoute::_(JUri::root() . 'images/landingpage' . DIRECTORY_SEPARATOR . $fileSingle, false);?>">
								<img width="150" src="<?php echo JUri::root() . 'images/landingpage' . DIRECTORY_SEPARATOR . $fileSingle; ?>" />
								<?php //echo $fileSingle; ?>
							</a>
              <button type="button" onclick="deleteImage(<?php echo $this->item->id; ?>,'image1');" class="btn ">
                <i class="fa fa-trash" aria-hidden="true"></i> Xoá ảnh
              </button>
							<?php $image1Files[] = $fileSingle; ?>
						<?php endif; ?>
					<?php endforeach; ?>
				<input type="hidden" name="jform[image1_hidden]" id="jform_image1_hidden" value="<?php echo implode(',', $image1Files); ?>" />
				<?php endif; ?>
			</div>
	</div>

	<div class="group-image"><?php echo $this->form->renderField('image2'); ?></div>
	<div class="control-group">
			<div class="control-label">
			</div>
			<div class="controls">
				<?php if (!empty($this->item->image2)) : ?>
					<?php $image2Files = array(); ?>
					<?php foreach ((array)$this->item->image2 as $fileSingle) : ?>
						<?php if (!is_array($fileSingle)) : ?>
							<a target="_blank" href="<?php echo JRoute::_(JUri::root() . 'images/landingpage' . DIRECTORY_SEPARATOR . $fileSingle, false);?>">
								<img width="150" src="<?php echo JUri::root() . 'images/landingpage' . DIRECTORY_SEPARATOR . $fileSingle; ?>" />
								<?php //echo $fileSingle; ?>
							</a>
              <button type="button" onclick="deleteImage(<?php echo $this->item->id; ?>,'image2');" class="btn ">
                <i class="fa fa-trash" aria-hidden="true"></i> Xoá ảnh
              </button>
							<?php $image2Files[] = $fileSingle; ?>
						<?php endif; ?>
					<?php endforeach; ?>
				<input type="hidden" name="jform[image2_hidden]" id="jform_image2_hidden" value="<?php echo implode(',', $image2Files); ?>" />
				<?php endif; ?>
			</div>
	</div>
	<div class="group-image"><?php echo $this->form->renderField('image3'); ?></div>
	<div class="control-group">
			<div class="control-label">
			</div>
			<div class="controls">
				<?php if (!empty($this->item->image3)) : ?>
					<?php $image3Files = array(); ?>
					<?php foreach ((array)$this->item->image3 as $fileSingle) : ?>
						<?php if (!is_array($fileSingle)) : ?>
							<a target="_blank" href="<?php echo JRoute::_(JUri::root() . 'images/landingpage' . DIRECTORY_SEPARATOR . $fileSingle, false);?>">
								<img width="150" src="<?php echo JUri::root() . 'images/landingpage' . DIRECTORY_SEPARATOR . $fileSingle; ?>" />
								<?php //echo $fileSingle; ?>
							</a>
              <button type="button" onclick="deleteImage(<?php echo $this->item->id; ?>,'image3');" class="btn ">
                <i class="fa fa-trash" aria-hidden="true"></i> Xoá ảnh
              </button>
							<?php $image3Files[] = $fileSingle; ?>
						<?php endif; ?>
					<?php endforeach; ?>
				<input type="hidden" name="jform[image3_hidden]" id="jform_image3_hidden" value="<?php echo implode(',', $image3Files); ?>" />
				<?php endif; ?>
			</div>
	</div>
	<div class="group-image"><?php echo $this->form->renderField('image4'); ?></div>
	<div class="control-group">
			<div class="control-label">
			</div>
			<div class="controls">
				<?php if (!empty($this->item->image4)) : ?>
					<?php $image4Files = array(); ?>
					<?php foreach ((array)$this->item->image4 as $fileSingle) : ?>
						<?php if (!is_array($fileSingle)) : ?>
							<a target="_blank" href="<?php echo JRoute::_(JUri::root() . 'images/landingpage' . DIRECTORY_SEPARATOR . $fileSingle, false);?>">
								<img width="150" src="<?php echo JUri::root() . 'images/landingpage' . DIRECTORY_SEPARATOR . $fileSingle; ?>" />
								<?php //echo $fileSingle; ?>
							</a>
              <button type="button" onclick="deleteImage(<?php echo $this->item->id; ?>,'image4');" class="btn ">
                <i class="fa fa-trash" aria-hidden="true"></i> Xoá ảnh
              </button>
							<?php $image4Files[] = $fileSingle; ?>
						<?php endif; ?>
					<?php endforeach; ?>
				<input type="hidden" name="jform[image4_hidden]" id="jform_image4_hidden" value="<?php echo implode(',', $image4Files); ?>" />
				<?php endif; ?>
			</div>
	</div>
  <h4>Banner WorkShop</h4>
  <div class="image5">
	<div class="group-image"><?php echo $this->form->renderField('image5'); ?></div>
	<div class="control-group">
			<div class="control-label">
			</div>
			<div class="controls">
				<?php if (!empty($this->item->image5)) : ?>
					<?php $image5Files = array(); ?>
					<?php foreach ((array)$this->item->image5 as $fileSingle) : ?>
						<?php if (!is_array($fileSingle)) : ?>
							<a target="_blank" href="<?php echo JRoute::_(JUri::root() . 'images/landingpage' . DIRECTORY_SEPARATOR . $fileSingle, false);?>">
								<img width="150" src="<?php echo JUri::root() . 'images/landingpage' . DIRECTORY_SEPARATOR . $fileSingle; ?>" />
								<?php //echo $fileSingle; ?>
							</a>


							<?php $image5Files[] = $fileSingle; ?>
						<?php endif; ?>
					<?php endforeach; ?>
				<input type="hidden" name="jform[image5_hidden]" id="jform_image5_hidden" value="<?php echo implode(',', $image5Files); ?>" />
				<?php endif; ?>
			</div>
	</div>
</div>

				<div class="fltlft" <?php if (!JFactory::getUser()->authorise('core.admin','agent_images')): ?> style="display:none;" <?php endif; ?> >
                <?php echo JHtml::_('sliders.start', 'permissions-sliders-'.$this->item->id, array('useCookie'=>1)); ?>
                <?php echo JHtml::_('sliders.panel', JText::_('ACL Configuration'), 'access-rules'); ?>
                <fieldset class="panelform">
                    <?php echo $this->form->getLabel('rules'); ?>
                    <?php echo $this->form->getInput('rules'); ?>
                </fieldset>
                <?php echo JHtml::_('sliders.end'); ?>
            </div>
				<?php if (!JFactory::getUser()->authorise('core.admin','agent_images')): ?>
                <script type="text/javascript">
                    jQuery.noConflict();
                    jQuery('.tab-pane select').each(function(){
                       var option_selected = jQuery(this).find(':selected');
                       var input = document.createElement("input");
                       input.setAttribute("type", "hidden");
                       input.setAttribute("name", jQuery(this).attr('name'));
                       input.setAttribute("value", option_selected.val());
                       document.getElementById("form-agentimage").appendChild(input);
                    });
                </script>
             <?php endif; ?>
			<div class="control-group agentimageform">
				<div class="controls">

					<?php if ($this->canSave): ?>
						<button type="submit" class="validate btn btn-primary">
							<?php echo Text::_('JSUBMIT'); ?>
						</button>
					<?php endif; ?>
					<!-- <a class="btn"
					   href="<?php echo Route::_('index.php?option=com_agent_images&task=agentimageform.cancel'); ?>"
					   title="<?php echo Text::_('JCANCEL'); ?>">
						<?php echo Text::_('JCANCEL'); ?>
					</a> -->
				</div>
			</div>

			<input type="hidden" name="option" value="com_agent_images"/>
			<input type="hidden" name="task"
				   value="agentimageform.save"/>
			<?php echo HTMLHelper::_('form.token'); ?>
		</form>
	<?php endif; ?>
</div>

<script>
jQuery(document).ready(function(){
	jQuery('.group-image input').removeAttr('multiple');
});

function deleteImage(id,image){
	var r = confirm("Bạn có chắc muốn xóa ảnh này?");
	if (r == true) {
		jQuery.ajax({url: "<?php echo JUri::base(); ?>index.php?option=com_agent_images&task=agentimageform.deleteImage&id=" + id + "&image="+image, success: function (result) {
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
