<?php
/**
 * @version        1.3.1
 * @package        Joomla
 * @subpackage    EShop
 * @author    Giang Dinh Truong
 * @copyright    Copyright (C) 2011 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die;
$langCode = JFactory::getLanguage()->getTag();

?>
<div id="products-list-container" class="products-list-container block grid">
    <div id="products-list">
        <?php foreach ($modules as $mod): ?>
            <div class="title" style="display: block; width: 100%; background: #f3f4f6;">
            <h3 class="sppb-addon-title" >
                <?php if($mod['group_type'] == 'home_category'):
                    $link = JRoute::_(EshopRoute::getCategoryRoute($mod['id']));
                    ?>
                    <a href="<?= $link ?>"><?= $mod['title'] ?></a>
                <?php else:
                    $link = JRoute::_('index.php?option=com_eshop&view=campaign&id='.$mod['id']);
                    ?>
                    <a href="<?= $link ?>"><?= $mod['title'] ?></a>
                <?php endif;?>
            </h3>
            </div>
            <?php $rows = array_chunk($mod['products'], 4);
            foreach ($rows as $cols):
                ?>
                <div class="row">
                    <?php foreach ($cols as $product):
                        $viewProductUrl = JRoute::_(EshopRoute::getProductRoute($product->id, EshopHelper::getProductCategory($product->id)));
                        ?>
                        <div class="col-xs-6 col-lg-3 ajax-block-product">
                            <div class="item-product">
                                <a href="<?= $viewProductUrl ?>"
                                   title="<?php echo $product->name; ?>" class="product-img-wrap">

                                    <img class="product-item" alt="<?php echo $product->name; ?>"
                                         src="<?php echo $product->product_image; ?>">
                                    <span class="feature_group <?= $product->p_group ?>"></span>
                                </a>
                                <a class="product-tile" href="<?= $viewProductUrl ?>"
                                   title="<?php echo $product->name; ?>"><?= $product->name; ?></a>
                                <div class="eshop-product-price">

                                    <?php if ($product->base_price): ?>
                                        <span class="eshop-base-price" style="text-decoration: line-through;"><?php echo $currency->format($tax->calculate($product->base_price, $product->product_taxclass_id, EshopHelper::getConfigValue('tax'))); ?></span>&nbsp;
                                        <span class="price"><?php echo $currency->format($tax->calculate($product->price, $product->product_taxclass_id, EshopHelper::getConfigValue('tax'))); ?></span>
                                    <?php else: ?>
                                        <span class="price"><?php echo $currency->format($tax->calculate($product->price, $product->product_taxclass_id, EshopHelper::getConfigValue('tax'))); ?><span class="weight"> / <?php echo $product->unit ; ?></span></span>
                                    <?php endif; ?>
                                </div>
                                <!-- <div class="eshop-buttons">
                                    <div class="eshop-cart-area">
                                        <a class="btn btn-default btn-primary"
                                           href="<?= $viewProductUrl ?>"
                                           title="<?php echo $product->name; ?>">
                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                            <span class="txt txt_detail">Chi tiết</span>
                                        </a>
                                    </div>
                                </div> -->
                                <div class="eshop-buttons">

                                        <div class="eshop-cart-area">
                                            <button id="add-to-cart-<?php echo $product->id; ?>" type="button" class="btn btn-success" onclick="addToCart(<?php echo $product->id; ?>, 1, '<?php echo EshopHelper::getSiteUrl(); ?>', '<?php echo EshopHelper::getAttachedLangLink(); ?>', '<?php echo EshopHelper::getConfigValue('cart_popout')?>', '<?php echo JRoute::_(EshopRoute::getViewRoute('cart')); ?>');" >
                                                <i class="fa fa-shopping-cart"></i>
                                                <span class="txt txt_cart"><?php echo JText::_('ESHOP_ADD_TO_CART'); ?></span>
                                            </button>
                                        </div>

                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>

                </div>
            <?php endforeach; ?>


        <?php endforeach; ?>
    </div>
</div>
