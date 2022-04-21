<?php
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
include(JPATH_ADMINISTRATOR . '/components/com_eshop/helper/BizHelper.php');

/**
 * HTML View class for EShop component
 *
 * @static
 * @package        Joomla
 * @subpackage    EShop
 * @since 1.5
 */
class EshopViewOrder extends EShopViewForm
{

    function _buildListArray(&$lists, $item)
    {
        $currency = new EshopCurrency();
        $db = JFactory::getDbo();
        //Customer list
        $query = $db->getQuery(true);
        $query->select('c.customer_id AS value, u.name AS text')
            ->from('#__eshop_customers AS c')
            ->innerJoin('#__users AS u ON (c.customer_id = u.id)')
            ->where('c.published = 1')
            ->where('u.block = 0');
        $db->setQuery($query);


        $lists['customer_id'] = JHtml::_('select.genericlist', $db->loadObjectList(), 'customer_id',
            array(
                'option.text.toHtml' => false,
                'option.text' => 'text',
                'option.value' => 'value',
                'list.attr' => ' class="inputbox chosen" ',
                'list.select' => $item->customer_id));
        //Customergroup list
        $query->clear();
        $query->select('a.id AS value, b.customergroup_name AS text')
            ->from('#__eshop_customergroups AS a')
            ->innerJoin('#__eshop_customergroupdetails AS b ON (a.id = b.customergroup_id)')
            ->where('a.published = 1')
            ->where('b.language = "' . JComponentHelper::getParams('com_languages')->get('site', 'en-GB') . '"')
            ->order('b.customergroup_name');
        $db->setQuery($query);
        $lists['customergroup_id'] = JHtml::_('select.genericlist', $db->loadObjectList(), 'customergroup_id',
            array(
                'option.text.toHtml' => false,
                'option.text' => 'text',
                'option.value' => 'value',
                'list.attr' => ' class="inputbox chosen" ',
                'list.select' => $item->customergroup_id));
        //Order products list
        $orderProducts = EshopHelper::getOrderProducts($item->id);
        $lists['order_products'] = $orderProducts;
        //Order totals list
        $query->clear();
        $query->select('*')
            ->from('#__eshop_ordertotals')
            ->where('order_id = ' . intval($item->id))
            ->order('id');
        $db->setQuery($query);
        $lists['order_totals'] = $db->loadObjectList();
        //Order status
        $paymentCountryId = $item->payment_country_id ? $item->payment_country_id : EshopHelper::getConfigValue('country_id');
        $shippingCountryId = $item->shipping_country_id ? $item->shipping_country_id : EshopHelper::getConfigValue('country_id');
        $query->clear();


        $nextStatus = BizHelper::getNextStatusList($item->order_status_id);

        $query->select('a.id, b.orderstatus_name')
            ->from('#__eshop_orderstatuses AS a')
            ->innerJoin('#__eshop_orderstatusdetails AS b ON (a.id = b.orderstatus_id)')
            ->where('a.published = 1')
            ->where('b.language = "' . JComponentHelper::getParams('com_languages')->get('site', 'en-GB') . '"')
            ->order('a.ordering ASC');
        $db->setQuery($query);
        $statusList = $db->loadObjectList();
        $newStatusList = array();
        foreach ($statusList as $status) {
            if ($status->id == $item->order_status_id || in_array($status->id, $nextStatus)) {
                $newStatusList[] = $status;
            }
        }
        $lists['order_status_id'] = JHtml::_('select.genericlist', $newStatusList, 'order_status_id', ' class="inputbox" ', 'id',
            'orderstatus_name', $item->order_status_id);


        //Payment and Shipping country, zone list
        $lists['payment_country_id'] = JHtml::_('select.genericlist', $this->_getCountryOptions(), 'payment_country_id',
            ' class="inputbox"  onchange="paymentCountry(this, \'' . $item->payment_zone_id . '\')" ', 'id', 'name', $paymentCountryId);
        $lists['payment_zone_id'] = JHtml::_('select.genericlist', $this->_getZoneList($paymentCountryId), 'payment_zone_id',
            ' class="inputbox" ', 'id', 'zone_name', $item->payment_zone_id);
        $lists['shipping_country_id'] = JHtml::_('select.genericlist', $this->_getCountryOptions(), 'shipping_country_id',
            ' class="inputbox"  onchange="shippingCountry(this, \'' . $item->shipping_zone_id . '\')" ', 'id', 'name', $shippingCountryId);
        $lists['shipping_zone_id'] = JHtml::_('select.genericlist', $this->_getZoneList($shippingCountryId), 'shipping_zone_id',
            ' class="inputbox" ', 'id', 'zone_name', $item->shipping_zone_id);
        $currency = new EshopCurrency();

        //Form for billing and shipping address
        $fields = array_keys($db->getTableColumns('#__eshop_orders'));
        $data = array();
        foreach ($fields as $field) {
            $data[$field] = $item->{$field};
        }
        $billingFields = EshopHelper::getFormFields('B');
        $shippingFields = EshopHelper::getFormFields('S');
        foreach ($billingFields as $field) {
            $field->name = 'payment_' . $field->name;
        }
        $billingForm = new RADForm($billingFields);
        $billingForm->bind($data);
        foreach ($shippingFields as $field) {
            $field->name = 'shipping_' . $field->name;
        }
        $shippingForm = new RADForm($shippingFields);
        $shippingForm->bind($data);

        $paymentZoneId = (int)$item->payment_zone_id;
        if (EshopHelper::isFieldPublished('country_id') && $paymentCountryId) {
            $countryField = $billingForm->getField('payment_country_id');
            if ($countryField instanceof RADFormFieldCountries) {
                $countryField->setValue($paymentCountryId);
            }
        }
        if ($paymentCountryId && EshopHelper::isFieldPublished('zone_id')) {
            $zoneField = $billingForm->getField('payment_zone_id');
            if ($zoneField instanceof RADFormFieldZone) {
                $zoneField->setCountryId($paymentCountryId);
                if ($paymentZoneId) {
                    $zoneField->setValue($paymentZoneId);
                }
            }
        }
        $shippingZoneId = (int)$item->shipping_zone_id;
        if (EshopHelper::isFieldPublished('country_id') && $shippingCountryId) {
            $countryField = $shippingForm->getField('shipping_country_id');
            if ($countryField instanceof RADFormFieldCountries) {
                $countryField->setValue($shippingCountryId);
            }
        }
        if ($shippingCountryId && EshopHelper::isFieldPublished('zone_id')) {
            $zoneField = $shippingForm->getField('shipping_zone_id');
            if ($zoneField instanceof RADFormFieldZone) {
                $zoneField->setCountryId($shippingCountryId);
                if ($shippingZoneId) {
                    $zoneField->setValue($shippingZoneId);
                }
            }
        }

        $this->billingForm = $billingForm;
        $this->shippingForm = $shippingForm;
        $this->currency = $currency;
    }

