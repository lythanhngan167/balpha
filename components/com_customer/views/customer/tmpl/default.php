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

$canEdit = JFactory::getUser()->authorise('core.edit', 'com_customer.' . $this->item->id);

if (!$canEdit && JFactory::getUser()->authorise('core.edit.own', 'com_customer' . $this->item->id))
{
	$canEdit = JFactory::getUser()->id == $this->item->created_by;
}
?>
<h3>Khách hàng: <?php echo $this->item->name; ?> - <?php echo $this->item->phone; ?></h3>

<div style="clear:both; text-align:right;">

<?php if($this->item2->status_id != 6 && $this->item2->status_id != 99){ ?><button id="addToTrash" class="btn btn-warning">Cho vào Sọt rác</button><?php } ?>
<?php if($this->item2->status_id != 7 && $this->item2->status_id != 6 ){ ?>
	<?php if($this->item2->status_id != 99){ ?>&nbsp;<button id="addchangetag" class="btn btn-warning">Chuyển trạng thái</button><?php } ?>
	&nbsp; <button id="tranfertoagent" class="btn btn-warning">Chuyển Đại lý khác</button>
	&nbsp; <button id="addchangecolor" class="btn btn-warning">Thay đổi màu</button>
<?php } ?>
</div>
<br>
<?php if($this->item2->status_id == 7){ ?><div style="clear:both; text-align:right;"><button id="changerevenue" class="btn btn-warning">Cập nhật doanh thu</button></div>
<br>
<?php } ?>
<?php if($this->item2->color != ''){ ?>
<div style="clear:both; text-align:right;">Màu hiện tại: <?php
switch($this->item2->color){
	case "green": echo '<span class="rectangle green2" style="float:right"> </span>';
	break;
	case "red": echo '<span class="rectangle red2" style="float:right"> </span>';
	break;
	case "black": echo '<span class="rectangle black2" style="float:right"> </span>';
	break;
	case "blue": echo '<span class="rectangle blue2" style="float:right"> </span>';
	break;
	case "yellow": echo '<span class="rectangle yellow2" style="float:right"> </span>';
	break;



}
 ?> </div>
 <span id="current_color" style="display:none;"><?php echo $this->item2->color; ?></span>
<?php } ?>
<div class="item_fields">

	<table class="table">

		<tr>
			<th>Mã Khách hàng</th>
			<td>#<?php echo $this->item->id; ?></td>
		</tr>
		<?php if($this->regis->duplicate_first_bca == 1 ){ ?>
		<tr>
			<th>Xác minh Data Đăng ký lại</th>
			<td>
				<?php if($this->regis->duplicate_status == 0 ){ ?>
				<button id="addConfirmData" class="btn btn-info">Xác minh Đăng ký lại</button>
				<?php } ?>
				<?php if($this->regis->duplicate_status == 1 ){ ?>
					<span class="label label-default">Chờ duyệt</span>
				<?php } ?>
				<?php if($this->regis->duplicate_status == 2 ){ ?>
					<span class="label label-success">Đã duyệt</span>
				<?php } ?>
				<?php if($this->regis->duplicate_status == 3 ){ ?>
					<span class="label label-warning">Từ chối</span>
				<?php } ?>
				<br>
				<?php
				if($this->regis->duplicate_id == 1){
					echo "Lý do: Không nhận tư vấn";
				}else{
					echo $this->regis->duplicate_note != "" ? "Lý do: ".$this->regis->duplicate_note : "";
				}
				?>

			</td>
		</tr>
		<?php } ?>
		<tr>
			<th><?php echo JText::_('COM_CUSTOMER_FORM_LBL_CUSTOMER_NAME'); ?></th>
			<td><?php echo $this->item->name; ?></td>
		</tr>
		<tr>
			<th><?php echo JText::_('COM_CUSTOMER_FORM_LBL_CUSTOMER_PHONE'); ?></th>
			<td><?php echo $this->item->phone; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_CUSTOMER_FORM_LBL_CUSTOMER_EMAIL'); ?></th>
			<td class="detail-email"><?php echo $this->item->email; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_CUSTOMER_FORM_LBL_CUSTOMER_PLACE'); ?></th>
			<td class="detail-place"><?php echo $this->item->place; ?> &nbsp;&nbsp;<button type="button" id="changeplace" class="btn btn-dark">Sửa</button></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_CUSTOMER_FORM_PROVINCE'); ?></th>
			<td><?php echo $this->item->province; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_CUSTOMER_FORM_LBL_CUSTOMER_CATEGORY_ID'); ?></th>
			<td><?php echo $this->item->category_id_title; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_CUSTOMER_FORM_LBL_CUSTOMER_PROJECT_ID'); ?></th>
			<td><?php echo $this->item->project_id; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_CUSTOMER_FORM_LBL_CUSTOMER_STATUS_ID'); ?></th>
			<td><?php echo $this->item->status_id; ?></td>
		</tr>

		<tr>
			<th>Ngày mua</th>
			<td><?php echo JFactory::getDate($this->item->buy_date)->format("d-m-Y H:i"); ?></td>
		</tr>

		<tr>
			<th>Ngày <?php if($this->item2->status_id == 7){ ?>hoàn thành<?php }else{ ?>cập nhật<?php } ?></th>
			<td><?php echo JFactory::getDate($this->item->modified_date)->format("d-m-Y H:i"); ?></td>
		</tr>

		<tr>
			<th>Tổng doanh thu</th>
			<td class="price" style="font-weight:bold;"><?php echo number_format($this->item->total_revenue,0,",","."); ?> <?php echo BIZ_XU; ?></td>
		</tr>
		<?php if($this->item2->status_id === "99"){ ?>
		<tr>
			<th>Lý do cho vào Sọt rác</th>
			<td>
				<?php
				//print_r($this->item);
				if(isset($this->item->rating_id)) {
					switch((int)$this->item->rating_id){
						case 3:
							$this->item->rating_text = 'Khác';
						break;
						case 1:
							$this->item->rating_text = 'Sai thông tin';
						break;
						case 2:
							$this->item->rating_text = 'Không có nhu cầu';
						break;
					}
				}
				?>
					<?php if((int)$this->item->rating_id === 3){?>
							<?php //echo $this->item->rating_note?>
							<?php echo $this->item->rating_text; ?>
		          <?php if($this->item->trash_confirmed_by_dm != ''){ ?>
							<br>
		          <b>DM</b>: <?php echo $this->item->trash_confirmed_by_dm; ?>
							<br><b>Ghi chú</b>: <?php echo $this->item->rating_note; ?>
		          <?php } ?>

					<?php } else {?>

							<?php echo $this->item->rating_text; ?>
		          <?php if($this->item->trash_confirmed_by_dm != ''){ ?>
		          <br>
		          <b>DM</b>: <?php echo $this->item->trash_confirmed_by_dm; ?>
							<?php if($this->item->rating_note != ''){ ?>
							<br><b>Ghi chú</b>: <?php echo $this->item->rating_note; ?>
							<?php } ?>
		          <?php } ?>

					<?php }?>

			</td>
		</tr>
		<?php } ?>

	<?php if($this->item2->status_id === "99"){?>
		<tr>
			<th>Trạng thái Sọt rác</th>
			<td>
				<?php if((int)$this->item->trash_approve === 0){?>
						<?php //if($item->project_id_int == AT_PROJECT){?>
						<?php echo '<span id="trashStatus" class="badge badge-warning">Chờ duyệt</span>' ?>
						<?php //} ?>

				<?php } ?>
				<?php if((int)$this->item->trash_approve === 2){?>

						<?php //if($item->project_id_int == AT_PROJECT){?>
						<?php echo '<span id="trashStatus" class="badge badge-danger">Từ chối</span>' ?>
						<?php //} ?>

				<?php } ?>
			</td>
		</tr>
	<?php } ?>






		<!-- <tr>
			<th><?php echo JText::_('COM_CUSTOMER_FORM_LBL_CUSTOMER_CREATED_BY'); ?></th>
			<td><?php echo $this->item->created_by_name; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_CUSTOMER_FORM_LBL_CUSTOMER_MODIFIED_BY'); ?></th>
			<td><?php echo $this->item->modified_by_name; ?></td>
		</tr> -->

	</table>

