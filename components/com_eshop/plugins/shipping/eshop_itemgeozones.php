<?php
/**
 * @version		3.1.0
 * @package		Joomla
 * @subpackage	EShop - Item Geo Zones Shipping
 * @author  	Giang Dinh Truong
 * @copyright	Copyright (C) 2012 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined( '_JEXEC' ) or die();

class eshop_itemgeozones extends eshop_shipping
{

	/**
	 *
	 * Constructor function
	 */
	public function __construct()
	{
		parent::setName('eshop_itemgeozones');
		parent::__construct();
	}
	
	/**
	 * 
	 * Function tet get quote for item geozones shipping
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
			{
				$customerGroupId = 0;
			}
			
			if (!in_array($customerGroupId, $customerGroups))
			{
				$status = false;
			}
		}

		$cart = new EshopCart();
		
		//Check min total
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
		
		//Find the Geo Zone first
		$query->clear()
			->select('geozone_id')
			->from('#__eshop_geozonezones')
			->where('country_id = ' . intval($addressData['country_id']))
			->where('(zone_id = 0 OR zone_id = ' . intval($addressData['zone_id']) . ')');
		$db->setQuery($query);
		$geozoneIds = $db->loadColumn();
		
		$foundGeozoneId = 0;
		foreach ($geozoneIds as $geozoneId)
		{
			$gzpStatus = EshopHelper::getGzpStatus($geozoneId, $addressData['postcode']);
				
			if ($gzpStatus)
			{
				$foundGeozoneId = $geozoneId;
				break;
			}
		}

		if ($status && $foundGeozoneId > 0)
		{
			$packageFee = $params->get('package_fee', 0);
			$cost = 0;
			
			foreach ($cart->getCartData() as $product)
			{
				if ($product['product_shipping'])
				{
					$productShippingCost			= 0;
					$productShippingCostGeozones	= $product['product_shipping_cost_geozones'];
					$productShippingCostGeozonesArr = explode('|', $productShippingCostGeozones);

					for ($i = 0; $n = count($productShippingCostGeozonesArr), $i < $n; $i++)
					{
						$productShippingCostGeozonesElement = explode(':', $productShippingCostGeozonesArr[$i]);
						if (isset($productShippingCostGeozonesElement[0]) && $productShippingCostGeozonesElement[0] == $foundGeozoneId)
						{
							if (isset($productShippingCostGeozonesElement[1]))
							{
								$productShippingCost = $productShippingCostGeozonesElement[1];
								break;
							}
						}
					}
					
					if (!$productShippingCost)
					{
						$productShippingCost = $product['product_shipping_cost'];
					}
					
					if ($params->get('depend_quantity', 0))
					{
						$cost += $productShippingCost * $product['quantity'];
					}
					else 
					{
						$cost += $productShippingCost;
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
				->where('name = "eshop_itemgeozones"');
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
				'name'			=> 'eshop_itemgeozones.item', 
				'title'			=> JText::_('PLG_ESHOP_ITEM_GEOZONES_DESC'), 
				'cost'			=> $cost, 
				'taxclass_id' 	=> $params->get('taxclass_id'), 
				'text'			=> $text);
			
			$methodData = array (
				'name'		=> 'eshop_itemgeozones',
				'title'		=> JText::_('PLG_ESHOP_ITEM_GEOZONES_TITLE'),
				'quote'		=> $quoteData,
				'ordering'	=> $row->ordering,
				'error'		=> false);
		}
		
		return $methodData;
	}
}