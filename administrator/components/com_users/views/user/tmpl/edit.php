<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
// $user = JFactory::getUser();
// $groups = JAccess::getGroupsByUser($user->id, false);
// print_r($groups[0]);
// die;
// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

JHtml::_('behavior.formvalidator');
JHtml::_('formbehavior.chosen', 'select');

JFactory::getDocument()->addScriptDeclaration("
	Joomla.submitbutton = function(task)
	{
		if (task == 'user.cancel' || document.formvalidator.isValid(document.getElementById('user-form')))
		{
			Joomla.submitform(task, document.getElementById('user-form'));
		}
	};

	Joomla.twoFactorMethodChange = function(e)
	{
		var selectedPane = 'com_users_twofactor_' + jQuery('#jform_twofactor_method').val();

		jQuery.each(jQuery('#com_users_twofactor_forms_container>div'), function(i, el) {
			if (el.id != selectedPane)
			{
				jQuery('#' + el.id).hide(0);
			}
			else
			{
				jQuery('#' + el.id).show(0);
			}
		});
	};
");

// Get the form fieldsets.
$fieldsets = $this->form->getFieldsets();
?>
<form action="<?php echo JRoute::_('index.php?option=com_users&layout=edit&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="user-form" class="form-validate form-horizontal" enctype="multipart/form-data">

<div id="information">
<div class="panel panel-default">
<div class="panel-heading">
	<h2 class="panel-title">Thông tin Thành viên</h2>
</div>
<div class="panel-body">

	<?php echo JLayoutHelper::render('joomla.edit.item_title', $this); ?>

	<fieldset>
		<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'details')); ?>

			<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'details', JText::_('COM_USERS_USER_ACCOUNT_DETAILS')); ?>

				<?php foreach ($this->form->getFieldset('user_details') as $field) : ?>
					<div class="control-group">
						<div class="control-label">
							<!-- <?php if($field->fieldname == 'id'):?>
								Mã TV<br><br>
							<?php endif; ?> -->
							<?php echo $field->label; ?>
						</div>
						<div class="controls">

							<!-- <?php if($field->fieldname == 'id'):?>
								<?php if($this->item->id > 0){ ?>
								<?php echo $this->item->level_tree.str_pad($this->item->id,6,"0",STR_PAD_LEFT); ?>
								<?php
								if($this->item->approved == 0){
									$approved = 'Chưa Duyệt';
									$btn =  'btn-warning';
								}
								if($this->item->approved == 1){
									$approved = 'Đã Cập nhật';
									$btn = 'btn-info';
								}
								if($this->item->approved == 9){
									$approved = 'Đã Duyệt';
									$btn = 'btn-success';
								}
								?>
								&nbsp;&nbsp;&nbsp;
								<span class="<?php echo $btn; ?>"><?php echo $approved; ?></span>
							<?php }else{ ?>
								#
							<?php } ?>
								<br><br>
							<?php endif; ?> -->

							<?php if ($field->fieldname == 'password') : ?>
								<?php // Disables autocomplete ?> <input type="password" style="display:none">
							<?php endif; ?>
							<?php echo $field->input; ?>

							<?php if($field->fieldname == 'invited_id'):

								?>
								<?php if($this->item->id > 0){
								$userinfo = JFactory::getUser($this->item->invited_id);
								if($userinfo->id > 0){
								//echo 'Cấp '.$userinfo->level_tree.':';
								}
								?>
								<br>
								<a href="<?php echo JRoute::_('index.php?option=com_users&task=user.edit&id=' . (int) $this->item->invited_id); ?>" >
									<?php if($this->item->invited_id > 0){
									echo $userinfo->level_tree.str_pad($userinfo->id,6,"0",STR_PAD_LEFT);

									}
									?>
								</a>
								<?php
								if($userinfo->id > 0){
									echo ' ( '.$userinfo->username.' - '.$userinfo->name.' ) ';
								}
								?>
							<?php }else{ ?>
								#
								<?php } ?>
							<?php endif; ?>


							<div class="parent-c1">
								<?php if($field->fieldname == 'invited_id'):?>
									<?php if($this->item->parent_c1 > 0){ ?>
									<?php
									$userinfo = JFactory::getUser($this->item->parent_c1);
									if($userinfo->id > 0){
									echo '<br>Cấp 1:';
									}
									?>
									<a href="<?php echo JRoute::_('index.php?option=com_users&task=user.edit&id=' . (int) $this->item->parent_c1); ?>" >
										<?php if($this->item->parent_c1 > 0){


										echo $userinfo->level.str_pad($userinfo->id,6,"0",STR_PAD_LEFT);

										}
										?>
									</a>
									<?php
									if($userinfo->id > 0){
										echo ' ( '.$userinfo->username.' - '.$userinfo->name.' ) ';
									}
									?>
								<?php }else{ ?>

									<?php } ?>
								<?php endif; ?>
							</div>

						</div>
					</div>
				<?php endforeach; ?>
				<?php if ((int)$this->userGroup[0] === 15) : ?>
                    <div class="control-group">
                        <div class="control-label">
                            <label for="">Người tạo</label>
                        </div>
                        <div class="controls">
                            <input type="hidden" name="jform[sbdm_id]" value="<?php echo $this->user->id?>"/>
                        <?php echo $this->user->name . ' - '. $this->user->username; ?></div>
                    </div>
                <?php endif; ?>
			<?php echo JHtml::_('bootstrap.endTab'); ?>

			<?php //if ($this->grouplist) : ?>
				<?php //echo JHtml::_('bootstrap.addTab', 'myTab', 'groups', JText::_('COM_USERS_ASSIGNED_GROUPS')); ?>
					<?php //echo $this->loadTemplate('groups'); ?>
				<?php //echo JHtml::_('bootstrap.endTab'); ?>
			<?php //endif; ?>

			<?php
			$this->ignore_fieldsets = array('user_details');
			echo JLayoutHelper::render('joomla.edit.params', $this);
			?>

		<?php if (!empty($this->tfaform) && $this->item->id) : ?>
		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'twofactorauth', JText::_('COM_USERS_USER_TWO_FACTOR_AUTH')); ?>
		<div class="control-group">
			<div class="control-label">
				<label id="jform_twofactor_method-lbl" for="jform_twofactor_method" class="hasTooltip"
						title="<?php echo '<strong>' . JText::_('COM_USERS_USER_FIELD_TWOFACTOR_LABEL') . '</strong><br />' . JText::_('COM_USERS_USER_FIELD_TWOFACTOR_DESC'); ?>">
					<?php echo JText::_('COM_USERS_USER_FIELD_TWOFACTOR_LABEL'); ?>
				</label>
			</div>
			<div class="controls">
				<?php echo JHtml::_('select.genericlist', Usershelper::getTwoFactorMethods(), 'jform[twofactor][method]', array('onchange' => 'Joomla.twoFactorMethodChange()'), 'value', 'text', $this->otpConfig->method, 'jform_twofactor_method', false); ?>
			</div>
		</div>
		<div id="com_users_twofactor_forms_container">
			<?php foreach ($this->tfaform as $form) : ?>
			<?php $style = $form['method'] == $this->otpConfig->method ? 'display: block' : 'display: none'; ?>
			<div id="com_users_twofactor_<?php echo $form['method'] ?>" style="<?php echo $style; ?>">
				<?php echo $form['form'] ?>
			</div>
			<?php endforeach; ?>
		</div>

		<fieldset>
			<legend>
				<?php echo JText::_('COM_USERS_USER_OTEPS'); ?>
			</legend>
			<div class="alert alert-info">
				<?php echo JText::_('COM_USERS_USER_OTEPS_DESC'); ?>
			</div>
			<?php if (empty($this->otpConfig->otep)) : ?>
			<div class="alert alert-warning">
				<?php echo JText::_('COM_USERS_USER_OTEPS_WAIT_DESC'); ?>
			</div>
			<?php else : ?>
			<?php foreach ($this->otpConfig->otep as $otep) : ?>
			<span class="span3">
				<?php echo substr($otep, 0, 4); ?>-<?php echo substr($otep, 4, 4); ?>-<?php echo substr($otep, 8, 4); ?>-<?php echo substr($otep, 12, 4); ?>
			</span>
			<?php endforeach; ?>
			<div class="clearfix"></div>
			<?php endif; ?>
		</fieldset>

		<?php echo JHtml::_('bootstrap.endTab'); ?>
		<?php endif; ?>

		<?php echo JHtml::_('bootstrap.endTabSet'); ?>
	</fieldset>

	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>

