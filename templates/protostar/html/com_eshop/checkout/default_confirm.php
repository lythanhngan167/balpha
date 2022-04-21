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
$pullRightClass         = $bootstrapHelper->getClassMapping('pull-right');
$btnClass				= $bootstrapHelper->getClassMapping('btn');

if (isset($this->success))
{
    ?>
    <div class="success"><?php echo $this->success; ?></div>
    <?php
}
?>
    <div class="cart-info">

        <?php /*
        if ($this->paymentClass->getName() == 'os_bank_transfer'){
            $params = $this->paymentClass->getParams();
            if ($params['payment_info']) {?>
                <div class="payment_info">
                    <?php echo nl2br($params['payment_info']); ?>
                </div>
            <?php }
        } */
        ?>
        <table class="table table-hover">
            <thead class="thead-light">
                <tr>
                    <th width="70%"><?php echo JText::_('ESHOP_PRODUCT_NAME'); ?></th>
                    <th class="text-right" width="30%"><?php echo JText::_('ESHOP_TOTAL'); ?></th>
                </tr>
            </thead>
            <tbody>
            <?php
            foreach ($this->cartData as $key => $product)
            {
                $optionData = $product['option_data'];
                $viewProductUrl = JRoute::_(EshopRoute::getProductRoute($product['product_id'], EshopHelper::getProductCategory($product['product_id'])));
                ?>
                <tr>
                    <td data-content="<?php echo JText::_('ESHOP_PRODUCT_NAME'); ?>">
                        <a href="<?php echo $viewProductUrl; ?>">
                            <?php echo $product['product_name']; ?>
                        </a><br />
                        <?php
                        for ($i = 0; $n = count($optionData), $i < $n; $i++)
                        {
                            echo '- ' . $optionData[$i]['option_name'] . ': ' . $optionData[$i]['option_value'] . (isset($optionData[$i]['sku']) && $optionData[$i]['sku'] != '' ? ' (' . $optionData[$i]['sku'] . ')' : '') . '<br />';
                        }
                        ?> (<?php echo $product['quantity']; ?> x <?php echo EshopHelper::getWeightUnitName($product['product_weight_id'], JFactory::getLanguage()->getTag()) ; ?> )
                    </td>
                    <td class="text-right" data-content="<?php echo JText::_('ESHOP_TOTAL'); ?>">
						<span class="total-price">
                            <?php
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
                            ?>
                        </span>
                    </td>
                </tr>
                <?php
            }
            foreach ($this->totalData as $data)
            {
                ?>
                <tr>
                    <td class="text-right"><span class="title"><?php echo $data['title']; ?></span> :</td>
                    <td class="text-right"><span class="price"><?php echo $data['text']; ?></span></td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
    </div>
<?php
if ($this->total > 0)
{

    if ($this->paymentClass->getName() != 'os_squareup')
    {
        ?>
        <div class="eshop-payment-information">
            <?php echo $this->paymentClass->renderPaymentInformation(); ?>
            <div class="clearfix"></div>
        </div>
        <?php
    }
}
else
{
    ?>

    <button type="submit" class="btn btn-outline-success" id="button-confirm" ><?php echo JText::_('ESHOP_CONFIRM_ORDER'); ?></button>
    <?php echo JHtml::_('form.token'); ?>
    <!--	</form>-->
    <?php
}
