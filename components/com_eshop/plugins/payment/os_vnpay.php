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
use api\model\Sconfig;

defined('_JEXEC') or die();

class os_vnpay extends os_payment
{
    private $method = 'VNPay';
    private $method_name = 'os_vnpay';

    /**
     * Constructor functions, init some parameter
     *
     * @param object $params
     */
    public function __construct($params)
    {
        $config = array();

        parent::__construct($params, $config);
    }

    /**
     * Process payment
     *
     */
    public function processPayment($data, $redirect = true, $flag = null)
    {
        $orderId = is_array($data) ? $data['order_id'] : $data->id;
        $gateWay = CommonGateway::getGateway($this->method, $this->params);
        $dao = new ShopOrderDao();
        $orderInfo = $dao->get(array('select' => '*', 'no_quote' => true, 'where' => array('id = ' . (int)$orderId)));
        $reuturnUrl = JUri::root() . 'index.php?option=com_eshop&task=checkout.verifyPayment&payment_method=' . $this->method_name;
        if ($redirect) {
            $reuturnUrl .= '&pl=web';
        }
        if ($orderInfo) {
            $gateWay->setParams(array(
                'order_id' => $orderInfo['id'],
                'order_no' => $orderInfo['order_number'],
                'order_amount' => $orderInfo['total'],
                'return_url' => $reuturnUrl
            ));
        }

        $url = $gateWay->purchase();
        if ($redirect) {
            JFactory::getApplication()->redirect($url);
        } else {
            return $url;
        }


    }

    public function verifyPayment()
    {
        $this->_trackResponeInfo();
        $gateWay = CommonGateway::getGateway($this->method, $this->params);
        $data = $_REQUEST;
        $result = $gateWay->validatePayment($data);
        $session = JFactory::getSession();
        if ($result) {
            JTable::addIncludePath(JPATH_ROOT . '/administrator/components/com_eshop/tables');
            $row = JTable::getInstance('Eshop', 'Order');
            $order_no = $data['vnp_TxnRef'];
            $temp = explode('_', $order_no);
            $id = $temp[0];
            $row->load($id);
            if ($row->id) {
                if ($row->total * 100 != $data['vnp_Amount']) {
                    $session->set('omnipay_payment_error_reason', 'Số tiền thanh toán không hợp lệ.');
                    $session->set('payment_data', $data);
                    if (@$data['pl'] == 'web') {
                        JFactory::getApplication()->redirect(JRoute::_(EshopRoute::getViewRoute('checkout') . '&layout=failure'));
                    }
                } else {
                    if (!empty($row->payment_code)) {
                        $session->set('omnipay_payment_error_reason', 'Đơn hàng đã được cập nhật.');
                        $session->set('payment_data', $data);
                        if (@$data['pl'] == 'web') {
                            JFactory::getApplication()->redirect(JRoute::_(EshopRoute::getViewRoute('checkout') . '&layout=failure'));
                        }
                    } else {

                        if ($gateWay->isSuccessPayment()) {
                            //$this->_updateOrder();

                            if (@$data['pl'] == 'web') {
                                JFactory::getApplication()->redirect(JRoute::_(EshopRoute::getViewRoute('checkout') . '&layout=complete'));
                            }
                        } else {

                            if (@$data['pl'] == 'web') {
                                $session->set('omnipay_payment_error_reason', $gateWay->getError());
                                $session->set('payment_data', $data);
                                JFactory::getApplication()->redirect(JRoute::_(EshopRoute::getViewRoute('checkout') . '&layout=failure'));
                            }
                        }
                    }


                }
            } else {
                $session->set('omnipay_payment_error_reason', 'Đơn  hàng không tồn tại.');
                $session->set('payment_data', $data);
                if (@$data['pl'] == 'web') {
                    JFactory::getApplication()->redirect(JRoute::_(EshopRoute::getViewRoute('checkout') . '&layout=failure'));
                }
            }


        } else {
            if (@$data['pl'] == 'web') {
                $session->set('omnipay_payment_error_reason', $gateWay->getError());
                $session->set('payment_data', $data);
                JFactory::getApplication()->redirect(JRoute::_(EshopRoute::getViewRoute('checkout') . '&layout=failure'));
            }
        }
        die();

    }

