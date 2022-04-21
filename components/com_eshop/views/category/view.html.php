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
use api\model\Sconfig;

defined('_JEXEC') or die;

/**
 * HTML View class for EShop component
 *
 * @static
 * @package        Joomla
 * @subpackage     EShop
 * @since          1.5
 */
class EShopViewCategory extends EShopView
{
	public function display($tpl = null)
	{
		$app      = JFactory::getApplication();
		$session  = JFactory::getSession();
		$model    = $this->getModel();
		$state    = $model->getState();
		$category = EshopHelper::getCategory($state->id, true, true);

		if (!is_object($category))
		{
			// Requested category does not existed.
			$session->set('warning', JText::_('ESHOP_CATEGORY_DOES_NOT_EXIST'));
			$app->redirect(JRoute::_(EshopRoute::getViewRoute('categories')));
		}
		else
		{
            $this->limitLocation = false;
		    // Check limit location
            $location = $session->get('location', 50);
            $sconfig = new Sconfig();
            if(in_array($location, $sconfig->shippingLimitArea)){
                if(in_array($category->id, $sconfig->shippingLimitCategory)){
                    $this->limitLocation = true;
                }
            }
			$document = JFactory::getDocument();
			$baseUri  = JUri::base(true);
			$document->addStyleSheet($baseUri . '/components/com_eshop/assets/colorbox/colorbox.css');
			$document->addStyleSheet($baseUri . '/components/com_eshop/assets/css/labels.css');

			//Handle breadcrump
			$menu     = $app->getMenu();
			$menuItem = $menu->getActive();
			if ($menuItem)
			{
				if (isset($menuItem->query['view']) && ($menuItem->query['view'] == 'frontpage' || $menuItem->query['view'] == 'categories' || $menuItem->query['view'] == 'category'))
				{
					$parentId = isset($menuItem->query['id']) ? (int) $menuItem->query['id'] : '0';

					if ($category->id)
					{
						$pathway = $app->getPathway();
						$paths   = EshopHelper::getCategoriesBreadcrumb($category->id, $parentId);

						for ($i = count($paths) - 1; $i >= 0; $i--)
						{
							$path    = $paths[$i];
							$pathUrl = EshopRoute::getCategoryRoute($path->id);
							$pathway->addItem($path->category_name, $pathUrl);
						}
					}
				}
			}

			// Update hits for category
			EshopHelper::updateHits($category->id, 'categories');

			// Set title of the page
			$categoryPageTitle = $category->category_page_title != '' ? $category->category_page_title : $category->category_name;
			$this->setPageTitle($categoryPageTitle);

			// Set metakey and metadesc
			$metaKey  = $category->meta_key;
			$metaDesc = $category->meta_desc;

			if ($metaKey)
			{
				$document->setMetaData('keywords', $metaKey);
			}

			if ($metaDesc)
			{
				$document->setMetaData('description', $metaDesc);
			}



			//Get subcategories
			JLoader::register('EshopModelCategories', JPATH_ROOT . '/components/com_eshop/models/categories.php');
			$subCategories = RADModel::getInstance('Categories', 'EshopModel', array('remember_states' => false))
				->limitstart(0)
				->limit(0)
				->filter_order('a.ordering')
				->id($state->id)
				->getData();

			//Sort options
			$sortOptions = EshopHelper::getConfigValue('sort_options');
			$sortOptions = explode(',', $sortOptions);
			$sortValues  = array(
				'a.ordering-ASC',
				'a.ordering-DESC',
				'b.product_name-ASC',
				'b.product_name-DESC',
				'a.product_sku-ASC',
				'a.product_sku-DESC',
				'a.product_price-ASC',
				'a.product_price-DESC',
				'a.product_length-ASC',
				'a.product_length-DESC',
				'a.product_width-ASC',
				'a.product_width-DESC',
				'a.product_height-ASC',
				'a.product_height-DESC',
				'a.product_weight-ASC',
				'a.product_weight-DESC',
				'a.product_quantity-ASC',
				'a.product_quantity-DESC',
				'b.product_short_desc-ASC',
				'b.product_short_desc-DESC',
				'b.product_desc-ASC',
				'b.product_desc-DESC',
				'product_rates-ASC',
				'product_rates-DESC',
				'product_reviews-ASC',
				'product_reviews-DESC',
				'a.id-DESC',
				'a.id-ASC',
				'product_best_sellers-DESC',
			    'RAND()'
			);

			$sortTexts = array(
				JText::_('ESHOP_SORTING_PRODUCT_ORDERING_ASC'),
				JText::_('ESHOP_SORTING_PRODUCT_ORDERING_DESC'),
				JText::_('ESHOP_SORTING_PRODUCT_NAME_ASC'),
				JText::_('ESHOP_SORTING_PRODUCT_NAME_DESC'),
				JText::_('ESHOP_SORTING_PRODUCT_SKU_ASC'),
				JText::_('ESHOP_SORTING_PRODUCT_SKU_DESC'),
				JText::_('ESHOP_SORTING_PRODUCT_PRICE_ASC'),
				JText::_('ESHOP_SORTING_PRODUCT_PRICE_DESC'),
				JText::_('ESHOP_SORTING_PRODUCT_LENGTH_ASC'),
				JText::_('ESHOP_SORTING_PRODUCT_LENGTH_DESC'),
				JText::_('ESHOP_SORTING_PRODUCT_WIDTH_ASC'),
				JText::_('ESHOP_SORTING_PRODUCT_WIDTH_DESC'),
				JText::_('ESHOP_SORTING_PRODUCT_HEIGHT_ASC'),
				JText::_('ESHOP_SORTING_PRODUCT_HEIGHT_DESC'),
				JText::_('ESHOP_SORTING_PRODUCT_WEIGHT_ASC'),
				JText::_('ESHOP_SORTING_PRODUCT_WEIGHT_DESC'),
				JText::_('ESHOP_SORTING_PRODUCT_QUANTITY_ASC'),
				JText::_('ESHOP_SORTING_PRODUCT_QUANTITY_DESC'),
				JText::_('ESHOP_SORTING_PRODUCT_SHORT_DESC_ASC'),
				JText::_('ESHOP_SORTING_PRODUCT_SHORT_DESC_DESC'),
				JText::_('ESHOP_SORTING_PRODUCT_DESC_ASC'),
				JText::_('ESHOP_SORTING_PRODUCT_DESC_DESC'),
				JText::_('ESHOP_SORTING_PRODUCT_RATES_ASC'),
				JText::_('ESHOP_SORTING_PRODUCT_RATES_DESC'),
				JText::_('ESHOP_SORTING_PRODUCT_REVIEWS_ASC'),
				JText::_('ESHOP_SORTING_PRODUCT_REVIEWS_DESC'),
				JText::_('ESHOP_SORTING_PRODUCT_LATEST'),
				JText::_('ESHOP_SORTING_PRODUCT_OLDEST'),
				JText::_('ESHOP_SORTING_PRODUCT_BEST_SELLERS'),
			    JText::_('ESHOP_SORTING_PRODUCT_RANDOMLY')
			);

			$options = array();

			for ($i = 0; $i < count($sortValues); $i++)
			{
				if (in_array($sortValues[$i], $sortOptions))
				{
					$options[] = JHtml::_('select.option', $sortValues[$i], $sortTexts[$i]);
				}
			}

			if (count($options) > 1)
			{
				$this->sort_options = JHtml::_('select.genericlist', $options, 'sort_options', 'class="inputbox input-xlarge" onchange="this.form.submit();" ', 'value', 'text', $state->sort_options ? $state->sort_options : EshopHelper::getConfigValue('default_sorting'));
			}
			else
			{
				$this->sort_options = '';
			}

			$app->setUserState('sort_options', $state->sort_options ? $state->sort_options : EshopHelper::getConfigValue('default_sorting'));
			$app->setUserState('from_view', 'category');
            if(!$this->limitLocation){
                $products   = $model->getData();
                $pagination = $model->getPagination();
            }


			// Store session for Continue Shopping Url
			$session->set('continue_shopping_url', JUri::getInstance()->toString());

			if ($state->sort_options && !$this->limitLocation)
			{
				$pagination->setAdditionalUrlParam('sort_options', $state->sort_options);
			}

			$tax                       = new EshopTax(EshopHelper::getConfig());
			$currency                  = new EshopCurrency();
			$category->category_desc   = JHtml::_('content.prepare', $category->category_desc);
			$this->category            = $category;
			$this->subCategories       = $subCategories;
			$this->subCategoriesPerRow = EshopHelper::getConfigValue('items_per_row', 3);
			$this->products            = $products;
			$this->pagination          = $pagination;
			$this->tax                 = $tax;
			$this->currency            = $currency;

			//Added by tuanpn, to use share common layout
			$productsPerRow = $category->products_per_row;

			if (!$productsPerRow)
			{
				$productsPerRow = EshopHelper::getConfigValue('items_per_row', 3);
			}

			if ($pagination->limitstart)
			{
				$this->actionUrl = JRoute::_(EshopRoute::getCategoryRoute($category->id) . '&limitstart=' . $pagination->limitstart);
			}
			else
			{
				$this->actionUrl = JRoute::_(EshopRoute::getCategoryRoute($category->id));
			}

			$this->productsPerRow        = $productsPerRow;
			$this->categoriesNavigation  = EshopHelper::getCategoriesNavigation($category->id);
			$this->bootstrapHelper       = new EshopHelperBootstrap(EshopHelper::getConfigValue('twitter_bootstrap_version'));

			parent::display($tpl);
		}
	}
}
