<?php
/**
 * @version		1.3.1
 * @package		Joomla
 * @subpackage	EShop
 * @author  	Giang Dinh Truong
 * @copyright	Copyright (C) 2012 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined( '_JEXEC' ) or die();
?>
<div class="eshop-category<?php echo $params->get( 'moduleclass_sfx' ) ?>">
	<ul>
		<?php
		foreach ($categories as $category)
		{
			if ($showNumberProducts)
			{
				$numberProducts = ' (' . EshopHelper::getNumCategoryProducts($category->id, true) . ')';
			}
			else
			{
				$numberProducts = '';
			}
			?>
			<li>
				<?php
				$active = $category->id == $parentCategoryId ? ' class="active"' : '';
				?>
				<a href="<?php echo JRoute::_(EshopRoute::getCategoryRoute($category->id)); ?>"<?php echo $active; ?>><?php echo $category->category_name . $numberProducts; ?></a>
				<?php
				if ($showChildren && $category->childCategories)
				{
				?>
					<ul>
					<?php
					foreach ($category->childCategories as $childCategory)
					{
						if ($showNumberProducts)
						{
							$numberProducts = ' (' . EshopHelper::getNumCategoryProducts($childCategory->id, true) . ')';
						}
						else
						{
							$numberProducts = '';
						}
						?>
						<li>
							<?php
							$active = $childCategory->id == $childCategoryId ? 'class="active"' : '';
							?>
							<a href="<?php echo JRoute::_(EshopRoute::getCategoryRoute($childCategory->id)); ?>" <?php echo $active; ?>> - <?php echo $childCategory->category_name . $numberProducts; ?></a>
						</li>
					<?php
					}
					?>
					</ul>
				<?php
				}
				?>
			</li>
			<?php
		}
		?>
	</ul>
</div>