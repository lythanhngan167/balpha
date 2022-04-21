<?php
/**
 * @version        3.1.0
 * @package        Joomla
 * @subpackage     EShop
 * @author         Giang Dinh Truong
 * @copyright      Copyright (C) 2013 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die;

class EshopCart
{
    /**
     * Cart id
     *
     * @var int
     */
    protected $cart_id;

    /**
     * Session cart data
     *
     * @var array
     */
    protected $cart;

    /**
     *
     * Entity cart data
     * @var array
     */
    protected $cartData;

    protected $cartAPI = null;
    protected $user_id = null;

    /**
     * Constructor function
     */
    public function __construct()
    {
        $this->cart_id = 0;
        $this->cart = JFactory::getSession()->get('cart');
        $this->cartData = array();

        $this->cartAPI = array();
        $this->user_id = 0;

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $user = JFactory::getUser();

        if ($user->get('id') > 0) {
            $query->select('*')
                ->from('#__eshop_carts')
                ->where('customer_id = ' . intval($user->get('id')));
            $db->setQuery($query);
            $cartRow = $db->loadObject();

            if (is_object($cartRow)) {
                $this->cart = json_decode($cartRow->cart_data, true);
                $this->cart_id = $cartRow->id;
            }
        }
    }

    /**
     *
     * Function to get data in the cart
     */
    public function getCartData()
    {
        $session = JFactory::getSession();
        $cart = $this->cart;

        if (!$cart) {
            $cart = $this->cartAPI;
        }

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $user = JFactory::getUser();

        if ($user->get('id')) {
            $customer = new EshopCustomer();
            $customerGroupId = $customer->getCustomerGroupId();
        } else {
            $customerGroupId = EshopHelper::getConfigValue('customergroup_id');
        }

        $weightTotal = array();

        if (!$this->cartData && count($cart)) {
            $baseUri = JUri::base(true);
            foreach ($cart as $key => $quantity) {
                $keyArr = explode(':', $key);
                $productId = $keyArr[0];
                $stock = true;
                $checkQty = true;

                if (isset($keyArr[1])) {
                    $options = unserialize(base64_decode($keyArr[1]));
                } else {
                    $options = array();
                }

                //Get product information
                $query->clear()
                    ->select('a.*, b.product_name, b.product_alias, b.product_desc, b.product_short_desc, b.meta_key, b.meta_desc')
                    ->from('#__eshop_products AS a')
                    ->innerJoin('#__eshop_productdetails AS b ON (a.id = b.product_id)')
                    ->where('a.id = ' . intval($productId))
                    ->where('b.language = "' . JFactory::getLanguage()->getTag() . '"');
                $db->setQuery($query);
                $row = $db->loadObject();

                if (is_object($row)) {
                    // Image
                    $imageSizeFunction = EshopHelper::getConfigValue('cart_image_size_function', 'resizeImage');

                    if ($row->product_image && JFile::exists(JPATH_ROOT . '/media/com_eshop/products/' . $row->product_image)) {
                        if (EshopHelper::getConfigValue('product_use_image_watermarks')) {
                            $watermarkImage = EshopHelper::generateWatermarkImage(JPATH_ROOT . '/media/com_eshop/products/' . $row->product_image);
                            $productImage = $watermarkImage;
                        } else {
                            $productImage = $row->product_image;
                        }

                        $image = call_user_func_array(array('EshopHelper', $imageSizeFunction), array($productImage, JPATH_ROOT . '/media/com_eshop/products/', EshopHelper::getConfigValue('image_cart_width'), EshopHelper::getConfigValue('image_cart_height')));
                    } else {
                        $image = call_user_func_array(array('EshopHelper', $imageSizeFunction), array('no-image.png', JPATH_ROOT . '/media/com_eshop/products/', EshopHelper::getConfigValue('image_cart_width'), EshopHelper::getConfigValue('image_cart_height')));
                    }

                    $image = $baseUri . '/media/com_eshop/products/resized/' . $image;
                    $price = $row->product_price;
                    //Prepare option data here
                    $optionData = array();
                    $optionPrice = 0;
                    $optionWeight = 0;
                    if ($row->product_total_weight > 0) {
                        $checkQty = false;
                    }

                    foreach ($options as $productOptionId => $optionValue) {
                        $query->clear()
                            ->select('po.id, po.option_id, o.option_type, od.option_name')
                            ->from('#__eshop_productoptions AS po')
                            ->innerJoin('#__eshop_options AS o ON (po.option_id = o.id)')
                            ->innerJoin('#__eshop_optiondetails AS od ON (o.id = od.option_id)')
                            ->where('po.id = ' . intval($productOptionId))
                            ->where('po.product_id = ' . intval($row->id))
                            ->where('od.language = "' . JFactory::getLanguage()->getTag() . '"');
                        $db->setQuery($query);
                        $optionRow = $db->loadObject();

                        if (is_object($optionRow)) {
                            if ($optionRow->option_type == 'Select' || $optionRow->option_type == 'Radio') {
                                $query->clear()
                                    ->select('pov.option_value_id, pov.sku, pov.quantity, pov.price, pov.price_sign, pov.price_type, pov.weight, pov.weight_sign, ovd.value')
                                    ->from('#__eshop_productoptionvalues AS pov')
                                    ->innerJoin('#__eshop_optionvalues AS ov ON (pov.option_value_id = ov.id)')
                                    ->innerJoin('#__eshop_optionvaluedetails AS ovd ON (ov.id = ovd.optionvalue_id)')
                                    ->where('pov.product_option_id = ' . intval($productOptionId))
                                    ->where('pov.id = ' . intval($optionValue))
                                    ->where('ovd.language = "' . JFactory::getLanguage()->getTag() . '"');
                                $db->setQuery($query);
                                $optionValueRow = $db->loadObject();

                                if (is_object($optionValueRow)) {
                                    //Calculate option price
                                    if ($optionValueRow->price_sign == '+') {
                                        if ($optionValueRow->price_type == 'P') {
                                            $optionPrice += $price * $optionValueRow->price / 100;
                                        } else {
                                            $optionPrice += $optionValueRow->price;
                                        }
                                    } elseif ($optionValueRow->price_sign == '-') {
                                        if ($optionValueRow->price_type == 'P') {
                                            $optionPrice -= $price * $optionValueRow->price / 100;
                                        } else {
                                            $optionPrice -= $optionValueRow->price;
                                        }
                                    }

                                    //Calculate option weight
                                    if ($optionValueRow->weight_sign == '+') {
                                        $optionWeight += $optionValueRow->weight;
                                    } elseif ($optionValueRow->weight_sign == '-') {
                                        $optionWeight -= $optionValueRow->weight;
                                    }

                                    if ($checkQty && (!$optionValueRow->quantity || $optionValueRow->quantity < $quantity)) {
                                        $stock = false;
                                    }

                                    $optionData[] = array(
                                        'product_option_id' => $productOptionId,
                                        'product_option_value_id' => $optionValue,
                                        'option_id' => $optionRow->option_id,
                                        'option_name' => $optionRow->option_name,
                                        'option_type' => $optionRow->option_type,
                                        'option_value_id' => $optionValueRow->option_value_id,
                                        'option_value' => $optionValueRow->value,
                                        'sku' => $optionValueRow->sku,
                                        'quantity' => $optionValueRow->quantity,
                                        'weight' => $optionValueRow->weight,
                                        'weight_sign' => $optionValueRow->weight_sign
                                    );
                                }
                            } elseif ($optionRow->option_type == 'Checkbox') {
                                foreach ($optionValue as $productOptionValueId) {
                                    $query->clear()
                                        ->select('pov.option_value_id, pov.sku, pov.quantity, pov.price, pov.price_sign, pov.price_type, pov.weight, pov.weight_sign, ovd.value')
                                        ->from('#__eshop_productoptionvalues AS pov')
                                        ->innerJoin('#__eshop_optionvalues AS ov ON (pov.option_value_id = ov.id)')
                                        ->innerJoin('#__eshop_optionvaluedetails AS ovd ON (ov.id = ovd.optionvalue_id)')
                                        ->where('pov.product_option_id = ' . intval($productOptionId))
                                        ->where('pov.id = ' . intval($productOptionValueId))
                                        ->where('ovd.language = "' . JFactory::getLanguage()->getTag() . '"');
                                    $db->setQuery($query);
                                    $optionValueRow = $db->loadObject();

                                    if (is_object($optionValueRow)) {
                                        //Calculate option price
                                        if ($optionValueRow->price_sign == '+') {
                                            if ($optionValueRow->price_type == 'P') {
                                                $optionPrice += $price * $optionValueRow->price / 100;
                                            } else {
                                                $optionPrice += $optionValueRow->price;
                                            }
                                        } elseif ($optionValueRow->price_sign == '-') {
                                            if ($optionValueRow->price_type == 'P') {
                                                $optionPrice -= $price * $optionValueRow->price / 100;
                                            } else {
                                                $optionPrice -= $optionValueRow->price;
                                            }
                                        }

                                        //Calculate option weight
                                        if ($optionValueRow->weight_sign == '+') {
                                            $optionWeight += $optionValueRow->weight;
                                        } elseif ($optionValueRow->weight_sign == '-') {
                                            $optionWeight -= $optionValueRow->weight;
                                        }

                                        if ($checkQty && (!$optionValueRow->quantity || $optionValueRow->quantity < $quantity)) {
                                            $stock = false;
                                        }

                                        $optionData[] = array(
                                            'product_option_id' => $productOptionId,
                                            'product_option_value_id' => $productOptionValueId,
                                            'option_id' => $optionRow->option_id,
                                            'option_name' => $optionRow->option_name,
                                            'option_type' => $optionRow->option_type,
                                            'option_value_id' => $optionValueRow->option_value_id,
                                            'option_value' => $optionValueRow->value,
                                            'sku' => $optionValueRow->sku,
                                            'quantity' => $optionValueRow->quantity,
                                            'weight' => $optionValueRow->weight,
                                            'weight_sign' => $optionValueRow->weight_sign
                                        );
                                    }
                                }
                            } elseif ($optionRow->option_type == 'Text' || $optionRow->option_type == 'Textarea') {
                                $query->clear()
                                    ->select('*')
                                    ->from('#__eshop_productoptionvalues')
                                    ->where('product_option_id = ' . intval($productOptionId))
                                    ->where('product_id = ' . intval($row->id))
                                    ->where('option_id = ' . $optionRow->option_id);
                                $db->setQuery($query);
                                $optionValueRow = $db->loadObject();

                                //Calculate option price
                                if ($optionValueRow->price_sign == '+') {
                                    if ($optionValueRow->price_type == 'P') {
                                        $optionPrice += ($price * $optionValueRow->price / 100) * strlen($optionValue);
                                    } else {
                                        $optionPrice += $optionValueRow->price * strlen($optionValue);
                                    }
                                } elseif ($optionValueRow->price_sign == '-') {
                                    if ($optionValueRow->price_type == 'P') {
                                        $optionPrice -= ($price * $optionValueRow->price / 100) * strlen($optionValue);
                                    } else {
                                        $optionPrice -= $optionValueRow->price * strlen($optionValue);
                                    }
                                }

                                $optionData[] = array(
                                    'product_option_id' => $productOptionId,
                                    'product_option_value_id' => $optionValueRow->id,
                                    'option_id' => $optionRow->option_id,
                                    'option_name' => $optionRow->option_name,
                                    'option_type' => $optionRow->option_type,
                                    'option_value_id' => $optionValueRow->option_value_id,
                                    'option_value' => $optionValue,
                                    'quantity' => $optionValueRow->quantity,
                                    'weight' => '',
                                    'weight_sign' => ''
                                );
                            } elseif ($optionRow->option_type == 'File' || $optionRow->option_type == 'Date' || $optionRow->option_type == 'Datetime') {
                                $optionData[] = array(
                                    'product_option_id' => $productOptionId,
                                    'product_option_value_id' => '',
                                    'option_id' => $optionRow->option_id,
                                    'option_name' => $optionRow->option_name,
                                    'option_type' => $optionRow->option_type,
                                    'option_value_id' => '',
                                    'option_value' => $optionValue,
                                    'quantity' => '',
                                    'weight' => '',
                                    'weight_sign' => ''
                                );
                            }
                        }
                    }

                    $optionPrice = EshopHelper::getOptionDiscountPrice($productId, $optionPrice);
                    if ($checkQty) {
                        if (!$row->product_quantity || $row->product_quantity < $quantity) {
                            $stock = false;
                        }
                    } else {
                        if (!isset($weightTotal[$row->id])) {
                            $weightTotal[$row->id] = $row->product_total_weight;
                            $requestWeight = ($row->product_weight + $optionWeight) * $quantity;
                            if ($requestWeight <= $weightTotal[$row->id]) {
                                $weightTotal[$row->id] -= $requestWeight;
                            } else {
                                $stock = false;
                            }
                        }
                    }

                    //Check discount price
                    $discountQuantity = 0;

                    foreach ($cart as $key2 => $quantity2) {
                        $product2 = explode(':', $key2);

                        if ($product2[0] == $productId) {
                            $discountQuantity += $quantity2;
                        }
                    }

                    $query->clear()
                        ->select('price')
                        ->from('#__eshop_productdiscounts')
                        ->where('product_id = ' . intval($productId))
                        ->where('customergroup_id = ' . intval($customerGroupId))
                        ->where('quantity <= ' . intval($discountQuantity))
                        ->where('(date_start = "0000-00-00" OR date_start < NOW())')
                        ->where('(date_end = "0000-00-00" OR date_end > NOW())')
                        ->where('published = 1')
                        ->order('quantity DESC, priority ASC, price ASC LIMIT 1');
                    $db->setQuery($query);

                    if ($db->loadResult()) {
                        $price = $db->loadResult();
                    }

                    // First, check if there is a special price for the product or not. Special Price has highest priority
                    $specialPrice = EshopHelper::getSpecialPrice($productId, $price);

                    if ($specialPrice >= 0) {
                        $price = $specialPrice;
                    }

                    //Prepare download data here
                    $downloadData = array();
                    $downloads = EshopHelper::getProductDownloads($productId);

                    foreach ($downloads as $download) {
                        $downloadData[] = array(
                            'id' => $download->id,
                            'download_name' => $download->download_name,
                            'filename' => $download->filename,
                            'total_downloads_allowed' => $download->total_downloads_allowed
                        );
                    }

                    $this->cartData[$key] = array(
                        'key' => $key,
                        'product_id' => $row->id,
                        'product_name' => $row->product_name,
                        'product_sku' => $row->product_sku,
                        'product_shipping' => $row->product_shipping,
                        'product_shipping_cost' => $row->product_shipping_cost,
                        'product_shipping_cost_geozones' => $row->product_shipping_cost_geozones,
                        'image' => $image,
                        'product_price' => $price,
                        'option_price' => $optionPrice,
                        'stock' => $stock,
                        'product_total_weight' => isset($row->product_total_weight) ? $row->product_total_weight : 0,
                        'price' => $price + $optionPrice,
                        'total_price' => ($price + $optionPrice) * $quantity,
                        'product_weight' => $row->product_weight,
                        'option_weight' => $optionWeight,
                        'weight' => $row->product_weight + $optionWeight,
                        'product_weight_id' => $row->product_weight_id,
                        'total_weight' => ($row->product_weight + $optionWeight) * $quantity,
                        'product_taxclass_id' => $row->product_taxclass_id,
                        'product_length' => $row->product_length,
                        'product_width' => $row->product_width,
                        'product_height' => $row->product_height,
                        'product_length_id' => $row->product_length_id,
                        'quantity' => $quantity,
                        'product_stock_checkout' => $row->product_stock_checkout,
                        'minimum_quantity' => $row->product_minimum_quantity,
                        'maximum_quantity' => $row->product_maximum_quantity,
                        'product_quantity' => $row->product_quantity,
                        'download_data' => $downloadData,
                        'option_data' => $optionData,
                        'params' => $row->params
                    );
                } else {
                    $this->remove($key);
                }
            }
        }

        return $this->cartData;
    }

    /**
     *
     * Function to add a product to the cart
     *
     * @param int $productId
     * @param int $quantity
     * @param array $options
     */
    public function add($productId, $quantity = 1, $options = array())
    {
        if (!count($options)) {
            $key = $productId;
        } else {
            $key = $productId . ':' . base64_encode(serialize($options));
        }

        if ($quantity > 0) {
            if (!isset($this->cart[$key])) {
                $this->cart[$key] = $quantity;
            } else {
                $this->cart[$key] += $quantity;
            }
        }

        $this->storeCart();
        JFactory::getSession()->set('cart', $this->cart);
        return $key;
    }

    /**
     *
     * Function to update a product in the cart
     *
     * @param string $key
     * @param int $quantity
     */
    public function update($key, $quantity)
    {
        if ($quantity > 0) {
            $this->cart[$key] = $quantity;

            $this->storeCart();
            JFactory::getSession()->set('cart', $this->cart);
        } else {
            $this->remove($key);
        }
    }

    /**
     *
     * Function to update quantities of products in the cart
     *
     * @param array $key
     * @param array $quantity
     */
    public function updates($key, $quantity)
    {
        $session = JFactory::getSession();

        for ($i = 0; $n = count($key), $i < $n; $i++) {
            if ($quantity[$i] > 0) {
                $this->cart[$key[$i]] = $quantity[$i];
                $this->storeCart();
                $session->set('cart', $this->cart);
            } else {
                $this->remove($key[$i]);
            }
        }
    }

    /**
     *
     * Function to remove a cart element based on key
     *
     * @param string $key
     */
    public function remove($key)
    {
        if (isset($this->cart[$key])) {
            unset($this->cart[$key]);
        }

        $this->storeCart();
        JFactory::getSession()->set('cart', $this->cart);
    }

    /**
     *
     * Function to clear the cart
     */
    public function clear()
    {
        $this->cart = array();
        $this->clearCart();
        JFactory::getSession()->set('cart', $this->cart);
    }

    /**
     *
     * Function to get sub total from the cart
     */
    public function getSubTotal($requireShipping = 0)
    {
        $subTotal = 0;

        foreach ($this->getCartData() as $product) {
            if (!$requireShipping || ($requireShipping && $product['product_shipping'])) {
                $subTotal += $product['total_price'];
            }
        }

        return $subTotal;
    }

    public function getSubTotal2($cartAPI, $user_id)
    {
        $subTotal = 0;
        $this->cartAPI = $cartAPI;
        $this->user_id = $user_id;

        foreach ($this->getCartData() as $product) {
            if (!$requireShipping || ($requireShipping && $product['product_shipping'])) {
                $subTotal += $product['total_price'];
            }
        }

        return $subTotal;
    }

    /**
     *
     * Function to get taxes of current cart data
     */
    public function getTaxes()
    {
        $tax = new EshopTax(EshopHelper::getConfig());
        $taxesData = array();

        foreach ($this->getCartData() as $product) {
            if ($product['product_taxclass_id']) {
                $taxRates = $tax->getTaxRates($product['price'], $product['product_taxclass_id']);

                foreach ($taxRates as $taxRate) {
                    if (!isset($taxesData[$taxRate['tax_rate_id']])) {
                        $taxesData[$taxRate['tax_rate_id']] = ($taxRate['amount'] * $product['quantity']);
                    } else {
                        $taxesData[$taxRate['tax_rate_id']] += ($taxRate['amount'] * $product['quantity']);
                    }
                }
            }
        }

        return $taxesData;
    }

    /**
     *
     * Function to get total weight of the products in the cart
     * @return float
     */
    public function getWeight()
    {
        $eshopWeight = new EshopWeight();
        $weight = 0;
        $weightId = EshopHelper::getConfigValue('weight_id');

        foreach ($this->getCartData() as $product) {
            if ($product['product_shipping']) {
                $weight += $eshopWeight->convert($product['total_weight'], $product['product_weight_id'], $weightId);
            }
        }

        return $weight;
    }

    /**
     *
     * Function to get Total
     */
    public function getTotal()
    {
        $total = 0;
        $tax = new EshopTax(EshopHelper::getConfig());
        $enableTax = EshopHelper::getConfigValue('tax');

        foreach ($this->getCartData() as $product) {
            $total += $tax->calculate($product['total_price'], $product['product_taxclass_id'], $enableTax);
        }

        return $total;
    }

    /**
     *
     * Function to count products in the cart
     * @return int
     */
    public function countProducts($requireShipping = false)
    {
        $countProducts = 0;

        foreach ($this->getCartData() as $product) {
            if (!$requireShipping || ($requireShipping && $product['product_shipping'])) {
                $countProducts += $product['quantity'];
            }
        }

        return $countProducts;
    }

    /**
     *
     * Function to check if the cart has products or not
     */
    public function hasProducts()
    {
        return count($this->cart);
    }

    /**
     *
     * Function to get stock warning
     * @return string
     */
    public function getStockWarning()
    {
        $warning = '';

        if (EshopHelper::getConfigValue('stock_warning')) {
            $stock = true;

            foreach ($this->getCartData() as $product) {
                if (!$product['stock']) {
                    $stock = false;
                    break;
                }
            }

            if (!$stock) {
                $warning = JText::_('ESHOP_CART_STOCK_WARNING');
            }
        }

        return $warning;
    }

    public function validateStockStatus()
    {
        $warning = array();
        foreach ($this->getCartData() as $product) {
            if (!$product['stock']) {
                $warning[$product['key']] = array(
                    'message' => "Sản phẩm {$product['product_name']} không đủ đáp ứng yêu cầu cầu của ban.",
                    'in_stock' => $product['product_quantity'],
                    'total_weigth' => $product['product_total_weight']
                );
            }
        }

        return $warning;
    }

    /**
     *
     * Function to check if shopper can go to checkout or not based on stock
     */
    public function canCheckout()
    {
        $canCheckout = true;

        if (!EshopHelper::getConfigValue('stock_checkout')) {
            foreach ($this->getCartData() as $product) {
                if (!$product['stock'] && !$product['product_stock_checkout']) {
                    $canCheckout = false;
                    break;
                }
            }
        }

        return $canCheckout;
    }

    /**
     *
     * Function to get minimum sub total warning
     * @return string
     */
    public function getMinSubTotalWarning()
    {
        $currency = new EshopCurrency();
        $warning = '';

        if (EshopHelper::getConfigValue('min_sub_total') > 0) {
            if ($this->getSubTotal() < EshopHelper::getConfigValue('min_sub_total')) {
                $warning = sprintf(JText::_('ESHOP_MIN_SUB_TOTAL_NOT_REACH'), $currency->format(EshopHelper::getConfigValue('min_sub_total')));
            }
        }

        return $warning;
    }

    /**
     *
     * Function to get minimum quantity warning
     * @return string
     */
    public function getMinQuantityWarning()
    {
        $warning = '';

        if (EshopHelper::getConfigValue('min_quantity') > 0) {
            if ($this->countProducts() < EshopHelper::getConfigValue('min_quantity')) {
                $warning = sprintf(JText::_('ESHOP_MIN_QUANTITY_NOT_REACH'), EshopHelper::getConfigValue('min_quantity'));
            }
        }

        return $warning;
    }

    /**
     *
     * Function to get minimum product quantity warning
     * @return string
     */
    public function getMinProductQuantityWarning()
    {
        $warning = '';

        foreach ($this->getCartData() as $product) {
            if ($product['minimum_quantity'] > 0 && $product['quantity'] < $product['minimum_quantity']) {
                $warning = sprintf(JText::_('ESHOP_MIN_PRODUCT_QUANTITY_NOT_REACH'), $product['product_name'], $product['minimum_quantity']);
                break;
            }
        }

        return $warning;
    }

    /**
     *
     * Function to get maximum product quantity warning
     * @return string
     */
    public function getMaxProductQuantityWarning()
    {
        $warning = '';

        foreach ($this->getCartData() as $product) {
            if ($product['maximum_quantity'] > 0 && $product['quantity'] > $product['maximum_quantity']) {
                $warning = sprintf(JText::_('ESHOP_MAX_PRODUCT_QUANTITY_EXCEED'), $product['product_name'], $product['maximum_quantity']);
                break;
            }
        }

        return $warning;
    }

    /**
     *
     * Function to check if products in the cart has shipping or not
     */
    public function hasShipping()
    {
        if (!EshopHelper::getConfigValue('require_shipping', '1')) {
            return false;
        }

        $shipping = false;

        foreach ($this->getCartData() as $product) {
            if ($product['product_shipping']) {
                $shipping = true;
                break;
            }
        }

        return $shipping;
    }

    /**
     *
     * Function to check if product in the cart has download or not
     * @return boolean
     */
    public function hasDownload()
    {
        $download = false;

        foreach ($this->getCartData() as $product) {
            if ($product['download_data']) {
                $download = true;
                break;
            }
        }

        return $download;
    }

    /**
     *
     * Function to store cart into database
     */
    public function storeCart()
    {
        if (EshopHelper::getConfigValue('store_cart', 0)) {
            JTable::addIncludePath(JPATH_ROOT . '/administrator/components/com_eshop/tables');
            $user = JFactory::getUser();
            $row = JTable::getInstance('Eshop', 'Cart');
            $row->id = $this->cart_id;
            $row->customer_id = $user->get('id');
            $row->cart_data = json_encode($this->cart);
            $row->created_date = JFactory::getDate()->toSql();
            $row->modified_date = JFactory::getDate()->toSql();
            $row->store();
        }
    }

    /**
     *
     * Function to clear cart of customer from the database
     */
    public function clearCart()
    {
        $user = JFactory::getUser();

        if ($user->get('id') > 0) {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->delete('#__eshop_carts')
                ->where('customer_id = ' . intval($user->get('id')));
            $db->setQuery($query);
            $db->execute();
        }
    }
}
