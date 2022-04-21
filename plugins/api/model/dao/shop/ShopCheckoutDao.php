<?php
/**
 * Created by PhpStorm.
 * User: lvson
 * Date: 4/26/2019
 * Time: 10:53 AM
 */

namespace api\model\dao\shop;

use api\model\AbtractDao;
use api\model\dao\shop\ShopStockDao;
use api\model\Sconfig;
use api\model\services\Onesignal;
use api\model\SUtil;
use Joomla\Registry\Registry;

$language = \JFactory::getLanguage();
$language->load('com_eshop', JPATH_SITE, 'vi-VN', true);
require_once(JPATH_SITE . '/administrator/components/com_eshop/libraries/defines.php');
require_once(JPATH_SITE . '/administrator/components/com_eshop/libraries/rad/form/field.php');
require_once(JPATH_SITE . '/administrator/components/com_eshop/libraries/rad/form/field/list.php');
require_once(JPATH_SITE . '/administrator/components/com_eshop/libraries/rad/form/field/checkboxes.php');
require_once(JPATH_SITE . '/administrator/components/com_eshop/libraries/rad/form/field/countries.php');

require_once(JPATH_SITE . '/administrator/components/com_eshop/libraries/rad/form/field/radio.php');
require_once(JPATH_SITE . '/administrator/components/com_eshop/libraries/rad/form/field/text.php');
require_once(JPATH_SITE . '/administrator/components/com_eshop/libraries/rad/form/field/textarea.php');
require_once(JPATH_SITE . '/administrator/components/com_eshop/libraries/rad/form/field/zone.php');
require_once(JPATH_SITE . '/components/com_eshop/helpers/html.php');

require_once(JPATH_SITE . '/administrator/components/com_eshop/libraries/rad/validator/validator.php');
require_once(JPATH_SITE . '/administrator/components/com_eshop/libraries/rad/form/form.php');
require_once(JPATH_SITE . '/components/com_eshop/plugins/payment/os_payment.php');
require_once(JPATH_SITE . '/administrator/components/com_eshop/tables/eshop.php');
require_once(JPATH_SITE . '/components/com_eshop/helpers/customer.php');
require_once(JPATH_SITE . '/components/com_eshop/helpers/voucher.php');
require_once(JPATH_SITE . '/components/com_eshop/helpers/payment.php');
require_once(JPATH_SITE . '/components/com_eshop/helpers/coupon.php');
require_once(JPATH_SITE . '/components/com_eshop/helpers/donate.php');
require_once(JPATH_SITE . '/components/com_eshop/helpers/shipping.php');
require_once(JPATH_SITE . '/components/com_eshop/helpers/discount.php');
require_once(JPATH_SITE . '/components/com_eshop/helpers/tax.php');
require_once(JPATH_SITE . '/components/com_eshop/helpers/customer.php');
require_once(JPATH_SITE . '/components/com_eshop/helpers/image.php');
require_once(JPATH_SITE . '/components/com_eshop/helpers/helper.php');
require_once(JPATH_SITE . '/components/com_eshop/helpers/currency.php');
require_once(JPATH_SITE . '/administrator/components/com_eshop/libraries/inflector.php');
require_once(JPATH_SITE . '/administrator/components/com_eshop/libraries/mvc/model.php');
require_once(JPATH_SITE . '/components/com_eshop/models/checkout.php');
require_once(JPATH_SITE . '/components/com_eshop/helpers/cart.php');
require_once(JPATH_SITE . '/components/com_eshop/helpers/currency.php');


class ShopCheckoutDao extends AbtractDao
{

    public $products = array();
    public $address;
    public $shipping;
    public $payment;
    public $coupon = '';
    public $voucher = '';

    public $result = array();
    public $comment = '';
    public $total;
    public $bank_tranfer = '';
    public $shipping_fee = 0;
    public $shipping_token = '';
    public $delivery_time = '';
    public $payment_menthod = '';
    public $delivery_hour = '';

    public $error = '';

    public function getTable()
    {
        return '#__eshop_addresses';
    }

    public function setProducts($products)
    {
        $this->products = $products;
    }

    public function setAddress($address)
    {
        $this->address = $address;
        $this->address['telephone'] = $this->address['phone'];
        $this->address['firstname'] = $this->address['name'];
    }

