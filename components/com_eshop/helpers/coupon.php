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
defined('_JEXEC') or die;

class EshopCoupon
{

	/**
	 *
	 * Function to get Costs, passed by reference to update
	 *
	 * @param  array $totalData
	 * @param  float $total
	 * @param  array $taxes
	 */
	public function getCosts(&$totalData, &$total, &$taxes)
	{
		$session    = JFactory::getSession();
		$tax        = new EshopTax(EshopHelper::getConfig());
		$currency   = new EshopCurrency();
		$couponData = $this->getCouponData($session->get('coupon_code'));

		if (count($couponData))
		{
			$cart          = new EshopCart();
			$cartData      = $cart->getCartData();
			$discountTotal = 0;

			if (!count($couponData['coupon_products_data']))
			{
				$subTotal = $cart->getSubTotal();
			}
			else
			{
				$subTotal = 0;

				foreach ($cartData as $product)
				{
					if (in_array($product['product_id'], $couponData['coupon_products_data']))
					{
						$subTotal += $product['total_price'];
					}
				}
			}

			if ($couponData['coupon_type'] == 'F')
			{
				$couponData['coupon_value'] = min($couponData['coupon_value'], $subTotal);
			}

			foreach ($cartData as $product)
			{
				$discount = 0;

				if (!$couponData['coupon_products_data'])
				{
					$status = true;
				}
				else
				{
					if (in_array($product['product_id'], $couponData['coupon_products_data']))
					{
						$status = true;
					}
					else
					{
						$status = false;
					}
				}

				if ($status)
				{
					if ($couponData['coupon_type'] == 'F')
					{
						$discount = $couponData['coupon_value'] * ($product['total_price'] / $subTotal);
					}
					elseif ($couponData['coupon_type'] == 'P')
					{
						$discount = $product['total_price'] / 100 * $couponData['coupon_value'];
					}

					if ($product['product_taxclass_id'])
					{
						$taxRates = $tax->getTaxRates($product['total_price'] - ($product['total_price'] - $discount),
							$product['product_taxclass_id']);

						foreach ($taxRates as $taxRate)
						{
							//Only update Tax Rate which has Percentate type
							if ($taxRate['tax_type'] == 'P')
							{
								$taxes[$taxRate['tax_rate_id']] -= $taxRate['amount'];
							}
						}
					}
				}
				$discountTotal += $discount;
			}

			$shippingMethod = $session->get('shipping_method');

			if ($couponData['coupon_shipping'] && is_array($shippingMethod))
			{
				if (!empty($shippingMethod['taxclass_id']))
				{
					$taxRates = $tax->getTaxRates($shippingMethod['cost'], $shippingMethod['taxclass_id']);

					foreach ($taxRates as $taxRate)
					{
						//Only update Tax Rate which has Percentate type
						if ($taxRate['tax_type'] == 'P')
						{
							$taxes[$taxRate['tax_rate_id']] -= $taxRate['amount'];
						}
					}
				}

				if ($couponData['coupon_type'] == 'F')
				{
					$discountTotal += $shippingMethod['cost'];
				}
				elseif ($couponData['coupon_type'] == 'P')
				{
					$discountTotal += $shippingMethod['cost'] / 100 * $couponData['coupon_value'];

				}
			}

			// If discount greater than total
			if ($discountTotal > $total)
			{
				$discountTotal = $total;
			}

			$totalData[] = array(
				'name'  => 'coupon',
				'title' => sprintf(JText::_('ESHOP_COUPON'), $session->get('coupon_code')),
				'text'  => $currency->format(-$discountTotal),
				'value' => -$discountTotal);
			$total -= $discountTotal;
		}
	}