</div>
</div>
</div>

<div id="invited_id">
	<div id="group_user">

		<h2 class="panel-title">Nhóm Thành viên</h2>
		<?php echo $this->loadTemplate('groups'); ?>
	</div>
	<?php


	if($_REQUEST['id'] > 0):
		$groups = JAccess::getGroupsByUser($_REQUEST['id'], false);
		if($groups[0] == 3):
		?>
<?php
$month_selected = date('m');
$year_selected = date('Y');
?>
<div class="panel panel-default">
	<br>
	<div class="text-red">Hoa hồng được trả cho Đại lý 2 lần / tháng.</div>
	<!-- <div class="text-black">
		 Hoa hồng được tính với đơn hàng đã được Kế toán duyệt <b>"Đã thanh toán"</b> từ <span class="text-red">00:00 ngày 15/<?php echo date("m") == 1?'12': date("m") - 1; ?>/<?php echo date("m") == 1?date('Y')-1: date("Y"); ?> <b>đến</b> 23:59 ngày 14/<?php echo date("m"); ?>/<?php echo date("Y"); ?></span>.
	</div>
	<br>
	<div class="panel-heading">
		<h2 class="panel-title">Danh số và Hoa hồng <?php echo date('m'),'/'.date('Y'); ?></h2>
	</div> -->
	<br>
	<div class="panel-body">
		Cá nhân (CN):&nbsp;
		<span class="price"><?php
		$type = 'individual';

		$money3 = EshopHelper::getRevenueAmount($this->item->id,$month_selected,$year_selected,$type);
		echo number_format($money3,0,".",".");
		 ?>
	 </span>
		 <br>
		 Nhóm:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		 <span class="price">
		 <?php
		$type = 'group';

		$money4 = EshopHelper::getRevenueAmount($this->item->id,$month_selected,$year_selected,$type);
		echo number_format($money4,0,".",".");
		 ?>
	 	</span>
		 <br>
		 Hoa hồng:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		 <span class="price">
		<?php
		$type = 'group';
		$money5 = EshopHelper::getCommissionAmount($this->item->id,$month_selected,$year_selected,$type);
		echo number_format($money5,0,".",".");
		 ?>
		</span>
	</div>
	<div class="panel-heading">
		<h2 class="panel-title">Danh sách Bảo trợ</h2>
	</div>
	<div class="panel-body">

	<?php
		if($this->item->id > 0){
			$childs = $this->listChildUserByLevel($this->item->id,0);

		}

		//print_r($childs);
		if(count($childs) > 0){

				?>

				<div class="rTable">
				<div class="rTableRow">
				<div class="rTableHead"><span style="font-weight: bold;">ID</span></div>
				<div class="rTableHead"><span style="font-weight: bold;">Họ tên</span></div>
				<div class="rTableHead"><span style="font-weight: bold;">ID Biznet</span></div>
				<div class="rTableHead"><span style="font-weight: bold;">Cấp ĐL</span></div>
				<div class="rTableHead"><span style="font-weight: bold;">Tỉnh/TP</span></div>
				<div class="rTableHead"><span style="font-weight: bold;">Doanh số & HH <?php echo date('m'),'/'.date('Y'); ?></span></div>
				<div class="rTableHead"><span style="font-weight: bold;">Ngày tạo</span></div>
				</div>
				<?php
				//print_r($childs); die;
				foreach($childs as $child){

					//print_r($child);
					?>
				<div class="rTableRow">
					<div class="rTableCell">
						<a href="<?php echo JRoute::_('index.php?option=com_users&task=user.edit&id=' . (int) $child->id); ?>" ><?php echo $child->id; ?></a>
					</div>
				<div class="rTableCell">
					<a href="<?php echo JRoute::_('index.php?option=com_users&task=user.edit&id=' . (int) $child->id); ?>" ><?php echo $child->name; ?></a><br>
					<?php echo $child->username; ?>
				</div>
				<div class="rTableCell">
					<a href="<?php echo JRoute::_('index.php?option=com_users&task=user.edit&id=' . (int) $child->id); ?>" ><?php echo $child->id_biznet;  //$child->level_tree.str_pad($child->id,6,"0",STR_PAD_LEFT); ?></a>
					<!-- <div class"user-status">
						<?php
						if($child->approved == 0){
							$approved = 'Chưa Duyệt';
							$btn =  'btn-warning';
						}
						if($child->approved == 1){
							$approved = 'Đã Cập nhật';
							$btn = 'btn-info';
						}
						if($child->approved == 9){
							$approved = 'Đã Duyệt';
							$btn = 'btn-success';
						}
						?>
						<span class="<?php echo $btn; ?>"><?php echo $approved; ?></span>
					</div> -->
				</div>
				<div class="rTableCell"><?php if($child->level_tree > 0){ ?>C<?php echo $child->level_tree; ?><?php }else{ echo "#"; } ?></div>
				<div class="rTableCell"><?php echo $this->getProvinceName($child->province); ?></div>
				<div class="rTableCell">

					Cá nhân:
					<span class="price"><?php
					$type = 'individual';

					$money1 = EshopHelper::getRevenueAmount($child->id,$month_selected,$year_selected,$type);
					echo number_format($money1,0,".",".");
					 ?>
				 </span>
					 <br>
					 Nhóm:
					 <span class="price">
					 <?php
 					$type = 'group';

 					$money2 = EshopHelper::getRevenueAmount($child->id,$month_selected,$year_selected,$type);
 					echo number_format($money2,0,".",".");
 					 ?>
					</span>
					<br>
					HH:
					<span class="price">
					<?php
					$type = 'group';
					$money6 = EshopHelper::getCommissionAmount($child->id,$month_selected,$year_selected,$type);
					echo number_format($money6,0,".",".");
					 ?>
					</span>
				</div>
				<div class="rTableCell"><?php echo date("d-m-Y",strtotime($child->registerDate)); ?></div>
				</div>
				<?php } ?>

				</div>
				<?php


		}else{
			echo "Không có Đại lý!";
		}
	?>
