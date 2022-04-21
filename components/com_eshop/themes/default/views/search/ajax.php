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
$input  = JFactory::getApplication()->input;
if(count($this->products))
{
	$descriptionMaxChars = $input->getInt('description_max_chars');
	foreach ($this->products as $product)
	{
		$viewProductUrl = JRoute::_(EshopRoute::getProductRoute($product->id, EshopHelper::getProductCategory($product->id)));
		?>
		<li onclick="window.open('<?php echo JUri::base().$viewProductUrl; ?>','_self');">
			<a href="<?php echo $viewProductUrl; ?>">
				<img alt="<?php echo $product->product_name; ?>" src="<?php echo $product->image; ?>" />
			</a>
			<div>
				<a href="<?php echo $viewProductUrl; ?>"><?php echo $product->product_name; ?></a><br />
				<span><?php echo EshopHelper::substring($product->product_short_desc, $descriptionMaxChars, '...'); ?></span>
			</div>
		</li>
		<?php
	}
}
else
{
	?>
	<li><?php echo JText::_('ESHOP_NO_PRODUCTS'); ?></li>
	<?php
}
