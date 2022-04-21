<?php
/**
 * @version		3.1.0
 * @package		Joomla
 * @subpackage	EShop
 * @author  	Giang Dinh Truong
 * @copyright	Copyright (C) 2012 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die();

/**
 * EShop controller
 *
 * @package		Joomla
 * @subpackage	EShop
 * @since 1.5
 */
class EShopControllerProducts extends JControllerLegacy
{

	/**
	 * Constructor function
	 *
	 * @param array $config
	 */
	function __construct($config = array())
	{
		parent::__construct($config);

	}



	/**
	 *
	 * Function to process batch products
	 */
	function batch()
	{
		$input = new RADInput();
		$post  = $input->post->getData(RAD_INPUT_ALLOWRAW);
		$model = $this->getModel('products');
		$ret = $model->batch($post);
		if ($ret)
		{
			$msg = JText::_('ESHOP_BATCH_PRODUCT_SUCCESSFULLY');
		}
		else
		{
			$msg = JText::_('ESHOP_BATCH_PRODUCT_ERROR');
		}
		$this->setRedirect('index.php?option=com_eshop&view=products', $msg);
	}

	/**
	 *
	 * Cancel function
	 */
	function cancel()
	{
		$this->setRedirect('index.php?option=com_eshop&view=products');
	}

	/**
	 *
	 * Cancel function
	 */
	function updatePrice()
	{
		$db = JFactory::getDbo();
		$product_id = $_REQUEST['product_id'];
		$price = $_REQUEST['price'];

		$query = $db->getQuery(true);
		// Fields to update.
		$fields = array(
		    $db->quoteName('product_price') . ' = '.$price
		);
		// Conditions for which records should be updated.
		$conditions = array(
		    $db->quoteName('id') . ' = '.$product_id
		);
		$query->update($db->quoteName('#__eshop_products'))->set($fields)->where($conditions);
		$db->setQuery($query);
		$result = $db->execute();
		if($result){
			$price = number_format($price,0,".",".");
			$data['price'] = $price;
			$data['error'] = 0;
			echo json_encode($data);
		}else{
			$data['error'] = 1;
			echo json_encode($data);
		}


		exit();
	}

	function updateManufacturer(){
		$db = JFactory::getDbo();
		$product_id = $_REQUEST['product_id'];
		$manufacturer_id = $_REQUEST['manufacturer_id'];

		$query = $db->getQuery(true);
		// Fields to update.
		$fields = array(
		    $db->quoteName('manufacturer_id') . ' = '.$manufacturer_id
		);
		// Conditions for which records should be updated.
		$conditions = array(
		    $db->quoteName('id') . ' = '.$product_id
		);

		$query->update($db->quoteName('#__eshop_products'))->set($fields)->where($conditions);
		$db->setQuery($query);
		$result = $db->execute();
		if($result){
			$data['error'] = 0;
			echo json_encode($data);
		}else{
			$data['error'] = 1;
			echo json_encode($data);
		}
		exit();
	}

	function updateStatus(){
		$db = JFactory::getDbo();
		$product_id = $_REQUEST['product_id'];
		$status_id = $_REQUEST['status_id'];
		if($status_id == 1){
			$published = 0;
		}else{
			$published = 1;
		}
		$query = $db->getQuery(true);
		// Fields to update.
		$fields = array(
		    $db->quoteName('published') . ' = '.$published
		);
		// Conditions for which records should be updated.
		$conditions = array(
		    $db->quoteName('id') . ' = '.$product_id
		);

		$query->update($db->quoteName('#__eshop_products'))->set($fields)->where($conditions);
		$db->setQuery($query);
		$result = $db->execute();


		if($result){
			$data['status'] = "".$published."";
			$data['error'] = '0';
			echo json_encode($data);
		}else{
			$data['error'] = '1';
			echo json_encode($data);
		}
		exit();
	}

}