	/**
	 *
	 * Function to get information for a specific coupon
	 *
	 * @param string $code
	 *
	 * @return array
	 */
	public function getCouponData($code)
	{
		$status = true;
		$db     = JFactory::getDbo();
		$query  = $db->getQuery(true);
		$query->select('*')
			->from('#__eshop_coupons')
			->where('coupon_code = ' . $db->quote($code))
			->where('(coupon_start_date = "0000-00-00 00:00:00" OR coupon_start_date < NOW())')
			->where('(coupon_end_date = "0000-00-00 00:00:00" OR coupon_end_date > NOW())')
			->where('published = 1');
		$db->setQuery($query);
		$coupon = $db->loadObject();
		if (is_object($coupon))
		{
			//Check coupon per customer
			if ($coupon->coupon_per_customer)
			{
				$user = JFactory::getUser();

				if (!$user->get('id'))
				{
					$status = false;
				}
				else
				{
					$query->clear()
						->select('COUNT(*)')
						->from('#__eshop_couponhistory')
						->where('coupon_id = ' . intval($coupon->id))
						->where('user_id = ' . intval($user->get('id')));
					$db->setQuery($query);

					if ($db->loadResult() >= $coupon->coupon_per_customer)
					{
						$status = false;
					}
				}
			}

			//Check min price condition
			$cart = new EshopCart();

			if ($coupon->coupon_min_total > $cart->getSubTotal())
			{
				$status = false;
			}

			//Check number of used times condition
			if ($coupon->coupon_times)
			{
				$query->clear()
					->select('COUNT(*)')
					->from('#__eshop_couponhistory')
					->where('coupon_id = ' . intval($coupon->id));
				$db->setQuery($query);

				if ($db->loadResult() >= $coupon->coupon_times)
				{
					$status = false;
				}
			}

			//Check total amount of used coupon condition
			if ($coupon->coupon_used > 0)
			{
				$query->clear()
					->select('ABS(SUM(amount))')
					->from('#__eshop_couponhistory')
					->where('coupon_id = ' . intval($coupon->id));
				$db->setQuery($query);

				if ($db->loadResult() >= $coupon->coupon_used)
				{
					$status = false;
				}
			}

			//Check coupon based on products
			$query->clear()
				->select('product_id')
				->from('#__eshop_couponproducts')
				->where('coupon_id = ' . intval($coupon->id));
			$db->setQuery($query);
			$couponProductsData = $db->loadColumn();

			if (count($couponProductsData))
			{
				$couponProduct = false;
				$cartData      = $cart->getCartData();

				foreach ($cartData as $product)
				{
					if (in_array($product['product_id'], $couponProductsData))
					{
						$couponProduct = true;
						break;
					}
				}

				if (!$couponProduct)
				{
					$status = false;
				}
			}

			//Check coupon based on categories
			$query->clear()
				->select('category_id')
				->from('#__eshop_couponcategories')
				->where('coupon_id = ' . intval($coupon->id));
			$db->setQuery($query);
			$couponCategoriesData     = $db->loadColumn();
			$tempCouponCategoriesData = $couponCategoriesData;


			for ($i = 0; $n = count($tempCouponCategoriesData), $i < $n; $i++)
			{
				$couponCategoriesData = array_merge($couponCategoriesData, EshopHelper::getAllChildCategories($tempCouponCategoriesData[$i]));
			}

			if (count($couponCategoriesData))
			{
				$cartData = $cart->getCartData();

				foreach ($cartData as $product)
				{
					$query->clear()
						->select('category_id')
						->from('#__eshop_productcategories')
						->where('product_id = ' . intval($product['product_id']));
					$db->setQuery($query);
					$productCategoryIds = $db->loadColumn();

					for ($i = 0; $n = count($productCategoryIds), $i < $n; $i++)
					{
						if (in_array($productCategoryIds[$i], $couponCategoriesData))
						{
							$couponProduct        = true;
							$couponProductsData[] = $product['product_id'];
							break;
						}
					}
				}

				if (empty($couponProduct))
				{
					$status = false;
				}
			}

			//Check coupon based on customer groups
			$query->clear()
				->select('customergroup_id')
				->from('#__eshop_couponcustomergroups')
				->where('coupon_id = ' . intval($coupon->id));
			$db->setQuery($query);
			$couponCustomerGroupsData = $db->loadColumn();

			if (count($couponCustomerGroupsData))
			{
				$couponCustomerGroup = false;
				$user                = JFactory::getUser();

				if ($user->get('id'))
				{
					$customer        = new EshopCustomer();
					$customerGroupId = $customer->getCustomerGroupId();
				}
				else
				{
					$customerGroupId = EshopHelper::getConfigValue('customergroup_id');
				}

				if (in_array($customerGroupId, $couponCustomerGroupsData))
				{
					$couponCustomerGroup = true;
				}

				if (!$couponCustomerGroup)
				{
					$status = false;
				}
			}

		}
		else
		{
			$status = false;
		}
		//Return
		if ($status)
		{
			return array(
				'coupon_id'            => $coupon->id,
				'coupon_name'          => $coupon->coupon_name,
				'coupon_code'          => $coupon->coupon_code,
				'coupon_type'          => $coupon->coupon_type,
				'coupon_value'         => $coupon->coupon_value,
				'coupon_min_total'     => $coupon->coupon_min_total,
				'coupon_start_date'    => $coupon->coupon_start_date,
				'coupon_end_date'      => $coupon->coupon_end_date,
				'coupon_shipping'      => $coupon->coupon_shipping,
				'coupon_times'         => $coupon->coupon_times,
				'coupon_used'          => $coupon->coupon_used,
				'coupon_products_data' => $couponProductsData);
		}
		else
		{
			return array();
		}
	}

	/**
	 *
	 * Function to add coupon history
	 *
	 * @param int   $couponId
	 * @param int   $orderId
	 * @param int   $userId
	 * @param float $amount
	 */
	public function addCouponHistory($couponId, $orderId, $userId, $amount)
	{
		$row               = JTable::getInstance('Eshop', 'Couponhistory');
		$row->id           = '';
		$row->coupon_id    = $couponId;
		$row->order_id     = $orderId;
		$row->user_id      = $userId;
		$row->amount       = $amount;
		$row->created_date = JFactory::getDate()->toSql();
		$row->store();
	}

	/**
	 *
	 * Function to get coupon information
	 *
	 * @param string $code
	 *
	 * @return stdClass coupon object
	 */
	function getCouponInfo($code)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')
			->from('#__eshop_coupons')
			->where('coupon_code = ' . $db->quote($code))
			->where('(coupon_start_date = "0000-00-00 00:00:00" OR coupon_start_date < NOW())')
			->where('(coupon_end_date = "0000-00-00 00:00:00" OR coupon_end_date > NOW())')
			->where('published = 1');
		$db->setQuery($query);

		return $db->loadObject();
	}
}