</div>
</div>
<?php endif; ?>
<?php endif; ?>
</div>
</form>

<script>
js = jQuery.noConflict();
js(document).ready(function () {
	<?php if($_GET['id'] > 0 ){ ?>

	<?php }else{ ?>
		// js('#1group_2').prop('checked', false);
		// js('#1group_7').prop('checked', false);
		// js('#1group_12').prop('checked', false);
		// js('#1group_13').prop('checked', true);

	<?php } ?>
	js('#group_user .control-group').each(function (index, value){
  	js(this).addClass('group-index-'+index);
	});

	<?php if((int)$this->userGroup[0] === 15) {?>
		js('#group_user .control-group').each(function (index, value){
			if(index !== 3) {
				js(this).attr('hidden', true);
			}

		});
	<?php }?>
});

js('input[name="jform[groups][]"]').click(function(){
	js('#1group_2').prop('checked', false);
	js('#1group_4').prop('checked', false);
	js('#1group_3').prop('checked', false);
	js('#1group_6').prop('checked', false);
	js('#1group_12').prop('checked', false);
	js('#1group_13').prop('checked', false);
	js('#1group_10').prop('checked', false);
	js('#1group_11').prop('checked', false);
	js('#1group_14').prop('checked', false);
	js('#1group_7').prop('checked', false);
	js('#1group_8').prop('checked', false);
	js('#1group_'+js(this).val()).prop('checked', true);
});

