<?php
/**
 * @version        3.1.0
 * @package        Joomla
 * @subpackage     EShop
 * @author         Giang Dinh Truong
 * @copyright      Copyright (C) 2012 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
// no direct access
use api\model\libs\VnpayGateway;

defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');
JHtml::_('formbehavior.chosen', 'select');
?>
<style>
  .done-date{ text-align: center;}
  .bg-warning {
      background-color: orange;
      padding: 5px;
      color: white;
  }

  .bg-danger {
      background-color: red;
      padding: 5px;
      color: white;
  }

  .bg-success {
      background-color: green;
      padding: 5px;
      color: white;
  }

  .bg-primary {
      background-color: blue;
      padding: 5px;
      color: white;
  }
  #agents_chzn .chzn-search {
    position: relative!important;
    left: 0!important;
  }
</style>
<script type="text/javascript">
    js = jQuery.noConflict();


    function sendRequestEmail(id) {
        js.ajax({
            url: "<?php echo JURI::root(); ?>administrator/index.php?option=com_eshop&task=order.sendRequestEmail&tmpl=component",
            method: 'POST',
            data: {id: id},
            success: function (result) {
                if (result) {
                    alert("Cập nhật thành công!");
                } else {
                    alert("Có lỗi, vui lòng thử lại sau!");
                }
            }
        });
    }


</script>
<?php
$input = JFactory::getApplication()->input;
$datarange = $input->get('daterange', '', 'STRING');
if ($datarange == '') {
    //$datarange = date('d/m/Y') . ' - ' . date('d/m/Y');
}
?>
<div id="msgBlock" style="display: none" class="alert"></div>
<form action="index.php?option=com_eshop&view=orders" method="post" name="adminForm" id="adminForm">
    <div id="j-main-container">
        <div id="filter-bar" class="btn-toolbar">
          <div class="filter-search btn-group pull-left">
              <label for="filter_search"
                     class="element-invisible"><?php echo JText::_('ESHOP_FILTER_SEARCH_ORDERS_DESC'); ?></label>
              <input type="text" name="search" id="filter_search"
                     placeholder="<?php echo JText::_('JSEARCH_FILTER'); ?>"
                     value="<?php echo $this->escape($this->state->search); ?>" class="hasTooltip"
                     title="<?php echo JHtml::tooltipText('ESHOP_SEARCH_ORDERS_DESC'); ?>"/>
          </div>

          <div class="filter-search btn-group pull-left">
              <label for="filter_search" class="element-invisible">Ngày đặt hàng:</label>
              <input type="text" name="daterange" id="daterange" value="<?= $datarange ?>"
                     placeholder="Ngày đặt hàng"/>
          </div>
          <div class="btn-group pull-left">
              <button type="submit" class="btn hasTooltip" title="Hôm nay"
                      onclick="document.getElementById('filter_search').value=''; document.getElementById('daterange').value='<?= $this->getToday(); ?>'; this.form.submit();">
                  <span>Hôm nay</span></button>
              <button type="submit" class="btn hasTooltip" title="Hôm qua"
                      onclick="document.getElementById('filter_search').value=''; document.getElementById('daterange').value='<?= $this->getYesterday();?>'; this.form.submit();">
                  <span>Hôm qua</span></button>
              <button type="submit" class="btn hasTooltip" title="Tháng này"
                      onclick="document.getElementById('filter_search').value=''; document.getElementById('daterange').value='<?= $this->getThisMonth();?>'; this.form.submit();">
                  <span>Tháng này</span></button>


              <button type="submit" class="btn hasTooltip"
                      title="<?php echo JHtml::tooltipText('JSEARCH_FILTER_SUBMIT'); ?>"><span
                          class="icon-search"></span></button>
              <button type="button" class="btn hasTooltip"
                      title="<?php echo JHtml::tooltipText('JSEARCH_FILTER_CLEAR'); ?>"
                      onclick="document.getElementById('filter_search').value=''; document.getElementById('daterange').value=''; this.form.submit();"><span
                          class="icon-remove"></span></button>
          </div>
            <!-- <div class="filter-search btn-group pull-left">
                <label for="filter_search"
                       class="element-invisible"><?php echo JText::_('ESHOP_FILTER_SEARCH_ORDERS_DESC'); ?></label>
                <input type="text" name="search" id="filter_search"
                       placeholder="<?php echo JText::_('JSEARCH_FILTER'); ?>"
                       value="<?php echo $this->escape($this->state->search); ?>" class="hasTooltip"
                       title="<?php echo JHtml::tooltipText('ESHOP_SEARCH_ORDERS_DESC'); ?>"/>
            </div>
            <div class="btn-group pull-left">
                <button type="submit" class="btn hasTooltip"
                        title="<?php echo JHtml::tooltipText('JSEARCH_FILTER_SUBMIT'); ?>"><span
                            class="icon-search"></span></button>
                <button type="button" class="btn hasTooltip"
                        title="<?php echo JHtml::tooltipText('JSEARCH_FILTER_CLEAR'); ?>"
                        onclick="document.getElementById('filter_search').value='';this.form.submit();"><span
                            class="icon-remove"></span></button>
            </div>
             -->
            <div class="btn-group pull-right hidden-phone">
                <?php
                //echo $this->lists['shipping_method'];
                echo $this->lists['order_status_id'];
                //echo $this->lists['payment_status'];
                echo $this->lists['agents'];
                echo $this->pagination->getLimitBox();
                ?>
            </div>
        </div>
        <div class="clearfix"></div>
        <table class="adminlist table table-striped">
            <thead>
            <tr>
                <th width="2%" class="text_center">
                    <?php echo JHtml::_('grid.checkall'); ?>
                </th>
                <th width="5%" class="text_center">
                    <?php echo JHtml::_('grid.sort', JText::_('ESHOP_ID'), 'a.id', $this->lists['order_Dir'], $this->lists['order']); ?>
                </th>
                <th width="10%" class="text_center">
                    <?php echo JHtml::_('grid.sort', JText::_('ESHOP_ORDER_NUMBER'), 'a.order_number', $this->lists['order_Dir'], $this->lists['order']); ?>
                </th>
                <th class="text_left" width="10%">
                    <?php echo JHtml::_('grid.sort', 'Đại lý / KH', 'a.firstname', $this->lists['order_Dir'], $this->lists['order']); ?>
                </th>
                <th class="text_left" width="10%">
                  <?php echo JHtml::_('grid.sort',  JText::_('ESHOP_AGENT'), '', $this->lists['order_Dir'], $this->lists['order'] ); ?>
                </th>
                <th class="text_center" width="10%">
                    <?php echo JText::_('ESHOP_ORDER_STATUS'); ?>
                </th>
                <th class="text_center" width="10%">
                    Tạm tính
                </th>
                <!-- <th class="text_center" width="7%">
                    Phí ship
                </th> -->
                <th class="text_center" width="10%">
                    <?php echo JHtml::_('grid.sort', JText::_('ESHOP_ORDER_TOTAL'), 'a.total', $this->lists['order_Dir'], $this->lists['order']); ?>
                </th>
                <th class="text_center">
                    <?php echo JHtml::_('grid.sort', 'Thanh toán', 'a.payment_status', $this->lists['order_Dir'], $this->lists['order']); ?>

                </th>
                <!-- <th class="text_center" width="7%">
                    Trạng thái GH
                </th> -->
                <th class="text_center" width="10%">
                    <?php echo JHtml::_('grid.sort', JText::_('ESHOP_CREATED_DATE'), 'a.created_date', $this->lists['order_Dir'], $this->lists['order']); ?>
                </th>
                <th class="text_center" width="10%">
                    <?php echo JHtml::_('grid.sort', JText::_('ESHOP_MODIFIED_DATE'), 'a.modified_date', $this->lists['order_Dir'], $this->lists['order']); ?>
                </th>
                <th width="15%" class="text_center">
                    <?php echo JText::_('ESHOP_ACTION'); ?>
                </th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <td colspan="12">
                    <?php echo $this->pagination->getListFooter(); ?>
                </td>
            </tr>
            </tfoot>
            <tbody>
            <?php
            $k = 0;
            for ($i = 0, $n = count($this->items); $i < $n; $i++) {
                $row = &$this->items[$i];
                $link = JRoute::_('index.php?option=com_eshop&task=order.edit&cid[]=' . $row->id . '&previous=stockreports');
                $checked = JHtml::_('grid.id', $i, $row->id);
                ?>
                <tr class="<?php echo "row$k"; ?>">
                    <td class="text_center">
                        <?php echo $checked; ?>
                    </td>
                    <td class="text_center">
                        <a href="<?php echo $link; ?>">
                            <?php echo $row->id; ?>
                        </a>
                    </td>
                    <td class="">
                        <a href="<?php echo $link; ?>">
                            <?php echo $row->order_number; ?>
                        </a>
                        <br />
                        Kênh: <?php echo $row->channel; ?>
                    </td>
                    <td class="text_left">
                      <?php
                      if($row->customer_id > 0){
                        $userAgent = JFactory::getUser($row->customer_id);
                        echo $userAgent->name;
                      }else{
                        echo $row->firstname . ' ' . $row->lastname;
                      }

                       ?>
                        <?php //echo $row->firstname . ' ' . $row->lastname; ?>
                    </td>
                    <td class="text_left">
                      <a target="_blank" href="<?php echo JRoute::_('index.php?option=com_users&task=user.edit&id=' . (int) $row->customer_id); ?>" ><?php
                        echo $userAgent->level_tree.str_pad($userAgent->id,6,"0",STR_PAD_LEFT);

                         ?>
                       </a>

                    </td>
                    <td class="text_center">
                        <?php
                        $className = '';
                        switch ($row->order_status_id) {
                            case 8 :
                                $className = 'label-warning';
                                break;
                            case 4:
                            case 13:
                            case 9 :
                                $className = 'label-success';
                                break;
                            case 10 :
                                $className = 'label-primary';
                                break;
                            case 7:
                            case 1:
                            case 11 :
                                $className = 'label-danger';
                                break;
                        }
                        ?>
                        <span class="label <?php echo $className; ?>">
							<?php echo EshopHelper::getOrderStatusName($row->order_status_id, JComponentHelper::getParams('com_languages')->get('site', 'en-GB')); ?>
						</span>

                    </td>
                    <td>
                        <span style="color:red;">
                        <?php
                        $orderTotal = EshopHelper::getOrderTotals($row->id);
                        if (is_array($orderTotal) && $orderTotal[0]->name == 'sub_total') {
                            echo $orderTotal[0]->text;
                        } else {
                            echo 0;
                        }
                        ?>
                            </span>
                    </td>
                    <!-- <td>
                        <?php
                    $orderTotal = EshopHelper::getOrderTotals($row->id);
                    if (is_array($orderTotal) && $orderTotal[1]->name == 'eshop_ghtk') {
                        echo $orderTotal[1]->text;
                    } else {
                        echo 0;
                    }
                    ?>
                    </td> -->
                    <td class="text_center">
                        <span style="color:red; font-weight:bold;">
						<?php echo $this->currency->format($row->total, $row->currency_code, $row->currency_exchanged_value); ?>
                            </span>
                    </td>
                    <td class="text_center">
                        <?php
                        parse_str($row->payment_request, $payInfo);
                        if(@$payInfo['vnp_TxnRef']){
                            echo 'Mã đơn hàng tại VNPAY: <b>'.$payInfo['vnp_TxnRef'].'</b>';
                        }
                        ?>
                        <div>

                            <?php if ($row->payment_status == 9): ?>
                                <span class="label label-success">Đã thanh toán</span>
                                <?php if($row->payment_status == 9){
                                  echo '<i class="done-date">'.EshopHelper::renderDate($row->cms_done_date, 'd/m/Y H:i').'</i>';
                                }
                                ?>
                                <!--Mã giao dịch: <b><?/*= $row->transaction_no */?></b>-->
                            <?php elseif ($row->payment_code):
                                echo VnpayGateway::getPaymentError($row->payment_code);
                                ?>
                            <?php else: ?>
                                <span class="label label-warning">
                                  Chưa thanh toán
                                </span>
                                <?php if($row->payment_status == 1){
                                  echo '<i>Đã tải ảnh CK</i>';
                                } else{
                                  echo '<i>Chờ tải ảnh CK</i>';
                                }  ?>
                            <?php endif; ?>
                        </div>
                    </td>
                    <!-- <td>
                        <?php
                    switch ($row->shipping_status) {
                        case 0 :
                            echo '<span class="label label-warning">Chờ giao hàng</span>';
                            break;
                        case 1:
                            echo '<span class="label label-primary">Chưa tiếp nhận</span>';
                            break;
                        case -1:
                            echo '<span class="label label-danger">Hủy giao hàng</span>';
                            break;
                    }
                    ?>
                    </td> -->
                    <td class="text_center">

                        <?php if (@$row->created_date): ?>
                          <?= EshopHelper::renderDate($row->created_date, 'd/m/Y H:i') ?>
                        <?php endif; ?>

                    </td>
                    <td class="text_center">

                        <?php if (@$row->modified_date): ?>
                            <?= EshopHelper::renderDate($row->modified_date, 'd/m/Y H:i') ?>
                        <?php endif; ?>

                    </td>

                    <td class="text_center">
                        <!-- <a href="<?php echo $link; ?>"><?php echo JText::_('ESHOP_EDIT'); ?></a> -->
                        <!-- <?php if ($row->shipping_status == 0) : ?>
                        <button data-orderId="<?php echo $row->id; ?>" id="btnShip_<?php echo $row->id; ?>"
                                type="button" class="btnShip btn btn-small btn-success">Xác nhận giao hàng
                        </button>
                        <?php elseif ($row->shipping_status == 1): ?>
                        <button data-ordernumber="<?php echo $row->order_number; ?>" data-id="<?php echo $row->id; ?>"
                                id="btnCancelShip_<?php echo $row->id; ?>" type="button"
                                class="btnCancelShip btn btn-small btn-danger">Hủy giao hàng
                        </button>
                        <?php endif; ?> -->


						<?php
                        if (EshopHelper::getConfigValue('invoice_enable'))
                        {
                        ?>
							                 <a onclick="reloadPage()" target="_blank" href="<?php echo JRoute::_('index.php?option=com_eshop&task=order.downloadInvoice&cid[]=' . $row->id); ?>"><?php echo JText::_('ESHOP_DOWNLOAD_INVOICE'); ?></a>
                        <?php
                        }
                        ?>
                        <?php
                        if (EshopHelper::getConfigValue('invoice_enable'))
                        {
                        ?>
							 <br><a onclick="reloadPage()" target="_blank" href="<?php echo JRoute::_('index.php?option=com_eshop&task=order.viewDownloadInvoice&cid[]=' . $row->id); ?>">Xem và In</a>
                        <?php
                        }
                        ?>
                    </td>
                </tr>
                <?php
                $k = 1 - $k;
            }
            ?>
            </tbody>
        </table>
    </div>
    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="boxchecked" value="0"/>
    <input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>"/>
    <input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>"/>
    <?php echo JHtml::_('form.token'); ?>
