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

class EshopModelManufacturers extends RADModelList
{

	public function __construct($config = array())
	{
		$config['translatable']        = true;
		$config['translatable_fields'] = array(
			'manufacturer_name',
			'manufacturer_alias',
			'manufacturer_desc',
			'manufacturer_page_title',
			'manufacturer_page_heading',
		    'manufacturer_alt_image'
		);

		parent::__construct($config);

		$app        = JFactory::getApplication();
		$listLength = EshopHelper::getConfigValue('catalog_limit');

		if (!$listLength)
		{
			$listLength = $app->getCfg('list_limit');
		}

		$this->state->insert('id', 'int', 0)
			->insert('limit', 'int', $listLength);

		$request = EshopHelper::getRequestData();
		$this->state->setData($request);

		if ($app->input->getCmd('view') == 'manufacturers')
		{
			$app->setUserState('limit', $this->state->limit);
		}
	}

	/**
	 * Method to get manufacturers data
	 *
	 * @access public
	 * @return array
	 */
	public function getData()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->data))
		{
			$rows                = parent::getData();
			$imageSizeFunction   = EshopHelper::getConfigValue('manufacturer_image_size_function', 'resizeImage');
			$imageManufacturerWidth  = EshopHelper::getConfigValue('image_manufacturer_width');
			$imageManufacturerHeight = EshopHelper::getConfigValue('image_manufacturer_height');
			$baseUri             = JUri::base(true);

			for ($i = 0; $n = count($rows), $i < $n; $i++)
			{
				$row = $rows[$i];

				if ($row->manufacturer_image && JFile::exists(JPATH_ROOT . '/media/com_eshop/manufacturers/' . $row->manufacturer_image))
				{
					if (EshopHelper::getConfigValue('manufacturer_use_image_watermarks'))
					{
						$watermarkImage = EshopHelper::generateWatermarkImage(JPATH_ROOT . '/media/com_eshop/manufacturers/' . $row->manufacturer_image);
						$manufacturerImage  = $watermarkImage;
					}
					else
					{
						$manufacturerImage = $row->manufacturer_image;
					}

					$image = call_user_func_array(array('EshopHelper', $imageSizeFunction),
						array($manufacturerImage, JPATH_ROOT . '/media/com_eshop/manufacturers/', $imageManufacturerWidth, $imageManufacturerHeight));
				}
				else
				{
					$image = call_user_func_array(array('EshopHelper', $imageSizeFunction),
						array('no-image.png', JPATH_ROOT . '/media/com_eshop/manufacturers/', $imageManufacturerWidth, $imageManufacturerHeight));
				}

				$row->image = $baseUri . '/media/com_eshop/manufacturers/resized/' . $image;
			}

			$this->data = $rows;
		}

		return $this->data;
	}

	/**
	 * Override BuildQueryWhere method
	 * @see RADModelList::_buildQueryWhere()
	 */
	protected function _buildQueryWhere(JDatabaseQuery $query)
	{
		parent::_buildQueryWhere($query);

		//Check viewable of customer groups
		$user = JFactory::getUser();

		if ($user->get('id'))
		{
			$customer        = new EshopCustomer();
			$customerGroupId = $customer->getCustomerGroupId();
		}
		else
		{
			$customerGroupId = EshopHelper::getConfigValue('customergroup_id');
		}

		if (!$customerGroupId)
		{
			$customerGroupId = 0;
		}

		$query->where('((a.manufacturer_customergroups = "") OR (a.manufacturer_customergroups IS NULL) OR (a.manufacturer_customergroups = "' . $customerGroupId . '") OR (a.manufacturer_customergroups LIKE "' . $customerGroupId . ',%") OR (a.manufacturer_customergroups LIKE "%,' . $customerGroupId . ',%") OR (a.manufacturer_customergroups LIKE "%,' . $customerGroupId . '"))');

		return $this;
	}
}