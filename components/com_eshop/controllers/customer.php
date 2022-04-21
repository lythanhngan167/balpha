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
defined('_JEXEC') or die;

/**
 * EShop controller
 *
 * @package        Joomla
 * @subpackage     EShop
 * @since          1.5
 */
class EShopControllerCustomer extends JControllerLegacy
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

	/**
	 *
	 * Function to download invoice
	 */
	public function downloadInvoice()
	{
		$orderId     = $this->input->getInt('order_id');
		$user        = JFactory::getUser();
		$canDownload = false;

		if ($user->get('id'))
		{
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('COUNT(*)')
				->from('#__eshop_orders')
				->where('id = ' . intval($orderId))
				->where('customer_id = ' . intval($user->get('id')))
				->where('((order_status_id = ' . intval(EshopHelper::getConfigValue('complete_status_id')) . ') OR (order_status_id = ' . intval(EshopHelper::getConfigValue('order_status_id')) . ' AND payment_method LIKE "os_offline%"))');
			$db->setQuery($query);

			if ($db->loadResult())
			{
				$canDownload = true;
			}
		}

		if (!$canDownload)
		{
			$app = JFactory::getApplication();
			$app->enqueueMessage(JText::_('ESHOP_DOWNLOAD_INVOICE_NOT_AVAILABLE'), 'Error');
			$app->redirect(EshopRoute::getViewRoute('customer') . '&layout=orders');
		}
		else
		{
			EshopHelper::downloadInvoice(array($orderId));
		}
	}

	/**
	 *
	 * Function to download file
	 */
	public function downloadFile()
	{
		$orderId      = $this->input->getInt('order_id');
		$downloadCode = $this->input->getString('download_code');
		$db           = JFactory::getDbo();
		$query        = $db->getQuery(true);
		$query->select('a.*')
			->from('#__eshop_orderdownloads AS a')
			->innerJoin('#__eshop_orders AS b ON (a.order_id = b.id)')
			->where('a.order_id = ' . (int) $orderId)
			->where('a.download_code = ' . $db->quote($downloadCode));
		$db->setQuery($query);
		$row = $db->loadObject();

		$canDownload = false;
		$fileName    = '';

		if ($row)
		{
			$query->clear()
				->select('total_downloads_allowed')
				->from('#__eshop_downloads')
				->where('id = ' . intval($row->download_id));
			$db->setQuery($query);
			$totalDownloadsAllowed = $db->loadResult();
			if ($totalDownloadsAllowed > 0)
			{
				if ($row->remaining)
				{
					$fileName = $row->filename;

					//Update remaining
					$query->clear()
						->update('#__eshop_orderdownloads')
						->set('remaining = remaining - 1')
						->where('id = ' . $row->id);
					$db->setQuery($query);
					$db->execute();

					$canDownload = true;
				}
				else
				{
					$message = JText::_('ESHOP_TOTAL_DOWNLOAD_ALLOWED_REACH');
				}
			}
			else
			{
				$canDownload = true;
			}
		}
		else
		{
			$message = JText::_('ESHOP_DO_NOT_HAVE_DOWNLOAD_PERMISSION');
		}

		if ($canDownload)
		{
			while (@ob_end_clean()) ;
			$filePath = JPATH_ROOT . '/media/com_eshop/downloads/' . $fileName;
			EshopHelper::processDownload($filePath, $fileName, true);
		}
		else
		{
			$application = JFactory::getApplication();
			$application->enqueueMessage($message, 'notice');
			$application->redirect('index.php');
		}
	}

	/**
	 * Function to process payment method
	 */
	public function processUser()
	{
		$post = $this->input->post->getArray();

		/* @var EShopModelCustomer $model */
		$model = $this->getModel('Customer');
		$json  = $model->processUser($post);
		echo json_encode($json);

		JFactory::getApplication()->close();
	}

	/**
	 *
	 * Function to process (add/update) address
	 */
	public function processAddress()
	{
		$session = JFactory::getSession();

		$post = $this->input->post->getArray();

		/* @var EShopModelCustomer $model */
		$model = $this->getModel('Customer');
		$json  = $model->processAddress($post);

		if ($session->get('shipping_address_id') && $session->get('shipping_address_id') == $post['id'])
		{
			$session->set('shipping_country_id', $post['country_id']);
			$session->set('shipping_zone_id', $post['zone_id']);
			$session->set('shipping_postcode', $post['postcode']);

			$session->clear('shipping_method');
			$session->clear('shipping_methods');
		}
		if ($session->get('payment_address_id') && $session->get('payment_address_id') == $post['id'])
		{
			$session->set('payment_country_id', $post['country_id']);
			$session->set('payment_zone_id', $post['zone_id']);

			$session->clear('payment_method');
		}

		echo json_encode($json);

		JFactory::getApplication()->close();
	}

	/**
	 *
	 * Function to delete address
	 */
	public function deleteAddress()
	{
		$id = $this->input->getInt('aid', 0);

		/* @var EShopModelCustomer $model */
		$model = $this->getModel('Customer');
		$json  = $model->deleteAddress($id);
		echo json_encode($json);

		JFactory::getApplication()->close();
	}

    public function cancelOrder(){
        $app = JFactory::getApplication();
        $orderId     = $app->input->getInt('orderId');
        $user        = JFactory::getUser();
        $order  = EshopHelper::getOrder($orderId);
        $json = array();
        if ($user->get('id') && $order->order_status_id == 8 && $order->payment_status == 0){
            $db= JFactory::getDbo();
            if($orderId){
                $set[] = 'order_status_id = 1';
                $sql = "UPDATE #__eshop_orders SET " . implode(', ', $set) . ' WHERE id = '. intval($orderId);
                $db->setQuery($sql)->execute();
                $json['success'] = 'Huỷ đơn hàng thành công!';
            }
        }else{
            $json['error'] = 'Bạn không có quyền thực hiện thao tác này!';
        }
        echo json_encode($json);
        $app->close();
    }
}
