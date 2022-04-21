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

$canEdit = JFactory::getUser()->authorise('core.edit', 'com_project.' . $this->item->id);

if (!$canEdit && JFactory::getUser()->authorise('core.edit.own', 'com_project' . $this->item->id))
{
	$canEdit = JFactory::getUser()->id == $this->item->created_by;
}
?>
<h3><?php echo $this->item->title; ?> </h3>
<div class="item_fields">

	<table class="table">

		<tr>
			<th>Mã Dự án</th>
			<td>#<?php echo $this->item->id; ?></td>
		</tr>
		<tr>
			<th><?php echo JText::_('COM_PROJECT_FORM_LBL_PROJECTS_TITLE'); ?></th>
			<td><?php echo $this->item->title; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_PROJECT_FORM_LBL_PROJECTS_SHORT_DESCRIPTION'); ?></th>
			<td><?php echo nl2br($this->item->short_description); ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_PROJECT_FORM_LBL_PROJECTS_DESCRIPTION'); ?></th>
			<td><?php echo nl2br($this->item->description); ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_PROJECT_FORM_LBL_PROJECTS_FILE_1'); ?></th>
			<td>
			<?php
			foreach ((array) $this->item->file_1 as $singleFile) :
				if (!is_array($singleFile)) :
					$uploadPath = 'media/upload' . DIRECTORY_SEPARATOR . $singleFile;
					 echo '<a href="' . JRoute::_(JUri::root() . $uploadPath, false) . '" target="_blank">' . $singleFile . '</a> ';
				endif;
			endforeach;
		?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_PROJECT_FORM_LBL_PROJECTS_FILE_2'); ?></th>
			<td>
			<?php
			foreach ((array) $this->item->file_2 as $singleFile) :
				if (!is_array($singleFile)) :
					$uploadPath = 'media/upload' . DIRECTORY_SEPARATOR . $singleFile;
					 echo '<a href="' . JRoute::_(JUri::root() . $uploadPath, false) . '" target="_blank">' . $singleFile . '</a> ';
				endif;
			endforeach;
		?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_PROJECT_FORM_LBL_PROJECTS_FILE_3'); ?></th>
			<td>
			<?php
			foreach ((array) $this->item->file_3 as $singleFile) :
				if (!is_array($singleFile)) :
					$uploadPath = 'media/upload' . DIRECTORY_SEPARATOR . $singleFile;
					 echo '<a href="' . JRoute::_(JUri::root() . $uploadPath, false) . '" target="_blank">' . $singleFile . '</a> ';
				endif;
			endforeach;
		?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_PROJECT_FORM_LBL_PROJECTS_FILE_4'); ?></th>
			<td>
			<?php
			foreach ((array) $this->item->file_4 as $singleFile) :
				if (!is_array($singleFile)) :
					$uploadPath = 'media/upload' . DIRECTORY_SEPARATOR . $singleFile;
					 echo '<a href="' . JRoute::_(JUri::root() . $uploadPath, false) . '" target="_blank">' . $singleFile . '</a> ';
				endif;
			endforeach;
		?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_PROJECT_FORM_LBL_PROJECTS_FILE_5'); ?></th>
			<td>
			<?php
			foreach ((array) $this->item->file_5 as $singleFile) :
				if (!is_array($singleFile)) :
					$uploadPath = 'media/upload' . DIRECTORY_SEPARATOR . $singleFile;
					 echo '<a href="' . JRoute::_(JUri::root() . $uploadPath, false) . '" target="_blank">' . $singleFile . '</a> ';
				endif;
			endforeach;
		?></td>
		</tr>

	</table>

</div>

<?php if($canEdit && $this->item->checked_out == 0): ?>

	<a class="btn" href="<?php echo JRoute::_('index.php?option=com_project&task=projects.edit&id='.$this->item->id); ?>"><?php echo JText::_("COM_PROJECT_EDIT_ITEM"); ?></a>

<?php endif; ?>

<?php if (JFactory::getUser()->authorise('core.delete','com_project.projects.'.$this->item->id)) : ?>

	<a class="btn btn-danger" href="#deleteModal" role="button" data-toggle="modal">
		<?php echo JText::_("COM_PROJECT_DELETE_ITEM"); ?>
	</a>

	<div id="deleteModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="deleteModal" aria-hidden="true">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h3><?php echo JText::_('COM_PROJECT_DELETE_ITEM'); ?></h3>
		</div>
		<div class="modal-body">
			<p><?php echo JText::sprintf('COM_PROJECT_DELETE_CONFIRM', $this->item->id); ?></p>
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal">Close</button>
			<a href="<?php echo JRoute::_('index.php?option=com_project&task=projects.remove&id=' . $this->item->id, false, 2); ?>" class="btn btn-danger">
				<?php echo JText::_('COM_PROJECT_DELETE_ITEM'); ?>
			</a>
		</div>
	</div>

<?php endif; ?>