</form>
<!-- Modal -->
<?php
$modal_params = array();
$modal_params['title'] = '<h4 class="modal-title">Thông tin bổ sung</h4>';
$modal_params['height'] = "400px";
$modal_params['width'] = "300px";
$modal_params['footer'] = '<button type="button" id="confirmShip" class="btn btn-success">Xác nhận</button><button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>';
$body = '
<div class="form-horizontal">
    <input type="hidden" name="orderId" id="orderId">
    <div class="control-group">
        <div class="control-label">
            <label for="pickDate">Ngày lấy hàng</label>
        </div>
        <div class="controls">' . JHTML::calendar(date("Y-m-d"), "pickDate", "pickDate", "%Y-%m-%d", array("size" => "8", "maxlength" => "10", "class" => "validate['required']",)) . '
        </div>
    </div>
    <div class="control-group">
        <div class="control-label">
            <label for="pickWorkShift">Thời gian lấy hàng</label>
        </div>
        <div class="controls">
            <select name="pickWorkShift" class="form-control" id="pickWorkShift">
                <option value="">--Chọn--</option>
                <option value="1">Buổi sáng</option>
                <option value="2">Buổi chiều</option>
                <option value="3">Buổi tối</option>
            </select>
        </div>
    </div>
    <div class="control-group">
        <div class="control-label">
            <label for="deliverWorkShift">Thời gian giao hàng</label>
        </div>
        <div class="controls">
            <select name="deliverWorkShift" class="form-control" id="deliverWorkShift">
                <option value="">--Chọn--</option>
                <option value="1">Buổi sáng</option>
                <option value="2">Buổi chiều</option>
                <option value="3">Buổi tối</option>
            </select>
        </div>
    </div>

    <div style="display: none" class="control-group">
        <div class="control-label">
            <label for="freeShip">Miễn phí giao hàng</label>
        </div>
        <div class="controls checkbox">
            <label><input type="checkbox" name="freeShip" value="1" id="freeShip"></label>
        </div>
    </div>
    <div class="control-group">
        <div class="control-label">
            <label for="note">Ghi chú</label>
        </div>
        <div class="controls">
            <textarea name="note" id="note" rows="5"></textarea>
        </div>
    </div>
