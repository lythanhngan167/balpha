<?php
/**
 * @version		3.1.0
 * @package		Joomla
 * @subpackage	EShop - UPS Shipping
 * @author  	Giang Dinh Truong
 * @copyright	Copyright (C) 2012 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined( '_JEXEC' ) or die();

class eshop_ups extends eshop_shipping
{
	
	/**
	 * 
	 * Constructor function
	 */
	public function __construct()
	{
		parent::setName('eshop_ups');
		parent::__construct();
	}

	/**
	 * 
	 * Function tet get quote for ups shipping
	 * @param array $addressData
	 * @param object $params
	 */
	function getQuote($addressData, $params)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		if (!$params->get('ups_geozone_id'))
		{
			$status = true;
		}
		else
		{
			$query->select('COUNT(*)')
				->from('#__eshop_geozonezones')
				->where('geozone_id = ' . intval($params->get('ups_geozone_id')))
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
			$currency = new EshopCurrency();
			$tax = new EshopTax(EshopHelper::getConfig());
			$eshopWeight = new EshopWeight();
			$eshopLength = new EshopLength();
			$cartWeight = $cart->getWeight();

			// Get weight and weight code
			$weight = $eshopWeight->convert($cartWeight, EshopHelper::getConfigValue('weight_id'), $params->get('ups_weight_id'));
			$weight = ($weight < 0.1 ? 0.1 : $weight);
			$weightCode = strtoupper($eshopWeight->getUnit($params->get('ups_weight_id')));
			if ($weightCode == 'KG')
			{
				$weightCode = 'KGS';
			}
			elseif ($weightCode == 'LB')
			{
				$weightCode = 'LBS';
			}
			
			// Get length and length code
			$length = $eshopLength->convert($params->get('ups_length'), EshopHelper::getConfigValue('length_id'), $params->get('ups_length_id'));
			$width = $eshopLength->convert($params->get('ups_width'), EshopHelper::getConfigValue('length_id'), $params->get('ups_length_id'));
			$height = $eshopLength->convert($params->get('ups_height'), EshopHelper::getConfigValue('length_id'), $params->get('ups_length_id'));
			$lengthCode = strtoupper($eshopLength->getUnit($params->get('ups_length_id')));
			
			// Service code
			$serviceCode = array(
				// US Origin
				'US' => array(
						'01' => JText::_('PLG_ESHOP_UPS_US_ORIGIN_01'),
						'02' => JText::_('PLG_ESHOP_UPS_US_ORIGIN_02'),
						'03' => JText::_('PLG_ESHOP_UPS_US_ORIGIN_03'),
						'07' => JText::_('PLG_ESHOP_UPS_US_ORIGIN_07'),
						'08' => JText::_('PLG_ESHOP_UPS_US_ORIGIN_08'),
						'11' => JText::_('PLG_ESHOP_UPS_US_ORIGIN_11'),
						'12' => JText::_('PLG_ESHOP_UPS_US_ORIGIN_12'),
						'13' => JText::_('PLG_ESHOP_UPS_US_ORIGIN_13'),
						'14' => JText::_('PLG_ESHOP_UPS_US_ORIGIN_14'),
						'54' => JText::_('PLG_ESHOP_UPS_US_ORIGIN_54'),
						'59' => JText::_('PLG_ESHOP_UPS_US_ORIGIN_59'),
						'65' => JText::_('PLG_ESHOP_UPS_US_ORIGIN_65')
				),
				// Canada Origin
				'CA' => array(
						'01' => JText::_('PLG_ESHOP_UPS_CA_ORIGIN_01'),
						'02' => JText::_('PLG_ESHOP_UPS_CA_ORIGIN_02'),
						'07' => JText::_('PLG_ESHOP_UPS_CA_ORIGIN_07'),
						'08' => JText::_('PLG_ESHOP_UPS_CA_ORIGIN_08'),
						'11' => JText::_('PLG_ESHOP_UPS_CA_ORIGIN_11'),
						'12' => JText::_('PLG_ESHOP_UPS_CA_ORIGIN_12'),
						'13' => JText::_('PLG_ESHOP_UPS_CA_ORIGIN_13'),
						'14' => JText::_('PLG_ESHOP_UPS_CA_ORIGIN_14'),
						'54' => JText::_('PLG_ESHOP_UPS_CA_ORIGIN_54'),
						'65' => JText::_('PLG_ESHOP_UPS_CA_ORIGIN_65')
				),
				// European Union Origin
				'EU' => array(
						'07' => JText::_('PLG_ESHOP_UPS_EU_ORIGIN_07'),
						'08' => JText::_('PLG_ESHOP_UPS_EU_ORIGIN_08'),
						'11' => JText::_('PLG_ESHOP_UPS_EU_ORIGIN_11'),
						'54' => JText::_('PLG_ESHOP_UPS_EU_ORIGIN_54'),
						'65' => JText::_('PLG_ESHOP_UPS_EU_ORIGIN_65'),
						// next five services Poland domestic only
						'82' => JText::_('PLG_ESHOP_UPS_EU_ORIGIN_82'),
						'83' => JText::_('PLG_ESHOP_UPS_EU_ORIGIN_83'),
						'84' => JText::_('PLG_ESHOP_UPS_EU_ORIGIN_84'),
						'85' => JText::_('PLG_ESHOP_UPS_EU_ORIGIN_85'),
						'86' => JText::_('PLG_ESHOP_UPS_EU_ORIGIN_86')
				),
				// Puerto Rico Origin
				'PR' => array(
						'01' => JText::_('PLG_ESHOP_UPS_PR_ORIGIN_01'),
						'02' => JText::_('PLG_ESHOP_UPS_PR_ORIGIN_02'),
						'03' => JText::_('PLG_ESHOP_UPS_PR_ORIGIN_03'),
						'07' => JText::_('PLG_ESHOP_UPS_PR_ORIGIN_07'),
						'08' => JText::_('PLG_ESHOP_UPS_PR_ORIGIN_08'),
						'14' => JText::_('PLG_ESHOP_UPS_PR_ORIGIN_14'),
						'54' => JText::_('PLG_ESHOP_UPS_PR_ORIGIN_54'),
						'65' => JText::_('PLG_ESHOP_UPS_PR_ORIGIN_65')
				),
				// Mexico Origin
				'MX' => array(
						'07' => JText::_('PLG_ESHOP_UPS_MX_ORIGIN_07'),
						'08' => JText::_('PLG_ESHOP_UPS_MX_ORIGIN_08'),
						'54' => JText::_('PLG_ESHOP_UPS_MX_ORIGIN_54'),
						'65' => JText::_('PLG_ESHOP_UPS_MX_ORIGIN_65')
				),
				// All other origins
				'other' => array(
						// service code 7 seems to be gone after January 2, 2007
						'07' => JText::_('PLG_ESHOP_UPS_OTHER_ORIGIN_07'),
						'08' => JText::_('PLG_ESHOP_UPS_OTHER_ORIGIN_08'),
						'11' => JText::_('PLG_ESHOP_UPS_OTHER_ORIGIN_11'),
						'54' => JText::_('PLG_ESHOP_UPS_OTHER_ORIGIN_54'),
						'65' => JText::_('PLG_ESHOP_UPS_OTHER_ORIGIN_65')
				)
			);
			
			$xml  = '<?xml version="1.0"?>';
			$xml .= '<AccessRequest xml:lang="en-US">';
			$xml .= '	<AccessLicenseNumber>' . $params->get('ups_key') . '</AccessLicenseNumber>';
			$xml .= '	<UserId>' . $params->get('ups_username') . '</UserId>';
			$xml .= '	<Password>' . $params->get('ups_password') . '</Password>';
			$xml .= '</AccessRequest>';
			$xml .= '<?xml version="1.0"?>';
			$xml .= '<RatingServiceSelectionRequest xml:lang="en-US">';
			$xml .= '	<Request>';
			$xml .= '		<TransactionReference>';
			$xml .= '			<CustomerContext>Bare Bones Rate Request</CustomerContext>';
			$xml .= '			<XpciVersion>1.0001</XpciVersion>';
			$xml .= '		</TransactionReference>';
			$xml .= '		<RequestAction>Rate</RequestAction>';
			$xml .= '		<RequestOption>shop</RequestOption>';
			$xml .= '	</Request>';
			$xml .= '   <PickupType>';
			$xml .= '       <Code>' . $params->get('ups_pickup') . '</Code>';
			$xml .= '   </PickupType>';
			
			if ($params->get('ups_country') == 'US' && $params->get('ups_pickup') == '11')
			{
				$xml .= '   <CustomerClassification>';
				$xml .= '       <Code>' . $params->get('ups_classification') . '</Code>';
				$xml .= '   </CustomerClassification>';
			}
				
			$xml .= '	<Shipment>';
			$xml .= '		<Shipper>';
			$xml .= '			<Address>';
			$xml .= '				<City>' . $params->get('ups_city') . '</City>';
			$xml .= '				<StateProvinceCode>'. $params->get('ups_state') . '</StateProvinceCode>';
			$xml .= '				<CountryCode>' . $params->get('ups_country') . '</CountryCode>';
			$xml .= '				<PostalCode>' . $params->get('ups_postcode') . '</PostalCode>';
			$xml .= '			</Address>';
			$xml .= '		</Shipper>';
			$xml .= '		<ShipTo>';
			$xml .= '			<Address>';
			$xml .= ' 				<City>' . $addressData['city'] . '</City>';
			$xml .= '				<StateProvinceCode>' . $addressData['zone_code'] . '</StateProvinceCode>';
			$xml .= '				<CountryCode>' . $addressData['iso_code_2'] . '</CountryCode>';
			$xml .= '				<PostalCode>' . $addressData['postcode'] . '</PostalCode>';
				
			if ($params->get('ups_quote_type') == 'residential')
			{
				$xml .= '				<ResidentialAddressIndicator />';
			}
				
			$xml .= '			</Address>';
			$xml .= '		</ShipTo>';
			$xml .= '		<ShipFrom>';
			$xml .= '			<Address>';
			$xml .= '				<City>' . $params->get('ups_city') . '</City>';
			$xml .= '				<StateProvinceCode>'. $params->get('ups_state') . '</StateProvinceCode>';
			$xml .= '				<CountryCode>' . $params->get('ups_country') . '</CountryCode>';
			$xml .= '				<PostalCode>' . $params->get('ups_postcode') . '</PostalCode>';
			$xml .= '			</Address>';
			$xml .= '		</ShipFrom>';
			
			$xml .= '		<Package>';
			$xml .= '			<PackagingType>';
			$xml .= '				<Code>' . $params->get('ups_packaging') . '</Code>';
			$xml .= '			</PackagingType>';
			
			$xml .= '		    <Dimensions>';
			$xml .= '				<UnitOfMeasurement>';
			$xml .= '					<Code>' . $lengthCode . '</Code>';
			$xml .= '				</UnitOfMeasurement>';
			$xml .= '				<Length>' . $length . '</Length>';
			$xml .= '				<Width>' . $width . '</Width>';
			$xml .= '				<Height>' . $height . '</Height>';
			$xml .= '			</Dimensions>';
				
			$xml .= '			<PackageWeight>';
			$xml .= '				<UnitOfMeasurement>';
			$xml .= '					<Code>' . $weightCode . '</Code>';
			$xml .= '				</UnitOfMeasurement>';
			$xml .= '				<Weight>' . $weight . '</Weight>';
			$xml .= '			</PackageWeight>';
			
			if ($params->get('ups_insurance'))
			{
				$xml .= '           <PackageServiceOptions>';
				$xml .= '               <InsuredValue>';
				$xml .= '                   <CurrencyCode>' . $currency->getCurrencyCode() . '</CurrencyCode>';
				$xml .= '                   <MonetaryValue>' . $currency->format($cart->getSubTotal(), '', '', false) . '</MonetaryValue>';
				$xml .= '               </InsuredValue>';
				$xml .= '           </PackageServiceOptions>';
			}
				
			$xml .= '		</Package>';
			 
			$xml .= '	</Shipment>';
			$xml .= '</RatingServiceSelectionRequest>';
				
			if (!$params->get('ups_test'))
			{
				$url = 'https://www.ups.com/ups.app/xml/Rate';
			}
			else
			{
				$url = 'https://wwwcie.ups.com/ups.app/xml/Rate';
			}
	
			$curl = curl_init($url);
			
			curl_setopt($curl, CURLOPT_HEADER, 0);
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_TIMEOUT, 60);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $xml);
				
			$result = curl_exec($curl);
				
			curl_close($curl);
				
			$error = '';
				
			$quoteData = array();
			$packageFee = $params->get('package_fee', 0);
				
			if ($result)
			{
			
				$dom = new DOMDocument('1.0', 'UTF-8');
				$dom->loadXml($result);
			
				$ratingServiceSelectionResponse = $dom->getElementsByTagName('RatingServiceSelectionResponse')->item(0);
			
				$response = $ratingServiceSelectionResponse->getElementsByTagName('Response')->item(0);
			
				$responseStatusCode = $response->getElementsByTagName('ResponseStatusCode');
			
				if ($responseStatusCode->item(0)->nodeValue != '1')
				{
					$error = $response->getElementsByTagName('Error')->item(0)->getElementsByTagName('ErrorCode')->item(0)->nodeValue . ': ' . $response->getElementsByTagName('Error')->item(0)->getElementsByTagName('ErrorDescription')->item(0)->nodeValue;
				}
				else
				{
					$ratedShipments = $ratingServiceSelectionResponse->getElementsByTagName('RatedShipment');
					
					foreach ($ratedShipments as $ratedShipment)
					{
						$service = $ratedShipment->getElementsByTagName('Service')->item(0);
							
						$code = $service->getElementsByTagName('Code')->item(0)->nodeValue;
			
						$totalCharges = $ratedShipment->getElementsByTagName('TotalCharges')->item(0);
							
						$cost = $totalCharges->getElementsByTagName('MonetaryValue')->item(0)->nodeValue;
			
						$currencyCode = $totalCharges->getElementsByTagName('CurrencyCode')->item(0)->nodeValue;
						
						if (!($code && $cost))
						{
							continue;
						}
						$upsCode = $params->get('ups_' . strtolower($params->get('ups_origin')));
						if (in_array($code, $upsCode))
						{
							$cost = $cost + $packageFee;
							
							if ($params->get('show_shipping_cost_with_tax', 1))
							{
							    $text = $currency->format($tax->calculate($currency->convert($cost, $currencyCode, $currency->getCurrencyCode()), $params->get('ups_taxclass_id'), EshopHelper::getConfigValue('tax')), $currency->getCurrencyCode(), 1.0000000);
							}
							else
							{
							    $text = $currency->format($cost, $currency->getCurrencyCode(), 1.0000000);
							}
							
							$quoteData[$code] = array(
								'name'			=> 'eshop_ups.' . $code,
								'title'			=> $serviceCode[$params->get('ups_origin')][$code],
								'cost'			=> $currency->convert($cost, $currencyCode, EshopHelper::getConfigValue('default_currency_code')),
								'taxclass_id'	=> $params->get('ups_taxclass_id'),
								'text'			=> $text
							);
						}
					}
				}
			}
			
			$title = JText::_('PLG_ESHOP_UPS_TITLE');
				
			if ($params->get('ups_display_weight'))
			{
				$title .= ' (' . JText::_('PLG_ESHOP_UPS_WEIGHT') . ' ' . $eshopWeight->format($weight, $params->get('ups_weight_id')) . ')';
			}
			
			$query->clear();
			$query->select('*')
				->from('#__eshop_shippings')
				->where('name = "eshop_ups"');
			$db->setQuery($query);
			$row = $db->loadObject();
			
			$methodData = array(
				'name'			=> 'eshop_ups',
				'title'			=> $title,
				'quote'			=> $quoteData,
				'ordering'		=> $row->ordering,
				'error'			=> $error
			);
		}
		return $methodData;
	}
}