    public function setShipping($shipping)
    {
        $this->shipping = $shipping;
        $this->shipping['telephone'] = $this->shipping['phone'];
        $this->shipping['firstname'] = $this->shipping['name'];
    }

    public function setPayment($payment)
    {
        $this->payment = $payment;
    }

    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    public function setCoupon($coupon)
    {
        $this->coupon = $coupon;
    }

    public function setVoucher($voucher)
    {
        $this->voucher = $voucher;
    }

    public function setTotal($total)
    {
        $this->total = $total;
    }

    /**
     * @return string
     */
    public function getDeliveryTime()
    {
        if ($this->delivery_time != '') {

            return $this->delivery_time;
        }
        return date('Y-m-d H:i:s');
    }

    /**
     * @param string $delivery_time
     */
    public function setDeliveryTime($delivery_time)
    {
        $tmp = explode('/', $delivery_time);
        if (count($tmp) == 3) {
            $delivery_time = "{$tmp[2]}-{$tmp[1]}-{$tmp[0]}";
        }
        $this->delivery_time = $delivery_time;
    }

    /**
     * @return string
     */
    public function getDeliveryHour()
    {
        return $this->delivery_hour;
    }

    /**
     * @param string $delivery_hour
     */
    public function setDeliveryHour($delivery_hour)
    {
        $this->delivery_hour = $delivery_hour;
    }


    /**
     * @return int
     */
    public function getShippingFee()
    {
        return $this->shipping_fee;
    }

    /**
     * @param int $shipping_fee
     */
    public function setShippingFee($shipping_fee)
    {
        $this->shipping_fee = $shipping_fee;
    }

    /**
     * @return string
     */
    public function getShippingToken()
    {
        return $this->shipping_token;
    }

    /**
     * @param string $shipping_token
     */
    public function setShippingToken($shipping_token)
    {
        $this->shipping_token = $shipping_token;
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param string $version
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }


