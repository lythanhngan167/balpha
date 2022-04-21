<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Order
 * @author     tung hoang <tungvacc@gmail.com>
 * @copyright  2017 tung hoang
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

use Joomla\Utilities\ArrayHelper;

/**
 * Orders list controller class.
 *
 * @since  1.6
 */
class OrderControllerOrders extends JControllerAdmin
{
    /**
     * Method to clone existing Orders
     *
     * @return void
     */
    public function duplicate()
    {
        // Check for request forgeries
        Jsession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        // Get id(s)
        $pks = $this->input->post->get('cid', array(), 'array');

        try {
            if (empty($pks)) {
                throw new Exception(JText::_('COM_ORDER_NO_ELEMENT_SELECTED'));
            }

            ArrayHelper::toInteger($pks);
            $model = $this->getModel();
            $model->duplicate($pks);
            $this->setMessage(Jtext::_('COM_ORDER_ITEMS_SUCCESS_DUPLICATED'));
        } catch (Exception $e) {
            JFactory::getApplication()->enqueueMessage($e->getMessage(), 'warning');
        }

        $this->setRedirect('index.php?option=com_order&view=orders');
    }

    /**
     * Proxy for getModel.
     *
     * @param string $name Optional. Model name
     * @param string $prefix Optional. Class prefix
     * @param array $config Optional. Configuration array for model
     *
     * @return  object    The Model
     *
     * @since    1.6
     */
    public function getModel($name = 'order', $prefix = 'OrderModel', $config = array())
    {
        $model = parent::getModel($name, $prefix, array('ignore_request' => true));

        return $model;
    }

    /**
     * Method to save the submitted ordering values for records via AJAX.
     *
     * @return  void
     *
     * @since   3.0
     */
    public function saveOrderAjax()
    {
        // Get the input
        $input = JFactory::getApplication()->input;
        $pks = $input->post->get('cid', array(), 'array');
        $order = $input->post->get('order', array(), 'array');

        // Sanitize the input
        ArrayHelper::toInteger($pks);
        ArrayHelper::toInteger($order);

        // Get the model
        $model = $this->getModel();

        // Save the ordering
        $return = $model->saveorder($pks, $order);

        if ($return) {
            echo "1";
        }

        // Close the application
        JFactory::getApplication()->close();
    }

    public function payback()
    {
        $user = JFactory::getUser();
        $input = JFactory::getApplication()->input;
        $cids = $input->post->get('cid', array(), 'array');
        $sale_id = $input->post->get('sale_id', 0);
        $db = JFactory::getDbo();
        if ($sale_id) {
            $sql = 'SELECT c.id, c.name,
 
            ( select concat_ws(":", id, price) from #__orders where (list_customer = c.id OR list_customer like concat(\'%,\', c.id, \',%\') OR list_customer like concat(\'%,\', c.id) OR list_customer like concat(id, \',%\') ) and created_by = c.sale_id limit 0, 1) as price
            FROM #__customers as c WHERE c.sale_id = ' . (int)$sale_id . ' AND c.category_id = 150 AND c.payback = 0 AND c.id IN (' . implode(',', $cids) . ')';
            $ids = $db->setQuery($sql)->loadAssocList();
            if (count($cids) != count($ids)) {
                echo json_encode(array('error' => true, 'message' => 'Yêu cầu không hợp lệ.'));
                die();
            }


            foreach ($ids as $item) {
                $tmp = explode(':', $item['price']);
                $item['price'] = @$tmp[1];
                $item['order_id'] = @$tmp[0];

                try {
                    $db->transactionStart();
                    $sql = 'UPDATE #__customers SET payback= 1, sale_id = 0, status_id = 1 WHERE sale_id = ' . (int)$sale_id . ' AND category_id = 150 AND payback = 0 AND id =' . $item['id'];
                    $db->setQuery($sql)->execute();
                    if ($item['price'] > 0 && $item['order_id']) {
                        // Insert
                        $totalPrice = $item['price'];
                        // Add history
                        $obj = new stdClass();
                        $obj->state = 1;
                        $obj->created_by = $sale_id;
                        $obj->title = 'Hoàn tiền khách hàng ' . $item['name'];
                        $obj->amount = $totalPrice;
                        $obj->created_date = date('Y-m-d H:i:s');
                        $obj->type_transaction = 'payback';
                        $obj->status = 'completed';
                        $obj->reference_id = $item['order_id'];
                        $obj->customer_id = $item['id'];
                        $db = JFactory::getDbo();
                        $db->insertObject('#__transaction_history', $obj, 'id');

                        // Increase money
                        $sql = "UPDATE #__users set money = money + " . $totalPrice . ' WHERE id = ' . $sale_id;
                        $db->setQuery($sql)->execute();
                    }
                    $db->transactionCommit();
                } catch (Exception $e) {
                    // catch any database errors.
                    $db->transactionRollback();
                    echo json_encode(array('error' => true, 'message' => 'Xảy ra lỗi.'));
                    die();
                }
            }

            echo json_encode(array('error' => false, 'message' => 'Hoàn tiền thành công.'));
            die();

        } else {
            echo json_encode(array('error' => true, 'message' => 'Yêu cầu không hợp lệ.'));
            die();
        }
    }
}
