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
class EShopViewSearch extends EShopView
{
	public function display($tpl = null)
	{
		$input  = JFactory::getApplication()->input;
		$keyword = $this->input->getString('keyword');
		$db      = JFactory::getDbo();
		$query   = $db->getQuery(true);
		$query->select('id')
			->from('#__eshop_tags')
			->where('tag_name = ' . $db->quote($keyword));
		$db->setQuery($query);
		$tagId = $db->loadResult();

		if ($tagId)
		{
			EshopHelper::updateHits($tagId, 'tags');
		}

		$model    = $this->getModel();
		$document = JFactory::getDocument();
		$params   = JFactory::getApplication()->getParams();
		$baseUri  = JUri::base(true);
		$document->addStyleSheet($baseUri . '/components/com_eshop/assets/colorbox/colorbox.css');
		$document->addStyleSheet($baseUri . '/components/com_eshop/assets/css/labels.css');

		$title = $params->get('page_title', '');

		if ($title == '')
		{
			$title = JText::_('ESHOP_SEARCH_RESULT');
		}

		$this->setPageTitle($title);

		//Sort options
		$sortOptions = EshopHelper::getConfigValue('sort_options');
		$sortOptions = explode(',', $sortOptions);
		$sortValues  = array(
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
			'b.product_desc-DESC'
		);

		$sortTexts = array(
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
			JText::_('ESHOP_SORTING_PRODUCT_DESC_DESC')
		);

		$options   = array();
		$options[] = JHtml::_('select.option', 'a.id-DESC', JText::_('ESHOP_SORTING_DEFAULT'));

		for ($i = 0; $i < count($sortValues); $i++)
		{
			if (in_array($sortValues[$i], $sortOptions))
			{
				$options[] = JHtml::_('select.option', $sortValues[$i], $sortTexts[$i]);
			}
		}

		if (count($options) > 1)
		{
			$this->sort_options = JHtml::_('select.genericlist', $options, 'sort_options', 'class="input-large" onchange="this.form.submit();" ', 'value', 'text', $input->get('sort_options', ''));
		}
		else
		{
			$this->sort_options = '';
		}

		// Store session for Continue Shopping Url
		JFactory::getSession()->set('continue_shopping_url', JUri::getInstance()->toString());
		$this->products       = $model->getData();
		$this->pagination     = $model->getPagination();
		$this->tax            = new EshopTax(EshopHelper::getConfig());
		$this->currency       = new EshopCurrency();
		$this->productsPerRow = EshopHelper::getConfigValue('items_per_row', 3);
		$this->actionUrl      = '';
		$this->bootstrapHelper       = new EshopHelperBootstrap(EshopHelper::getConfigValue('twitter_bootstrap_version'));

		parent::display($tpl);
	}
}