    public function checkout()
    {
        $config = new Sconfig();
        if ($config->order_limit) {
            $curTime = \EshopHelper::renderDate(date('Y:m:d H:i:s'), 'H:i');
            if ($curTime < $config->order_begin || $curTime > $config->order_end) {
                $this->error = "Chỉ nhận đơn hàng trong khoản thời gian {$config->order_begin} - {$config->order_end}. Vui lòng thử lại sau.".$curTime;
                return false;
            }
        }
        $model = new \EShopModelCheckout();
        $cart = new \EshopCart();
        $cart->clear();
        // Set cart
        $data = array();
        $data['stock'] = array();
        foreach ($this->products as $k => $item) {
            $options = array();
            if ($item['op']) {
                foreach ($item['op'] as $op) {
                    if (isset($options[$op['op_id']])) {
                        $options[$op['op_id']] = (array)$options[$op['op_id']];
                        $options[$op['op_id']][] = $op['id'];
                    } else {
                        $options[$op['op_id']] = $op['id'];
                    }

                }
            }
            $this->products[$k]['key'] = $cart->add($item['id'], $item['quantity'], $options);
        }

        $stockStatus = $cart->validateStockStatus();
        if ($stockStatus) {
            $error = array();
            foreach ($data as $item) {
                if (isset($stockStatus[$item['key']])) {
                    $error[] = $item['uid'];
                }
            }
            $this->error = 'S:' . implode('|', $error);
            return false;
        }


        $session = \JFactory::getSession();
        if ($this->coupon) {
            $session->set('coupon_code', $this->coupon);

            $coupon = \EshopHelper::getCoupon($this->coupon);
            $data['coupon_id'] = $coupon->id;
            $data['coupon_code'] = $coupon->coupon_code;
        } else {
            $data['coupon_id'] = 0;
            $data['coupon_code'] = '';
        }


        if ($this->voucher) {
            $session->set('voucher_code', $this->voucher);
            $voucher = \EshopHelper::getVoucher($this->voucher);
            $data['voucher_id'] = $voucher->id;
            $data['voucher_code'] = $voucher->voucher_code;
        } else {
            $data['voucher_id'] = 0;
            $data['voucher_code'] = '';
        }

        // Get information for the order
        if ($this->getShippingFee()) {
            $shipper = SUtil::decryptData($this->shipping_token);
            $method = explode('.', $shipper['name']);
            $model->getCosts($shipper['cost']);

            $data['shipping_method'] = $method[0];
            $data['shipping_method_title'] = $shipper['title'];
            $data['shipping_lng'] = $shipper['lng'];
            $data['shipping_lat'] = $shipper['lat'];
        } else {
            $model->getCosts();
        }

        $data['delivery_date'] = $this->getDeliveryTime();
        $data['delivery_hour'] = $this->getDeliveryHour();

        $totalData = $model->getTotalData();
        $total = $model->getTotal();
        if ($this->total != $total) {
            $cartData = $cart->getCartData();
            $error_price = array();
            foreach ($this->products as $item) {
                if ($item['price'] != $cartData[$item['key']]['price']) {
                    $error_price[] = $item['uid'];
                }
            }
            $this->error = 'P:' . implode('|', $error_price);
            return false;
        }

        $cartAmount = 0;
        foreach ($totalData as $total_item) {
            if ($total_item['name'] == 'sub_total') {
                $cartAmount = $total_item['value'];
            }
        }
        $config = new Sconfig();
        if ($cartAmount < $config->minCartAmount) {
            $this->error = 'Đặt hàng online chỉ áp dụng cho đơn hàng tối thiểu ' . number_format($config->minCartAmount) . ' '.BIZ_XU;
            return false;
        }


        $user = \JFactory::getUser();

        // Prepare customer data
        if ($user->get('id')) {
            $data['customer_id'] = $user->get('id');
            $data['email'] = $user->get('email');
            $this->address['email'] = $data['email'];
            $customer = \EshopHelper::getCustomer($user->get('id'));

            if (is_object($customer)) {
                $data['customergroup_id'] = $customer->customergroup_id;
                $data['firstname'] = $customer->firstname;
                $data['lastname'] = $customer->lastname;
                $data['telephone'] = $customer->telephone;
                $data['fax'] = $customer->fax;
            } else {
                $data['customergroup_id'] = '';
                $data['firstname'] = '';
                $data['lastname'] = '';
                $data['telephone'] = '';
                $data['fax'] = '';
            }

            //$paymentAddress = EshopHelper::getAddress($session->get('payment_address_id'));
        }

        // Prepare payment data
        $billingFields = \EshopHelper::getFormFields('B');


        foreach ($billingFields as $field) {
            $fieldName = $field->name;

            if (isset($this->address[$fieldName])) {
                if (is_array($this->address[$fieldName])) {
                    $data['payment_' . $fieldName] = json_encode($this->address[$fieldName]);
                } else {
                    $data['payment_' . $fieldName] = $this->address[$fieldName];
                }
            } else {
                $data['payment_' . $fieldName] = '';
            }
        }
        $data['payment_zone_name'] = $this->address['zone_name'];
        $data['payment_country_name'] = $this->address['country_name'];
        $data['payment_method'] = $this->payment['name'];
        $data['payment_method_title'] = $this->payment['title'];

        // Prepare shipping data
        $shippingFields = \EshopHelper::getFormFields('S');

        if ($this->shipping) {

            foreach ($shippingFields as $field) {
                $fieldName = $field->name;

                if (isset($this->shipping[$fieldName])) {
                    if (is_array($this->shipping[$fieldName])) {
                        $data['shipping_' . $fieldName] = json_encode($this->shipping[$fieldName]);
                    } else {
                        $data['shipping_' . $fieldName] = $this->shipping[$fieldName];
                    }
                } else {
                    $data['shipping_' . $fieldName] = '';
                }
            }

            $data['shipping_zone_name'] = $this->shipping['zone_name'];
            $data['shipping_country_name'] = $this->shipping['country_name'];
        } else {
            foreach ($shippingFields as $field) {
                $fieldName = $field->name;
                $data['shipping_' . $fieldName] = '';
            }

            $data['shipping_zone_name'] = '';
            $data['shipping_country_name'] = '';
        }
        if (!$data['shipping_method']) {
            $config = new Sconfig();
            $data['shipping_method'] = 'eshop_free';
            $data['shipping_method_title'] = $config->siteName . ' vận chuyển';
        }


//        if ($this->getShippingFee() == 0) {
//            $data['shipping_method'] = '';
//            $data['shipping_method_title'] = '';
//        }

        $data['totals'] = $totalData;
        //$data['delivery_date'] = '';
        $data['comment'] = $this->comment;
        $data['order_status_id'] = \EshopHelper::getConfigValue('order_status_id');
        $data['language'] = \JFactory::getLanguage()->getTag();
        $currency = new \EshopCurrency();
        $data['currency_id'] = $currency->getCurrencyId();
        $data['currency_code'] = $currency->getCurrencyCode();


        $data['currency_exchanged_value'] = $currency->getExchangedValue();
        $data['total'] = $total;
        $data['order_number'] = '';
        $data['invoice_number'] = \EshopHelper::getInvoiceNumber();
        $data['ref_fee'] = $this->getShippingFee();

        $this->result['email'] = $data['email'];
        return $this->processOrder($data);
    }

