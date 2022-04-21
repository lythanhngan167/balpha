<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$listOrder	= $data->state->get('list.ordering');
$listDirn	= $data->state->get('list.direction');
?>
<div class="row-fluid joomprofile-grid-header">
	<div class="row-fluid">
		<h3><i class="fa fa-user"></i> <?php echo JText::_('COM_JOOMPROFILE_PROFILE');?> > <?php echo JText::_('COM_JOOMPROFILE_FIELDS');?></h3>
	</div>

	<div class="row-fluid joomprofile-filter-block">
			<div class="filter-search btn-group pull-left">
				<form id="joomprofile-filter-form" class="form-search" action="index.php?option=com_joomprofile&view=profile&task=field.grid" method="POST">
					<div class="input-append">
						<input type="text" placeholder="<?php echo JText::_('COM_JOOMPROFILE_FIELDGROUP_FILTER_SEARCH');?>"
								class="input-xlarge search-query" name="filter_search" value="<?php echo $data->state->get('filter.search');?>">
						<button type="button" class="btn" onclick="joomprofile.jQuery('input[name=\'filter_search\']').val(''); joomprofile.jQuery('#joomprofile-filter-form').submit(); return true;">
							<i class="fa fa-times"> </i>
						</button>
					</div>
					<?php echo JHtml::_('form.token'); ?>
				</form>
			</div>

			<div class="pull-right">
			<?php
				// TODO : use in base code
				$this->set('ext_name', 'field');
				echo $this->render('admin.profile.menu.grid');
			?>
		</div>
	</div>
</div>

<?php if(empty($data->records)):?>
	<div class="well">
		<h1><?php echo JText::_('COM_JOOMPROFILE_NO_RECORDS_FOUND');?></h1>
		<h3><?php echo JText::_('COM_JOOMPROFILE_EITHER_CREATE_OR_SEARCH_AGAIN');?></h3>
	</div>
	<?php return;?>
<?php endif;?>

<div class="row-fluid joomprofile-grid">
	<form id="adminForm" name="adminForm" action="index.php?option=com_joomprofile&view=profile" method="POST">
		<table class="table table-hover">
			<thead>
				<tr>
					<th width="1%"><input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" /></th>
	                <th>
	                	<?php echo JHtml::_('grid.sort', 'COM_JOOMPROFILE_TITLE', 'title', $listDirn, $listOrder); ?>
	                </th>
    				<th><?php echo JText::_('COM_JOOMPROFILE_TOOLTIP');?></th>
                	<th class="pull-right">
                		<?php echo JHtml::_('grid.sort', 'COM_JOOMPROFILE_STATUS', 'published', $listDirn, $listOrder); ?>
                	</th>
            	</tr>
			</thead>
			<tbody>
				<?php $counter = 0; ?>
				<?php foreach($data->records as $record):?>
				<tr>
					<td><?php echo JHtml::_('grid.id', $counter, $record->id); ?></td>
					<td class="span4">
						<a href="index.php?option=com_joomprofile&view=profile&task=field.edit&id=<?php echo $record->id;?>">
							<?php echo $record->title;?>
						</a>
							<?php if(isset($data->fieldgroup_mapping[$record->id])):?>
								<br/>
								<?php $field_group_titles = array();?>
								<?php foreach($data->fieldgroup_mapping[$record->id] as $group_id):?>
									<?php $field_group_titles[] = $data->fieldgroups[$group_id]->title;?>
								<?php endforeach;?>

								<?php echo implode(', ', $field_group_titles);?>

							<?php endif;?>

					</td>
					<td class="span6">
						<div><?php echo $record->tooltip;?></div>
					</td>
					<td>
						<div class="pull-right">
							<?php if($record->published):?>
								<span class="badge badge-success"><?php echo JText::_('COM_JOOMPROFILE_PUBLISHED');?></span>
							<?php else:?>
								<span class="badge badge-important"><?php echo JText::_('COM_JOOMPROFILE_UNPUBLISHED');?></span>
							<?php endif;?>
						</div>
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

		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<input type="hidden" name="task" value="field.grid" />
		<?php echo JHtml::_('form.token'); ?>
	</form>
</div>
<?php
