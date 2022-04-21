<?php

use Joomla\Utilities\ArrayHelper;

/**
 * @version        3.1.0
 * @package        Joomla
 * @subpackage    EShop
 * @author    Giang Dinh Truong
 * @copyright    Copyright (C) 2012 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die();

/**
 * HTML View class for EShop component
 *
 * @static
 * @package        Joomla
 * @subpackage    EShop
 * @since 1.5
 */
class EShopViewProducts extends EShopViewList
{
    public function display($tpl = null, $is_product_list = false)
    {
        $this->currency = new EshopCurrency();
        if($_GET['template'] == 'updateprice'){
          $tpl = 'updateprice';
        }

        parent::display($tpl, true);
        if (version_compare(JVERSION, '3.0', 'ge'))
            $this->addToolbar();
    }

    public function setExtraValue()
    {
        $ids = array_map(function ($item) {
            return $item->id;
        }, $this->items);
        if ($ids) {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select('*')
                ->from('#__eshop_home_products')
                ->where('product_id IN ( ' . implode(',', $ids) . ')');
            $db->setQuery($query);
            $rows = $db->loadAssocList();
            if($rows){
                foreach ($this->items as &$item){
                    foreach ($rows as $extra){
                        if($extra['product_id'] == $item->id){
                            $name = 'group_'.$extra['group_id'];
                            $item->$name = 1;
                        }
                    }
                }
            }

        }
    }

    /**
     * Build all the lists items used in the form and store it into the array
     * @param  $lists
     * @return boolean
     */
    public function _buildListArray(&$lists, $state)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('a.id, b.category_name AS title, a.category_parent_id AS parent_id')
            ->from('#__eshop_categories AS a')
            ->innerJoin('#__eshop_categorydetails AS b ON (a.id = b.category_id)')
            ->where('b.language = "' . JComponentHelper::getParams('com_languages')->get('site', 'en-GB') . '"');
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        $children = array();
        if ($rows) {
            // first pass - collect children
            foreach ($rows as $v) {
                $pt = $v->parent_id;
                $list = @$children[$pt] ? $children[$pt] : array();
                array_push($list, $v);
                $children[$pt] = $list;
            }
        }
        $list = JHtml::_('menu.treerecurse', 0, '', array(), $children, 9999, 0, 0);
        $options = array();
        $options[] = JHtml::_('select.option', '0', JText::_('ESHOP_SELECT_A_CATEGORY'));
        foreach ($list as $listItem) {
            $options[] = JHtml::_('select.option', $listItem->id, $listItem->treename);
        }
        $lists['category_id'] = JHtml::_('select.genericlist', $options, 'category_id',
            array(
                'option.text.toHtml' => false,
                'option.text' => 'text',
                'option.value' => 'value',
                'list.attr' => ' class="inputbox" onchange="this.form.submit();"',
                'list.select' => $state->category_id));
        // Stock status list
        $options = array();
        $options[] = JHtml::_('select.option', '0', JText::_('ESHOP_SELECT_STOCK_STATUS'));
        $options[] = JHtml::_('select.option', '1', JText::_('ESHOP_IN_STOCK'));
        $options[] = JHtml::_('select.option', '2', JText::_('ESHOP_OUT_OF_STOCK'));
        $lists['stock_status'] = JHtml::_('select.genericlist', $options, 'stock_status',
            array(
                'option.text.toHtml' => false,
                'option.text' => 'text',
                'option.value' => 'value',
                'list.attr' => ' class="inputbox" onchange="this.form.submit();"',
                'list.select' => $state->stock_status));