</div>

<!-- <?php if($canEdit && $this->item->checked_out == 0): ?>

	<a class="btn" href="<?php echo JRoute::_('index.php?option=com_customer&task=customer.edit&id='.$this->item->id); ?>"><?php echo JText::_("COM_CUSTOMER_EDIT_ITEM"); ?></a>

<?php endif; ?> -->

<?php if (JFactory::getUser()->authorise('core.delete','com_customer.customer.'.$this->item->id)) : ?>

	<a class="btn btn-danger" href="#deleteModal" role="button" data-toggle="modal">
		<?php echo JText::_("COM_CUSTOMER_DELETE_ITEM"); ?>
	</a>

	<div id="deleteModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="deleteModal" aria-hidden="true">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h3><?php echo JText::_('COM_CUSTOMER_DELETE_ITEM'); ?></h3>
		</div>
		<div class="modal-body">
			<p><?php echo JText::sprintf('COM_CUSTOMER_DELETE_CONFIRM', $this->item->id); ?></p>
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal">Close</button>
			<a href="<?php echo JRoute::_('index.php?option=com_customer&task=customer.remove&id=' . $this->item->id, false, 2); ?>" class="btn btn-danger">
				<?php echo JText::_('COM_CUSTOMER_DELETE_ITEM'); ?>
			</a>
		</div>
	</div>

