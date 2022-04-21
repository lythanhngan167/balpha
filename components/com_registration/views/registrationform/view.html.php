<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Registration
 * @author     nganly <lythanhngan167@gmail.com>
 * @copyright  2020 nganly
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use \Joomla\CMS\Language\Text;

jimport('joomla.application.component.view');

/**
 * View to edit
 *
 * @since  1.6
 */
class RegistrationViewRegistrationform extends \Joomla\CMS\MVC\View\HtmlView
{
	protected $state;

	protected $item;

	protected $form;

	protected $params;

	protected $canSave;

	/**
	 * Display the view
	 *
	 * @param   string  $tpl  Template name
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	public function display($tpl = null)
	{
		$app  = Factory::getApplication();
		$user = Factory::getUser();
		$session = JFactory::getSession();
		// $session->set('landingpage_userid', 0);


		$userid = $session->get('landingpage_userid');
		$dataBiznetOk = $this->getDataBiznet($userid);
		$arrayJSON = json_decode($dataBiznetOk);
		$this->arrayJSON = $arrayJSON;
		if($dataBiznetOk['landingpage_block'] == 1){
			if ($_SERVER['HTTP_HOST'] == "localhost") {
				$url = "http://localhost/bcavietnam";
			}else {
				$url = "http://bcavietnam.com";
			}
			header("Location: $url");
			exit();
		}

		$this->state   = $this->get('State');
		$this->item    = $this->get('Item');
		$this->params  = $app->getParams('com_registration');
		$this->canSave = $this->get('CanSave');
		$this->form		= $this->get('Form');

		//opengraph
		$document = JFactory::getDocument();
		$document->addCustomTag('<meta property="og:url" content="'.JURI::current().'" />');
		$document->addCustomTag('<meta property="og:type" content="registration" />');
		$document->addCustomTag('<meta property="og:title" content="'. $this->params->get('page_title') .'" />');

		$document->addCustomTag('<meta property="og:description" content="'. JHtml::_('string.truncate', $this->params->get('page_description'), 155, false, false ) .'" />');
		$document->addCustomTag('<meta property="og:image" content="'. JURI::root().'images/og-image.jpg" />');
		$document->addCustomTag('<meta property="og:image:width" content="600" />');
		$document->addCustomTag('<meta property="og:image:height" content="400" />');

	// 	<meta property="og:url" content="http://localhost/bcavietnam/dich-vu/139-tu-van-ke-hoach-tao-quy-hoc-vanhuu-tri-dau-tu.html" />
	// <meta property="og:type" content="article" />
	// <meta property="og:title" content="Tư vấn kế hoạch tạo quỹ học vấn/hưu trí/ đầu tư" />
	// <meta property="og:description" content="Tất cả chúng ta rồi sẽ già đi theo quy luật của cuộc sống." />
	// <meta property="og:image" content="http://localhost/bcavietnam/images/og-image.jpg" />
	// <meta property="og:image:width" content="600" />
	// <meta property="og:image:height" content="400" />

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors));
		}



		$this->_prepareDocument();

		parent::display($tpl);
	}

	/**
	 * Prepares the document
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	protected function _prepareDocument()
	{
		$app   = Factory::getApplication();
		$menus = $app->getMenu();
		$title = null;

		// Because the application sets a default page title,
		// we need to get it from the menu item itself
		$menu = $menus->getActive();

		if ($menu)
		{
			$this->params->def('page_heading', $this->params->get('page_title', $menu->title));
		}
		else
		{
			$this->params->def('page_heading', Text::_('COM_REGISTRATION_DEFAULT_PAGE_TITLE'));
		}

		$title = $this->params->get('page_title', '');

		if (empty($title))
		{
			$title = $app->get('sitename');
		}
		elseif ($app->get('sitename_pagetitles', 0) == 1)
		{
			$title = Text::sprintf('JPAGETITLE', $app->get('sitename'), $title);
		}
		elseif ($app->get('sitename_pagetitles', 0) == 2)
		{
			$title = Text::sprintf('JPAGETITLE', $title, $app->get('sitename'));
		}

		$this->document->setTitle($title);

		if ($this->params->get('menu-meta_description'))
		{
			$this->document->setDescription($this->params->get('menu-meta_description'));
		}

		if ($this->params->get('menu-meta_keywords'))
		{
			$this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
		}

		if ($this->params->get('robots'))
		{
			$this->document->setMetadata('robots', $this->params->get('robots'));
		}
	}

	public function getIntro($userid)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from($db->quoteName('#__agent_intro'));
		$query->where($db->quoteName('created_by') . " = " . $db->quote($userid));
		$query->where($db->quoteName('state') . " IN (0,1)");
		$query->setLimit(1);
		$db->setQuery($query);
		$result = $db->loadObject();
		return $result;
	}

	public function getImages($userid)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from($db->quoteName('#__agent_images'));
		$query->where($db->quoteName('created_by') . " = " . $db->quote($userid));
		$query->where($db->quoteName('state') . " IN (0,1)");
		$query->order('id DESC');
		$query->setLimit(1);
		$db->setQuery($query);
		$result = $db->loadObject();
		return $result;
	}

	public function getContact($userid)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from($db->quoteName('#__agent_contact'));
		$query->where($db->quoteName('created_by') . " = " . $db->quote($userid));
		$query->where($db->quoteName('state') . " IN (0,1)");
		$query->setLimit(1);
		$db->setQuery($query);
		$result = $db->loadObject();
		return $result;
	}

	public function getUserNameAgent($id){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from($db->quoteName('#__users'));
		$query->where($db->quoteName('id') . " = ".$id);
		$db->setQuery($query);
		$result = $db->loadObject();
		return $result;
	}

	public function getDataBiznet($userid){
		$data['userid'] = $userid;
		$param = $data;
		if($_SERVER['HTTP_HOST'] == 'localhost'){
				$url_biznet = 'http://localhost/biznetweb';
		}else{
			$url_biznet = 'https://biznet.com.vn';
		}

		// URL có chứa hai thông tin name và diachi
		$url = $url_biznet.'/index.php?option=com_registration&task=registrationform.landingpagePersonal';
		// Khởi tạo CURL
		$ch = curl_init($url);
		// Thiết lập có return
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// Thiết lập sử dụng POST
		curl_setopt($ch, CURLOPT_POST, count($param));
		// Thiết lập các dữ liệu gửi đi
		curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}

}
