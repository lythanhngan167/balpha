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

JHtml::_('behavior.tooltip');
JHtml::_('behavior.modal');
JHtml::_('formbehavior.chosen', 'select');

$ordering = ($this->lists['order'] == 'a.ordering');

if ($ordering) {
    $saveOrderingUrl = 'index.php?option=com_eshop&task=product.save_order_ajax';
    JHtml::_('sortablelist.sortable', 'recordsList', 'adminForm', strtolower($this->lists['order_Dir']), $saveOrderingUrl);
}

$customOptions = array(
    'filtersHidden' => true,
    'defaultLimit' => JFactory::getApplication()->get('list_limit', 20),
    'searchFieldSelector' => '#filter_search',
    'orderFieldSelector' => '#filter_full_ordering'
);
JHtml::_('searchtools.form', '#adminForm', $customOptions);
$extraCols = $this->getHomeAttributes();
?>
<style>
    #toolbar-copy, #toolbar-batch {
        display: none !important;
    }

    #stock_status_chzn {
        display: none
    }

    .price {
        color: red;
    }
</style>
<form action="index.php?option=com_eshop&view=products" method="post" name="adminForm" id="adminForm">
    <div id="j-main-container">
        <div id="filter-bar" class="btn-toolbar">
            <div class="filter-search btn-group pull-left">
                <label for="filter_search"
                       class="element-invisible"><?php echo JText::_('ESHOP_FILTER_SEARCH_PRODUCTS_DESC'); ?></label>
                <input type="text" name="search" id="filter_search"
                       placeholder="<?php echo JText::_('JSEARCH_FILTER'); ?>"
                       value="<?php echo $this->escape($this->state->search); ?>" class="hasTooltip"
                       title="<?php echo JHtml::tooltipText('ESHOP_SEARCH_PRODUCTS_DESC'); ?>"/>
            </div>
            <div class="btn-group pull-left">
                <button type="submit" class="btn hasTooltip"
                        title="<?php echo JHtml::tooltipText('JSEARCH_FILTER_SUBMIT'); ?>"><span
                            class="icon-search"></span></button>
                <button type="button" class="btn hasTooltip"
                        title="<?php echo JHtml::tooltipText('JSEARCH_FILTER_CLEAR'); ?>"
                        onclick="document.getElementById('filter_search').value='';this.form.submit();"><span
                            class="icon-remove"></span></button>
            </div>
            <div class="btn-group pull-right hidden-phone">
                <?php
                echo $this->lists['category_id'];
                echo $this->lists['mf_id'];
                echo $this->lists['filter_state'];
                echo $this->lists['stock_status'];
                echo $this->pagination->getLimitBox();
                ?>
            </div>
        </div>
        <div class="clearfix"></div>
        <table class="adminlist table table-striped" id="recordsList">
            <thead>
            <tr>
                <th width="1%" class="nowrap center hidden-phone">
                    <?php echo JHtml::_('searchtools.sort', '', 'a.ordering', $this->lists['order_Dir'], $this->lists['order'], null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-menu-2'); ?>
                </th>
                <th class="text_center" width="2%">
                    <?php echo JHtml::_('grid.checkall'); ?>
                </th>
                <th width="1%" class="text_center" style="min-width:55px">
                    <?php echo JHtml::_('searchtools.sort', JText::_('JSTATUS'), 'a.published', $this->lists['order_Dir'], $this->lists['order']); ?>
                </th>
                <th class="text_left" width="15%">
                    <?php echo JHtml::_('searchtools.sort', JText::_('ESHOP_NAME'), 'b.product_name', $this->lists['order_Dir'], $this->lists['order']); ?>
                </th>
                <th class="text_center" width="5%">
                    <?php echo JHtml::_('searchtools.sort', JText::_('ESHOP_PRODUCT_SKU'), 'a.product_sku', $this->lists['order_Dir'], $this->lists['order']); ?>
                </th>
                <th class="text_center" width="2%">
                    <?php echo JText::_('ESHOP_IMAGE'); ?>
                </th>
                <th class="text_center" width="10%">
                    <?php echo JHtml::_('searchtools.sort', JText::_('ESHOP_PRODUCT_PRICE'), 'a.product_price', $this->lists['order_Dir'], $this->lists['order']); ?>
                </th>
                <th class="text_center" width="10%">
                    <?php echo JHtml::_('searchtools.sort', JText::_('PV Sản phẩm'), 'a.number_pv', $this->lists['order_Dir'], $this->lists['order']);  ?>
                </th>
                <!-- <th class="text_center" width="10%">
                    <?php echo JHtml::_('searchtools.sort', JText::_('ESHOP_PRODUCT_QUANTITY'), 'a.product_quantity', $this->lists['order_Dir'], $this->lists['order']); ?>
                </th> -->
                <th class="text_center" width="18%">
                    <?php echo JText::_('ESHOP_CATEGORY'); ?>
                </th>
                <!--<th class="text_center" width="10%">
                    <?php /*echo JHtml::_('searchtools.sort', JText::_('ESHOP_MANUFACTURER'), 'a.manufacturer_id', $this->lists['order_Dir'], $this->lists['order']); */ ?>
                </th>-->
                <th class="text_center" width="5%">
                    <?php echo JHtml::_('searchtools.sort', JText::_('ESHOP_FEATURED'), 'a.product_featured', $this->lists['order_Dir'], $this->lists['order']); ?>
                </th>
                <?php foreach ($extraCols as $excol): ?>
                    <th class="text_center" width="5%">
                        <?php echo JHtml::_('searchtools.sort', $excol['title'], 'a.extra.' . $excol['id'], $this->lists['order_Dir'], $this->lists['order']); ?>
                    </th>
                <?php endforeach; ?>
                <th class="text_center" width="5%">
                    <?php echo JHtml::_('searchtools.sort', JText::_('ESHOP_PUBLISHED'), 'a.published', $this->lists['order_Dir'], $this->lists['order']); ?>
                </th>
                <th class="text_center" width="4%">
                    <?php echo JHtml::_('searchtools.sort', JText::_('ESHOP_ID'), 'a.id', $this->lists['order_Dir'], $this->lists['order']); ?>
                </th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <td colspan="13">
                    <?php echo $this->pagination->getListFooter(); ?>
                </td>
            </tr>
            </tfoot>
            <tbody>
            <?php
            $k = 0;

            $rootUri = JUri::root();
            for ($i = 0, $n = count($this->items); $i < $n; $i++) {
                $row = &$this->items[$i];
                $link = JRoute::_('index.php?option=com_eshop&task=product.edit&cid[]=' . $row->id);
                $published = JHtml::_('jgrid.published', $row->published, $i, 'product.');
                ?>
                <tr class="<?php echo "row$k"; ?>">
                    <td class="order nowrap center hidden-phone">
                        <?php
                        $iconClass = '';

                        if (!$ordering) {
                            $iconClass = ' inactive tip-top hasTooltip';
                        }
                        ?>
                        <span class="sortable-handler<?php echo $iconClass ?>">
						<i class="icon-menu"></i>
						</span>
                        <?php if ($ordering) : ?>
                            <input type="text" style="display:none" name="order[]" size="5"
                                   value="<?php echo $row->ordering ? $row->ordering : '0'; ?>"
                                   class="width-20 text-area-order "/>
                        <?php endif; ?>
                    </td>
                    <td class="text_center">
                        <?php echo JHtml::_('grid.id', $i, $row->id); ?>
                    </td>
                    <td class="text_center">
                        <div class="btn-group">
                            <?php
                            echo $published;
                            //echo $this->featured($row->product_featured, $i);
                            //echo $this->addDropdownList(JText::_('ESHOP_COPY'), 'copy', $i, 'product.copy');
                            //echo $this->addDropdownList(JText::_('ESHOP_DELETE'), 'trash', $i, 'product.remove');
                            //echo $this->renderDropdownList($this->escape($row->product_name));
                            ?>
                        </div>
                    </td>
                    <td class="text_left">
                        <a href="<?php echo $link; ?>"><?php echo $row->product_name; ?></a>
                        <?php if (@$row->stock_modified_date): ?>
                            <div style="font-size: 12px;">Cập nhật số lượng: <span class="price"><?= EshopHelper::renderDate($row->stock_modified_date, 'd/m/Y H:i:s') ?></span></div>
                        <?php endif; ?>
                        <?php if (@$row->price_modified_date): ?>
                            <div style="font-size: 12px;">Cập nhật giá: <span class="price"><?= EshopHelper::renderDate($row->price_modified_date, 'd/m/Y H:i:s') ?></span></div>
                        <?php endif; ?>
                    </td>
                    <td class="text_center">
                        <?php echo $row->product_sku; ?>
                    </td>
                    <td class="text_center">
                        <?php
                        if (JFile::exists(JPATH_ROOT . '/media/com_eshop/products/' . $row->product_image)) {
                            $viewImage = JFile::stripExt($row->product_image) . '-100x100.' . JFile::getExt($row->product_image);

                            if (Jfile::exists(JPATH_ROOT . '/media/com_eshop/products/resized/' . $viewImage)) {
                                ?>
                                <img src="<?php echo $rootUri . 'media/com_eshop/products/resized/' . $viewImage; ?>"
                                     width="50"/>
                                <?php
                            } else {
                                ?>
                                <img src="<?php echo $rootUri . 'media/com_eshop/products/' . $row->product_image; ?>"
                                     width="50"/>
                                <?php
                            }
                        }
                        ?>
                        <a target="_blank"
                           href="<?php echo JURI::root() ?>index.php?option=com_eshop&view=product&id=<?php echo $row->id; ?>&catid=<?php echo $this->getCat($row->id); ?>&Itemid=132">Tạo
                            Thumnail</a>
                    </td>
                    <td class="text_center">
                        <?php
                        $productPriceArray = EshopHelper::getProductPriceArray($row->id, $row->product_price);

                        if ($productPriceArray['salePrice'] >= 0) {
                            ?>
                            <span class="base-price"><?php echo $this->currency->format($productPriceArray['basePrice'], EshopHelper::getConfigValue('default_currency_code')); ?></span>&nbsp;
                            <span class="sale-price"><?php echo $this->currency->format($productPriceArray['salePrice'], EshopHelper::getConfigValue('default_currency_code')); ?></span>
                            <?php
                        } else {
                            ?>
                            <span class="price"><?php echo $this->currency->format($productPriceArray['basePrice'], EshopHelper::getConfigValue('default_currency_code')); ?></span>
                            <?php
                        }
                        ?>
                    </td>
                    <td class="text_center">
                      <?php echo $row->number_pv;  ?>

                    </td>
                    <!-- <td class="text_center">
                        <?php echo $row->product_quantity; ?>
                    </td> -->
                    <td class="text_center">
                        <?php
                        $categories = EshopHelper::getProductCategories($row->id);

                        for ($j = 0; $m = count($categories), $j < $m; $j++) {
                            $category = $categories[$j];
                            $editCategoryLink = JRoute::_('index.php?option=com_eshop&task=category.edit&cid[]=' . $category->id);
                            $dividedChar = ($j < ($m - 1)) ? ' | ' : '';
                            ?>
                            <a
                            href='<?php echo $editCategoryLink; ?>'><?php echo $category->category_name; ?></a><?php echo $dividedChar; ?>
                            <?php
                        }
                        ?>
                    </td>
                    <!--<td class="text_center">
                        <?php
                    /*                        $manufacturer = EshopHelper::getProductManufacturer($row->id);

                                            if (is_object($manufacturer)) {
                                                $editManufacturerLink = JRoute::_('index.php?option=com_eshop&task=manufacturer.edit&cid[]=' . $manufacturer->id);
                                                */ ?>
                            <a href='<?php /*echo $editManufacturerLink; */ ?>'><?php /*echo $manufacturer->manufacturer_name; */ ?></a>
                            <?php
                    /*                        }
                                            */ ?>

                    </td>-->
                    <td class="text_center">
                        <?php echo $this->featured($row->product_featured, $i); ?>
                    </td>
                    <?php foreach ($extraCols as $excol):
                        $name = 'group_' . $excol['id'];
                        $value = isset($row->$name) ? 1 : 0;
                        ?>
                        <td class="text_center">
                            <?php echo $this->extraAttribute($value, $i, true, $excol); ?>
                        </td>
                    <?php endforeach; ?>
                    <td class="text_center">
                        <?php echo $published; ?>
                    </td>
                    <td class="text_center">
                        <?php echo $row->id; ?>
                    </td>
                </tr>
                <?php
                $k = 1 - $k;
            }
            ?>
            </tbody>
        </table>
        <div class="clearfix"></div>
    </div>
    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="boxchecked" value="0"/>
    <input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>"/>
    <input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>"/>
    <input type="hidden" id="filter_full_ordering" name="filter_full_ordering" value=""/>
    <?php
    // Load the batch processing form
    echo JHtml::_(
        'bootstrap.renderModal',
        'collapseModal',
        array(
            'title' => JText::_('ESHOP_PRODUCTS_BATCH_OPTIONS'),
            'footer' => $this->loadTemplate('batch_footer')
        ),
        $this->loadTemplate('batch_body')
    );

    echo JHtml::_('form.token');
    ?>
</form>

<?php

$user   = JFactory::getUser();
$groups = $user->get('groups');
$hide_delete = 0;
foreach ($groups as $group)
{
    if($group == 7){
      $hide_delete = 1;
    }
}

?>
<style>
<?php if($hide_delete == 1){

?>
#toolbar-delete{display: none;}
<?php } ?>
</style>