<?php endif; ?>
<br>
<h3>Lịch sử cuộc gọi</h3>
<div style="clear:both; text-align:right;"><button id="addnote" class="btn btn-success">Thêm ghi nhớ cuộc gọi</button></div>
<div class="call_all">
	<div class="call_item header">
		<div class="call_item_id">Mã</div>
		<div class="call_item_conten">Nội dung</div>
		<div class="call_item_day">Ngày tháng</div>
	</div>
	<?php
	foreach($this->list_call as $call){
	?>
	<div class="call_item">
		<div class="call_item_id">#<?php echo $call->id ?></div>
		<div class="call_item_conten"><?php echo $call->note ?></div>
		<div class="call_item_day"><?php echo JFactory::getDate($call->create_date)->format("d-m-Y H:i:s") ?></div>
	</div>
	<?php } ?>
	<?php if(count($this->list_call) <= 0){ ?>
		<div class="call_item nocall">
			<br>Chưa có cuộc gọi nào!
		</div>
	<?php } ?>

</div>



<div class="modal" id="myModal" role="dialog">
  <div class="modal-dialog">
    <form name="reasonFrom" id="reasonForm">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Ghi nhớ cuộc gọi</h4>
      </div>
      <div class="modal-body">
        <input type="text" id="reason" name="reason" value="" placeholder="Nhập ghi nhớ">
        <input type="hidden" name="id" value="">
				<input type="hidden" name="customer_id" id="customer_id" value="<?php echo $this->item->id; ?>">
        <input type="hidden" name="status" value="">
      </div>
      <div class="modal-footer">
        <div class="btn-group1" >
        <button type="button" class="btn btn-default" id="cancel_bt" data-dismiss="modal">Huỷ</button>
        <button type="button" class="btn btn-primary" onclick="saveCall()" id="save_bt" data-dismiss="modal">Lưu</button>
        </div>
      </div>
    </div>
    </form>
  </div>
</div>

