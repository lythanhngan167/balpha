<?php
/**
 * @version		3.1.0
 * @package		Joomla
 * @subpackage	EShop
 * @author  	Giang Dinh Truong
 * @copyright	Copyright (C) 2012 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die();

/**
 * EShop controller
 *
 * @package		Joomla
 * @subpackage	EShop
 * @since 1.5
 */
class EShopControllerExports extends JControllerLegacy
{

	/**
	 * Constructor function
	 *
	 * @param array $config
	 */
	function __construct($config = array())
	{
		parent::__construct($config);
	
	}

	/**
	 * Save the category
	 *
	 */
	function process()
	{
		$input = JFactory::getApplication()->input;
		$exportType = $input->getString('export_type', 'products');
		switch ($exportType)
		{
			case 'products':
				$this->_exportProducts();
				break;
			case 'categories':
				$this->_exportCategories();
				break;
			case 'manufacturers':
				$this->_exportManufacturers();
				break;
			case 'customers':
				$this->_exportCustomers();
				break;	
			case 'orders':
				$this->_exportOrders();
				break;
			case 'google_feed':
				$this->_exportGoogleFeed();
				break;	
		}
	}
	
	/**
	 * 
	 * Function to export products
	 */
	function _exportProducts()
	{
	    $input = JFactory::getApplication()->input;
		$fieldDelimiter	= $input->getString('field_delimiter', ',');
		$imageSeparator	= $input->getString('image_separator', ';');
		$language		= $input->getString('language', 'en-GB');
		$exportFormat	= $input->getString('export_format', 'csv');
		$db = JFactory::getDbo();
		$languageSql = $db->quote($language);
		$query = $db->getQuery(true);
		$query->select('a.*, b.*, a.id AS id')
			->from('#__eshop_products AS a')
			->innerJoin('#__eshop_productdetails AS b ON (a.id = b.product_id)')
			->where('b.language = ' . $languageSql );
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		$csvOutput = array();
		if (count($rows))
		{
			if ($exportFormat == 'csv')
			{
				$resultsArr = array();
				$resultsArr[] = 'id';
				$resultsArr[] = 'language';
				$resultsArr[] = 'product_sku';
				$resultsArr[] = 'product_name';
				$resultsArr[] = 'product_alias';
				$resultsArr[] = 'product_desc';
				$resultsArr[] = 'product_short_desc';
				$resultsArr[] = 'product_page_title';
				$resultsArr[] = 'product_page_heading';
				$resultsArr[] = 'tab1_title';
				$resultsArr[] = 'tab1_content';
				$resultsArr[] = 'tab2_title';
				$resultsArr[] = 'tab2_content';
				$resultsArr[] = 'tab3_title';
				$resultsArr[] = 'tab3_content';
				$resultsArr[] = 'tab4_title';
				$resultsArr[] = 'tab4_content';
				$resultsArr[] = 'tab5_title';
				$resultsArr[] = 'tab5_content';
				$resultsArr[] = 'product_meta_key';
				$resultsArr[] = 'product_meta_desc';
				$resultsArr[] = 'product_weight';
				$resultsArr[] = 'product_weight_id';
				$resultsArr[] = 'product_length';
				$resultsArr[] = 'product_width';
				$resultsArr[] = 'product_height';
				$resultsArr[] = 'product_length_id';
				$resultsArr[] = 'product_price';
				$resultsArr[] = 'product_call_for_price';
				$resultsArr[] = 'product_taxclass_id';
				$resultsArr[] = 'product_quantity';
				$resultsArr[] = 'product_threshold';
				$resultsArr[] = 'product_threshold_notify';
				$resultsArr[] = 'product_stock_checkout';
				$resultsArr[] = 'product_minimum_quantity';
				$resultsArr[] = 'product_maximum_quantity';
				$resultsArr[] = 'product_shipping';
				$resultsArr[] = 'product_shipping_cost';
				$resultsArr[] = 'product_image';
				$resultsArr[] = 'product_available_date';
				$resultsArr[] = 'product_featured';
				$resultsArr[] = 'product_customergroups';
				$resultsArr[] = 'product_stock_status_id';
				$resultsArr[] = 'product_quote_mode';
				$resultsArr[] = 'product_published';
				$resultsArr[] = 'product_ordering';
				$resultsArr[] = 'product_hits';
				$resultsArr[] = 'product_additional_images';
				$resultsArr[] = 'manufacturer_name';
				$resultsArr[] = 'category_name';
				$resultsArr[] = 'option_type';
				$resultsArr[] = 'option_name';
				$resultsArr[] = 'option_value';
				$resultsArr[] = 'option_sku';
				$resultsArr[] = 'option_quantity';
				$resultsArr[] = 'option_price';
				$resultsArr[] = 'option_price_sign';
				$resultsArr[] = 'option_price_type';
				$resultsArr[] = 'option_weight';
				$resultsArr[] = 'option_weight_sign';
				$resultsArr[] = 'option_image';
				$resultsArr[] = 'attributegroup_name';
				$resultsArr[] = 'attribute_name';
				$resultsArr[] = 'attribute_value';
				
				$csvOutput[] = $resultsArr;
			}
			
			foreach ($rows as $row)
			{
				//Get additional images for product
				$query->clear();
				$query->select('image')
					->from('#__eshop_productimages')
					->where('product_id = ' . $row->id);
				$db->setQuery($query);
				$images = $db->loadColumn();
				
				//Get product manufacturer
				$manufacturer = EshopHelper::getProductManufacturer($row->id, $language);
				
				//Get product categories
				$productCategories = EshopHelper::getProductCategories($row->id, $language);
				
				// field options
				$query	->clear()->select('a.option_type')->from('#__eshop_options AS a')
						->select('b.option_name')->innerJoin('#__eshop_optiondetails AS b ON (b.option_id = a.id AND b.language='.$languageSql.')')
						->select('c.value AS option_value')->innerJoin('#__eshop_optionvaluedetails AS c ON (c.option_id = a.id AND c.language = '.$languageSql.')')
						->select('d.sku AS option_sku, d.quantity AS option_quantity, d.price AS option_price, d.price_sign AS option_price_sign, d.price_type AS option_price_type, d.weight AS option_weight, d.weight_sign AS option_weight_sign, d.image AS option_image')
						->innerJoin('#__eshop_productoptionvalues AS d ON (d.option_id = a.id AND d.option_value_id = c.optionvalue_id  AND d.product_id='. $row->product_id.')')
						->order('a.ordering');
				$db->setQuery($query);
				$optionlist = $db->loadObjectList();
				$valueoptions = array();
				if (count($optionlist))
				foreach ($optionlist as $obj) {
					$valueoptions['option_type'][] 			= $obj->option_type;
					$valueoptions['option_name'][] 			= $obj->option_name;
					$valueoptions['option_value'][] 		= $obj->option_value;
					$valueoptions['option_sku'][] 			= $obj->option_sku;
					$valueoptions['option_quantity'][] 		= $obj->option_quantity;
					$valueoptions['option_price'][] 		= $obj->option_price;
					$valueoptions['option_price_sign'][] 	= $obj->option_price_sign;
					$valueoptions['option_price_type'][] 	= $obj->option_price_type;
					$valueoptions['option_weight'][] 		= $obj->option_weight;
					$valueoptions['option_weight_sign'][] 	= $obj->option_weight_sign;
					$valueoptions['option_image'][] 		= $obj->option_image;					
				}
				
				// field attribute
				$query->clear()
						->select('a.attributegroup_name')
						->from('#__eshop_attributegroupdetails AS a')
						->innerJoin('#__eshop_attributes AS b ON a.attributegroup_id = b.attributegroup_id')
						->select('c.attribute_name')
						->innerJoin('#__eshop_attributedetails AS c ON (b.id=c.attribute_id AND c.language = ' . $languageSql . ')')
						->innerJoin('#__eshop_productattributes AS d ON (c.attribute_id = d.attribute_id AND d.product_id = '.$row->product_id.')')
						->select('e.value AS attribute_value')
						->innerJoin('#__eshop_productattributedetails AS e ON (e.productattribute_id = d.id AND e.product_id = ' . $row->product_id . ' AND e.language = ' . $languageSql . ')')
						->where('a.language = ' . $languageSql);
				$db->setQuery($query);
				$attributelist = $db->loadObjectList();
				$valueattributes = array();
				
				if (count($attributelist))
				{	
					foreach ($attributelist as $obj)
					{
						$valueattributes['attributegroup_name'][] = $obj->attributegroup_name;
						$valueattributes['attribute_name'][] = $obj->attribute_name;
						$valueattributes['attribute_value'][] = $obj->attribute_value;
					}
				}
			
				if ($exportFormat == 'csv')
				{
					$resultsArr = array();
					$resultsArr[] = $row->product_id;
					$resultsArr[] = $language;
					$resultsArr[] = $row->product_sku;
					$resultsArr[] = $row->product_name;
					$resultsArr[] = $row->product_alias;
					$resultsArr[] = $row->product_desc;
					$resultsArr[] = $row->product_short_desc;
					$resultsArr[] = $row->product_page_title;
					$resultsArr[] = $row->product_page_heading;
					$resultsArr[] = $row->tab1_title;
					$resultsArr[] = $row->tab1_content;
					$resultsArr[] = $row->tab2_title;
					$resultsArr[] = $row->tab2_content;
					$resultsArr[] = $row->tab3_title;
					$resultsArr[] = $row->tab3_content;
					$resultsArr[] = $row->tab4_title;
					$resultsArr[] = $row->tab4_content;
					$resultsArr[] = $row->tab5_title;
					$resultsArr[] = $row->tab5_content;
					$resultsArr[] = $row->meta_key;
					$resultsArr[] = $row->meta_desc;
					$resultsArr[] = $row->product_weight;
					$resultsArr[] = $row->product_weight_id;
					$resultsArr[] = $row->product_length;
					$resultsArr[] = $row->product_width;
					$resultsArr[] = $row->product_height;
					$resultsArr[] = $row->product_length_id;
					$resultsArr[] = $row->product_price;
					$resultsArr[] = $row->product_call_for_price;
					$resultsArr[] = $row->product_taxclass_id;
					$resultsArr[] = $row->product_quantity;
					$resultsArr[] = $row->product_threshold;
					$resultsArr[] = $row->product_threshold_notify;
					$resultsArr[] = $row->product_stock_checkout;
					$resultsArr[] = $row->product_minimum_quantity;
					$resultsArr[] = $row->product_maximum_quantity;
					$resultsArr[] = $row->product_shipping;
					$resultsArr[] = $row->product_shipping_cost;
					$resultsArr[] = $row->product_image;
					$resultsArr[] = $row->product_available_date;
					$resultsArr[] = $row->product_featured;
					$resultsArr[] = $row->product_customergroups;
					$resultsArr[] = $row->product_stock_status_id;
					$resultsArr[] = $row->product_quote_mode;
					$resultsArr[] = $row->published;
					$resultsArr[] = $row->ordering;
					$resultsArr[] = $row->hits;
					
					if (count($images))
					{
						$resultsArr[] = implode($imageSeparator, $images);
					}
					else
					{
						$resultsArr[] = '';
					}
					
					if (is_object($manufacturer))
					{
						$resultsArr[] = $manufacturer->manufacturer_name;
					}
					else
					{
						$resultsArr[] = '';
					}
					
					$categories = array();
					
					if (count($productCategories))
					{
						foreach ($productCategories as $category)
						{
							//$categories[] = implode('/', EshopHelper::getCategoryNamePath($category->id, $language));
							$categories[] = $category->category_name;
						}
						$resultsArr[] = implode(' | ', $categories);
					}
					else
					{
						$resultsArr[] = '';
					}
					
					$resultsArr[] = isset($valueoptions['option_type']) ? implode(';', $valueoptions['option_type'])	: '';
					$resultsArr[] = isset($valueoptions['option_name']) ? implode(';', $valueoptions['option_name'])	: '';
					$resultsArr[] = isset($valueoptions['option_value']) ? implode(';', $valueoptions['option_value']) : '';
					$resultsArr[] = isset($valueoptions['option_sku']) ? implode(';', $valueoptions['option_sku']) : '';
					$resultsArr[] = isset($valueoptions['option_quantity']) ? implode(';', $valueoptions['option_quantity'])	: '';
					$resultsArr[] = isset($valueoptions['option_price']) ? implode(';', $valueoptions['option_price']) : '';
					$resultsArr[] = isset($valueoptions['option_price_sign']) ? implode(';', $valueoptions['option_price_sign'])	: '';
					$resultsArr[] = isset($valueoptions['option_price_type']) ? implode(';', $valueoptions['option_price_type']): '';
					$resultsArr[] = isset($valueoptions['option_weight']) ? implode(';', $valueoptions['option_weight']) : '';
					$resultsArr[] = isset($valueoptions['option_weight_sign']) ? implode(';', $valueoptions['option_weight_sign']) : '';
					$resultsArr[] = isset($valueoptions['option_image']) ? implode(';', $valueoptions['option_image']) : '';
					
					$resultsArr[] = isset($valueattributes['attributegroup_name']) ? implode(';', $valueattributes['attributegroup_name']) : '';
					$resultsArr[] = isset($valueattributes['attribute_name']) ? implode(';', $valueattributes['attribute_name']) : '';
					$resultsArr[] = isset($valueattributes['attribute_value']) ? implode(';', $valueattributes['attribute_value']) : '';
					
					$csvOutput[] = $resultsArr;
				}
				else 
				{
					$productUrl = JUri::root().JRoute::_(EshopRoute::getProductRoute($row->product_id, EshopHelper::getProductCategory($row->product_id)));
					$productUrl = str_replace("/administrator/", '', $productUrl);
					$categories = array();
					if (count($productCategories))
					{
						foreach ($productCategories as $category)
						{
							$categories[] = implode(' > ', EshopHelper::getCategoryNamePath($category->id, $language));
						}
					}
					$productXmlArray = array();
					$xmlarray['products']['product'][] = array(
						'id'							=> $row->product_id,
						'link'							=> $productUrl,
						'product_sku'					=> $row->product_sku,
						'name'							=> $row->product_name,
						'product_alias'					=> $row->product_alias,
						'product_desc'					=> $row->product_desc,
						'product_short_desc'			=> $row->product_short_desc,
						'product_page_title'			=> $row->product_page_title,
						'product_page_heading'			=> $row->product_page_heading,
						'tab1_title'					=> $row->tab1_title,
						'tab1_content'					=> $row->tab1_content,
						'tab2_title'					=> $row->tab2_title,
						'tab2_content'					=> $row->tab2_content,
						'tab3_title'					=> $row->tab3_title,
						'tab3_content'					=> $row->tab3_content,
						'tab4_title'					=> $row->tab4_title,
						'tab4_content'					=> $row->tab4_content,
						'tab5_title'					=> $row->tab5_title,
						'tab5_content'					=> $row->tab5_content,
						'product_meta_key'				=> $row->meta_key,
						'product_meta_desc'				=> $row->meta_desc,
						'product_weight'				=> $row->product_weight,
						'product_weight_id'				=> $row->product_weight_id,
						'product_length'				=> $row->product_length,
						'product_width'					=> $row->product_width,
						'product_height'				=> $row->product_height,
						'product_length_id'				=> $row->product_length_id,
						'product_price'					=> $row->product_price,
						'product_call_for_price'		=> $row->product_call_for_price,
						'product_taxclass_id'			=> $row->product_taxclass_id,
						'product_quantity'				=> $row->product_quantity,
						'product_threshold'				=> $row->product_threshold,
						'product_threshold_notify'		=> $row->product_threshold_notify,
						'product_stock_checkout'		=> $row->product_stock_checkout,
						'product_minimum_quantity'		=> $row->product_minimum_quantity,
						'product_maximum_quantity'		=> $row->product_maximum_quantity,
						'product_shipping'				=> $row->product_shipping,
						'product_shipping_cost'			=> $row->product_shipping_cost,
						'image'							=> JUri::root().'media/com_eshop/products/'.$row->product_image,
						'product_available_date'		=> $row->product_available_date,
						'product_featured'				=> $row->product_featured,
						'product_customergroups'		=> $row->product_customergroups,
						'product_stock_status_id'		=> $row->product_stock_status_id,
						'product_quote_mode'			=> $row->product_quote_mode,
						'product_published'				=> $row->published,
						'product_ordering'				=> $row->ordering,
						'product_hits'					=> $row->hits,
						'product_additional_images'		=> implode($imageSeparator, $images),
						'manufacturer_name'				=> $manufacturer->manufacturer_name,
						'category'						=> implode(';', $categories),
						'option_type'					=> isset($valueoptions['option_type']) ? implode(';', $valueoptions['option_type'])	: '',
						'option_name'					=> isset($valueoptions['option_name']) ? implode(';', $valueoptions['option_name'])	: '',
						'option_value'					=> isset($valueoptions['option_value']) ? implode(';', $valueoptions['option_value']) : '',
						'option_sku'					=> isset($valueoptions['option_sku']) ? implode(';', $valueoptions['option_sku']) : '',
						'option_quantity'				=> isset($valueoptions['option_quantity']) ? implode(';', $valueoptions['option_quantity'])	: '',
						'option_price'					=> isset($valueoptions['option_price']) ? implode(';', $valueoptions['option_price']) : '',
						'option_price_sign'				=> isset($valueoptions['option_price_sign']) ? implode(';', $valueoptions['option_price_sign'])	: '',
						'option_price_type'				=> isset($valueoptions['option_price_type']) ? implode(';', $valueoptions['option_price_type']): '',
						'option_weight'					=> isset($valueoptions['option_weight']) ? implode(';', $valueoptions['option_weight']) : '',
						'option_weight_sign'			=> isset($valueoptions['option_weight_sign']) ? implode(';', $valueoptions['option_weight_sign']) : '',
						'option_image'					=> isset($valueoptions['option_image']) ? implode(';', $valueoptions['option_image']) : '',
						'attributegroup_name'			=> isset($valueattributes['attributegroup_name']) ? implode(';', $valueattributes['attributegroup_name']) : '',
						'attribute_name'				=> isset($valueattributes['attribute_name']) ? implode(';', $valueattributes['attribute_name']) : '',
						'attribute_value'				=> isset($valueattributes['attribute_value']) ? implode(';', $valueattributes['attribute_value']) : ''
					);
					$productXmlArray[] = $xmlarray;
				}
			}
			
			if ($exportFormat == 'csv')
			{
				$filename = 'products_'.date('YmdHis').'.csv';
				$fp = fopen(JPATH_ROOT . '/media/com_eshop/files/' . $filename, 'w');
				fprintf($fp,chr(0xEF).chr(0xBB).chr(0xBF));
				foreach ($csvOutput as $output)
				{
					fputcsv($fp, $output, $fieldDelimiter);
				}
				fclose($fp);
				EshopHelper::processDownload(JPATH_ROOT . '/media/com_eshop/files/' . $filename, $filename, true);
				exit();
			}
			else 
			{
				$filename = 'products_'.date('YmdHis').'.xml';
				include_once JPATH_ROOT.'/components/com_eshop/helpers/array2xml.php';
				$xml = Array2XML::createXML('mywebstore', $productXmlArray[0]);
				JFile::write(JPATH_ROOT . '/media/com_eshop/files/' . $filename, $xml->saveXML());
				EshopHelper::processDownload(JPATH_ROOT . '/media/com_eshop/files/' . $filename, $filename, true);
				exit();
			}
		}
		else
		{
			$mainframe = JFactory::getApplication();
			$mainframe->enqueueMessage(JText::_('ESHOP_NO_DATA_TO_EXPORT'), 'notice');
			$mainframe->redirect('index.php?option=com_eshop&view=exports');
		}
	}
	
