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

class EShopModelProduct extends EShopModel
{
	/**
	 * Entity ID
	 *
	 * @var int
	 */
	protected $id = null;

	/**
	 * Entity data
	 *
	 * @var array
	 */
	protected $data = null;

	/**
	 * Current active language
	 *
	 * @var string
	 */
	protected $language = null;

	/**
	 *
	 * Constructor
	 * @since 1.5
	 */
	public function __construct($config = array())
	{
		parent::__construct();
		$input			= JFactory::getApplication()->input;
		$this->id       = $input->getInt('id');
		$this->data     = null;
		$this->language = JFactory::getLanguage()->getTag();
	}

	/**
	 *
	 * Function to get product data
	 * @see EShopModel::getData()
	 */
	public function &getData()
	{
		if (empty($this->data))
		{
			$this->_loadData();
		}

		return $this->data;
	}

	/**
	 *
	 * Function to load product data
	 * @see EShopModel::_loadData()
	 */
	public function _loadData()
	{
		$db    = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('a.*, b.product_name, b.product_alias, b.product_desc, b.product_short_desc, b.product_page_title, b.product_page_heading, b.product_alt_image, b.meta_key, b.meta_desc, b.tab1_title, b.tab1_content, b.tab2_title, b.tab2_content, b.tab3_title, b.tab3_content, b.tab4_title, b.tab4_content, b.tab5_title, b.tab5_content')
			->from('#__eshop_products AS a')
			->innerJoin('#__eshop_productdetails AS b ON (a.id = b.product_id)')
			->where('a.id = ' . intval($this->id))
			->where('a.published = 1')
			->where('b.language = "' . $this->language . '"');

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

		$query->where('((a.product_customergroups = "") OR (a.product_customergroups IS NULL) OR (a.product_customergroups = "' . $customerGroupId . '") OR (a.product_customergroups LIKE "' . $customerGroupId . ',%") OR (a.product_customergroups LIKE "%,' . $customerGroupId . ',%") OR (a.product_customergroups LIKE "%,' . $customerGroupId . '"))');
		
		$currentDate = $this->getDbo()->quote(EshopHelper::getServerTimeFromGMTTime());
		$query->where('(a.product_available_date = "0000-00-00 00:00:00" OR a.product_available_date <= ' . $currentDate . ')');
		
		$langCode = JFactory::getLanguage()->getTag();
		$query->where('((a.product_languages = "") OR (a.product_languages IS NULL) OR (a.product_languages = "' . $langCode . '") OR (a.product_languages LIKE "' . $langCode . ',%") OR (a.product_languages LIKE "%,' . $langCode . ',%") OR (a.product_languages LIKE "%,' . $langCode . '"))');

		//Check out of stock
		if (EshopHelper::getConfigValue('hide_out_of_stock_products'))
		{
			$query->where('a.product_quantity > 0');
		}

		$db->setQuery($query);

		$this->data = $db->loadObject();
	}

	/**
	 *
	 * Function to write review
	 *
	 * @param array $data
	 *
	 * @return  array
	 */
	public function writeReview($data)
	{
		$user = JFactory::getUser();
		$json = array();

		if (strlen($data['author']) < 3 || strlen($data['author']) > 25)
		{
			$json['error'] = JText::_('ESHOP_ERROR_YOUR_NAME');

			return $json;
		}

		if (strlen($data['review']) < 25 || strlen($data['review']) > 1000)
		{
			$json['error'] = JText::_('ESHOP_ERROR_YOUR_REVIEW');

			return $json;
		}

		if (!$data['rating'])
		{
			$json['error'] = JText::_('ESHOP_ERROR_RATING');

			return $json;
		}

		if (EshopHelper::getConfigValue('enable_reviews_captcha'))
		{
			$app           = JFactory::getApplication();
			$captchaPlugin = $app->getParams()->get('captcha', JFactory::getConfig()->get('captcha'));

			if ($captchaPlugin == 'recaptcha')
			{
				$res = JCaptcha::getInstance($captchaPlugin)->checkAnswer($app->input->post->get('recaptcha_response_field', '', 'string'));

				if (!$res)
				{
					$json['error'] = JText::_('ESHOP_INVALID_CAPTCHA');

					return $json;
				}
			}
		}

		if (!$json)
		{
			$row = JTable::getInstance('Eshop', 'Review');
			$row->bind($data);
			$row->id               = '';
			$row->product_id       = $data['product_id'];
			$row->customer_id      = $user->get('id') ? $user->get('id') : 0;
			$row->published        = 0;
			$row->created_date     = JFactory::getDate()->toSql();
			$row->created_by       = $user->get('id') ? $user->get('id') : 0;
			$row->modified_date    = JFactory::getDate()->toSql();
			$row->modified_by      = $user->get('id') ? $user->get('id') : 0;
			$row->checked_out      = 0;
			$row->checked_out_time = '0000-00-00 00:00:00';

			if ($row->store())
			{
				$json['success'] = JText::_('ESHOP_REVIEW_SUBMITTED_SUCESSFULLY');
				//Send notification to admin
				if (EshopHelper::getConfigValue('product_alert_review', 1))
				{
					$sendFrom			= EshopHelper::getSendFrom();
					$fromName			= $sendFrom['from_name'];
					$fromEmail			= $sendFrom['from_email'];
					$reviewSubject		= EshopHelper::getMessageValue('review_notification_email_subject');
					$reviewBody			= EshopHelper::getReviewNotificationEmailBody($data);
					$adminEmail			= EshopHelper::getConfigValue('email') ? trim(EshopHelper::getConfigValue('email')) : $fromEmail;
					$mailer				= JFactory::getMailer();
					$mailer->sendMail($fromEmail, $fromName, $adminEmail, $reviewSubject, $reviewBody, 1);
				}
			}
			else
			{
				$json['error'] = JText::_('ESHOP_REVIEW_SUBMITTED_FAILURED');
			}

			return $json;
		}
	}