<div class="modal" id="myModalColor" role="dialog">
  <div class="modal-dialog">
    <form name="reasonFrom" id="reasonForm">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Thay đổi màu sắc</h4>
      </div>
      <div class="modal-body">
				<select id="color_id" name="color_id" style="display:none">
					<option value="">--Vui lòng chọn--</option>
			  <option value="black">Đen</option>
			  <option value="green">Xanh lá</option>
			  <option value="red">Đỏ</option>
				<option value="yellow">Vàng</option>
				<option value="blue">Xanh da trời</option>
			</select>
			<br>

			<div class="wrap-color">
		  <input type="radio" class="color2" <?php  if($_GET['color'] == 'red') echo 'checked="checked"'; ?> onclick='setColor("red")' name="color" value="red"> <span class="rectangle red2"> </span>
		  <input type="radio" class="color2" <?php  if($_GET['color'] == 'green') echo 'checked="checked"'; ?> onclick='setColor("green")' name="color" value="green"> <span class="rectangle green2"> </span>
		  <input type="radio" class="color2" <?php  if($_GET['color'] == 'blue') echo 'checked="checked"'; ?> onclick='setColor("blue")' name="color" value="blue"> <span class="rectangle blue2"> </span>
		  <input type="radio" class="color2" <?php  if($_GET['color'] == 'yellow') echo 'checked="checked"'; ?> onclick='setColor("yellow")' name="color" value="yellow"> <span class="rectangle yellow2"> </span>
		  <input type="radio" class="color2" <?php  if($_GET['color'] == 'black') echo 'checked="checked"'; ?> onclick='setColor("black")' name="color" value="black"> <span class="rectangle black2"> </span>
		</div>

				<input type="hidden" name="color_customer_id" id="color_customer_id" value="<?php echo $this->item->id; ?>">
      </div>
      <div class="modal-footer">
        <div class="btn-group1" >
        <button type="button" class="btn btn-default" id="cancel_status_bt" data-dismiss="modal">Huỷ</button>
        <button type="button" class="btn btn-primary" id="save_status_bt" onclick="saveColor()" >Lưu</button>
        </div>
      </div>
    </div>
    </form>
  </div>
</div>


<div class="modal" id="myModalTag" role="dialog">
  <div class="modal-dialog">
    <form name="reasonFrom" id="reasonForm">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Chuyển trạng thái Khách hàng</h4>
      </div>
      <div class="modal-body">
				<select id="status_id" name="status_id">
					<option value="">--Vui lòng chọn--</option>
			  <option value="2">Shilly – Shally (Lưỡng lự)</option>
			  <option value="3">Interested (Quan tâm)</option>
			  <!-- <option value="4">Very Interested (Rất Quan tâm)</option> -->
			  <!-- <option value="5">Potential (Tiềm năng)</option> -->
				<option value="7">Done (Hoàn thành)</option>
				<!-- <option value="6">Return (Trả lại)</option> -->
				<!-- <option value="8">Cancel (Hủy)</option> -->

			</select>
			<br>
			  <input type="text" style="display:none;" id="total_revenue" name="total_revenue" value="" placeholder="Nhập doanh thu BizXu">
				<input type="hidden" name="status_customer_id" id="status_customer_id" value="<?php echo $this->item->id; ?>">
      </div>
      <div class="modal-footer">
        <div class="btn-group1" >
        <button type="button" class="btn btn-default" id="cancel_status_bt" data-dismiss="modal">Huỷ</button>
        <button type="button" class="btn btn-primary" id="save_status_bt" onclick="saveStatus()" >Lưu</button>
        </div>
      </div>
    </div>
    </form>
  </div>
</div>



<div class="modal" id="myModalTagRevenue" role="dialog">
  <div class="modal-dialog">
    <form name="reasonFrom" id="reasonFormRevenue">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Thay đổi tổng doanh thu</h4>
      </div>
      <div class="modal-body">

			  <input type="text" style="" id="change_total_revenue" name="change_total_revenue" value="" placeholder="Nhập doanh thu BizXu">
				<input type="hidden" name="change_status_customer_id" id="change_status_customer_id" value="<?php echo $this->item->id; ?>">
      </div>
      <div class="modal-footer">
        <div class="btn-group1" >
        <button type="button" class="btn btn-default" id="change_cancel_status_bt" data-dismiss="modal">Huỷ</button>
        <button type="button" class="btn btn-primary" id="change_save_status_bt" onclick="updateRevenue(<?php echo $this->item->id; ?>)" >Lưu</button>
        </div>
      </div>
    </div>
    </form>
  </div>