	/**
	 *
	 * Function to export categories
	 */
	function _exportCategories()
	{
		$input = JFactory::getApplication()->input;
		$fieldDelimiter = $input->getString('field_delimiter', ',');
		$language = $input->getString('language', 'en-GB');
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('a.*, b.category_name, b.category_alias, b.category_desc, b.category_page_title, b.category_page_heading, b.meta_key, b.meta_desc')
			->from('#__eshop_categories AS a')
			->innerJoin('#__eshop_categorydetails AS b ON (a.id = b.category_id)')
			->where('b.language = "' . $language . '"');
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		$csvOutput = array();
		if (count($rows))
		{
			$resultsArr = array();
			$resultsArr[] = 'id';
			$resultsArr[] = 'language';
			$resultsArr[] = 'category_name';
			$resultsArr[] = 'category_alias';
			$resultsArr[] = 'category_desc';
			$resultsArr[] = 'category_page_title';
			$resultsArr[] = 'category_page_heading';
			$resultsArr[] = 'category_image';
			$resultsArr[] = 'products_per_page';
			$resultsArr[] = 'products_per_row';
			$resultsArr[] = 'category_published';
			$resultsArr[] = 'category_ordering';
			$resultsArr[] = 'category_hits';
			$resultsArr[] = 'category_meta_key';
			$resultsArr[] = 'category_meta_desc';
			$csvOutput[] = $resultsArr;
				
			foreach ($rows as $row)
			{
				$resultsArr = array();
				$resultsArr[] = $row->id;
				$resultsArr[] = $language;
				$resultsArr[] = $row->category_name;
				$resultsArr[] = $row->category_alias;
				$resultsArr[] = $row->category_desc;
				$resultsArr[] = $row->category_page_title;
				$resultsArr[] = $row->category_page_heading;
				$resultsArr[] = $row->category_image;
				$resultsArr[] = $row->products_per_page;
				$resultsArr[] = $row->products_per_row;
				$resultsArr[] = $row->published;
				$resultsArr[] = $row->ordering;
				$resultsArr[] = $row->hits;
				$resultsArr[] = $row->meta_key;
				$resultsArr[] = $row->meta_desc;
				$csvOutput[] = $resultsArr;
			}
				
			$filename = 'categories_'.date('YmdHis').'.csv';
			$fp = fopen(JPATH_ROOT.'/media/com_eshop/files/'.$filename, 'w');
			fprintf($fp,chr(0xEF).chr(0xBB).chr(0xBF));
			foreach ($csvOutput as $output)
			{
				fputcsv($fp, $output, $fieldDelimiter);
			}
			fclose($fp);
			EshopHelper::processDownload(JPATH_ROOT . '/media/com_eshop/files/' . $filename, $filename, true);
			exit();
		}
		else
		{
			$mainframe = JFactory::getApplication();
			$mainframe->enqueueMessage(JText::_('ESHOP_NO_DATA_TO_EXPORT'), 'notice');
			$mainframe->redirect('index.php?option=com_eshop&view=exports');
		}
	}
	
