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
class plgContentEshopCategory extends JPlugin
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
			if (strpos($article->text, 'eshopcategory') === false)
			{
				return true;
			}
			$regex = "#{eshopcategory (\d+)}#s";
			$article->text = preg_replace_callback($regex, array(&$this, 'displayProducts'), $article->text);
		}
		return true;
	}

	/**
	 * Replace callback function
	 * 
	 * @param array $matches
	 */
	function displayProducts($matches)
	{
		//Require the controller
		require_once JPATH_ROOT . '/administrator/components/com_eshop/libraries/defines.php';
		require_once JPATH_ROOT . '/administrator/components/com_eshop/libraries/inflector.php';
		require_once JPATH_ROOT . '/administrator/components/com_eshop/libraries/autoload.php';
		require_once JPATH_ADMINISTRATOR . '/components/com_eshop/libraries/rad/bootstrap.php';
		jimport('joomla.filesystem.file');
		$categoryId = $matches[1];
		$document = JFactory::getDocument();
		$currency = new EshopCurrency();
		$config = EshopHelper::getConfig();
		$tax = new EshopTax($config);
		$category = EshopHelper::getCategory($categoryId);
		//Added by tuanpn, to use share common layout
		$productsPerRow = $category->products_per_row;
		if (!$productsPerRow)
		{
			$productsPerRow = EshopHelper::getConfigValue('items_per_row', 3);
		}	
		$categoriesNavigation = EshopHelper::getCategoriesNavigation($category->id);
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
		$Itemid = JRequest::getInt('Itemid');
		if (!$Itemid)
		{
			$Itemid = EshopHelper::getItemid();
		}
		$eshopModel = new EShopModel();
		$categoryModel = $eshopModel->getModel('category');
		$products = $categoryModel->reset()->id($categoryId)->limitstart(0)->limit($this->params->get('number_product', 15))->getData();
		
		$category		= EshopHelper::getCategory($categoryId, true, true);
		$categoryLayout	= '';

		$bootstrapHelper      = new EshopHelperBootstrap(EshopHelper::getConfigValue('twitter_bootstrap_version'));
		$rowFluidClass        = $bootstrapHelper->getClassMapping('row-fluid');
		$span4Class           = $bootstrapHelper->getClassMapping('span4');
		$span8Class           = $bootstrapHelper->getClassMapping('span8');
		$span12Class           = $bootstrapHelper->getClassMapping('span12');
		$imgPolaroid          = $bootstrapHelper->getClassMapping('img-polaroid');
		
		if (EshopHelper::getConfigValue('show_category_image') || EshopHelper::getConfigValue('show_category_desc'))
		{
			$categoryLayout .= '<div class="' . $rowFluidClass . '">';
			
			if (EshopHelper::getConfigValue('show_category_image'))
			{
				$categoryLayout .= '<div class="' . $span4Class . '"><img class="' . $imgPolaroid . '" src="' . $category->image . '" title="' . ($category->category_page_title != '' ? $category->category_page_title : $category->category_name) . '" alt="' . ($category->category_page_title != '' ? $category->category_page_title : $category->category_name) . '" /></div>';
			}
			
			if (EshopHelper::getConfigValue('show_category_desc'))
			{
				$categoryLayout .= '<div class="' . (EshopHelper::getConfigValue('show_category_image') ? $span8Class : $span12Class) . '">' . $category->category_desc . '</div>';
			}
			
			$categoryLayout .= '</div><hr />';
		}
		
		$productsListLayout = EshopHtmlHelper::loadCommonLayout('common/products.php', array('products' => $products, 'productsPerRow' => $productsPerRow, 'categoriesNavigation' => $categoriesNavigation, 'category' => $category, 'currency' => $currency, 'config' => $config, 'tax' => $tax, 'Itemid' => $Itemid, 'catId' => $categoryId, 'showSortOptions' => false, 'bootstrapHelper' => $bootstrapHelper));
		
		return $categoryLayout . $productsListLayout;
	}
}