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

$span                   = intval(12 / $productsPerRow);
//$rowFluidClass          = $bootstrapHelper->getClassMapping('row');
//$spanClass              = $bootstrapHelper->getClassMapping('span' . $span);
//$span3Class             = $bootstrapHelper->getClassMapping('span3');
//$span9Class             = $bootstrapHelper->getClassMapping('span9');
/*
 * @var $bootstrapHelper
 */
$rowFluidClass          = $bootstrapHelper->getClassMapping('row-fluid');
$spanClass              = $bootstrapHelper->getClassMapping('col-sm-3' );
$span3Class             = $bootstrapHelper->getClassMapping('col-sm-3');
$span6Class             = $bootstrapHelper->getClassMapping('col-sm-6');
$span9Class             = $bootstrapHelper->getClassMapping('col-sm-9');
$hiddenPhoneClass       = $bootstrapHelper->getClassMapping('hidden-phone');
$inputAppendClass       = $bootstrapHelper->getClassMapping('input-append');
$inputPrependClass      = $bootstrapHelper->getClassMapping('input-prepend');
$imgPolaroid            = $bootstrapHelper->getClassMapping('img-polaroid');
$btnClass				= $bootstrapHelper->getClassMapping('btn');
?>
<script src="<?php echo JUri::base(true); ?>/components/com_eshop/assets/colorbox/jquery.colorbox.js" type="text/javascript"></script>
<script src="<?php echo JUri::base(true); ?>/components/com_eshop/assets/js/jquery.cookie.js" type="text/javascript"></script>
<script src="<?php echo JUri::base(true); ?>/components/com_eshop/assets/js/layout.js" type="text/javascript"></script>
<script>
	Eshop.jQuery(function($){
		$(document).ready(function() {
			changeLayout('<?php echo EshopHelper::getConfigValue('default_products_layout', 'list'); ?>');
		});
	});
