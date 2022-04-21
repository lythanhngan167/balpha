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
$rowFluidClass          = $bootstrapHelper->getClassMapping('row-fluid');
?>
<div id="eb-categories">
	<?php
	foreach ($categories as $category)
	{
		?>
		<div class="<?php echo $rowFluidClass; ?> clearfix">
			<h3 class="eshop-category-title">
				<a href="<?php echo JRoute::_(EshopRoute::getCategoryRoute($category->id)); ?>" class="eshop-category-title-link">
					<?php echo $category->category_name; ?>
				</a>
			</h3>
			<?php
			if($category->category_desc)
			{
			?>
				<div class="clearfix"><?php echo $category->category_desc;?></div>
			<?php
			}

			if (count($category->products))
			{
				?>
				<div class="eshop-products-table">
					<?php
					echo EshopHtmlHelper::loadCommonLayout('common/products_table.php', array (
						'products'	=> $category->products,
						'tax'		=> $tax,
						'currency'	=> $currency,
						'catId'		=> $category->id,
					    'bootstrapHelper' => $bootstrapHelper
					));
					?>
				</div>
				<?php
			}
			?>
		</div>
	<?php
	}
	?>
</div>