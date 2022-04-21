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

/**
 * EShop controller
 *
 * @package        Joomla
 * @subpackage     EShop
 * @since          1.5
 */
class EShopController extends JControllerLegacy
{
	/**
	 * Constructor function
	 *
	 * @param array $config
	 */
	function __construct($config = array())
	{
		//By adding this code, the system will first find the model from backend, if not exist, it will use the model class defined in the front-end
		$config['model_path'] = JPATH_ADMINISTRATOR . '/components/com_eshop/models';
		parent::__construct($config);
		$this->addModelPath(JPATH_COMPONENT . '/models', $this->model_prefix);
	}


	function search()
	{
		$input			 = JFactory::getApplication()->input;
		$currency        = new EshopCurrency();
		$currencyCode    = $currency->getCurrencyCode();
		$db              = JFactory::getDbo();
		$query           = $db->getQuery(true);
		$query->select('left_symbol, right_symbol')
			->from('#__eshop_currencies')
			->where('currency_code = ' . $db->quote($currencyCode));
		$db->setQuery($query);
		$row = $db->loadObject();
		($row->left_symbol) ? $symbol = $row->left_symbol : $symbol = $row->right_symbol;

		// Get weight unit
		$weight     = new EshopWeight();
		$weightId   = EshopHelper::getConfigValue('weight_id');
		$weightUnit = $weight->getUnit($weightId);

		//Get length unit
		$length     = new EshopLength();
		$lengthId   = EshopHelper::getConfigValue('length_id');
		$lengthUnit = $length->getUnit($lengthId);

		// Get submitted values
		$minPrice       = (float) str_replace($symbol, '', $input->get('min_price'));
		$maxPrice       = (float) str_replace($symbol, '', $input->get('max_price'));
		$minWeight      = (float) str_replace($weightUnit, '', $input->get('min_weight'));
		$maxWeight      = (float) str_replace($weightUnit, '', $input->get('max_weight'));
		$sameWeightUnit = $input->get('same_weight_unit');
		$minLength      = (float) str_replace($lengthUnit, '', $input->get('min_length'));
		$maxLength      = (float) str_replace($lengthUnit, '', $input->get('max_length'));
		$minWidth       = (float) str_replace($lengthUnit, '', $input->get('min_width'));
		$maxWidth       = (float) str_replace($lengthUnit, '', $input->get('max_width'));
		$minHeight      = (float) str_replace($lengthUnit, '', $input->get('min_height'));
		$maxHeight      = (float) str_replace($lengthUnit, '', $input->get('max_height'));
		$sameLengthUnit = $input->get('same_length_unit');
		$productInStock = $input->get('product_in_stock', 0);
		$categoryIds    = $input->get('category_ids');
		if (!$categoryIds)
		{
			$categoryIds = array();
		}
		$manufacturerIds = $input->get('manufacturer_ids');
		if (!$manufacturerIds)
		{
			$manufacturerIds = array();
		}
		$attributeIds = $input->get('attribute_ids');
		if (!$attributeIds)
		{
			$attributeIds = array();
		}
		$optionValueIds = $input->get('optionvalue_ids');
		if (!$optionValueIds)
		{
			$optionValueIds = array();
		}
		$keyword = $input->getString('keyword');
		
		// Build query string
		$query           = array();
		if ($minPrice > 0)
		{
			$query['min_price'] = $minPrice;
		}

		if ($maxPrice > 0)
		{
			$query['max_price'] = $maxPrice;
		}

		if ($minWeight > 0)
		{
			$query['min_weight'] = $minWeight;
		}

		if ($maxWeight)
		{
			$query['max_weight'] = $maxWeight;
		}
		
		if ($minWeight > 0 || $maxWeight > 0)
		{
			$query['same_weight_unit'] = $sameWeightUnit;
		}

		if ($minLength > 0)
		{
			$query['min_length'] = $minLength;
		}

		if ($maxLength)
		{
			$query['max_length'] = $maxLength;
		}

		if ($minWidth > 0)
		{
			$query['min_width'] = $minWidth;
		}

		if ($maxWidth)
		{
			$query['max_width'] = $maxWidth;
		}

		if ($minHeight > 0)
		{
			$query['min_height'] = $minHeight;
		}

		if ($maxHeight)
		{
			$query['max_height'] = $maxHeight;
		}
		
		if ($minLength > 0 || $maxLength > 0 || $minWidth > 0 || $maxWidth > 0 || $minHeight > 0 || $maxHeight > 0)
		{
			$query['same_length_unit'] = $sameLengthUnit;
		}

		if ($productInStock != 0)
		{
			$query['product_in_stock'] = $productInStock;
		}

		if (count($categoryIds))
		{
			$query['category_ids'] = implode(',', $categoryIds);
		}

		if (count($manufacturerIds))
		{
			$query['manufacturer_ids'] = implode(',', $manufacturerIds);
		}

		if (count($attributeIds))
		{
			$query['attribute_ids'] = implode(',', $attributeIds);
		}

		if (count($optionValueIds))
		{
			$query['optionvalue_ids'] = implode(',', $optionValueIds);
		}

		if ($keyword)
		{
			$query['keyword'] = $keyword;
		}
		$uri = JUri::getInstance();
		$uri->setQuery($query);
		$searchQuery = substr($uri->toString(array('query', 'fragment')), 1);
		$this->setRedirect(JRoute::_(EshopRoute::getViewRoute('search').'&'.$searchQuery, false));
	}
	
	/**
	 * 
	 * Function to download option file
	 */
	function downloadOptionFile()
	{
		$input  = JFactory::getApplication()->input;
		$id = $input->get('id');
		$app = JFactory::getApplication();
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('option_value')
			->from('#__eshop_orderoptions')
			->where('id = ' . intval($id));
		$db->setQuery($query);
		$filename = $db->loadResult();
		while (@ob_end_clean());
		EshopHelper::processDownload(JPATH_ROOT . '/media/com_eshop/files/' . $filename, $filename, true);
		$app->close(0);
	}
}