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

if (count($this->products))
{
	
	if (EshopHelper::getConfigValue('products_filter_layout', 'default') == 'default')
	{
		$productsFilterLayout = '';
	}
	else
	{
		$productsFilterLayout = '_table';	
	}
	
	echo EshopHtmlHelper::loadCommonLayout('common/products_list' . $productsFilterLayout . '.php', array(
		'categories'      => $this->categories,
		'products'        => $this->products,
		'pagination'      => $this->pagination,
		'actionUrl'		  => $this->actionUrl,
		'sort_options'    => $this->sort_options,
		'tax'             => $this->tax,
		'currency'        => $this->currency,
		'category'        => $this->category,
		'productsPerRow'  => $this->productsPerRow,
		'catId'           => 0,
		'showSortOptions' => true,
		'manufacturers'   => $this->manufacturers,
		'attributes'      => $this->attributes,
		'options'         => $this->options,
		'filterData'      => $this->filterData,
	    'bootstrapHelper' => $this->bootstrapHelper,
	));
}
else
{
?>
	<div class="eshop-empty-search-result"><?php echo JText::_('ESHOP_NO_PRODUCTS_FOUND'); ?></div>
<?php
}