        //Start prepare the data for batch process
        //Build manufacturer list
        $query->clear();
        $query->select('a.id AS value, b.manufacturer_name AS text')
            ->from('#__eshop_manufacturers AS a')
            ->innerJoin('#__eshop_manufacturerdetails AS b ON (a.id = b.manufacturer_id)')
            ->where('a.published = 1')
            ->where('b.language = "' . JComponentHelper::getParams('com_languages')->get('site', 'en-GB') . '"')
            ->order('a.ordering');
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        $options = array();
        $options[] = JHtml::_('select.option', 0, JText::_('ESHOP_BATCH_PRODUCTS_KEEP_MANUFACTURER'), 'value', 'text');
        if (count($rows)) {
            $options = array_merge($options, $rows);
        }
        $lists['manufacturer'] = JHtml::_('select.genericlist', $options, 'manufacturer_id',
            array(
                'option.text.toHtml' => false,
                'option.value' => 'value',
                'option.text' => 'text',
                'list.attr' => ' class="inputbox chosen" ',
                'list.select' => ''));
        $options = array();
        $options[] = JHtml::_('select.option', '0', JText::_('ESHOP_SELECT_A_MANUFACTURER'));
        if (count($rows)) {
            $options = array_merge($options, $rows);
        }
        $lists['mf_id'] = JHtml::_('select.genericlist', $options, 'mf_id',
            array(
                'option.text.toHtml' => false,
                'option.value' => 'value',
                'option.text' => 'text',
                'list.attr' => ' class="inputbox" onchange="this.form.submit();" ',
                'list.select' => $state->mf_id));

        //Build categories list
        $query->clear();
        $query->select('a.id, b.category_name AS title, a.category_parent_id AS parent_id')
            ->from('#__eshop_categories AS a')
            ->innerJoin('#__eshop_categorydetails AS b ON (a.id = b.category_id)')
            ->where('a.published = 1')
            ->where('b.language = "' . JComponentHelper::getParams('com_languages')->get('site', 'en-GB') . '"');
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        $children = array();
        if ($rows) {
            foreach ($rows as $v) {
                $pt = $v->parent_id;
                $list = @$children[$pt] ? $children[$pt] : array();
                array_push($list, $v);
                $children[$pt] = $list;
            }
        }
        $list = JHtml::_('menu.treerecurse', 0, '', array(), $children, 9999, 0, 0);
        $options = array();
        $options[] = JHtml::_('select.option', 0, JText::_('ESHOP_BATCH_PRODUCTS_KEEP_MAIN_CATEGORY'));
        foreach ($list as $listItem) {
            $options[] = JHtml::_('select.option', $listItem->id, $listItem->treename);
        }
        $lists['main_category_id'] = JHtml::_('select.genericlist', $options, 'main_category_id', array(
            'option.text.toHtml' => false,
            'option.text' => 'text',
            'option.value' => 'value',
            'list.attr' => 'class="inputbox chosen"',
            'list.select' => ''));
        array_shift($options);
        $options_first = JHTML::_('select.option', 0, JText::_('ESHOP_BATCH_PRODUCTS_KEEP_ADDITIONAL_CATEGORY'));
        array_unshift($options, $options_first);
        $lists['additional_category_id'] = JHtml::_('select.genericlist', $options, 'additional_category_id[]', array(
            'option.text.toHtml' => false,
            'option.text' => 'text',
            'option.value' => 'value',
            'list.attr' => 'class="inputbox chosen" multiple="multiple"',
            'list.select' => 0));

        //Build customer groups list
        $query->clear();
        $query->select('a.id AS value, b.customergroup_name AS text')
            ->from('#__eshop_customergroups AS a')
            ->innerJoin('#__eshop_customergroupdetails AS b ON (a.id = b.customergroup_id)')
            ->where('a.published = 1')
            ->where('b.language = "' . JComponentHelper::getParams('com_languages')->get('site', 'en-GB') . '"')
            ->order('b.customergroup_name');
        $db->setQuery($query);
        $options = $db->loadObjectList();
        $options_first = JHTML::_('select.option', 0, JText::_('ESHOP_BATCH_PRODUCTS_KEEP_CUSTOMER_GROUPS'));
        array_unshift($options, $options_first);
        $lists['product_customergroups'] = JHtml::_('select.genericlist', $options, 'product_customergroups[]',
            array(
                'option.text.toHtml' => false,
                'option.text' => 'text',
                'option.value' => 'value',
                'list.attr' => ' class="inputbox chosen" multiple ',
                'list.select' => 0));
        //Build Lengths list
        $query->clear();
        $query->select('a.id AS value, b.length_name AS text')
            ->from('#__eshop_lengths AS a')
            ->innerJoin('#__eshop_lengthdetails AS b ON (a.id = b.length_id)')
            ->where('a.published = 1')
            ->where('b.language = "' . JComponentHelper::getParams('com_languages')->get('site', 'en-GB') . '"');
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        $options = array();
        $options[] = JHtml::_('select.option', 0, JText::_('ESHOP_BATCH_PRODUCTS_KEEP_LENGTH'), 'value', 'text');
        if (count($rows)) {
            $options = array_merge($options, $rows);
        }
        $lists['product_length_id'] = JHtml::_('select.genericlist', $options, 'product_length_id',
            array(
                'option.text.toHtml' => false,
                'option.value' => 'value',
                'option.text' => 'text',
                'list.attr' => ' class="inputbox chosen" ',
                'list.select' => ''));

