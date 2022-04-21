<?php


class BizHelper
{
    static $weightList = null;

    public static function getWeightList()
    {
        if (static::$weightList == null) {
            $db = JFactory::getDbo();
            $sql = 'SELECT weight_id, weight_name FROM #__eshop_weightdetails WHERE `language` = \'vi-VN\'';
            $result = $db->setQuery($sql)->loadAssocList();
            foreach ($result as $item) {
                $key = trim(strtolower($item['weight_name']));
                static::$weightList[$key] = $item['weight_id'];
            }
        }
        return static::$weightList;
    }

    public static function makeRowObject($pattern, $row, $lanuage)
    {
        $obj = new stdClass();
        foreach ($row as $key => $val) {
            if (isset($pattern[$key]) && !empty($pattern[$key])) {
                $obj->{$pattern[$key]} = trim($val);
            }
        }
        if ($obj) {
            $obj->language = $lanuage;
        }

        return $obj;
    }

    public static function import($filename)
    {
        $language = 'vi-VN';
        $path = JPATH_ADMINISTRATOR . '/components/com_eshop/libraries/Classes/PHPExcel/IOFactory.php';
        include($path);
        try {
            $objPHPExcel = PHPExcel_IOFactory::load($filename);
            $allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
            $numRow = count($allDataInSheet);  // Here get total count of row in that Excel sheet

            $rownumber = 1;
            $row = $objPHPExcel->getActiveSheet()->getRowIterator($rownumber)->current();
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);


            $rowPattern = $allDataInSheet[1];
            for ($i = 2; $i <= $numRow; $i++) {
                $obj = static::makeRowObject($rowPattern, $allDataInSheet[$i], $language);
                if (empty($obj->product_sku)) {
                    continue;
                }
                static::importProducts($obj, $language);
            }
        } catch (Exception $e) {
            print_r($e);
            die('Error loading file ' . $filename);
        }
    }

    public static function importAttribute($row, $language)
    {
        $db = JFactory::getDbo();
        if (isset($row->product_id)) {
            $query = $db->getQuery(true);
            $conditions = array('product_id =' . (int)$row->product_id);
            $query->delete($db->quoteName('#__eshop_productattributedetails'));
            $query->where($conditions);
            $db->setQuery($query);
            $db->execute();

            $query = $db->getQuery(true);
            $conditions = array('product_id =' . (int)$row->product_id);
            $query->delete($db->quoteName('#__eshop_productattributes'));
            $query->where($conditions);
            $db->setQuery($query);
            $db->execute();
        }
        $groupList = explode(';', $row->attributegroup_name);
        $attributeList = explode(';', $row->attribute_name);
        $attributeValueList = explode(';', $row->attribute_value);
        $attributeGroupPublishList = explode(';', $row->attributegroup_published);
        $attributePublishList = explode(';', $row->attribute_published);
        $attributeValuePublishList = explode(';', $row->attribute_value_published);

        foreach ($attributeList as $key => $attr) {
            if (!empty($groupList[$key]) && !empty($attributeList[$key])) {
                $groupStatus = isset($attributeGroupPublishList[$key]) ? $attributeGroupPublishList[$key] : 1;
                $attribute = array();

                $attribute['attributegroup_id'] = static::upsertGroupAttribute($groupList[$key], $groupStatus, $language);
                $attribute['published'] = isset($attributePublishList[$key]) ? $attributePublishList[$key] : 1;
                $attribute['attribute_name'] = $attributeList[$key];
                $attribute['language'] = $language;
                $attrId = static::upsertAttribute($attribute);

                if (isset($row->product_id)) {
                    $pAttributeObj = new stdClass();
                    $pAttributeObj->product_id = $row->product_id;
                    $pAttributeObj->attribute_id = $attrId;
                    $pAttributeObj->published = isset($attributeValuePublishList[$key]) ? $attributeValuePublishList[$key] : 1;
                    $db->insertObject('#__eshop_productattributes', $pAttributeObj, 'id');

                    $pid = $pAttributeObj->id;
                    $pattDetailObj = new stdClass();
                    $pattDetailObj->productattribute_id = $pid;
                    $pattDetailObj->product_id = $row->product_id;
                    $pattDetailObj->value = isset($attributeValueList[$key]) ? $attributeValueList[$key] : null;
                    $pattDetailObj->language = $language;
                    $db->insertObject('#__eshop_productattributedetails', $pattDetailObj, 'id');
                }
            }

        }
    }

    public static function importCategory($row, $language)
    {
        $catIds = array();
        $categoryList = explode(';', $row->category_name);
        $categoryImageList = explode(';', @$row->category_image);
        $categoryCustomerGroup = explode(';', @$row->category_customergroups);
        $categoryProductPerPage = explode(';', @$row->products_per_page);
        $categoryProductPerRow = explode(';', @$row->products_per_row);

        $customerGroup = array();
        foreach ($categoryCustomerGroup as $customerGroup) {
            if (!empty($customerGroup)) {
                $customerGroup[] = static::upsertCustomerGroup($customerGroup, $row->category_published, $language);
            }
        }
        foreach ($categoryList as $key => $cat) {
            $category = array();
            $category['category_parent_id'] = 0;
            $catNameList = explode('/', $cat);
            $catImageList = explode('/', $categoryImageList[$key]);
            $pageItemList = explode('/', $categoryProductPerPage[$key]);
            $rowItemList = explode('/', $categoryProductPerRow[$key]);
            if ($customerGroup) {
                $category['category_customergroups'] = implode(',', $customerGroup);
            }

            foreach ($catNameList as $k => $value) {
                $category['category_name'] = $value;
                $category['category_image'] = !empty($catImageList[$k]) ? $catImageList[$k] : null;
                $category['products_per_page'] = !empty($pageItemList[$k]) ? $pageItemList[$k] : null;
                $category['products_per_row'] = !empty($rowItemList[$k]) ? $rowItemList[$k] : null;
                $category['category_layout'] = 'default';
                $category['published'] = @$row->category_published;
                $category['category_alias'] = @$row->category_alias;
                $category['hits'] = @$row->category_hits;
                $category['level'] = $k + 1;
                $category['meta_key'] = @$row->category_meta_key;
                $category['meta_desc'] = @$row->category_meta_desc;
                $category['category_desc'] = @$row->category_desc;
                $category['category_page_title'] = @$row->category_page_title;
                $category['category_page_heading'] = @$row->category_page_heading;
                $category['language'] = $language;
                $id = static::upsertCategory($category);
                $category['category_parent_id'] = $id;
                $catIds[] = $id;
            }
        }
        return $catIds;
    }


    public static function importManufacturer($row, $language)
    {
        $manuCustomerGroup = explode(';', $row->manufacturer_customergroups);
        $customerGroup = array();
        foreach ($manuCustomerGroup as $group) {
            if (!empty($group)) {
                $customerGroup[] = static::upsertCustomerGroup($group, $row->manufacturer_published, $language);
            }
        }
        $user = JFactory::getUser();
        $db = JFactory::getDbo();
        $query = $db->getQuery(true)
            ->select('manufacturer_id')
            ->from('#__eshop_manufacturerdetails')
            ->where('manufacturer_name = ' . $db->quote($row->manufacturer_name))
            ->where($db->quoteName('language') . '=' . $db->quote($language));
        $id = $db->setQuery($query)->loadResult();
        if (!$id) {
            $manuObj = new stdClass();
            $manuObj->manufacturer_email = $row->manufacturer_email;
            $manuObj->manufacturer_url = $row->manufacturer_url;
            $manuObj->manufacturer_image = $row->manufacturer_image;
            $manuObj->manufacturer_customergroups = implode(',', $customerGroup);
            $manuObj->published = $row->manufacturer_published;
            $manuObj->hits = $row->manufacturer_hits;
            $manuObj->created_by = $user->id;
            $db->insertObject('#__eshop_manufacturers', $manuObj, 'id');

            $id = $manuObj->id;
            $manuDetailObj = new stdClass();
            $manuDetailObj->manufacturer_id = $id;
            $manuDetailObj->manufacturer_name = $row->manufacturer_name;
            $manuDetailObj->manufacturer_alias = !empty($row->manufacturer_alias) ? static::transliterate($row->manufacturer_alias) : static::transliterate($row->manufacturer_name);
            $manuDetailObj->manufacturer_desc = $row->manufacturer_desc;
            $manuDetailObj->manufacturer_page_title = $row->manufacturer_page_title;
            $manuDetailObj->manufacturer_page_heading = $row->manufacturer_page_heading;
            $manuDetailObj->language = $language;
            $db->insertObject('#__eshop_manufacturerdetails', $manuDetailObj, 'id');
        }
        return $id;
    }


    public static function importOption($row, $language)
    {
        $user = JFactory::getUser();
        $db = JFactory::getDbo();
        if (isset($row->product_id)) {
            $query = $db->getQuery(true);
            $conditions = array('product_id =' . (int)$row->product_id);
            $query->delete($db->quoteName('#__eshop_productoptions'));
            $query->where($conditions);
            $db->setQuery($query);
            $db->execute();

            $query = $db->getQuery(true);
            $conditions = array('product_id =' . (int)$row->product_id);
            $query->delete($db->quoteName('#__eshop_productoptionvalues'));
            $query->where($conditions);
            $db->setQuery($query);
            $db->execute();
        }


        $optionTypeList = explode(';', $row->option_type);
        $optionNameList = explode(';', $row->option_name);
        $optionImageList = explode(';', $row->option_image);
        $optionDescList = explode(';', $row->option_desc);
        $optionValueList = explode(';', $row->option_value);
        $optionSkuList = explode(';', $row->option_sku);
        $optionQtyList = explode(';', $row->option_quantity);
        $optionPriceList = explode(';', $row->option_price);
        $optionPriceSignList = explode(';', $row->option_price_sign);
        $optionPriceTypeList = explode(';', $row->price_type);
        $optionValuePublishList = explode(';', $row->option_value_published);
        $optionWeightList = explode(';', $row->option_weight);
        $optionWeightSignList = explode(';', $row->option_weight_sign);
        foreach ($optionTypeList as $key => $value) {
            $query = $db->getQuery(true)
                ->select('a.option_id')
                ->from($db->quoteName('#__eshop_optiondetails', 'a'))
                ->join('LEFT', $db->quoteName('#__eshop_options', 'o') . ' ON a.option_id = o.id')
                ->where('a.option_name = ' . $db->quote($optionNameList[$key]))
                ->where('o.option_type = ' . $db->quote($value))
                ->where($db->quoteName('language') . '=' . $db->quote($language));
            $id = $db->setQuery($query)->loadResult();
            if (!$id) {
                $optionObj = new stdClass();
                $optionObj->option_type = $value;
                $optionObj->option_image = isset($optionImageList[$key]) ? $optionImageList[$key] : null;
                $optionObj->published = isset($row->option_published) ? $row->option_published : 1;
                $optionObj->created_by = $user->id;
                $db->insertObject('#__eshop_options', $optionObj, 'id');
                $id = $optionObj->id;

                $optionDetailObj = new stdClass();
                $optionDetailObj->option_id = $id;
                $optionDetailObj->option_name = $optionNameList[$key];
                $optionDetailObj->option_desc = isset($optionDescList[$key]) ? $optionDescList[$key] : null;
                $optionDetailObj->language = $language;
                $db->insertObject('#__eshop_optiondetails', $optionDetailObj, 'id');
            }

            $query = $db->getQuery(true)
                ->select('optionvalue_id')
                ->from('#__eshop_optionvaluedetails')
                ->where('`value` = ' . $db->quote($optionValueList[$key]))
                ->where($db->quoteName('language') . '=' . $db->quote($language));
            $optionValId = $db->setQuery($query)->loadResult();
            if (!$optionValId) {
                $valueObj = new stdClass();
                $valueObj->option_id = $id;
                $valueObj->published = isset($optionValuePublishList[$key]) ? $optionValuePublishList[$key] : 1;
                $db->insertObject('#__eshop_optionvalues', $valueObj, 'id');
                $optionValId = $valueObj->id;


                $valueDetailObj = new stdClass();
                $valueDetailObj->optionvalue_id = $optionValId;
                $valueDetailObj->option_id = $id;
                $valueDetailObj->value = $optionValueList[$key];
                $valueDetailObj->language = $language;
                $db->insertObject('#__eshop_optionvaluedetails', $valueDetailObj, 'id');

            }
            if (isset($row->product_id)) {
                $query = $db->getQuery(true)
                    ->select('id')
                    ->from('#__eshop_productoptions')
                    ->where('`product_id` = ' . (int)$row->product_id)
                    ->where('`option_id` = ' . (int)$id);
                $productOptionId = $db->setQuery($query)->loadResult();

                $productOptionObj = new stdClass();
                $productOptionObj->product_id = $row->product_id;
                $productOptionObj->option_id = $id;
                $productOptionObj->required = isset($row->option_required) ? $row->option_required : 1;
                if (!$productOptionId) {
                    $db->insertObject('#__eshop_productoptions', $productOptionObj, 'id');
                    $productOptionId = $productOptionObj->id;
                } else {
                    $productOptionObj->id = $productOptionId;
                    $db->updateObject('#__eshop_productoptions', $productOptionObj, 'id');
                }

                $query = $db->getQuery(true)
                    ->select('id')
                    ->from('#__eshop_productoptions')
                    ->where('`product_option_id` = ' . (int)$productOptionId)
                    ->where($db->quoteName('language') . '=' . $db->quote($language));
                $productOptionDetailId = $db->setQuery($query)->loadResult();


                $productOptionDetailObj = new stdClass();
                $productOptionDetailObj->product_option_id = $productOptionId;
                $productOptionDetailObj->product_id = $row->product_id;
                $productOptionDetailObj->option_id = $id;
                $productOptionDetailObj->option_value_id = $optionValId;
                $productOptionDetailObj->sku = isset($optionSkuList[$key]) ? $optionSkuList[$key] : null;
                $productOptionDetailObj->quantity = isset($optionQtyList[$key]) ? $optionQtyList[$key] : null;
                $productOptionDetailObj->price = isset($optionPriceList[$key]) ? $optionPriceList[$key] : null;
                $productOptionDetailObj->price_sign = isset($optionPriceSignList[$key]) ? $optionPriceSignList[$key] : null;
                $productOptionDetailObj->price_type = isset($optionPriceTypeList[$key]) ? $optionPriceTypeList[$key] : null;
                $productOptionDetailObj->weight = isset($optionWeightList[$key]) ? $optionWeightList[$key] : null;
                $productOptionDetailObj->weight_sign = isset($optionWeightSignList[$key]) ? $optionWeightSignList[$key] : null;

                if (!$productOptionDetailId) {
                    $db->insertObject('#__eshop_productoptionvalues', $productOptionDetailObj, 'id');
                } else {
                    $productOptionDetailObj->id = $productOptionDetailId;
                    $db->updateObject('#__eshop_productoptionvalues', $productOptionDetailObj, 'id');
                }
            }


        }
    }


    public static function importProducts($row, $language)
    {
        $user = JFactory::getUser();
        $db = JFactory::getDbo();
        $query = $db->getQuery(true)
            ->select('id')
            ->from('#__eshop_products')
            ->where('product_sku = ' . $db->quote($row->product_sku));
        $id = $db->setQuery($query)->loadResult();

        $productObj = new stdClass();
        $productObj->product_sku = $row->product_sku;
        $productObj->product_weight = isset($row->product_weight) ? $row->product_weight : null;
        $productObj->product_weight_id = isset($row->product_weight_id) ? $row->product_weight_id : null;
        $productObj->product_length = isset($row->product_length) ? $row->product_length : null;
        $productObj->product_width = isset($row->product_width) ? $row->product_width : null;
        $productObj->product_height = isset($row->product_height) ? $row->product_height : null;
        $productObj->product_length_id = isset($row->product_length_id) ? $row->product_length_id : null;
        $productObj->product_cost = isset($row->product_cost) ? $row->product_cost : null;
        $productObj->product_price = isset($row->product_price) ? $row->product_price : null;
        $productObj->product_call_for_price = isset($row->product_call_for_price) ? $row->product_call_for_price : null;
        $productObj->product_taxclass_id = isset($row->product_taxclass_id) ? $row->product_taxclass_id : null;
        $productObj->product_quantity = isset($row->product_quantity) ? $row->product_quantity : null;
        $productObj->product_threshold = isset($row->product_threshold) ? $row->product_threshold : null;
        $productObj->product_threshold_notify = isset($row->product_threshold_notify) ? $row->product_threshold_notify : null;
        $productObj->product_stock_checkout = isset($row->product_stock_checkout) ? $row->product_stock_checkout : null;
        $productObj->product_minimum_quantity = isset($row->product_minimum_quantity) ? $row->product_minimum_quantity : null;
        $productObj->product_maximum_quantity = isset($row->product_maximum_quantity) ? $row->product_maximum_quantity : null;
        $productObj->product_shipping = isset($row->product_shipping) ? $row->product_shipping : 1;
        $productObj->product_shipping_cost = isset($row->product_shipping_cost) ? $row->product_shipping_cost : null;
        $productObj->product_shipping_cost_geozones = isset($row->product_shipping_cost_geozones) ? $row->product_shipping_cost_geozones : null;
        $productObj->product_image = isset($row->product_image) ? $row->product_image : null;
        $productObj->product_available_date = isset($row->product_available_date) ? $row->product_available_date : null;
        $productObj->product_featured = isset($row->product_featured) ? $row->product_featured : null;
        $productObj->product_stock_status_id = isset($row->product_stock_status_id) ? $row->product_stock_status_id : null;
        $productObj->product_cart_mode = isset($row->product_cart_mode) ? $row->product_cart_mode : null;
        $productObj->product_quote_mode = isset($row->product_quote_mode) ? $row->product_quote_mode : null;
        $productObj->published = isset($row->product_published) ? $row->product_published : 1;
        $productObj->product_available_date = "0000-00-00 00:00:00";


        if (isset($row->manufacturer_name)) {
            $manuId = static::importManufacturer($row, $language);
            $productObj->manufacturer_id = $manuId;
        }

        if (!$productObj->product_image && !empty($row->product_name)) {

            $productObj->product_image = static::generateImageName($row->product_name) . '.jpg';
        }

        if (isset($row->product_weight_name)) {
            $weightList = static::getWeightList();
            $weightKey = trim(strtolower($row->product_weight_name));
            if (isset($weightList[$weightKey])) {
                $productObj->product_weight_id = $weightList[$weightKey];
            } else {
                // Insert new weight name
                $productObj->product_weight_id = static::insertWeight($row->product_weight_name, $language);
            }
        }

        if (!$id) {
            $productObj->created_by = $user->id;
            $db->insertObject('#__eshop_products', $productObj, 'id');
            $id = $productObj->id;
        } else {
            $productObj->id = $id;
            unset($productObj->product_image);
            $productObj->modified_by = $user->id;
            $db->updateObject('#__eshop_products', $productObj, 'id');
        }

        $query = $db->getQuery(true)
            ->select('id')
            ->from('#__eshop_productdetails')
            ->where('product_id = ' . $db->quote($id));
        $detailId = $db->setQuery($query)->loadResult();
        if(isset($row->product_name)){
            $productDetailObj = new stdClass();
            $productDetailObj->product_id = $id;
            $productDetailObj->product_name = isset($row->product_name) ? $row->product_name : null;
            $productDetailObj->product_alias = !empty($row->product_alias) ? static::transliterate($row->product_alias) : static::transliterate($row->product_name);
            $productDetailObj->product_desc = isset($row->product_desc) ? $row->product_desc : null;
            $productDetailObj->product_short_desc = isset($row->product_short_desc) ? $row->product_short_desc : null;
            $productDetailObj->product_page_title = isset($row->product_page_title) ? $row->product_page_title : null;
            $productDetailObj->product_page_heading = isset($row->product_page_heading) ? $row->product_page_heading : null;
            $productDetailObj->product_alt_image = isset($row->product_alt_image) ? $row->product_alt_image : null;
            $productDetailObj->tab1_title = isset($row->tab1_title) ? $row->tab1_title : null;
            $productDetailObj->tab1_content = isset($row->tab1_content) ? $row->tab1_content : null;
            $productDetailObj->tab2_title = isset($row->tab2_title) ? $row->tab2_title : null;
            $productDetailObj->tab2_content = isset($row->tab2_content) ? $row->tab2_content : null;
            $productDetailObj->tab3_title = isset($row->tab3_title) ? $row->tab3_title : null;
            $productDetailObj->tab3_content = isset($row->tab3_content) ? $row->tab3_content : null;
            $productDetailObj->tab4_title = isset($row->tab4_title) ? $row->tab4_title : null;
            $productDetailObj->tab4_content = isset($row->tab4_content) ? $row->tab4_content : null;
            $productDetailObj->tab5_title = isset($row->tab5_title) ? $row->tab5_title : null;
            $productDetailObj->tab5_content = isset($row->tab5_content) ? $row->tab5_content : null;
            $productDetailObj->meta_key = isset($row->product_meta_key) ? $row->product_meta_key : null;
            $productDetailObj->meta_desc = isset($row->product_meta_desc) ? $row->product_meta_desc : null;
            $productDetailObj->language = $language;
            $productDetailObj->product_short_desc = nl2br($productDetailObj->product_short_desc);

            if(!$productDetailObj->product_desc){
                $productDetailObj->product_desc = $productDetailObj->product_short_desc;
            }

            if (!$detailId) {
                $db->insertObject('#__eshop_productdetails', $productDetailObj, 'id');
                $detailId = $productDetailObj->id;
            } else {
                $productDetailObj->id = $detailId;
                $db->updateObject('#__eshop_productdetails', $productDetailObj, 'id');
            }
        }


        if (isset($row->category_name)) {
            $catIds = static::importCategory($row, $language);
            $query = $db->getQuery(true);
            $conditions = array('product_id =' . (int)$id);
            $query->delete($db->quoteName('#__eshop_productcategories'));
            $query->where($conditions);
            $db->setQuery($query);
            $db->execute();

            $columns = array('product_id', 'category_id', 'main_category');
            $values = array();
            foreach ($catIds as $key => $cat_id) {
                $mainCat = $key == 0 ? 1 : 0;
                $array = array(
                    $db->quote($id),
                    $db->quote($cat_id),
                    $mainCat,
                );
                $values[] = implode(',', $array);
            }
            $query->insert($db->quoteName('#__eshop_productcategories'));
            $query->columns($db->quoteName($columns));
            $query->values($values);
            $db->setQuery($query)->execute();
        }
        $row->product_id = $id;
        if (!empty($row->attributegroup_name) && !empty($row->attribute_name) && !empty($row->attribute_value)) {
            static::importAttribute($row, $language);
        }

        if (!empty($row->option_type) && !empty($row->option_name) && !empty($row->option_value)) {
            static::importOption($row, $language);
        }
    }


    public static function upsertCategory($category)
    {
        $user = JFactory::getUser();
        $db = JFactory::getDbo();
        $query = $db->getQuery(true)
            ->select('category_id')
            ->from('#__eshop_categorydetails')
            ->where('category_name = ' . $db->quote($category['category_name']))
            ->where($db->quoteName('language') . '=' . $db->quote($category['language']));
        $id = $db->setQuery($query)->loadResult();
        if (!$id) {
            $categoryObj = new stdClass();
            $categoryObj->category_parent_id = $category['category_parent_id'];
            $categoryObj->category_image = $category['category_image'];
            $categoryObj->category_layout = $category['category_layout'];
            $categoryObj->category_parent_id = $category['category_parent_id'];
            $categoryObj->category_customergroups = $category['category_customergroups'];
            $categoryObj->products_per_page = $category['products_per_page'];
            $categoryObj->products_per_row = $category['products_per_row'];
            $categoryObj->published = $category['published'];
            $categoryObj->created_by = $user->id;
            $categoryObj->level = $category['level'];
            $db->insertObject('#__eshop_categories', $categoryObj, 'id');
            $id = $categoryObj->id;

            $categoryDetailObj = new stdClass();
            $categoryDetailObj->category_id = $id;
            $categoryDetailObj->category_name = $category['category_name'];
            $categoryDetailObj->category_alias = !empty($category['category_alias']) ? static::transliterate($category['category_alias']) : static::transliterate($category['category_name']);
            $categoryDetailObj->category_desc = $category['category_desc'];
            $categoryDetailObj->category_page_title = $category['category_page_title'];
            $categoryDetailObj->category_page_heading = $category['category_page_heading'];
            $categoryDetailObj->meta_key = $category['meta_key'];
            $categoryDetailObj->meta_desc = $category['meta_desc'];
            $categoryDetailObj->language = $category['language'];
            $db->insertObject('#__eshop_categorydetails', $categoryDetailObj, 'id');
        } else {
            // Update data here
        }
        return $id;
    }

    public static function transliterate($string)
    {
        $str = JString::strtolower($string);

        $glyph_array = array(
            'a' => 'á,à,ả,ã,ạ,À,Á,Ả,Ã,Ạ,â,ầ,ấ,ẩ,ẫ,ậ,Â,Ầ,Ấ,Ẩ,Ẫ,Ậ,ă,ằ,ắ,ẳ,ẳ,ặ',
            'd' => 'Ð,đ',
            'e' => 'è,é,ẻ,ẽ,ẹ,È,É,Ẻ,Ẽ,Ẹ,ê,ề,ế,ể,ễ,ệ,Ê,Ề,Ế,Ể,Ễ,Ệ',
            'i' => 'í,ì,ỉ,ĩ,ị,Ì,Í,Ỉ,Ĩ,Ị',
            'o' => 'ò,ó,ỏ,õ,ọ,ơ,ờ,ớ,ở,ỡ,ợ,Ò,Ó,Ỏ,Õ,Ọ,Ơ,Ờ,Ớ,Ở,Ỡ,Ợ,ô,ồ,ố,ổ,ỗ,ộ,Ồ,Ố,Ổ,Ỗ,Ổ,Ộ',
            'u' => 'ù,ú,ủ,ũ,ụ,Ù,Ú,Ủ,Ũ,ư,ừ,ứ,ử,ữ,ự,Ư,Ừ,Ứ,Ử,Ữ,Ự',
            'y' => 'ý,ỷ,ỳ,ỹ,ỵ,Ỹ,Ỷ,Ỵ,Ý,Ỳ'
        );

        foreach ($glyph_array as $letter => $glyphs) {
            $glyphs = explode(',', $glyphs);
            $str = str_replace($glyphs, $letter, $str);
        }


        return $str;
    }

    public static function generateImageName($str)
    {
        $glyph_array = array(
            'a' => 'á,à,ả,ã,ạ,â,ầ,ấ,ẩ,ẫ,ậ,ă,ằ,ắ,ẳ,ẳ,ặ',
            'A' => 'À,Á,Ả,Ã,Ạ,Â,Ầ,Ấ,Ẩ,Ẫ,Ậ',
            'd' => 'đ',
            'D' => 'Ð,Đ',
            'e' => 'è,é,ẻ,ẽ,ẹ,ê,ề,ế,ể,ễ,ệ',
            'E' => 'È,É,Ẻ,Ẽ,Ẹ,Ê,Ề,Ế,Ể,Ễ,Ệ',
            'i' => 'í,ì,ỉ,ĩ,ị',
            'I' => 'Ì,Í,Ỉ,Ĩ,Ị',
            'o' => 'ò,ó,ỏ,õ,ọ,ơ,ờ,ớ,ở,ỡ,ợ,ô,ồ,ố,ổ,ỗ,ộ,ẵ',
            'O' => 'Ò,Ó,Ỏ,Õ,Ọ,Ơ,Ờ,Ớ,Ở,Ỡ,Ợ,Ồ,Ố,Ổ,Ỗ,Ổ,Ộ,Ô',
            'u' => 'ù,ú,ủ,ũ,ụ,ư,ừ,ứ,ử,ữ,ừ,ự',
            'U' => 'Ù,Ú,Ủ,Ũ,Ư,Ừ,Ứ,Ử,Ữ,Ự',
            'y' => 'ý,ỷ,ỳ,ỹ,ỵ',
            'Y' => 'Ỹ,Ỷ,Ỵ,Ý,Ỳ'
        );

        foreach ($glyph_array as $letter => $glyphs) {
            $glyphs = explode(',', $glyphs);
            $str = str_replace($glyphs, $letter, $str);
        }
        $search = array(
            ' ',
            '/',
            'Đ',
            ',',
        );
        $replace = array(
            '-',
            '-',
            'D',
            '-',
        );
        $str = str_replace($search, $replace, $str);
        $str = str_replace(array('!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '.', '+'), '-', $str);
        return $str;
    }

    public static function upsertCustomerGroup($groupName, $status, $language)
    {
        $user = JFactory::getUser();
        $db = JFactory::getDbo();
        $query = $db->getQuery(true)
            ->select('customergroup_id')
            ->from('#__eshop_customergroupdetails')
            ->where('customergroup_name = ' . $db->quote($groupName))
            ->where($db->quoteName('language') . '=' . $db->quote($language));
        $id = $db->setQuery($query)->loadResult();
        if (!$id) {
            $groupObj = new stdClass();
            $groupObj->id = null;
            $groupObj->published = $status;
            $db->insertObject('#__eshop_customergroups', $groupObj, 'id');
            $id = $groupObj->id;
            $groupDetailObj = new stdClass();
            $groupDetailObj->customergroup_id = $id;
            $groupDetailObj->customergroup_name = $groupName;
            $groupDetailObj->language = $language;
            $db->insertObject('#__eshop_customergroupdetails', $groupDetailObj, 'id');
        } else {
            $groupObj = new stdClass();
            $groupObj->id = $id;
            $groupObj->published = $status;
            $db->updateObject('#__eshop_customergroups', $groupObj, 'id');
        }
        return $id;

    }

    public static function upsertAttribute($attribute)
    {
        $user = JFactory::getUser();
        $db = JFactory::getDbo();
        $query = $db->getQuery(true)
            ->select('attribute_id')
            ->from('#__eshop_attributedetails')
            ->where('attribute_name = ' . $db->quote($attribute['attribute_name']))
            ->where($db->quoteName('language') . '=' . $db->quote($attribute['language']));
        $id = $db->setQuery($query)->loadResult();
        if (!$id) {
            $attributeObj = new stdClass();
            $attributeObj->attributegroup_id = $attribute['attributegroup_id'];
            $attributeObj->published = $attribute['published'];
            $attributeObj->created_by = $user->id;
            $db->insertObject('#__eshop_attributes', $attributeObj, 'id');


            $id = $attributeObj->id;
            $attributeDetailObj = new stdClass();
            $attributeDetailObj->attribute_id = $id;
            $attributeDetailObj->attribute_name = $attribute['attribute_name'];
            $attributeDetailObj->language = $attribute['language'];
            $db->insertObject('#__eshop_attributedetails', $attributeDetailObj, 'id');

        } else {
            $attributeObj = new stdClass();
            $attributeObj->id = $id;
            $attributeObj->published = $attribute['published'];
            $attributeObj->modified_by = $user->id;
            $db->updateObject('#__eshop_attributes', $attributeObj, 'id');
        }
        return $id;
    }

    /**
     * @param $groupName
     * @param $status
     * @param $language
     */
    public static function upsertGroupAttribute($groupName, $status, $language)
    {
        $user = JFactory::getUser();
        $db = JFactory::getDbo();
        $query = $db->getQuery(true)
            ->select('attributegroup_id')
            ->from('#__eshop_attributegroupdetails')
            ->where('attributegroup_name = ' . $db->quote($groupName))
            ->where($db->quoteName('language') . '=' . $db->quote($language));
        $id = $db->setQuery($query)->loadResult();
        if (!$id) {
            $groupObj = new stdClass();
            $groupObj->id = null;
            $groupObj->published = $status;
            $groupObj->created_by = $user->id;
            $db->insertObject('#__eshop_attributegroups', $groupObj, 'id');

            $id = $groupObj->id;
            $detailObj = new stdClass();
            $detailObj->attributegroup_id = $id;
            $detailObj->attributegroup_name = $groupName;
            $detailObj->language = $language;
            $db->insertObject('#__eshop_attributegroupdetails', $detailObj, 'id');
        } else {
            $groupObj = new stdClass();
            $groupObj->id = $id;
            $groupObj->published = $status;
            $db->updateObject('#__eshop_attributegroups', $groupObj, 'id');
        }
        return $id;
    }

    public static function insertWeight($weightName, $language)
    {
        $user = JFactory::getUser();
        $db = JFactory::getDbo();
        $weightObj = new stdClass();
        $weightObj->id = null;
        $weightObj->exchanged_value = 1;
        $weightObj->published = 1;
        $weightObj->created_by = $user->id;
        $db->insertObject('#__eshop_weights', $weightObj, 'id');

        $id = $weightObj->id;
        $detailObj = new stdClass();
        $detailObj->weight_id = $id;
        $detailObj->weight_name = trim($weightName);
        $detailObj->weight_unit = trim($weightName);
        $detailObj->language = $language;
        $db->insertObject('#__eshop_weightdetails', $detailObj, 'id');
        return $id;
    }


    public static function getNextStatusList($id){
        $db = JFactory::getDbo();
        $sql = 'SELECT next_status FROM #__eshop_status_workflow WHERE order_status = '.(int)$id;
        return $db->setQuery($sql)->loadColumn();
    }


}
