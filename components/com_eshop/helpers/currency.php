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

class EshopCurrency
{
	/**
	 *
	 * @var string
	 */
	protected $currencyCode;
	/**
	 *
	 * @var array
	 */
	protected $currencies;

	/**
	 * Constructor function
	 */
	public function __construct()
	{
		$session = JFactory::getSession();

		if ($session->get('currency_code') != '')
		{
			$this->currencyCode = $session->get('currency_code');
		}
		elseif ($cookieCurrencyCode = JFactory::getApplication()->input->cookie->getString('currency_code', ''))
		{
			$this->currencyCode = $cookieCurrencyCode;
		}
		else
		{
			$this->currencyCode = EshopHelper::getConfigValue('default_currency_code');
		}

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')
			->from('#__eshop_currencies')
			->where('published = 1');
		$db->setQuery($query);
		$rows = $db->loadObjectList();

		foreach ($rows as $row)
		{
			$this->currencies[$row->currency_code] = array(
				'currency_id'         => $row->id,
				'currency_name'       => $row->currency_name,
				'left_symbol'         => $row->left_symbol,
				'right_symbol'        => $row->right_symbol,
				'decimal_symbol'      => $row->decimal_symbol,
				'decimal_place'       => $row->decimal_place,
				'thousands_separator' => $row->thousands_separator,
				'exchanged_value'     => $row->exchanged_value
			);
		}
	}

	/**
	 *
	 * @param float  $number
	 * @param string $currencyCode
	 *
	 * @return string formatted number
	 */
	public function format($number, $currencyCode = '', $exchangedValue = '', $format = true, $decimalSymbol = '', $thousandsSeparator = '')
	{
		if ($currencyCode != '' && isset($this->currencies[$currencyCode]))
		{
			$code = $currencyCode;
		}
		else
		{
			$code = $this->currencyCode;
		}

		// Calculate when doing exchange between currency
		if (!$exchangedValue)
		{
			$exchangedValue = $this->currencies[$code]['exchanged_value'];
		}

		if ($exchangedValue)
		{
			$number = (float) $number * $exchangedValue;
		}

		$sign = '';

		if ($number < 0)
		{
			$sign   = '-';
			$number = abs($number);
		}

		$numberString = $sign;

		if ($format)
		{
			$numberString .= $this->currencies[$code]['left_symbol'];
		}

		if ($decimalSymbol == '')
		{
			$decimalSymbol = $this->currencies[$code]['decimal_symbol'];
		}

		if ($thousandsSeparator == '')
		{
			$thousandsSeparator = $this->currencies[$code]['thousands_separator'];
		}

		$numberString .= number_format(round($number, (int) $this->currencies[$code]['decimal_place']), (int) $this->currencies[$code]['decimal_place'], $decimalSymbol, $thousandsSeparator);

		if ($format)
		{
			$numberString .= $this->currencies[$code]['right_symbol'];
		}

		return $numberString;
	}

	/**
	 *
	 * @param float  $number
	 * @param string $currencyFromCode
	 * @param strong $currencyToCode
	 *
	 * @return float converted number
	 */
	public function convert($number, $currencyFromCode, $currencyToCode)
	{
		if (isset($this->currencies[$currencyFromCode]))
		{
			$currencyFromExchangedValue = $this->currencies[$currencyFromCode]['exchanged_value'];
		}
		else
		{
			$currencyFromExchangedValue = 1;
		}

		if (isset($this->currencies[$currencyToCode]))
		{
			$currencyToExchangedValue = $this->currencies[$currencyToCode]['exchanged_value'];
		}
		else
		{
			$currencyToExchangedValue = 1;
		}

		return $number * ($currencyToExchangedValue / $currencyFromExchangedValue);
	}

	/**
	 *
	 * Function to get ID for a specific currency code
	 *
	 * @param string $currencyCode
	 *
	 * @return int
	 */
	public function getCurrencyId($currencyCode = '')
	{
		if (!$currencyCode)
		{
			return $this->currencies[$this->currencyCode]['currency_id'];
		}
		elseif ($currencyCode && isset($this->currencies[$currencyCode]))
		{
			return $this->currencies[$currencyCode]['currency_id'];
		}
		else
		{
			return 0;
		}
	}

	/**
	 *
	 * Function to get currency code
	 * @return string
	 */
	public function getCurrencyCode()
	{
		return $this->currencyCode;
	}

	/**
	 *
	 * Function to get exchanged value for a specific currency code
	 *
	 * @param string $currencyCode
	 *
	 * @return float
	 */
	public function getExchangedValue($currencyCode = '')
	{
		if (!$currencyCode)
		{
			return $this->currencies[$this->currencyCode]['exchanged_value'];
		}
		elseif ($currencyCode && isset($this->currencies[$currencyCode]))
		{
			return $this->currencies[$currencyCode]['exchanged_value'];
		}
		else
		{
			return 1;
		}
	}
}