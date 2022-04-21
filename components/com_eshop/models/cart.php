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
defined('_JEXEC') or die;

class EShopModelCart extends EShopModel
{
	/**
	 * Entity data
	 *
	 * @var array
	 */
	protected $cartData = null;

	/**
	 *
	 * Total Data object array, each element is an price price in the cart
	 * @var object array
	 */
	protected $totalData = null;

	/**
	 *
	 * Final total price of the cart
	 * @var float
	 */
	protected $total = null;

	/**
	 *
	 * Taxes of all elements in the cart
	 * @var array
	 */
	protected $taxes = null;

	public function __construct($config = array())
	{
		parent::__construct();

		$this->cartData  = null;
		$this->totalData = null;
		$this->total     = null;
		$this->taxes     = null;
	}

	/**
	 *
	 * Function to get Cart Data
	 */
	public function getCartData()
	{
		$cart = new EshopCart();

		if (!$this->cartData)
		{
			$this->cartData = $cart->getCartData();
		}

		return $this->cartData;
	}

	/**
	 *
	 * Function to get Costs
	 */
	public function getCosts()
	{
		$totalData = array();
		$total     = 0;
		$taxes     = array();
		$this->getSubTotalCosts($totalData, $total, $taxes);
		$this->getDiscountCosts($totalData, $total, $taxes);
		$this->getShippingCosts($totalData, $total, $taxes);
		$this->getCouponCosts($totalData, $total, $taxes);
		$this->getTaxesCosts($totalData, $total, $taxes);
		$this->getVoucherCosts($totalData, $total, $taxes);
		$this->getTotalCosts($totalData, $total, $taxes);
		$this->totalData = $totalData;
		$this->total     = $total;
		$this->taxes     = $taxes;
	}

	/**
	 *
	 * Function to get Sub Total Costs
	 *
	 * @param  array $totalData
	 * @param  float $total
	 * @param  array $taxes
	 */
	public function getSubTotalCosts(&$totalData, &$total, &$taxes)
	{
		$cart        = new EshopCart();
		$currency    = new EshopCurrency();
		$total       = $cart->getSubTotal();
		$totalData[] = array(
			'name'  => 'sub_total',
			'title' => JText::_('ESHOP_SUB_TOTAL'),
			'text'  => $currency->format(max(0, $total)),
			'value' => max(0, $total)
		);
		$taxes       = $cart->getTaxes();
	}

	/**
	 *
	 * Function to get Discount Costs
	 *
	 * @param  array $totalData
	 * @param  float $total
	 * @param  array $taxes
	 */
	public function getDiscountCosts(&$totalData, &$total, &$taxes)
	{
		$discount = new EshopDiscount();
		$discount->getCosts($totalData, $total, $taxes);
	}

	/**
	 *
	 * Function to get Coupon Costs
	 *
	 * @param  array $totalData
	 * @param  float $total
	 * @param  array $taxes
	 */
	public function getCouponCosts(&$totalData, &$total, &$taxes)
	{
		$coupon = new EshopCoupon();
		$coupon->getCosts($totalData, $total, $taxes);
	}

	/**
	 *
	 * Function to get Voucher Costs
	 *
	 * @param  array $totalData
	 * @param  float $total
	 * @param  array $taxes
	 */
	public function getVoucherCosts(&$totalData, &$total, &$taxes)
	{
		$voucher = new EshopVoucher();
		$voucher->getCosts($totalData, $total, $taxes);
	}

	/**
	 *
	 * Function to get Shipping Costs
	 *
	 * @param  array $totalData
	 * @param  float $total
	 * @param  array $taxes
	 */
	public function getShippingCosts(&$totalData, &$total, &$taxes)
	{
		$shipping = new EshopShipping();
		$shipping->getCosts($totalData, $total, $taxes);
	}

	/**
	 *
	 * Function to get Taxes Costs
	 *
	 * @param  array $totalData
	 * @param  float $total
	 * @param  array $taxes
	 */
	public function getTaxesCosts(&$totalData, &$total, &$taxes)
	{
		$tax = new EshopTax(EshopHelper::getConfig());
		$tax->getCosts($totalData, $total, $taxes);
	}

	/**
	 *
	 * Function to get Total Costs
	 *
	 * @param  array $totalData
	 * @param  float $total
	 * @param  array $taxes
	 */
	public function getTotalCosts(&$totalData, &$total, &$taxes)
	{
		$currency    = new EshopCurrency();
		$totalData[] = array(
			'name'  => 'total',
			'title' => JText::_('ESHOP_TOTAL'),
			'text'  => $currency->format(max(0, $total)),
			'value' => max(0, $total)
		);
	}

	/**
	 *
	 * Function to get Total Data
	 */
	public function getTotalData()
	{
		return $this->totalData;
	}

	/**
	 *
	 * Function to get Total
	 */
	public function getTotal()
	{
		return $this->total;
	}

	/**
	 *
	 * Function to get Taxes
	 */
	public function getTaxes()
	{
		return $this->taxes;
	}
}