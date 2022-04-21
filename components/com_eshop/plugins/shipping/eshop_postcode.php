<?php
/**
 * @version		3.1.0
 * @package		Joomla
 * @subpackage	EShop - Postcode Shipping
 * @author  	Giang Dinh Truong
 * @copyright	Copyright (C) 2012 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined( '_JEXEC' ) or die();

class eshop_postcode extends eshop_shipping
{

	/**
	 *
	 * Constructor function
	 */
	public function __construct()
	{
		parent::setName('eshop_postcode');
		parent::__construct();
	}
	
	/**
	 * 
	 * Function tet get quote for postcode shipping
	 * @param array $addressData
	 * @param object $params
	 */
	function getQuote($addressData, $params)
	{
		//Check geozone condition
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
		//Check min total for free condition
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
		
		//Check input data condition
		$shippingCostRule = explode("\r\n", $params->get('shipping_cost_rule'));
		if (!count($shippingCostRule))
		{
			$status = false;
		}
		$methodData = array();
		if ($status)
		{
			$tax = new EshopTax(EshopHelper::getConfig());
			$currency = new EshopCurrency();
			$quoteData = array();
			$packageFee = $params->get('package_fee', 0);
			$cost = 0;
			$shippingAvailable = false;
			for ($i = 0; $n = count($shippingCostRule), $i < $n; $i++)
			{
				$shippingCostRuleZone = explode('|', $shippingCostRule[$i]);
				if (count($shippingCostRuleZone))
				{
					$zoneId = $shippingCostRuleZone[0];
					if ($zoneId == $addressData['zone_id'])
					{
						for ($j = 1; $m = count($shippingCostRuleZone), $j < $m; $j++)
						{
							$shippingCostRulePostcode = explode(':', $shippingCostRuleZone[$j]);
							$shippingCostRulePostcodeRange = explode('/', $shippingCostRulePostcode[0]);
							for ($k = 0; $o = count($shippingCostRulePostcodeRange), $k < $o; $k++)
							{
								$shippingCostRulePostcodeRangeArr = explode('-', $shippingCostRulePostcodeRange[$k]);
								$shippingCostRulePostcodeStart = $shippingCostRulePostcodeRangeArr[0];
								$shippingCostRulePostcodeEnd = $shippingCostRulePostcodeRangeArr[1];
								if ($addressData['postcode'] >= $shippingCostRulePostcodeStart && $addressData['postcode'] <= $shippingCostRulePostcodeEnd)
								{
									$cost = $shippingCostRulePostcode[1];
									$shippingAvailable = true;
									break;
								}
							}
							if ($shippingAvailable)
								break;
						}
						break;
					}
				}
			}
			if ($shippingAvailable)
			{
				$cost += $packageFee;
				$query->clear();
				$query->select('*')
					->from('#__eshop_shippings')
					->where('name = "eshop_postcode"');
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
				
				if ($cost)
				{
					$quoteData['postcode'] = array(
						'name'			=> 'eshop_postcode.postcode',
						'title'			=> JText::_('PLG_ESHOP_POSTCODE_DESC'),
						'cost'			=> $cost,
						'taxclass_id' 	=> $params->get('taxclass_id'),
						'text'			=> $text);
					$methodData = array(
						'name'		=> 'eshop_postcode',
						'title'		=> JText::_('PLG_ESHOP_POSTCODE_TITLE'),
						'quote'		=> $quoteData,
						'ordering'	=> $row->ordering,
						'error'		=> false);
				}
				else 
				{
					$quoteData['postcode'] = array(
						'name'			=> 'eshop_postcode.postcode',
						'title'			=> JText::_('PLG_ESHOP_POSTCODE_FREE_DESC'),
						'cost'			=> $cost,
						'taxclass_id' 	=> $params->get('taxclass_id'),
						'text'			=> $currency->format($cost));
					$methodData = array(
						'name'		=> 'eshop_postcode',
						'title'		=> JText::_('PLG_ESHOP_POSTCODE_FREE_TITLE'),
						'quote'		=> $quoteData,
						'ordering'	=> $row->ordering,
						'error'		=> false);
				}
			}
		}
		return $methodData;
	}
}