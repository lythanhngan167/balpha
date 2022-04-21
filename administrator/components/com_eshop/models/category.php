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
defined('_JEXEC') or die();

/**
 * EShop Component Category Model
 *
 * @package    Joomla
 * @subpackage EShop
 * @since      1.5
 */
class EShopModelCategory extends EShopModel
{

	public function __construct($config)
	{
		$config['translatable']        = true;
		$config['translatable_fields'] = array('category_name', 'category_alias', 'category_desc', 'category_page_title', 'category_page_heading', 'category_alt_image', 'meta_key', 'meta_desc');

		parent::__construct($config);
	}

	function store(&$data)
	{
		$input = JFactory::getApplication()->input;
		$imagePath = JPATH_ROOT . '/media/com_eshop/categories/';
		if ($input->getInt('remove_image') && $data['id'])
		{
			//Remove image first
			$row = new EShopTable('#__eshop_categories', 'id', $this->getDbo());
			$row->load($data['id']);
			if (JFile::exists($imagePath . $row->category_image))
				JFile::delete($imagePath . $row->category_image);

			if (JFile::exists($imagePath . 'resized/' . JFile::stripExt($row->category_image) . '-100x100.' . JFile::getExt($row->category_image)))
				JFile::delete($imagePath . 'resized/' . JFile::stripExt($row->category_image) . '-100x100.' . JFile::getExt($row->category_image));
			$data['category_image'] = '';
		}

		$categoryImage = $_FILES['category_image'];
		if ($categoryImage['name'])
		{
			$checkFileUpload = EshopFile::checkFileUpload($categoryImage);
			if (is_array($checkFileUpload))
			{
				$mainframe = JFactory::getApplication();
				$mainframe->enqueueMessage(sprintf(JText::_('ESHOP_UPLOAD_IMAGE_ERROR'), implode(' / ', $checkFileUpload)), 'error');
				$mainframe->redirect('index.php?option=com_eshop&task=category.edit&cid[]=' . $data['id']);
			}
			else
			{
				if (is_uploaded_file($categoryImage['tmp_name']) && file_exists($categoryImage['tmp_name']))
				{
					if (JFile::exists($imagePath . $categoryImage['name']))
					{
						$imageFileName = uniqid('image_') . '_' . JFile::makeSafe($categoryImage['name']);
					}
					else
					{
						$imageFileName = JFile::makeSafe($categoryImage['name']);
					}
					JFile::upload($categoryImage['tmp_name'], $imagePath . $imageFileName, false, true);
					// Resize image
					EshopHelper::resizeImage($imageFileName, JPATH_ROOT . '/media/com_eshop/categories/', 100, 100);
					$data['category_image'] = $imageFileName;
				}
			}
		}


		if ($input->getInt('remove_image_icon') && $data['id'])
		{
			//Remove image first
			$row = new EShopTable('#__eshop_categories', 'id', $this->getDbo());
			$row->load($data['id']);
			if (JFile::exists($imagePath . $row->category_image_icon))
				JFile::delete($imagePath . $row->category_image_icon);

			if (JFile::exists($imagePath . 'resized/' . JFile::stripExt($row->category_image_icon) . '-100x100.' . JFile::getExt($row->category_image_icon)))
				JFile::delete($imagePath . 'resized/' . JFile::stripExt($row->category_image_icon) . '-100x100.' . JFile::getExt($row->category_image_icon));
			$data['category_image_icon'] = '';
		}

		$categoryImageIcon = $_FILES['category_image_icon'];
		if ($categoryImageIcon['name'])
		{
			$checkFileUpload = EshopFile::checkFileUpload($categoryImageIcon);
			if (is_array($checkFileUpload))
			{
				$mainframe = JFactory::getApplication();
				$mainframe->enqueueMessage(sprintf(JText::_('ESHOP_UPLOAD_IMAGE_ERROR'), implode(' / ', $checkFileUpload)), 'error');
				$mainframe->redirect('index.php?option=com_eshop&task=category.edit&cid[]=' . $data['id']);
			}
			else
			{
				if (is_uploaded_file($categoryImageIcon['tmp_name']) && file_exists($categoryImageIcon['tmp_name']))
				{
					if (JFile::exists($imagePath . $categoryImageIcon['name']))
					{
						$imageFileName = uniqid('image_') . '_' . JFile::makeSafe($categoryImageIcon['name']);
					}
					else
					{
						$imageFileName = JFile::makeSafe($categoryImageIcon['name']);
					}
					JFile::upload($categoryImageIcon['tmp_name'], $imagePath . $imageFileName, false, true);
					// Resize image
					EshopHelper::resizeImage($imageFileName, JPATH_ROOT . '/media/com_eshop/categories/', 100, 100);
					$data['category_image_icon'] = $imageFileName;
				}
			}
		}

		if (count($data['category_customergroups']))
		{
			$data['category_customergroups'] = implode(',', $data['category_customergroups']);
		}
		else
		{
			$data['category_customergroups'] = '';
		}

		// Calculate category level
		if ($data['category_parent_id'] > 0)
		{
			$db    = $this->getDbo();
			$query = $db->getQuery(true);
			// Calculate level
			$query->clear();
			$query->select('`level`')
				->from('#__eshop_categories')
				->where('id = ' . (int) $data['category_parent_id']);
			$db->setQuery($query);
			$data['level'] = (int) $db->loadResult() + 1;
		}
		else
		{
			$data['level'] = 1;
		}

		parent::store($data);

		return true;
	}

	/**
	 * Method to remove categories
	 *
	 * @access    public
	 * @return boolean True on success
	 * @since     1.5
	 */
	public function delete($cid = array())
	{
		if (count($cid))
		{
			$db    = $this->getDbo();
			$cids  = implode(',', $cid);
			$query = $db->getQuery(true);
			$query->select('id')
				->from('#__eshop_categories')
				->where('id IN (' . $cids . ')')
				->where('id NOT IN (SELECT  DISTINCT(category_id) FROM #__eshop_productcategories)')
				->where('id NOT IN (SELECT DISTINCT(category_parent_id) FROM #__eshop_categories WHERE category_parent_id > 0)');
			$db->setQuery($query);
			$categories = $db->loadColumn();
			if (count($categories))
			{
				$query->clear();
				$query->delete('#__eshop_categories')
					->where('id IN (' . implode(',', $categories) . ')');
				$db->setQuery($query);
				if (!$db->execute())
					//Removed error
					return 0;
				$numItemsDeleted = $db->getAffectedRows();
				//Delete details records
				$query->clear();
				$query->delete('#__eshop_categorydetails')
					->where('category_id IN (' . implode(',', $categories) . ')');
				$db->setQuery($query);
				if (!$db->execute())
					//Removed error
					return 0;
				//Remove SEF urls for categories
				for ($i = 0; $n = count($categories), $i < $n; $i++)
				{
					$query->clear();
					$query->delete('#__eshop_urls')
						->where('query LIKE "view=category&id=' . $categories[$i] . '"');
					$db->setQuery($query);
					$db->execute();
				}
				if ($numItemsDeleted < count($cid))
				{
					//Removed warning
					return 2;
				}
			}
			else
			{
				return 2;
			}
		}

		//Removed success
		return 1;
	}
}