	/**
	 *
	 * Function to export manufacturers
	 */
	function _exportManufacturers()
	{
		$input = JFactory::getApplication()->input;
		$fieldDelimiter = $input->getString('field_delimiter', ',');
		$language = $input->getString('language', 'en-GB');
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('a.*, b.manufacturer_name, b.manufacturer_alias, b.manufacturer_desc, b.manufacturer_page_title, b.manufacturer_page_heading')
			->from('#__eshop_manufacturers AS a')
			->innerJoin('#__eshop_manufacturerdetails AS b ON (a.id = b.manufacturer_id)')
			->where('b.language = "' . $language . '"');
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		$csvOutput = array();
		if (count($rows))
		{
			$resultsArr = array();
			$resultsArr[] = 'id';
			$resultsArr[] = 'manufacturer_email';
			$resultsArr[] = 'manufacturer_url';
			$resultsArr[] = 'manufacturer_image';
			$resultsArr[] = 'manufacturer_customergroups';
			$resultsArr[] = 'manufacturer_published';
			$resultsArr[] = 'manufacturer_ordering';
			$resultsArr[] = 'manufacturer_hits';
			$resultsArr[] = 'language';
			$resultsArr[] = 'manufacturer_name';
			$resultsArr[] = 'manufacturer_alias';
			$resultsArr[] = 'manufacturer_desc';
			$resultsArr[] = 'manufacturer_page_title';
			$resultsArr[] = 'manufacturer_page_heading';
			$csvOutput[] = $resultsArr;
	
			foreach ($rows as $row)
			{
				$resultsArr = array();
				$resultsArr[] = $row->id;
				$resultsArr[] = $row->manufacturer_email;
				$resultsArr[] = $row->manufacturer_url;
				$resultsArr[] = $row->manufacturer_image;
				$resultsArr[] = $row->manufacturer_customergroups;
				$resultsArr[] = $row->published;
				$resultsArr[] = $row->ordering;
				$resultsArr[] = $row->hits;
				$resultsArr[] = $language;
				$resultsArr[] = $row->manufacturer_name;
				$resultsArr[] = $row->manufacturer_alias;
				$resultsArr[] = $row->manufacturer_desc;
				$resultsArr[] = $row->manufacturer_page_title;
				$resultsArr[] = $row->manufacturer_page_heading;
				$csvOutput[] = $resultsArr;
			}
	
			$filename = 'manufacturers_'.date('YmdHis').'.csv';
			$fp = fopen(JPATH_ROOT.'/media/com_eshop/files/'.$filename, 'w');
			fprintf($fp,chr(0xEF).chr(0xBB).chr(0xBF));
			foreach ($csvOutput as $output)
			{
				fputcsv($fp, $output, $fieldDelimiter);
			}
			fclose($fp);
			EshopHelper::processDownload(JPATH_ROOT . '/media/com_eshop/files/' . $filename, $filename, true);
			exit();
		}
		else
		{
			$mainframe = JFactory::getApplication();
			$mainframe->enqueueMessage(JText::_('ESHOP_NO_DATA_TO_EXPORT'), 'notice');
			$mainframe->redirect('index.php?option=com_eshop&view=exports');
		}
	}
	
