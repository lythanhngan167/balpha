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
use api\model\libs\VnpayGateway;

defined('_JEXEC') or die();
?>
<h1><?php echo JText::_('ESHOP_PAYMENT_FAILURE_TITLE'); ?></h1>
<p>
    <?php
    $session = JFactory::getSession();

    $paymentData = $session->get('payment_data');
    ?>
</p>
<div style="background: #FFF; padding: 10px;">
    <?php if ($paymentData): ?>
        Mã đơn hàng: <?= $paymentData['vnp_OrderInfo']; ?> ( Mã đơn hàng tại VNPAY: <?= $paymentData['vnp_TxnRef']; ?>)
        <br/>
        Số tiền:  <?= number_format($paymentData['vnp_Amount'] / 100) ?><br/>
        Thời gian: <?= EshopHelper::formatVnpayDate($paymentData['vnp_PayDate']); ?><br/>
        Trạng thái thanh toán: Giao dịch thất bại.

    <?php else:
        echo $session->get('omnipay_payment_error_reason');
        ?>
    <?php endif; ?>

</div>
