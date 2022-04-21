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

$canEdit = JFactory::getUser()->authorise('core.edit', 'com_agent_intro.' . $this->item->id);

if (!$canEdit && JFactory::getUser()->authorise('core.edit.own', 'com_agent_intro' . $this->item->id))
{
	$canEdit = JFactory::getUser()->id == $this->item->created_by;
}
?>

<div class="item_fields">

	<table class="table">


		<tr>
			<th><?php echo JText::_('COM_AGENT_INTRO_FORM_LBL_AGENTINTRO_TITLE'); ?></th>
			<td><?php echo $this->item->title; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_AGENT_INTRO_FORM_LBL_AGENTINTRO_INTRO_TEXT'); ?></th>
			<td><?php echo nl2br($this->item->intro_text); ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_AGENT_INTRO_FORM_LBL_AGENTINTRO_IMAGE'); ?></th>
			<td>
			<?php
			foreach ((array) $this->item->image as $singleFile) :
				if (!is_array($singleFile)) :
					$uploadPath = 'images/landingpage' . DIRECTORY_SEPARATOR . $singleFile;
					 echo '<a href="' . JRoute::_(JUri::root() . $uploadPath, false) . '" target="_blank">' . $singleFile . '</a> ';
				endif;
			endforeach;
		?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_AGENT_INTRO_FORM_LBL_AGENTINTRO_YOUTUBE_VIDEO_URL'); ?></th>
			<td><?php echo $this->item->youtube_video_url; ?></td>
		</tr>

	</table>

</div>

<?php if($canEdit && $this->item->checked_out == 0): ?>

	<a class="btn" href="<?php echo JRoute::_('index.php?option=com_agent_intro&task=agentintro.edit&id='.$this->item->id); ?>"><?php echo JText::_("COM_AGENT_INTRO_EDIT_ITEM"); ?></a>

<?php endif; ?>

<?php if (JFactory::getUser()->authorise('core.delete','com_agent_intro.agentintro.'.$this->item->id)) : ?>

	<a class="btn btn-danger" href="#deleteModal" role="button" data-toggle="modal">
		<?php echo JText::_("COM_AGENT_INTRO_DELETE_ITEM"); ?>
	</a>

	<div id="deleteModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="deleteModal" aria-hidden="true">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h3><?php echo JText::_('COM_AGENT_INTRO_DELETE_ITEM'); ?></h3>
		</div>
		<div class="modal-body">
			<p><?php echo JText::sprintf('COM_AGENT_INTRO_DELETE_CONFIRM', $this->item->id); ?></p>
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal">Close</button>
			<a href="<?php echo JRoute::_('index.php?option=com_agent_intro&task=agentintro.remove&id=' . $this->item->id, false, 2); ?>" class="btn btn-danger">
				<?php echo JText::_('COM_AGENT_INTRO_DELETE_ITEM'); ?>
			</a>
		</div>
	</div>

<?php endif; ?>
