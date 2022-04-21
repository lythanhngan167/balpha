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

$language = JFactory::getLanguage();
$tag = $language->getTag();
$bootstrapHelper = $this->bootstrapHelper;
$rowFluidClass = $bootstrapHelper->getClassMapping('row-fluid');
$span2Class = $bootstrapHelper->getClassMapping('span2');
$pullLeftClass = $bootstrapHelper->getClassMapping('pull-left');
$btnClass = $bootstrapHelper->getClassMapping('btn');

if (!$tag) {
    $tag = 'vi-VN';
}
?>
<div class="order-page">
        <?php
        if (isset($this->warning)) {
            ?>
            <div class="warning"><?php echo $this->warning; ?></div>
            <?php
        }

        if (!count($this->orders)) {
            ?>
            <div class="col-sm-9">
                <h1><?php echo JText::_('ESHOP_ORDER_HISTORY'); ?></h1>
            </div>
            <div class="col-sm-3 filtering text-right">
               <?php
               $listStatus = EshopHelper::getListOrderStatusName($tag);
               $statusUrl = JFactory::getApplication()->input->get('status_id', '', 'filter');
               ?>
               <select name="filter_status" class="form-control" id="filter_status"
                       onchange="this.options[this.selectedIndex].value && (window.location = '<?php echo JRoute::_(EshopRoute::getViewRoute('customer') . '&layout=orders&status_id=') ?>' + this.options[this.selectedIndex].value);">
                   <option value="0">Chọn trạng thái</option>
                   <?php foreach ($listStatus as $status) { ?>
                       <option <?php echo ($statusUrl == $status->orderstatus_id) ? 'selected' : ''; ?>
                               value="<?php echo $status->orderstatus_id; ?>"><?php echo $status->orderstatus_name; ?></option>
                   <?php } ?>
               </select>
           </div>

           <div class="no-content" style="clear:both;"><?php echo JText::_('ESHOP_NO_ORDERS'); ?></div>

            <?php
        } else {
            ?>
            <div class="<?php echo $rowFluidClass; ?>">
                <form id="adminForm" class="order-list">
                 <div class="col-sm-9">
                     <h1><?php echo JText::_('ESHOP_ORDER_HISTORY'); ?></h1>
                 </div>
                <div class="col-sm-3 filtering text-right">
                    <?php
                    $listStatus = EshopHelper::getListOrderStatusName($tag);
                    $statusUrl = JFactory::getApplication()->input->get('status_id', '', 'filter');
                    ?>
                    <select name="filter_status" class="form-control" id="filter_status"
                            onchange="this.options[this.selectedIndex].value && (window.location = '<?php echo JRoute::_(EshopRoute::getViewRoute('customer') . '&layout=orders&status_id=') ?>' + this.options[this.selectedIndex].value);">
                        <option value="0">Chọn trạng thái</option>
                        <?php foreach ($listStatus as $status) { ?>
                            <option <?php echo ($statusUrl == $status->orderstatus_id) ? 'selected' : ''; ?>
                                    value="<?php echo $status->orderstatus_id; ?>"><?php echo $status->orderstatus_name; ?></option>
                        <?php } ?>
                    </select>
                </div>
                  <div class="col-sm-12">
                      <ul class="order-list">
                      <?php foreach ($this->orders as $order) {
                          $status = '';
                          switch ($order->order_status_id) {
                              case 1:
                                  $status = '<span class="text-danger">' . EshopHelper::getOrderStatusName($order->order_status_id, $tag) . '</span>';
                                  break;
                              case 2:
                                  $status = '<span class="text-danger">' . EshopHelper::getOrderStatusName($order->order_status_id, $tag) . '</span>';
                                  break;
                              case 3:
                                  $status = '<span class="text-danger">' . EshopHelper::getOrderStatusName($order->order_status_id, $tag) . '</span>';
                                  break;
                              case 4:
                                  $status = '<span class="text-success">' . EshopHelper::getOrderStatusName($order->order_status_id, $tag) . '</span>';
                                  break;
                              case 5:
                                  $status = '<span class="text-danger">' . EshopHelper::getOrderStatusName($order->order_status_id, $tag) . '</span>';
                                  break;
                              case 6:
                                  $status = '<span class="text-danger">' . EshopHelper::getOrderStatusName($order->order_status_id, $tag) . '</span>';
                                  break;
                              case 7:
                                  $status = '<span class="text-danger">' . EshopHelper::getOrderStatusName($order->order_status_id, $tag) . '</span>';
                                  break;
                              case 8:
                                  $status = '<span class="text-warning">' . EshopHelper::getOrderStatusName($order->order_status_id, $tag) . '</span>';
                                  break;
                              case 9:
                                  $status = '<span class="text-success">' . EshopHelper::getOrderStatusName($order->order_status_id, $tag) . '</span>';
                                  break;
                              case 10:
                                  $status = '<span class="text-warning">' . EshopHelper::getOrderStatusName($order->order_status_id, $tag) . '</span>';
                                  break;
                              case 11:
                                  $status = '<span class="text-danger">' . EshopHelper::getOrderStatusName($order->order_status_id, $tag) . '</span>';
                                  break;
                              case 12:
                                  $status = '<span class="text-danger">' . EshopHelper::getOrderStatusName($order->order_status_id, $tag) . '</span>';
                                  break;
                              case 13:
                                  $status = '<span class="text-danger">' . EshopHelper::getOrderStatusName($order->order_status_id, $tag) . '</span>';
                                  break;
                              case 14:
                                  $status = '<span class="text-danger">' . EshopHelper::getOrderStatusName($order->order_status_id, $tag) . '</span>';
                                  break;
                          }
                          ?>
                          <li>
                              <div class="order-box__head">
                                  <span class="order-box__head-id"><i class="fa fa-list"></i> <?php echo JText::_('ESHOP_ORDER_ID'); ?> : <strong>#<a href="<?php echo JRoute::_(EshopRoute::getViewRoute('customer') . '&layout=order&order_id=' . (int)$order->id); ?>"><?php echo $order->order_number; ?></a></strong> (<?php echo EshopHelper::getNumberProduct($order->id); ?> <?php echo JText::_('ESHOP_PRODUCT'); ?>)</span>

                              </div>
                              <div class="order-box__content">
                                  <ul class="order-products-list">
                                      <?php $products = EshopHelper::getOrderProductsList($order->id);
                                      $k = 0;
                                      foreach ($products as $product) {
                                         $k++;
                                         if($k >= 6){
                                           break;
                                         }
                                          //$viewProductUrl = JRoute::_(EshopRoute::getProductRoute($product->id, EshopHelper::getProductCategory($product->id)));
                                          $viewProductUrl = JRoute::_(EshopRoute::getProductRoute($product->product_id, EshopHelper::getProductCategory($product->product_id)));
                                          ?>
                                          <li>
                                              <div class="item-product__box">
                                                  <div class="item-product__box-img">
                                                      <a href="<?php echo $viewProductUrl;?>">
                                                          <?php
                                                          // Image
                                                          $imageSizeFunction = 'resizeImage';
                                                          if ($product->product_image && JFile::exists(JPATH_ROOT.'/media/com_eshop/products/' . $product->product_image))
                                                          {
                                                              $image = call_user_func_array(array('EshopHelper', $imageSizeFunction), array($product->product_image, JPATH_ROOT . '/media/com_eshop/products/', 100, 100));
                                                          }
                                                          else
                                                          {
                                                              $image = call_user_func_array(array('EshopHelper', $imageSizeFunction), array('no-image.png', JPATH_ROOT . '/media/com_eshop/products/', 100, 100));
                                                          }
                                                          $image = JURI::base() . 'media/com_eshop/products/resized/' . $image;
                                                          ?>
                                                          <img class="mr-4" src="<?php echo $image;?>" alt="<?php echo $product->product_name;?>">
                                                      </a>
                                                  </div>
                                                  <div class="item-product__box-content">
                                                      <div class="item-product__box-content--name">
                                                          <h4><a href="<?php echo $viewProductUrl;?>"><?php echo $product->product_name;?></a></h4>
                                                          <p class="text-gray">
                                                              <?php echo $product->quantity; ?> x <?php echo $product->unit ; ?>
                                                          </p>
                                                      </div>
                                                      <div class="item-product__box-content--price">
                                                          <span class="price"><?php echo $product->total_price; ?></span>
                                                      </div>
                                                  </div>
                                              </div>
                                          </li>
                                          <?php

                                      } ?>
                                  </ul>
                              </div>
                              <div class="order-box__bottom">
                                <span class="order-box__head-status"><?php echo JText::_('ESHOP_STATUS'); ?> : <?php echo $status; ?></span>
                                  <!-- <span class="txt-customer"><i class="fa fa-user"></i> Tên<?php //echo JText::_('ESHOP_CUSTOMER'); ?> : <strong><?php echo $order->shipping_firstname .' '. $order->shipping_lastname; ?></strong></span> -->
                                  <span class="txt-create-date"><i class="fa fa-clock-o"></i> Ngày đặt : <strong><?= EshopHelper::renderDate($order->created_date, 'd/m/Y H:i') ?> <?php //echo JHtml::date($order->created_date, EshopHelper::getConfigValue('date_format', 'm-d-Y H:i'), null); ?></strong></span>
                              </div>
                              <div class="order-box__footer text-right">
                                  <p><?php echo JText::_('ESHOP_TOTAL'); ?>: <strong class="price"><?php echo $order->total; ?></strong></p>
                                  <a class="btn btn-default" href="<?php echo JRoute::_(EshopRoute::getViewRoute('customer') . '&layout=order&order_id=' . (int)$order->id); ?>">
                                      <i class="fa fa-file-text-o" aria-hidden="true"></i> <?php echo JText::_('ESHOP_VIEW'); ?>
                                  </a>
                                  <?php
                                  if (EshopHelper::getConfigValue('allow_re_order')) {
                                      ?>
                                      &nbsp;|&nbsp;
                                      <a href="<?php echo JRoute::_('index.php?option=com_eshop&task=cart.reOrder&order_id=' . (int)$order->id); ?>"><?php echo JText::_('ESHOP_RE_ORDER'); ?></a>
                                      <?php
                                  }
                                  if (EshopHelper::getConfigValue('invoice_enable') && $order->order_status_id == EshopHelper::getConfigValue('complete_status_id')) {
                                      ?>
                                      &nbsp;|&nbsp;
                                      <a href="<?php echo JRoute::_('index.php?option=com_eshop&task=customer.downloadInvoice&order_id=' . (int)$order->id); ?>"><?php echo JText::_('ESHOP_DOWNLOAD_INVOICE'); ?></a>
                                      <?php
                                  }
                                  ?>
                              </div>
                            </li>
                          <?php
                      }
                      ?>
                      </ul>
                  </div>
                </form>
            </div>
            <?php
        }
        ?>
</div>

<?php /*
 <button type="button" id="button-back-order" class="btn btn-warning"><?php echo JText::_('ESHOP_BACK'); ?></button>
 */ ?>
<script type="text/javascript">
    Eshop.jQuery(function ($) {
        $(document).ready(function () {
            $('#button-back-order').click(function () {
                var url = '<?php echo JRoute::_(EshopRoute::getViewRoute('customer')); ?>';
                $(location).attr('href', url);
            });
        })
    });
</script>
