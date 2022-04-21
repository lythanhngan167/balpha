<?php
/**
 * @version		1.3.1
 * @package		Joomla
 * @subpackage	EShop
 * @author  	Giang Dinh Truong
 * @copyright	Copyright (C) 2012 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die();
class plgContentEshopProduct extends JPlugin
{

	function onContentPrepare($context, &$article, &$params, $limitstart)
	{
		if (file_exists(JPATH_ROOT . '/components/com_eshop/eshop.php'))
		{
			$app = JFactory::getApplication();
			if ($app->getName() != 'site')
			{
				return;
			}
			if (strpos($article->text, 'eshopproduct') === false)
			{
				return true;
			}
			$regex = "#{eshopproduct (\d+)}#s";
			$article->text = preg_replace_callback($regex, array(&$this, 'displayProduct'), $article->text);
		}
		
		return true;
	}

	/**
	 * Replace callback function
	 * 
	 * @param array $matches
	 */
	function displayProduct($matches)
	{
		//Require the controller
		require_once JPATH_ROOT . '/administrator/components/com_eshop/libraries/defines.php';
		require_once JPATH_ROOT . '/administrator/components/com_eshop/libraries/inflector.php';
		require_once JPATH_ROOT . '/administrator/components/com_eshop/libraries/autoload.php';
		require_once JPATH_ADMINISTRATOR . '/components/com_eshop/libraries/rad/bootstrap.php';
		jimport('joomla.filesystem.file') ;
		$document = JFactory::getDocument();
		$currency = new EshopCurrency();
		$config = EshopHelper::getConfig();
		$tax = new EshopTax($config);
		$language = JFactory::getLanguage();
		$tag = $language->getTag();
		if (!$tag)
			$tag = 'en-GB';
		$language->load('com_eshop', JPATH_ROOT, $tag);
		//Load javascript and css
		$theme = EshopHelper::getConfigValue('theme');
		if (JFile::exists(JPATH_ROOT.'/components/com_eshop/themes/' . $theme . '/css/style.css'))
		{
			$document->addStyleSheet(EshopHelper::getSiteUrl().'components/com_eshop/themes/' . $theme . '/css/style.css');
		}
		else 
		{
			$document->addStyleSheet(EshopHelper::getSiteUrl().'components/com_eshop/themes/default/css/style.css');
		}
		$document->addStyleSheet(EshopHelper::getSiteUrl().'components/com_eshop/assets/colorbox/colorbox.css');
		// Load Bootstrap CSS and JS
		if (EshopHelper::getConfigValue('load_bootstrap_css'))
		{
			EshopHelper::loadBootstrapCss();
		}
		if (EshopHelper::getConfigValue('load_bootstrap_js'))
		{
			EshopHelper::loadBootstrapJs();
		}
		JHtml::_('script', EshopHelper::getSiteUrl(). 'components/com_eshop/assets/js/noconflict.js', false, false);
		JHtml::_('script', EshopHelper::getSiteUrl(). 'components/com_eshop/assets/js/eshop.js', false, false);
		$productId = $matches[1];
		//echo $productId;
		$viewConfig['name'] = 'product';
		$viewConfig['base_path'] = JPATH_ROOT . '/components/com_eshop/themes/' . $theme . '/views/product';
		$viewConfig['template_path'] = JPATH_ROOT . '/components/com_eshop/themes/' . $theme . '/views/product';
		$viewConfig['layout'] = 'default';
		$view = new JViewLegacy($viewConfig);
		
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('a.*, b.product_name, b.product_alias, b.product_desc, b.product_short_desc, b.product_page_title, b.product_page_heading, b.meta_key, b.meta_desc, b.tab1_title, b.tab1_content, b.tab2_title, b.tab2_content, b.tab3_title, b.tab3_content, b.tab4_title, b.tab4_content, b.tab5_title, b.tab5_content')
			->from('#__eshop_products AS a')
			->innerJoin('#__eshop_productdetails AS b ON (a.id = b.product_id)')
			->where('a.id = ' . intval($productId))
			->where('b.language = "' . $tag . '"');
		$db->setQuery($query);
		$item = $db->loadObject();
		if (!is_object($item))
		{
			return '';
		}
		else 
		{
			//Set session for viewed products
			$session = JFactory::getSession();
			$viewedProductIds = $session->get('viewed_product_ids');
			if (!count($viewedProductIds))
			{
				$viewedProductIds = array();
			}
			if (!in_array($productId, $viewedProductIds))
			{
				$viewedProductIds[] = $productId;
			}
			$session->set('viewed_product_ids', $viewedProductIds);
			// Update hits for product
			EshopHelper::updateHits($productId, 'products');
			
			$additionalImageSizeFunction = EshopHelper::getConfigValue('additional_image_size_function', 'resizeImage');
			$thumbImageSizeFunction = EshopHelper::getConfigValue('thumb_image_size_function', 'resizeImage');
			$popupImageSizeFunction = EshopHelper::getConfigValue('popup_image_size_function', 'resizeImage');
			$relatedImageSizeFunction = EshopHelper::getConfigValue('related_image_size_function', 'resizeImage');
			// Main image resize
			if ($item->product_image && JFile::exists(JPATH_ROOT.'/media/com_eshop/products/' . $item->product_image))
			{
				$smallThumbImage = call_user_func_array(array('EshopHelper', $additionalImageSizeFunction), array($item->product_image, JPATH_ROOT . '/media/com_eshop/products/', EshopHelper::getConfigValue('image_additional_width'), EshopHelper::getConfigValue('image_additional_height')));
				$thumbImage = call_user_func_array(array('EshopHelper', $thumbImageSizeFunction), array($item->product_image, JPATH_ROOT . '/media/com_eshop/products/', EshopHelper::getConfigValue('image_thumb_width'), EshopHelper::getConfigValue('image_thumb_height')));
				$popupImage = call_user_func_array(array('EshopHelper', $popupImageSizeFunction), array($item->product_image, JPATH_ROOT . '/media/com_eshop/products/', EshopHelper::getConfigValue('image_popup_width'), EshopHelper::getConfigValue('image_popup_height')));
			}
			else
			{
				$smallThumbImage = call_user_func_array(array('EshopHelper', $additionalImageSizeFunction), array('no-image.png', JPATH_ROOT . '/media/com_eshop/products/', EshopHelper::getConfigValue('image_additional_width'), EshopHelper::getConfigValue('image_additional_height')));
				$thumbImage = call_user_func_array(array('EshopHelper', $thumbImageSizeFunction), array('no-image.png', JPATH_ROOT . '/media/com_eshop/products/', EshopHelper::getConfigValue('image_thumb_width'), EshopHelper::getConfigValue('image_thumb_height')));
				$popupImage = call_user_func_array(array('EshopHelper', $popupImageSizeFunction), array('no-image.png', JPATH_ROOT . '/media/com_eshop/products/', EshopHelper::getConfigValue('image_popup_width'), EshopHelper::getConfigValue('image_popup_height')));
			}
			$item->small_thumb_image = JUri::base(true) . '/media/com_eshop/products/resized/' . $smallThumbImage;
			$item->thumb_image = JUri::base(true) . '/media/com_eshop/products/resized/' . $thumbImage;
			$item->popup_image = JUri::base(true) . '/media/com_eshop/products/resized/' . $popupImage;
			// Product availability
			if ($item->product_quantity <= 0)
			{
				$availability = EshopHelper::getStockStatusName(EshopHelper::getConfigValue('stock_status_id'), $tag);
			}
			elseif (EshopHelper::getConfigValue('stock_display'))
			{
				$availability = $item->product_quantity;
			}
			else
			{
				$availability = JText::_('ESHOP_IN_STOCK');
			}
			$item->availability = $availability;
			// Product tags
			$productTags       = EshopHelper::getProductTags($item->id);
			
			//Product attachments
			$query = $db->getQuery(true);
			$query->select('*')
				->from('#__eshop_productattachments')
				->where('product_id = ' . intval($item->id))
				->order('ordering');
			$db->setQuery($query);
			$productAttachments = $db->loadObjectList();
			$item->product_desc = JHtml::_('content.prepare', $item->product_desc);
			
			if ($item->tab1_title != '' && $item->tab1_content != '')
			{
				$item->tab1_content = JHtml::_('content.prepare', $item->tab1_content);
			}
			
			if ($item->tab2_title != '' && $item->tab2_content != '')
			{
				$item->tab2_content = JHtml::_('content.prepare', $item->tab2_content);
			}
			
			if ($item->tab3_title != '' && $item->tab3_content != '')
			{
				$item->tab3_content = JHtml::_('content.prepare', $item->tab3_content);
			}
			
			if ($item->tab4_title != '' && $item->tab4_content != '')
			{
				$item->tab4_content = JHtml::_('content.prepare', $item->tab4_content);
			}
			
			if ($item->tab5_title != '' && $item->tab5_content != '')
			{
				$item->tab5_content = JHtml::_('content.prepare', $item->tab5_content);
			}
			
			// Get information related to this current product
			$productImages = EshopHelper::getProductImages($productId);
			// Additional images resize
			for ($i = 0; $n = count($productImages), $i < $n; $i++)
			{
				if ($productImages[$i]->image && JFile::exists(JPATH_ROOT.'/media/com_eshop/products/' . $productImages[$i]->image))
				{
					$smallThumbImage = call_user_func_array(array('EshopHelper', $additionalImageSizeFunction), array($productImages[$i]->image, JPATH_ROOT . '/media/com_eshop/products/', EshopHelper::getConfigValue('image_additional_width'), EshopHelper::getConfigValue('image_additional_height')));
					$thumbImage = call_user_func_array(array('EshopHelper', $thumbImageSizeFunction), array($productImages[$i]->image, JPATH_ROOT . '/media/com_eshop/products/', EshopHelper::getConfigValue('image_thumb_width'), EshopHelper::getConfigValue('image_thumb_height')));
					$popupImage = call_user_func_array(array('EshopHelper', $popupImageSizeFunction), array($productImages[$i]->image, JPATH_ROOT . '/media/com_eshop/products/', EshopHelper::getConfigValue('image_popup_width'), EshopHelper::getConfigValue('image_popup_height')));
				}
				else
				{
					$smallThumbImage = call_user_func_array(array('EshopHelper', $additionalImageSizeFunction), array('no-image.png', JPATH_ROOT . '/media/com_eshop/products/', EshopHelper::getConfigValue('image_additional_width'), EshopHelper::getConfigValue('image_additional_height')));
					$thumbImage = call_user_func_array(array('EshopHelper', $thumbImageSizeFunction), array('no-image.png', JPATH_ROOT . '/media/com_eshop/products/', EshopHelper::getConfigValue('image_thumb_width'), EshopHelper::getConfigValue('image_thumb_height')));
					$popupImage = call_user_func_array(array('EshopHelper', $popupImageSizeFunction), array('no-image.png', JPATH_ROOT . '/media/com_eshop/products/', EshopHelper::getConfigValue('image_popup_width'), EshopHelper::getConfigValue('image_popup_height')));
				}
				$productImages[$i]->small_thumb_image = JUri::base(true) . '/media/com_eshop/products/resized/' . $smallThumbImage;
				$productImages[$i]->thumb_image = JUri::base(true) . '/media/com_eshop/products/resized/' . $thumbImage;
				$productImages[$i]->popup_image = JUri::base(true) . '/media/com_eshop/products/resized/' . $popupImage;
			}
			$discountPrices = EshopHelper::getDiscountPrices($productId);
			$productOptions = EshopHelper::getProductOptions($productId, $tag);
			$hasSpecification = false;
			$attributeGroups = EshopHelper::getAttributeGroups($tag);
			$productAttributes = array();
			for ($i = 0; $n = count($attributeGroups), $i < $n; $i++)
			{
				$productAttributes[] = EshopHelper::getAttributes($productId, $attributeGroups[$i]->id, $tag);
				if (count($productAttributes[$i])) $hasSpecification = true;
			}
			$productRelations = EshopHelper::getProductRelations($productId, $tag);
			// Related products images resize
			for ($i = 0; $n = count($productRelations), $i < $n; $i++)
			{
				if ($productRelations[$i]->product_image && JFile::exists(JPATH_ROOT.'/media/com_eshop/products/' . $productRelations[$i]->product_image))
				{
					$thumbImage = call_user_func_array(array('EshopHelper', $relatedImageSizeFunction), array($productRelations[$i]->product_image, JPATH_ROOT . '/media/com_eshop/products/', EshopHelper::getConfigValue('image_related_width'), EshopHelper::getConfigValue('image_related_height')));
				}
				else
				{
					$thumbImage = call_user_func_array(array('EshopHelper', $relatedImageSizeFunction), array('no-image.png', JPATH_ROOT . '/media/com_eshop/products/', EshopHelper::getConfigValue('image_related_width'), EshopHelper::getConfigValue('image_related_height')));
				}
				$productRelations[$i]->thumb_image = JUri::base(true) . '/media/com_eshop/products/resized/' . $thumbImage;
			}
			if (EshopHelper::getConfigValue('allow_reviews'))
			{
				$productReviews = EshopHelper::getProductReviews($productId);
				$view->assignRef('productReviews', $productReviews);
			}
			$tax = new EshopTax(EshopHelper::getConfig());
			
			if (EshopHelper::getConfigValue('social_enable'))
			{
				EshopHelper::loadShareScripts($item);
			}
			
			//Custom fields handle
			if (EshopHelper::getConfigValue('product_custom_fields'))
			{
				EshopHelper::prepareCustomFieldsData(array($item));
			}
			
			$view->assignRef('currency', $currency);
			$view->assignRef('item', $item);
			$view->assignRef('productImages', $productImages);
			$view->assignRef('discountPrices', $discountPrices);
			$view->assignRef('productOptions', $productOptions);
			$view->assignRef('hasSpecification', $hasSpecification);
			$view->assignRef('attributeGroups', $attributeGroups);
			$view->assignRef('productAttributes', $productAttributes);
			$view->assignRef('productRelations', $productRelations);
			$view->assignRef('productAttachments', $productAttachments);
			$view->assignRef('productTags', $productTags);
			$manufacturer = EshopHelper::getProductManufacturer($productId, $tag);
			$view->assignRef('manufacturer', $manufacturer);
			$view->assignRef('tax', $tax);
			
			// Preparing rating html
			$ratingHtml = '<b>' . JText::_('ESHOP_BAD') . '</b>';
			for ($i = 1; $i <= 5; $i++)
			{
				$ratingHtml .= '<input type="radio" name="rating" value="' . $i . '" style="width: 25px;" />';
			}
			$ratingHtml .= '<b>' . JText::_('ESHOP_EXCELLENT') . '</b>';
			$view->assignRef('ratingHtml', $ratingHtml);
			$productsNavigation = array('', '');
			$view->assignRef('productsNavigation', $productsNavigation);
			$labels = EshopHelper::getProductLabels($item->id);
			$view->assignRef('labels', $labels);
			
			//Captcha
			$showCaptcha = 0;
			if (EshopHelper::getConfigValue('enable_reviews_captcha'))
			{
				$captchaPlugin = JFactory::getApplication()->getParams()->get('captcha', JFactory::getConfig()->get('captcha'));
				if ($captchaPlugin == 'recaptcha')
				{
					$showCaptcha = 1;
					$view->assignRef('captcha', JCaptcha::getInstance($captchaPlugin)->display('dynamic_recaptcha_1', 'dynamic_recaptcha_1', 'required'));
				}
			}
			$view->assignRef('showCaptcha', $showCaptcha);
			$bootstrapHelper      = new EshopHelperBootstrap(EshopHelper::getConfigValue('twitter_bootstrap_version'));
			$view->assignRef('bootstrapHelper', $bootstrapHelper);
			ob_start();
			$view->display();
			$text = ob_get_contents();
			ob_end_clean();
			return $text;
		}
	}
}