    public function ipnPayment()
    {
        $uri = Joomla\CMS\Uri\Uri::getInstance();
        try {
            $this->_trackPaymentInfo();
            $gateWay = CommonGateway::getGateway($this->method, $this->params);
            $data = $_REQUEST;
            $result = $gateWay->validatePayment($data);


            if ($result) {
                JTable::addIncludePath(JPATH_ROOT . '/administrator/components/com_eshop/tables');
                $row = JTable::getInstance('Eshop', 'Order');
                $order_no = $_REQUEST['vnp_TxnRef'];
                $temp = explode('_', $order_no);
                $id = $temp[0];
                $row->load($id);
                if ($row->id) {
                    if ($row->total * 100 != $data['vnp_Amount']) {
                        $vnpReturn = array(
                            'RspCode' => '04',
                            'Message' => 'Invalid amount'
                        );
                    } else {
                        if (!empty($row->payment_code)) {
                            $vnpReturn = array(
                                'RspCode' => '02',
                                'Message' => 'Order already confirmed'
                            );
                        } else {
                            $db = JFactory::getDbo();
                            CommonGateway::updateOrder(array(
                                'set' => array(
                                    'payment_code = ' . $db->quote($_REQUEST['vnp_ResponseCode']),
                                    'transaction_no = ' . $db->quote(@$_REQUEST['vnp_TransactionNo'])
                                ),
                                'where' => array(
                                    'id = ' . $id
                                )
                            ));

                            $vnpReturn = array(
                                'RspCode' => '00',
                                'Message' => 'Confirm Success'
                            );

                            if ($gateWay->isSuccessPayment()) {
                                $this->_updateOrder();
                                EshopHelper::trackHistory(array(
                                    'id' => $id,
                                    'message' => 'Thanh toán thành công.',
                                    'content' => $uri->toString()
                                ));
                                EshopHelper::completeOrder($row);
                                //Send confirmation email here
                                if (EshopHelper::getConfigValue('order_alert_mail')) {
                                    EshopHelper::sendEmails($row);
                                }

                            } else {
                                EshopHelper::trackHistory(array(
                                    'id' => $id,
                                    'message' => 'Thanh toán thất bại.',
                                    'content' => $uri->toString()
                                ));

                                $config = new Sconfig();
                                $db = JFactory::getDbo();
                                // Cancel order
                                $sql = 'UPDATE #__eshop_orders SET order_status_id = ' . (int)$config->orderCancleStatus . ' WHERE id =' . (int)$row->id;
                                $db->setQuery($sql)->execute();

                                EshopHelper::trackHistory(array(
                                    'id' => $id,
                                    'order_status_id' => $config->orderCancleStatus
                                ));
                            }
                        }


                    }

                } else {
                    $vnpReturn = array(
                        'RspCode' => '01',
                        'Message' => 'Order not found'
                    );
                }

            } else {
                $vnpReturn = array(
                    'RspCode' => '97',
                    'Message' => 'Invalid signature'
                );
            }
        } catch (Exception $e) {
            $vnpReturn = array(
                'RspCode' => '99',
                'Message' => ''
            );
        }
        echo json_encode($vnpReturn);
        die();

    }

    private function _updateOrder()
    {
        $db = JFactory::getDbo();
        $order_no = $_REQUEST['vnp_TxnRef'];
        if ($order_no) {
            $temp = explode('_', $order_no);
            $order_no = $temp[0];
            CommonGateway::updateOrder(array(
                'set' => array(
                    'payment_status = 1',
                    'payment_code = ' . $db->quote($_REQUEST['vnp_ResponseCode']),
                ),
                'where' => array(
                    'id = ' . $order_no,
                    'payment_status = 0'
                )
            ));
        }
    }

    private function _trackResponeInfo()
    {
        $order_no = $_REQUEST['vnp_TxnRef'];
        if ($order_no) {
            $temp = explode('_', $order_no);
            $order_no = $temp[0];
            $dao = new ShopOrderDao();
            $orderInfo = $dao->get(array('select' => '*', 'no_quote' => true, 'where' => array('id = ' . (int)$order_no)));
            if ($orderInfo && $orderInfo['payment_status'] == 0) {
                $uri = Joomla\CMS\Uri\Uri::getInstance();
                CommonGateway::updateOrder(array(
                    'set' => array(
                        'payment_response = ' . $dao->db->quote($uri->toString()),
                        'payment_response_date = ' . $dao->db->quote(\JFactory::getDate()->toSql()),
                    ),
                    'where' => array(
                        'id = ' . $order_no
                    )
                ));

            }

        }


    }


    private function _trackPaymentInfo()
    {
        $order_no = $_REQUEST['vnp_TxnRef'];
        if ($order_no) {
            $temp = explode('_', $order_no);
            $order_no = $temp[0];
            $dao = new ShopOrderDao();
            $orderInfo = $dao->get(array('select' => '*', 'no_quote' => true, 'where' => array('id = ' . (int)$order_no)));
            if ($orderInfo && $orderInfo['payment_status'] == 0) {
                $uri = Joomla\CMS\Uri\Uri::getInstance();
                CommonGateway::updateOrder(array(
                    'set' => array(
                        'payment_response = ' . $dao->db->quote($uri->toString()),
                        'payment_response_date = ' . $dao->db->quote(\JFactory::getDate()->toSql()),
                        'transaction_no = ' . $dao->db->quote(@$_REQUEST['vnp_BankTranNo']),

                    ),
                    'where' => array(
                        'id = ' . $order_no
                    )
                ));

            }

        }


    }

}
