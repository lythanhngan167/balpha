<?php
/**
 * @version		3.1.0
 * @package		Joomla
 * @subpackage	EShop - Flat Item Shipping
 * @author  	Giang Dinh Truong
 * @copyright	Copyright (C) 2012 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined( '_JEXEC' ) or die();

class eshop_flatitem extends eshop_shipping
{

	/**
	 *
	 * Constructor function
	 */
	public function __construct()
	{
		parent::setName('eshop_flatitem');
		parent::__construct();
	}
	
	/**
	 * 
	 * Function tet get quote for flat item shipping
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
					$cost = $rate[1];
				}
				$cost = $cart->countProducts() * $cost;
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
					
					$quoteData['flatitem_' . $geozoneId] = array (
						'name'			=> 'eshop_flatitem.flatitem_' . $geozoneId,
						'title'			=> $geozone->geozone_name,
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
				->where('name = "eshop_flatitem"');
			$db->setQuery($query);
			$row = $db->loadObject();
			$methodData = array (
				'name'		=> 'eshop_flatitem',
				'title'		=> JText::_('PLG_ESHOP_FLATITEM_TITLE'),
				'quote'		=> $quoteData,
				'ordering'	=> $row->ordering,
				'error'		=> false);
		}
		return $methodData;
	}
}