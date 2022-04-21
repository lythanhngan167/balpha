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

$librariesPath = dirname(__FILE__);
$classes = array(
	'EShopViewList'			=> $librariesPath.'/mvc/viewlist.php',
	'EShopViewForm'			=> $librariesPath.'/mvc/viewform.php',
	'EShopModelList'		=> $librariesPath.'/mvc/modellist.php',				
	'EShopModel'			=> $librariesPath.'/mvc/model.php',
	'EShopController'		=> $librariesPath.'/mvc/controller.php',
	'EShopTable'			=> $librariesPath.'/mvc/table.php',
	'EShopView'				=> $librariesPath.'/mvc/view.php',
	'EshopCart'				=> JPATH_ROOT.'/components/com_eshop/helpers/cart.php',
	'EshopQuote'			=> JPATH_ROOT.'/components/com_eshop/helpers/quote.php',
	'EshopCoupon'			=> JPATH_ROOT.'/components/com_eshop/helpers/coupon.php',
	'EshopVoucher'			=> JPATH_ROOT.'/components/com_eshop/helpers/voucher.php',
	'EshopCurrency'			=> JPATH_ROOT.'/components/com_eshop/helpers/currency.php',
	'EshopCustomer'			=> JPATH_ROOT.'/components/com_eshop/helpers/customer.php',
	'EshopHelper'			=> JPATH_ROOT.'/components/com_eshop/helpers/helper.php',
	'EshopAPI'				=> JPATH_ROOT.'/components/com_eshop/helpers/api.php',
	'EshopGoogleAnalytics'	=> JPATH_ROOT.'/components/com_eshop/helpers/googleanalytics.php',
	'EshopHtmlHelper'		=> JPATH_ROOT.'/components/com_eshop/helpers/html.php',
	'EshopImage'			=> JPATH_ROOT.'/components/com_eshop/helpers/image.php',
	'EshopLength'			=> JPATH_ROOT.'/components/com_eshop/helpers/length.php',
	'EshopOption'			=> JPATH_ROOT.'/components/com_eshop/helpers/option.php',
	'EshopShipping'			=> JPATH_ROOT.'/components/com_eshop/helpers/shipping.php',
	'EshopPayment'			=> JPATH_ROOT.'/components/com_eshop/helpers/payment.php',
	'EshopDiscount'			=> JPATH_ROOT.'/components/com_eshop/helpers/discount.php',
	'EshopDonate'			=> JPATH_ROOT.'/components/com_eshop/helpers/donate.php',
	'EshopEuvat'			=> JPATH_ROOT.'/components/com_eshop/helpers/euvat.php',
	'EshopFile'				=> JPATH_ROOT.'/components/com_eshop/helpers/file.php',
	'EshopTax'				=> JPATH_ROOT.'/components/com_eshop/helpers/tax.php',
	'EshopWeight'			=> JPATH_ROOT.'/components/com_eshop/helpers/weight.php',
    'EshopHelperBootstrap'  => JPATH_ROOT.'/components/com_eshop/helpers/bootstrap.php',
	'os_payments'			=> JPATH_ROOT.'/components/com_eshop/plugins/payment/os_payments.php',
	'os_payment'			=> JPATH_ROOT.'/components/com_eshop/plugins/payment/os_payment.php',
	'EShopOmnipayPayment'	=> JPATH_ROOT.'/components/com_eshop/plugins/payment/omnipay.php',
	'eshop_shipping'		=> JPATH_ROOT.'/components/com_eshop/plugins/shipping/eshop_shipping.php'
);

foreach ($classes as $className => $path)
{
	JLoader::register($className, $path);
}
JLoader::register('EshopRoute', JPATH_ROOT.'/components/com_eshop/helpers/' . ((version_compare(JVERSION, '3.0', 'ge') && JLanguageMultilang::isEnabled() && count(EshopHelper::getLanguages()) > 1) ? 'routev3.php' : 'route.php'));

// Disable ONLY_FULL_GROUP_BY mode
if (version_compare(JVERSION, '4.0.0-dev', 'ge'))
{
    $db = JFactory::getDbo();
    $db->setQuery("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
    $db->execute();
}