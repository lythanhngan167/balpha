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
 * Eshop Component Model
 *
 * @package		Joomla
 * @subpackage	EShop
 * @since 1.5
 */
class EShopModelDownload extends EShopModel
{

	public function __construct($config)
	{
		$config['translatable'] = true;
		$config['translatable_fields'] = array('download_name');
		
		parent::__construct($config);
	}

	function store(&$data)
	{
		$file = $_FILES['file'];
		$message = '';
		if (!empty($file['name']))
		{
			$fileName = JFile::makeSafe($file['name']);
			if ((strlen($fileName) < 3) || (strlen($fileName) > 64))
			{
				$message = JText::_('ESHOP_UPLOAD_ERROR_FILENAME');
			}
			//Allowed file extension types
			$allowed = array();
			$fileTypes = explode("\n", EshopHelper::getConfigValue('file_extensions_allowed'));
			foreach ($fileTypes as $fileType)
			{
				$allowed[] = trim($fileType);
			}
			if (!in_array(substr(strrchr($fileName, '.'), 1), $allowed))
			{
				$message = JText::_('ESHOP_UPLOAD_ERROR_FILETYPE');
			}
			// Allowed file mime types
			$allowed = array();
			$fileTypes = explode("\n", EshopHelper::getConfigValue('file_mime_types_allowed'));
			foreach ($fileTypes as $fileType)
			{
				$allowed[] = trim($fileType);
			}
			if (!in_array($file['type'], $allowed))
			{
				$message = JText::_('ESHOP_UPLOAD_ERROR_FILE_MIME_TYPE');
			}
			if ($file['error'] != UPLOAD_ERR_OK)
			{
				$message = JText::_('ESHOP_ERROR_UPLOAD_' . $file['error']);
			}
			if (JFile::exists(JPATH_ROOT . '/media/com_eshop/downloads/' . $fileName))
			{
				if (!isset($_POST['overwrite']))
				{
					$message = JText::_('ESHOP_FILE_EXISTED');
				}
			}
			if ($message == '')
			{
				JFile::upload($file['tmp_name'], JPATH_ROOT . '/media/com_eshop/downloads/' . $fileName, false, true);
				$data['filename'] = $fileName;
			}
			else 
			{
				$mainframe = JFactory::getApplication();
				$mainframe->enqueueMessage($message, 'error');
				$mainframe->redirect('index.php?option=com_eshop&view=download&cid[]='.$data['id']);
			}
		}
		else 
		{
			if ($data['existed_file'] != '')
			{
				$data['filename'] = $data['existed_file'];
			}
		}
		parent::store($data);
		return true;
	}
	
	/**
	 * Method to remove downloads
	 *
	 * @access	public
	 * @return boolean True on success
	 * @since	1.5
	 */
	public function delete($cid = array())
	{
		//Remove download elements
		if (count($cid))
		{
			$db = $this->getDbo();
			$query = $db->getQuery(true);
			$query->delete('#__eshop_productdownloads')
				->where('download_id IN (' . implode(',', $cid) . ')');
			$db->setQuery($query);
			$db->execute();
		}
		parent::delete($cid);
	}
}