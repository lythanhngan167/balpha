<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>
<div class="row-fluid joomprofile-grid-header">
	<div class="row-fluid">
		<h3><i class="fa-lg fa fa-user"></i> <?php echo JText::_('COM_JOOMPROFILE_PROFILE');?> > <?php echo JText::_('COM_JOOMPROFILE_USERGROUP');?></h3>
	</div>

	<div class="row-fluid joomprofile-filter-block">
		<div class="filter-search btn-group pull-left">
		</div>
		<div class="pull-right">
		</div>
	</div>
</div>

<div class="row-fluid joomprofile-grid">
	<form id="adminForm" name="adminForm" action="index.php?option=com_joomprofile&view=profile" method="POST">
		<table class="table table-hover">
			<thead>
				<tr>
	                <th>
	                	<?php echo JText::_('COM_JOOMPROFILE_TITLE'); ?>
	                </th>
	            </tr>
			</thead>
			<tbody>
				<?php $counter = 0; ?>
				<?php $data->records = array_values($data->records);?>
				<?php foreach($data->records as $record):?>
				<tr>
					<td>
						<a href="index.php?option=com_joomprofile&view=profile&task=usergroup.edit&id=<?php echo $record->usergroup_id;?>">
						<?php echo $record->title;?></a>
					</td>
				</tr>
				<?php $counter++;?>
				<?php endforeach;?>
			</tbody>
		</table>

		<hr />
		<div class="row-fluid">
			<div class="pull-right"><?php echo $data->pagination->getLimitBox(); ?></div>
			<div class="center"><?php echo $data->pagination->getListFooter(); ?></div>
		</div>

		<input type="hidden" name="task" value="usergroup.grid" />
		<?php echo JHtml::_('form.token'); ?>

	</form>
</div>
<?php
