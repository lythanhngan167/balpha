<?php
/**
 * @version        2.5.4
 * @package        Joomla
 * @subpackage    EShop
 * @author    Giang Dinh Truong
 * @copyright    Copyright (C) 2012 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */

// no direct access
use api\model\dao\shop\ShopOrderDao;
use api\model\libs\CommonGateway;

defined('_JEXEC') or die();

class os_onepay extends os_payment
{
    /**
     * Constructor functions, init some parameter
     *
     * @param object $params
     */
    public function __construct($params)
    {
        $config = array(
            'type' => 0,
            'show_card_type' => false,
            'show_card_holder_name' => false,
            'bank_list' => true
        );

        parent::__construct($params, $config);
    }

    /**
     * Process payment
     *
     */
    public function processPayment($data)
    {
        $method = 'OnepayDomestic';
        switch ($data['sub_method']) {
            case 'domestic':
                $method = 'OnepayDomestic';
                break;

            default:
                $method = 'OnepayInternational';
                break;
        }
        $gateWay = CommonGateway::getGateway($method);
        $dao = new ShopOrderDao();
        $orderInfo = $dao->get(array('select' => '*', 'no_quote' => true, 'where' => array('id = ' . (int)$data['order_id'])));
        if ($orderInfo) {
            $gateWay->setParams(array(
                'order_id' => $orderInfo['id'],
                'order_no' => $orderInfo['order_number'],
                'order_amount' => $orderInfo['total'],
                'return_url' => JUri::root() . 'index.php?option=com_eshop&task=payment.paymentReturn&gateway=' . $method . '&pl=web'
            ));
        }

        $url = $gateWay->purchase();
        JFactory::getApplication()->redirect($url);



    }

    public function processPaymentAPI($data)
    {
        $row = JTable::getInstance('Eshop', 'Order');
        $id = $data['order_id'];
        $row->load($id);
        EshopHelper::completeOrder($row);
        //Send confirmation email here
        if (EshopHelper::getConfigValue('order_alert_mail')) {
            EshopHelper::sendEmails($row);
        }

    }
}