    public function getOrderId()
    {
        return $this->result['id'];
    }

    public function getSuccessResult()
    {
        $config = new Sconfig();
        $this->result['title'] = sprintf('Cảm ơn bạn đã mua sắm mua sắm tại ' . $config->siteName . ' với mã đơn hàng: %s', $this->result['order_number']);


        if ($this->payment_menthod == 'os_bank_transfer') {
            $this->result['notes'] = array(
                'Vui lòng chuyển khoản vào số tài khoản: ',
                // sprintf('Email xác nhận đã đuợc gửi đến địa chỉ %s. Nếu không thấy email trong hộp thư đến (Inbox), vui lòng kiểm tra hộp thư Spam hoạc Junk Folder.', $this->result['email']),

            );
            $this->result['notes'][] = $this->bank_tranfer . "\n\n";
            //$this->result['notes'][] = 'Nội dung chuyển khoản: Mã đơn hàng + Số điện thoại';
        } else {
            $this->result['notes'] = array(
                'Bộ phận Bán hàng sẽ liên hệ với bạn nhanh nhất có thể để xác nhận đơn hàng.',
                // sprintf('Email xác nhận đã đuợc gửi đến địa chỉ %s. Nếu không thấy email trong hộp thư đến (Inbox), vui lòng kiểm tra hộp thư Spam hoạc Junk Folder.', $this->result['email']),

            );
        }

        $this->result['notes'][] = sprintf('Nếu có bất kỳ thắc mắc nào, vui lòng gọi đến %s để nhận đuợc hỗ trợ cần thiết.', $config->hotline);
        return $this->result;
    }

    public function getOnePayMessage($order_number)
    {
        $config = new Sconfig();
        $this->result = array();
        $this->result['title'] = sprintf('Cảm ơn bạn đã mua sắm tại ' . $config->siteName . ' với mã đơn hàng: %s', $order_number);
        $this->result['notes'][] = sprintf('Nếu có bất kỳ thắc mắc nào, vui lòng gọi đến %s để nhận đuợc hỗ trợ cần thiết.', $config->hotline);
        return $this->result;
    }

    public function getError()
    {
        return $this->error;
    }

