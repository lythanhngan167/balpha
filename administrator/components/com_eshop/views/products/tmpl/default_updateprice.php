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
    #isisJsData{display: none;}
    .nav-tabs{
      display: none;
    }
    .manufacturer .hzn-container{
      width: 110px!important;
    }
    #manufacturer_1164_chzn,.chzn-container-single,.hzn-container{
      width: 110px!important;
    }
</style>
<form action="index.php?option=com_eshop&view=products&template=updateprice" method="post" name="adminForm" id="adminForm">
    <div id="j-main-container">

        <div class="clearfix"></div>
        <table class="adminlist table table-striped" id="recordsList">
            <thead>
            <tr>
              <th width="5%" class="text_center">
                 STT
              </th>
                <th width="5%" class="text_center">
                    <?php echo JHtml::_('searchtools.sort', JText::_('JSTATUS'), 'a.published', $this->lists['order_Dir'], $this->lists['order']); ?>
                </th>
                <th class="text_left" width="20%">
                    <?php echo JHtml::_('searchtools.sort', JText::_('ESHOP_NAME'), 'b.product_name', $this->lists['order_Dir'], $this->lists['order']); ?>
                </th>
                <th class="text_center" width="10%">
                    <?php echo JHtml::_('searchtools.sort', JText::_('ESHOP_PRODUCT_SKU'), 'a.product_sku', $this->lists['order_Dir'], $this->lists['order']); ?>
                </th>
                <th class="text_center" width="10%">
                    Hình
                </th>
                <th class="text_left" width="30%">
                    <?php echo JHtml::_('searchtools.sort', JText::_('ESHOP_PRODUCT_PRICE'), 'a.product_price', $this->lists['order_Dir'], $this->lists['order']); ?>
                </th>
                <!--<th class="text_center" width="10%">
                    <?php /*echo JHtml::_('searchtools.sort', JText::_('ESHOP_PRODUCT_COST'), 'a.product_cost', $this->lists['order_Dir'], $this->lists['order']); */ ?>
                </th>-->
                <!-- <th class="text_center" width="10%">
                    <?php echo JHtml::_('searchtools.sort', JText::_('ESHOP_PRODUCT_QUANTITY'), 'a.product_quantity', $this->lists['order_Dir'], $this->lists['order']); ?>
                </th> -->
                <th class="text_center" width="10%">
                    <?php echo JText::_('ESHOP_CATEGORY'); ?>
                </th>
                <th class="text_center" width="20%">
                    <?php echo JHtml::_('searchtools.sort', JText::_('ESHOP_MANUFACTURER'), 'a.manufacturer_id', $this->lists['order_Dir'], $this->lists['order']);  ?>
                </th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <td colspan="6">
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
                  <td class="text_center">
                      <?php echo $i+1; ?>
                  </td>
                  <td class="text_center">

                      <div class="btn-group">
                        <div style="display:none;" id="status2-<?php echo $row->id; ?>"><?php if($row->published == 1){ echo '1'; }else { echo '0'; } ?></div>
                        <a class="btn btn-micro active hasTooltip" onclick="changeStatus(<?php echo $row->id; ?>)">

                          <?php if($row->published == 1){ ?>
                          <span class="icon-publish" id="publish-<?php echo $row->id; ?>" aria-hidden="true"></span>

                        <?php } ?>
                        <?php if($row->published == 0){ ?>
                        <span class="icon-unpublish" id="publish-<?php echo $row->id; ?>" aria-hidden="true"></span>

                      <?php } ?>
                        </a>
                          <?php
                          //echo $published;
                          //echo $this->featured($row->product_featured, $i);
                          //echo $this->addDropdownList(JText::_('ESHOP_COPY'), 'copy', $i, 'product.copy');
                          //echo $this->addDropdownList(JText::_('ESHOP_DELETE'), 'trash', $i, 'product.remove');
                          //echo $this->renderDropdownList($this->escape($row->product_name));
                          ?>
                      </div>
                  </td>
                    <td class="text_left">
                        <a href="<?php echo $link; ?>" target="_blank"><?php echo $row->product_name; ?></a>
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

                    </td>
                    <td class="text_left">
                        <?php
                        $langTag = JFactory::getLanguage()->getTag();
                        $productPriceArray = EshopHelper::getProductPriceArray($row->id, $row->product_price);

                        if ($productPriceArray['salePrice'] >= 0) {
                            ?>
                            <span class="base-price" id="price-base-text-<?php echo $row->id;  ?>"><?php echo $this->currency->format($productPriceArray['basePrice'], EshopHelper::getConfigValue('default_currency_code')); ?></span>&nbsp;
                            <span class="sale-price" id="price-sale-text-<?php echo $row->id;  ?>"><?php echo $this->currency->format($productPriceArray['salePrice'], EshopHelper::getConfigValue('default_currency_code')); ?></span>
                            <?php
                        } else {
                            ?>
                            <span class="price" id="price-text-<?php echo $row->id;  ?>"><?php echo $this->currency->format($productPriceArray['basePrice'], EshopHelper::getConfigValue('default_currency_code')); ?></span> / <?php echo EshopHelper::getWeightUnitName($row->product_weight_id,$langTag); ?>
                            <?php
                        }
                        ?>
                        <div class="price-wrap">
                        <input type="text" class="pro_price" name="pro_price" value="<?php echo $productPriceArray['basePrice']; ?>" id="price-<?php echo $row->id;  ?>">
                        <input type="button" class="btn-price btn-primary" id="btn-price-<?php echo $row->id;  ?>" value="Lưu" onclick="updatePriceProduct(<?php echo $row->id; ?>)">
                      </div>
                    </td>
                    <!--<td class="text_center">
                      <?php /*echo $this->currency->format($row->product_cost, EshopHelper::getConfigValue('default_currency_code')); */ ?>

                    </td>-->
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
                    <td class="text_center manufacturer">
                        <?php

                                          $manufacturer = EshopHelper::getProductManufacturer($row->id);

                                            //if (is_object($manufacturer)) {
                                                //$editManufacturerLink = JRoute::_('index.php?option=com_eshop&task=manufacturer.edit&cid[]=' . $manufacturer->id);
                                                 ?>
                            <!-- <a href='<?php echo $editManufacturerLink;  ?>'><?php echo $manufacturer->manufacturer_name;  ?></a> -->
                            <?php
                                           //}
                                             ?>

                        <?php
                          //print_r($row);
                          $manufacturers = EshopHelper::getAllManufacturer();
                          //print_r($manufacturers);

                        ?>
                        <select onchange="updateManufacturer(<?php echo $row->id; ?>,this.value)" id="manufacturer-<?php echo $row->id; ?>" class="sel-manufacturer">
                          <option value="" <?php if($row->manufacturer_id > 0){ ?> selected <?php } ?>>Chọn Nhà cung cấp</option>
                        <?php foreach($manufacturers as $manu){ ?>

                          <option <?php if($manu->id == $row->manufacturer_id){ ?> selected <?php } ?> value="<?php echo $manu->id; ?>"><?php echo $manu->manufacturer_name; ?></option>
                        <?php } ?>
                        </select>


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