</div>



<div class="modal" id="myModalTagAddress" role="dialog">
  <div class="modal-dialog">
    <form name="reasonFrom" id="reasonFormAddress">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Thay đổi Địa chỉ/Khác</h4>
      </div>
      <div class="modal-body">

			  <input type="text" style="" id="change_address" name="change_address" value="" placeholder="Nhập Địa chỉ/Khác">
				<input type="hidden" name="change_address_customer_id" id="change_address_customer_id" value="<?php echo $this->item->id; ?>">
      </div>
      <div class="modal-footer">
        <div class="btn-group1" >
        <button type="button" class="btn btn-default" id="change_cancel_status_bt" data-dismiss="modal">Huỷ</button>
        <button type="button" class="btn btn-primary" id="change_save_status_bt" onclick="updateAddress(<?php echo $this->item->id; ?>)" >Lưu</button>
        </div>
      </div>
    </div>
    </form>
  </div>
</div>

<div class="modal" id="tranferToAgentModel" role="dialog">
  <div class="modal-dialog">
    <form name="reasonFrom" id="reasonForm">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Chuyển cho đại lý khác</h4>
      </div>
      <div class="modal-body">
		<input type="text" name="agent" id="agent" placeholder="ID Biznet/Số điện thoại"/></div>
      <div class="modal-footer">
        <div class="btn-group1" >
        <button type="button" class="btn btn-default" id="cancel_status_bt" data-dismiss="modal">Huỷ</button>
        <button type="button" class="btn btn-primary" id="save_status_bt" onclick="tranferToAgent()" >Lưu</button>
        </div>
      </div>
    </div>
    </form>
  </div>
</div>

<div class="modal" id="addToTrashModel" role="dialog">
  <div class="modal-dialog">
    <form name="reasonFrom" id="reasonForm">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Cho vào Sọt rác</h4>
      </div>
      <div class="modal-body">
		<select name="ratingId" id="ratingId">
			<option value="">--Lý do--</option>
			<option value="1">Sai thông tin</option>
			<option value="2">Không có nhu cầu</option>
			<option value="3">Khác</option>
		</select>
		<div>
			<input type="text" name="confirmedByDM" id="confirmedByDM" placeholder="Họ tên và ID Biznet của DM đã xác nhận" class="form-control" value=""/>
		</div>
		<div>
			<input type="text" name="ratingNote" id="ratingNote" placeholder="Ghi chú lý do" class="form-control" value=""/>
		</div>
	  </div>
      <div class="modal-footer">
        <div class="btn-group1" >
        <button type="button" class="btn btn-default" id="cancel_status_bt" data-dismiss="modal">Huỷ</button>
        <button type="button" class="btn btn-primary" id="save_status_bt" onclick="addToTrash()" >Lưu</button>
        </div>
      </div>
    </div>
    </form>
  </div>
</div>


<div class="modal" id="addConfirmDataModel" role="dialog">
  <div class="modal-dialog">
    <form name="reasonFrom" id="reasonForm">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Data đăng ký lại không chất lượng</h4>
      </div>
      <div class="modal-body">
		<select name="confirmDataId" id="confirmDataId">
			<option value="">--Lý do--</option>
			<option value="1">Không nhận tư vấn</option>
			<!-- <option value="2">Không có nhu cầu</option> -->
			<option value="3">Khác</option>
		</select>
		<div>
			<input type="hidden" name="confirmDataNote" id="confirmDataNote" placeholder="Ghi chú" class="form-control" value=""/>
		</div>
	  </div>
      <div class="modal-footer">
        <div class="btn-group1" >
        <button type="button" class="btn btn-default" id="cancel_status_bt" data-dismiss="modal">Huỷ</button>
        <button type="button" class="btn btn-primary" id="save_status_bt" onclick="addConfirmData()" >Lưu</button>
        </div>
      </div>
    </div>
    </form>
  </div>
</div>

<script>

function setColor(color){
	jQuery('#color_id').val(color);
}
jQuery('#addnote').click(function(){
    jQuery('#myModal').modal('toggle');
});

