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
defined('_JEXEC') or die();
require_once dirname(__FILE__) . '/products.php';

class EShopModelSearch extends EShopModelProducts
{

	protected function _buildQueryWhere(JDatabaseQuery $query)
	{
		parent::_buildQueryWhere($query);
		$state = $this->getState();
		$db = $this->getDbo();
		$subQuery = $db->getQuery(true);
		// Filter by categories
		if ($state->category_ids)
		{
			$categoryIdsArr = explode(',', $state->category_ids);
			JArrayHelper::toInteger($categoryIdsArr);
			if (EshopHelper::getConfigValue('show_products_in_all_levels'))
			{
				$tempCategoryIdsArr = array();
				foreach ($categoryIdsArr as $categoryId)
				{
					$tempCategoryIdsArr = array_merge($tempCategoryIdsArr, array($categoryId), EshopHelper::getAllChildCategories($categoryId));
				}
				$categoryIdsArr = $tempCategoryIdsArr;
			}
			$subQuery->clear();
			$subQuery->select('pc.product_id FROM #__eshop_productcategories AS pc WHERE pc.category_id IN (' . implode(',', $categoryIdsArr) . ')');
			$query->where('a.id IN (' . (string) $subQuery . ')');
		}
		// Filter by manufacturers
		if ($state->manufacturer_ids)
		{
			$manufacturerIdsArr = explode(',', $state->manufacturer_ids);
			JArrayHelper::toInteger($manufacturerIdsArr);
			$query->where('a.manufacturer_id IN (' . implode(',', $manufacturerIdsArr) . ')');
		}
		// Filter by price
		if ($state->min_price)
		{
			$query->where('a.product_price >= ' . $state->min_price);
		}
		if ($state->max_price)
		{
			$query->where('a.product_price <= ' . $state->max_price);
		}
		// Filter by weight
		$minWeight = $state->min_weight;
		$maxWeight = $state->max_weight;
		if ($state->same_weight_unit)
		{
			if ($minWeight)
			{
				$query->where('a.product_weight >= ' . $minWeight);
			}
			if ($maxWeight)
			{
				$query->where('a.product_weight <= ' . $maxWeight);
			}
		}
		else
		{
			$eshopWeight = new EshopWeight();
			$weightIds = EshopHelper::getWeightIds();
			$defaultWeightId = EshopHelper::getConfigValue('weight_id');
			if ($minWeight || $maxWeight)
			{
				$minWeightQuery = array();
				$maxWeightQuery = array();
				foreach ($weightIds as $weightId)
				{
					if ($minWeight)
					{
						$minWeightQuery[] = '(a.product_weight_id = ' . $weightId . ' AND a.product_weight >= ' . $eshopWeight->convert($minWeight, $defaultWeightId, $weightId) . ')';
					}
					if ($maxWeight)
					{
						$maxWeightQuery[] = '(a.product_weight_id = ' . $weightId . ' AND a.product_weight <= ' . $eshopWeight->convert($maxWeight, $defaultWeightId, $weightId) . ')';
					}
				}
				if (count($minWeightQuery))
				{
					$query->where('(' . implode(' OR ', $minWeightQuery) . ')');
				}
				if (count($maxWeightQuery))
				{
					$query->where('(' . implode(' OR ', $maxWeightQuery) . ')');
				}
			}
		}
		// Filter by length
		$minLength = $state->min_length;
		$maxLength = $state->max_length;
		if ($state->same_length_unit)
		{
			if ($minLength)
			{
				$query->where('a.product_length >= ' . $minLength);
			}
			if ($maxLength)
			{
				$query->where('a.product_length <= ' . $maxLength);
			}
		}
		else
		{
			$eshopLength = new EshopLength();
			$lengthIds = EshopHelper::getLengthIds();
			$defaultLengthId = EshopHelper::getConfigValue('length_id');
			if ($minLength || $maxLength)
			{
				$minLengthQuery = array();
				$maxLengthQuery = array();
				foreach ($lengthIds as $lengthId)
				{
					if ($minLength)
					{
						$minLengthQuery[] = '(a.product_length_id = ' . $lengthId . ' AND a.product_length >= ' . $eshopLength->convert($minLength, $defaultLengthId, $lengthId) . ')';
					}
					if ($maxLength)
					{
						$maxLengthQuery[] = '(a.product_length_id = ' . $lengthId . ' AND a.product_length <= ' . $eshopLength->convert($maxLength, $defaultLengthId, $lengthId) . ')';
					}
				}
				if (count($minLengthQuery))
				{
					$query->where('(' . implode(' OR ', $minLengthQuery) . ')');
				}
				if (count($maxLengthQuery))
				{
					$query->where('(' . implode(' OR ', $maxLengthQuery) . ')');
				}
			}
		}
		// Filter by width
		$minWidth = $state->min_width;
		$maxWidth = $state->max_width;
		if ($state->same_length_unit)
		{
			if ($minWidth)
			{
				$query->where('a.product_width >= ' . $minWidth);
			}
			if ($maxWidth)
			{
				$query->where('a.product_width <= ' . $maxWidth);
			}
		}
		else
		{
			$eshopLength = new EshopLength();
			$lengthIds = EshopHelper::getLengthIds();
			$defaultLengthId = EshopHelper::getConfigValue('length_id');
			if ($minWidth || $maxWidth)
			{
				$minWidthQuery = array();
				$maxWidthQuery = array();
				foreach ($lengthIds as $lengthId)
				{
					if ($minWidth)
					{
						$minWidthQuery[] = '(a.product_length_id = ' . $lengthId . ' AND a.product_width >= ' . $eshopLength->convert($minWidth, $defaultLengthId, $lengthId) . ')';
					}
					if ($maxWidth)
					{
						$maxWidthQuery[] = '(a.product_length_id = ' . $lengthId . ' AND a.product_width <= ' . $eshopLength->convert($maxWidth, $defaultLengthId, $lengthId) . ')';
					}
				}
				if (count($minWidthQuery))
				{
					$query->where('(' . implode(' OR ', $minWidthQuery) . ')');
				}
				if (count($maxWidthQuery))
				{
					$query->where('(' . implode(' OR ', $maxWidthQuery) . ')');
				}
			}
		}
		// Filter by height
		$minHeight = $state->min_height;
		$maxHeight = $state->max_height;
		if ($state->same_length_unit)
		{
			if ($minHeight)
			{
				$query->where('a.product_height >= ' . $minHeight);
			}
			if ($maxHeight)
			{
				$query->where('a.product_height <= ' . $maxHeight);
			}
		}
		else
		{
			$eshopLength = new EshopLength();
			$lengthIds = EshopHelper::getLengthIds();
			$defaultLengthId = EshopHelper::getConfigValue('length_id');
			if ($minHeight || $maxHeight)
			{
				$minHeightQuery = array();
				$maxHeightQuery = array();
				foreach ($lengthIds as $lengthId)
				{
					if ($minHeight)
					{
						$minHeightQuery[] = '(a.product_length_id = ' . $lengthId . ' AND a.product_height >= ' . $eshopLength->convert($minHeight, $defaultLengthId, $lengthId) . ')';
					}
					if ($maxHeight)
					{
						$maxHeightQuery[] = '(a.product_length_id = ' . $lengthId . ' AND a.product_height <= ' . $eshopLength->convert($maxHeight, $defaultLengthId, $lengthId) . ')';
					}
				}
				if (count($minHeightQuery))
				{
					$query->where('(' . implode(' OR ', $minHeightQuery) . ')');
				}
				if (count($maxHeightQuery))
				{
					$query->where('(' . implode(' OR ', $maxHeightQuery) . ')');
				}
			}
		}
		// Filter by stock
		if ($state->product_in_stock == 1)
		{
			$query->where('a.product_quantity > 0');
		}
		elseif ($state->product_in_stock == -1)
		{
			$query->where('a.product_quantity <= 0');
		}
		//Filter by attributes
		if ($state->attribute_ids)
		{
			$attributeIdsArr = explode(',', $state->attribute_ids);
			JArrayHelper::toInteger($attributeIdsArr);
			$subQuery->clear();
			$subQuery->select('pa.product_id FROM #__eshop_productattributes AS pa WHERE pa.attribute_id IN (' . implode(',', $attributeIdsArr) . ')');
			$query->where('a.id IN (' . (string) $subQuery . ')');
		}
		//Filter by options
		if ($state->optionvalue_ids)
		{
			$optionvalueIdsArr = explode(',', $state->optionvalue_ids);
			JArrayHelper::toInteger($optionvalueIdsArr);
			$subQuery->clear();
			$subQuery->select('po.product_id FROM #__eshop_productoptionvalues AS po WHERE po.option_value_id IN (' . implode(',', $optionvalueIdsArr) . ')');
			$query->where('a.id IN (' . (string) $subQuery . ')');
		}
		// Filter by keyword
		if ($state->keyword)
		{
            $query->where ('(MATCH( b.`product_name`) AGAINST(' . $db->quote($state->keyword) . ') OR ( a.product_sku LIKE ' . $db->quote('%' . trim($state->keyword) . '%') . '))');
            $query->order('MATCH( b.`product_name`) AGAINST(' . $db->quote($state->keyword) . ') DESC');
			/*$keywordArr = explode(' ', $state->keyword);
			foreach ($keywordArr as $keyword)
			{
				$keyword = $db->quote('%' . trim($keyword) . '%');
				$searchKeywordArr = array();
				$searchKeywordArr[] = 'a.product_sku LIKE ' . $keyword;
				$searchKeywordArr[] = 'b.product_name LIKE ' . $keyword;*/
				/*$searchKeywordArr[] = 'b.product_short_desc LIKE ' . $keyword;
				$searchKeywordArr[] = 'b.product_desc LIKE ' . $keyword;
				$searchKeywordArr[] = 'b.tab1_title LIKE ' . $keyword;
				$searchKeywordArr[] = 'b.tab1_content LIKE ' . $keyword;
				$searchKeywordArr[] = 'b.tab2_title LIKE ' . $keyword;
				$searchKeywordArr[] = 'b.tab2_content LIKE ' . $keyword;
				$searchKeywordArr[] = 'b.tab3_title LIKE ' . $keyword;
				$searchKeywordArr[] = 'b.tab3_content LIKE ' . $keyword;
				$searchKeywordArr[] = 'b.tab4_title LIKE ' . $keyword;
				$searchKeywordArr[] = 'b.tab4_content LIKE ' . $keyword;
				$searchKeywordArr[] = 'b.tab5_title LIKE ' . $keyword;
				$searchKeywordArr[] = 'b.tab5_content LIKE ' . $keyword;
				$searchKeywordArr[] = 'a.id IN (SELECT product_id FROM #__eshop_producttags WHERE tag_id IN (SELECT id FROM #__eshop_tags WHERE tag_name LIKE ' . $keyword . '))';*/
				/*$subQuery->clear();
				$subQuery->select('po.product_id FROM #__eshop_productoptionvalues AS po WHERE po.sku LIKE ' . $keyword);
				$searchKeywordArr[] = 'a.id IN (' . (string) $subQuery . ')';*/
				/*$query->where('(' . implode(' OR ', $searchKeywordArr) . ')');
			}*/
		}
		//Check viewable of customer groups
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
		$query->where('((a.product_customergroups = "") OR (a.product_customergroups IS NULL) OR (a.product_customergroups = "' . $customerGroupId . '") OR (a.product_customergroups LIKE "' . $customerGroupId . ',%") OR (a.product_customergroups LIKE "%,' . $customerGroupId . ',%") OR (a.product_customergroups LIKE "%,' . $customerGroupId . '"))');

		$currentDate = $this->getDbo()->quote(EshopHelper::getServerTimeFromGMTTime());
		$query->where('(a.product_available_date = "0000-00-00 00:00:00" OR a.product_available_date <= ' . $currentDate . ')');

		$langCode = JFactory::getLanguage()->getTag();
		$query->where('((a.product_languages = "") OR (a.product_languages IS NULL) OR (a.product_languages = "' . $langCode . '") OR (a.product_languages LIKE "' . $langCode . ',%") OR (a.product_languages LIKE "%,' . $langCode . ',%") OR (a.product_languages LIKE "%,' . $langCode . '"))');

		return $this;
	}
}
