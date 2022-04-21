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
defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');
JHtml::_('formbehavior.chosen', 'select');
?>
<style>
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
    #toolbar-download, #toolbar-edit, #toolbar-delete, .nav-tabs {
        display: none;
    }
    .report h3, .report h4 {
        text-align: center;
    }
    .report span {
        color: red;
    }
    .report ul {
        padding: 0;
        margin: 0;
        text-align: center;
    }
    .report ul li {
        list-style: none;
        padding: 5px 0;
        margin: 0 5px 15px 5px;
        display: inline-block;
        width: 23%;
        border: 1px solid #c6c6c6;
    }
    .report p {
        margin: 0;
    }
    .status-title{
      color:#3071a9;
      font-size: 15px;
      font-weight: bold;
    }
    .statusList li{
      height: 73px;
      float: left;
    }
    .rows-border{
      background: orange;
      color:#FFF!important;
      padding: 2px 5px 2px 5px;
      border-radius: 20px;
    }
    .time-title{
      color:#3071a9!important;
    }
    .subhead-collapse{
      display: none;
    }
    #agents_chzn .chzn-search {
      position: relative!important;
      left: 0!important;
    }
</style>

<?php
//https://longbill.github.io/jquery-date-range-picker/
$document = JFactory::getDocument();
$document->addScript('/administrator/components/com_eshop/assets/js/moment.js');
$document->addScript('/administrator/components/com_eshop/assets/js/jquery.daterangepicker.min.js');

$document->addStyleSheet('/administrator/components/com_eshop/assets/css/daterangepicker.css');
?>
<?php
$input = JFactory::getApplication()->input;
$datarange = $input->get('daterange', '', 'STRING');
if ($datarange == '') {
    //$datarange = date('d/m/Y') . ' - ' . date('d/m/Y');
}
?>
<div class="report">
    <h4><span class="time-title">Thời gian đặt hàng</span>:  <?= $datarange ? $datarange : 'Tất cả' ?></h4>
        <h3>Tổng doanh thu: <span><?= number_format($this->reports['total']) ?> đ</span></h3>
        <ul class="statusList">
            <?php foreach ($this->reports['statusList'] as $status): ?>
                <li><?= $status['orderstatus_name'] ?>
                    <p><b>Số lượng</b>: <span class="rows-border"><?= $status['rows'] ?></span></p>
                    <p><b>Doanh thu</b>: <span><?= number_format($status['total']) ?> đ</span></p>
                </li>
            <?php endforeach; ?>
        </ul>

</div>

<form style="clear:both;" action="index.php?option=com_eshop&view=stockreports" method="post" name="adminForm" id="adminForm">
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

            <div class="btn-group pull-right hidden-phone">
                <?php
                //				    echo $this->lists['shipping_method'];

                //echo $this->lists['stock'];

                echo $this->lists['agents'];
                echo $this->lists['order_status_id'];
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
                <th class="text_left" width="20%">
                    <?php echo JHtml::_('grid.sort', JText::_('ESHOP_CUSTOMER'), 'a.firstname', $this->lists['order_Dir'], $this->lists['order']); ?>
                </th>
                <th class="text_left" width="10%">
                    <?php echo JHtml::_('grid.sort', JText::_('ESHOP_AGENT'), '', $this->lists['order_Dir'], $this->lists['order']); ?>
                </th>
                <th class="text_center" width="10%">
                    <?php echo JText::_('ESHOP_ORDER_STATUS'); ?>
                </th>
                <th class="text_center" width="10%">
                    <?php echo JHtml::_('grid.sort', JText::_('ESHOP_ORDER_TOTAL'), 'a.total', $this->lists['order_Dir'], $this->lists['order']); ?>
                </th>
                <th class="text_center">
                    <?php echo JHtml::_('grid.sort', 'Thanh toán', 'a.payment_status', $this->lists['order_Dir'], $this->lists['order']); ?>

                </th>
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
                <td colspan="10">
                    <?php echo $this->pagination->getListFooter(); ?>
                </td>
            </tr>
            </tfoot>
            <tbody>
            <?php
            $k = 0;
            for ($i = 0, $n = count($this->items); $i < $n; $i++) {
                $row = &$this->items[$i];
                $link = JRoute::_('index.php?option=com_eshop&task=order.edit&cid[]=' . $row->id.'&page=stockreports');
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
                    <td class="text_center">
                        <a target="_blank" href="<?php echo $link; ?>">
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
                                <?php
                                  echo '<i class="done-date">'.EshopHelper::renderDate($row->cms_done_date, 'd/m/Y H:i').'</i>';

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
                    <td class="text_center">
                      <?php if (@$row->created_date): ?>
                        <?= EshopHelper::renderDate($row->created_date, 'd/m/Y H:i:s') ?>
                      <?php endif; ?>
                    </td>
                    <td class="text_center">
                      <?php if (@$row->modified_date): ?>
                          <?= EshopHelper::renderDate($row->modified_date, 'd/m/Y H:i:s') ?>
                      <?php endif; ?>
                    </td>

                    <td class="text_center">
                        <a href="<?php echo $link; ?>"><?php echo JText::_('ESHOP_EDIT'); ?></a>
                        <?php
                                    if (EshopHelper::getConfigValue('invoice_enable'))
                                    {
                                    ?>
                           &nbsp;|&nbsp;<a href="<?php echo JRoute::_('index.php?option=com_eshop&task=order.downloadInvoice&cid[]=' . $row->id); ?>"><?php echo JText::_('ESHOP_DOWNLOAD_INVOICE'); ?></a>
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

<script>
    jQuery(document).ready(function(){
      jQuery('#agents_chzn input').prop('readonly', false);

    });
    var jq = jQuery.noConflict();
    jq(function ($) {


        $('input[name="daterange"]').dateRangePicker({
            startOfWeek: 'monday',
            separator: ' - ',
            format: 'DD/MM/YYYY',
            autoClose: true,

            time: {
                enabled: false
            }
        }).bind('datepicker-apply', function (event, obj) {
            /* This event will be triggered when user clicks on the apply button */
            console.log(obj);
        })
            .bind('datepicker-close', function () {
                /* This event will be triggered before date range picker close animation */
                console.log('before close');
            });


    });

</script>
