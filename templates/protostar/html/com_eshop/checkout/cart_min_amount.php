<?php
/**
 * @version        3.1.0
 * @package        Joomla
 * @subpackage    EShop
 * @author    Giang Dinh Truong
 * @copyright    Copyright (C) 2012 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
use api\model\Sconfig;
defined('_JEXEC') or die();
$config = new Sconfig();

 ?>
<h1><?php echo JText::_('ESHOP_PAYMENT_FAILURE_TITLE'); ?></h1>
<p>
</p>
<div style="background: #FFF; padding: 10px;">
    Đặt hàng online chỉ áp dụng cho đơn hàng tối thiểu <span style="color: red"><?= number_format($config->minCartAmount) ?> đ</span>

</div>
