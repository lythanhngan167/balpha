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
$span = intval(12 / $manufacturersPerRow);
$rowFluidClass          = $bootstrapHelper->getClassMapping('row-fluid');
$spanClass              = $bootstrapHelper->getClassMapping('span' . $span);
?>
<div class="<?php echo $rowFluidClass; ?>">
	<?php
	$count = 0;
	foreach ($manufacturers as $manufacturer) 
	{
		$manufacturerUrl = JRoute::_(EshopRoute::getManufacturerRoute($manufacturer->id));
		?>
		<div class="<?php echo $spanClass; ?>">
			<div class="eshop-manufacturer-wrap">
				<div class="image">
					<a href="<?php echo $manufacturerUrl; ?>" title="<?php echo $manufacturer->manufacturer_page_title != '' ? $manufacturer->manufacturer_page_title : $manufacturer->manufacturer_name; ?>">
						<img src="<?php echo $manufacturer->image; ?>" alt="<?php echo $manufacturer->manufacturer_alt_image != '' ? $manufacturer->manufacturer_alt_image : $manufacturer->manufacturer_name; ?>" />	            
					</a>
	            </div>
				<div class="eshop-info-block">
					<h5>
						<a href="<?php echo $manufacturerUrl; ?>" title="<?php echo $manufacturer->manufacturer_page_title != '' ? $manufacturer->manufacturer_page_title : $manufacturer->manufacturer_name; ?>">
							<?php echo $manufacturer->manufacturer_name; ?>
						</a>
					</h5>
				</div>
			</div>
		</div>
		<?php
		$count++;
		if ($count % $manufacturersPerRow == 0 && $count < count($manufacturers))
		{
		?>
			</div><div class="<?php echo $rowFluidClass; ?>">
		<?php
		}
	}
	?>
</div>