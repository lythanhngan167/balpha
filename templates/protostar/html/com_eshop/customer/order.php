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
$bootstrapHelper = $this->bootstrapHelper;
$pullRightClass = $bootstrapHelper->getClassMapping('pull-right');
$btnClass = $bootstrapHelper->getClassMapping('btn');

$user = JFactory::getUser();
$language = JFactory::getLanguage();
$tag = $language->getTag();


if (!$tag) {
    $tag = 'en-GB';
}
?>
<div class="customer-box order_customer">
    <div class="customer-box__content">
        <div class="customer-box__head">
            <h1 class="customer-box__head--title"><?php echo JText::_('ESHOP_ORDER_DETAILS'); ?></h1>
        </div>
        <div id="msg-box" style="display: none" class="alert"></div>
        <?php if (!$this->orderProducts) { ?>
            <div class="warning"><?php echo JText::_('ESHOP_ORDER_DOES_NOT_EXITS'); ?></div>
        <?php } else {
            $hasShipping = $this->orderInfor->shipping_method; ?>
            <form id="adminForm">
                <table cellpadding="0" cellspacing="0" class="list">
                    <thead>
                    <tr>
                        <td colspan="2" class="left">
                            <?php echo JText::_('ESHOP_ORDER_DETAILS'); ?>
                        </td>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td style="width: 50%;" class="left">
                            <b><?php echo JText::_('ESHOP_ORDER_ID'); ?>
                                : </b><?php echo $this->orderInfor->order_number; ?><br/>

                            <b><?php echo JText::_('ESHOP_DATE_ADDED'); ?>
                                : </b> <?= EshopHelper::renderDate($this->orderInfor->created_date, 'd/m/Y H:i') ?> <?php //echo JHtml::date($this->orderInfor->created_date, EshopHelper::getConfigValue('date_format', 'm-d-Y'), null); ?>
                        </td>
                        <td style="width: 50%;" class="left">
                            <b><?php echo JText::_('ESHOP_PAYMENT_METHOD'); ?>
                                : </b> <?php echo JText::_($this->orderInfor->payment_method_title); ?><br/>
                            <?php if ($this->orderInfor->payment_method == 'os_bank_transfer') { ?>
                                <?php echo nl2br($this->orderInfor->bank_transfer); ?>
                                <br/>
                            <?php } ?>
                            <?php if ($this->orderInfor->shipping_method_title) { ?>
                                <b><?php echo JText::_('ESHOP_SHIPPING_METHOD'); ?>
                                    : </b> <?php echo JText::_($this->orderInfor->shipping_method_title); ?><br/>
                            <?php } ?>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <table cellpadding="0" cellspacing="0" class="list">
                    <thead>
                    <tr>
                        <td class="left">
                            <?php echo JText::_('ESHOP_PAYMENT_ADDRESS'); ?>
                        </td>
                        <?php
                        if ($hasShipping) {
                            ?>
                            <td class="left">
                                <?php echo JText::_('ESHOP_SHIPPING_ADDRESS'); ?>
                            </td>
                            <?php
                        }
                        ?>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td class="left" style="width: 50%;">
                            <?php
                            echo EshopHelper::getPaymentAddress($this->orderInfor, true);
                            $excludedFields = array('firstname', 'lastname', 'email', 'telephone', 'fax', 'company', 'company_id', 'address_1', 'address_2', 'city', 'postcode', 'country_id', 'zone_id');
                            foreach ($this->paymentFields as $field) {
                                $fieldName = $field->name;
                                if (!in_array($fieldName, $excludedFields)) {
                                    $fieldValue = $this->orderInfor->{'payment_' . $fieldName};
                                    if (is_string($fieldValue) && is_array(json_decode($fieldValue))) {
                                        $fieldValue = implode(', ', json_decode($fieldValue));
                                    }
                                    if ($fieldValue != '') {
                                        echo '<br />' . JText::_($field->title) . ': ' . $fieldValue;
                                    }
                                }
                            }
                            ?>
                        </td>
                        <?php
                        if ($hasShipping) {
                            ?>
                            <td class="left" style="width: 50%;">
                                <?php
                                //print_r($this->orderInfor);
                                echo EshopHelper::getShippingAddress($this->orderInfor, true);
                                foreach ($this->shippingFields as $field) {
                                    $fieldName = $field->name;
                                    if (!in_array($fieldName, $excludedFields)) {
                                        $fieldValue = $this->orderInfor->{'shipping_' . $fieldName};
                                        if (is_string($fieldValue) && is_array(json_decode($fieldValue))) {
                                            $fieldValue = implode(', ', json_decode($fieldValue));
                                        }
                                        if ($fieldValue != '') {
                                            echo '<br />' . JText::_($field->title) . ': ' . $fieldValue;
                                        }
                                    }
                                }
                                ?>
                            </td>
                            <?php
                        }
                        ?>
                    </tr>
                    </tbody>
                </table>
                <table cellpadding="0" cellspacing="0" class="list desktop_only">
                    <thead>
                    <tr>
                        <td class="left">
                            <?php echo JText::_('ESHOP_PRODUCT_NAME'); ?>
                        </td>
                        <td class="left">
                            <?php echo JText::_('ESHOP_MODEL'); ?>
                        </td>
                        <td class="left">
                            <?php echo JText::_('ESHOP_QUANTITY'); ?>
                        </td>
                        <td class="left">
                            <?php echo JText::_('ESHOP_PRICE'); ?>
                        </td>
                        <td class="left">
                            <?php echo JText::_('ESHOP_TOTAL'); ?>
                        </td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($this->orderProducts as $product) {
                        $options = $product->options;
                        $viewProductUrl = JRoute::_(EshopRoute::getProductRoute($product->product_id, EshopHelper::getProductCategory($product->product_id)));
                        ?>
                        <tr>
                            <td class="left">
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
                                  <img width="70" class="mr-4" src="<?php echo $image;?>" alt="<?php echo $product->product_name;?>">
                              </a>
                                <?php
                                echo '<a href="'.$viewProductUrl.'">' . $product->product_name . '</a>';
                                for ($i = 0; $n = count($options), $i < $n; $i++) {
                                    echo '<br />- ' . $options[$i]->option_name . ': ' . $options[$i]->option_value . (isset($options[$i]->sku) && $options[$i]->sku != '' ? ' (' . $options[$i]->sku . ')' : '');
                                }
                                ?>
                            </td>
                            <td class="left"><?php echo $product->product_sku; ?></td>
                            <td class="left"><?php echo $product->quantity; ?> x <?php echo $product->unit; ?></td>
                            <td class="right"><?php echo $product->price; ?></td>
                            <td class="right"><?php echo $product->total_price; ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                    <tfoot>
                    <?php
                    foreach ($this->orderTotals as $ordertotal) {
                        ?>
                        <tr>
                            <td colspan="3"></td>
                            <td class="right">
                                <b><?php echo $ordertotal->title ?>: </b>
                            </td>
                            <td class="right">
                                <?php echo $ordertotal->text ?>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tfoot>
                </table>

                <table cellpadding="0" cellspacing="0" class="list mobile_only">
                    <tbody>
                    <?php
                    foreach ($this->orderProducts as $product) {
                        $options = $product->options;
                        ?>
                        <tr>
                            <td class="left">
                                <?php echo JText::_('ESHOP_PRODUCT_NAME'); ?>
                            </td>
                            <td class="left">
                                <?php
                                echo '<b>' . $product->product_name . '</b>';
                                for ($i = 0; $n = count($options), $i < $n; $i++) {
                                    echo '<br />- ' . $options[$i]->option_name . ': ' . $options[$i]->option_value . (isset($options[$i]->sku) && $options[$i]->sku != '' ? ' (' . $options[$i]->sku . ')' : '');
                                }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="left">
                                <?php echo JText::_('ESHOP_MODEL'); ?>
                            </td>
                            <td class="right"><?php echo $product->product_sku; ?></td>
                        </tr>
                        <tr>
                            <td class="left">
                                <?php echo JText::_('ESHOP_QUANTITY'); ?>
                            </td>
                            <td class="right"><?php echo $product->quantity; ?></td>
                        </tr>
                        <tr>
                            <td class="left">
                                <?php echo JText::_('ESHOP_PRICE'); ?>
                            </td>
                            <td class="right"><?php echo $product->price; ?></td>
                        </tr>
                        <tr>
                            <td class="left">
                                <?php echo JText::_('ESHOP_TOTAL'); ?>
                            </td>
                            <td class="right"><?php echo $product->total_price; ?></td>
                        </tr>
                        <tr>
                            <td colspan="2"></td>
                        </tr>

                        <?php
                    }
                    ?>
                    </tbody>
                    <tfoot>
                    <?php
                    foreach ($this->orderTotals as $ordertotal) {
                        ?>
                        <tr>

                            <td class="left">
                                <b><?php echo $ordertotal->title ?>: </b>
                            </td>
                            <td class="right">
                                <?php echo $ordertotal->text ?>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tfoot>
                </table>


                <h2><?php echo JText::_('ESHOP_ORDER_HISTORY'); ?></h2>
                <table cellpadding="0" cellspacing="0" class="list">
                    <thead>
                    <tr>
                        <td class="left">
                            <?php //echo JText::_('ESHOP_DATE_ADDED'); ?>
                            Ngày đặt
                        </td>
                        <td class="left">
                            <?php echo JText::_('ESHOP_STATUS'); ?>
                        </td>
                        <td class="left">
                            <?php echo JText::_('ESHOP_COMMENT'); ?>
                        </td>
                        <?php
                        if (EshopHelper::getConfigValue('delivery_date')) {
                            ?>
                            <td class="left">
                                <?php echo JText::_('ESHOP_DELIVERY_DATE'); ?>
                            </td>
                            <?php
                        }
                        ?>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td class="left">
                          <?= EshopHelper::renderDate($this->orderInfor->created_date, 'd/m/Y H:i') ?>
                            <?php //echo JHtml::date($this->orderInfor->created_date, EshopHelper::getConfigValue('date_format', 'm-d-Y'), null); ?>
                        </td>
                        <td class="left">
                            <?php echo EshopHelper::getOrderStatusName($this->orderInfor->order_status_id, $tag); ?>
                        </td>
                        <td class="left">
                            <?php echo nl2br($this->orderInfor->comment); ?>
                        </td>
                        <?php
                        if (EshopHelper::getConfigValue('delivery_date')) {
                            ?>
                            <td class="left">
                                <?php echo JHtml::date($this->orderInfor->delivery_date, EshopHelper::getConfigValue('date_format', 'm-d-Y'), null); ?> <?php if($this->orderInfor->delivery_hour != ''){ echo " : ".$this->orderInfor->delivery_hour; } ?>
                            </td>
                            <?php
                        }
                        ?>
                    </tr>
                    </tbody>
                </table>
            </form>
            <a class="btn btn-default"
               href="<?php echo str_replace('amp;', '', JRoute::_(EshopRoute::getViewRoute('customer') . '&layout=orders')); ?>"><?php echo JText::_('ESHOP_BACK'); ?></a>
            <?php if ($this->orderInfor->order_status_id == 8 && $this->orderInfor->payment_status == 0) : ?>
                <button id="btn-cancel-order" data-id="<?php echo $this->orderInfor->id; ?>" class="btn btn-danger">Huỷ
                    đơn hàng
                </button>
            <?php endif; ?>
            <?php /*
            <div class="no_margin_left">
            <input type="button" value="<?php echo JText::_('ESHOP_BACK'); ?>" id="button-user-orderinfor" class="<?php echo $btnClass; ?> btn-primary <?php echo $pullRightClass; ?>">
        </div>
        */ ?>
        <?php } ?>
    </div>
</div>

<script type="text/javascript">
    Eshop.jQuery(function ($) {
        $(document).ready(function () {
            $('#button-user-orderinfor').click(function () {
                var url = '<?php echo str_replace('amp;', '', JRoute::_(EshopRoute::getViewRoute('customer') . '&layout=orders')); ?>';
                $(location).attr('href', url);
            });

            $('#btn-cancel-order').click(function () {
                var siteUrl = '<?php echo EshopHelper::getSiteUrl(); ?>';
                var orderId = $(this).data('id');
                var btnCancel = $(this);
                if (confirm("Bạn có chắc chắn huỷ đơn hàng này?")) {
                    $.ajax({
                        url: siteUrl + 'index.php?option=com_eshop&task=customer.cancelOrder<?php echo EshopHelper::getAttachedLangLink(); ?>',
                        type: 'post',
                        data: {orderId: orderId},
                        dataType: 'json',
                        beforeSend: function () {
                            btnCancel.attr('disabled', true);
                            btnCancel.after('<span class="wait">&nbsp;<img src="components/com_eshop/assets/images/loading.gif" alt="" /></span>');
                        },
                        complete: function () {
                            btnCancel.attr('disabled', false);
                            $('.wait').remove();
                        },
                        success: function (json) {
                            $('.warning, .error').remove();
                            if (json['return']) {
                                window.location.href = json['return'];
                            } else if (json['error']) {
                                if (json['error']['warning']) {
                                    $('#msg-box').addClass('alert-danger').html(json['error']['warning']);
                                    $('#msg-box').fadeIn('slow');
                                }
                            } else {
                                $('#msg-box').addClass('alert-success').html(json['success']);
                                $('#msg-box').fadeIn('slow');
                                btnCancel.remove();
                            }
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            console.log(xhr.responseText);
                        }
                    });
                } else {
                    return false;
                }
            });
        })
    });
</script>
