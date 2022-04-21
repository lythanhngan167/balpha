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
 * EShop Component Configuration Model
 *
 * @package		Joomla
 * @subpackage	EShop
 * @since 1.5
 */
class EShopModelConfiguration extends JModelLegacy
{

	/**
	 * Containing all config data,  store in an object with key, value
	 *
	 * @var object
	 */
	var $_data = null;

	function __construct()
	{
		parent::__construct();
	}

	/**
	 * Get configuration data
	 * @return object
	 */
	function getData()
	{
		if (empty($this->_data))
		{
			$config = new stdClass();
			$db = $this->getDbo();
			$query = $db->getQuery(true);
			$query->select('config_key, config_value')
				->from('#__eshop_configs');
			$db->setQuery($query);
			$rows = $db->loadObjectList();
			if (count($rows))
			{
				for ($i = 0, $n = count($rows); $i < $n; $i++)
				{
					$row = $rows[$i];
					$key = $row->config_key;
					$value = $row->config_value;
					$config->$key = stripslashes($value);
				}
			}
			$this->_data = $config;
		}
		
		return $this->_data;
	}

	/**
	 * Store the configuration data
	 *
	 * @param array $post
	 * @return Boolean
	 */
	function store($data)
	{
		$db = $this->getDbo();
		$db->truncateTable('#__eshop_configs');
		$row = new EShopTable('#__eshop_configs', 'id', $db);
		$startOrderId = isset($data['start_order_id']) ? $data['start_order_id'] : 0;
		$data['start_order_id'] = 0;
		
		if (is_uploaded_file($_FILES['watermark_photo_file']['tmp_name']))
		{
			unset($data['watermark_photo_file']);
			if (isset($data['watermark_photo']))
			{
				unset($data['watermark_photo']);
			}
		}
		
		if (isset($data['custom_css']))
		{
			$customCss = $data['custom_css'];
			unset($data['custom_css']);
		}
		
		foreach ($data as $key => $value)
		{
			$row->id = 0;
			if (is_array($value))
				$value = implode(',', $value);
			$row->config_key = $key;
			$row->config_value = $value;
			$row->store();
		}
		
		//Watermark photo
		if (is_uploaded_file($_FILES['watermark_photo_file']['tmp_name']))
		{
			$filename    = $_FILES['watermark_photo_file']['name'];
			$filenameArr = explode(".", $filename);
			$ext         = $filenameArr[count($filenameArr) - 1];
			$filename    = "eshopwatermark." . $ext;
			move_uploaded_file($_FILES['watermark_photo_file']['tmp_name'], JPATH_ROOT . "/images/" . $filename);
			$row->id = 0;
			$row->config_key = 'watermark_photo';
			$row->config_value = $filename;
			$row->store();
		}
		
		if ($startOrderId)
		{
			$query = 'ALTER TABLE  #__eshop_orders AUTO_INCREMENT = ' . intval($startOrderId);
			$db->setQuery($query);
			$db->execute();
		}
		
		if (isset($customCss))
		{
			$theme    = EshopHelper::getConfigValue('theme');
			JFile::write(JPATH_ROOT . '/components/com_eshop/themes/' . $theme . '/css/custom.css', trim($customCss));
		}
		
		//Update currencies
		EshopHelper::updateCurrencies(true);
		return true;
	}
}