</script>
<div id="products-list-container" class="products-list-container block list">

	<div class="sortPagiBar <?php echo $rowFluidClass; ?>">
		<?php
		if ($showSortOptions)
		{
			?>
			<div class="col-sm-12">
				<form method="post" name="adminForm" id="adminForm" action="<?php echo $actionUrl; ?>">
                    <?php
                    if ($sort_options)
                    {
                        ?>
                        <!--  <div class="col-sm-5 text-right">--><?php //echo JText::_('ESHOP_SORTING_BY'); ?><!--: </div>-->
                        <?php echo $sort_options; ?>
                        <?php
                    }
                    ?>
                    <?php //echo $pagination->getLimitBox(); ?>
                    <!-- <div class="col-sm-1 text-right">--><?php //echo JText::_('ESHOP_SHOW'); ?><!--: </div>-->
                    <div class="btn-group <?php echo $hiddenPhoneClass; ?>">
                        <?php
                        if (EshopHelper::getConfigValue('default_products_layout') == 'grid')
                        {
                            ?>
                            <a rel="grid" href="#" class="<?php echo $btnClass; ?>"><i class="icon-th-large"></i></a>
                            <a rel="list" href="#" class="<?php echo $btnClass; ?>"><i class="icon-th-list"></i></a>
                            <?php
                        }
                        else
                        {
                            ?>
                            <a rel="list" href="#" class="<?php echo $btnClass; ?>"><i class="icon-th-list"></i></a>
                            <a rel="grid" href="#" class="<?php echo $btnClass; ?>"><i class="icon-th-large"></i></a>
                            <?php
                        }
                        ?>
                    </div>
				</form>
			</div>
		<?php
		}
		?>
	</div>

	<div id="products-list">

		<div class="<?php echo $rowFluidClass; ?>">
			<?php
				$count = 0;

				foreach ($products as $product)
				{
					$productUrl = JRoute::_(EshopRoute::getProductRoute($product->id, ($catId && EshopHelper::isProductCategory($product->id, $catId)) ? $catId : EshopHelper::getProductCategory($product->id)));
					?>
					<div class="col-sm-6 col-md-3 col-xs-6 ajax-block-product">
                        <div class="item-product">
                            <a href="<?php echo $productUrl; ?>" title="<?php echo $product->product_name; ?>" class="product-img-wrap">
                                <?php
                                if (count($product->labels))
                                {
                                    for ($i = 0; $n = count($product->labels), $i < $n; $i++)
                                    {
                                        $label = $product->labels[$i];
                                        if ($label->label_style == 'rotated' && !($label->enable_image && $label->label_image))
                                        {
                                            ?>
                                            <div class="cut-rotated">
                                            <?php
                                        }

                                        if ($label->enable_image && $label->label_image)
                                        {
                                            $imageWidth = $label->label_image_width > 0 ? $label->label_image_width : EshopHelper::getConfigValue('label_image_width');
                                            if (!$imageWidth)
                                                $imageWidth = 50;
                                            $imageHeight = $label->label_image_height > 0 ? $label->label_image_height : EshopHelper::getConfigValue('label_image_height');
                                            if (!$imageHeight)
                                                $imageHeight = 50;
                                            ?>
                                            <span class="horizontal <?php echo $label->label_position; ?> small-db" style="opacity: <?php echo $label->label_opacity; ?>;<?php echo 'background-image: url(' . $label->label_image . ')'; ?>; background-repeat: no-repeat; width: <?php echo $imageWidth; ?>px; height: <?php echo $imageHeight; ?>px; box-shadow: none;"></span>
                                            <?php
                                        }
                                        else
                                        {
                                            ?>
                                            <span class="<?php echo $label->label_style; ?> <?php echo $label->label_position; ?> small-db" style="background-color: <?php echo '#'.$label->label_background_color; ?>; color: <?php echo '#'.$label->label_foreground_color; ?>; opacity: <?php echo $label->label_opacity; ?>;<?php if ($label->label_bold) echo 'font-weight: bold;'; ?>">
													<?php echo $label->label_name; ?>
												</span>
                                            <?php
                                        }
                                        if ($label->label_style == 'rotated' && !($label->enable_image && $label->label_image))
                                        {
                                            ?>
                                            </div>
                                            <?php
                                        }
                                    }
                                }
                                ?>

                                <img class="img-responsive" src="<?php echo $product->image; ?>" title="<?php echo $product->product_page_title != '' ? $product->product_page_title : $product->product_name; ?>" alt="<?php echo $product->product_alt_image != '' ? $product->product_alt_image : $product->product_name; ?>" />
                                <span class="feature_group group_<?=  $product->p_group ?>"></span>
                            </a>
                            <a class="product-tile" href="<?php echo $productUrl; ?>" title="<?php echo $product->product_name; ?>"><?php echo $product->product_name;?></a>
                            <div class="eshop-product-price">
                                <?php
                                if (EshopHelper::showPrice() && !$product->product_call_for_price)
                                {
                                    ?>
                                    <?php
                                    $productPriceArray = EshopHelper::getProductPriceArray($product->id, $product->product_price);
                                    if ($productPriceArray['salePrice'] >= 0)
                                    {
                                        ?>
                                        <span class="eshop-base-price"><?php echo $currency->format($tax->calculate($productPriceArray['basePrice'], $product->product_taxclass_id, EshopHelper::getConfigValue('tax'))); ?></span>&nbsp;
                                        <span class="eshop-sale-price"><?php echo $currency->format($tax->calculate($productPriceArray['salePrice'], $product->product_taxclass_id, EshopHelper::getConfigValue('tax'))); ?></span>
                                        <?php
                                    }
                                    else
                                    {
                                        ?>
                                        <span class="price"><?php echo $currency->format($tax->calculate($productPriceArray['basePrice'], $product->product_taxclass_id, EshopHelper::getConfigValue('tax'))); ?><span class="weight"> /  <?php  echo EshopHelper::getWeightUnitName($product->product_weight_id, JFactory::getLanguage()->getTag()) ; ?></span></span>
                                        <?php
                                    }
                                    if (EshopHelper::getConfigValue('tax') && EshopHelper::getConfigValue('display_ex_tax'))
                                    {
                                        ?>
                                        <small>
                                            <?php echo JText::_('ESHOP_EX_TAX'); ?>:
                                            <?php
                                            if ($productPriceArray['salePrice'] >= 0)
                                            {
                                                echo $currency->format($productPriceArray['salePrice']);
                                            }
                                            else
                                            {
                                                echo $currency->format($productPriceArray['basePrice']);
                                            }
                                            ?>
                                        </small>
                                        <?php
                                    }
                                    ?>

                                    <?php
                                }
                                if ($product->product_call_for_price)
                                {
                                    ?>
                                    <p><?php echo JText::_('ESHOP_CALL_FOR_PRICE'); ?>: <?php echo EshopHelper::getConfigValue('telephone'); ?></p>
                                    <?php
                                }
                                ?>
                            </div>
                            <div class="eshop-product-desc"><?php echo $product->product_short_desc;?></div>
                            <div class="eshop-buttons">
                                <?php
                                if (!EshopHelper::isRequiredOptionProduct($product->id))
                                {
                                    if (EshopHelper::isCartMode($product) || EshopHelper::isQuoteMode($product))
                                    {
                                        ?>
                                        <div class="eshop-cart-area">

                                            <?php
                                            if (EshopHelper::getConfigValue('show_quantity_box'))
                                            {
                                                ?>
                                                <div class="<?php echo $inputAppendClass; ?> <?php echo $inputPrependClass; ?>">
												<span class="eshop-quantity">
													<a class="<?php echo $btnClass; ?> btn-default button-minus" id="<?php echo $product->id; ?>" data="down">-</a>
													<input type="text" class="eshop-quantity-value" id="quantity_<?php echo $product->id; ?>" name="quantity[]" value="<?php echo EshopHelper::getConfigValue('start_quantity_number', '1'); ?>" />
													<input type="hidden" id="quantity_step_<?php echo $product->id; ?>" name="quantity_step_<?php echo $product->id; ?>" value="<?php echo EshopHelper::getConfigValue('quantity_step', '1'); ?>" />
													<?php
                                                    if (EshopHelper::getConfigValue('one_add_to_cart_button', '0'))
                                                    {
                                                        ?>
                                                        <input type="hidden" name="product_id[]" value="<?php echo $product->id; ?>" />
                                                        <?php
                                                    }
                                                    ?>
                                                    <a class="<?php echo $btnClass; ?> btn-default button-plus" id="<?php echo $product->id; ?>" data="up">+</a>
												</span>
                                                </div>
                                                <?php
                                            }
                                            if (EshopHelper::isCartMode($product) && !EshopHelper::getConfigValue('one_add_to_cart_button', '0'))
                                            {
                                                ?>
                                                <button id="add-to-cart-<?php echo $product->id; ?>" type="button" class="<?php echo $btnClass; ?> btn-success btn-block" onclick="addToCart(<?php echo $product->id; ?>, 1, '<?php echo EshopHelper::getSiteUrl(); ?>', '<?php echo EshopHelper::getAttachedLangLink(); ?>', '<?php echo EshopHelper::getConfigValue('cart_popout')?>', '<?php echo JRoute::_(EshopRoute::getViewRoute('cart')); ?>');">
                                                    <i class="fa fa-shopping-cart"></i>
                                                    <span class="txt txt_cart"><?php echo JText::_('ESHOP_ADD_TO_CART'); ?></span>
                                                </button>
                                                <?php
                                            }
                                            if (EshopHelper::isQuoteMode($product))
                                            {
                                                ?>
                                                <input id="add-to-quote-<?php echo $product->id; ?>" type="button" class="<?php echo $btnClass; ?> btn-primary" onclick="addToQuote(<?php echo $product->id; ?>, 1, '<?php echo EshopHelper::getSiteUrl(); ?>', '<?php echo EshopHelper::getAttachedLangLink(); ?>');" value="<?php echo JText::_('ESHOP_ADD_TO_QUOTE'); ?>" />
                                                <?php
                                            }
                                            ?>
                                        </div>
                                        <?php
                                    }
                                }
                                else
                                {
                                    ?>
                                    <div class="eshop-cart-area">

                                        <a class="<?php echo $btnClass; ?> btn-primary" href="<?php echo $productUrl; ?>" title="<?php echo $product->product_name; ?>">
                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                            <span class="txt txt_detail"><?php echo JText::_('ESHOP_PRODUCT_VIEW_DETAILS'); ?></span>
                                        </a>
                                    </div>
                                    <?php
                                }

                                if (($product->product_quantity <= 0 && EshopHelper::getConfigValue('allow_notify') && !EshopHelper::getConfigValue('stock_checkout')) || EshopHelper::getConfigValue('allow_wishlist') || EshopHelper::getConfigValue('allow_compare'))
                                {
                                    ?>
                                    <div class="btn-wishlist">
                                        <?php
                                        if ($product->product_quantity <= 0 && EshopHelper::getConfigValue('allow_notify')  && !EshopHelper::getConfigValue('stock_checkout'))
                                        {
                                            ?>
                                            <a class="<?php echo $btnClass; ?> button" onclick="makeNotify(<?php echo $product->id; ?>, '<?php echo EshopHelper::getSiteUrl();?>', '<?php echo EshopHelper::getAttachedLangLink(); ?>')" ><?php echo JText::_('ESHOP_PRODUCT_NOTIFY');?></a>
                                            <?php
                                        }
                                        if (EshopHelper::getConfigValue('allow_wishlist'))
                                        {
                                            ?>
                                            <a class="<?php echo $btnClass; ?> button" style="cursor: pointer;" onclick="addToWishList(<?php echo $product->id; ?>, '<?php echo EshopHelper::getSiteUrl(); ?>', '<?php echo EshopHelper::getAttachedLangLink(); ?>')" title="<?php echo JText::_('ESHOP_ADD_TO_WISH_LIST'); ?>">
																							<i class="fa fa-heart" aria-hidden="true"></i>
																							<?php //echo JText::_('ESHOP_ADD_TO_WISH_LIST'); ?>
																						</a>
                                            <?php
                                        }
                                        if (EshopHelper::getConfigValue('allow_compare'))
                                        {
                                            ?>
                                            <a class="<?php echo $btnClass; ?> button" style="cursor: pointer;" onclick="addToCompare(<?php echo $product->id; ?>, '<?php echo EshopHelper::getSiteUrl(); ?>', '<?php echo EshopHelper::getAttachedLangLink(); ?>')" title="<?php echo JText::_('ESHOP_ADD_TO_COMPARE'); ?>"><?php echo JText::_('ESHOP_ADD_TO_COMPARE'); ?></a>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
					</div>
					<?php
				$count++;

				if ($count % $productsPerRow == 0 && $count < count($products))
				{
					?>
        </div>
        <div class="<?php echo $rowFluidClass; ?>">
					<?php
				}
			}
			?>
		</div>
		<?php
		if (EshopHelper::getConfigValue('show_quantity_box') && EshopHelper::getConfigValue('one_add_to_cart_button', '0'))
		{
			?>
			<div class="<?php echo $rowFluidClass; ?>">
				<input id="multiple-products-add-to-cart" type="button" class="<?php echo $btnClass; ?> btn-primary" onclick="multipleProductsAddToCart('<?php echo EshopHelper::getSiteUrl(); ?>', '<?php echo EshopHelper::getAttachedLangLink(); ?>', '<?php echo EshopHelper::getConfigValue('cart_popout')?>', '<?php echo JRoute::_(EshopRoute::getViewRoute('cart')); ?>');" value="<?php echo JText::_('ESHOP_MULTIPLE_PRODUCTS_ADD_TO_CART'); ?>" />
			</div>
			<?php
		}

		?>
	</div>
	<?php
	if (isset($pagination) && ($pagination->total > $pagination->limit))
	{
		?>
					<div class="product-pagination">
							<?php echo $pagination->getPagesLinks(); ?>
					</div>
		<?php
	}
	 ?>
</div>