jQuery('#addchangetag').click(function(){
    jQuery('#myModalTag').modal('toggle');
});

jQuery('#addchangecolor').click(function(){
    jQuery('#myModalColor').modal('toggle');
		jQuery('#color_id').val(jQuery('#current_color').text());

});

jQuery('#addToTrash').click(function(){
	jQuery('#addToTrashModel').modal('toggle');
});

jQuery('#addConfirmData').click(function(){
	jQuery('#addConfirmDataModel').modal('toggle');
});



jQuery('#changeplace').click(function(){
    jQuery('#myModalTagAddress').modal('toggle');
});

jQuery('#changerevenue').click(function(){
    jQuery('#myModalTagRevenue').modal('toggle');
});


jQuery('#status_id').change(function(){
		if(jQuery(this).val() == 7){
			jQuery('#total_revenue').css("display","block");
		}else{
			jQuery('#total_revenue').css("display","none");
		}
});

jQuery('#tranfertoagent').click(function(){
    jQuery('#tranferToAgentModel').modal('toggle');
});

jQuery("#ratingId").change(function() {
	var reason = jQuery('#ratingId').val();
	if(parseInt(reason) === 3) {
		jQuery('#ratingNote').attr('type', 'text');
	} else {
		jQuery('#ratingNote').attr('type', 'text');
	}
});

jQuery("#confirmDataId").change(function() {
	var reason = jQuery('#confirmDataId').val();
	if(parseInt(reason) === 3) {
		jQuery('#confirmDataNote').attr('type', 'text');
	} else {
		jQuery('#confirmDataNote').attr('type', 'hidden');
	}
});

function updateRevenue(customer_id){
	var total_revenue = jQuery('#change_total_revenue').val();
	if(total_revenue == '' || total_revenue <= 0){
		alert("Vui lòng nhâp tổng doanh thu bằng BizXu!");
	}else{
		jQuery.ajax({url: "<?php echo JUri::base(); ?>index.php?option=com_customer&ajax=1&type=revenue&customer_id=" + customer_id + "&total_revenue=" + total_revenue, success: function (result) {
					if(result == '-1'){
						alert("Vui lòng đăng nhập!");
						location.reload();
					}
					if(result == '1'){
						alert("Cập nhật Doanh thu thành công!");
						location.reload();
					}
					if(result == '0'){
						alert("Cập nhật Doanh thu thất bại, vui lòng kiểm tra lại.");
						location.reload();
					}
			 }
		});
	}
}


function updateAddress(customer_id){
	var address = jQuery('#change_address').val();
	if(address == ''){
		alert("Vui lòng nhâp địa chỉ!");
	}else{
		jQuery.ajax({url: "<?php echo JUri::base(); ?>index.php?option=com_customer&ajax=1&type=address&customer_id=" + customer_id + "&address=" + address, success: function (result) {
					if(result == '-1'){
						alert("Vui lòng đăng nhập!");
						location.reload();
					}
					if(result == '1'){
						alert("Cập nhật Địa chỉ thành công!");
						location.reload();
					}
					if(result == '0'){
						alert("Cập nhật Địa chỉ thất bại, vui lòng kiểm tra lại.");
						location.reload();
					}
			 }
		});
	}
}
function saveColor(){
	var color_id = jQuery('#color_id').val();
	var customer_id = jQuery('#color_customer_id').val();

	if(color_id != ''){
		jQuery.ajax({url: "<?php echo JUri::base(); ?>index.php?option=com_customer&ajax=1&type=color&customer_id=" + customer_id + "&color_id=" + color_id, success: function (result) {
					if(result == '-1'){
						alert("Vui lòng đăng nhập!");
						location.reload();
					}
					if(result == '1'){
						alert("Đổi màu thành công!");
						location.reload();

					}
					if(result == '0'){
						alert("Đổi màu thất bại, vui lòng kiểm tra lại.");
						location.reload();
					}
			 }
		});
	}else{
		alert("Vui lòng chọn màu!");
	}
}
function saveStatus(){
	var status_id = jQuery('#status_id').val();
	var customer_id = jQuery('#status_customer_id').val();
	if(status_id == 7){
		var total_revenue = jQuery('#total_revenue').val();
		if(total_revenue > 0){
		}else{
			alert("Vui lòng nhâp tổng doanh thu bằng BizXu!");
		}
	}
	if(status_id > 0){
		jQuery.ajax({url: "<?php echo JUri::base(); ?>index.php?option=com_customer&ajax=1&type=status&customer_id=" + customer_id + "&total_revenue=" + total_revenue + "&status_id="+status_id, success: function (result) {
					if(result == '-1'){
						alert("Vui lòng đăng nhập!");
						location.reload();
					}
					if(result == '1'){
						alert("Chuyển trạng thái thành công!");
						location.reload();

					}
					if(result == '0'){
						alert("Chuyển trạng thái thất bại, vui lòng kiểm tra lại.");
						location.reload();
					}
			 }
		});
	}else{
		alert("Vui lòng chọn Trạng thái cần chuyển!");
	}

}

