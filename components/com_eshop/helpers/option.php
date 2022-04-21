<?php
/**
 * @version        3.1.0
 * @package        Joomla
 * @subpackage     EShop
 * @author         Giang Dinh Truong
 * @copyright      Copyright (C) 2012 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die;

/**
 * Options helper class
 *
 */
class EshopOption
{
    /**
     *
     * Function to render an option input for a product
     *
     * @param int $productId
     * @param int $optionId
     * @param int $optionType
     * @param int $taxClassId
     *
     * @return string code
     */
    public static function renderOption($productId, $optionId, $optionType, $taxClassId)
    {
        $currency = new EshopCurrency();
        $tax = new EshopTax(EshopHelper::getConfig());
        $bootstrapHelper = new EshopHelperBootstrap(EshopHelper::getConfigValue('twitter_bootstrap_version'));
        $product = EshopHelper::getProduct($productId);
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('id')
            ->from('#__eshop_productoptions')
            ->where('product_id = ' . intval($productId))
            ->where('option_id = ' . intval($optionId));
        $db->setQuery($query);
        $productOptionId = $db->loadResult();

        switch ($optionType) {
            case 'Text':
            case 'Textarea':
                $query->clear()
                    ->select('price, price_sign, price_type')
                    ->from('#__eshop_productoptionvalues')
                    ->where('product_option_id = ' . intval($productOptionId));
                break;
            default:
                $query->clear()
                    ->select('ovd.value, pov.id, pov.price, pov.price_sign, pov.price_type, pov.image')
                    ->from('#__eshop_optionvalues AS ov')
                    ->innerJoin('#__eshop_optionvaluedetails AS ovd ON (ov.id = ovd.optionvalue_id)')
                    ->innerJoin('#__eshop_productoptionvalues AS pov ON (ovd.optionvalue_id = pov.option_value_id)')
                    ->where('ov.published = 1')
                    ->where('pov.product_option_id = ' . intval($productOptionId))
                    ->where('ovd.language = "' . JFactory::getLanguage()->getTag() . '"')
                    ->order('pov.id');

                if (EshopHelper::getConfigValue('hide_out_of_stock_products')) {
                    $query->where('pov.quantity > 0');
                }
                break;
        }

        $db->setQuery($query);
        $rows = $db->loadObjectList();
        $optionHtml = '';
        $optionImage = '';
        $imagePath = JPATH_ROOT . '/media/com_eshop/options/';
        $imageSizeFunction = EshopHelper::getConfigValue('option_image_size_function', 'resizeImage');
        $thumbImageSizeFunction = EshopHelper::getConfigValue('thumb_image_size_function', 'resizeImage');
        $popupImageSizeFunction = EshopHelper::getConfigValue('popup_image_size_function', 'resizeImage');
        $baseUri = JUri::base(true);

        for ($i = 0; $n = count($rows), $i < $n; $i++) {
            $row = $rows[$i];

            if (EshopHelper::showPrice() && $row->price > 0 && !$product->product_call_for_price) {
                if (EshopHelper::getConfigValue('display_option_price', 'only_option_price') == 'only_option_price') {
                    if ($optionType == 'Text' || $optionType == 'Textarea') {
                        if ($row->price_type == 'P') {
                            $rows[$i]->text = '(' . $row->price_sign . number_format($row->price, 2) . '% ' . JText::_('ESHOP_PER_CHAR') . ')';
                        } else {
                            $rows[$i]->text = '(' . $row->price_sign . $currency->format($tax->calculate($row->price, $taxClassId, EshopHelper::getConfigValue('tax'))) . ' ' . JText::_('ESHOP_PER_CHAR') . ')';
                        }
                    } else {
                        if ($row->price_type == 'P') {
                            $rows[$i]->text = $row->value ;//. ' (' . $row->price_sign . number_format($row->price, 2) . '%' . ')';
                        } else {
                            $rows[$i]->text = $row->value;// . ' (' . $row->price_sign . $currency->format($tax->calculate($row->price, $taxClassId, EshopHelper::getConfigValue('tax'))) . ')';
                        }
                    }
                } else {
                    $rows[$i]->text = isset($row->value) ? $row->value : '';
                }
            } else {
                $rows[$i]->text = isset($row->value) ? $row->value : '';
            }

            if ($optionType != 'Text' && $optionType != 'Textarea') {
                $rows[$i]->value = $row->id;
            }

            //Resize option image
            if (isset($row->image) && $row->image != '') {
                if (EshopHelper::getConfigValue('product_use_image_watermarks')) {
                    $watermarkImage = EshopHelper::generateWatermarkImage(JPATH_ROOT . '/media/com_eshop/options/' . $row->image);
                    $tempImage = $watermarkImage;
                } else {
                    $tempImage = $row->image;
                }

                $thumbImage = call_user_func_array(array('EshopHelper', $thumbImageSizeFunction), array($tempImage, $imagePath, EshopHelper::getConfigValue('image_thumb_width'), EshopHelper::getConfigValue('image_thumb_height')));
                $popupImage = call_user_func_array(array('EshopHelper', $popupImageSizeFunction), array($tempImage, $imagePath, EshopHelper::getConfigValue('image_popup_width'), EshopHelper::getConfigValue('image_popup_height')));
                $rows[$i]->thumb_image = $baseUri . '/media/com_eshop/options/resized/' . $thumbImage;
                $rows[$i]->popup_image = $baseUri . '/media/com_eshop/options/resized/' . $popupImage;

                $imageWidth = EshopHelper::getConfigValue('image_option_width');

                if (!$imageWidth) {
                    $imageWidth = 100;
                }

                $imageHeight = EshopHelper::getConfigValue('image_option_height');

                if (!$imageHeight) {
                    $imageHeight = 100;
                }

                if (!JFile::exists($imagePath . 'resized/' . JFile::stripExt($row->image) . '-' . $imageWidth . 'x' . $imageHeight . '.' . JFile::getExt($row->image))) {
                    $rows[$i]->image = $baseUri . '/media/com_eshop/options/resized/' . call_user_func_array(array('EshopHelper', $imageSizeFunction), array($tempImage, $imagePath, $imageWidth, $imageHeight));
                } else {
                    $rows[$i]->image = $baseUri . '/media/com_eshop/options/resized/' . JFile::stripExt($row->image) . '-' . $imageWidth . 'x' . $imageHeight . '.' . JFile::getExt($row->image);
                }

                if (EshopHelper::getConfigValue('view_image') == 'zoom') {
                    $optionImage .= '<a id="option-image-' . $rows[$i]->id . '" class="option-image-zoom" href="javascript:void(0);" rel="{gallery: \'product-thumbnails\', smallimage: \'' . $rows[$i]->thumb_image . '\',largeimage: \'' . $rows[$i]->popup_image . '\'}">
										<img src="' . $rows[$i]->image . '">
									</a>';
                } else {
                    $optionImage .= '<a id="option-image-' . $rows[$i]->id . '" class="product-image" href="' . $rows[$i]->popup_image . '">
										<img src="' . $rows[$i]->thumb_image . '" title="' . $rows[$i]->text . '" alt="' . $rows[$i]->text . '" />
									</a>';
                }
            }
        }

        if ($optionImage != '') {
            $optionHtml .= '<span style="display:none;" class="option-image">' . $optionImage . '</span>';
        }

        if (EshopHelper::isCartMode($product) || EshopHelper::isQuoteMode($product)) {
            $updatePrice = '';
            if (EshopHelper::getConfigValue('dynamic_price') && EshopHelper::showPrice() && !$product->product_call_for_price)
                switch ($optionType) {
                    case 'Text':
                    case 'Textarea':
                        $updatePrice = ' onkeyup="updatePrice();"';
                        break;
                    default:
                        $updatePrice = ' onchange="updatePrice();"';
                        break;
                }

            switch ($optionType) {
                case 'Select':
                    $options[] = JHtml::_('select.option', '', JText::_('ESHOP_PLEASE_SELECT'), 'value', 'text');
                    $optionHtml .= JHtml::_('select.genericlist', array_merge($options, $rows), 'options[' . $productOptionId . ']',
                        array(
                            'option.text.toHtml' => false,
                            'option.value' => 'value',
                            'option.text' => 'text',
                            'list.attr' => ' class="inputbox" onchange="updatePrice();" '));
                    break;
                case 'Checkbox':
                    for ($i = 0; $n = count($rows), $i < $n; $i++) {
                        $optionHtml .= '<label class="checkbox">';
                        $optionHtml .= '<input type="checkbox" name="options[' . $productOptionId . '][]" value="' . $rows[$i]->id . '"' . $updatePrice . '> ' . $rows[$i]->text;
                        $optionHtml .= '</label>';
                    }
                    break;
                case 'Radio':
                    for ($i = 0; $n = count($rows), $i < $n; $i++) {
                        $optionHtml .= '<label class="radio">';
                        $optionHtml .= '<input type="radio" name="options[' . $productOptionId . ']" value="' . $rows[$i]->id . '"' . $updatePrice . '> ' . $rows[$i]->text;
                        $optionHtml .= '</label>';
                    }
                    break;
                case 'Text':
                    $optionHtml .= '<input type="text" name="options[' . $productOptionId . ']" value=""' . $updatePrice . ' />' . $rows[0]->text;
                    break;
                case 'Textarea':
                    $optionHtml .= '<textarea name="options[' . $productOptionId . ']" cols="40" rows="5"' . $updatePrice . ' ></textarea>' . $rows[0]->text;
                    break;
                case 'File':
                    $optionHtml .= '<input type="button" value="' . JText::_('ESHOP_UPLOAD_FILE') . '" id="button-option-' . $productOptionId . '" class="' . $bootstrapHelper->getClassMapping('btn') . ' btn-primary">';
                    $optionHtml .= '<input type="hidden" name="options[' . $productOptionId . ']" value="" />';
                    break;
                case 'Date':
                    $optionHtml .= JHtml::_('calendar', '', 'options[' . $productOptionId . ']', 'options[' . $productOptionId . ']', '%Y-%m-%d');
                    break;
                case 'Datetime':
                    $optionHtml .= JHtml::_('calendar', '', 'options[' . $productOptionId . ']', 'options[' . $productOptionId . ']', '%Y-%m-%d 00:00:00');
                    break;
                default:
                    break;
            }
        } else {
            for ($i = 0; $n = count($rows), $i < $n; $i++) {
                echo $rows[$i]->text . '<br />';
            }
        }

        return $optionHtml;
    }
}
