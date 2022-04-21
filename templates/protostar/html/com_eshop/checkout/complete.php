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

if (!is_object($this->orderInfor))
{
	?>
    <div class="order-empty col-md-offset-3 col-md-6 col-xs-12 col-xs-offset-0">
            <h3>Thông tin đơn hàng</h3>
        <i class="fa fa-shopping-basket fa-5x"></i>
        <p><?php echo JText::_('ESHOP_ORDER_NOT_EXISTED'); ?></p>
    </div>
	<?php
}
else
{
	if ($this->conversionTrackingCode != '')
	{
		?>
		<script language="javascript">
			<?php echo $this->conversionTrackingCode; ?>
		</script>
		<?php
	}
	// iDevAffiliate integration
	if (EshopHelper::getConfigValue('idevaffiliate_integration') && file_exists( JPATH_SITE . "/" . EshopHelper::getConfigValue('idevaffiliate_path') . "/sale.php" ))
	{
		?>
		<img border="0" src="<?php echo self::getSiteUrl() . self::getConfigValue('idevaffiliate_path'); ?>/sale.php?profile=72198&idev_saleamt=<?php echo $this->orderInfor->total; ?>&idev_ordernum=<?php echo $this->orderInfor->order_number; ?>" width="1" height="1" />
		<?php
		EshopHelper::iDevAffiliate($this->orderInfor);
	}
	$hasShipping = $this->orderInfor->shipping_method;
	?>
	<div class="order-complete">
        <div class="shop-invoice col-sm-8 col-sm-offset-2">
            <div class="row">
                <div class="col-md-12">
                    <h1>Đặt hàng thành công</h1>
                    <h3 class="title">Cảm ơn bạn đã chọn <strong class="text-secondary"><?php echo EshopHelper::getConfigValue('store_name')?></strong>! Dưới đây là chi tiết đơn hàng:</h3>

								</div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <p class="text-black"><?php echo JText::_('ESHOP_ORDER_ID'); ?>: <strong>#<?php echo $this->orderInfor->order_number; ?></strong></p>
                    <p class="mb-1">Ngày đặt hàng: <strong><?php echo JHtml::date($this->orderInfor->created_date, EshopHelper::getConfigValue('date_format', 'm-d-Y H:i:s'), null); ?></strong></p>
										<p class="mb-1">Ngày giao hàng: <strong><?php echo JHtml::date($this->orderInfor->delivery_date, EshopHelper::getConfigValue('date_format', 'm-d-Y H:i:s'), null); ?></strong><?php if($this->orderInfor->delivery_hour !=''){ echo " : ".$this->orderInfor->delivery_hour; } ?></p>
                    <p class="mb-1"><?php echo JText::_('ESHOP_PAYMENT_METHOD'); ?>:
                        <strong>
                            <?php echo JText::_($this->orderInfor->payment_method_title); ?>
                        </strong>
                        <?php if($this->orderInfor->payment_method == 'os_bank_transfer'){ ?>
                            <span class="bank-transfer">
                                <?php echo nl2br($this->orderInfor->bank_transfer); ?>
                            </span>
                        <?php } ?>
                    </p>
                    <?php if($this->orderInfor->payment_method == 'os_vnpay' && $this->orderInfor->payment_code == '00'):?>
                        <p class="mb-1">Trạng thái thanh toán: Thanh toán thành công.</p>

                    <?php endif;?>
                    <p><?php echo JText::_('ESHOP_SHIPPING_METHOD'); ?>: <strong><?php echo JText::_($this->orderInfor->shipping_method_title); ?></strong></p>
                    <p class="mb-1">Trạng thái đơn hàng: <strong class="text-warning">Đang chờ</strong></p>
                </div>
            </div>
            <hr />
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-1 text-primary">Địa chỉ thanh toán:</p>
                    <p class="mb-1 text-black">Họ tên: <strong><?php echo $this->orderInfor->payment_firstname. ' '. $this->orderInfor->payment_lastname; ?></strong></p>
                    <p class="mb-1">Địa chỉ: <strong><?php echo $this->orderInfor->payment_address_1. ', '. $this->orderInfor->payment_zone_name. ', '. $this->orderInfor->payment_country_name; ?></strong></p>

										<?php if($this->orderInfor->payment_email != ''){ ?>
										<p class="mb-1">Email: <strong><?php echo $this->orderInfor->payment_email; ?></strong></p>
										<?php } ?>
                    <p class="mb-1">Số điện thoại: <strong><?php echo $this->orderInfor->payment_telephone; ?></strong></p>
                </div>
                <div class="col-md-6">
                    <p class="mb-1 text-primary">Địa chỉ nhận hàng:</p>
                    <p class="mb-1 text-black">Họ tên: <strong><?php echo JText::_($this->orderInfor->shipping_firstname.' '.$this->orderInfor->shipping_lastname); ?></strong></p>
                    <p class="mb-1">Địa chỉ: <strong><?php echo JText::_($this->orderInfor->shipping_address_1.', '.$this->orderInfor->shipping_zone_name.', '.$this->orderInfor->shipping_country_name); ?></strong></p>

										<?php if($this->orderInfor->shipping_email != ''){ ?>
										<p class="mb-1">Email: <strong><?php echo $this->orderInfor->shipping_email; ?></strong></p>
										<?php } ?>
                    <p class="mb-1">Số điện thoại: <strong><?php echo $this->orderInfor->shipping_telephone; ?></strong></p>
                </div>
            </div>
            <hr />
            <div class="row desktop_only">
                <div class="col-sm-12" style="overflow: scroll">
                    <table class="table" style="overflow: scroll">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col"><?php echo JText::_('ESHOP_PRODUCT_NAME'); ?></th>
                                <th scope="col"><?php echo JText::_('ESHOP_MODEL'); ?></th>
                                <th scope="col" class="text-right"><?php echo JText::_('ESHOP_PRICE'); ?></th>
                                <th scope="col" class="text-center"><?php echo JText::_('ESHOP_QUANTITY'); ?></th>
                                <th scope="col" class="text-right"><?php echo JText::_('ESHOP_TOTAL'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach ($this->orderProducts as $product)
                        {
                            $options = $product->options;
                            ?>
                            <tr>
                                <td width="30%" data-content="<?php echo JText::_('ESHOP_PRODUCT_NAME'); ?>">
                                    <?php
                                    echo '<b>' . $product->product_name . '</b>';
                                    for ($i = 0; $n = count($options), $i < $n; $i++)
                                    {
                                        $option = $options[$i];
                                        if ($option->option_type == 'File' && $option->option_value != '')
                                        {
                                            echo '<br />- ' . $option->option_name . ': <a href="index.php?option=com_eshop&task=downloadOptionFile&id=' . $option->id . '">' . $option->option_value . '</a>';
                                        }
                                        else
                                        {
                                            echo '<br />- ' . $option->option_name . ': ' . $option->option_value . (isset($option->sku) && $option->sku != '' ? ' (' . $option->sku . ')' : '');
                                        }
                                    }
                                    ?>
                                </td>
                                <td data-content="<?php echo JText::_('ESHOP_MODEL'); ?>"><?php echo $product->product_sku; ?></td>
                                <td class="text-right"  data-content="<?php echo JText::_('ESHOP_PRICE'); ?>"><?php echo $product->price; ?></td>
                                <td class="text-center" data-content="<?php echo JText::_('ESHOP_QUANTITY'); ?>"><?php echo $product->quantity; ?> x <?php echo $product->unit ; ?></td>
                                <td class="text-right" data-content="<?php echo JText::_('ESHOP_TOTAL'); ?>"><?php echo $product->total_price; ?></td>
                            </tr>
                            <?php
                        }
                        ?>
                        </tbody>
                        <tfoot>
                        <?php
                        foreach ($this->orderTotals as $ordertotal)
                        {
                            ?>
                            <tr>
                                <td colspan="4" class="text-right">
                                    <span class="text-success"><?php echo $ordertotal->title?>:</span>
                                </td>
                                <td class="text-right">
                                    <strong class="text-danger"><?php echo $ordertotal->text?></strong>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="mobile_only">
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
                                for ($i = 0; $n = count($options), $i < $n; $i++)
                                {
                                    $option = $options[$i];
                                    if ($option->option_type == 'File' && $option->option_value != '')
                                    {
                                        echo '<br />- ' . $option->option_name . ': <a href="index.php?option=com_eshop&task=downloadOptionFile&id=' . $option->id . '">' . $option->option_value . '</a>';
                                    }
                                    else
                                    {
                                        echo '<br />- ' . $option->option_name . ': ' . $option->option_value . (isset($option->sku) && $option->sku != '' ? ' (' . $option->sku . ')' : '');
                                    }
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
                                <span class="text-success"><?php echo $ordertotal->title ?>: </span>
                            </td>
                            <td class="right">
                                <strong class="text-danger"><?php echo $ordertotal->text ?></strong>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tfoot>
                </table>
                <br />
            </div>
        </div>
    </div>
<?php } ?>