	/**
	 *
	 * Function to export customers
	 */
	function _exportCustomers()
	{
		$input = JFactory::getApplication()->input;
		$fieldDelimiter = $input->getString('field_delimiter', ',');
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('a.name, a.email')
			->from('#__users AS a');
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		$csvOutput = array();
		if (count($rows))
		{
			$resultsArr = array();
			$resultsArr[] = 'Name';
			$resultsArr[] = 'Email';
			$csvOutput[] = $resultsArr;
	
			foreach ($rows as $row)
			{
				$resultsArr = array();
				$resultsArr[] = $row->name;
				$resultsArr[] = $row->email;
				$csvOutput[] = $resultsArr;
			}
	
			$filename = 'customers_'.date('YmdHis').'.csv';
			$fp = fopen(JPATH_ROOT.'/media/com_eshop/files/'.$filename, 'w');
			fprintf($fp,chr(0xEF).chr(0xBB).chr(0xBF));
			foreach ($csvOutput as $output)
			{
				fputcsv($fp, $output, $fieldDelimiter);
			}
			fclose($fp);
			EshopHelper::processDownload(JPATH_ROOT . '/media/com_eshop/files/' . $filename, $filename, true);
			exit();
		}
		else
		{
			$mainframe = JFactory::getApplication();
			$mainframe->enqueueMessage(JText::_('ESHOP_NO_DATA_TO_EXPORT'), 'notice');
			$mainframe->redirect('index.php?option=com_eshop&view=exports');
		}
	}
	