<script type="text/javascript">
        function changeStatus(product_id){
          var status_id = jQuery('#status2-'+product_id).html();
          var r = confirm("Bạn có chắc muốn đổi Trạng thái Sản phẩm?");
          if (r == true) {
            if (status_id != '') {
                jQuery.ajax({
                    url: 'index.php?option=com_eshop&task=products.updateStatus',
                    data: 'product_id=' + product_id + '&status_id=' + status_id,
                    dataType: 'json',
                    beforeSend: function () {

                    },
                    complete: function () {

                    },
                    success: function (json) {
                      if(json['error'] == '0'){

                        if(json['status'] == '1'){
                          jQuery('#publish-'+product_id).attr('class', 'icon-publish');
                          jQuery('#status2-'+product_id).html("1");
                        }else{
                          jQuery('#publish-'+product_id).attr('class', 'icon-unpublish');
                          jQuery('#status2-'+product_id).html("0");
                        }
                      }else{
                        alert("Có lỗi, vui lòng thử lại sau.");
                      }
                    },

                })
            }else{
              alert("Có lỗi, vui lòng thử lại.");
            }
          } else {
            //txt = "You pressed Cancel!";
          }


        }
        function updateManufacturer(product_id, manufacturer_id){
          var r = confirm("Bạn có chắc muốn đổi Nhà cung cấp?");
          if (r == true) {
            if (manufacturer_id != '') {
                jQuery.ajax({
                    url: 'index.php?option=com_eshop&task=products.updateManufacturer',
                    data: 'product_id=' + product_id + '&manufacturer_id=' + manufacturer_id,
                    dataType: 'json',
                    beforeSend: function () {

                    },
                    complete: function () {

                    },
                    success: function (json) {
                      //console.log(json);
                      if(json['error'] == 0){
                        //alert("Cập nhật Nhà cung cấp thành công!");
                      }else{
                        alert("Có lỗi, vui lòng thử lại sau.");
                      }
                    },

                })
            }else{
              alert("Vui lòng chọn Nhà cung cấp!");
            }
          } else {
            //txt = "You pressed Cancel!";
          }

        }
        function updatePriceProduct(product_id) {
            var price = jQuery('#price-'+product_id).val();
            if (price > 0) {

                jQuery.ajax({
                    url: 'index.php?option=com_eshop&task=products.updatePrice',
                    data: 'product_id=' + product_id + '&price=' + price,
                    dataType: 'json',
                    beforeSend: function () {

                    },
                    complete: function () {

                    },
                    success: function (json) {
                      //console.log(json);
                      if(json['error'] == 0){
                        jQuery('#price-text-'+product_id).html(json['price']+" đ");
                        jQuery('#price-text-'+product_id).css("color","orange");
                      }
                    },

                })
            }else{
              alert("Vui lòng nhập Giá lớn hơn 0 và là số nguyên!");
            }
        }
</script>