        //Build Weights list
        $query->clear();
        $query->select('a.id AS value, b.weight_name AS text')
            ->from('#__eshop_weights AS a')
            ->innerJoin('#__eshop_weightdetails AS b ON (a.id = b.weight_id)')
            ->where('a.published = 1')
            ->where('b.language = "' . JComponentHelper::getParams('com_languages')->get('site', 'en-GB') . '"');
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        $options = array();
        $options[] = JHtml::_('select.option', 0, JText::_('ESHOP_BATCH_PRODUCTS_KEEP_WEIGHT'), 'value', 'text');
        if (count($rows)) {
            $options = array_merge($options, $rows);
        }
        $lists['product_weight_id'] = JHtml::_('select.genericlist', $options, 'product_weight_id',
            array(
                'option.text.toHtml' => false,
                'option.value' => 'value',
                'option.text' => 'text',
                'list.attr' => ' class="inputbox chosen" ',
                'list.select' => ''));

        //Build taxclasses list
        $query->clear();
        $query->select('id AS value, taxclass_name AS text')
            ->from('#__eshop_taxclasses')
            ->where('published = 1');
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        $options = array();
        $options[] = JHtml::_('select.option', 0, JText::_('ESHOP_BATCH_PRODUCTS_KEEP_TAXCLASS'), 'value', 'text');
        if (count($rows)) {
            $options = array_merge($options, $rows);
        }
        $lists['product_taxclass_id'] = JHtml::_('select.genericlist', $options, 'product_taxclass_id',
            array(
                'option.text.toHtml' => false,
                'option.value' => 'value',
                'option.text' => 'text',
                'list.attr' => ' class="inputbox chosen" ',
                'list.select' => ''));

        //Stock status list
        $query->clear();
        $query->select('a.id AS value, b.stockstatus_name AS text')
            ->from('#__eshop_stockstatuses AS a')
            ->innerJoin('#__eshop_stockstatusdetails AS b ON (a.id = b.stockstatus_id)')
            ->where('a.published = 1')
            ->where('b.language = "' . JComponentHelper::getParams('com_languages')->get('site', 'en-GB') . '"');
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        $options = array();
        $options[] = JHtml::_('select.option', 0, JText::_('ESHOP_BATCH_PRODUCTS_KEEP_STOCK_STATUS'), 'value', 'text');
        if (count($rows)) {
            $options = array_merge($options, $rows);
        }
        $lists['product_stock_status_id'] = JHtml::_('select.genericlist', $options, 'product_stock_status_id',
            array(
                'option.text.toHtml' => false,
                'option.value' => 'value',
                'option.text' => 'text',
                'list.attr' => ' class="inputbox chosen" ',
                'list.select' => ''));

