<?php
/**
 * @version        3.1.0
 * @package        Joomla
 * @subpackage     EShop
 * @author         Giang Dinh Truong
 * @copyright      Copyright (C) 2013 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die();

class EshopShipping
{

    /**
     *
     * Function to get Costs, passed by reference to update
     *
     * @param array $totalData
     * @param float $total
     * @param array $taxes
     */
    public function getCosts(&$totalData, &$total, &$taxes)
    {
        $currency = new EshopCurrency();
        $shippingMethod = JFactory::getSession()->get('shipping_method');
        if (is_array($shippingMethod)) {
            $totalData[] = array(
                'name' => 'shipping',
                'title' => $shippingMethod['title'],
                'text' => $currency->format($shippingMethod['cost']),
                'value' => $shippingMethod['cost']);

            if (!empty($shippingMethod['taxclass_id'])) {
                $tax = new EshopTax(EshopHelper::getConfig());
                $taxRates = $tax->getTaxRates($shippingMethod['cost'], $shippingMethod['taxclass_id']);

                foreach ($taxRates as $taxRate) {
                    if (!isset($taxes[$taxRate['tax_rate_id']])) {
                        $taxes[$taxRate['tax_rate_id']] = $taxRate['amount'];
                    } else {
                        $taxes[$taxRate['tax_rate_id']] += $taxRate['amount'];
                    }
                }
            }

            $total += $shippingMethod['cost'];
        }
    }

    public function setExtraShippingCosts(&$totalData, &$total, $fee = 0)
    {
        if ($fee > 0) {
            $currency = new EshopCurrency();
            $db = JFactory::getDbo();
            $sql = 'SELECT `name`, title FROM #__eshop_shippings WHERE `name` = \'eshop_ghtk\'';
            $shipping = $db->setQuery($sql)->loadAssoc();
            $totalData[] = array(
                'name' => $shipping['name'],
                'title' => $shipping['title'],
                'text' => $currency->format($fee),
                'value' => $fee);
            $total += $fee;
        }

    }
}
