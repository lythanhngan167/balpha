<?php
/**
 * @version		3.1.0
 * @package		Joomla
 * @subpackage	EShop
 * @author  	Giang Dinh Truong
 * @copyright	Copyright (C) 2012 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined( '_JEXEC' ) or die();
$bootstrapHelper        = $this->bootstrapHelper;
$controlGroupClass      = $bootstrapHelper->getClassMapping('control-group');
$controlsClass          = $bootstrapHelper->getClassMapping('controls');
$inputAppendClass       = $bootstrapHelper->getClassMapping('input-append');
$inputPrependClass      = $bootstrapHelper->getClassMapping('input-prepend');
$imgPolaroid            = $bootstrapHelper->getClassMapping('img-polaroid');
$btnClass				        = $bootstrapHelper->getClassMapping('btn');
?>
<script src="<?php echo JUri::base(true); ?>/components/com_eshop/assets/colorbox/jquery.colorbox.js" type="text/javascript"></script>
<div class="cart-popup">
    <h1>
        <?php echo JText::_('ESHOP_SHOPPING_CART'); ?>
        <?php
        if ($this->weight)
        {
            echo '&nbsp;(' . $this->weight . ')';
        }
        ?>
    </h1>
    <?php
    if (isset($this->success))
    {
        ?>
        <div class="alert alert-success"><i class="fa fa-check-circle-o"></i> <?php echo $this->success; ?></div>
        <?php
    }
    if (isset($this->warning))
    {
        ?>
        <div class="alert alert-warning"><i class="fa fa-warning"></i> <?php echo $this->warning; ?></div>
        <?php
    }
    ?>
    <?php
    if (!count($this->cartData))
    {
        ?>
        <div class="no-content"><?php echo JText::_('ESHOP_CART_EMPTY'); ?></div>
    <?php
    }
    else
    {
    ?>
        <div class="cart-info">
            <?php
            $countProducts = 0;
            ?>

            <table class="table table-responsive table-hover table-striped">
                <thead>
                <tr>
                    <th class="col-product"><?php echo JText::_('ESHOP_PRODUCTS'); ?></th>
                    <th class="col-price"><?php echo JText::_('ESHOP_UNIT_PRICE'); ?></th>
                    <th class="col-weight">Đơn vị</th>
                    <th class="col-quantity"><?php echo JText::_('ESHOP_QUANTITY'); ?></th>
                    <th class="col-total"><?php echo JText::_('ESHOP_TOTAL'); ?></th>
                    <th class="action-btn"></th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($this->cartData as $key => $product)
                {
                    $countProducts++;
                    $optionData = $product['option_data'];
                    $viewProductUrl = JRoute::_(EshopRoute::getProductRoute($product['product_id'], EshopHelper::getProductCategory($product['product_id'])));
                    ?>
                    <tr>
                        <td data-content="<?php echo JText::_('ESHOP_PRODUCTS'); ?>">
                            <div class="row">
                                <div class="col-sm-3">
                                  <a href="<?php echo $viewProductUrl; ?>">
                                    <img class="<?php echo $imgPolaroid; ?>" src="<?php echo $product['image']; ?>" />
                                  </a>
                                </div>
                                <div class="col-sm-9">
                                    <h4 class="product-name">
                                        <a href="<?php echo $viewProductUrl; ?>">
                                            <?php echo $product['product_name']; ?>
                                        </a>
                                    </h4>
                                    <p><?php echo $product['product_short_desc']; ?></p>
                                    <?php
                                    if (EshopHelper::getConfigValue('stock_warning') && !$product['stock'])
                                    {
                                        ?>
                                        <span class="stock">***</span> <br />
                                        <?php
                                    }
                                    ?>
                                    <?php
                                    for ($i = 0; $n = count($optionData), $i < $n; $i++)
                                    {
                                        echo '<p class="options">- ' . $optionData[$i]['option_name'] . ': ' . $optionData[$i]['option_value'] . (isset($optionData[$i]['sku']) && $optionData[$i]['sku'] != '' ? ' (' . $optionData[$i]['sku'] . ')' : '') . '</p>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </td>
                       <?php /*
                        <td class="eshop-center-text" style="vertical-align: middle;" data-content="<?php echo JText::_('ESHOP_IMAGE'); ?>">
                            <a href="<?php echo $viewProductUrl; ?>">
                                <img class="<?php echo $imgPolaroid; ?>" src="<?php echo $product['image']; ?>" />
                            </a>

                        </td>
                        <td data-content="<?php echo JText::_('ESHOP_PRODUCT_NAME'); ?>">
                            <a href="<?php echo $viewProductUrl; ?>">
                                <?php echo $product['product_name']; ?>
                            </a>
                            <?php
                            if (EshopHelper::getConfigValue('stock_warning') && !$product['stock'])
                            {
                                ?>
                                <span class="stock">***</span>
                                <?php
                            }
                            ?>
                            <br />
                            <?php
                            for ($i = 0; $n = count($optionData), $i < $n; $i++)
                            {
                                echo '- ' . $optionData[$i]['option_name'] . ': ' . $optionData[$i]['option_value'] . (isset($optionData[$i]['sku']) && $optionData[$i]['sku'] != '' ? ' (' . $optionData[$i]['sku'] . ')' : '') . '<br />';
                            }
                            ?>
                        </td>
                        */?>
                        <td class="text-center" data-content="<?php echo JText::_('ESHOP_UNIT_PRICE'); ?>">
                           <span class="unit-price">
                               <?php
                               if (EshopHelper::showPrice())
                               {
                                   if (EshopHelper::getConfigValue('include_tax_anywhere', '0'))
                                   {
                                       echo $this->currency->format($this->tax->calculate($product['price'], $product['product_taxclass_id'], EshopHelper::getConfigValue('tax')));
                                   }
                                   else
                                   {
                                       echo $this->currency->format($product['price']);
                                   }
                               }
                               ?></span>
                        </td>
                        <td class="text-center" data-content="<?php echo JText::_('ESHOP_UNIT_WEIGHT'); ?>">
                           <span class="unit-weight">
                               <?php
                               echo EshopHelper::getWeightUnitName($product['product_weight_id'], JFactory::getLanguage()->getTag()) ;
                               ?></span>
                        </td>
                        <td data-content="<?php echo JText::_('ESHOP_QUANTITY'); ?>">
                            <div class="<?php echo $inputAppendClass; ?> <?php echo $inputPrependClass; ?>">
								<span class="eshop-quantity">
									<input type="hidden" name="key[]" value="<?php echo $key; ?>" />
									<a onclick="quantityUpdate('+', <?php echo $countProducts; ?>)" class="button-plus item" id="popout_<?php echo $countProducts; ?>">+</a>
										<input type="text" class="item eshop-quantity-value" value="<?php echo htmlspecialchars($product['quantity'], ENT_COMPAT, 'UTF-8'); ?>" name="quantity[]" id="quantity_popout_<?php echo $countProducts; ?>" />
									<a onclick="quantityUpdate('-', <?php echo $countProducts; ?>)" class="button-minus item" id="popout_<?php echo $countProducts; ?>">-</a>
								</span>
                            </div>
                        </td>
                        <td class="text-right" data-content="<?php echo JText::_('ESHOP_TOTAL'); ?>">
                           <span class="total-price"> <?php
                               if (EshopHelper::showPrice())
                               {
                                   if (EshopHelper::getConfigValue('include_tax_anywhere', '0'))
                                   {
                                       echo $this->currency->format($this->tax->calculate($product['total_price'], $product['product_taxclass_id'], EshopHelper::getConfigValue('tax')));
                                   }
                                   else
                                   {
                                       echo $this->currency->format($product['total_price']);
                                   }
                               }
                               ?></span>
                        </td>
                        <td class="eshop-center-text" data-content="<?php echo JText::_('ESHOP_REMOVE'); ?>">
                            <a title="<?php echo JText::_('ESHOP_REMOVE'); ?>" class="eshop-remove-item-cart btn btn-danger btn-sm" id="<?php echo $key; ?>">
                                <i class="fa fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
                <tfoot>
                    <?php if (EshopHelper::showPrice())  {
                        foreach ($this->totalData as $data)
                        {
                        ?>
                            <tr class="totals">
                                <td colspan="4" class="text-right title"><?php echo $data['title']; ?>:</td>
                                <td class="text-right"><span class="price"><?php echo $data['text']; ?></span></td>
                                <td></td>
                            </tr>
                        <?php
                        }
                    } ?>
                    <tr>
                        <td><a class="btn btn-outline-warning btn-continue" href="<?php echo JRoute::_(EshopHelper::getContinueShopingUrl()); ?>"><?php echo JText::_('ESHOP_CONTINUE_SHOPPING'); ?></a></td>
                        <td colspan="5" class="text-right">
                            <?php
                            if (EshopHelper::getConfigValue('active_https'))
                            {
                                $checkoutUrl = JRoute::_(EshopRoute::getViewRoute('checkout'), true, 1);
                            }
                            else
                            {
                                $checkoutUrl = JRoute::_(EshopRoute::getViewRoute('checkout'));
                            }
                            ?>
                            <a class="btn btn-outline-default" href="<?php echo JRoute::_(EshopRoute::getViewRoute('cart')); ?>"><i class="fa fa-shopping-basket" aria-hidden="true"></i> <?php echo JText::_('ESHOP_SHOPPING_CART'); ?></a>
                            <a class="btn btn-outline-primary"  onclick="updateCart();" id="update-cart"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> <?php echo JText::_('ESHOP_UPDATE_CART'); ?></a>
                            <!-- <div class="text-right top-action-btn"><button type="button" class="btn btn-outline-primary" onclick="updateCart();" id="update-cart"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> <span><?php echo JText::_('ESHOP_UPDATE_CART'); ?></span></button></div> -->
                            <a class="btn btn-outline-success btn-checkout" href="<?php echo $checkoutUrl; ?>"><?php echo JText::_('ESHOP_CHECKOUT'); ?></a>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <script type="text/javascript">
            Eshop.jQuery(function($){
                $(document).ready(function(){
                    quantityUpdate = (function(sign, productId){
                        var oldQuantity = $('#quantity_popout_' + productId).val();
                        if(sign == '+')
                        {
                            oldQuantity++;
                            $('#quantity_popout_' + productId).val(oldQuantity);
                        }
                        else if (sign == '-')
                        {
                            oldQuantity--;
                            if (oldQuantity > 0)
                            {
                                $('#quantity_popout_' + productId).val(oldQuantity);
                            }
                        }
                    })
                })
            })
            //Function to update cart
            function updateCart()
            {
                Eshop.jQuery(function($){
                    var siteUrl = '<?php echo EshopHelper::getSiteUrl(); ?>';
                    $.ajax({
                        type: 'POST',
                        url: siteUrl + 'index.php?option=com_eshop&task=cart.updates<?php echo EshopHelper::getAttachedLangLink(); ?>',
                        data: $('.cart-info input[type=\'text\'], .cart-info input[type=\'hidden\']'),
                        beforeSend: function() {
                            $('#update-cart').attr('disabled', true);
                            $('#update-cart').after('<span class="wait">&nbsp;<img src="components/com_eshop/assets/images/loading.gif" alt="" /></span>');
                        },
                        complete: function() {
                            $('#update-cart').attr('disabled', false);
                            $('.wait').remove();
                        },
                        success: function() {
                            $.ajax({
                                url: siteUrl + 'index.php?option=com_eshop&view=cart&layout=popout&format=raw<?php echo EshopHelper::getAttachedLangLink(); ?>&pt=<?php echo time(); ?>',
                                dataType: 'html',
                                success: function(html) {
                                    $.colorbox({
                                        overlayClose: true,
                                        opacity: 0.5,
                                        href: false,
                                        html: html
                                    });
                                    $.ajax({
                                        url: siteUrl + 'index.php?option=com_eshop&view=cart&layout=mini&format=raw<?php echo EshopHelper::getAttachedLangLink(); ?>&pt=<?php echo time(); ?>',
                                        dataType: 'html',
                                        success: function(html) {
                                            $('#eshop-cart').html(html);
                                            $('.eshop-content').hide();
                                        },
                                        error: function(xhr, ajaxOptions, thrownError) {
                                            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                                        }
                                    });
                                },
                                error: function(xhr, ajaxOptions, thrownError) {
                                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                                }
                            });
                        }
                    });
                })
            }

            Eshop.jQuery(function($) {
                //Ajax remove cart item
                $('.eshop-remove-item-cart').bind('click', function() {
                    var aTag = $(this);
                    var id = aTag.attr('id');
                    var siteUrl = '<?php echo EshopHelper::getSiteUrl(); ?>';
                    $.ajax({
                        type :'POST',
                        url: siteUrl + 'index.php?option=com_eshop&task=cart.remove&key=' +  id + '&redirect=1<?php echo EshopHelper::getAttachedLangLink(); ?>',
                        beforeSend: function() {
                            aTag.attr('disabled', true);
                            aTag.after('<span class="wait">&nbsp;<img src="components/com_eshop/assets/images/loading.gif" alt="" /></span>');
                        },
                        complete: function() {
                            aTag.attr('disabled', false);
                            $('.wait').remove();
                        },
                        success : function() {
                            $.ajax({
                                url: siteUrl + 'index.php?option=com_eshop&view=cart&layout=popout&format=raw<?php echo EshopHelper::getAttachedLangLink(); ?>&pt=<?php echo time(); ?>',
                                dataType: 'html',
                                success: function(html) {
                                    $.colorbox({
                                        overlayClose: true,
                                        opacity: 0.5,
                                        href: false,
                                        html: html
                                    });
                                    $.ajax({
                                        url: siteUrl + 'index.php?option=com_eshop&view=cart&layout=mini&format=raw<?php echo EshopHelper::getAttachedLangLink(); ?>&pt=<?php echo time(); ?>',
                                        dataType: 'html',
                                        success: function(html) {
                                            $('#eshop-cart').html(html);
                                            $('.eshop-content').hide();
                                        },
                                        error: function(xhr, ajaxOptions, thrownError) {
                                            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                                        }
                                    });
                                },
                                error: function(xhr, ajaxOptions, thrownError) {
                                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                                }
                            });
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                        }
                    });
                });
            });
        </script>
        <?php
    }
    ?>
</div>