        //Build stock checkout list
        $lists['product_stock_checkout'] = JHtml::_('select.booleanlist', 'product_stock_checkout', ' class="inputbox" disabled ', 1);
        //Build shipping list
        $lists['product_shipping'] = JHtml::_('select.booleanlist', 'product_shipping', ' class="inputbox" disabled ', 1);
        //Build featured  list
        $lists['product_featured'] = JHtml::_('select.booleanlist', 'product_featured', ' class="inputbox" disabled ', 1);
        //Build call for price list
        $lists['product_call_for_price'] = JHtml::_('select.booleanlist', 'product_call_for_price', ' class="inputbox" disabled ', 1);
        //Quote mode
        $lists['product_quote_mode'] = JHtml::_('select.booleanlist', 'product_quote_mode', ' class="inputbox" disabled ', 1);
        EshopHelper::chosen();
        //End prepare the data for batch process
    }

    /**
     * Additional grid function for featured toggles
     *
     * @return string HTML code to write the toggle button
     */
    function toggle($field, $i, $imgY = 'tick.png', $imgX = 'publish_x.png', $prefix = '')
    {
        $img = $field ? $imgY : $imgX;
        $task = $field ? 'product.unfeatured' : 'product.featured';
        $alt = $field ? JText::_('ESHOP_PRODUCT_FEATURED') : JText::_('ESHOP_PRODUCT_UNFEATURED');
        $action = $field ? JText::_('ESHOP_PRODUCT_UNFEATURED') : JText::_('ESHOP_PRODUCT_FEATURED');
        return ('<a href="#" onclick="return listItemTask(\'cb' . $i . '\',\'' . $task . '\')" title="' . $action . '">' .
            JHtml::_('image', 'admin/' . $img, $alt, null, true) . '</a>');
    }

    public function featured($value = 0, $i, $canChange = true)
    {
        JHtml::_('bootstrap.tooltip');
        // Array of image, task, title, action
        $states = array(
            0 => array('unfeatured', 'product.featured', 'ESHOP_PRODUCT_UNFEATURED', 'ESHOP_PRODUCT_FEATURED'),
            1 => array('featured', 'product.unfeatured', 'ESHOP_PRODUCT_FEATURED', 'ESHOP_PRODUCT_UNFEATURED'),
        );
        $state = ArrayHelper::getValue($states, (int)$value, $states[1]);
        $icon = $state[0];
        if ($canChange) {
            $html = '<a href="#" onclick="return listItemTask(\'cb' . $i . '\',\'' . $state[1] . '\')" class="btn btn-micro hasTooltip' . ($value == 1 ? ' active' : '') . '" title="' . JHtml::tooltipText($state[3]) . '"><i class="icon-' . $icon . '"></i></a>';
        } else {
            $html = '<a class="btn btn-micro hasTooltip disabled' . ($value == 1 ? ' active' : '') . '" title="' . JHtml::tooltipText($state[2]) . '"><i class="icon-' . $icon . '"></i></a>';
        }
        return $html;
    }

    public function extraAttribute($value = 0, $i, $canChange = true, $extra = array())
    {
        // print_r($extra);die;
        JHtml::_('bootstrap.tooltip');
        // Array of image, task, title, action
        $states = array(
            0 => array('unfeatured', 'product.extra.' . $extra['id'], 'ESHOP_PRODUCT_UNFEATURED', 'ESHOP_PRODUCT_FEATURED'),
            1 => array('featured', 'product.unextra.' . $extra['id'], 'ESHOP_PRODUCT_UNFEATURED', 'ESHOP_PRODUCT_FEATURED'),
        );
        $state = ArrayHelper::getValue($states, (int)$value, $states[1]);
        $icon = $state[0];
        if ($canChange) {
            $html = '<a href="#" onclick="return listItemTask(\'cb' . $i . '\',\'' . $state[1] . '\')" class="btn btn-micro hasTooltip' . ($value == 1 ? ' active' : '') . '" title="' . JHtml::tooltipText($state[3]) . '"><i class="icon-' . $icon . '"></i></a>';
        } else {
            $html = '<a class="btn btn-micro hasTooltip disabled' . ($value == 1 ? ' active' : '') . '" title="' . JHtml::tooltipText($state[2]) . '"><i class="icon-' . $icon . '"></i></a>';
        }
        return $html;
    }

    /**
     *
     * Function to add tool bar
     */
    protected function addToolbar()
    {
        $toolbar = JToolbar::getInstance('toolbar');
        $title = JText::_('ESHOP_BATCH');
        // Instantiate a new JLayoutFile instance and render the batch button
        $layout = new JLayoutFile('joomla.toolbar.batch');
        $dhtml = $layout->render(array('title' => $title));
        $toolbar->appendButton('Custom', $dhtml, 'batch');
    }

    public function getCat($pro_id)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*')
            ->from('#__eshop_productcategories')
            ->where('product_id = ' . $pro_id);
        $db->setQuery($query);
        $row = $db->loadObject();
        return ($row->category_id);
    }

    public function getHomeAttributes()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*')
            ->from('#__eshop_home_group')
            ->where('published = 1')
            ->andWhere('group_type NOT IN ( \'product_featured\',  \'product_new\')')
            ->order('ordering ASC, title ASC');
        $db->setQuery($query);
        return $db->loadAssocList();

    }
}
