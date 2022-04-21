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
defined( '_JEXEC' ) or die();
?>
<h1><?php echo JText::_('ESHOP_SEARCH_RESULT'); ?></h1>
<?php
if (count($this->products))
{
	?>
	<div class="eshop-products-list">
		<?php
		echo EshopHtmlHelper::loadCommonLayout('common/products.php', array (
			'products' => $this->products,
			'pagination' => $this->pagination,
			'sort_options' => $this->sort_options,
			'tax' => $this->tax,
			'currency' => $this->currency,
			'productsPerRow' => $this->productsPerRow,
			'catId' => 0,
			'actionUrl' => $this->actionUrl,
			'showSortOptions' => true,
		    'bootstrapHelper' => $this->bootstrapHelper
		));
		?>
	</div>
	<?php
}
else
{
	?>
	<div class="eshop-empty-search-result"><?php echo JText::_('ESHOP_NO_PRODUCTS_FOUND'); ?></div>
	<?php
}