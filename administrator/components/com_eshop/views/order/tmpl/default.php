<?php
/**
 * @version        3.1.0
 * @package        Joomla
 * @subpackage    EShop
 * @author    Giang Dinh Truong
 * @copyright    Copyright (C) 2012 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die();
//EshopHelper::chosen();

use api\model\SUtil; ?>
<style>
  .image_banking{
    padding-top: 10px;
    padding-bottom: 10px;
  }
  .accountant span{
    padding: 2px 4px 2px 4px;
  }
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
  .price{
    color:red;
  }
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
  .order-detail tr{ line-height: 25px;}
  table#commission {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 100%;
  }

  table#commission td, table#commission th {
    border: 1px solid #dddddd;
    text-align: left;
    padding: 8px;
  }

  table#commission tr:nth-child(even) {
    background-color: #ececec;
  }
  #commission{
    margin-top:10px;
  }
</style>

<?php
//print_r($this->item->id);
//EshopHelper::updateCommissionFields($this->item->id, $this->item->customer_id,$this->item->total);
?>
<div id="msgBlock" style="display: none" class="alert"></div>
<form action="index.php" method="post" name="adminForm" id="adminForm">
    <?php
    echo JHtml::_('bootstrap.startTabSet', 'order', array('active' => 'general-page'));
    echo JHtml::_('bootstrap.addTab', 'order', 'general-page', JText::_('ESHOP_GENERAL', true));
    ?>
    <div class="span12">
        <table class="adminlist table table-bordered" style="text-align: center;">
            <thead>
            <tr>
              <th class="text_left">STT</th>
                <th class="text_left"><?php echo JText::_('ESHOP_PRODUCT_NAME'); ?></th>
                <th class="text_left">Hình</th>
                <th class="text_left"><?php echo JText::_('ESHOP_MODEL'); ?></th>
                <th class="text_left"><?php echo JText::_('ESHOP_QUANTITY'); ?></th>
                <th class="text_right"><?php echo JText::_('ESHOP_UNIT_PRICE'); ?></th>
                <!-- <th class="text_right"><?php echo JText::_('ESHOP_STOCK'); ?></th> -->
                <!-- <th class="text_right"><?php echo JText::_('ESHOP_ORDER_STATUS'); ?></th> -->
                <th class="text_right"><?php echo JText::_('ESHOP_TOTAL'); ?></th>
            </tr>
            </thead>
            <tbody>
            <?php
            $rootUri = JUri::root();
            $k = 0;
            foreach ($this->lists['order_products'] as $product) {
                $options = $product->orderOptions;
                $k = $k + 1;
                ?>
                <tr>
                    <td width="5%"><?php echo $k; ?></td>
                    <td class="text_left">
                        <?php
                        echo '<a href="index.php?option=com_eshop&task=product.edit&cid[]='.$product->product_id.'">' . $product->product_name . '</a>';
                        for ($i = 0; $n = count($options), $i < $n; $i++) {
                            if ($options[$i]->option_type == 'File' && $options[$i]->option_value != '') {
                                echo '<br />- ' . $options[$i]->option_name . ': <a href="index.php?option=com_eshop&task=order.downloadFile&id=' . $options[$i]->id . '">' . $options[$i]->option_value . '</a>';
                            } else {
                                echo '<br />- ' . $options[$i]->option_name . ': ' . $options[$i]->option_value . (isset($options[$i]->sku) && $options[$i]->sku != '' ? ' (' . $options[$i]->sku . ')' : '');
                            }
                        }
                        ?>
                    </td>
                    <td class="text_left">
                      <a href="index.php?option=com_eshop&task=product.edit&cid[]=<?php echo $product->product_id; ?>">
                      <?php
                      if (JFile::exists(JPATH_ROOT . '/media/com_eshop/products/' . $product->product_image)) {
                          $viewImage = JFile::stripExt($product->product_image) . '-100x100.' . JFile::getExt($product->product_image);

                          if (Jfile::exists(JPATH_ROOT . '/media/com_eshop/products/resized/' . $viewImage)) {
                              ?>
                              <img src="<?php echo $rootUri . 'media/com_eshop/products/resized/' . $viewImage; ?>"
                                   width="50"/>
                              <?php
                          } else {
                              ?>
                              <img src="<?php echo $rootUri . 'media/com_eshop/products/' . $row->product_image; ?>"
                                   width="50"/>
                              <?php
                          }
                      }
                      ?>
                      </a>
                    </td>
                    <td class="text_left"><?php echo $product->product_sku; ?></td>
                    <td class="text_left"><?php echo $product->quantity; ?> x <?php echo $product->unit; ?></td>
                    <td class="text_right">
                        <?php echo $product->price; ?>
                    </td>
                    <!-- <td class="text_right">
                        <?php echo $product->stock_name; ?>
                    </td> -->
                    <!-- <td class="text_right">
                        <?php echo EshopHelper::getOrderStatusName($product->status_id, JComponentHelper::getParams('com_languages')->get('site', 'en-GB')); ?>
                    </td> -->
                    <td class="text_right price">
                        <?php echo $product->total_price; ?>
                    </td>
                </tr>
                <?php
            }
            foreach ($this->lists['order_totals'] as $total) {
                ?>
                <tr>
                    <td colspan="6" class="text_right"><?php echo $total->title; ?>:</td>
                    <td class="text_right"><span
                                style="color: #ee0000; font-weight: bold"><?php echo $total->text; ?></span></td>
                </tr>
                <?php
            }
            ?>
            <!-- <tr>
                <td class="text_right" colspan="5">
                    <?php if ($this->item->shipping_status == 0) : ?>
                        <button data-orderid="<?php echo $this->item->id ?>" type="button"
                                class="btn btn-small btn-success btnShip">Xác nhận giao hàng
                        </button>
                    <?php elseif ($this->item->shipping_status == 1): ?>
                        <button data-ordernumber="<?php echo $this->item->order_number; ?>"
                                data-id="<?php echo $this->item->id; ?>" id="btnCancelShip" type="button"
                                class="btnCancelShip btn btn-small btn-danger">Hủy giao hàng
                        </button>
                    <?php endif; ?>
                </td>
            </tr> -->
            </tbody>
        </table>
        <table class="admintable order-detail adminform" style="width: 100%;">

            <tr>
                <td class="key">
                    <?php echo JText::_('ESHOP_ORDER_NUMBER'); ?>
                </td>
                <td>
                    <?php echo $this->item->order_number; ?>
                </td>
            </tr>
            <tr>
      				<td class="key">
      					Đại lý / KH
      				</td>
      				<td>
                <?php
                if($this->item->customer_id > 0){
                  $userAgent = JFactory::getUser($this->item->customer_id);
                  echo $userAgent->name;
                }else{
                  echo $this->item->firstname . ' ' . $this->item->lastname;
                }

                 ?>
      				</td>
      			</tr>
            <tr>
      				<td class="key">
      					Mã TV
      				</td>
      				<td>
                <a target="_blank" href="<?php echo JRoute::_('index.php?option=com_users&task=user.edit&id=' . (int) $this->item->customer_id); ?>" ><?php
                  echo $userAgent->level_tree.str_pad($userAgent->id,6,"0",STR_PAD_LEFT);

                   ?>
                 </a>
      				</td>
      			</tr>
            <tr>
                <td class="key">
                    Kênh
                </td>
                <td>
                  <?php echo $this->item->channel; ?>
                </td>
            </tr>
            <tr>
                <td class="key">
                    Thời gian nhận hàng
                </td>
                <td>

                    <?= EshopHelper::renderDate($this->item->delivery_date, 'd/m/Y') ?>
                    <?php if($this->item->delivery_hour):?>
                    <?php echo ', '.$this->item->delivery_hour; ?>
                    <?php endif;?>
                </td>
            </tr>
            <tr>
                <td class="key">
                    Ngày tạo
                </td>
                <td>


                  <?php if (@$this->item->created_date): ?>
                      <?= EshopHelper::renderDate($this->item->created_date, 'd/m/Y H:i:s') ?>
                  <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td class="key">
                    Ngày sửa
                </td>
                <td>
                  <?php if (@$this->item->modified_date): ?>
                      <?= EshopHelper::renderDate($this->item->modified_date, 'd/m/Y H:i:s') ?>
                  <?php endif; ?>

                </td>
            </tr>
            <tr>
                <td class="key">
                    <?php echo JText::_('ESHOP_ORDER_PAYMENT_METHOD'); ?>
                </td>
                <td>
                    <?php echo JText::_($this->item->payment_method_title); ?>
                </td>
            </tr>
            <tr>
                <td class="key">
                    <?php echo 'Trạng thái thanh toán'; ?>
                </td>
                <td>

                    <span class="label <?= $this->item->payment_status == 9 ? 'label-success' : 'label-warning' ?>">
                      <?= $this->item->payment_status == 9  ? 'Đã thanh toán' : 'Chưa thanh toán' ?>
                    </span>
                    <?php
                    if($this->item->payment_status == 9){
                      echo EshopHelper::renderDate($this->item->cms_done_date, 'd/m/Y H:i');
                    }
                    ?>
                    <?php if($this->item->payment_status == 1){
                      echo '<i>Đã tải ảnh CK</i>';
                    } elseif($this->item->payment_status == 0){
                      echo '<i>Chờ tải ảnh CK</i>';
                    }  ?>

                    <?php if ($this->item->payment_status == 0 && $this->item->payment_method == 'os_onepay'): ?>
                    <?= SUtil::getPaymentError($this->item->payment_code) ?>
                    <?php endif; ?>
                </td>
            </tr>
            <?php if ($this->item->payment_method == 'os_onepay' && $this->item->transaction_no): ?>
                <tr>
                    <td class="key">
                        ID thanh toán
                    </td>
                    <td>
                        <?= $this->item->transaction_no ?>
                    </td>
                </tr>
            <?php endif; ?>

            <?php
            if ($this->item->payment_method == "os_creditcard") {
                $params = new JRegistry($this->item->params);
                ?>
                <tr>
                    <td class="key">
                        <?php echo JText::_('ESHOP_FIRST_PART_CREDIT_OF_CARD_NUMBER'); ?>
                    </td>
                    <td>
                        <?php echo $params->get('card_number'); ?>
                    </td>
                </tr>
                <tr>
                    <td class="key">
                        <?php echo JText::_('ESHOP_CARD_EXPIRATION_DATE'); ?>
                    </td>
                    <td>
                        <?php echo $params->get('exp_date'); ?>
                    </td>
                </tr>
                <tr>
                    <td class="key">
                        <?php echo JText::_('ESHOP_CARD_CVV_CODE'); ?>
                    </td>
                    <td>
                        <?php echo $params->get('cvv'); ?>
                    </td>
                </tr>
                <?php
            }
            ?>
            <tr>
                <td class="key">
                    <?php echo JText::_('ESHOP_ORDER_SHIPPING_METHOD'); ?>
                </td>
                <td>
                    <?php echo $this->item->shipping_method_title; ?>
                </td>
            </tr>
            <tr>
                <td class="key">
                    <?php echo JText::_('ESHOP_ORDER_STATUS'); ?>
                </td>
                <td>
                    <?php echo $this->lists['order_status_id']; ?>
                </td>
            </tr>
            <tr>
                <td class="key">
                    <?php echo JText::_('ESHOP_ID'); ?>
                </td>
                <td>
                    <?php echo $this->item->id; ?>
                </td>
            </tr>
            <tr>
                <td class="key">
                    <?php echo JText::_('ESHOP_SEND_NOTIFICATION_EMAIL'); ?>
                </td>
                <td>
                    <label class="checkbox">
                        <input type="checkbox" name="send_notification_email" value="1"/><span
                                class="help">(<?php echo JText::_('ESHOP_SEND_NOTIFICATION_EMAIL_HELP'); ?>)</span>
                    </label>
                </td>
            </tr>
            <!-- <tr>
				<td class="key">
					<?php echo JText::_('ESHOP_SHIPPING_TRACKING_NUMBER'); ?>
				</td>
				<td>
					<input class="input-large" type="text" name="shipping_tracking_number" id="shipping_tracking_number" value="<?php echo $this->item->shipping_tracking_number; ?>" />
				</td>
			</tr> -->
            <!-- <tr>
				<td class="key">
					<?php echo JText::_('ESHOP_SHIPPING_TRACKING_URL'); ?>
				</td>
				<td>
					<input class="input-xxlarge" type="text" name="shipping_tracking_url" id="shipping_tracking_url" value="<?php echo $this->item->shipping_tracking_url; ?>" />
				</td>
			</tr> -->
            <tr style="display:none;">
                <td class="key">
                    <?php echo JText::_('ESHOP_SEND_SHIPPING_NOTIFICATION_EMAIL'); ?>
                </td>
                <td>
                    <label class="checkbox">
                        <input type="checkbox" name="send_shipping_notification_email" value="1"/><span
                                class="help">(<?php echo JText::_('ESHOP_SEND_SHIPPING_NOTIFICATION_EMAIL_HELP'); ?>)</span>
                    </label>
                </td>
            </tr>
            <tr>
                <td class="key">
                    <?php echo JText::_('ESHOP_USER_IP'); ?>
                </td>
                <td>
                    <?php echo $this->item->user_ip; ?>
                </td>
            </tr>
            <tr>
                <td class="key" valign="top">
                    <?php echo JText::_('ESHOP_COMMENT'); ?>
                </td>
                <td>
                    <textarea name="comment" cols="30" rows="5"><?php echo $this->item->comment; ?></textarea>
                </td>
            </tr>
            <?php
            if (EshopHelper::getConfigValue('delivery_date')) {
                ?>
                <!-- <tr>
                    <td class="key">
                        <?php echo JText::_('ESHOP_DELIVERY_DATE'); ?>
                    </td>
                    <td>
                        <?php echo JHtml::_('date', $this->item->delivery_date, 'd/m/Y', null); ?>
                    </td>
                </tr> -->
                <?php
            }
            ?>
            <?php if (@$this->item->cms_done_date): ?>
            <tr>
                <td class="key">
                    Ngày Kế Toán xác nhận
                </td>
                <td class="accountant">
                    <?= EshopHelper::renderDate($this->item->cms_done_date, 'd/m/Y H:i:s') ?>
                </td>
            </tr>
            <?php endif; ?>
            <tr>
                <td class="key" valign="top">
                    Bằng chứng thanh toán <br>
                    <i>(Hình ảnh chụp chuyển khoản)</i>
                </td>
                <td class="accountant">
                    <?php
                    $showButtonConfirmBanking = '';
                    $userCurrent = JFactory::getUser();
                    $group = $userCurrent->get('groups');



                    // echo  $this->item->payment_image == '' ?
                    // '<span class="btn-warning">Đại lý chưa cập nhật</span>
                    //
                    // ':
                    // '<span class="btn-success">Đại lý đã cập nhật</span>
                    // <div class="image_banking">
                    //  <a target="_blank" href="'.JURI::root().'images/payment/'.$this->item->payment_image.'"><img width="250" src="'.JURI::root().'images/payment/'.$this->item->payment_image.'" /></a>
                    // </div>
                    // <div class="confirm_banking">
                    // <br>
                    // '.$showButtonConfirmBanking.'
                    // </div>
                    // ';
                    ?>



                    <?php
                    if($this->item->payment_image != ''){
                      echo $payment_image = '<div class="image_banking">
                         <a target="_blank" href="'.JURI::root().'images/payment/'.$this->item->payment_image.'"><img width="250" src="'.JURI::root().'images/payment/'.$this->item->payment_image.'" /></a>
                        </div>';
                    }
                    ?>

                    <?php
                    // $this->item->payment_status = 0;
                    if($this->item->payment_status == 0){
                      $payment_status = '<span class="btn-danger">Chờ Đại lý cập nhật bằng chứng</span>';
                    }
                    if($this->item->payment_status == 1){
                      $payment_status = '<span class="btn-warning">Đại lý đã cập nhật bằng chứng</span>';
                    }
                    if($this->item->payment_status == 9){
                      $payment_status = '<span class="btn-success">Kế toán xác nhận đã thanh toán</span>';
                    }
                    echo $payment_status;
                    ?>

                    <?php

                    if(($group['6'] == 6 || $group['7'] == 7) && ($this->item->payment_status == 0 || $this->item->payment_status == 1)){
                      echo $showButtonConfirmBanking = '
                      <div class="confirm_banking">
                      <br>
                      <button  type="button" class="btn btn-primary " id="confirmBanking">Xác nhận đã nhận thanh toán</button>
                      <i>(Kế Toán xác nhận đã nhận được tiền chuyển khoản của Đại lý)</i>
                      </div>
                      ';
                    }else{
                      echo $showButtonConfirmBanking = '';
                    }
                     ?>
                </td>
            </tr>
            <?php if($this->item->cms_active > 0){ ?>

            <tr>
                <td class="key" valign="top">
                    Hoa hồng các cấp
                </td>
                <td>

                  <table id="commission" >
                    <tr>
                      <th>Đại lý C1</th>
                      <th>Đại lý C2</th>
                      <th>Đại lý C3</th>
                      <th>Đại lý C4</th>
                      <th>Đại lý C5</th>
                    </tr>
                    <tr>
                      <td>
                        <?php
                        if($this->item->cms_c1 > 0){
                        ?>
                        <a target="_blank" href="<?php echo JRoute::_('index.php?option=com_users&task=user.edit&id=' . (int) $this->item->cms_c1); ?>" >
          									<?php echo JFactory::getUser($this->item->cms_c1)->level_tree.str_pad(JFactory::getUser($this->item->cms_c1)->id,6,"0",STR_PAD_LEFT);  ?>
          							</a>
                        <br>
                        <?php echo JFactory::getUser($this->item->cms_c1)->username  ?><br>
                        <?php echo JFactory::getUser($this->item->cms_c1)->name; ?>
                        <?php } ?>
                      </td>
                      <td>
                        <?php
                        if($this->item->cms_c2 > 0){
                        ?>
                        <a target="_blank" href="<?php echo JRoute::_('index.php?option=com_users&task=user.edit&id=' . (int) $this->item->cms_c2); ?>" >
                            <?php echo JFactory::getUser($this->item->cms_c2)->level_tree.str_pad(JFactory::getUser($this->item->cms_c2)->id,6,"0",STR_PAD_LEFT);  ?>
                        </a>
                        <br>
                        <?php echo JFactory::getUser($this->item->cms_c2)->username  ?><br>
                        <?php echo JFactory::getUser($this->item->cms_c2)->name; ?>
                        <?php } ?>
                      </td>
                      <td>
                        <?php
                        if($this->item->cms_c3 > 0){
                        ?>
                        <a target="_blank" href="<?php echo JRoute::_('index.php?option=com_users&task=user.edit&id=' . (int) $this->item->cms_c3); ?>" >
                            <?php echo JFactory::getUser($this->item->cms_c3)->level_tree.str_pad(JFactory::getUser($this->item->cms_c3)->id,6,"0",STR_PAD_LEFT);  ?>
                        </a>
                        <br>
                        <?php echo JFactory::getUser($this->item->cms_c3)->username  ?><br>
                        <?php echo JFactory::getUser($this->item->cms_c3)->name; ?>
                        <?php } ?>
                      </td>
                      <td>
                        <?php
                        if($this->item->cms_c4 > 0){
                        ?>
                        <a target="_blank" href="<?php echo JRoute::_('index.php?option=com_users&task=user.edit&id=' . (int) $this->item->cms_c4); ?>" >
                            <?php echo JFactory::getUser($this->item->cms_c4)->level_tree.str_pad(JFactory::getUser($this->item->cms_c4)->id,6,"0",STR_PAD_LEFT);  ?>
                        </a>
                        <br>
                        <?php echo JFactory::getUser($this->item->cms_c4)->username  ?><br>
                        <?php echo JFactory::getUser($this->item->cms_c4)->name; ?>
                        <?php } ?>
                      </td>
                      <td>
                        <?php
                        if($this->item->cms_c5 > 0){
                        ?>
                        <a target="_blank" href="<?php echo JRoute::_('index.php?option=com_users&task=user.edit&id=' . (int) $this->item->cms_c5); ?>" >
                            <?php echo JFactory::getUser($this->item->cms_c5)->level_tree.str_pad(JFactory::getUser($this->item->cms_c5)->id,6,"0",STR_PAD_LEFT);  ?>
                        </a>
                        <br>
                        <?php echo JFactory::getUser($this->item->cms_c5)->username  ?><br>
                        <?php echo JFactory::getUser($this->item->cms_c5)->name; ?>
                        <?php } ?>
                      </td>
                    </tr>
                    <tr>
                      <td><?php echo $this->item->cms_discount_c1;  ?>%</td>
                      <td><?php echo $this->item->cms_discount_c2;  ?>%</td>
                      <td><?php echo $this->item->cms_discount_c3;  ?>%</td>
                      <td><?php echo $this->item->cms_discount_c4;  ?>%</td>
                      <td><?php echo $this->item->cms_discount_c5;  ?>%</td>
                    </tr>
                    <tr class="price">
                      <td><?php echo number_format($this->item->cms_money_c1,0,".","."); ?></td>
                      <td><?php echo number_format($this->item->cms_money_c2,0,".",".");  ?></td>
                      <td><?php echo number_format($this->item->cms_money_c3,0,".",".");  ?></td>
                      <td><?php echo number_format($this->item->cms_money_c4,0,".",".");  ?></td>
                      <td><?php echo number_format($this->item->cms_money_c5,0,".",".");  ?></td>
                    </tr>
                  </table>

                </td>
            </tr>
            <tr>
                <td class="key">
                    Trạng thái Hoa hồng
                </td>
                <td class="accountant">
                    <?php echo  $this->item->cms_status == 1 ? '<span class="btn-success">Kế toán đã xác nhận</span>':'<span class="btn-warning">Kế toán chưa xác nhận</span>'; ?>
                </td>
            </tr>
          <?php } ?>

        </table>
    </div>
    <?php
    echo JHtml::_('bootstrap.endTab');
    /*
   echo JHtml::_('bootstrap.addTab', 'order', 'customer-details-page', JText::_('ESHOP_ORDER_CUSTOMER_DETAILS', true));
   ?>
   <table class="admintable adminform" style="width: 100%;">
       <tr>
           <td class="key">
               <?php echo JText::_('ESHOP_CUSTOMER'); ?>
           </td>
           <td>
               <?php
               if ($this->item->customer_id)
               {
                   echo $this->lists['customer_id'];
               }
               else
               {
                   echo $this->item->firstname . ' ' . $this->item->lastname;
               }
               ?>
           </td>
       </tr>
       <tr>
           <td class="key">
               <?php echo JText::_('ESHOP_CUSTOMERGROUP'); ?>
           </td>
           <td>
               <?php echo $this->lists['customergroup_id']; ?>
           </td>
       </tr>
       <tr>
           <td class="key">
               <span class="required">*</span>
               <?php echo JText::_('ESHOP_FIRST_NAME'); ?>
           </td>
           <td>
               <input class="input-memdium" type="text" name="firstname" id="firstname" maxlength="32" value="<?php echo $this->item->firstname; ?>" />
           </td>
       </tr>
       <tr>
           <td class="key">
               <span class="required">*</span>
               <?php echo JText::_('ESHOP_LAST_NAME'); ?>
           </td>
           <td>
               <input class="input-memdium" type="text" name="lastname" id="lastname" maxlength="32" value="<?php echo $this->item->lastname; ?>" />
           </td>
       </tr>
       <tr>
           <td class="key">
               <span class="required">*</span>
               <?php echo JText::_('ESHOP_EMAIL'); ?>
           </td>
           <td>
               <input class="input-memdium" type="text" name="email" id="email" maxlength="96" value="<?php echo $this->item->email; ?>" />
           </td>
       </tr>
       <tr>
           <td class="key">
               <span class="required">*</span>
               <?php echo JText::_('ESHOP_TELEPHONE'); ?>
           </td>
           <td>
               <input class="input-memdium" type="text" name="telephone" id="telephone" maxlength="32" value="<?php echo $this->item->telephone; ?>" />
           </td>
       </tr>
       <tr>
           <td class="key">
               <?php echo JText::_('ESHOP_FAX'); ?>
           </td>
           <td>
               <input class="input-memdium" type="text" name="fax" id="fax" maxlength="32" value="<?php echo $this->item->fax; ?>" />
           </td>
       </tr>
   </table>
   <?php
   echo JHtml::_('bootstrap.endTab');
   */
    echo JHtml::_('bootstrap.addTab', 'order', 'payment-details-page', JText::_('ESHOP_ORDER_PAYMENT_DETAILS', true));
    ?>
    <table class="admintable adminform" style="width: 100%;">
        <?php
        echo $this->billingForm->render(false);
        ?>
    </table>
    <?php
    echo JHtml::_('bootstrap.endTab');
    echo JHtml::_('bootstrap.addTab', 'order', 'shipping-details-page', JText::_('ESHOP_ORDER_SHIPPING_DETAILS', true));
    ?>
    <table class="admintable adminform" style="width: 100%;">
        <?php
        echo $this->shippingForm->render(false);
        ?>
        <!-- <tr>
            <td class="text_right" colspan="5">
                <?php if ($this->item->shipping_status == 0) : ?>
                    <button data-orderid="<?php echo $this->item->id ?>" type="button"
                            class="btn btn-small btn-success btnShip">Xác nhận giao hàng
                    </button>
                <?php elseif ($this->item->shipping_status == 1): ?>
                    <button data-ordernumber="<?php echo $this->item->order_number; ?>"
                            data-id="<?php echo $this->item->id; ?>" id="btnCancelShip" type="button"
                            class="btnCancelShip btn btn-small btn-danger">Hủy giao hàng
                    </button>
                <?php endif; ?>
            </td>
        </tr> -->
    </table>
    <?php
    echo JHtml::_('bootstrap.endTab');
    echo JHtml::_('bootstrap.endTabSet');
    ?>
    <?php echo JHtml::_('form.token'); ?>
    <input type="hidden" name="option" value="com_eshop"/>
    <input type="hidden" name="cid[]" value="<?php echo intval($this->item->id); ?>"/>
    <input type="hidden" name="task" value=""/>

