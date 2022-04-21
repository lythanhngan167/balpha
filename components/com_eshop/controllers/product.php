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
class EShopControllerProduct extends JControllerLegacy
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
	 * Function to write review
	 */
	public function writeReview()
	{
		$post = $this->input->post->getArray();

		/* @var EshopModelProduct $model */
		$model = $this->getModel('Product');
		$json  = $model->writeReview($post);

		echo json_encode($json);

		JFactory::getApplication()->close();
	}

	/**
	 *
	 * Function to upload file
	 */
	public function uploadFile()
	{
		$json = array();

		$file = $this->input->files->get('file', null, 'raw');

		if (!empty($file['name']))
		{
			$fileName = basename(preg_replace('/[^a-zA-Z0-9\.\-\s+]/', '', html_entity_decode($file['name'], ENT_QUOTES, 'UTF-8')));

			if ((strlen($fileName) < 3) || (strlen($fileName) > 64))
			{
				$json['error'] = JText::_('ESHOP_UPLOAD_ERROR_FILENAME');
			}

			//Allowed file extension types
			$allowed   = array();
			$fileTypes = explode("\n", EshopHelper::getConfigValue('file_extensions_allowed'));

			foreach ($fileTypes as $fileType)
			{
				$allowed[] = trim($fileType);
			}

			if (!in_array(substr(strrchr($fileName, '.'), 1), $allowed))
			{
				$json['error'] = JText::_('ESHOP_UPLOAD_ERROR_FILETYPE');
			}

			// Allowed file mime types
			$allowed   = array();
			$fileTypes = explode("\n", EshopHelper::getConfigValue('file_mime_types_allowed'));

			foreach ($fileTypes as $fileType)
			{
				$allowed[] = trim($fileType);
			}

			if (!in_array($file['type'], $allowed))
			{
				$json['error'] = JText::_('ESHOP_UPLOAD_ERROR_FILE_MIME_TYPE');
			}

			if ($file['error'] != UPLOAD_ERR_OK)
			{
				$json['error'] = JText::_('ESHOP_ERROR_UPLOAD_' . $file['error']);
			}
		}
		else
		{
			$json['error'] = JText::_('ESHOP_ERROR_UPLOAD');
		}

		if (!$json && is_uploaded_file($file['tmp_name']) && file_exists($file['tmp_name']))
		{
			if (JFile::exists(JPATH_ROOT . '/media/com_eshop/files/' . $fileName))
			{
				$fileName = uniqid('file_') . '_' . $fileName;
			}

			$json['file'] = $fileName;

			JFile::upload($file['tmp_name'], JPATH_ROOT . '/media/com_eshop/files/' . $fileName, false, true);

			$json['success'] = JText::_('ESHOP_SUCCESS_UPLOAD');
		}

		echo json_encode($json);

		JFactory::getApplication()->close();
	}

	/**
	 *
	 * Function to process ask question
	 */
	public function processAskQuestion()
	{
		$data = $this->input->post->getArray();

		/* @var EshopModelProduct $model */
		$model = $this->getModel('product');
		$model->processAskQuestion($data);
	}

	/**
	 *
	 * Function to process send a friend
	 */
	public function processEmailAFriend()
	{
		
		if (EshopHelper::getConfigValue('allow_email_to_a_friend'))
		{
			$data = $this->input->post->getArray();
	
			/* @var EshopModelProduct $model */
			$model = $this->getModel('product');
			$model->processEmailAFriend($data);
		}
	}

	/**
	 * Function to process notify
	 *
	 */
	public function processNotify()
	{
		$data = $this->input->post->getArray();

		/* @var EshopModelProduct $model */
		$model = $this->getModel('product');
		$model->processNotify($data);
		JFactory::getApplication(0)->close();
	}

	/**
	 *
	 * Function to download product with pdf
	 */
	public function downloadPDF()
	{
		$productId = $this->input->getInt('product_id', 0);

		/* @var EshopModelProduct $model */
		$model = $this->getModel('Product');
		$model->downloadPDF($productId);
	}
}