	/**
	 *
	 * Function to process ask question
	 *
	 * @param array $data
	 */
	public function processAskQuestion($data)
	{
		if (EshopHelper::getConfigValue('product_alert_ask_question', 1))
		{
			$sendFrom           = EshopHelper::getSendFrom();
			$fromName           = $sendFrom['from_name'];
			$fromEmail          = $sendFrom['from_email'];
			$product            = EshopHelper::getProduct($data['product_id']);
			$askQuestionSubject = EshopHelper::getMessageValue('ask_question_notification_email_subject');
			$askQuestionBody    = EshopHelper::getAskQuestionEmailBody($data, $product);
			$adminEmail         = EshopHelper::getConfigValue('email') ? trim(EshopHelper::getConfigValue('email')) : $fromEmail;
			$mailer             = JFactory::getMailer();
			$mailer->sendMail($fromEmail, $fromName, $adminEmail, $askQuestionSubject, $askQuestionBody, 1);
		}
	}

	/**
	 *
	 * Function to process email a friend
	 *
	 * @param array $data
	 */
	public function processEmailAFriend($data)
	{
		$jconfig                   = new JConfig();
		$fromName                  = $jconfig->fromname;
		$fromEmail                 = $jconfig->mailfrom;
		$product                   = EshopHelper::getProduct($data['product_id']);
		$emailAFriendSubject       = EshopHelper::getMessageValue('email_a_friend_subject');
		$emailAFriendSubject       = str_replace('[STORE_NAME]', EshopHelper::getConfigValue('store_name'), $emailAFriendSubject);
		$emailAFriendSubject       = str_replace('[PRODUCT_NAME]', $product->product_name, $emailAFriendSubject);
		$emailAFriendBody          = EshopHelper::getMessageValue('email_a_friend');
		$replaces                  = array();
		$replaces['sender_name']   = $data['sender_name'];
		$replaces['sender_email']  = $data['sender_email'];
		$replaces['invitee_name']  = $data['invitee_name'];
		$replaces['invitee_email'] = $data['invitee_email'];
		$replaces['message']       = $data['message'];
		$replaces['product_link']  = JRoute::_(JUri::root() . EshopRoute::getProductRoute($data['product_id'], EShopHelper::getProductCategory($data['product_id']), JFactory::getLanguage()->getTag()));

		foreach ($replaces as $key => $value)
		{
			$key              = strtoupper($key);
			$emailAFriendBody = str_replace("[$key]", $value, $emailAFriendBody);
		}

		$emailAFriendBody = EshopHelper::convertImgTags($emailAFriendBody);
		$mailer           = JFactory::getMailer();
		$mailer->sendMail($fromEmail, $fromName, $data['invitee_email'], $emailAFriendSubject, $emailAFriendBody, 1);
	}

	/**
	 *
	 * Function to process notify
	 *
	 * @param array $data
	 */
	public function processNotify($data)
	{
		if (!isset($data['product_id']) || !$data['product_id'])
		{
			echo JText::_('ESHOP_PRODUCT_NOTIFY_ERROR_MISS_PRODUCT');
		}
		elseif (!isset($data['notify_email']) && empty($data['notify_email']))
		{
			echo JText::_('ESHOP_PRODUCT_NOTIFY_ERROR_MISS_NOTIFY_EMAIL');
		}
		else
		{
			$row = JTable::getInstance('Eshop', 'Notify');
			$row->load(array('product_id' => $data['product_id'], 'notify_email' => $data['notify_email'], 'sent_email' => 0));
			$this->id         = $data['product_id'];
			$this->data       = $this->getData();
			$data['language'] = JFactory::getLanguage()->getTag();

			if ($row->id)
			{
				echo sprintf(JText::_('ESHOP_PRODUCT_NOTIFY_EXISTED'), $data['notify_email'], $this->data->product_name);
			}
			else
			{
				$row->bind($data);

				if ($row->store())
				{
					echo sprintf(JText::_('ESHOP_PRODUCT_NOTIFY_SUCCESSFULLY'), $data['notify_email'], $this->data->product_name);
				}
				else
				{
					echo JText::_('ESHOP_PRODUCT_NOTIFY_ERROR');
				}
			}
		}
	}

	/**
	 *
	 * Function to process download PDF
	 *
	 * @param int $productId
	 */
	public function downloadPDF($productId)
	{
		EshopHelper::generateProductPDF($productId);
		$product        = EshopHelper::getProduct($productId, JFactory::getLanguage()->getTag());
		$filename       = 'product_' . $product->product_sku . '.pdf';
		$productPdfPath = JPATH_ROOT . '/media/com_eshop/pdf/' . $filename;
		while (@ob_end_clean()) ;
		EshopHelper::processDownload($productPdfPath, $filename, true);
	}
}