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
class EShopViewCampaign extends EShopView
{
    public function display($tpl = null)
    {
        $app = JFactory::getApplication();
        $session = JFactory::getSession();
        $model = $this->getModel();
        $state = $model->getState();
        $campaign = $model->getCampaignInfo($state->id);
        if (!$campaign) {
            // Requested category does not existed.
            $session->set('warning', JText::_('ESHOP_CATEGORY_DOES_NOT_EXIST'));
            $app->redirect(JRoute::_(EshopRoute::getViewRoute('categories')));
            exit();
        }

        // getCampaignInfo

        $document = JFactory::getDocument();
        $baseUri = JUri::base(true);
        $document->addStyleSheet($baseUri . '/components/com_eshop/assets/colorbox/colorbox.css');
        $document->addStyleSheet($baseUri . '/components/com_eshop/assets/css/labels.css');

        //Handle breadcrump
        $menu = $app->getMenu();
        $menuItem = $menu->getActive();
        if ($menuItem) {
            $pathway = $app->getPathway();
            $pathway->addItem($campaign['title'], '');

        }


        // Set title of the page
        $this->setPageTitle($campaign['title']);


        //Sort options
        $sortOptions = EshopHelper::getConfigValue('sort_options');
        $sortOptions = explode(',', $sortOptions);
        $sortValues = array(
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

        for ($i = 0; $i < count($sortValues); $i++) {
            if (in_array($sortValues[$i], $sortOptions)) {
                $options[] = JHtml::_('select.option', $sortValues[$i], $sortTexts[$i]);
            }
        }

        if (count($options) > 1) {
            $this->sort_options = JHtml::_('select.genericlist', $options, 'sort_options', 'class="inputbox input-xlarge" onchange="this.form.submit();" ', 'value', 'text', $state->sort_options ? $state->sort_options : EshopHelper::getConfigValue('default_sorting'));
        } else {
            $this->sort_options = '';
        }

        $app->setUserState('sort_options', $state->sort_options ? $state->sort_options : EshopHelper::getConfigValue('default_sorting'));
        $app->setUserState('from_view', 'category');
        $products = $model->getData();
        $pagination = $model->getPagination();


        // Store session for Continue Shopping Url
        $session->set('continue_shopping_url', JUri::getInstance()->toString());

        if ($state->sort_options && !$this->limitLocation) {
            $pagination->setAdditionalUrlParam('sort_options', $state->sort_options);
        }

        $tax = new EshopTax(EshopHelper::getConfig());
        $currency = new EshopCurrency();
        $this->products = $products;
        $this->pagination = $pagination;
        $this->tax = $tax;
        $this->currency = $currency;
        $this->campaign = $campaign;

        //Added by tuanpn, to use share common layout
        $productsPerRow = 4;

        if (!$productsPerRow) {
            $productsPerRow = EshopHelper::getConfigValue('items_per_row', 3);
        }

        if ($pagination->limitstart) {
            $this->actionUrl = JRoute::_('index.php?option=com_eshop&view=campaign&id=' . $campaign['id'] . '&limitstart=' . $pagination->limitstart);
        } else {
            $this->actionUrl = JRoute::_('index.php?option=com_eshop&view=campaign&id=' . $campaign['id']);
        }

        $this->productsPerRow = $productsPerRow;
        $this->bootstrapHelper = new EshopHelperBootstrap(EshopHelper::getConfigValue('twitter_bootstrap_version'));

        parent::display($tpl);

    }
}
