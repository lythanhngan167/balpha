<?php
/**
 * @version		3.1.0
 * @package		Joomla
 * @subpackage	EShop - Item Shipping
 * @author  	Giang Dinh Truong
 * @copyright	Copyright (C) 2012 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined( '_JEXEC' ) or die();

class eshop_item extends eshop_shipping
{

	/**
	 *
	 * Constructor function
	 */
	public function __construct()
	{
		parent::setName('eshop_item');
		parent::__construct();
	}
	
	/**
	 * 
	 * Function tet get quote for item shipping
	 * @param array $addressData
	 * @param object $params
	 */
	function getQuote($addressData, $params)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		if (!$params->get('geozone_id'))
		{
			$status = true;
		}
		else
		{
			$query->select('COUNT(*)')
				->from('#__eshop_geozonezones')
				->where('geozone_id = ' . intval($params->get('geozone_id')))
				->where('country_id = ' . intval($addressData['country_id']))
				->where('(zone_id = 0 OR zone_id = ' . intval($addressData['zone_id']) . ')');
			$db->setQuery($query);
			if ($db->loadResult())
			{
				$status = true;
			}
			else
			{
				$status = false;
			}
			
			//Check geozone postcode status
			if ($status)
			{
				$gzpStatus = EshopHelper::getGzpStatus($params->get('geozone_id'), $addressData['postcode']);
			
				if (!$gzpStatus)
				{
					$status = false;
				}
			}
		}
		//Check customer groups
		$customerGroups = $params->get('customer_groups');
		if (count($customerGroups))
		{
			$user = JFactory::getUser();
			if ($user->get('id'))
			{
				$customer = new EshopCustomer();
				$customerGroupId = $customer->getCustomerGroupId();
			}
			else
			{
				$customerGroupId = EshopHelper::getConfigValue('customergroup_id');
			}
			if (!$customerGroupId)
				$customerGroupId = 0;
			if (!in_array($customerGroupId, $customerGroups))
			{
				$status = false;
			}
		}
		$cart = new EshopCart();
		$total = $cart->getTotal();
		$minTotal = $params->get('min_total', 0);
		
		if ($minTotal > 0 && $total >= $minTotal)
		{
			$status = false;
		}
		
		$quantity = $cart->countProducts(true);
		$minQuantity = $params->get('min_quantity', 0);
		
		if ($minQuantity > 0 && $quantity >= $minQuantity)
		{
			$status = false;
		}
		
		$methodData = array();
		if ($status)
		{
			$packageFee = $params->get('package_fee', 0);
			$cost = 0;
			foreach ($cart->getCartData() as $product)
			{
				if ($product['product_shipping'])
				{
					if ($params->get('depend_quantity', 0))
					{
						$cost += $product['product_shipping_cost'] * $product['quantity'];
					}
					else 
					{
						$cost += $product['product_shipping_cost'];
					}
				}
			}
			$cost = $cost + $packageFee;
			$tax = new EshopTax(EshopHelper::getConfig());
			$currency = new EshopCurrency();
			$quoteData = array();
			$query->clear();
			$query->select('*')
				->from('#__eshop_shippings')
				->where('name = "eshop_item"');
			$db->setQuery($query);
			$row = $db->loadObject();
			
			if ($params->get('show_shipping_cost_with_tax', 1))
			{
			    $text = $currency->format($tax->calculate($cost, $params->get('taxclass_id'), EshopHelper::getConfigValue('tax')));
			}
			else
			{
			    $text = $currency->format($cost);
			}
			
			$quoteData['item'] = array (
				'name'			=> 'eshop_item.item', 
				'title'			=> JText::_('PLG_ESHOP_ITEM_DESC'), 
				'cost'			=> $cost, 
				'taxclass_id' 	=> $params->get('taxclass_id'), 
				'text'			=> $text);
			
			$methodData = array (
				'name'		=> 'eshop_item',
				'title'		=> JText::_('PLG_ESHOP_ITEM_TITLE'),
				'quote'		=> $quoteData,
				'ordering'	=> $row->ordering,
				'error'		=> false);
		}
		return $methodData;
	}
}