<?php
//set sendEmail = 1
if((int)$this->item->id == 0){
?>
js(document).ready(function(){
  js('#jform_sendEmail0').trigger('click');
	js('#1group_4').prop('checked', true);
	js('#1group_2').prop('checked', false);
});
<?php
}
?>

</script>


<style>
label.checkbox[for="1group_1"]
{
  display:none!important;
}
label.checkbox[for="1group_9"]
{
  display:none!important;
}
label.checkbox[for="1group_4"]
{
  display:block!important;
}
label.checkbox[for="1group_5"]
{
  display:none!important;
}

label.checkbox[for="1group_15"]
{
  display:none!important;
}
label.checkbox[for="1group_12"]
{
  display:none!important;
}
label.checkbox[for="1group_13"]
{
  display:none!important;
}
/* label.checkbox[for="1group_14"]
{
  display:none!important;
} */
label.checkbox[for="1group_11"]
{
  display:none!important;
}
label.checkbox[for="1group_10"]
{
  display:none!important;
}
#groups.tab-pane .control-group{margin-bottom: 0px!important;}
/* .group-index-0{display: none;}
.group-index-1{display: none;}
.group-index-3{display: none;}
.group-index-4{display: none;}
.group-index-5{display: none;} */
#group_user .control-group{
	margin-bottom: 5px;
}
.group-index-12{
	margin-bottom: 15px!important;
}
#jform_invited_id{
	display:none;
}
#information {
    width: 50%;
    float: left;
}
#invited_id {
    width: 50%;
    float: left;
}
.panel-title {
    color: #12489c;
}


.rTable {
  	display: table;
  	width: 100%;
}
.rTableRow {
  	display: table-row;
}
.rTableHeading {
  	display: table-header-group;
  	background-color: #ddd;
}
.rTableCell, .rTableHead {
  	display: table-cell;
  	padding: 10px 10px;
  	border: 1px solid #999999;
}
.rTableHeading {
  	display: table-header-group;
  	background-color: #ddd;
  	font-weight: bold;
}
.rTableFoot {
  	display: table-footer-group;
  	font-weight: bold;
  	background-color: #ddd;
}
.rTableBody {
  	display: table-row-group;
}
.text-orange{
	color:orange;
	font-size: 15px;
}
.text-red{
	color:red;
	font-size: 15px;
}
.text-price-red{
	color:red;
}
.text-black{
	color:black;
	font-size: 15px;
}
#group_user .controls {
  margin-left: 20px!important	;
}
</style>

<?php
function isMobile($mobile)
{
    return preg_match('/^0[0-9]{9}+$/', $mobile);
}


	 // $phone_num = echo"<script>jQuery('#jform_username').val();<script>"
	 // $this->isMobile($phone_num);
	 // print_r();
 ?>
