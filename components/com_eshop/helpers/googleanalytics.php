<?php
/**
 * @version        3.1.0
 * @package        Joomla
 * @subpackage     EShop
 * @author         Giang Dinh Truong
 * @copyright      Copyright (C) 2012 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
// no direct access
//defined('_JEXEC') or die();

class EshopGoogleAnalytics
{
	/**
	 *
	 * Function to process Classic Analytics
	 * @param order object $row
	 */
	public static function processClassicAnalytics($row)
	{
		//Initialise
		$doc = JFactory::getDocument();
	
		if ($row->id)
		{
			//Get transaction Id
			$transactionId = $row->id;
				
			//Get sub total
			$subTotal = self::getOrderTotalValue($row->id, 'sub_total');
				
			//Get tax
			$tax = self::getOrderTotalValue($row->id, 'tax');
				
			//Get shipping
			$shipping = self::getOrderTotalValue($row->id, 'shipping');
				
			$script = "";
			$script .= "
				(function() {
				  var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
				  ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
				  var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
				})();
    			";
	
			//Add Transaction
			$script .= "
    				var _gaq = _gaq || [];
					  _gaq.push(['_setAccount', '" . EshopHelper::getConfigValue('ga_tracking_id') . "']);
					  _gaq.push(['_trackPageview']);
					  _gaq.push(['_addTrans',
					    '" . $transactionId . "',
					    '" . EshopHelper::getConfigValue('store_name') . "',
					    '" . number_format($subTotal, 2) . "',
					    '" . number_format($tax, 2) . "',
					    '" . number_format($shipping, 2) . "',
					    '" . $row->payment_city . "',
					    '" . $row->payment_zone_name . "',
					    '" . $row->payment_country_name . "'
					  ]);
    				";
	
	
			//Now Add Items
			$orderProducts = self::getOrderProducts($row->id);
				
			foreach($orderProducts as $product)
			{
	
				$productSku = $product->product_sku;
				$productVariation = self::getProductVariation($row, $product);
	
				$script .= "
				_gaq.push(['_addItem',
				    '" . $transactionId . "',
				    '" . $product->product_sku . "',
				    '" . $product->product_name . "',";
	
				if ($productVariation != '')
				{
					$script .= "'" . $productVariation . "',";
				}
	
				$script .= "
				    '" . number_format($product->price, 2) . "',
				    '" . $product->quantity . "'
				  ]);
    			";
			}
	
			//Add currency
			$script .= "
    				_gaq.push(['_set', 'currencyCode', '" . $row->currency_code . "']);
    				";
	
			//Submit transaction
			$script .= "_gaq.push(['_trackTrans']);";
				
			$doc->addScriptDeclaration($script);
		}
	}
	
	/**
	 *
	 * Function to process Universal Analytics
	 * @param order object $row
	 */
	public static function processUniversalAnalytics($row)
	{
		//Initialise
		$doc = JFactory::getDocument();
	
		if ($row->id)
		{
			//Get transaction Id
			$transactionId = $row->id;
	
			//Get sub total
			$subTotal = self::getOrderTotalValue($row->id, 'sub_total');
	
			//Get tax
			$tax = self::getOrderTotalValue($row->id, 'tax');
	
			//Get shipping
			$shipping = self::getOrderTotalValue($row->id, 'shipping');
				
			$script = "";
			$script .= "
				(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
					(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
					m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
				})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
    			";
	
			//Add Transaction
			$script .= "
    				ga('create', '" . EshopHelper::getConfigValue('ga_tracking_id') . "', 'auto');
					ga('send', 'pageview');
					ga('require', 'ecommerce');
    				ga('ecommerce:addTransaction', {
					  'id': '" . $transactionId . "',
					  'affiliation': '" . EshopHelper::getConfigValue('store_name') . "',
					  'revenue': '" . number_format($subTotal, 2) . "',
					  'shipping': '" . number_format($shipping, 2) . "',
					  'tax': '" . number_format($tax, 2) . "',
					  'currency': '" . $row->currency_code . "'
					});
    				";
	
			//Now Add Items
				
			$orderProducts = self::getOrderProducts($row->id);
	
			foreach($orderProducts as $product)
			{
					
				$productSku = $product->product_sku;
				$productVariation = self::getProductVariation($row, $product);
					
				$script .= "
    			ga('ecommerce:addItem', {
				  'id': '" . $transactionId . "',
				  'name': '" . $product->product_name . "',
				  'sku': '" . $product->product_sku . "',";
	
				if ($productVariation != '')
				{
					$script .= "'category': '" . $productVariation . "',";
				}
				$script .= "
				  'price': '" . number_format($product->price, 2) . "',
				  'quantity': '" . $product->quantity . "'
				});
    			";
			}
	
			//Submit transaction
			$script .= "ga('ecommerce:send');";
				
			$doc->addScriptDeclaration ( $script );
		}
	}
	
	/**
	 *
	 * Private function to get order products
	 * @param int $orderId
	 * @return order products objects list
	 */
	public static function getOrderProducts($orderId)
	{
		$db       = JFactory::getDbo();
		$query    = $db->getQuery(true);
	
		$query->select('*')
		->from('#__eshop_orderproducts')
		->where('order_id = ' . intval($orderId));
		$db->setQuery($query);
		$orderProducts = $db->loadObjectList();
	
		return $orderProducts;
	}
	
	/**
	 *
	 * Function to get a total value of a specific order
	 * @param string $totalName - example: total, sub_total, shipping, tax
	 * @return float
	 */
	public static function getOrderTotalValue($orderId, $totalName)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
	
		$query->select('value')
		->from('#__eshop_ordertotals')
		->where('order_id = ' . intval($orderId))
		->where('name = ' . $db->quote($totalName));
		$db->setQuery($query);
		$totalValue = $db->loadResult();
	
		if (!$totalValue)
		{
			$totalValue = 0;
		}
	
		return $totalValue;
	}
	
	/**
	 *
	 * Function to get product variation
	 * @param order object $row
	 * @param order product object $product
	 * @return string product variation
	 */
	public static function getProductVariation($row, $product)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$variationType = EshopHelper::getConfigValue('variation_type', 'none');
		$productVariation = '';
	
		if ($variationType == 'variation')
		{
			$query->select('option_name, option_value')
			->from('#__eshop_orderoptions')
			->where('order_product_id = ' . intval($product->id));
			$db->setQuery($query);
			$productOrderOptions = $db->loadObjectList();
				
			if (count($productOrderOptions))
			{
				foreach ($productOrderOptions as $productOrderOption)
				{
					$productVariation .= ' | '. $productOrderOption->option_name . ':' . $productOrderOption->option_value;
				}
			}
		}
		elseif ($variationType == 'category')
		{
			$categoryId = EshopHelper::getProductCategory($product->product_id);
				
			if ($categoryId > 0)
			{
				$query->select('category_name')
				->from('#__eshop_categorydetails')
				->where('category_id = ' . intval($categoryId))
				->where('language = ' . $db->quote($row->language));
				$db->setQuery($query);
				$productVariation = $db->loadResult();
			}
		}
	
		return $productVariation;
	}
}