function saveCall(){
	var customer_id = jQuery('#customer_id').val();
	var reason = jQuery('#reason').val();

	jQuery.ajax({url: "<?php echo JUri::base(); ?>index.php?option=com_customer&ajax=1&type=call&customer_id=" + customer_id + "&reason=" + reason, success: function (result) {
				if(result == '-1'){
					alert("Vui lòng đăng nhập!");
					location.reload();
				}
				if(result == '1'){
					alert("Thêm ghi chú cuộc gọi thành công!");
					location.reload();
				}
				if(result == '0'){
					alert("Thêm ghi chú thất bại, vui lòng kiểm tra lại.");
					location.reload();
				}
		 }
	});
}

function tranferToAgent() {
	var agent 		= jQuery('#agent').val();
	if(agent) {
		jQuery.ajax({
			url: "<?php echo JUri::base(); ?>index.php?option=com_customer&task=customer.isAgentExist",
			type: "POST",
			dataType:"text",
			data : {
				agent : agent
			},
			success: function (result) {
				if(result == '-1'){
					alert("Đại lý không tồn tại!");
				} else {
					jQuery.ajax({
						url: "<?php echo JUri::base(); ?>index.php?option=com_customer&task=customer.transferToAgent",
						type: "POST",
						dataType:"text",
						data : {
							agentId : result,
							customerId: <?php echo $this->item->id?>
						},
						success: function (result) {
							if(result == '-1'){
								alert("Vui lòng chọn đại lý!");
								location.reload();
							}

							if(result == '-2'){
								alert("Vui lòng đăng nhập!");
								location.reload();
							}

							if(result == '1'){
								alert("Chuyển đại lý thành công!");
								window.location.href = "<?php echo JRoute::_('index.php?option=com_customer&view=customers&Itemid='.$this->itemID); ?>";
							}

							if(result == '0'){
								alert("Chuyển đại lý thất bại!");
								location.reload();
							}
						}
					});
				}

			}
		});

	} else {
		alert("Vui lòng nhập id biznet hoặc số điện thoại!");

	}
}

function addToTrash(){
	var ratingId 	= jQuery('#ratingId').val();
	var ratingNote 	= jQuery('#ratingNote').val();
	var confirmedByDM 	= jQuery('#confirmedByDM').val();
	if(ratingId){
		if(parseInt(ratingId) === 3 && !ratingNote){
			alert("Vui lòng nhập Lý do!");
			return;
		}

		if(!confirmedByDM){
			alert("Vui lòng nhập Họ tên và ID Biznet của DM đã xác nhận!");
			return;
		}

		if(!ratingNote){
			alert("Vui lòng nhập Ghi chú lý do!");
			return;
		}

		jQuery.ajax({
			url: "<?php echo JUri::base(); ?>index.php?option=com_customer&task=customer.addToTrash",
			type: "POST",
			dataType:"text",
			data : {
				ratingId : ratingId,
				ratingNote : ratingNote,
				confirmedByDM : confirmedByDM,
				customerId : <?php echo $this->item->id?>
			},
			success: function (result) {
				if(result == '-1'){
					alert("Vui lòng chọn lý do!");
				}

				if(result == '-2') {
					alert("Vui lòng nhập lý do!");
				}

				if(result == '-4') {
					alert("Vui lòng nhập Họ tên và ID Biznet của DM đã xác nhận!");
				}

				if(result == '-9') {
					alert("Tạm thời không cho vào Sọt rác được, vui lòng thử lại sau 4 - 7 ngày!");
				}

				if(result == '-3') {
					alert("Vui lòng đăng nhập!");
					location.reload();
				}
				if(result == '1'){
					alert("Cho vào Sọt rác thành công!");
					window.location.href = "<?php echo JRoute::_('index.php?option=com_customer&view=customers&Itemid='.$this->itemID); ?>";
				}
			}
		});

	}else{
		alert("Vui lòng chọn lý do!");
	}

}



