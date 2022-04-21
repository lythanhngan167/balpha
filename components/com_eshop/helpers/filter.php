<?php
/**
 * @version        3.1.0
 * @package        Joomla
 * @subpackage     EShop
 * @author         Giang Dinh Truong
 * @copyright      Copyright (C) 2013 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
use Joomla\Utilities\ArrayHelper;
use Joomla\Registry\Registry;

// no direct access
defined('_JEXEC') or die;

class EshopFilter
{
	/**
	 * Get filter data from application request
	 *
	 * @return array
	 */
	public static function getFilterData()
	{
		static $filterData;

		if ($filterData === null)
		{
			$input      = JFactory::getApplication()->input;
			$filterData = array();

			$option = $input->getCmd('option');
			$view   = $input->getCmd('view');

			if ($option == 'com_eshop' && $view == 'category')
			{
				$categoryId = $input->getInt('id', 0);
			}
			elseif ($option == 'com_eshop' && $view == 'product')
			{
				$categoryId = $input->getInt('catid', 0);
			}
			else
			{
				$categoryId = $input->getInt('category_id', 0);
			}

			if ($categoryId > 0)
			{
				$filterData['category_id'] = $categoryId;
			}

			$manufacturerIds = $input->get('manufacturer_ids', array(), 'array');
			
			if (count($manufacturerIds))
			{
				$manufacturerIds                = ArrayHelper::toInteger($manufacturerIds);
				$filterData['manufacturer_ids'] = $manufacturerIds;
			}

			$attributes = EshopFilter::getAttributes();

			foreach ($attributes as $attribute)
			{
				$attributeInputName = 'attribute_' . $attribute->id;

				$attributeValues = $input->get($attributeInputName, array(), 'array');

				if (count($attributeValues) > 0)
				{
					$filterData[$attributeInputName] = $attributeValues;
				}
			}

			$options = EshopFilter::getOptions();

			foreach ($options as $option)
			{
				$optionInputName = 'option_' . $option->id;
				$optionValues    = $input->get($optionInputName, array(), 'array');

				if (count($optionValues) > 0)
				{
					$filterData[$optionInputName] = $optionValues;
				}

			}

			if ($input->get('filter_by_price'))
			{
				$minPrice			= $input->get('min_price', 0, 'float');
				$minPriceDefault	= $input->get('min_price_default', 0, 'float');
				$maxPrice			= $input->get('max_price', 0, 'float');
				$maxPriceDefault	= $input->get('max_price_default', 0, 'float');
				
				if ($minPrice > $minPriceDefault || $maxPrice < $maxPriceDefault)
				{
					$filterData['filter_by_price'] = true;
					if ($minPrice > $minPriceDefault)
					{
						$filterData['min_price'] = $minPrice;
					}
					if ($maxPrice < $maxPriceDefault)
					{
						$filterData['max_price'] = $maxPrice;
					}
				}
			}

			if ($input->get('filter_by_weight'))
			{
				$minWeight			= $input->get('min_weight', 0, 'float');
				$minWeightDefault	= $input->get('min_weight_default', 0, 'float');
				$maxWeight			= $input->get('max_weight', 0, 'float');
				$maxWeightDefault	= $input->get('max_weight_default', 0, 'float');
				
				if ($minWeight > $minWeightDefault || $maxWeight < $maxWeightDefault)
				{
					$filterData['filter_by_weight'] = true;
					if ($minWeight > $minWeightDefault)
					{
						$filterData['min_weight'] = $minWeight;
					}
					if ($maxWeight < $maxWeightDefault)
					{
						$filterData['max_weight'] = $maxWeight;
					}
					$filterData['same_weight_unit']	= $input->get('same_weight_unit', 1, 'int');
				}
			}

			if ($input->get('filter_by_length'))
			{
				$minLength			= $input->get('min_length', 0, 'float');
				$minLengthDefault	= $input->get('min_length_default', 0, 'float');
				$maxLength			= $input->get('max_length', 0, 'float');
				$maxLengthDefault	= $input->get('max_length_default', 0, 'float');
				
				if ($minLength > $minLengthDefault || $maxLength < $maxLengthDefault)
				{
					$filterData['filter_by_length'] = true;
					if ($minLength > $minLengthDefault)
					{
						$filterData['min_length'] = $minLength;
					}
					if ($maxLength < $maxLengthDefault)
					{
						$filterData['max_length'] = $maxLength;
					}
				}
			}

			if ($input->get('filter_by_width'))
			{
				$minWidth			= $input->get('min_width', 0, 'float');
				$minWidthDefault	= $input->get('min_width_default', 0, 'float');
				$maxWidth			= $input->get('max_width', 0, 'float');
				$maxWidthDefault	= $input->get('max_width_default', 0, 'float');

				if ($minWidth > $minWidthDefault || $maxWidth < $maxWidthDefault)
				{
					$filterData['filter_by_width'] = true;
					if ($minWidth > $minWidthDefault)
					{
						$filterData['min_width'] = $minWidth;
					}
					if ($maxWidth < $maxWidthDefault)
					{
						$filterData['max_width'] = $maxWidth;
					}
				}
			}

			if ($input->get('filter_by_height'))
			{
				$minHeight			= $input->get('min_height', 0, 'float');
				$minHeightDefault	= $input->get('min_height_default', 0, 'float');
				$maxHeight			= $input->get('max_height', 0, 'float');
				$maxHeightDefault	= $input->get('max_height_default', 0, 'float');
				
				if ($minHeight > $minHeightDefault || $maxHeight < $maxHeightDefault)
				{
					$filterData['filter_by_height'] = true;
					if ($minHeight > $minHeightDefault)
					{
						$filterData['min_height'] = $minHeight;
					}
					if ($maxHeight < $maxHeightDefault)
					{
						$filterData['max_height'] = $maxHeight;
					}
				}
			}

			if ($input->get('filter_by_length') || $input->get('filter_by_width') || $input->get('filter_by_height'))
			{
				$filterData['same_length_unit']		= $input->get('same_length_unit', 1, 'int');
			}
			
			$productInStock 					= $input->getInt('product_in_stock', 0, 'int');
			
			if ($productInStock != 0)
			{
				$filterData['product_in_stock']		= $productInStock;
			}
			
			$keyword         					= $input->getString('keyword');
			
			if (!empty($keyword))
			{
				$filterData['keyword'] = $keyword;
			}

			$filterData['change_filter']		= $input->getString('change_filter');

			// Build the query which return the products
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('a.id')
				->from('#__eshop_products AS a')
				->where('a.published = 1');

			// Apply filters
			static::applyFilters($query, $filterData);
			$db->setQuery($query);

			$filterData['product_ids'] = $db->loadColumn();
		}

		return $filterData;
	}

	/**
	 * Function to get list of categories
	 *
	 * @param array $filterData
	 *
	 * @return array
	 */
	public static function getCategories($filterData = array())
	{
		if (empty($filterData['product_ids']))
		{
			return array();
		}

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('a.id, a.category_parent_id, b.category_name')
			->from('#__eshop_categories AS a')
			->innerJoin('#__eshop_categorydetails AS b ON a.id = b.category_id')
			->where('a.published = 1')
			->where('b.language = ' . $db->quote(JFactory::getLanguage()->getTag()))
			->order('a.ordering');
		
		//Check viewable of customer groups for category
		$user = JFactory::getUser();
		
		if ($user->get('id'))
		{
			$customer        = new EshopCustomer();
			$customerGroupId = $customer->getCustomerGroupId();
		}
		else
		{
			$customerGroupId = EshopHelper::getConfigValue('customergroup_id');
		}
		
		if (!$customerGroupId)
		{
			$customerGroupId = 0;
		}
		
		$query->where('((a.category_customergroups = "") OR (a.category_customergroups IS NULL) OR (a.category_customergroups = "' . $customerGroupId . '") OR (a.category_customergroups LIKE "' . $customerGroupId . ',%") OR (a.category_customergroups LIKE "%,' . $customerGroupId . ',%") OR (a.category_customergroups LIKE "%,' . $customerGroupId . '"))');

		if (empty($filterData['category_id']))
		{
			$parentId = 0;
		}
		else
		{
			$parentId = $filterData['category_id'];
		}

		$query->where('a.category_parent_id = ' . $parentId);

		$db->setQuery($query);

		$rows = $db->loadObjectList();

		// first pass - collect children
		if (count($rows))
		{
			$countFromAllLevels = EshopHelper::getConfigValue('show_products_in_all_levels');

			$query->clear()
				->select('COUNT(*)')
				->from('#__eshop_productcategories');

			foreach ($rows as $row)
			{
				if ($countFromAllLevels)
				{
					$categoryIds = array_merge(array($row->id), EshopHelper::getAllChildCategories($row->id));
				}
				else
				{
					$categoryIds = array($row->id);
				}

				$query->where('category_id IN (' . implode(',', $categoryIds) . ')')
					->where('product_id IN (' . implode(',', $filterData['product_ids']) . ')');
				$db->setQuery($query);
				$row->number_products = $db->loadResult();

				$query->clear('where');
			}
		}



		return $rows;
	}

	/**
	 *  Function to get list of manufacturers
	 *
	 * @param array $filterData
	 *
	 * @return array manufacturers list
	 */
	public static function getManufacturers($filterData = array())
	{
		if (empty($filterData['product_ids']))
		{
			return array();
		}

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('mn.id, b.manufacturer_id, b.manufacturer_name, COUNT(a.id) AS number_products')
			->from('#__eshop_manufacturers AS mn')
			->innerJoin('#__eshop_manufacturerdetails AS b ON (mn.id = b.manufacturer_id)')
			->innerJoin('#__eshop_products AS a ON mn.id = a.manufacturer_id')
			->where('mn.published = 1')
			->where('a.published = 1')
			->where('b.language = ' . $db->quote(JFactory::getLanguage()->getTag()))
			->group('mn.id');

		if ($filterData['change_filter'] != 'manufacturer')
		{
			$query->where('a.id IN (' . implode(',', $filterData['product_ids']) . ')');
		}
		else
		{
			// Apply filters
			static::applyFilters($query, $filterData, 'Manufacturer');
		}


		//Check viewable of customer groups
		$user = JFactory::getUser();

		if ($user->get('id'))
		{
			$customer        = new EshopCustomer();
			$customerGroupId = $customer->getCustomerGroupId();
		}
		else
		{
			$customerGroupId = EshopHelper::getConfigValue('customergroup_id');
		}

		if (!$customerGroupId)
		{
			$customerGroupId = 0;
		}

		$query->where('((mn.manufacturer_customergroups = "") OR (mn.manufacturer_customergroups IS NULL) OR (mn.manufacturer_customergroups = "' . $customerGroupId . '") OR (mn.manufacturer_customergroups LIKE "' . $customerGroupId . ',%") OR (mn.manufacturer_customergroups LIKE "%,' . $customerGroupId . ',%") OR (mn.manufacturer_customergroups LIKE "%,' . $customerGroupId . '"))');

		$db->setQuery($query);

		return $db->loadObjectList();
	}

	/**
	 *
	 * Function to get attributes
	 *
	 * @param array $filterData
	 * @param bool  $getAttributeValues
	 *
	 * @return array object list
	 */
	public static function getAttributes($filterData = array(), $getAttributeValues = false)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('at.id, ad.attribute_name')
			->from('#__eshop_attributes AS at')
			->innerJoin('#__eshop_attributedetails AS ad ON at.id = ad.attribute_id')
			->innerJoin('#__eshop_productattributes AS pa ON at.id = pa.attribute_id')
			->where('at.published = 1')
			->where('pa.published = 1')
			->where('ad.language = ' . $db->quote(JFactory::getLanguage()->getTag()))
			->group('at.id')
			->order('at.ordering');

		$filterAttributes = self::getFilterParam('filter_attributes');
		
		if (count($filterAttributes))
		{
			$query->where('at.id IN(' . implode(',', $filterAttributes) . ')');
		}
		
		$db->setQuery($query);

		$attributes = $db->loadObjectList();

		if ($getAttributeValues)
		{
			$subQuery = $db->getQuery(true);
			$subQuery->select('a.id')
				->from('#__eshop_products AS a')
				->where('published = 1');

			// Apply filters
			static::applyFilters($subQuery, $filterData, 'Attribute');

			foreach ($attributes as $attribute)
			{

				if (empty($filterData['product_ids']))
				{
					$attribute->attributeValues = array();
					continue;
				}

				$query->clear()
					->select('pad.value, COUNT(pad.id) AS number_products')
					->from('#__eshop_productattributes AS pa')
					->innerJoin('#__eshop_productattributedetails AS pad ON pa.id = pad.productattribute_id')
					->where('pa.attribute_id = ' . $attribute->id)
					->where('pad.language = ' . $db->quote(JFactory::getLanguage()->getTag()))
					->group('pad.value');

				if ($filterData['change_filter'] != 'attribute_' . $attribute->id)
				{

					$query->where('pa.product_id  IN (' . implode(',', $filterData['product_ids']) . ')');
				}
				else
				{
					$atQuery = clone $subQuery;

					// Add attribute filter
					static::applyAttributeFilter($atQuery, $filterData, $attribute->id);

					$query->where('pa.product_id  IN (' . (string) $atQuery . ')');
				}

				$db->setQuery($query);
				$attribute->attributeValues = $db->loadObjectList();
			}
		}

		return $attributes;
	}

	/**
	 * Function to get options
	 *
	 * @param array $filterData
	 *
	 * @return array object list
	 */
	public static function getOptions($filterData = array(), $getOptionValues = false)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('o.id, od.option_name')
			->from('#__eshop_options AS o')
			->innerJoin('#__eshop_optiondetails AS od ON o.id = od.option_id')
			->innerJoin('#__eshop_productoptions AS po ON o.id = po.option_id')
			->where('o.published = 1')
			->where('od.language = ' . $db->quote(JFactory::getLanguage()->getTag()))
			->group('o.id')
			->order('o.ordering');
		
		$filterOptions = self::getFilterParam('filter_options');
		
		if (count($filterOptions))
		{
			$query->where('o.id IN (' . implode(',', $filterOptions) . ')');
		}
		
		$db->setQuery($query);
		$options = $db->loadObjectList();

		if ($getOptionValues)
		{
			$subQuery = $db->getQuery(true);
			$subQuery->select('a.id')
				->from('#__eshop_products AS a')
				->where('published = 1');

			// Apply filters
			static::applyFilters($subQuery, $filterData, 'Option');

			foreach ($options as $option)
			{

				if (empty($filterData['product_ids']))
				{
					$option->optionValues = array();
					continue;
				}

				$query->clear()
					->select('ovd.value AS value, pov.option_value_id AS id, COUNT(pov.id) AS number_products')
					->from('#__eshop_productoptions AS po')
					->innerJoin('#__eshop_productoptionvalues AS pov ON po.id = pov.product_option_id')
					->innerJoin('#__eshop_optionvaluedetails AS ovd ON ovd.optionvalue_id = pov.option_value_id')
					->where('po.option_id = ' . $option->id)
					->where('ovd.language = ' . $db->quote(JFactory::getLanguage()->getTag()))
					->group('pov.option_value_id');

				if ($filterData['change_filter'] != 'option_' . $option->id)
				{

					$query->where('po.product_id  IN (' . implode(',', $filterData['product_ids']) . ')');
				}
				else
				{
					$otQuery = clone $subQuery;

					// Add option filter
					static::applyOptionFilter($otQuery, $filterData, $option->id);

					$query->where('po.product_id  IN (' . (string) $otQuery . ')');
				}
				$db->setQuery($query);
				$option->optionValues = $db->loadObjectList();
			}
		}

		return $options;
	}

	/**
	 * Function to apply filters
	 *
	 * @param JDatabaseQuery $query
	 * @param array          $filterData
	 * @param string         $exceptElement
	 */
	public static function applyFilters($query, $filterData, $exceptElement = '')
	{
		$elements = array('Category', 'Manufacturer', 'Attribute', 'Option', 'Price', 'Weight', 'Length', 'Width', 'Height', 'Stock', 'Keyword');

		foreach ($elements as $element)
		{
			if ($element == $exceptElement || ($element == 'Category' && empty($filterData['category_id'])) || ($element == 'Manufacturer' && empty($filterData['manufacturer_ids'])))
			{
				continue;
			}

			$filterFunction = 'apply' . $element . 'Filter';
			call_user_func_array(array('EshopFilter', $filterFunction), array($query, $filterData));
		}
	}

	/**
	 * Add category filter to query
	 *
	 * @param JDatabaseQuery $query
	 * @param array          $filterData
	 */
	public static function applyCategoryFilter($query, $filterData)
	{
		if (EshopHelper::getConfigValue('show_products_in_all_levels'))
		{
			$categoryIds = array_merge(array($filterData['category_id']), EshopHelper::getAllChildCategories($filterData['category_id']));

			$query->where('a.id IN (SELECT pc.product_id FROM #__eshop_productcategories AS pc WHERE pc.category_id IN (' . implode(',', $categoryIds) . '))');
		}
		else
		{
			$query->where('a.id IN (SELECT pc.product_id FROM #__eshop_productcategories AS pc WHERE pc.category_id = ' . $filterData['category_id'] . ')');
		}
	}

	/**
	 * Add manufacturer filter to query
	 *
	 * @param JDatabaseQuery $query
	 * @param  array         $filterData
	 */
	public static function applyManufacturerFilter($query, $filterData)
	{
		$query->where('a.manufacturer_id IN (' . implode(',', $filterData['manufacturer_ids']) . ')');
	}

	/**
	 * Add attribute filter to query
	 *
	 * @param JDatabaseQuery $query
	 * @param  array         $filterData
	 * @param int            $currentAttributeId
	 *
	 */
	public static function applyAttributeFilter($query, $filterData, $currentAttributeId = 0)
	{
		// Get all attributes
		$attributes = static::getAttributes();
		$db         = JFactory::getDbo();
		$atQuery    = $db->getQuery(true);

		foreach ($attributes as $attribute)
		{
			if ($attribute->id == $currentAttributeId)
			{
				continue;
			}

			if (!empty($filterData['attribute_' . $attribute->id]))
			{
				$attributeValues = $filterData['attribute_' . $attribute->id];

				$attributeValues = array_map(array($db, 'quote'), $attributeValues);

				$atQuery->clear()
					->select('pad.product_id')
					->from('#__eshop_productattributes AS pa')
					->innerJoin('#__eshop_productattributedetails AS pad ON pa.id = pad.productattribute_id')
					->where('pa.attribute_id = ' . $attribute->id)
					->where('pad.value IN (' . implode(',', $attributeValues) . ')');

				$query->where('a.id IN (' . (string) $atQuery . ')');
			}
		}
	}

	/**
	 * Add option filter to query
	 *
	 * @param JDatabaseQuery $query
	 * @param  array         $filterData
	 * @param int            $currentOptionId
	 */
	public static function applyOptionFilter($query, $filterData, $currentOptionId = 0)
	{
		// Get all options
		$options = static::getOptions();
		$db      = JFactory::getDbo();
		$otQuery = $db->getQuery(true);

		foreach ($options as $option)
		{
			if ($option->id == $currentOptionId)
			{
				continue;
			}

			if (!empty($filterData['option_' . $option->id]))
			{
				$optionValues = $filterData['option_' . $option->id];

				$optionValues = array_map(array($db, 'quote'), $optionValues);

				$otQuery->clear()
					->select('pov.product_id')
					->from('#__eshop_productoptions AS po')
					->innerJoin('#__eshop_productoptionvalues AS pov ON po.id = pov.product_option_id')
					->where('po.option_id = ' . $option->id)
					->where('pov.option_value_id IN (' . implode(',', $optionValues) . ')');

				$query->where('a.id IN (' . (string) $otQuery . ')');
			}
		}
	}

	/**
	 * Add price filter to query
	 *
	 * @param JDatabaseQuery $query
	 * @param array          $filterData
	 */
	public static function applyPriceFilter($query, $filterData)
	{
		if (isset($filterData['min_price']))
		{
			$query->where('a.product_price >= ' . $filterData['min_price']);
		}
		
		if (isset($filterData['max_price']))
		{
			$query->where('a.product_price <= ' . $filterData['max_price']);
		}
	}

	/**
	 * Add weight filter to query
	 *
	 * @param JDatabase $query
	 * @param array     $filterData
	 */
	public static function applyWeightFilter($query, $filterData)
	{
		if (isset($filterData['same_weight_unit']) && $filterData['same_weight_unit'])
		{
			if (isset($filterData['min_weight']))
			{
				$query->where('a.product_weight >= ' . $filterData['min_weight']);
			}
			if (isset($filterData['max_weight']))
			{
				$query->where('a.product_weight <= ' . $filterData['max_weight']);
			}
		}
		else
		{
			$eshopWeight     = new EshopWeight();
			$weightIds       = EshopHelper::getWeightIds();
			$defaultWeightId = EshopHelper::getConfigValue('weight_id');
			if (isset($filterData['min_weight']) || isset($filterData['max_weight']))
			{
				$minWeightQuery = array();
				$maxWeightQuery = array();
				foreach ($weightIds as $weightId)
				{
					if (isset($filterData['min_weight']))
					{
						$minWeightQuery[] = '(a.product_weight_id = ' . $weightId . ' AND a.product_weight >= ' . $eshopWeight->convert($filterData['min_weight'], $defaultWeightId, $weightId) . ')';
					}
					if (isset($filterData['max_weight']))
					{
						$maxWeightQuery[] = '(a.product_weight_id = ' . $weightId . ' AND a.product_weight <= ' . $eshopWeight->convert($filterData['max_weight'], $defaultWeightId, $weightId) . ')';
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
	}

	/**
	 * Add length filter to query
	 *
	 * @param JDatabase $query
	 * @param array     $filterData
	 */
	public static function applyLengthFilter($query, $filterData)
	{
		if (isset($filterData['same_length_unit']) && $filterData['same_length_unit'])
		{
			if (isset($filterData['min_length']))
			{
				$query->where('a.product_length >= ' . $filterData['min_length']);
			}
			if (isset($filterData['max_length']))
			{
				$query->where('a.product_length <= ' . $filterData['max_length']);
			}
		}
		else
		{
			$eshopLength     = new EshopLength();
			$lengthIds       = EshopHelper::getLengthIds();
			$defaultLengthId = EshopHelper::getConfigValue('length_id');
			if (isset($filterData['min_length']) || isset($filterData['max_length']))
			{
				$minLengthQuery = array();
				$maxLengthQuery = array();
				foreach ($lengthIds as $lengthId)
				{
					if (isset($filterData['min_length']))
					{
						$minLengthQuery[] = '(a.product_length_id = ' . $lengthId . ' AND a.product_length >= ' . $eshopLength->convert($filterData['min_length'], $defaultLengthId, $lengthId) . ')';
					}
					if (isset($filterData['max_length']))
					{
						$maxLengthQuery[] = '(a.product_length_id = ' . $lengthId . ' AND a.product_length <= ' . $eshopLength->convert($filterData['max_length'], $defaultLengthId, $lengthId) . ')';
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
	}

	/**
	 * Add width filter to query
	 *
	 * @param JDatabase $query
	 * @param array     $filterData
	 */
	public static function applyWidthFilter($query, $filterData)
	{
		if (isset($filterData['same_length_unit']) && $filterData['same_length_unit'])
		{
			if (isset($filterData['min_width']))
			{
				$query->where('a.product_width >= ' . $filterData['min_width']);
			}
			if (isset($filterData['max_width']))
			{
				$query->where('a.product_width <= ' . $filterData['max_width']);
			}
		}
		else
		{
			$eshopLength     = new EshopLength();
			$lengthIds       = EshopHelper::getLengthIds();
			$defaultLengthId = EshopHelper::getConfigValue('length_id');
			if (isset($filterData['min_width']) || isset($filterData['max_width']))
			{
				$minWidthQuery = array();
				$maxWidthQuery = array();
				foreach ($lengthIds as $lengthId)
				{
					if (isset($filterData['min_width']))
					{
						$minWidthQuery[] = '(a.product_length_id = ' . $lengthId . ' AND a.product_width >= ' . $eshopLength->convert($filterData['min_width'], $defaultLengthId, $lengthId) . ')';
					}
					if (isset($filterData['max_width']))
					{
						$maxWidthQuery[] = '(a.product_length_id = ' . $lengthId . ' AND a.product_width <= ' . $eshopLength->convert($filterData['max_width'], $defaultLengthId, $lengthId) . ')';
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
	}

	/**
	 * Add height filter to query
	 *
	 * @param JDatabase $query
	 * @param array     $filterData
	 */
	public static function applyHeightFilter($query, $filterData)
	{
		if (isset($filterData['same_length_unit']) && $filterData['same_length_unit'])
		{
			if (isset($filterData['min_height']))
			{
				$query->where('a.product_height >= ' . $filterData['min_height']);
			}
			if (isset($filterData['max_height']))
			{
				$query->where('a.product_height <= ' . $filterData['max_height']);
			}
		}
		else
		{
			$eshopLength     = new EshopLength();
			$lengthIds       = EshopHelper::getLengthIds();
			$defaultLengthId = EshopHelper::getConfigValue('length_id');
			if (isset($filterData['min_height']) || isset($filterData['max_height']))
			{
				$minHeightQuery = array();
				$maxHeightQuery = array();
				foreach ($lengthIds as $lengthId)
				{
					if (isset($filterData['min_height']))
					{
						$minHeightQuery[] = '(a.product_length_id = ' . $lengthId . ' AND a.product_height >= ' . $eshopLength->convert($filterData['min_height'], $defaultLengthId, $lengthId) . ')';
					}
					if (isset($filterData['max_height']))
					{
						$maxHeightQuery[] = '(a.product_length_id = ' . $lengthId . ' AND a.product_height <= ' . $eshopLength->convert($filterData['max_height'], $defaultLengthId, $lengthId) . ')';
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
	}

	/**
	 * Add stock filter to query
	 *
	 * @param JDatabase $query
	 * @param array     $filterData
	 */
	public static function applyStockFilter($query, $filterData)
	{
		if (isset($filterData['product_in_stock']))
		{
			if ($filterData['product_in_stock'] == 1)
			{
				$query->where('a.product_quantity > 0');
			}
			if ($filterData['product_in_stock'] == -1)
			{
				$query->where('a.product_quantity <= 0');
			}	
		}
	}

	/**
	 * Add keyword filter to query
	 *
	 * @param JDatabase $query
	 * @param array     $filterData
	 */
	public static function applyKeywordFilter($query, $filterData)
	{
		if (!empty($filterData['keyword']))
		{
			$db = JFactory::getDbo();
			$query->innerJoin('#__eshop_productdetails AS pd ON a.id = pd.product_id')
				->where('pd.language = ' . $db->quote(JFactory::getLanguage()->getTag()));
			$keywordArr = explode(' ', $filterData['keyword']);
			foreach ($keywordArr as $keyword)
			{
				$keyword = $db->quote('%' . trim($keyword) . '%');
				$searchKeywordArr = array();
				$searchKeywordArr[] = 'a.product_sku LIKE ' . $keyword;
				$searchKeywordArr[] = 'pd.product_name LIKE ' . $keyword;
				$searchKeywordArr[] = 'pd.product_short_desc LIKE ' . $keyword;
				$searchKeywordArr[] = 'pd.product_desc LIKE ' . $keyword;
				$searchKeywordArr[] = 'pd.tab1_title LIKE ' . $keyword;
				$searchKeywordArr[] = 'pd.tab1_content LIKE ' . $keyword;
				$searchKeywordArr[] = 'pd.tab2_title LIKE ' . $keyword;
				$searchKeywordArr[] = 'pd.tab2_content LIKE ' . $keyword;
				$searchKeywordArr[] = 'pd.tab3_title LIKE ' . $keyword;
				$searchKeywordArr[] = 'pd.tab3_content LIKE ' . $keyword;
				$searchKeywordArr[] = 'pd.tab4_title LIKE ' . $keyword;
				$searchKeywordArr[] = 'pd.tab4_content LIKE ' . $keyword;
				$searchKeywordArr[] = 'pd.tab5_title LIKE ' . $keyword;
				$searchKeywordArr[] = 'pd.tab5_content LIKE ' . $keyword;
				$searchKeywordArr[] = 'pd.id IN (SELECT product_id FROM #__eshop_producttags WHERE tag_id IN (SELECT id FROM #__eshop_tags WHERE tag_name LIKE ' . $keyword . '))';
				$query->where('(' . implode(' OR ', $searchKeywordArr) . ')');
			}
		}
	}
	
	/**
	 * 
	 * Function to get parameter of EShop Products Filter module
	 * @param string $paramName
	 * @return value of parameter
	 */
	public static function getFilterParam($paramName)
	{
		$module = JModuleHelper::getModule('mod_eshop_products_filter');
		$params = new Registry($module->params);
		return $params->get($paramName);		
	}
}