	/**
	 * 
	 * Function to export orders
	 */
	function _exportOrders()
	{
	    ini_set('memory_limit', '-1');
	    set_time_limit(0);
	     
		$input = JFactory::getApplication()->input;
		$currency = new EshopCurrency();
		$dateStart = $input->getString('date_start');
		$dateEnd = $input->getString('date_end');
		$groupBy = $input->getString('group_by', 'week');
		$orderStatusId = $input->getInt('order_status_id', 0);
		$orderIdFrom = $input->getInt('order_id_from', 0);
		$orderIdTo = $input->getInt('order_id_to', 0);
		$listOrderId = $input->getString('list_order_id', '');
		$fieldDelimiter = $input->getString('field_delimiter', ',');
		$exportFormat	= $input->getString('export_format', 'csv');
		$db = JFactory::getDbo();
		$nullDate = $db->getNullDate();
		$query = $db->getQuery(true);
		$query->select('*')
			->from('#__eshop_orders');
		if ($orderStatusId)
		{
			$query->where('order_status_id = ' . (int)$orderStatusId);
		}
		if ($orderIdFrom)
		{
			$query->where('id >= ' . intval($orderIdFrom));	
		}
		if ($orderIdTo)
		{
			$query->where('id <= ' . intval($orderIdTo));
		}
		if ($listOrderId != '')
		{
			$query->where('id IN (' . trim($listOrderId) . ')');
		}
		if (!empty($dateStart))
		{
			$query->where('created_date >= "' . $dateStart . '"');
		}
		if (!empty($dateEnd))
		{
			$query->where('created_date <= "' . $dateEnd . '"');
		}
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		$csvOutput = array();
		if (count($rows))
		{
		    if ($exportFormat == 'csv')
		    {
    			$resultsArr = array();
    			$resultsArr[] = 'order_id';
    			$resultsArr[] = 'order_number';
    			$resultsArr[] = 'invoice_number';
    			$resultsArr[] = 'customer_firstname';
    			$resultsArr[] = 'customer_lastname';
    			$resultsArr[] = 'customer_email';
    			$resultsArr[] = 'customer_telephone';
    			$resultsArr[] = 'customer_fax';
    			$resultsArr[] = 'payment_firstname';
    			$resultsArr[] = 'payment_lastname';
    			$resultsArr[] = 'payment_email';
    			$resultsArr[] = 'payment_telephone';
    			$resultsArr[] = 'payment_fax';
    			$resultsArr[] = 'payment_company';
    			$resultsArr[] = 'payment_company_id';
    			$resultsArr[] = 'payment_address_1';
    			$resultsArr[] = 'payment_address_2';
    			$resultsArr[] = 'payment_city';
    			$resultsArr[] = 'payment_postcode';
    			$resultsArr[] = 'payment_country_name';
    			$resultsArr[] = 'payment_zone_name';
    			$resultsArr[] = 'payment_method';
    			$resultsArr[] = 'payment_method_title';
    			$resultsArr[] = 'transaction_id';
    			$resultsArr[] = 'shipping_firstname';
    			$resultsArr[] = 'shipping_lastname';
    			$resultsArr[] = 'shipping_email';
    			$resultsArr[] = 'shipping_telephone';
    			$resultsArr[] = 'shipping_fax';
    			$resultsArr[] = 'shipping_company';
    			$resultsArr[] = 'shipping_company_id';
    			$resultsArr[] = 'shipping_address_1';
    			$resultsArr[] = 'shipping_address_2';
    			$resultsArr[] = 'shipping_city';
    			$resultsArr[] = 'shipping_postcode';
    			$resultsArr[] = 'shipping_country_name';
    			$resultsArr[] = 'shipping_zone_name';
    			$resultsArr[] = 'shipping_method';
    			$resultsArr[] = 'shipping_method_title';
    			$resultsArr[] = 'shipping_tracking_number';
    			$resultsArr[] = 'shipping_tracking_url';
    			$resultsArr[] = 'shipping_amount';
    			$resultsArr[] = 'tax_amount';
    			$resultsArr[] = 'total';
    			$resultsArr[] = 'comment';
    			$resultsArr[] = 'order_status';
    			$resultsArr[] = 'created_date';
    			$resultsArr[] = 'modified_date';
    			$resultsArr[] = 'product_id';
    			$resultsArr[] = 'product_name';
    			$resultsArr[] = 'option_name';
    			$resultsArr[] = 'option_value';
    			$resultsArr[] = 'option_sku';
    			$resultsArr[] = 'model';
    			$resultsArr[] = 'quantity';
    			$resultsArr[] = 'unit_price';
    			$resultsArr[] = 'unit_total';
    			
    			$csvOutput[] = $resultsArr;
    			foreach ($rows as $row)
    			{
    				$resultsArr = array();
    				$resultsArr[] = $row->id;
    				$resultsArr[] = $row->order_number;
    				$resultsArr[] = $row->invoice_number;
    				$resultsArr[] = $row->firstname;
    				$resultsArr[] = $row->lastname;
    				$resultsArr[] = $row->email;
    				$resultsArr[] = $row->telephone;
    				$resultsArr[] = $row->fax;
    				$resultsArr[] = $row->payment_firstname;
    				$resultsArr[] = $row->payment_lastname;
    				$resultsArr[] = $row->payment_email;
    				$resultsArr[] = $row->payment_telephone;
    				$resultsArr[] = $row->payment_fax;
    				$resultsArr[] = $row->payment_company;
    				$resultsArr[] = $row->payment_company_id;
    				$resultsArr[] = $row->payment_address_1;
    				$resultsArr[] = $row->payment_address_2;
    				$resultsArr[] = $row->payment_city;
    				$resultsArr[] = $row->payment_postcode;
    				$resultsArr[] = $row->payment_country_name;
    				$resultsArr[] = $row->payment_zone_name;
    				$resultsArr[] = $row->payment_method;
    				$resultsArr[] = $row->payment_method_title;
    				$resultsArr[] = $row->transaction_id;
    				$resultsArr[] = $row->shipping_firstname;
    				$resultsArr[] = $row->shipping_lastname;
    				$resultsArr[] = $row->shipping_email;
    				$resultsArr[] = $row->shipping_telephone;
    				$resultsArr[] = $row->shipping_fax;
    				$resultsArr[] = $row->shipping_company;
    				$resultsArr[] = $row->shipping_company_id;
    				$resultsArr[] = $row->shipping_address_1;
    				$resultsArr[] = $row->shipping_address_2;
    				$resultsArr[] = $row->shipping_city;
    				$resultsArr[] = $row->shipping_postcode;
    				$resultsArr[] = $row->shipping_country_name;
    				$resultsArr[] = $row->shipping_zone_name;
    				$resultsArr[] = $row->shipping_method;
    				$resultsArr[] = $row->shipping_method_title;
    				$resultsArr[] = $row->shipping_tracking_number;
    				$resultsArr[] = $row->shipping_tracking_url;
    				$query->clear()
    					->select('text')
    					->from('#__eshop_ordertotals')
    					->where('order_id = ' . intval($row->id))
    					->where('(name = "shipping" OR name="tax")')
    					->order('name ASC');
    				$db->setQuery($query);
    				$orderTotals = $db->loadColumn();
    				$resultsArr[] = isset($orderTotals[0]) ? $orderTotals[0] : '';
    				$resultsArr[] = isset($orderTotals[1]) ? $orderTotals[1] : '';
    				$resultsArr[] = $currency->format($row->total, $row->currency_code, $row->currency_exchanged_value);
    				$resultsArr[] = $row->comment;
    				$resultsArr[] = EshopHelper::getOrderStatusName($row->order_status_id, JComponentHelper::getParams('com_languages')->get('site', 'en-GB'));
    				if ($row->created_date != $nullDate)
    				{
    					$resultsArr[] = JHtml::_('date', $row->created_date, EshopHelper::getConfigValue('date_format', 'm-d-Y'), null);
    				}
    				else 
    				{
    					$resultsArr[] = '';
    				}
    				if ($row->modified_date != $nullDate)
    				{
    					$resultsArr[] = JHtml::_('date', $row->modified_date, EshopHelper::getConfigValue('date_format', 'm-d-Y'), null);
    				}
    				else 
    				{
    					$resultsArr[] = '';
    				}
    				
    				$query->clear();
    				$query->select('*')
    					->from('#__eshop_orderproducts')
    					->where('order_id = ' . intval($row->id));
    				$db->setQuery($query);
    				$orderProducts = $db->loadObjectList();
    				for ($i = 0; $n = count($orderProducts), $i < $n; $i++)
    				{
    					$totalOptionResultsArr = array();
    					$query->clear();
    					$query->select('*')
    						->from('#__eshop_orderoptions')
    						->where('order_product_id = ' . intval($orderProducts[$i]->id));
    					$db->setQuery($query);
    					$options = $db->loadObjectList();
    					if ($i > 0)
    					{
    						$resultsArr = array();
    						$resultsArr[] = '';
    						$resultsArr[] = '';
    						$resultsArr[] = '';
    						$resultsArr[] = '';
    						$resultsArr[] = '';
    						$resultsArr[] = '';
    						$resultsArr[] = '';
    						$resultsArr[] = '';
    						$resultsArr[] = $row->payment_firstname;
    						$resultsArr[] = $row->payment_lastname;
    						$resultsArr[] = $row->payment_email;
    						$resultsArr[] = $row->payment_telephone;
    						$resultsArr[] = $row->payment_fax;
    						$resultsArr[] = $row->payment_company;
    						$resultsArr[] = $row->payment_company_id;
    						$resultsArr[] = $row->payment_address_1;
    						$resultsArr[] = $row->payment_address_2;
    						$resultsArr[] = $row->payment_city;
    						$resultsArr[] = $row->payment_postcode;
    						$resultsArr[] = $row->payment_country_name;
    						$resultsArr[] = $row->payment_zone_name;
    						$resultsArr[] = $row->payment_method;
    						$resultsArr[] = $row->payment_method_title;
    						$resultsArr[] = '';
    						$resultsArr[] = $row->shipping_firstname;
    						$resultsArr[] = $row->shipping_lastname;
    						$resultsArr[] = $row->shipping_email;
    						$resultsArr[] = $row->shipping_telephone;
    						$resultsArr[] = $row->shipping_fax;
    						$resultsArr[] = $row->shipping_company;
    						$resultsArr[] = $row->shipping_company_id;
    						$resultsArr[] = $row->shipping_address_1;
    						$resultsArr[] = $row->shipping_address_2;
    						$resultsArr[] = $row->shipping_city;
    						$resultsArr[] = $row->shipping_postcode;
    						$resultsArr[] = $row->shipping_country_name;
    						$resultsArr[] = $row->shipping_zone_name;
    						$resultsArr[] = $row->shipping_method;
    						$resultsArr[] = $row->shipping_method_title;
    						$resultsArr[] = $row->shipping_tracking_number;
    						$resultsArr[] = $row->shipping_tracking_url;
    						$resultsArr[] = '';
    						$resultsArr[] = '';
    						$resultsArr[] = '';
    						$resultsArr[] = '';
    						$resultsArr[] = '';
    						$resultsArr[] = '';
    						$resultsArr[] = '';
    					}
    					$resultsArr[] = $orderProducts[$i]->product_id;
    					$resultsArr[] = $orderProducts[$i]->product_name;
    					for ($j = 0; $m = count($options), $j < $m; $j++)
    					{
    						if ($j > 0)
    						{
    							$optionResultsArr = array();
    							$optionResultsArr[] = '';
    							$optionResultsArr[] = '';
    							$optionResultsArr[] = '';
    							$optionResultsArr[] = '';
    							$optionResultsArr[] = '';
    							$optionResultsArr[] = '';
    							$optionResultsArr[] = '';
    							$optionResultsArr[] = '';
    							$optionResultsArr[] = $row->payment_firstname;
    							$optionResultsArr[] = $row->payment_lastname;
    							$optionResultsArr[] = $row->payment_email;
    							$optionResultsArr[] = $row->payment_telephone;
    							$optionResultsArr[] = $row->payment_fax;
    							$optionResultsArr[] = $row->payment_company;
    							$optionResultsArr[] = $row->payment_company_id;
    							$optionResultsArr[] = $row->payment_address_1;
    							$optionResultsArr[] = $row->payment_address_2;
    							$optionResultsArr[] = $row->payment_city;
    							$optionResultsArr[] = $row->payment_postcode;
    							$optionResultsArr[] = $row->payment_country_name;
    							$optionResultsArr[] = $row->payment_zone_name;
    							$optionResultsArr[] = $row->payment_method;
    							$optionResultsArr[] = $row->payment_method_title;
    							$optionResultsArr[] = '';
    							$optionResultsArr[] = $row->shipping_firstname;
    							$optionResultsArr[] = $row->shipping_lastname;
    							$optionResultsArr[] = $row->shipping_email;
    							$optionResultsArr[] = $row->shipping_telephone;
    							$optionResultsArr[] = $row->shipping_fax;
    							$optionResultsArr[] = $row->shipping_company;
    							$optionResultsArr[] = $row->shipping_company_id;
    							$optionResultsArr[] = $row->shipping_address_1;
    							$optionResultsArr[] = $row->shipping_address_2;
    							$optionResultsArr[] = $row->shipping_city;
    							$optionResultsArr[] = $row->shipping_postcode;
    							$optionResultsArr[] = $row->shipping_country_name;
    							$optionResultsArr[] = $row->shipping_zone_name;
    							$optionResultsArr[] = $row->shipping_method;
    							$optionResultsArr[] = $row->shipping_method_title;
    							$optionResultsArr[] = $row->shipping_tracking_number;
    							$optionResultsArr[] = $row->shipping_tracking_url;
    							$optionResultsArr[] = '';
    							$optionResultsArr[] = '';
    							$optionResultsArr[] = '';
    							$optionResultsArr[] = '';
    							$optionResultsArr[] = '';
    							$optionResultsArr[] = '';
    							$optionResultsArr[] = '';
    							$optionResultsArr[] = '';
    							$optionResultsArr[] = '';
    							$optionResultsArr[]	= $options[$j]->option_name;
    							$optionResultsArr[]	= $options[$j]->option_value;
    							$optionResultsArr[]	= $options[$j]->sku;
    							$optionResultsArr[]	= '';
    							$optionResultsArr[]	= '';
    							$optionResultsArr[]	= '';
    							$optionResultsArr[]	= '';
    							$totalOptionResultsArr[] = $optionResultsArr;
    						}
    						else
    						{
    							$resultsArr[]	= $options[$j]->option_name;
    							$resultsArr[]	= $options[$j]->option_value;
    							$resultsArr[]	= $options[$j]->sku;
    						}
    					}
    					if ($j == 0)
    					{
    						$resultsArr[]	= '';
    						$resultsArr[]	= '';
    						$resultsArr[]	= '';
    					}
    					$resultsArr[] = $orderProducts[$i]->product_sku;
    					$resultsArr[] = $orderProducts[$i]->quantity;
    					$resultsArr[] = $currency->format($orderProducts[$i]->price, $row->currency_code, $row->currency_exchanged_value);
    					$resultsArr[] = $currency->format($orderProducts[$i]->total_price, $row->currency_code, $row->currency_exchanged_value);
    					$csvOutput[] = $resultsArr;
    					if (count($totalOptionResultsArr))
    					{
    						foreach ($totalOptionResultsArr as $optionResultsArr)
    						{
    							$csvOutput[] = $optionResultsArr;
    						}
    					}
    				}
    			}
    			$filename = 'orders_'.date('YmdHis').'.csv';
    			$fp = fopen(JPATH_ROOT.'/media/com_eshop/files/'.$filename, 'w');
    			fprintf($fp,chr(0xEF).chr(0xBB).chr(0xBF));
    			foreach ($csvOutput as $output)
    			{
    				fputcsv($fp, $output, $fieldDelimiter);
    			}
    			fclose($fp);
    			EshopHelper::processDownload(JPATH_ROOT . '/media/com_eshop/files/' . $filename, $filename, true);
    			exit();
		    }
		    //end csv export
		    else {
		        foreach ($rows as $row)
		        {
		            //list product order
		            $orderProductXmlArray = array();
		            $query->clear();
		            $query->select('*')
    		            ->from('#__eshop_orderproducts')
    		            ->where('order_id = ' . intval($row->id));
		            $db->setQuery($query);
		            $orderProducts = $db->loadObjectList();
		            foreach ($orderProducts as $orderProduct)
		            {
    		            $productXmlArray = array(
    		                'sku'							        => $orderProduct->product_sku ,
    		                'product_name'							=> $orderProduct->product_name ,
    		                'quantity'							    => $orderProduct->quantity ,
    		                'unit_price'							=> $orderProduct->price,
    		            );
    		            $orderProductXmlArray[] = $productXmlArray;
		            }
		            //end list product order
		            
		            //start customer xml
		             $customerXml = array(
    		              	    'firstname'						    => $row->firstname,
            		            'lastname'						    => $row->lastname,
            		            'email'						        => $row->email,
            		            'telephone'						    => $row->telephone,
            		            'fax'						        => $row->fax,
    		            );
		            //end customer xml
		            
		            
    		        $orderXmlArray = array();
    		        $xmlarray['Orders']['Order'][] = array(
    		            'order_id'							=> $row->id,
    		            'order_number'						=> $row->order_number,
    		            'invoice_number'				    => $row->invoice_number,
    		            'payment_firstname'					=> $row->payment_firstname,
    		            'payment_lastname'					=> $row->payment_lastname,
    		            'payment_email'						=> $row->payment_email,
    		            'payment_telephone'					=> $row->payment_telephone,
    		            'payment_fax'						=> $row->payment_fax,
    		            'payment_company'					=> $row->payment_company,
    		            'payment_company_id'				=> $row->payment_company_id,
    		            'payment_address_1'					=> $row->payment_address_1,
    		            'payment_address_2'					=> $row->payment_address_2,
    		            'payment_city'						=> $row->payment_city,
    		            'payment_postcode'					=> $row->payment_postcode,
    		            'payment_country_name'				=> $row->payment_country_name,
    		            'payment_zone_name'					=> $row->payment_zone_name,
    		            'payment_method'					=> $row->payment_method,
    		            'payment_method_title'				=> $row->payment_method_title,
    		            'transaction_id'					=> $row->transaction_id,
    		            'shipping_firstname'				=> $row->shipping_firstname,
    		            'shipping_lastname'					=> $row->shipping_lastname,
    		            'shipping_email'					=> $row->shipping_email,
    		            'shipping_telephone'				=> $row->shipping_telephone,
    		            'shipping_fax'						=> $row->shipping_fax,
    		            'shipping_company'					=> $row->shipping_company,
    		            'shipping_company_id'				=> $row->shipping_company_id,
    		            'shipping_address_1'				=> $row->shipping_address_1,
    		            'shipping_address_2'				=> $row->shipping_address_2,
    		            'shipping_city'						=> $row->shipping_city,
    		            'shipping_postcode'					=> $row->shipping_postcode,
    		            'shipping_country_name'				=> $row->shipping_country_name,
    		            'shipping_zone_name'				=> $row->shipping_zone_name,
    		            'shipping_method'					=> $row->shipping_method,
    		            'shipping_method_title'				=> $row->shipping_method_title,
    		            'shipping_tracking_number'			=> $row->shipping_tracking_number,
    		            'shipping_tracking_url'				=> $row->shipping_tracking_url,
    		            'Orderlines'				        => array(
    		                'line'            => $orderProductXmlArray,
    		            ),
                        'customers'                         => $customerXml,    		            
    		            
    		        );
    		        $orderXmlArray[] = $xmlarray;
		        }
		        
		        $filename = 'orders_'.date('YmdHis').'.xml';
		        include_once JPATH_ROOT.'/components/com_eshop/helpers/array2xml.php';
		        $xml = Array2XML::createXML('SConnectData', $orderXmlArray[0]);
		        JFile::write(JPATH_ROOT . '/media/com_eshop/files/' . $filename, $xml->saveXML());
		        EshopHelper::processDownload(JPATH_ROOT . '/media/com_eshop/files/' . $filename, $filename, true);
		        exit();
		    }
		    
		}
		else 
		{
			$mainframe = JFactory::getApplication();
			$mainframe->enqueueMessage(JText::_('ESHOP_NO_DATA_TO_EXPORT'), 'notice');
			$mainframe->redirect('index.php?option=com_eshop&view=reports&layout=orders&date_start=' . $dateStart . '&date_end=' . $dateEnd . '&group_by=' . $groupBy . '&order_status_id=' . $orderStatusId);
		}
	}
	