    /**
     * Function to process order
     */
    public function processOrder($data)
    {
        jimport('joomla.user.helper');

        $session = \JFactory::getSession();
        $cart = new \EshopCart();
        $tax = new \EshopTax(\EshopHelper::getConfig());
        $currency = new \EshopCurrency();
        $db = \JFactory::getDbo();
        $query = $db->getQuery(true);

        // Store Order
        $row = \JTable::getInstance('Eshop', 'Order');
        $row->bind($data);
        $row->user_ip = \EshopHelper::getUserIp();
        if ($session->get('newsletter_interest')) {
            $row->newsletter_interest = 1;
        }
        $row->privacy_policy_agree = 1;
        $row->created_date = \JFactory::getDate()->toSql();
        $row->modified_date = \JFactory::getDate()->toSql();
        $row->modified_by = 0;
        $row->checked_out = 0;
        $row->checked_out_time = '0000-00-00 00:00:00';
        $row->channel = 'mobile';
        $row->store();
        $orderRow = $row;
        $orderId = $row->id;
        $session->set('order_id', $orderId);
        \EshopHelper::trackHistory(array('id' => $row->id, 'order_status_id' => @$row->order_status_id));
        //print_r($row);
        //\EshopHelper::updateCommissionFields($row->id, $row->customer_id, $row->total);
        if($row->id > 0 && $row->customer_id > 0){
          $cuser = \JFactory::getUser($row->customer_id);
          if($cuser->level_tree > 0){
            \EshopHelper::updateCommissionFields($row->id, $row->customer_id, $row->total);
          }
        }

        $data['order_number'] = \EshopHelper::updateOrderNumber($orderId);
        $this->result['order_number'] = $data['order_number'];

        $data['created_date'] = date('d/m/Y H:i');

        // Set payment info
        $sql = "SELECT params FROM #__eshop_payments WHERE `name` = " . $db->quote($row->payment_method);
        $paymentInfo = $db->setQuery($sql)->loadAssoc();

        if ($paymentInfo['params']) {
            $paymentParams = new Registry($paymentInfo['params']);
            $this->bank_tranfer = nl2br($paymentParams->get('payment_info'));
            $this->payment_menthod = $row->payment_method;
        }

        $row->load($orderId);
        $orderTotal = $row->total;

        // Store Order Products, Order Options and Order Downloads
        $stockInfo = array();
        $stockProducts = array();
        foreach ($cart->getCartData() as $product) {
            // Order Products
            $row = \JTable::getInstance('Eshop', 'Orderproducts');
            $row->id = '';
            $row->order_id = $orderId;
            $row->product_id = $product['product_id'];
            $row->product_name = $product['product_name'];
            $row->product_sku = $product['product_sku'];
            $row->quantity = $product['quantity'];
            $row->price = $product['price'];
            $row->total_price = $product['total_price'];
            $row->tax = $tax->getTax($product['price'], $product['product_taxclass_id']);
            $emailProduct = array();
            if (isset($data['stock'][$product['product_id']])) {
                $row->stock_id = $data['stock'][$product['product_id']];
                $stockInfo[] = array('qty' => $row->quantity, 'stock_id' => $row->stock_id, 'product_id' => $row->product_id);
                $emailProduct = array(
                    'product_name' => $row->product_name,
                    'product_sku' => $row->product_sku,
                    'quantity' => $row->quantity,
                    'price' => $row->price,
                    'total_price' => $row->total_price,
                );
            }
            $row->modified_date = \JFactory::getDate()->toSql();
            $row->status_id = 8;
            $row->store();
            $orderProductId = $row->id;

            $emailProduct['options'] = array();
            // Order Options
            foreach ($product['option_data'] as $option) {
                $row = \JTable::getInstance('Eshop', 'Orderoptions');
                $row->id = '';
                $row->order_id = $orderId;
                $row->order_product_id = $orderProductId;
                $row->product_option_id = $option['product_option_id'];
                $row->product_option_value_id = $option['product_option_value_id'];
                $row->option_name = $option['option_name'];
                $row->option_value = $option['option_value'];
                $row->option_type = $option['option_type'];
                $row->sku = $option['sku'];
                $row->store();
                $emailProduct['options'][] = $row->option_name;
            }

            if (isset($data['stock'][$product['product_id']])) {
                $stockUid = $data['stock'][$product['product_id']];
                if (!isset($stockProducts[$stockUid])) {
                    $stockProducts[$stockUid] = array();
                }
                $stockProducts[$stockUid][] = $emailProduct;
            }

            // Order Downloads
            foreach ($product['download_data'] as $download) {
                $row = \JTable::getInstance('Eshop', 'Orderdownloads');
                $row->id = '';
                $row->order_id = $orderId;
                $row->order_product_id = $orderProductId;
                $row->download_id = $download['id'];
                $row->download_name = $download['download_name'];
                $row->filename = $download['filename'];

                //Generate download code
                $downloadCode = '';
                while (true) {
                    $downloadCode = \JUserHelper::genRandomPassword(10);
                    $query->clear()
                        ->select('COUNT(*)')
                        ->from('#__eshop_orderdownloads')
                        ->where('download_code = "' . $downloadCode . '"');
                    $db->setQuery($query);

                    if (!$db->loadResult()) {
                        break;
                    }
                }

                $row->download_code = $downloadCode;
                $row->remaining = $download['total_downloads_allowed'];
                $row->store();
            }
        }

        // Store Order Totals
        foreach ($data['totals'] as $total) {
            $row = \JTable::getInstance('Eshop', 'Ordertotals');
            $row->id = '';
            $row->order_id = $orderId;

            if ($total['name'] == 'eshop_ghtk' && $total['name'] != $data['shipping_method']) {
                $row->name = $data['shipping_method'];
                $row->title = $data['shipping_method_title'];
            } else {
                $row->name = $total['name'];
                $row->title = $total['title'];
            }


            $row->text = $total['text'];
            $row->value = $total['value'];
            $row->store();
        }

        \JPluginHelper::importPlugin('eshop');
        \JFactory::getApplication()->triggerEvent('onAfterStoreOrder', array($orderRow));
        $data['order_id'] = $orderId;
        $this->result['id'] = $data['order_id'];
        // Prepare products data
        $productData = array();

        foreach ($cart->getCartData() as $product) {
            $optionData = array();

            foreach ($product['option_data'] as $option) {
                $optionData[] = array(
                    'option_name' => $option['option_name'],
                    'option_value' => $option['option_value']
                );
            }

            $productData[] = array(
                'product_name' => $product['product_name'],
                'product_sku' => $product['product_sku'],
                'option_data' => $optionData,
                'quantity' => $product['quantity'],
                'weight' => $product['weight'],
                'price' => round($currency->convert($product['price'], \EshopHelper::getConfigValue('default_currency_code'), $data['currency_code']), 2)
            );
        }

        //Get total for shipping, taxes
        $otherTotal = round($currency->convert($data['total'] - $cart->getSubTotal(), \EshopHelper::getConfigValue('default_currency_code'), $data['currency_code']), 2);
        $data['discount_amount_cart'] = 0;

        if ($otherTotal > 0) {
            $productData[] = array(
                'product_name' => \JText::_('ESHOP_SHIPPING_DISCOUNTS_AND_TAXES'),
                'product_sku' => '',
                'option_data' => array(),
                'quantity' => 1,
                'weight' => 0,
                'price' => $otherTotal
            );
        } else {
            $data['discount_amount_cart'] -= $otherTotal;
        }

        $data['products'] = $productData;

        if ($session->get('newsletter_interest')) {
            if (\EshopHelper::getConfigValue('acymailing_integration') && \JFile::exists(JPATH_ADMINISTRATOR . '/components/com_acymailing/helpers/helper.php')) {
                $acyMailingIntegration = true;
            } else {
                $acyMailingIntegration = false;
            }

            $mailchimpIntegration = \EshopHelper::getConfigValue('mailchimp_integration');

            foreach ($cart->getCartData() as $product) {
                //Store customer to AcyMailing
                if ($acyMailingIntegration) {
                    $params = new \JRegistry($product['params']);
                    $listIds = $params->get('acymailing_list_ids', '');
                    if ($listIds != '') {
                        require_once JPATH_ADMINISTRATOR . '/components/com_acymailing/helpers/helper.php';
                        $userClass = acymailing_get('class.subscriber');
                        $subId = $userClass->subid($row->email);
                        if (!$subId) {
                            $myUser = new stdClass();
                            $myUser->email = $data['email'];
                            $myUser->name = $data['firstname'] . ' ' . $data['lastname'];
                            $myUser->userid = $data['customer_id'];
                            $eventClass = acymailing_get('class.subscriber');
                            $subId = $eventClass->save($myUser);
                        }
                        $listIds = explode(',', $listIds);
                        $newProduct = array();
                        foreach ($listIds as $listId) {
                            $newList = array();
                            $newList['status'] = 1;
                            $newProduct[$listId] = $newList;
                        }
                        $userClass->saveSubscription($subId, $newProduct);
                    }
                }

                //Store subscriber to MailChimp
                if ($mailchimpIntegration) {
                    $params = new Registry($product['params']);
                    $listIds = $params->get('mailchimp_list_ids', '');

                    if ($listIds != '') {
                        $listIds = explode(',', $listIds);

                        if (count($listIds)) {
                            require_once JPATH_SITE . '/components/com_eshop/helpers/MailChimp.php';

                            $mailchimp = new \MailChimp(\EshopHelper::getConfigValue('api_key_mailchimp'));

                            foreach ($listIds as $listId) {
                                if ($listId) {
                                    $mailchimp->call('lists/subscribe', array(
                                        'id' => $listId,
                                        'email' => array('email' => $data['email']),
                                        'merge_vars' => array('FNAME' => $data['firstname'], 'LNAME' => $data['lastname']),
                                        'double_optin' => false,
                                        'update_existing' => true,
                                        'replace_interests' => false,
                                        'send_welcome' => false,
                                    ));
                                }
                            }
                        }
                    }
                }
            }
        }

        // Send onesignal message
        //Onesignal::sendMessage();


        // Update stock info
        /*
        if ($stockInfo) {
            $db = \JFactory::getDbo();
            $query = $db->getQuery(true);
            $stockNotify = array();
            foreach ($stockInfo as $item) {
                $stockNotify[] = $item['stock_id'];
                $query->clear()
                    ->update('#__eshop_stock_product')
                    ->set('qty = qty - ' . intval($item['qty']))
                    ->where('stock_id = ' . intval($item['stock_id']))
                    ->andWhere('product_id =' . intval($item['product_id']));
                $db->setQuery($query);
                $db->execute();
            }

            $stockNotify = array_filter($stockNotify);
            if ($stockNotify) {
                $stocksManager = $this->getStockManagerInfo($stockNotify);
                if($stocksManager){
                    $shipInfo = array();
                    $shipInfo[] = $data['shipping_address_1'];
                    $shipInfo[] = $data['shipping_zone_name'];
                    $shipInfo[] = $data['shipping_country_name'];
                    foreach ($stocksManager as $stockItem){
                        $stockItem['order_number'] = $data['order_number'];
                        $stockItem['customer'] = $data['firstname'].' '.$data['lastname'];
                        $stockItem['comment'] = $data['comment'];
                        $stockItem['created_date'] = $data['created_date'];
                        $stockItem['customer_email'] = $data['email'];
                        $stockItem['customer_telephone'] = $data['telephone'];
                        $stockItem['shipping_method'] = $data['shipping_method'];
                        $stockItem['shipping_address'] = implode(', ', array_filter($shipInfo));

                        if(isset($stockProducts[$stockItem['stock_id']])){
                            $stockItem['products'] = $stockProducts[$stockItem['stock_id']];
                        }
                        \EshopHelper::sendManagerEmails($stockItem);
                    }
                }


            }
        }
        */


        if ($data['payment_method'] == 'os_onepay') {
            return true;
        }


        if ($orderTotal > 0) {
            // Process Payment here
            $paymentMethod = $data['payment_method'];
            require_once JPATH_SITE . '/components/com_eshop/plugins/payment/' . $paymentMethod . '.php';

            $db = \JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select('params, title')
                ->from('#__eshop_payments')
                ->where('name = ' . $db->quote($paymentMethod));
            $db->setQuery($query);

            $plugin = $db->loadObject();
            $params = new Registry($plugin->params);
            $paymentClass = new $paymentMethod($params);
            $paymentClass->setTitle($plugin->title);

            $rf = new \ReflectionMethod($paymentClass, 'processPayment');

            $completedMethod = array('os_offline', 'os_bank_transfer');
            if (\in_array($paymentMethod, $completedMethod)) {
                \EshopHelper::completeOrder($orderRow);
                //Send confirmation email here
                if (\EshopHelper::getConfigValue('order_alert_mail')) {
                    \EshopHelper::sendEmails($orderRow);
                }
            } elseif ($rf->getNumberOfParameters() == '3') {
                $this->redirectUrl = $paymentClass->processPayment($data, false);
            } elseif ($rf->getNumberOfParameters() == '1') {
                $this->redirectUrl = $paymentClass->processPayment($data);
            } else {
                $this->redirectUrl = $paymentClass->processPayment($orderRow, $data);
            }

            /*if ($rf->getNumberOfParameters() == '1') {
                $paymentClass->processPayment($data);
            } else {
                $paymentClass->processPayment($orderRow, $data);
            }*/

        } else {
            // If total = 0, then complete order
            $row = JTable::getInstance('Eshop', 'Order');
            $id = $data['order_id'];
            $row->load($id);
            $row->order_status_id = \EshopHelper::getConfigValue('complete_status_id');
            $row->store();
            \EshopHelper::completeOrder($orderRow);

            \JPluginHelper::importPlugin('eshop');
            \JFactory::getApplication()->triggerEvent('onAfterCompleteOrder', array($orderRow));

            //Send confirmation email here
            if (\EshopHelper::getConfigValue('order_alert_mail')) {
                \EshopHelper::sendEmails($orderRow);
            }


        }
        return true;
    }


    public function getStockManagerInfo($stock_id = array())
    {
        $sql = 'SELECT u.name as stock_name, u.email, s.stock_id FROM `#__eshop_stock_user` AS s
                LEFT JOIN `#__users` AS u
                ON s.user_id = u.id
                WHERE s.is_stock_manager = 1 AND s.stock_id IN (' . implode(',', $stock_id) . ')';
        return $this->db->setQuery($sql)->loadAssocList();
    }

    public function getRedirectUrl()
    {
        return isset($this->redirectUrl) ? $this->redirectUrl : '';
    }

}
