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
$user   = JFactory::getUser();
$groups = $user->get('groups');
$gr = 0;

foreach ($groups as $group)
{
    $gr = $group;
}
if($gr != 8){ ?>
	<style>
		#joomprofile-filter-form .btn-large{
			display:none;
		}
	</style>
<?php }
?>
<script src="../media/com_joomprofile/js/joomprofile.js"></script>

<div class="row-fluid joomprofile-grid-header">
	<div class="row-fluid">
		<h3><i class="fa fa-user"></i> Hồ sơ Tư vấn viên<?php //echo JText::_('COM_JOOMPROFILE_PROFILE');?> : Người dùng<?php //echo JText::_('COM_JOOMPROFILE_USERS');?></h3>
	</div>

	<div class="row-fluid joomprofile-filter-block">
		<div class="filter-search">
			<div class="row-fluid">
			<form id="joomprofile-filter-form" class="form-search" action="index.php?option=com_joomprofile&view=profile&task=user.grid" method="POST">
				<div class="span4">
				<div class="input-append">
					<input type="text" placeholder="Tên đăng nhập, Tên và Email<?php //echo JText::_('COM_JOOMPROFILE_USER_FILTER_SEARCH');?>"
							class="input-xlarge search-query" name="filter_search" value="<?php echo $data->state->get('filter.search');?>">
					<button type="button" class="btn" onclick="joomprofile.jQuery('input[name=\'filter_search\']').val(''); joomprofile.jQuery('#joomprofile-filter-form').submit(); return true;">
						<i class="fa fa-times"> </i>
					</button>
				</div>
				</div>

				<div class="span3">
				<?php $usergroups = $data->groupnames;?>
				<?php $filteredUsergroup = $data->state->get('filter.usergroup');?>
				<select onchange="this.form.submit()" class="chosen" id="filter_usergroup" name="filter_usergroup">
					<option value="">- Chọn nhóm<?php //echo JText::_('COM_JOOMPROFILE_USERGROUPS');?> -</option>
					<?php foreach ($usergroups as $usergroup):?>
						<option <?php echo ($filteredUsergroup == $usergroup->id) ? 'selected="selected"' : '';?> value="<?php echo $usergroup->id;?>">
							<?php echo $usergroup->title;?>
						</option>
					<?php endforeach;?>
				</select>
				</div>

				<div class="btn-group pull-right">
					<a class="btn btn-large btn-info f90-flat" href="index.php?option=com_joomprofile&view=profile&task=user.export&<?php echo JSession::getFormToken()?>=1">
						<i class="fa fa-download"></i>
						<?php echo JText::_('Xuất File');?>
					</a>
				</div>
			</form>
			</div>
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
				 	<th>
	                	<?php echo JHtml::_('grid.sort', 'id', 'id', $listDirn, $listOrder); ?>
	                </th>
	                <th>
	                	<?php echo JHtml::_('grid.sort', 'Tên', 'name', $listDirn, $listOrder); ?>
	                </th>
	                <th>
	                	<?php echo JHtml::_('grid.sort', 'Tên đăng nhập', 'username', $listDirn, $listOrder); ?></th>
	                <th><?php echo JHtml::_('grid.sort', 'Email', 'email', $listDirn, $listOrder); ?></th>
	                <th><?php echo JHtml::_('grid.sort', 'Là Tư vấn viên', 'is_consultinger', $listDirn, $listOrder); ?></th>
	                <th><?php echo JText::_('Nhóm');?></th>
	                <th class="pull-right"><?php echo JHtml::_('grid.sort', 'Trạng thái', 'block', $listDirn, $listOrder); ?></th>
	            </tr>
			</thead>
			<tbody>
				<?php $counter = 0; ?>
				<?php $data->records = array_values($data->records);?>
				<?php foreach($data->records as $record):?>
				<tr>
					<td class="">
						<?php echo $record->id;?>
					</td>
					<td class="">
						<a href="index.php?option=com_joomprofile&view=profile&task=user.edit&id=<?php echo $record->id;?>">
						<?php echo $record->name;?></a>
					</td>
					<td class="">
						<div><?php echo $record->username;?></div>
					</td>
					<td class="">
						<div><?php echo $record->email;?></div>
					</td>
					<td class="">
						<div style="text-align:center;">
							<?php foreach($data->usergroups[$record->id] as $usergroup):?>
								<?php if($usergroup->id ==3 ){
									if ($record->is_consultinger == 1): ?>

										<a class="btn-f90pro btn btn-success" title="Tắt Tư vấn viên" href="#" onclick="joomprofile.saveisconsultinger(<?php echo $record->id; ?>,0,'<?php echo JURI::root(); ?>'); ">
											<i class="icon-ok"></i>
										</a>

									<?php endif;
								  if ($record->is_consultinger == 0): ?>
									<a class="btn-f90pro btn btn-eror" title="Bật Tư vấn viên" href="#" onclick="joomprofile.saveisconsultinger(<?php echo $record->id; ?>,1,'<?php echo JURI::root(); ?>');">
										<i class="icon-remove text-error"></i>
									</a>
									<?php endif;
								};?>

							<?php endforeach;?>


						</div>
					</td>
					<td class="">
						<div>
							<?php foreach($data->usergroups[$record->id] as $usergroup):?>
								<?php echo $usergroup->title;?><br/>
							<?php endforeach;?>
						</div>
					</td>
					<td class="span2">
						<div class="pull-right">
							<?php if(!$record->block):?>
								<span class="badge badge-success"><?php echo JText::_('KÍCH HOẠT');?></span>
							<?php else:?>
								<span class="badge badge-important"><?php echo JText::_('VÔ HIỆU HÓA');?></span>
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
		<input type="hidden" name="task" value="user.grid" />
		<?php echo JHtml::_('form.token'); ?>
	</form>
</div>
<style>
.icon-ok:before{
	color:#FFF;
}
</style>
<script>
	jQuery(document).ready(function() {
		// jQuery('#filter_usergroup option[value=1]').attr('selected',false);
		// jQuery('#filter_usergroup option[value="3"]').attr('selected','selected');
});

</script>
<?php
