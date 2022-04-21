<?php
/**
 * @version		3.1.0
 * @package		Joomla
 * @subpackage	EShop - Weight Shipping
 * @author  	Giang Dinh Truong
 * @copyright	Copyright (C) 2012 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined( '_JEXEC' ) or die();

class eshop_weight extends eshop_shipping
{

	/**
	 *
	 * Constructor function
	 */
	public function __construct()
	{
		parent::setName('eshop_weight');
		parent::__construct();
	}
	
	/**
	 * 
	 * Function tet get quote for Weight Shipping
	 * @param array $addressData
	 * @param object $params
	 */
	function getQuote($addressData, $params)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$cart = new EshopCart();
		$tax = new EshopTax(EshopHelper::getConfig());
		$currency = new EshopCurrency();
		$weight = new EshopWeight();
		$cartWeight = $cart->getWeight();
		$rates = explode("\r\n", $params->get('rates'));
		$quoteData = array();
		$cart = new EshopCart();
		$total = $cart->getTotal();
		$minTotal = $params->get('min_total', 0);
		$status = true;
		
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
		
		if ($status)
		{
			for ($i = 0; $n = count($rates), $i < $n; $i++)
			{
				$status = false;
				$rate = explode("|", $rates[$i]);
				$geozoneId = $rate[0];
				if ($geozoneId)
				{
					$query->clear();
					$query->select('COUNT(*)')
						->from('#__eshop_geozonezones')
						->where('geozone_id = ' . intval($geozoneId))
						->where('country_id = ' . intval($addressData['country_id']))
						->where('(zone_id = 0 OR zone_id = ' . intval($addressData['zone_id']) . ')');
					$db->setQuery($query);
					if ($db->loadResult())
					{
						$status = true;
					}
					
					//Check geozone postcode status
					if ($status)
					{
						$gzpStatus = EshopHelper::getGzpStatus($geozoneId, $addressData['postcode']);
					
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
				$cost = 0;
				if ($status)
				{
					for ($j = 1; $m = count($rate), $j < $m; $j++)
					{
						$data = explode(";", $rate[$j]);
						if (isset($data[0]) && $data[0] >= $cartWeight)
						{
							if (isset($data[1]))
							{
								$cost = $data[1];
								break;
							}
						}
					}
				}
				if ($cost)
				{
					$packageFee = $params->get('package_fee', 0);
					$cost = $cost + $packageFee;
					$geozone = EshopHelper::getGeozone($geozoneId);
					
					if ($params->get('show_shipping_cost_with_tax', 1))
					{
					    $text = $currency->format($tax->calculate($cost, $params->get('taxclass_id'), EshopHelper::getConfigValue('tax')));
					}
					else
					{
					    $text = $currency->format($cost);
					}
					
					$quoteData['weight_' . $geozoneId] = array (
						'name'			=> 'eshop_weight.weight_' . $geozoneId,
						'title'			=> JText::_($geozone->geozone_name) . ($params->get('show_weight') ? ' (' . JText::_('PLG_ESHOP_WEIGHT_WEIGHT') . ': ' . $weight->format($cartWeight, EshopHelper::getConfigValue('weight_id')) . ')' : ''),
						'cost'			=> $cost,
						'taxclass_id' 	=> $params->get('taxclass_id'),
						'text'			=> $text);
				}
			}
		}
		$methodData = array();
		if ($quoteData)
		{
			$query->clear();
			$query->select('*')
				->from('#__eshop_shippings')
				->where('name = "eshop_weight"');
			$db->setQuery($query);
			$row = $db->loadObject();
			$methodData = array (
				'name'		=> 'eshop_weight',
				'title'		=> JText::_('PLG_ESHOP_WEIGHT_TITLE'),
				'quote'		=> $quoteData,
				'ordering'	=> $row->ordering,
				'error'		=> false);
		}
		return $methodData;
	}
}