    /**
     *
     * Private method to get Country Options
     * @param array $lists
     */
    function _getCountryOptions()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('id, country_name AS name')
            ->from('#__eshop_countries')
            ->where('published = 1')
            ->order('country_name');
        $db->setQuery($query);
        $options = array();
        $options[] = JHtml::_('select.option', 0, JText::_('ESHOP_PLEASE_SELECT'), 'id', 'name');
        $options = array_merge($options, $db->loadObjectList());
        return $options;
    }

    /**
     *
     * Private method to get Zone Options
     * @param array $lists
     */
    function _getZoneList($countryId = '')
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('id, zone_name')
            ->from('#__eshop_zones')
            ->where('country_id = ' . (int)$countryId)
            ->where('published = 1');
        $db->setQuery($query);
        $options = array();
        $options[] = JHtml::_('select.option', 0, JText::_('ESHOP_PLEASE_SELECT'), 'id', 'zone_name');
        $options = array_merge($options, $db->loadObjectList());
        return $options;
    }

    /**
     * Override Build Toolbar function, only need Save, Save & Close and Close
     */
    function _buildToolbar()
    {
        $viewName = $this->getName();
        $canDo = EshopHelper::getActions($viewName);
        $text = JText::_($this->lang_prefix . '_EDIT');
        JToolBarHelper::title(JText::_($this->lang_prefix . '_' . $viewName) . ': <small><small>[ ' . $text . ' ]</small></small>');
        if ($canDo->get('core.edit')) {
            JToolBarHelper::apply($viewName . '.apply');
            JToolBarHelper::save($viewName . '.save');
        }
        if (EshopHelper::getConfigValue('invoice_enable'))
            JToolBarHelper::custom('order.downloadInvoice', 'print', 'print', Jtext::_('ESHOP_DOWNLOAD_INVOICE'), false);
            JToolBarHelper::custom('order.viewDownloadInvoice', 'print', 'print', 'Xem và In', false);
        JToolBarHelper::cancel($viewName . '.cancel', 'JTOOLBAR_CLOSE');
    }
}