</form>

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

<?= $this->loadTemplate('history') ?>

<script type="text/javascript">
    Joomla.submitbutton = function (pressbutton) {
        var form = document.adminForm;
        if (pressbutton == 'order.cancel') {
            Joomla.submitform(pressbutton, form);
            return;
        } else {
            Joomla.submitform(pressbutton, form);
        }
    }

    if (typeof (Eshop) === 'undefined') {
        var Eshop = {};
    }


    jQuery('#confirmBanking').click(function () {

        var siteUrl = '<?php echo JURI::base(); ?>';
        var payment_status = 9;

        var r = confirm("Bạn có chắc đã nhận được chuyển khoản của Người đặt hàng?");
        if (r == true) {
          jQuery.ajax({
              url: siteUrl + 'index.php?option=com_eshop&task=order.confirmBanking',
              method: 'POST',
              type: 'text',
              data: {
                  order_id:<?php echo $this->item->id; ?>,
                  payment_status: payment_status
              },
              beforeSend: function () {
                  jQuery('button[id="confirmBanking"]').after('<span class="wait">&nbsp;<img src="<?php echo JURI::root(); ?>administrator/components/com_eshop/assets/images/loading.gif" alt="" /></span>');
              },
              complete: function () {
                  jQuery('.wait').remove();
              },
              success: function (result) {
                  if(result == '1'){
                    alert("Xác nhận thanh toán thành công!");
                    window.location.reload();
                  }else{
                    alert("Xác nhận thanh toán thất bại, vui lòng thử lại.");
                    window.location.reload();
                  }
              },
              error: function (xhr, ajaxOptions, thrownError) {
                  alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
              }
          })
        } else {

        }

    });


    Eshop.jQuery = jQuery.noConflict();
    Eshop.jQuery(document).ready(function ($) {
        $('#payment_country_id').change(function () {
            $.ajax({
                url: 'index.php?option=com_eshop&task=customer.country&country_id=' + this.value,
                dataType: 'json',
                beforeSend: function () {
                    $('#payment_country_id').after('<span class="wait">&nbsp;<img src="<?php echo JURI::root(); ?>administrator/components/com_eshop/assets/images/loading.gif" alt="" /></span>');
                },
                complete: function () {
                    $('.wait').remove();
                },
                success: function (json) {
                    html = '<option value="0"><?php echo JText::_('ESHOP_PLEASE_SELECT'); ?></option>';
                    if (json['zones'] != '') {
                        for (i = 0; i < json['zones'].length; i++) {
                            html += '<option value="' + json['zones'][i]['id'] + '"';
                            if (json['zones'][i]['id'] == '<?php echo $this->item->payment_zone_id; ?>') {
                                html += ' selected="selected"';
                            }
                            html += '>' + json['zones'][i]['zone_name'] + '</option>';
                        }
                    }
                    $('#payment_zone_id').html(html);
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            })
        });
        $('#shipping_country_id').change(function () {
            $.ajax({
                url: 'index.php?option=com_eshop&task=customer.country&country_id=' + this.value,
                dataType: 'json',
                beforeSend: function () {
                    jQuery('select[name=\'shipping_country_id\']').after('<span class="wait">&nbsp;<img src="<?php echo JURI::root(); ?>administrator/components/com_eshop/assets/images/loading.gif" alt="" /></span>');
                },
                complete: function () {
                    jQuery('.wait').remove();
                },
                success: function (json) {
                    html = '<option value="0"><?php echo JText::_('ESHOP_PLEASE_SELECT'); ?></option>';
                    if (json['zones'] != '') {
                        for (i = 0; i < json['zones'].length; i++) {
                            html += '<option value="' + json['zones'][i]['id'] + '"';
                            if (json['zones'][i]['id'] == '<?php echo $this->item->shipping_zone_id; ?>') {
                                html += ' selected="selected"';
                            }
                            html += '>' + json['zones'][i]['zone_name'] + '</option>';
                        }
                    }
                    jQuery('select[name=\'shipping_zone_id\']').html(html);
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            })
        });

        $('.btnShip').click(function (e) {
            var orderId = $(this).data('orderid');
            $('#orderId').val(orderId);
            e.preventDefault();
            $('#confirmOrderShipping').modal({
                backdrop: 'static',
                keyboard: false,
            });
        });

        $('#confirmShip').click(function () {
            var siteUrl = '<?php echo EshopHelper::getSiteUrl(); ?>';
            var orderId = $('#orderId').val();
            var noteShip = $('#note').val();
            var pickDateShip = $('#pickDate').val();
            var pickWorkShift = $('#pickWorkShift').val();
            var deliverWorkShift = $('#deliverWorkShift').val();
            var freeShip = $('#freeShip').is(':checked') ? 1 : 0;
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
                    var result = json.data;
                    var msgBlock = $('#msgBlock');
                    var obj = JSON.parse(result);
                    if (obj.success) {
                        $('#confirmOrderShipping').modal('hide');
                        var html = '<h3>Tạo đơn hàng thành công</h3>';
                        html += '<p>Phí vận chuyển: ' + obj.order.fee + '</p>';
                        html += '<p>Ngày lấy hàng: ' + obj.order.estimated_pick_time + '</p>';
                        html += '<p>Ngày giao hàng: ' + obj.order.estimated_deliver_time + '</p>';
                        msgBlock.show().addClass('alert-success').html(html);
                        var ghtkData = {
                            shipping_tracking_number: obj.order.label,
                            estimated_pick_time: obj.order.estimated_pick_time,
                            estimated_deliver_time: obj.order.estimated_deliver_time,
                            ref_fee: obj.order.fee,
                            status_id: 9,
                            orderId: orderId
                        };
                        $.ajax({
                            url: "<?php echo JURI::root(); ?>administrator/index.php?option=com_eshop&task=order.updateShipOrder&tmpl=component",
                            type: 'post',
                            data: ghtkData,
                            dataType: 'json',
                            success: function (json) {
                                console.log(json);
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
                        jQuery('button[id="btnCancelShip"]').after('<span class="wait">&nbsp;<img src="<?php echo JURI::root(); ?>administrator/components/com_eshop/assets/images/loading.gif" alt="" /></span>');
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

    function sendEmail(id) {
        Eshop.jQuery.ajax({
            url: "<?php echo JURI::root(); ?>administrator/index.php?option=com_eshop&task=order.sendEmail&tmpl=component",
            method: 'POST',
            data: {cid: id},
            success: function (result) {
                if (result) {
                    Eshop.jQuery('#email_bt').html('<span class="btn">Đã gửi email</span>');
                } else {
                    alert("Có lỗi, vui lòng thử lại sau!");
                }
            }
        });
    }
</script>
