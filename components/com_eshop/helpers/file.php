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

class EshopFile
{

	/**
	 *
	 * Function to check file before uploading
	 *
	 * @param array $file
	 *
	 * @return mixed
	 */
	public static function checkFileUpload($file)
	{
		$error = array();

		if (is_array($file['name']))
		{
			for ($i = 0; $n = count($file['name']), $i < $n; $i++)
			{
				if ($file['name'][$i] != '')
				{
					$fileName = basename(preg_replace('/[^a-zA-Z0-9\.\-\s+]/', '', html_entity_decode($file['name'][$i], ENT_QUOTES, 'UTF-8')));

					if ((strlen($fileName) < 3) || (strlen($fileName) > 64))
					{
						$error[] = JText::_('ESHOP_UPLOAD_ERROR_FILENAME');
					}

					//Allowed file extension types
					$allowed   = array();
					$fileTypes = explode("\n", EshopHelper::getConfigValue('file_extensions_allowed'));

					foreach ($fileTypes as $fileType)
					{
						$allowed[] = strtolower(trim($fileType));
					}

					if (!in_array(strtolower(substr(strrchr($fileName, '.'), 1)), $allowed))
					{
						$error[] = JText::_('ESHOP_UPLOAD_ERROR_FILETYPE');
					}

					// Allowed file mime types
					$allowed   = array();
					$fileTypes = explode("\n", EshopHelper::getConfigValue('file_mime_types_allowed'));

					foreach ($fileTypes as $fileType)
					{
						$allowed[] = strtolower(trim($fileType));
					}

					if (!in_array(strtolower($file['type'][$i]), $allowed))
					{
						$error[] = JText::_('ESHOP_UPLOAD_ERROR_FILE_MIME_TYPE');
					}

					if ($file['error'][$i] != UPLOAD_ERR_OK)
					{
						$error[] = JText::_('ESHOP_ERROR_UPLOAD_' . $file['error'][$i]);
					}

					if (count($error))
					{
						break;
					}
				}
			}
		}
		else
		{
			$fileName = basename(preg_replace('/[^a-zA-Z0-9\.\-\s+]/', '', html_entity_decode($file['name'], ENT_QUOTES, 'UTF-8')));

			if ((strlen($fileName) < 3) || (strlen($fileName) > 64))
			{
				$error[] = JText::_('ESHOP_UPLOAD_ERROR_FILENAME');
			}

			//Allowed file extension types
			$allowed   = array();
			$fileTypes = explode("\n", EshopHelper::getConfigValue('file_extensions_allowed'));

			foreach ($fileTypes as $fileType)
			{
				$allowed[] = strtolower(trim($fileType));
			}

			if (!in_array(strtolower(substr(strrchr($fileName, '.'), 1)), $allowed))
			{
				$error[] = JText::_('ESHOP_UPLOAD_ERROR_FILETYPE');
			}

			// Allowed file mime types
			$allowed   = array();
			$fileTypes = explode("\n", EshopHelper::getConfigValue('file_mime_types_allowed'));

			foreach ($fileTypes as $fileType)
			{
				$allowed[] = strtolower(trim($fileType));
			}

			if (!in_array(strtolower($file['type']), $allowed))
			{
				$error[] = JText::_('ESHOP_UPLOAD_ERROR_FILE_MIME_TYPE');
			}

			if ($file['error'] != UPLOAD_ERR_OK)
			{
				$error[] = JText::_('ESHOP_ERROR_UPLOAD_' . $file['error']);
			}
		}

		if (count($error) > 0)
		{
			return $error;
		}
		else
		{
			return true;
		}
	}
}