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

$translatable = JLanguageMultilang::isEnabled() && count($this->languages) > 1;
$editor = JEditor::getInstance(JFactory::getConfig()->get('editor'));;
$rootUri = JUri::root();

if ($translatable) {
    $rootUri = JUri::root();
    echo JHtml::_('bootstrap.startTabSet', 'general-page-translation', array('active' => 'general-page-translation-' . $this->languages[0]->lang_code));

    foreach ($this->languages as $language) {
        $langId = $language->lang_id;
        $langCode = $language->lang_code;

        echo JHtml::_('bootstrap.addTab', 'general-page-translation', 'general-page-translation-' . $langCode, $this->languageData['title'][$langCode] . ' <img src="' . $rootUri . 'media/com_eshop/flags/' . $this->languageData['flag'][$langCode] . '" />');
        ?>
        <div class="control-group">
            <div class="control-label">
                <?php echo JText::_('ESHOP_MANUFACTURER'); ?>
            </div>
            <div class="controls">
                <?php echo $this->lists['manufacturer']; ?>
            </div>
        </div>
        <div class="control-group">
            <div class="control-label">
                <span class="required">*</span>
                <?php echo JText::_('ESHOP_NAME'); ?>
            </div>
            <div class="controls">
                <input class="input-xlarge" type="text" name="product_name_<?php echo $langCode; ?>"
                       id="product_name_<?php echo $langId; ?>" size="" maxlength="250"
                       value="<?php echo isset($this->item->{'product_name_' . $langCode}) ? $this->item->{'product_name_' . $langCode} : ''; ?>"/>
            </div>
        </div>
        <div class="control-group">
            <div class="control-label">
                <?php echo JText::_('ESHOP_ALIAS'); ?>
            </div>
            <div class="controls">
                <input class="input-xlarge" type="text" name="product_alias_<?php echo $langCode; ?>"
                       id="product_alias_<?php echo $langId; ?>" size="" maxlength="250"
                       value="<?php echo isset($this->item->{'product_alias_' . $langCode}) ? $this->item->{'product_alias_' . $langCode} : ''; ?>"/>
            </div>
        </div>
        <div class="control-group">
            <div class="control-label">
                <?php echo JText::_('ESHOP_PAGE_TITLE'); ?>
            </div>
            <div class="controls">
                <input class="input-xlarge" type="text" name="product_page_title_<?php echo $langCode; ?>"
                       id="product_page_title_<?php echo $langId; ?>" size="" maxlength="250"
                       value="<?php echo isset($this->item->{'product_page_title_' . $langCode}) ? $this->item->{'product_page_title_' . $langCode} : ''; ?>"/>
            </div>
        </div>
        <div class="control-group">
            <div class="control-label">
                <?php echo JText::_('ESHOP_PAGE_HEADING'); ?>
            </div>
            <div class="controls">
                <input class="input-xlarge" type="text" name="product_page_heading_<?php echo $langCode; ?>"
                       id="product_page_heading_<?php echo $langId; ?>" size="" maxlength="250"
                       value="<?php echo isset($this->item->{'product_page_heading_' . $langCode}) ? $this->item->{'product_page_heading_' . $langCode} : ''; ?>"/>
            </div>
        </div>
        <div class="control-group">
            <div class="control-label">
                <?php echo JText::_('ESHOP_ALT_IMAGE'); ?>
            </div>
            <div class="controls">
                <input class="input-xlarge" type="text" name="product_alt_image_<?php echo $langCode; ?>"
                       id="product_alt_image_<?php echo $langId; ?>" size="" maxlength="250"
                       value="<?php echo isset($this->item->{'product_alt_image_' . $langCode}) ? $this->item->{'product_alt_image_' . $langCode} : ''; ?>"/>
            </div>
        </div>


        <div class="control-group">
            <div class="control-label">
                <?php echo JText::_('ESHOP_PRODUCT_SHORT_DESCRIPTION'); ?>
            </div>
            <div class="controls">
                <?php echo $editor->display('product_short_desc_' . $langCode, isset($this->item->{'product_short_desc_' . $langCode}) ? $this->item->{'product_short_desc_' . $langCode} : '', '100%', '100', '75', '10'); ?>
            </div>
        </div>
        <div class="control-group">
            <div class="control-label">
                <?php echo JText::_('ESHOP_DESCRIPTION'); ?>
            </div>
            <div class="controls">
                <?php echo $editor->display('product_desc_' . $langCode, isset($this->item->{'product_desc_' . $langCode}) ? $this->item->{'product_desc_' . $langCode} : '', '100%', '250', '75', '10'); ?>
            </div>
        </div>
        <div class="control-group">
            <div class="control-label">
                <?php echo JText::_('ESHOP_TAB1_TITLE'); ?>
            </div>
            <div class="controls">
                <input class="input-xlarge" type="text" name="tab1_title_<?php echo $langCode; ?>"
                       id="tab1_title_<?php echo $langId; ?>" size="" maxlength="250"
                       value="<?php echo isset($this->item->{'tab1_title_' . $langCode}) ? $this->item->{'tab1_title_' . $langCode} : ''; ?>"/>
            </div>
        </div>
        <div class="control-group">
            <div class="control-label">
                <?php echo JText::_('ESHOP_TAB1_CONTENT'); ?>
            </div>
            <div class="controls">
                <?php echo $editor->display('tab1_content_' . $langCode, isset($this->item->{'tab1_content_' . $langCode}) ? $this->item->{'tab1_content_' . $langCode} : '', '100%', '250', '75', '10'); ?>
            </div>
        </div>
        <div class="control-group">
            <div class="control-label">
                <?php echo JText::_('ESHOP_TAB2_TITLE'); ?>
            </div>
            <div class="controls">
                <input class="input-xlarge" type="text" name="tab2_title_<?php echo $langCode; ?>"
                       id="tab2_title_<?php echo $langId; ?>" size="" maxlength="250"
                       value="<?php echo isset($this->item->{'tab2_title_' . $langCode}) ? $this->item->{'tab2_title_' . $langCode} : ''; ?>"/>
            </div>
        </div>
        <div class="control-group">
            <div class="control-label">
                <?php echo JText::_('ESHOP_TAB2_CONTENT'); ?>
            </div>
            <div class="controls">
                <?php echo $editor->display('tab2_content_' . $langCode, isset($this->item->{'tab2_content_' . $langCode}) ? $this->item->{'tab2_content_' . $langCode} : '', '100%', '250', '75', '10'); ?>
            </div>
        </div>
        <div class="control-group">
            <div class="control-label">
                <?php echo JText::_('ESHOP_TAB3_TITLE'); ?>
            </div>
            <div class="controls">
                <input class="input-xlarge" type="text" name="tab3_title_<?php echo $langCode; ?>"
                       id="tab3_title_<?php echo $langId; ?>" size="" maxlength="250"
                       value="<?php echo isset($this->item->{'tab3_title_' . $langCode}) ? $this->item->{'tab3_title_' . $langCode} : ''; ?>"/>
            </div>
        </div>
        <div class="control-group">
            <div class="control-label">
                <?php echo JText::_('ESHOP_TAB3_CONTENT'); ?>
            </div>
            <div class="controls">
                <?php echo $editor->display('tab3_content_' . $langCode, isset($this->item->{'tab3_content_' . $langCode}) ? $this->item->{'tab3_content_' . $langCode} : '', '100%', '250', '75', '10'); ?>
            </div>
        </div>
        <div class="control-group">
            <div class="control-label">
                <?php echo JText::_('ESHOP_TAB4_TITLE'); ?>
            </div>
            <div class="controls">
                <input class="input-xlarge" type="text" name="tab4_title_<?php echo $langCode; ?>"
                       id="tab4_title_<?php echo $langId; ?>" size="" maxlength="250"
                       value="<?php echo isset($this->item->{'tab4_title_' . $langCode}) ? $this->item->{'tab4_title_' . $langCode} : ''; ?>"/>
            </div>
        </div>
        <div class="control-group">
            <div class="control-label">
                <?php echo JText::_('ESHOP_TAB4_CONTENT'); ?>
            </div>
            <div class="controls">
                <?php echo $editor->display('tab4_content_' . $langCode, isset($this->item->{'tab4_content_' . $langCode}) ? $this->item->{'tab4_content_' . $langCode} : '', '100%', '250', '75', '10'); ?>
            </div>
        </div>
        <div class="control-group">
            <div class="control-label">
                <?php echo JText::_('ESHOP_TAB5_TITLE'); ?>
            </div>
            <div class="controls">
                <input class="input-xlarge" type="text" name="tab5_title_<?php echo $langCode; ?>"
                       id="tab5_title_<?php echo $langId; ?>" size="" maxlength="250"
                       value="<?php echo isset($this->item->{'tab5_title_' . $langCode}) ? $this->item->{'tab5_title_' . $langCode} : ''; ?>"/>
            </div>
        </div>
        <div class="control-group">
            <div class="control-label">
                <?php echo JText::_('ESHOP_TAB5_CONTENT'); ?>
            </div>
            <div class="controls">
                <?php echo $editor->display('tab5_content_' . $langCode, isset($this->item->{'tab5_content_' . $langCode}) ? $this->item->{'tab5_content_' . $langCode} : '', '100%', '250', '75', '10'); ?>
            </div>
        </div>
        <div class="control-group">
            <div class="control-label">
                <?php echo JText::_('ESHOP_META_KEYS'); ?>
            </div>
            <div class="controls">
                <textarea rows="5" cols="30"
                          name="meta_key_<?php echo $langCode; ?>"><?php echo $this->item->{'meta_key_' . $langCode}; ?></textarea>
            </div>
        </div>
        <div class="control-group">
            <div class="control-label">
                <?php echo JText::_('ESHOP_META_DESC'); ?>
            </div>
            <div class="controls">
                <textarea rows="5" cols="30"
                          name="meta_desc_<?php echo $langCode; ?>"><?php echo $this->item->{'meta_desc_' . $langCode}; ?></textarea>
            </div>
        </div>
        <?php
        echo JHtml::_('bootstrap.endTab');
    }

    echo JHtml::_('bootstrap.endTabSet');
} else {
    ?>
    <?php if ($this->item): ?>
        <?php if (@$this->item->stock_modified_date): ?>
            <div class="control-group">
                <div class="control-label">
                    Cập nhật số lượng
                </div>
                <div class="controls price" style="color: red;">
                    <?= EshopHelper::renderDate($this->item->stock_modified_date, 'd/m/Y H:i:s') ?>
                </div>
            </div>
        <?php endif; ?>
        <?php if (@$this->item->price_modified_date): ?>
            <div class="control-group">
                <div class="control-label">
                    Cập nhật giá
                </div>
                <div class="controls price" style="color: red;">
                    <?= EshopHelper::renderDate($this->item->price_modified_date, 'd/m/Y H:i:s') ?>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
    <div class="control-group">
        <div class="control-label">
            <span class="required">*</span>
            <?php echo JText::_('ESHOP_NAME'); ?>
        </div>
        <div class="controls">
            <input class="input-xlarge" type="text" name="product_name" id="product_name" size="" maxlength="250"
                   value="<?php echo $this->item->product_name; ?>"/>
        </div>
    </div>
    <div class="control-group ">
        <div class="control-label">
            <?php echo JText::_('ESHOP_ALIAS'); ?>
        </div>
        <div class="controls">
            <input class="input-xlarge" type="text" name="product_alias" id="product_alias" size="" maxlength="250"
                   value="<?php echo $this->item->product_alias; ?>"/> ( Tự động sinh ra khi nhập "Tên" )
        </div>
    </div>
    <div class="control-group">
        <div class="control-label">
            <span class="required">*</span>
            <?php echo JText::_('ESHOP_PRODUCT_SKU'); ?>
        </div>
        <div class="controls">
            <input class="input-medium" type="text" name="product_sku" id="product_sku" size="" maxlength="250"
                   value="<?php echo $this->item->product_sku; ?>"/>
        </div>
    </div>
    <div class="control-group">
        <div class="control-label">
            <span class="required">*</span>
            <?php echo JText::_('ESHOP_MAIN_CATEGORY'); ?>
        </div>
        <div class="controls">
            <?php echo $this->lists['main_category_id']; ?>
        </div>
    </div>

    <div class="control-group">
        <div class="control-label">
            <?php echo EshopHtmlHelper::getFieldLabel('product_price', JText::_('ESHOP_PRODUCT_PRICE'), JText::_('ESHOP_PRODUCT_PRICE_HELP')); ?>
        </div>
        <div class="controls">
            <input class="input-small" type="text" name="product_price" id="product_price" size="" maxlength="250"
                   value="<?php echo round($this->item->product_price); ?>"/> <?php echo BIZ_XU; ?>
        </div>
    </div>

    <div class="control-group">
        <div class="control-label">
          <span class="required">*</span>
            PV Sản phẩm
        </div>
        <div class="controls">
          <?php
            if($this->item->id <= 0){
              $this->item->number_pv = 0;
            }
          ?>
            <input class="input-small" type="number" min="0" max="1000000" name="number_pv" id="number_pv" value="<?php echo $this->item->number_pv; ?>"/>
        </div>
    </div>

    <div class="control-group">
        <div class="control-label">
            <?php echo JText::_('ESHOP_PRODUCT_QUANTITY'); ?>
        </div>
        <div class="controls">
            <?php
            // if($this->item->product_quantity <= 100){
            //   $this->item->product_quantity = 100000;
            // }
            ?>
            <input class="input-small" type="text" name="product_quantity" id="product_quantity" size="" maxlength="250"
                   value="<?php echo $this->item->product_quantity; ?>"/>
        </div>
    </div>

    <div class="control-group">
        <div class="control-label">
            <?php echo JText::_('ESHOP_PRODUCT_WEIGHT_UNIT'); ?>
        </div>
        <div class="controls">
            <?php echo $this->lists['product_weight_id']; ?>
        </div>
    </div>

    <div class="control-group">
        <div class="control-label">
            <?php echo JText::_('ESHOP_MANUFACTURER'); ?>
        </div>
        <div class="controls">
            <?php echo $this->lists['manufacturer']; ?>
        </div>
    </div>

    <div class="control-group" style="display:none;">
        <div class="control-label">
            <!-- <span class="required">*</span><?php echo JText::_('ESHOP_NAME_STOCK'); ?> -->
        </div>
        <div class="controls">
            <?php
            // $listObjectStock = $this->getListStock();
            // $selected_stock = array();
            // $listStockProduct = $this->getListStockByProduct($this->item->id);
            //
            // if(count($listStockProduct) > 0){
            // 	foreach($listStockProduct as $stockproduct){
            // 		$selected_stock[] = $stockproduct->stock_id;
            // 	}
            // }
            //
            // foreach($listObjectStock as $stock){
            // 	$listStock[]       = JHTML::_('select.option',  $stock->id, $stock->title, 'choose_stock', 'name_stock' );
            // }
            // echo  JHTML::_('select.genericlist',  $listStock, 'choose_stock[]', 'multiple="multiple" class="inputbox chosen" size="4" ', 'choose_stock',
            //        'name_stock', $selected_stock);
            ?>
        </div>
    </div>
    <div class="control-group" style="display:none;">
        <div class="controls">
            <div id="stock-quantity">
                <h4 style="color:orange; display:none;">Nhập số lượng trong mỗi Kho</h4>
                <?php
                // foreach ($listObjectStock as $stock){
                // 	$inList = 0;
                // 	$quantity_stock = 0;
                // 	if(count($listStockProduct) > 0){
                // 		foreach($listStockProduct as $stockproduct){
                // 			if($stock->id == $stockproduct->stock_id){
                // 				$inList = 1;
                // 				$quantity_stock = $stockproduct->qty;
                // 			}
                // 		}
                // 	}

                ?>
                <!-- <div <?php if ($inList == 1) { ?>style="display:block"<?php } else { ?>style="display:none"<?php } ?> class="stock-item" id="stock-item-<?php echo $stock->id; ?>">
							<div class="stock-name">Kho: <b><?php echo $stock->title; ?></b></div>
							<div class="stock-input-quantity">
								Số lương: <input class="input-small" type="number" min="0" max="1000000" name="stock-quantity-<?php echo $stock->id; ?>" id="stock-quantity-<?php echo $stock->id; ?>" maxlength="7" value="<?php echo $quantity_stock; ?>" />
							</div>
						</div>
						<br> -->
                <?php //}
                ?>
            </div>
        </div>
    </div>

    <div class="control-group">
        <div class="control-label">
            <?php echo JText::_('ESHOP_PRODUCT_WEIGHT'); ?>
        </div>
        <div class="controls">
            <input class="input-small" type="text" name="product_weight" id="product_weight" size="" maxlength="250"
                   value="<?php echo $this->item->product_weight; ?>"/> kg
        </div>
    </div>






    <div class="control-group">
        <div class="control-label">
            <?php echo EshopHtmlHelper::getFieldLabel('product_cost', JText::_('ESHOP_PRODUCT_COST'), JText::_('ESHOP_PRODUCT_COST_HELP')); ?>
        </div>
        <div class="controls">
            <input class="input-small" type="text" name="product_cost" id="product_cost" size="" maxlength="250"
                   value="<?php echo round($this->item->product_cost); ?>"/> <?php echo BIZ_XU; ?>
        </div>
    </div>
    <div class="control-group">
        <div class="control-label">
            <?php echo JText::_('ESHOP_IMAGE'); ?>
        </div>
        <div class="controls">
            <input type="file" class="input-large" accept="image/*" name="product_image"/>
            <?php
            if (JFile::exists(JPATH_ROOT . '/media/com_eshop/products/' . $this->item->product_image)) {
                $viewImage = JFile::stripExt($this->item->product_image) . '-100x100.' . JFile::getExt($this->item->product_image);

                if (Jfile::exists(JPATH_ROOT . '/media/com_eshop/products/resized/' . $viewImage)) {
                    ?>
                    <img width="100" height="100" src="<?php echo $rootUri . 'media/com_eshop/products/resized/' . $viewImage; ?>"/>
                    <?php
                } else {
                    ?>
                    <img width="100" src="<?php echo $rootUri . 'media/com_eshop/products/' . $this->item->product_image; ?>"
                         height="100"/>
                    <?php
                }
                ?>
                <input type="checkbox" name="remove_image" value="1"/>
                <?php echo JText::_('ESHOP_REMOVE_IMAGE'); ?>
                <?php
            }
            ?>
        </div>
    </div>


    <div class="control-group displaynone">
        <div class="control-label">
            <?php echo JText::_('ESHOP_ADDITIONAL_CATEGORIES'); ?>
        </div>
        <div class="controls">
            <?php echo $this->lists['category_id']; ?>
        </div>
    </div>


    <div class="control-group displaynone" id="call-for-price">
        <div class="control-label">
            <?php echo JText::_('ESHOP_PRODUCT_CALL_FOR_PRICE'); ?>
        </div>
        <div class="controls">
            <?php echo $this->lists['product_call_for_price']; ?>
        </div>
    </div>



    <div class="control-group">
        <div class="control-label">
            <?php echo JText::_('ESHOP_PUBLISHED'); ?>
        </div>
        <div class="controls">
            <?php echo $this->lists['published']; ?>
        </div>
    </div>
    <div class="control-group">
        <div class="control-label">
            <?php echo JText::_('ESHOP_PRODUCT_FEATURED'); ?>
        </div>
        <div class="controls">
            <?php echo $this->lists['featured']; ?>
        </div>
    </div>

    <?php if ($this->lists['extra']): ?>
        <?php foreach ($this->lists['extra'] as $att): ?>
            <div class="control-group">
                <div class="control-label">
                    <?php echo $att['title']; ?>
                </div>
                <div class="controls">
                    <?php echo $att['input']; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>


    <div class="control-group">
        <div class="control-label">
            <?php echo JText::_('ESHOP_PRODUCT_SHORT_DESCRIPTION'); ?>
        </div>
        <div class="controls">
            <?php echo $editor->display('product_short_desc', $this->item->product_short_desc, '100%', '100', '75', '10'); ?>
        </div>
    </div>
    <div class="control-group">
        <div class="control-label">
            <?php echo JText::_('ESHOP_DESCRIPTION'); ?>
        </div>
        <div class="controls">
            <?php echo $editor->display('product_desc', $this->item->product_desc, '100%', '250', '75', '10'); ?>
        </div>
    </div>


    <div class="control-group displaynone">
        <div class="control-label">
            <?php echo JText::_('ESHOP_PAGE_TITLE'); ?>
        </div>
        <div class="controls">
            <input class="input-xlarge" type="text" name="product_page_title" id="product_page_title" size=""
                   maxlength="250" value="<?php echo $this->item->product_page_title; ?>"/>
        </div>
    </div>
    <div class="control-group displaynone">
        <div class="control-label">
            <?php echo JText::_('ESHOP_PAGE_HEADING'); ?>
        </div>
        <div class="controls">
            <input class="input-xlarge" type="text" name="product_page_heading" id="product_page_heading" size=""
                   maxlength="250" value="<?php echo $this->item->product_page_heading; ?>"/>
        </div>
    </div>
    <div class="control-group displaynone">
        <div class="control-label">
            <?php echo JText::_('ESHOP_ALT_IMAGE'); ?>
        </div>
        <div class="controls">
            <input class="input-xlarge" type="text" name="product_alt_image" id="product_alt_image" size=""
                   maxlength="250" value="<?php echo $this->item->product_alt_image; ?>"/>
        </div>
    </div>
    <div class="control-group displaynone">
        <div class="control-label">
            <?php echo JText::_('ESHOP_TAB1_TITLE'); ?>
        </div>
        <div class="controls">
            <input class="input-xlarge" type="text" name="tab1_title" id="tab1_title" size="" maxlength="250"
                   value="<?php echo $this->item->tab1_title; ?>"/>
        </div>
    </div>
    <div class="control-group displaynone">
        <div class="control-label">
            <?php echo JText::_('ESHOP_TAB1_CONTENT'); ?>
        </div>
        <div class="controls">
            <?php echo $editor->display('tab1_content', $this->item->tab1_content, '100%', '250', '75', '10'); ?>
        </div>
    </div>
    <div class="control-group displaynone">
        <div class="control-label">
            <?php echo JText::_('ESHOP_TAB2_TITLE'); ?>
        </div>
        <div class="controls">
            <input class="input-xlarge" type="text" name="tab2_title" id="tab2_title" size="" maxlength="250"
                   value="<?php echo $this->item->tab2_title; ?>"/>
        </div>
    </div>
    <div class="control-group displaynone">
        <div class="control-label">
            <?php echo JText::_('ESHOP_TAB2_CONTENT'); ?>
        </div>
        <div class="controls">
            <?php echo $editor->display('tab2_content', $this->item->tab2_content, '100%', '250', '75', '10'); ?>
        </div>
    </div>
    <div class="control-group displaynone">
        <div class="control-label">
            <?php echo JText::_('ESHOP_TAB3_TITLE'); ?>
        </div>
        <div class="controls">
            <input class="input-xlarge" type="text" name="tab3_title" id="tab3_title" size="" maxlength="250"
                   value="<?php echo $this->item->tab3_title; ?>"/>
        </div>
    </div>
    <div class="control-group displaynone">
        <div class="control-label">
            <?php echo JText::_('ESHOP_TAB3_CONTENT'); ?>
        </div>
        <div class="controls">
            <?php echo $editor->display('tab3_content', $this->item->tab3_content, '100%', '250', '75', '10'); ?>
        </div>
    </div>
    <div class="control-group displaynone">
        <div class="control-label">
            <?php echo JText::_('ESHOP_TAB4_TITLE'); ?>
        </div>
        <div class="controls">
            <input class="input-xlarge" type="text" name="tab4_title" id="tab4_title" size="" maxlength="250"
                   value="<?php echo $this->item->tab4_title; ?>"/>
        </div>
    </div>
    <div class="control-group displaynone">
        <div class="control-label">
            <?php echo JText::_('ESHOP_TAB4_CONTENT'); ?>
        </div>
        <div class="controls">
            <?php echo $editor->display('tab4_content', $this->item->tab4_content, '100%', '250', '75', '10'); ?>
        </div>
    </div>
    <div class="control-group displaynone">
        <div class="control-label">
            <?php echo JText::_('ESHOP_TAB5_TITLE'); ?>
        </div>
        <div class="controls">
            <input class="input-xlarge" type="text" name="tab5_title" id="tab5_title" size="" maxlength="250"
                   value="<?php echo $this->item->tab5_title; ?>"/>
        </div>
    </div>
    <div class="control-group displaynone">
        <div class="control-label">
            <?php echo JText::_('ESHOP_TAB5_CONTENT'); ?>
        </div>
        <div class="controls">
            <?php echo $editor->display('tab5_content', $this->item->tab5_content, '100%', '250', '75', '10'); ?>
        </div>
    </div>
    <div class="control-group displaynone">
        <div class="control-label">
            <?php echo JText::_('ESHOP_META_KEYS'); ?>
        </div>
        <div class="controls">
            <textarea rows="5" cols="30" name="meta_key"><?php echo $this->item->meta_key; ?></textarea>
        </div>
    </div>
    <div class="control-group displaynone">
        <div class="control-label">
            <?php echo JText::_('ESHOP_META_DESC'); ?>
        </div>
        <div class="controls">
            <textarea rows="5" cols="30" name="meta_desc"><?php echo $this->item->meta_desc; ?></textarea>
        </div>
    </div>
    <script>
        js = jQuery.noConflict();

        js(document).ready(function () {
            js('#choose_stock').change(function () {
                js('#stock-quantity h4').css('display', 'block');
                var idstock = String(js(this).val());
                var list_id_stock = idstock.split(',');

                js('.stock-item').css('display', 'none');
                list_id_stock.forEach(function (stock_id) {
                    js('#stock-item-' + stock_id).css('display', 'block');
                });

            });
        });
    </script>
    <style>
        .displaynone {
            display: none;
        }
    </style>
    <?php
}
