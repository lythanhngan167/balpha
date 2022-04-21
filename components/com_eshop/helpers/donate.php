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

class EshopDonate
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
		$currency     = new EshopCurrency();
		$session      = JFactory::getSession();
		$donateAmount = $session->get('donate_amount');

		if ($donateAmount > 0)
		{
			$totalData[] = array(
				'name'  => 'donate_amount',
				'title' => JText::_('ESHOP_DONATE_AMOUNT'),
				'text'  => $currency->format($donateAmount),
				'value' => $donateAmount);

			$total += $donateAmount;
		}
	}
}