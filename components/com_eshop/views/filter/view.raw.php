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
 * HTML View class for EShop component
 *
 * @static
 *
 * @package    Joomla
 * @subpackage EShop
 * @since      1.5
 */
class EShopViewFilter extends EShopView
{

	public function display($tpl = null)
	{
		require_once JPATH_ROOT . '/components/com_eshop/helpers/filter.php';

		$filterData = EshopFilter::getFilterData();
		
		$app = JFactory::getApplication();
		$model = $this->getModel();
		$state = $model->getState();
		
		if (!empty($filterData['product_ids']))
		{
			$this->products = $model->getData();
		}
		else
		{
			$products       = array();
			$this->products = $products;
		}

		/* @var JPagination $pagination */
		$pagination = $model->getPagination();
		$actionUrl = EshopHelper::getSiteUrl() . 'index.php?option=com_eshop&view=filter';
		
		foreach ($filterData as $key => $values)
		{
			if ($key == 'product_ids')
			{
				continue;
			}
			if (is_array($values))
			{
				$index = 0;

				foreach ($values as $value)
				{
					$pagination->setAdditionalUrlParam($key . "[$index]", $value);
					$actionUrl .= '&' . $key . '[' . $index . ']=' . $value;
					$index++;
				}
			}
			elseif (in_array($key, array('min_weight', 'max_weight', 'min_length', 'max_length', 'min_width', 'max_width', 'min_height', 'max_height')))
			{
				$pagination->setAdditionalUrlParam($key, $this->input->getString($key));
				$actionUrl .= '&' . $key . '=' . $this->input->getString($key);
			}
			else
			{
				$pagination->setAdditionalUrlParam($key, $values);
				$actionUrl .= '&' . $key . '=' . $values;
			}
		}
		
		$actionUrl = JRoute::_($actionUrl . '&Itemid=' . EshopRoute::getDefaultItemId());

		if (!empty($filterData['category_id']))
		{
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select(' a.id, a.category_parent_id, b.category_name')
				->from('#__eshop_categories AS a')
				->innerJoin('#__eshop_categorydetails AS b ON a.id = b.category_id')
				->where('a.id = ' . $filterData['category_id']);

			if (JLanguageMultilang::isEnabled())
			{
				$query->where('b.language = ' . $db->quote(JFactory::getLanguage()->getTag()));
			}

			$db->setQuery($query);
			$category = $db->loadObject();

			$this->category = $category;
		}
		else
		{
			$this->category = null;
		}

		//Sort options
		$sortOptions = EshopHelper::getConfigValue('sort_options');
		$sortOptions = explode(',', $sortOptions);
		$sortValues = array (
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
				'product_best_sellers-DESC'
		);
		$sortTexts = array (
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
				JText::_('ESHOP_SORTING_PRODUCT_BEST_SELLERS')
		);
		$options = array();
		for ($i = 0; $i< count($sortValues); $i++)
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
		$app->setUserState('from_view', 'filter');
		if ($state->sort_options)
		{
			$pagination->setAdditionalUrlParam('sort_options', $state->sort_options);
		}
		
		$this->pagination		= $pagination;
		$this->actionUrl		= $actionUrl;
		$this->categories		= EshopFilter::getCategories($filterData);
		$this->manufacturers	= EshopFilter::getManufacturers($filterData);
		$this->attributes		= EshopFilter::getAttributes($filterData, true);
		$this->options			= EshopFilter::getOptions($filterData, true);
		$this->tax				= new EshopTax(EshopHelper::getConfig());
		$this->currency			= new EshopCurrency();
		$this->productsPerRow	= EshopHelper::getConfigValue('items_per_row', 3);
		$this->filterData		= $filterData;
		$this->bootstrapHelper  = new EshopHelperBootstrap(EshopHelper::getConfigValue('twitter_bootstrap_version'));

		$app    = JFactory::getApplication();
		$router = $app::getRouter();
		$router->setVar('format', 'html');

		parent::display($tpl);
	}
}