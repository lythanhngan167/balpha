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

/**
 * HTML View class for EShop component
 *
 * @static
 * @package        Joomla
 * @subpackage     EShop
 * @since          1.5
 */
class EShopViewCompare extends EShopView
{
	public function display($tpl = null)
	{
		$baseUri = JUri::base(true);
		JFactory::getDocument()->addStyleSheet($baseUri . '/components/com_eshop/assets/colorbox/colorbox.css');

		$tax      = new EshopTax(EshopHelper::getConfig());
		$currency = new EshopCurrency();

		$session                = JFactory::getSession();
		$compare                = $session->get('compare');
		$products               = array();
		$attributeGroups        = EshopHelper::getAttributeGroups(JFactory::getLanguage()->getTag());
		$visibleAttributeGroups = array();


		$this->setPageTitle(JText::_('ESHOP_COMPARE'));

		if (count($compare))
		{
		    $fieldTitle = array();
		    
			foreach ($compare as $productId)
			{
				$productInfo = EshopHelper::getProduct($productId, JFactory::getLanguage()->getTag());

				if (is_object($productInfo))
				{
					// Image
					$imageSizeFunction = EshopHelper::getConfigValue('compare_image_size_function', 'resizeImage');

					if ($productInfo->product_image && JFile::exists(JPATH_ROOT . '/media/com_eshop/products/' . $productInfo->product_image))
					{
						if (EshopHelper::getConfigValue('product_use_image_watermarks'))
						{
							$watermarkImage = EshopHelper::generateWatermarkImage(JPATH_ROOT . '/media/com_eshop/products/' . $productInfo->product_image);
							$productImage   = $watermarkImage;
						}
						else
						{
							$productImage = $productInfo->product_image;
						}

						$image = call_user_func_array(array('EshopHelper', $imageSizeFunction), array($productImage, JPATH_ROOT . '/media/com_eshop/products/', EshopHelper::getConfigValue('image_compare_width'), EshopHelper::getConfigValue('image_compare_height')));
					}
					else
					{
						$image = call_user_func_array(array('EshopHelper', $imageSizeFunction), array('no-image.png', JPATH_ROOT . '/media/com_eshop/products/', EshopHelper::getConfigValue('image_compare_width'), EshopHelper::getConfigValue('image_compare_height')));
					}

					$image = $baseUri . '/media/com_eshop/products/resized/' . $image;

					// Availability
					if ($productInfo->product_quantity <= 0)
					{
						$availability = EshopHelper::getStockStatusName($productInfo->product_stock_status_id ? $productInfo->product_stock_status_id : EshopHelper::getConfigValue('stock_status_id'), JFactory::getLanguage()->getTag());
					}
					elseif (EshopHelper::getConfigValue('stock_display'))
					{
						$availability = $productInfo->product_quantity;
					}
					else
					{
						$availability = JText::_('ESHOP_IN_STOCK');
					}

					// Manufacturer
					$manufacturer = EshopHelper::getProductManufacturer($productId, JFactory::getLanguage()->getTag());

					// Price
					$productPriceArray = EshopHelper::getProductPriceArray($productId, $productInfo->product_price);

					if ($productPriceArray['salePrice'] >= 0)
					{
						$basePrice = $currency->format($tax->calculate($productPriceArray['basePrice'], $productInfo->product_taxclass_id, EshopHelper::getConfigValue('tax')));
						$salePrice = $currency->format($tax->calculate($productPriceArray['salePrice'], $productInfo->product_taxclass_id, EshopHelper::getConfigValue('tax')));
					}
					else
					{
						$basePrice = $currency->format($tax->calculate($productPriceArray['basePrice'], $productInfo->product_taxclass_id, EshopHelper::getConfigValue('tax')));
						$salePrice = 0;
					}

					// Atrributes
					$productAttributes = array();

					for ($j = 0; $m = count($attributeGroups), $j < $m; $j++)
					{
						$attributes = EshopHelper::getAttributes($productId, $attributeGroups[$j]->id, JFactory::getLanguage()->getTag());

						if (count($attributes))
						{
							$visibleAttributeGroups[$attributeGroups[$j]->id]['id']                  = $attributeGroups[$j]->id;
							$visibleAttributeGroups[$attributeGroups[$j]->id]['attributegroup_name'] = $attributeGroups[$j]->attributegroup_name;

							foreach ($attributes as $attribute)
							{
								if (isset($visibleAttributeGroups[$attributeGroups[$j]->id]['attribute_name']))
								{
									if (!in_array($attribute->attribute_name, $visibleAttributeGroups[$attributeGroups[$j]->id]['attribute_name']))
									{
										$visibleAttributeGroups[$attributeGroups[$j]->id]['attribute_name'][] = $attribute->attribute_name;
									}
								}
								else
								{
									$visibleAttributeGroups[$attributeGroups[$j]->id]['attribute_name'][] = $attribute->attribute_name;
								}

								$productAttributes[$attributeGroups[$j]->id]['value'][$attribute->attribute_name] = $attribute->value;
							}
						}
					}
					
					//Custom fields handle
					$productFieldValue = array();
					
					if (EshopHelper::getConfigValue('product_custom_fields'))
					{
					    EshopHelper::prepareCustomFieldsData(array($productInfo), true);
					    
					    if (!count($fieldTitle))
					    {
					        foreach ($productInfo->paramData as $param)
					        {
					            $fieldTitle[] = $param['title'];
					        }
					    }
					    
					    foreach ($productInfo->paramData as $param)
					    {
					        $productFieldValue[] = $param['value'];
					    }
					}

					$products[$productId] = array(
						'product_id'             => $productId,
						'product_sku'            => $productInfo->product_sku,
						'product_name'           => $productInfo->product_name,
						'product_short_desc'     => $productInfo->product_short_desc,
						'image'                  => $image,
						'product_desc'           => substr(strip_tags(html_entity_decode($productInfo->product_desc, ENT_QUOTES, 'UTF-8')), 0, 200) . '...',
						'base_price'             => $basePrice,
						'sale_price'             => $salePrice,
						'product_call_for_price' => $productInfo->product_call_for_price,
						'availability'           => $availability,
						'rating'                 => EshopHelper::getProductRating($productId),
						'num_reviews'            => count(EshopHelper::getProductReviews($productId)),
						'weight'                 => number_format($productInfo->product_weight, 2) . EshopHelper::getWeightUnit($productInfo->product_weight_id, JFactory::getLanguage()->getTag()),
						'length'                 => number_format($productInfo->product_length, 2) . EshopHelper::getLengthUnit($productInfo->product_length_id, JFactory::getLanguage()->getTag()),
						'width'                  => number_format($productInfo->product_width, 2) . EshopHelper::getLengthUnit($productInfo->product_length_id, JFactory::getLanguage()->getTag()),
						'height'                 => number_format($productInfo->product_height, 2) . EshopHelper::getLengthUnit($productInfo->product_length_id, JFactory::getLanguage()->getTag()),
						'manufacturer'           => isset($manufacturer->manufacturer_name) ? $manufacturer->manufacturer_name : '',
						'attributes'             => $productAttributes,
					    'productFieldValue'      => $productFieldValue
					);
				}
			}
		}

		if ($session->get('success'))
		{
			$this->success = $session->get('success');
			$session->clear('success');
		}

		$this->visibleAttributeGroups = $visibleAttributeGroups;
		$this->products               = $products;
		$this->fieldTitle             = $fieldTitle;
		$this->bootstrapHelper        = new EshopHelperBootstrap(EshopHelper::getConfigValue('twitter_bootstrap_version'));

		parent::display($tpl);
	}
}