</div>';
echo JHTML::_('bootstrap.renderModal', 'confirmOrderShipping', $modal_params, $body);
?>
<?php

$user   = JFactory::getUser();
$groups = $user->get('groups');
$hide_delete = 0;
foreach ($groups as $group)
{
    if($group == 7){
      $hide_delete = 1;
    }
}

?>
<style>
#toolbar-download, #toolbar-download{display: none;}
<?php if($hide_delete == 1){

?>
#toolbar-delete{display: none;}
<?php } ?>
    .modal {
        text-align: center;
        width: 50% !important;
        margin-left: 0 !important;
        left: 25% !important;
    }

    div.modal.fade.in {
        top: 20% !important;
    }

    .modal:before {
        display: inline-block;
        vertical-align: middle;
        content: " ";
        height: 100%;
    }

    .modal-header .close {
        line-height: 0;
    }

    .modal-body {
        text-align: left;
        vertical-align: middle;
        padding: 15px;
    }
</style>
<script>
    jQuery(document).ready(function(){
      jQuery('#agents_chzn input').prop('readonly', false);

    });
    function reloadPage(){
      location.reload();
    }

    if (typeof (Eshop) === 'undefined') {
        var Eshop = {};
    }
    Eshop.jQuery = jQuery.noConflict();
    Eshop.jQuery(document).ready(function ($) {
        $('.btnShip').click(function (e) {
            let orderId = $(this).data('orderid');
            $('#orderId').val(orderId);
            e.preventDefault();
            $('#confirmOrderShipping').modal({
                backdrop: 'static',
                keyboard: false,
            });
        });
        $('#confirmShip').click(function () {
            let siteUrl = '<?php echo EshopHelper::getSiteUrl(); ?>';
            let orderId = $('#orderId').val();
            let noteShip = $('#note').val();
            let pickDateShip = $('#pickDate').val();
            let pickWorkShift = $('#pickWorkShift').val();
            let deliverWorkShift = $('#deliverWorkShift').val();
            let freeShip = $('#freeShip').is(':checked') ? 1 : 0;
            if (!orderId) {
                $('#msgBlock').show().addClass('alert-danger').text('Không có đơn hàng nào được chọn!');
                return false;
            }
            $.ajax({
                url: siteUrl + 'api/users/shopshipghtk',
                method: 'post',
                type: 'json',
                data: {
                    orderId: orderId,
                    note: noteShip,
                    pickDate: pickDateShip,
                    pickWorkShift: pickWorkShift,
                    deliverWorkShift: deliverWorkShift,
                    freeShip: freeShip
                },
                beforeSend: function () {
                    jQuery('button[id="confirmShip"]').after('<span class="wait">&nbsp;<img src="<?php echo JURI::root(); ?>administrator/components/com_eshop/assets/images/loading.gif" alt="" /></span>');
                },
                complete: function () {
                    jQuery('.wait').remove();
                },
                success: function (json) {
                    let result = json.data;
                    let msgBlock = $('#msgBlock');
                    let obj = JSON.parse(result);
                    if (obj.success) {
                        $('#confirmOrderShipping').modal('hide');
                        let html = '<h3>Tạo đơn hàng thành công</h3>';
                        html += '<p>Phí vận chuyển: ' + obj.order.fee + '</p>';
                        html += '<p>Ngày lấy hàng: ' + obj.order.estimated_pick_time + '</p>';
                        html += '<p>Ngày giao hàng: ' + obj.order.estimated_deliver_time + '</p>';
                        msgBlock.show().addClass('alert-success').html(html);
                        let ghtkData = {
                            shipping_tracking_number: obj.order.label,
                            estimated_pick_time: obj.order.estimated_pick_time,
                            estimated_deliver_time: obj.order.estimated_deliver_time,
                            ref_fee: obj.order.fee,
                            status_id: 9,
                            orderId: orderId
                        };
                        $.ajax({
                            url: "<?php echo JURI::root(); ?>administrator/index.php?option=com_eshop&task=order.updateShipOrder",
                            type: 'post',
                            data: ghtkData,
                            dataType: 'json',
                            success: function (json) {
                                window.location.reload();
                            },
                            error: function (xhr, ajaxOptions, thrownError) {
                                console.log(xhr.responseText);
                            }
                        });
                    } else {
                        $('#confirmOrderShipping').modal('hide');
                        $('#note').val('');
                        $('#pickDate').val("<?php echo date('Y-m-d'); ?>");
                        $('#pickWorkShift').val('');
                        $('#deliverWorkShift').val('');
                        $('#freeShip').prop('checked', false);
                        $('.wait').remove();
                        msgBlock.show().addClass('alert-danger').text(obj.message);
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            })
        });
        $('.btnCancelShip').click(function () {
            let siteUrl = '<?php echo EshopHelper::getSiteUrl(); ?>';
            let orderNumber = $(this).data('ordernumber');
            let orderId = $(this).data('id');
            if (confirm("Bạn có chắc chắn hủy giao hàng!")) {
                if (!orderNumber) {
                    $('#msgBlock').show().addClass('alert-danger').text('Không có đơn hàng nào được chọn!');
                    return false;
                }
                $.ajax({
                    url: siteUrl + 'api/users/shopshipghtk',
                    method: 'post',
                    type: 'json',
                    data: {partner_id: orderNumber},
                    beforeSend: function () {
                        jQuery('button[id="btnCancelShip_' + orderId + '"]').after('<span class="wait">&nbsp;<img src="<?php echo JURI::root(); ?>administrator/components/com_eshop/assets/images/loading.gif" alt="" /></span>');
                    },
                    complete: function () {
                        jQuery('.wait').remove();
                    },
                    success: function (json) {
                        let result = json.data;
                        let msgBlock = $('#msgBlock');
                        if (result.success) {
                            msgBlock.show().addClass('alert-success').html('Hủy giao hàng thành công!');
                            var ghtkData = {
                                status_id: 2,
                                orderId: orderId
                            };
                            $.ajax({
                                url: "<?php echo JURI::root(); ?>administrator/index.php?option=com_eshop&task=order.cancelShipOrder&tmpl=component",
                                type: 'post',
                                data: ghtkData,
                                dataType: 'json',
                                success: function (json) {
                                    window.location.reload();
                                },
                                error: function (xhr, ajaxOptions, thrownError) {
                                    console.log(xhr.responseText);
                                }
                            });
                        } else {
                            $('.wait').remove();
                            msgBlock.show().addClass('alert-danger').text(result.message);
                        }
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    }
                })
            }
        });
    });
</script>
