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
JLoader::discover('API', JPATH_COMPONENT . '/libraries/exceptions');
JLoader::registerNamespace('api', JPATH_PLUGINS . '/api', false, false, 'psr4');

use api\model\dao\shop\ShopOrderDao;
use api\model\libs\CommonGateway;

/**
 * EShop controller
 *
 * @package        Joomla
 * @subpackage     EShop
 * @since          1.5
 */
class EShopControllerPayment extends JControllerLegacy
{
    /**
     * Constructor function
     *
     * @param array $config
     */
    public function __construct($config = array())
    {
        parent::__construct($config);
    }

    public function makePayment()
    {
        $gateWay = CommonGateway::getGateway('OnepayDomestic');
        $dao = new ShopOrderDao();
        $orderInfo = $dao->get(array('select' => '*', 'no_quote' => true, 'where' => array('id = 6')));
        if ($orderInfo) {
            $gateWay->setParams(array(
                'order_id' => $orderInfo['id'],
                'order_no' => $orderInfo['order_number'],
                'order_amount' => $orderInfo['total'],
                'return_url' => 'http://minhcaumart.vn/index.php?option=com_eshop&task=payment.paymentReturn&gateway=OnepayDomestic'
            ));
        }


        $url = $gateWay->purchase();
        echo json_encode(array('url' => $url));
        die;
    }

    public function paymentReturn()
    {
        $layout = 'failure';
        $data = $_REQUEST;
        $session = JFactory::getSession();
        $gateWay = CommonGateway::getGateway($data['gateway']);
        if ($gateWay->validatePayment($data)) {
            $this->_trackPaymentInfo();
            if ($data["vpc_TxnResponseCode"] == '0') {
                $this->_updateOrder();
                JTable::addIncludePath(JPATH_ROOT . '/administrator/components/com_eshop/tables');
                $row = JTable::getInstance('Eshop', 'Order');
                $order_no = $_REQUEST['vpc_MerchTxnRef'];
                $temp = explode('_', $order_no);
                $id = $temp[0];
                $row->load($id);
                EshopHelper::completeOrder($row);
                //Send confirmation email here
                if (EshopHelper::getConfigValue('order_alert_mail')) {
                    EshopHelper::sendEmails($row);
                }
                $layout = 'complete';

            } else {
                $session->set('omnipay_payment_error_reason', \api\model\SUtil::getPaymentError($data["vpc_TxnResponseCode"], '[result]'));
            }
        } else {
            //echo $gateWay->getError();

            $session->set('omnipay_payment_error_reason', $gateWay->getError());
        }
        if (@$data['pl'] == 'web') {
            JFactory::getApplication()->redirect(JRoute::_(EshopRoute::getViewRoute('checkout') . '&layout=' . $layout));
        }

        die();
    }

    public function ipn()
    {
        $data = $_REQUEST;
        $gateWay = CommonGateway::getGateway($data['gateway']);
        if ($gateWay->validatePayment($data)) {
            $this->_trackPaymentInfo();
            if ($data["vpc_TxnResponseCode"] == '0') {
                $this->_updateOrder();
                echo 'Giao dich thanh cong';
            }
            echo "responsecode=1&desc=confirm-success";
        } else {
            echo $gateWay->getError();
            echo "responsecode=0&desc=confirm-fail";
        }
        die();

    }

    private function _updateOrder()
    {
        $order_no = $_REQUEST['vpc_MerchTxnRef'];
        if ($order_no) {
            $temp = explode('_', $order_no);
            $order_no = $temp[0];
            CommonGateway::updateOrder(array(
                'set' => array(
                    'payment_status = 1',
                ),
                'where' => array(
                    'id = ' . $order_no,
                    'payment_status = 0'
                )
            ));
        }
    }

    private function _trackPaymentInfo()
    {
        $order_no = $_REQUEST['vpc_MerchTxnRef'];
        if ($order_no) {
            $temp = explode('_', $order_no);
            $order_no = $temp[0];
            $dao = new ShopOrderDao();
            $orderInfo = $dao->get(array('select' => '*', 'no_quote' => true, 'where' => array('id = ' . (int)$order_no)));
            if ($orderInfo && $orderInfo['payment_status'] == 0) {
                $uri = Joomla\CMS\Uri\Uri::getInstance();
                switch ($_REQUEST['gateway']) {
                    case 'OnepayDomestic':
                    case 'OnepayInternational':
                        CommonGateway::updateOrder(array(
                            'set' => array(
                                'payment_response = ' . $dao->db->quote($uri->toString()),
                                'payment_response_date = ' . $dao->db->quote(\JFactory::getDate()->toSql()),
                                'payment_code = ' . $dao->db->quote($_REQUEST['vpc_TxnResponseCode']),
                                'transaction_no = ' . $dao->db->quote(@$_REQUEST['vpc_TransactionNo']),
                            ),
                            'where' => array(
                                'id = ' . $order_no
                            )
                        ));
                        break;
                }

            }

        }


    }

}