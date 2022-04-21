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

class EshopWeight
{
	public $weights = array();

	/**
	 * Constructor function
	 */
	public function __construct()
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('a.*, b.weight_name, b.weight_unit')
			->from('#__eshop_weights AS a')
			->innerJoin('#__eshop_weightdetails AS b ON (a.id = b.weight_id)')
			->where('a.published = 1')
			->where('b.language = "' . JFactory::getLanguage()->getTag() . '"');
		$db->setQuery($query);
		$rows = $db->loadObjectList();

		foreach ($rows as $row)
		{
			$this->weights[$row->id] = array(
				'weight_id'       => $row->id,
				'weight_name'     => $row->weight_name,
				'weight_unit'     => $row->weight_unit,
				'exchanged_value' => $row->exchanged_value
			);
		}
	}

	/**
	 *
	 * Function to convert a number between weight unit
	 *
	 * @param float $number
	 * @param int   $weightFromId
	 * @param int   $weightToId
	 *
	 * @return float
	 */
	public function convert($number, $weightFromId, $weightToId)
	{
		if (!$weightToId)
		{
			$weightToId = 1;
		}

		if (!$weightFromId)
		{
			$weightFromId = $weightToId;
		}

		if ($weightFromId == $weightToId || !isset($this->weights[$weightFromId]) || !isset($this->weights[$weightToId]))
		{
			return $number;
		}

		$weightFrom = $this->weights[$weightFromId]['exchanged_value'];
		$weightTo   = $this->weights[$weightToId]['exchanged_value'];

		return $number * ($weightTo / $weightFrom);
	}

	/**
	 *
	 * Function to format a number based on weight
	 *
	 * @param float  $number
	 * @param int    $weightId
	 * @param string $decimalPoint
	 * @param string $thousandPoint
	 *
	 * @return float
	 */
	public function format($number, $weightId, $decimalPoint = '.', $thousandPoint = ',')
	{
		if (isset($this->weights[$weightId]))
		{
			return number_format($number, 2, $decimalPoint, $thousandPoint) . $this->weights[$weightId]['weight_unit'];
		}

		return number_format($number, 2, $decimalPoint, $thousandPoint);
	}

	/**
	 *
	 * Function to get unit of a specific weight
	 *
	 * @param int $weightId
	 *
	 * @return string
	 */
	public function getUnit($weightId)
	{
		if (isset($this->weights[$weightId]))
		{
			return $this->weights[$weightId]['weight_unit'];
		}

		return '';
	}
}