function addConfirmData(){
	var confirmDataId 	= jQuery('#confirmDataId').val();
	var confirmDataNote 	= jQuery('#confirmDataNote').val();
	if(confirmDataId){
		if(parseInt(confirmDataId) === 3 && !confirmDataId){
			alert("Vui lòng nhập lý do!");
			return;
		}

		jQuery.ajax({
			url: "<?php echo JUri::base(); ?>index.php?option=com_customer&task=customer.addConfirmData",
			type: "POST",
			dataType:"text",
			data : {
				confirmDataId : confirmDataId,
				confirmDataNote : confirmDataNote,
				customerId : <?php echo $this->item->id?>
			},
			success: function (result) {
				if(result == '-1'){
					alert("Vui lòng chọn lý do!");
				}

				if(result == '-2') {
					alert("Vui lòng nhập lý do!");
				}

				if(result == '-3') {
					alert("Vui lòng đăng nhập!");
					location.reload();
				}
				if(result == '1'){
					alert("Xác minh Data trả lại thành công!");
					location.reload();
				}
			}
		});

	}else{
		alert("Vui lòng chọn lý do!");
	}

}

</script>

<style>
.call_item_id{width:20%; float:left;}
.call_item_conten{width:50%; float:left;}
.call_item_day{width:30%; float:left;}
.call_item.header div{
	background-color:#CCC;
	padding-top:10px;
	padding-bottom:10px;
	margin-top:10px;
	padding-left:5px;
}
.call_item div{
	padding-top:10px;
	padding-bottom:10px;
	padding-left:5px;
}
#myModalTag select{ min-width:200px; height: 35px; }
#reason{ width:100%; }
.nocall{ clear:both; }
</style>

<style>
.red2{ background-color:red; width:20px; height:20px; display:block; float:left;}
.green2{ background-color:green; width:20px; height:20px; display:block; float:left;}
.red2{ background-color:red; width:20px; height:20px; display:block; float:left;}
.blue2{ background-color:blue; width:20px; height:20px; display:block; float:left;}
.yellow2{ background-color:yellow; width:20px; height:20px; display:block; float:left; }
.black2{ background-color:black; width:20px; height:20px; display:block; float:left;}
.color2{float:left; padding-right:10px;}
.rectangle{ margin-right:10px; margin-left:4px;}
.wrap-color{
  padding-top:10px;
  padding-bottom:10px;
}

#ratingId {
	margin-bottom: 10px;
}

#tranfertoagent {
	margin-bottom: 10px;
}
#addToTrash {
	margin-bottom: 10px;
}
#addchangetag {
	margin-bottom: 10px;
}

#addchangecolor {
	margin-bottom: 10px;
}

#confirmDataNote{
	margin-top:10px;
}
#confirmedByDM{
	width: 70%;
}
#ratingNote{
	width: 70%;
}
@media screen and (max-width: 768px){
	#addConfirmData{
		font-size:14px;
	}
	#confirmedByDM{
		width: 100%;
	}
	#ratingNote{
		width: 100%;
	}
}
.detail-email{
	word-break: break-all;
}
.detail-place{
	word-break: break-all;
}

</style>
