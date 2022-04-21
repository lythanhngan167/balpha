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
use Joomla\CMS\Language\Text;

defined('_JEXEC') or die();
$bootstrapHelper = $this->bootstrapHelper;
$rowFuildClass = $bootstrapHelper->getClassMapping('row');
$controlGroupClass = $bootstrapHelper->getClassMapping('form-group');
$controlLabelClass = $bootstrapHelper->getClassMapping('control-label');
$controlsClass = $bootstrapHelper->getClassMapping('form-control');
$pullRightClass = $bootstrapHelper->getClassMapping('pull-right');
$btnClass = $bootstrapHelper->getClassMapping('btn');
$this->shipping_required = 1;
?>
<h1><?php echo JText::_('ESHOP_CHECKOUT'); ?></h1><br/>
<div class="row">
    <div class="col-sm-12">
        <div class="alert" style="display: none" id="msg-box"></div>
        <div class="loader"></div>
        <div id="smartwizard">
            <ul>
                <li><a href="#step-1">Đăng nhập / Đăng ký</a></li>
                <li><a href="#step-2">Thanh toán & đặt mua</a></li>
            </ul>
            <div>
                <div id="step-1">
                    <div id="checkout-options">
                        <div class="box__content-body">
                            <?php echo $this->loadTemplate("login"); ?>
                        </div>
                    </div>
                </div>
                <div id="step-2">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="box__content">
                                <div class="box__content-heading">
                                    <h3><i class="fa fa-truck" aria-hidden="true"></i> Thông tin giao hàng</h3>
                                </div>
                                <div id="payment-address">
                                    <h3><?php echo JText::_('ESHOP_PAYMENT_ADDRESS'); ?></h3>
                                    <div class="box__content-body"></div>
                                </div>
                                <?php
                                // if has required shipping
                                if ($this->shipping_required) {
                                    if (EshopHelper::getConfigValue('require_shipping_address', 1)) {
                                        ?>
                                        <hr/>
                                        <div id="shipping-address">
                                            <h3><?php echo JText::_('ESHOP_SHIPPING_ADDRESS'); ?></h3>
                                            <div class="box__content-body">
                                                <?php echo $this->loadTemplate("shipping_address"); ?>
                                            </div>
                                        </div>

                                        <div class="box" id="shipping-method" style="display: none">
                                            <div class="box__content shipping">
                                                <div class="box__content-heading">
                                                    <h3><i class="fa fa-truck" aria-hidden="true"></i> Phương thức vận
                                                        chuyển</h3>
                                                </div>
                                                <div class="box__content-body">
                                                    <?php echo $this->loadTemplate("shipping_method"); ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php }
                                } ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="box" id="payment-method">
                                <div class="box__content">
                                    <div class="box__content-heading">
                                        <h3><i class="fa fa-credit-card" aria-hidden="true"></i> Phương thức thanh toán
                                        </h3>
                                    </div>
                                    <div class="box__content-body">
                                        <?php echo $this->loadTemplate("payment_method"); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="box" id="confirm">
                                <div class="box__content">
                                    <div class="box__content-heading">
                                        <h3><i class="fa fa-shopping-basket" aria-hidden="true"></i> Đơn hàng</h3>
                                    </div>
                                    <div class="box__content-body">
                                        <?php echo $this->loadTemplate("confirm"); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    Eshop.jQuery(function ($) {
        // Smart Wizard
        $('#smartwizard').smartWizard({
            selected: 0,
            theme: 'dots',
            showStepURLhash: false,
            cycleSteps: true,
            anchorSettings: {
                enableAnchorOnDoneStep: false
            },
            toolbarSettings: {
                showNextButton: false,
                showPreviousButton: false,
            }
        });

        //If user is not logged in, then show login layout
        <?php
        if (!$this->user->get('id'))
        {
        if (EshopHelper::getConfigValue('checkout_type') == 'guest_only')
        {
        ?>
        $(document).ready(function () {
            var siteUrl = '<?php echo EshopHelper::getSiteUrl(); ?>';
            $.ajax({
                url: siteUrl + 'index.php?option=com_eshop&view=checkout&layout=guest&format=raw<?php echo EshopHelper::getAttachedLangLink(); ?>',
                dataType: 'html',
                success: function (html) {
                    $('#payment-address .box__content-body').html(html);
                    $('#payment-address .box__content-body').slideDown('slow');
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        });
        <?php
        }
        else
        {
        ?>
        $(document).ready(function () {
            var siteUrl = '<?php echo EshopHelper::getSiteUrl(); ?>';
            $.ajax({
                url: siteUrl + 'index.php?option=com_eshop&view=checkout&layout=login&format=raw<?php echo EshopHelper::getAttachedLangLink(); ?>',
                dataType: 'html',
                success: function (html) {
                    $('#smartwizard').css("display","block");
                    $('.loader').css("display","none");
                    $('#checkout-options .box__content-body').html(html);
                    $('#checkout-options .box__content-body').slideDown('slow');
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    console.log(xhr.responseText)
                }
            });
        });
        <?php
        }
        }
        //Else, show payment address layout
        else
        {
        ?>
        $(document).ready(function () {
            var siteUrl = '<?php echo EshopHelper::getSiteUrl(); ?>';
            $.ajax({
                url: siteUrl + 'index.php?option=com_eshop&view=checkout&layout=payment_address&format=raw<?php echo EshopHelper::getAttachedLangLink(); ?>',
                dataType: 'html',
                success: function (html) {
                    $('#smartwizard').css("display","block");
                    $('.loader').css("display","none");

                    $('#payment-address .box__content-body').html(html);
                    $('#smartwizard').smartWizard("next");
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    console.log(xhr.responseText)
                }
            });
        });
        <?php
        }
        ?>
    });


</script>

<script type="text/javascript">
    //Function to update cart
    function updateCart() {
        Eshop.jQuery(function ($) {
            var siteUrl = '<?php echo EshopHelper::getSiteUrl(); ?>';
            $.ajax({
                type: 'POST',
                url: siteUrl + 'index.php?option=com_eshop&task=cart.updates<?php echo EshopHelper::getAttachedLangLink(); ?>',
                data: $('.cart-info input[type=\'text\'], .cart-info input[type=\'hidden\']'),
                beforeSend: function () {
                    $('#update-cart').attr('disabled', true);
                    $('#update-cart').after('<span class="wait">&nbsp;<img src="components/com_eshop/assets/images/loading.gif" alt="" /></span>');
                },
                complete: function () {
                    $('#update-cart').attr('disabled', false);
                    $('.wait').remove();
                },
                success: function () {
                    window.location.href = "<?php echo JRoute::_(EshopRoute::getViewRoute('cart')); ?>";
                }
            });
        })
    }

    Eshop.jQuery(function ($) {
        //Ajax remove cart item
        $('.eshop-remove-item-cart').bind('click', function () {
            var aTag = $(this);
            var id = aTag.attr('id');
            var siteUrl = '<?php echo EshopHelper::getSiteUrl(); ?>';
            $.ajax({
                type: 'POST',
                url: siteUrl + 'index.php?option=com_eshop&task=cart.remove&key=' + id + '&redirect=1<?php echo EshopHelper::getAttachedLangLink(); ?>',
                beforeSend: function () {
                    aTag.attr('disabled', true);
                    aTag.after('<span class="wait">&nbsp;<img src="components/com_eshop/assets/images/loading.gif" alt="" /></span>');
                },
                complete: function () {
                    aTag.attr('disabled', false);
                    $('.wait').remove();
                },
                success: function () {
                    window.location.href = '<?php echo JRoute::_(EshopRoute::getViewRoute('cart')); ?>';
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        });
    });
    <?php
    if (false && EshopHelper::getConfigValue('allow_coupon'))
    {
    ?>
    //Function to apply coupon
    function applyCoupon() {
        Eshop.jQuery(function ($) {
            var siteUrl = '<?php echo EshopHelper::getSiteUrl(); ?>';
            $.ajax({
                type: 'POST',
                url: siteUrl + 'index.php?option=com_eshop&task=cart.applyCoupon<?php echo EshopHelper::getAttachedLangLink(); ?>',
                data: 'coupon_code=' + document.getElementById('coupon_code').value,
                beforeSend: function () {
                    $('#apply-coupon').attr('disabled', true);
                    $('#apply-coupon').after('<span class="wait">&nbsp;<img src="components/com_eshop/assets/images/loading.gif" alt="" /></span>');
                },
                complete: function () {
                    $('#apply-coupon').attr('disabled', false);
                    $('.wait').remove();
                },
                success: function () {
                    window.location.href = "<?php echo JRoute::_(EshopRoute::getViewRoute('cart')); ?>";
                }
            });
        });
    }
    <?php
    }
    if (EshopHelper::getConfigValue('allow_voucher') || EshopHelper::getConfigValue('allow_coupon'))
    {
    ?>
    //Function to apply voucher
    function applyVoucher() {
        Eshop.jQuery(function ($) {
            var siteUrl = '<?php echo EshopHelper::getSiteUrl(); ?>';
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: siteUrl + 'index.php?option=com_eshop&task=cart.checkDiscountCode<?php echo EshopHelper::getAttachedLangLink(); ?>',
                data: 'code=' + document.getElementById('code').value,
                beforeSend: function () {
                    $('#apply-voucher').attr('disabled', true);
                    $('#apply-voucher').after('<span class="wait">&nbsp;<img src="components/com_eshop/assets/images/loading.gif" alt="" /></span>');
                },
                complete: function () {
                    $('#apply-voucher').attr('disabled', false);
                    $('.wait').remove();
                },
                success: function (json) {
                    console.log(json);
                    $('#confirm .box__content-body').empty().html(json['html']);
                    if (json['error'] == false) {

                        $('.code_message').text('Áp dụng mã giảm giá thành công.').removeClass('error');
                    } else {
                        $('.code_message').text('Mã giảm giá không hợp lệ.').addClass('error');
                    }
                }
            });
        });
    }
    <?php
    }
    if ($this->shipping_required)
    {
    ?>
    Eshop.jQuery(function ($) {
        $('select[name=\'country_id\']').bind('change', function () {
            var siteUrl = '<?php echo EshopHelper::getSiteUrl(); ?>';
            $.ajax({
                url: siteUrl + 'index.php?option=com_eshop&task=cart.getZones<?php echo EshopHelper::getAttachedLangLink(); ?>&country_id=' + this.value,
                dataType: 'json',
                beforeSend: function () {
                    $('.wait').remove();
                    $('select[name=\'country_id\']').after('<span class="wait">&nbsp;<img src="components/com_eshop/assets/images/loading.gif" alt="" /></span>');
                },
                complete: function () {
                    $('.wait').remove();
                },
                success: function (json) {
                    if (json['postcode_required'] == '1') {
                        $('#postcode-required').show();
                    } else {
                        $('#postcode-required').hide();
                    }
                    html = '<option value=""><?php echo JText::_('ESHOP_PLEASE_SELECT'); ?></option>';
                    if (json['zones'] != '') {
                        for (var i = 0; i < json['zones'].length; i++) {
                            html += '<option value="' + json['zones'][i]['id'] + '"';
                            if (json['zones'][i]['id'] == '<?php $this->shipping_zone_id; ?>') {
                                html += ' selected="selected"';
                            }
                            html += '>' + json['zones'][i]['zone_name'] + '</option>';
                        }
                    }
                    $('select[name=\'zone_id\']').html(html);
                }
            });
        });
    });

    //Function to apply shipping
    function applyShipping() {
        Eshop.jQuery(function ($) {
            var shippingMethod = document.getElementsByName('shipping_method');
            var validated = false;
            var selectedShippingMethod = '';
            for (var i = 0, length = shippingMethod.length; i < length; i++) {
                if (shippingMethod[i].checked) {
                    validated = true;
                    selectedShippingMethod = shippingMethod[i].value;
                    break;
                }
            }
            if (!validated) {
                alert('<?php echo JText::_('ESHOP_ERROR_SHIPPING_METHOD'); ?>');
                return;
            } else {
                var siteUrl = '<?php echo EshopHelper::getSiteUrl(); ?>';
                $.ajax({
                    type: 'POST',
                    url: siteUrl + 'index.php?option=com_eshop&task=cart.applyShipping<?php echo EshopHelper::getAttachedLangLink(); ?>',
                    data: 'shipping_method=' + selectedShippingMethod,
                    beforeSend: function () {
                        $('#apply-shipping').attr('disabled', true);
                        $('#apply-shipping').after('<span class="wait">&nbsp;<img src="components/com_eshop/assets/images/loading.gif" alt="" /></span>');
                    },
                    complete: function () {
                        $('#apply-shipping').attr('disabled', false);
                        $('.wait').remove();
                    },
                    success: function () {
                        window.location.href = "<?php echo JRoute::_(EshopRoute::getViewRoute('cart')); ?>";
                    }
                });
            }
        });
    }

    //Function to get quotes
    Eshop.jQuery(function ($) {
        $('#get-quotes').bind('click', function () {
            var siteUrl = '<?php echo EshopHelper::getSiteUrl(); ?>';
            var dataString = 'country_id=' + $('select[name=\'country_id\']').val() + '&zone_id=' + $('select[name=\'zone_id\']').val() + '&postcode=' + encodeURIComponent($('input[name=\'postcode\']').val());
            $.ajax({
                type: 'POST',
                url: siteUrl + 'index.php?option=com_eshop&task=cart.getQuote<?php echo EshopHelper::getAttachedLangLink(); ?>',
                data: dataString,
                dataType: 'json',
                beforeSend: function () {
                    $('#get-quotes').attr('disabled', true);
                    $('#get-quotes').after('<span class="wait">&nbsp;<img src="components/com_eshop/assets/images/loading.gif" alt="" /></span>');
                },
                complete: function () {
                    $('#get-quotes').attr('disabled', false);
                    $('.wait').remove();
                },
                success: function (json) {
                    $(' .error').remove();
                    if (json['error']) {
                        if (json['error']['warning']) {
                            $.colorbox({
                                overlayClose: true,
                                opacity: 0.5,
                                href: false,
                                html: '<h1>' + json['error']['warning'] + '</h1>' + '<div class="no-shipping-method">' + '<?php echo JText::_('ESHOP_NO_SHIPPING_METHOD_AVAILABLE'); ?>' + '</div>'
                            });
                        }
                        if (json['error']['country']) {
                            $('select[name=\'country_id\']').after('<span class="error">' + json['error']['country'] + '</span>');
                        }
                        if (json['error']['zone']) {
                            $('select[name=\'zone_id\']').after('<span class="error">' + json['error']['zone'] + '</span>');
                        }
                        if (json['error']['postcode']) {
                            $('input[name=\'postcode\']').after('<span class="error">' + json['error']['postcode'] + '</span>');
                        }
                    }
                    if (json['shipping_methods']) {
                        //Prepare html for shipping methods list here
                        html = '<div>';
                        html += '<h1><?php echo JText::_('ESHOP_SHIPPING_METHOD_TITLE'); ?></h1>';
                        html += '<form action="" method="post" enctype="multipart/form-data" name="shipping_form">';
                        var firstShippingOption = true;
                        for (i in json['shipping_methods']) {
                            html += '<div>';
                            html += '<strong>' + json['shipping_methods'][i]['title'] + '</strong><br />';
                            if (!json['shipping_methods'][i]['error']) {
                                for (j in json['shipping_methods'][i]['quote']) {
                                    var checkedStr = ' ';
                                    <?php
                                    if ($this->shipping_method != '')
                                    {
                                    ?>
                                    if (json['shipping_methods'][i]['quote'][j]['name'] == '<?php echo $this->shipping_method; ?>') {
                                        checkedStr = " checked = 'checked' ";
                                    }
                                    <?php
                                    }
                                    else
                                    {
                                    ?>
                                    if (firstShippingOption) {
                                        checkedStr = " checked = 'checked' ";
                                    }
                                    <?php
                                    }
                                    ?>
                                    firstShippingOption = false;
                                    html += '<label class="radio">';
                                    html += '<input type="radio" value="' + json['shipping_methods'][i]['quote'][j]['name'] + '" name="shipping_method"' + checkedStr + '/>';
                                    html += json['shipping_methods'][i]['quote'][j]['title'];
                                    if (json['shipping_methods'][i]['quote'][j]['text']) {
                                        html += ' (';
                                        html += json['shipping_methods'][i]['quote'][j]['text'];
                                        html += ')';
                                    }
                                    html += '</label>';
                                }
                            } else {
                                html += json['shipping_methods'][i]['error'];
                            }
                            html += '</div>';
                        }
                        html += '<input class="<?php echo $btnClass; ?> btn-primary" type="button" onclick="applyShipping();" id="apply-shipping" value="<?php echo JText::_('ESHOP_SHIPPING_APPLY'); ?>">';
                        html += '</form>';
                        html += '</div>';
                        $.colorbox({
                            overlayClose: true,
                            opacity: 0.5,
                            href: false,
                            html: html
                        });
                    }
                }
            });
        });
    });
    <?php
    }
    ?>
</script>
