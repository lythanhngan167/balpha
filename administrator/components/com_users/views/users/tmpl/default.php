<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');


$listOrder  = $this->escape($this->state->get('list.ordering'));
$listDirn   = $this->escape($this->state->get('list.direction'));
$loggeduser = JFactory::getUser();
$debugUsers = $this->state->get('params')->get('debugUsers', 1);
?>
<form action="<?php echo JRoute::_('index.php?option=com_users&view=users'); ?>" method="post" name="adminForm" id="adminForm">
	<?php if (!empty( $this->sidebar)) : ?>
		<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
		</div>
		<div id="j-main-container" class="span10">
	<?php else : ?>
		<div id="j-main-container">
	<?php endif; ?>

	<?php if (@$this->projectInfo): ?>
			<h3><?php echo $this->projectInfo['title']; ?> - Giá bán: <span class="text-danger"
																																			style="color: red;"><?php echo number_format($this->projectInfo['price'], 0, ",", "."); ?>  đ</span>
					- Khách hàng tồn: <?php echo number_format($this->projectInfo['remain_customer'], 0, ",", "."); ?>
			</h3>
	<?php endif; ?>
		<?php
		// Search tools bar
		echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));
		?>
		<?php if (empty($this->items)) : ?>
			<div class="alert alert-no-items">
				<?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
			</div>
		<?php else : ?>
			<table class="table table-striped" id="userList">
				<thead>
					<tr>
						<th width="1%" class="nowrap center">
							<?php echo JHtml::_('grid.checkall'); ?>
						</th>
						<th width="10%" class="nowrap">
							<?php echo JHtml::_('searchtools.sort', 'Mã TV', 'a.id', $listDirn, $listOrder); ?>
						</th>
						<!-- <th width="10%" class="nowrap">
							<?php echo JHtml::_('searchtools.sort', 'ID Biznet', 'a.id_biznet', $listDirn, $listOrder); ?>
						</th> -->
						<th width="10%" class="nowrap">
							<?php echo JHtml::_('searchtools.sort', 'COM_USERS_HEADING_NAME', 'a.name', $listDirn, $listOrder); ?>
						</th>
						<th width="10%" class="nowrap">
							<?php echo JHtml::_('searchtools.sort', 'JGLOBAL_USERNAME', 'a.username', $listDirn, $listOrder); ?>
						</th>
						<!-- <th width="10%" class="nowrap">
							<?php echo JHtml::_('searchtools.sort', 'JGLOBAL_BIZXU', 'a.money', $listDirn, $listOrder); ?>
						</th> -->
						<!-- <th width="5%" class="nowrap center">
								<?php echo JHtml::_('searchtools.sort', 'Landingpage', 'a.block_landingpage', $listDirn, $listOrder); ?>
						</th> -->
						<!-- <th width="10%" class="nowrap">
							<?php echo JHtml::_('searchtools.sort', 'Cấp Đại lý', 'a.level', $listDirn, $listOrder); ?>
						</th> -->
						<th width="5%" class="nowrap center">
							<?php echo JHtml::_('searchtools.sort', 'COM_USERS_HEADING_ENABLED', 'a.block', $listDirn, $listOrder); ?>
						</th>
						<!-- <th width="5%" class="nowrap center hidden-phone">
							<?php echo JHtml::_('searchtools.sort', 'COM_USERS_HEADING_ACTIVATED', 'a.activation', $listDirn, $listOrder); ?>
						</th> -->

						<th width="10%" class="nowrap">
							<?php echo JText::_('Nhóm TV'); ?>
						</th>
						<th width="10%" class="nowrap">
							<?php echo JHtml::_('searchtools.sort', 'JGLOBAL_EMAIL', 'a.email', $listDirn, $listOrder); ?>
						</th>
						<!-- <th width="10%" class="nowrap hidden-phone hidden-tablet">
							<?php echo JHtml::_('searchtools.sort', 'COM_USERS_HEADING_LAST_VISIT_DATE', 'a.lastvisitDate', $listDirn, $listOrder); ?>
						</th> -->

						<!-- <th width="10%" class="nowrap ">
							<?php echo JHtml::_('searchtools.sort', 'Công việc', 'a.job', $listDirn, $listOrder); ?>
						</th>
						<th width="10%" class="nowrap ">
							<?php echo JHtml::_('searchtools.sort', 'Tỉnh/TP', 'a.province', $listDirn, $listOrder); ?>
						</th>
						<th width="10%" class="nowrap ">
							<?php echo JHtml::_('searchtools.sort', 'Giới tính', 'a.sex', $listDirn, $listOrder); ?>
						</th>
						<th width="10%" class="nowrap">
							<?php echo JHtml::_('searchtools.sort', 'Bảo trợ', 'a.invited_id', $listDirn, $listOrder); ?>
						</th> -->
						<!-- <th width="1%" class="nowrap hidden-phone">
							<?php echo JHtml::_('searchtools.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
						</th> -->
						<th width="5%" class="nowrap">
							<?php echo JHtml::_('searchtools.sort', 'COM_USERS_HEADING_REGISTRATION_DATE', 'a.registerDate', $listDirn, $listOrder); ?>
						</th>
						<?php //if((int)$this->userGroup[0] === 15) {?>
						<th width="5%" class="nowrap">
							<?php echo "Người tạo" ?>
						</th>
						<?php //} ?>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<td colspan="10">
							<?php echo $this->pagination->getListFooter(); ?>
						</td>
					</tr>
				</tfoot>
				<tbody>
				<?php foreach ($this->items as $i => $item) :
					$canEdit   = $this->canDo->get('core.edit');
					$canChange = $loggeduser->authorise('core.edit.state',	'com_users');

					// If this group is super admin and this user is not super admin, $canEdit is false
					if ((!$loggeduser->authorise('core.admin')) && JAccess::check($item->id, 'core.admin'))
					{
						$canEdit   = false;
						$canChange = false;
					}
				?>
					<tr class="row<?php echo $i % 2; ?>">
						<td class="center">
							<?php if ($canEdit || $canChange) : ?>
								<?php echo JHtml::_('grid.id', $i, $item->id); ?>
							<?php endif; ?>
						</td>
						<td class="left">
								<?php echo $item->id; ?>
						</td>
						<!-- <td class="left">
								<?php if ($item->id_biznet == "" || $item->id_biznet == 0) {
									echo "#";
								}
								else {
									echo $item->id_biznet;
								}
								 ?>
						</td> -->
						<td>
							<div class="name break-word">
							<?php if ($canEdit) : ?>
								<a href="<?php echo JRoute::_('index.php?option=com_users&task=user.edit&id=' . (int) $item->id); ?>" title="<?php echo JText::sprintf('COM_USERS_EDIT_USER', $this->escape($item->name)); ?>">
									<?php echo $this->escape($item->name); ?></a>
							<?php else : ?>
								<?php echo $this->escape($item->name); ?>
							<?php endif; ?>
							</div>
							<div class="btn-group">
								<?php echo JHtml::_('users.filterNotes', $item->note_count, $item->id); ?>
								<?php echo JHtml::_('users.notes', $item->note_count, $item->id); ?>
								<?php echo JHtml::_('users.addNote', $item->id); ?>
							</div>
							<?php echo JHtml::_('users.notesModal', $item->note_count, $item->id); ?>
							<?php if ($item->requireReset == '1') : ?>
								<span class="label label-warning"><?php echo JText::_('COM_USERS_PASSWORD_RESET_REQUIRED'); ?></span>
							<?php endif; ?>
							<?php if (!$debugUsers) : ?>
								<div class="small"><a href="<?php echo JRoute::_('index.php?option=com_users&view=debuguser&user_id=' . (int) $item->id); ?>">
								<?php echo JText::_('COM_USERS_DEBUG_USER'); ?></a></div>
							<?php endif; ?>
						</td>
						<td class="break-word">
							<?php echo $this->escape($item->username); ?>
							<!-- <div class="upgrade-level"><a target="_blank" href="index.php?option=com_users&view=search&keyword=<?php echo $this->escape($item->username); ?>">Nâng Level</a></div> -->
						</td>
						<!-- <td class="" style="color:red;">
							<?php echo number_format($this->escape($item->money),0,".","."); ?>
							<?php if (@$this->projectInfo && $this->projectInfo['price'] <= $item->money): ?>
									<div class="form-row assignCustomer">
											<div class="input-group">
													<input data-id="<?php echo $item->id; ?>"
																 data-qty="<?php echo $this->projectInfo['remain_customer']; ?>"
																 data-price="<?php echo $this->projectInfo['price']; ?>"
																 data-pid="<?php echo $this->projectInfo['id']; ?>"
																 data-money="<?php echo $item->money; ?>"
																 class="form-control" id="validationDefaultUsername"
																 placeholder="Số lượng"
																 type="number"
																 aria-describedby="inputGroupPrepend2" required>
													<button class="btn btn-primary assignbt" type="button">Gán</button>
											</div>
									</div>
							<?php endif; ?>
						</td> -->
						<!-- <td class="center">
								<?php
								$self = $loggeduser->id == $item->id;

								if ($canChange) :
										echo JHtml::_('jgrid.state', JHtmlUsers::blockLandingpage($self), $item->block_landingpage, $i, 'users.', !$self);
								else :
										echo JHtml::_('jgrid.state', JHtmlUsers::blockLandingpage($self), $item->block_landingpage, $i, 'users.', false);
								endif; ?>
						</td> -->
						<!-- <td class="">
							Datacenter: Level <?php echo $this->escape($item->level); ?>
							<br>
							<br>
							Đại lý BH: Level <?php echo $this->escape($item->level_tree); ?>
						</td> -->
						<td class="center">
							<?php
							$self = $loggeduser->id == $item->id;

							if ($canChange) :
								echo JHtml::_('jgrid.state', JHtmlUsers::blockStates($self), $item->block, $i, 'users.', !$self);
							else :
								echo JHtml::_('jgrid.state', JHtmlUsers::blockStates($self), $item->block, $i, 'users.', false);
							endif; ?>
						</td>
						<!-- <td class="center hidden-phone">
							<?php
							$activated = empty( $item->activation) ? 0 : 1;
							echo JHtml::_('jgrid.state', JHtmlUsers::activateStates(), $activated, $i, 'users.', (boolean) $activated);
							?>
						</td> -->
						<td>
							<?php if (substr_count($item->group_names, "\n") > 1) : ?>
								<span class="hasTooltip" title="<?php echo JHtml::_('tooltipText', JText::_('COM_USERS_HEADING_GROUPS'), nl2br($item->group_names), 0); ?>"><?php echo JText::_('COM_USERS_USERS_MULTIPLE_GROUPS'); ?></span>
							<?php else : ?>
								<?php echo nl2br($item->group_names); ?>
							<?php endif; ?>
						</td>
						<td class="hidden-phone break-word hidden-tablet">
							<?php echo JStringPunycode::emailToUTF8($this->escape($item->email)); ?>
						</td>
						<!-- <td class="hidden-phone hidden-tablet">
							<?php if ($item->lastvisitDate != $this->db->getNullDate()) : ?>
								<?php echo JHtml::_('date', $item->lastvisitDate, JText::_('DATE_FORMAT_LC6')); ?>
							<?php else : ?>
								<?php echo JText::_('JNEVER'); ?>
							<?php endif; ?>
						</td> -->

						<!-- <td class="hidden-phone">
							<?php echo $item->job ? JText::_($item->job) : ''; ?>
						</td>
						<td class="hidden-phone">
							<?php //echo $item->province; ?>
							<?php echo $item->country_name; ?>
						</td> -->
						<!-- <td class="hidden-phone">
							<?php echo $item->sex ? JText::_('COM_USERS_USER_SEX_OPTION_'.$item->sex) : ''; ?>
						</td> -->
						<!-- <td class="hidden-phone">
							<?php
							if ($item->invited_id == 0) {
								echo "#";
							}
							// elseif($item->invited_id == "") {
							// 	echo $item->username;
							// }
							// else {
							// 	$invited_user = JFactory::getUser($invited_id);
							// 	echo $invited_user->id_biznet;
							// }
							else{
								$invited_user = JFactory::getUser($item->invited_id);
								if ($invited_user->id_biznet == "") {
									 echo $invited_user->username;
								}
								else {
									echo $invited_user->id_biznet;
								}
							}
							 ?>
						</td> -->
						<!-- <td class="hidden-phone">
							<?php echo (int) $item->id; ?>
						</td> -->
						<td class="hidden-phone hidden-tablet">
							<?php echo JHtml::_('date', $item->registerDate, JText::_('DATE_FORMAT_LC6')); ?>
						</td>
						<?php //if((int)$this->userGroup[0] === 15 && $item->sbdm_id) {?>
						<td class="hidden-phone hidden-tablet break-word">
							<?php echo $item->sbdm_id > 0 ? $item->sbdm_name . '<br/>'. '('.$item->sbdm_username.')':''; ?>
						</td>
						<?php //} ?>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			<?php // Load the batch processing form if user is allowed ?>
			<?php if ($loggeduser->authorise('core.create', 'com_users')
				&& $loggeduser->authorise('core.edit', 'com_users')
				&& $loggeduser->authorise('core.edit.state', 'com_users')) : ?>
				<?php echo JHtml::_(
					'bootstrap.renderModal',
					'collapseModal',
					array(
						'title'  => JText::_('COM_USERS_BATCH_OPTIONS'),
						'footer' => $this->loadTemplate('batch_footer'),
					),
					$this->loadTemplate('batch_body')
				); ?>
			<?php endif; ?>
		<?php endif; ?>

		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>

<style>
.upgrade-level a{
	color:orange;
}
.form-row {
		position: relative;
		padding-right: 50px;
		margin-top: 5px;
}
.assignCustomer{padding-right: 5px!important; }
.form-row .assignbt {
		position: relative;
		top: 0;
		right: 0;
		width: 52px;
		padding-left: 5px;
		padding-right: 5px;
		border-radius: 0;
}
#validationDefaultUsername{
	width: 70px;
}
</style>

<script type="text/javascript">
    jQuery(document).ready(function () {
				jQuery('.assignbt').click(function () {
					var box = jQuery(this).parent();
					var input = box.find('input');
					var r = confirm("Bạn có chắc muốn gán "+input.val()+" Data cho Đại lý này?");
					if (r == true) {
							var price = input.data('price');
							var qty = input.data('qty');
							var user_id = input.data('id');
							var pid = input.data('pid');
							var money = input.data('money');
							var requestQty = input.val();
							if(requestQty <= 0){
									alert('Vui lòng nhập số lượng khách hàng.');
									return false;
							}
							if (requestQty > qty) {
									alert('Số lượng khách hàng không đủ.');
									return false;
							}

							if ((requestQty * price) > money) {
									alert('Số dư tài khoản không đủ.');
									return false;
							}

							jQuery.ajax({
									url: "<?php echo JUri::base(); ?>index.php?option=com_users&task=user.assign_customer",
									type: "POST",
									dataType: 'json',
									data: {user_id: user_id, pid: pid, qty: requestQty},
									success: function (result) {

											if(result && result.error == true){
													alert(result.message);
													location.reload();
											}else{
													alert(result.message);
													location.reload();
											}

									}
							});
						}
				});


    });

</script>