	/**
	 * 
	 * Function to export google feed
	 */
	function _exportGoogleFeed()
	{
		$input = JFactory::getApplication()->input;
		$fieldDelimiter					= $input->getString('field_delimiter', ',', 'string');
		$language						= $input->getString('language', 'en-GB');
		$removeZeroPriceProducts		= $input->getInt('remove_zero_price_products', '0');
		$removeOutOfStockProducts		= $input->getInt('remove_out_of_stock_products', '0');
		$googleId						= $input->getInt('google_id', '1');
		$googleTitle					= $input->getInt('google_title', '1');
		$googleDescription				= $input->getInt('google_description', '1');
		$googleProductType				= $input->getInt('google_product_type', '1');
		$googleLink						= $input->getInt('google_link', '1');
		$googleMobileLink				= $input->getInt('google_mobile_link', '1');
		$googleImageLink				= $input->getInt('google_image_link', '1');
		$googleAdditionalImageLink		= $input->getInt('google_additional_image_link', '1');
		$googleAvailability				= $input->getInt('google_availability', '1');
		$googlePrice					= $input->getInt('google_price', '1');
		$googleSalePrice				= $input->getInt('google_sale_price', '1');
		$googleMpn						= $input->getInt('google_mpn', '1');
		$googleBrand					= $input->getInt('google_brand', '1');
		$googleShippingWeight			= $input->getInt('google_shipping_weight', '1');
		
		$db = JFactory::getDbo();
		$languageSql = $db->quote($language);
		$query = $db->getQuery(true);
		$query->select('a.*, b.*')
			->from('#__eshop_products AS a')
			->innerJoin('#__eshop_productdetails AS b ON (a.id = b.product_id)')
			->where('b.language = ' . $languageSql );
		
		if ($removeZeroPriceProducts)
		{
			$query->where('a.product_price > 0');
		}
		
		if ($removeOutOfStockProducts)
		{
			$query->where('a.product_quantity > 0');
		}
		
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		$csvOutput = array();
		
		if (count($rows))
		{
			$resultsArr = array();
			
			if ($googleId)
			{
				$resultsArr[] = 'id';
			}
			
			if ($googleTitle)
			{
				$resultsArr[] = 'title';
			}
			
			if ($googleDescription)
			{
				$resultsArr[] = 'description';
			}
			
			if ($googleProductType)
			{
				$resultsArr[] = 'product_type';
			}
			
			if ($googleLink)
			{
				$resultsArr[] = 'link';
			}
			
			if ($googleMobileLink)
			{
				$resultsArr[] = 'mobile_link';
			}
			
			if ($googleImageLink)
			{
				$resultsArr[] = 'image_link';
			}
			
			if ($googleAdditionalImageLink)
			{
				$resultsArr[] = 'additional_image_link';
			}
			
			if ($googleAvailability)
			{
				$resultsArr[] = 'availability';
			}
			
			if ($googlePrice)
			{
				$resultsArr[] = 'price';
			}
			
			if ($googleSalePrice)
			{
				$resultsArr[] = 'sale_price';
			}
			
			if ($googleMpn)
			{
				$resultsArr[] = 'mpn';
			}
			
			if ($googleBrand)
			{
				$resultsArr[] = 'brand';
			}
			
			if ($googleShippingWeight)
			{
				$resultsArr[] = 'shipping_weight';
			}
	
			$csvOutput[] = $resultsArr;
				
			foreach ($rows as $row)
			{
				//Get additional images for product
				$query->clear();
				$query->select('image')
					->from('#__eshop_productimages')
					->where('product_id = ' . $row->product_id);
				$db->setQuery($query);
				$images = $db->loadColumn();
		
				//Get product manufacturer
				$manufacturer = EshopHelper::getProductManufacturer($row->product_id, $language);
				
				//Get product category
				$categoryId = EshopHelper::getProductCategory($row->product_id);
				
				$resultsArr = array();
				
				if ($googleId)
				{
					$resultsArr[] = $row->product_id;
				}
					
				if ($googleTitle)
				{
					$resultsArr[] = $row->product_name;
				}
					
				if ($googleDescription)
				{
					$resultsArr[] = $row->product_desc;
				}
					
				if ($googleProductType)
				{
					if ($categoryId > 0)
					{
						$resultsArr[] = implode('/', EshopHelper::getCategoryNamePath($categoryId, $language));
					}
					else 
					{
						$resultsArr[] = '';
					}
				}
					
				if ($googleLink)
				{
					$resultsArr[] = EshopHelper::getSiteUrl() . EshopRoute::getProductRoute($row->product_id, $categoryId);
				}
					
				if ($googleMobileLink)
				{
					$resultsArr[] = EshopHelper::getSiteUrl() . EshopRoute::getProductRoute($row->product_id, $categoryId);
				}
					
				if ($googleImageLink)
				{
					$resultsArr[] = EshopHelper::getSiteUrl() . 'media/com_eshop/products/' . $row->product_image;
				}
					
				if ($googleAdditionalImageLink)
				{
					if (count($images))
					{
						$resultsArr[] = EshopHelper::getSiteUrl() . 'media/com_eshop/products/' . $images[0];
					}
					else 
					{
						$resultsArr[] = '';
					}
				}
					
				if ($googleAvailability)
				{
					if ($row->product_quantity > 0)
					{
						$resultsArr[] = 'in stock';
					}
					else 
					{
						$resultsArr[] = 'out of stock';
					}
				}
					
				$defaultCurrency = EshopHelper::getConfigValue('default_currency_code');
				if ($googlePrice)
				{
					$resultsArr[] = number_format($row->product_price, 2) . ' ' . $defaultCurrency;
				}
					
				if ($googleSalePrice)
				{
					$resultsArr[] = number_format($row->product_price, 2) . ' ' . $defaultCurrency;
				}
					
				if ($googleMpn)
				{
					$resultsArr[] = $row->product_sku;
				}
					
				if ($googleBrand)
				{
					if (is_object($manufacturer))
					{
						$resultsArr[] = $manufacturer->manufacturer_name;
					}
					else 
					{
						$resultsArr[] = '';
					}
				}
					
				if ($googleShippingWeight)
				{
					$eshopWeight = new EshopWeight();
					$resultsArr[] = $eshopWeight->format($row->product_weight, $row->product_weight_id);
				}
					
				$csvOutput[] = $resultsArr;
			}
			
			$filename = 'google_feed_'.date('YmdHis').'.csv';
			$fp = fopen(JPATH_ROOT . '/media/com_eshop/files/' . $filename, 'w');
			fprintf($fp,chr(0xEF).chr(0xBB).chr(0xBF));
			foreach ($csvOutput as $output)
			{
				fputcsv($fp, $output, $fieldDelimiter);
			}
			fclose($fp);
			EshopHelper::processDownload(JPATH_ROOT . '/media/com_eshop/files/' . $filename, $filename, true);
			exit();
		}
		else
		{
			$mainframe = JFactory::getApplication();
			$mainframe->enqueueMessage(JText::_('ESHOP_NO_DATA_TO_EXPORT'), 'notice');
			$mainframe->redirect('index.php?option=com_eshop&view=exports');
		}
	}
	
	/**
	 * Cancel the exports
	 *
	 */
	function cancel()
	{
		$this->setRedirect('index.php?option=com_eshop&view=dashboard');
	}
}