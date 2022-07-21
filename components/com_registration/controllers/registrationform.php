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
jimport('joomla.filesystem.file');

use api\model\SUtil;
use \Joomla\CMS\Factory;
use \Joomla\CMS\Router\Route;
use \Joomla\CMS\Session\Session;
use \Joomla\CMS\Language\Text;

/**
 * Registration controller class.
 *
 * @since  1.6
 */
class RegistrationControllerRegistrationForm extends \Joomla\CMS\MVC\Controller\FormController
{
	/**
	 * Method to check out an item for editing and redirect to the edit form.
	 *
	 * @return void
	 *
	 * @since    1.6
     *
     * @throws Exception
	 */
	public function edit($key = NULL, $urlVar = NULL)
	{
		$app = Factory::getApplication();

		// Get the previous edit id (if any) and the current edit id.
		$previousId = (int) $app->getUserState('com_registration.edit.registration.id');
		$editId     = $app->input->getInt('id', 0);

		// Set the user id for the user to edit in the session.
		$app->setUserState('com_registration.edit.registration.id', $editId);

		// Get the model.
		$model = $this->getModel('RegistrationForm', 'RegistrationModel');

		// Check out the item
		if ($editId)
		{
			$model->checkout($editId);
		}

		// Check in the previous user.
		if ($previousId)
		{
			$model->checkin($previousId);
		}

		// Redirect to the edit screen.
		$this->setRedirect(Route::_('index.php?option=com_registration&view=registrationform&layout=edit', false));
	}

	public function saveCustomer()
	{
		// echo "<pre>";
		// print_r($_POST);
		// echo "</pre>";
		// die;
		$app   = Factory::getApplication();
		$model = $this->getModel('RegistrationForm', 'RegistrationModel');
		$data = array();
		$data['website'] = $_POST['from_website'];
		$data['name'] = $_POST['name'];
		$data['email'] = $_POST['email'];
		$data['phone'] = $_POST['phone'];
		$data['province'] = $_POST['province'];
		$data['utm_source'] = $_POST['utm_source'];
		$data['utm_sourceonly'] = $_POST['utm_sourceonly'];
		$data['utm_mediumonly'] = $_POST['utm_mediumonly'];
		$data['utm_compainonly'] = $_POST['utm_compainonly'];
		$data['from_landingpage'] = $_POST['from_landingpage'];
		$data['state'] = $_POST['state']; // trang thai active
		$data['from_website'] = $_POST['from_website'];
		$data['test'] = $_POST['test'];
		$data['status'] = $_POST['status']; // mac dinh 1 moi
		$data['created_by'] = $_POST['created_by'];

		if($data['from_website'] == 'bcavietnam.com'){
			if($data['test'] == 1){
				$myFile = JPATH_SITE.DS."test_tranfer_data.txt";
				$data['time'] = date("d-m-Y H:i:s");
				JFile::write($myFile, json_encode($data));
				//$currentIndex = JFile::read($myFile);
				echo "Test file ok";
			}else{
				$id_registration_biznet = $model->save($data);
				echo $id_registration_biznet;
			}
			die;
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
		//$result = $db->loadAssoc();
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

	public function checkLandingpageOk($userid)
	{
		$app   = Factory::getApplication();
		$model = $this->getModel('RegistrationForm', 'RegistrationModel');
		$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('us.*')
				->from('#__users AS us')
				->where("us.id = " .$userid);
			$db->setQuery($query);
			$result = $db->loadObject();
			if ($result) {
				if ($result->block_landingpage == 0) {
					$isOk = 1;
				}
				else {
					$isOk = 0;
				}
			}
			else {
				$isOk = 0;
			}
			return $isOk;
	}

	public function landingpagePersonal(){
		$data = array();
		$data['userid'] = $_POST['userid'];
		$userid = $data['userid'];
		$isOk = $this->checkLandingpageOk($userid);
		if ($isOk == 1) {
			$arrayResult = array();
			$arrayResult['intro'] = $this->getIntro($userid);
			$arrayResult['image'] = $this->getImages($userid);
			$arrayResult['contact'] = $this->getContact($userid);
			$arrayResult['landingpage_block'] = 0;
			echo json_encode($arrayResult);
		}else {
			$arrayResult = array();
			$arrayResult['landingpage_block'] = 1;
			echo json_encode($arrayResult);
		}
		die();
	}

	public function checkUserLandingpage(){
		$userid = 0;
		$userid = $_REQUEST['userid'];

		$isOk = 0;
		if($userid > 0){
			$isOk = $this->checkLandingpageOk($userid);
		}
		if($isOk == 1){
			echo $userid;
		}else{
			echo 0;
		}
		die();
	}

	/**
	 * Method to save a user's profile data.
	 *
	 * @return void
	 *
	 * @throws Exception
	 * @since  1.6
	 */
	public function save($key = NULL, $urlVar = NULL)
	{
		// Check for request forgeries.
		Session::checkToken() or jexit(Text::_('JINVALID_TOKEN'));

		// Initialise variables.
		$app   = Factory::getApplication();
		$model = $this->getModel('RegistrationForm', 'RegistrationModel');

		// Get the user data.
		$data = Factory::getApplication()->input->get('jform', array(), 'array');

		// Validate the posted data.
		$form = $model->getForm();

		if (!$form)
		{
			throw new Exception($model->getError(), 500);
		}

		// Validate the posted data.
		$data = $model->validate($form, $data);

		// Check for errors.
		if ($data === false)
		{
			// Get the validation messages.
			$errors = $model->getErrors();

			// Push up to three validation messages out to the user.
			for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++)
			{
				if ($errors[$i] instanceof Exception)
				{
					$app->enqueueMessage($errors[$i]->getMessage(), 'warning');
				}
				else
				{
					$app->enqueueMessage($errors[$i], 'warning');
				}
			}

			$input = $app->input;
			$jform = $input->get('jform', array(), 'ARRAY');

			// Save the data in the session.
			$app->setUserState('com_registration.edit.registration.data', $jform);

			// Redirect back to the edit screen.
			$id = (int) $app->getUserState('com_registration.edit.registration.id');
			$this->setRedirect(Route::_('index.php?option=com_registration&view=registrationform&layout=edit&id=' . $id, false));

			$this->redirect();
		}

		// Attempt to save the data.
		$landingpage_uid = Factory::getApplication()->input->get('landingpage_uid', 0);
		if($landingpage_uid > 0){
			$data['created_by'] = $landingpage_uid;
			$landingpage_name = Factory::getApplication()->input->get('landingpage_name', '');
			$data['landingpage_name'] = $landingpage_name;
		}

		if($data['created_by'] > 0){
			$userDataBiznet = $this->getUserDataBiznet($data['created_by']);
			$arrayJSON = json_decode($userDataBiznet);
			$limitDataLP = 0;
			if($arrayJSON->userinfo->landingpage_level == 1){
				$limitDataLP = $arrayJSON->limitLPLevel1;
			}
			if($arrayJSON->userinfo->landingpage_level == 2){
				$limitDataLP = $arrayJSON->limitLPLevel2;
			}
			if($arrayJSON->userinfo->landingpage_level == 3){
				$limitDataLP = $arrayJSON->limitLPLevel3;
			}

			if($arrayJSON->currentDataLandingpage > $limitDataLP){
				$this->setRedirect(JUri::root().'agent/'.$data['created_by'].'.html#content', 'Đã vượt quá giới hạn đăng ký. Vui lòng liên hệ Hotline để được tư vấn!', 'error');
				return;
			}

		}

		$return = $model->save($data);

		// Check for errors.
		if ($return === false)
		{
			// Save the data in the session.
			$app->setUserState('com_registration.edit.registration.data', $data);

			// Redirect back to the edit screen.
			$id = (int) $app->getUserState('com_registration.edit.registration.id');
			$this->setMessage(Text::sprintf('Save failed', $model->getError()), 'warning');
			$this->setRedirect(Route::_('index.php?option=com_registration&view=registrationform&layout=edit&id=' . $id, false));
		}

		// Check in the profile.
		if ($return)
		{
			$model->checkin($return);
		}

		// Clear the profile id from the session.
		$app->setUserState('com_registration.edit.registration.id', null);

		// Redirect to the list screen.

		$landingpage_uid = Factory::getApplication()->input->get('landingpage_uid', 0);
		$landingpage_uname = Factory::getApplication()->input->get('landingpage_uname', '');

		if($landingpage_uid > 0){
			$this->setMessage(Text::_('COM_REGISTRATION_ITEM_SAVED_SUCCESSFULLY2'));
		}else{
			if($_REQUEST['Itemid'] == AGENT || $_REQUEST['Itemid'] == FOUR_ZERO_INSURACNE){
				$this->setMessage(Text::_('COM_REGISTRATION_ITEM_SAVED_SUCCESSFULLY2'));
			}else{
				$this->setMessage(Text::_('COM_REGISTRATION_ITEM_SAVED_SUCCESSFULLY'));
			}

		}
		$menu = Factory::getApplication()->getMenu();
		$item = $menu->getActive();

		$url  = (empty($item->link) ? 'index.php?option=com_registration&view=registrations' : $item->link);

		$params_link = '';
		if($data['utm_source'] != ''){
			$params_link =  "&".$data['utm_source'];
		}

		if($landingpage_uid > 0){
			//$this->setRedirect(JUri::root().'agent/'.$landingpage_uname.'.html#sp-component', Text::_('COM_REGISTRATION_ITEM_SAVED_SUCCESSFULLY2'), false);
			//$this->setRedirect(JUri::root().'cam-on-ban-da-dang-ky.html?type=user'.$params_link.'&landing='.$landingpage_uname, '', false);

			$landingpage_name = Factory::getApplication()->input->get('landingpage_name', '');
			if($landingpage_name == 'workshop2h'){
				$this->setRedirect('http://ws.b-alpha.vn/con-1-buoc-nua', '', false);
			}else{
				$this->setRedirect('https://tuyendung.b-alpha.vn/trang-cam-on', '', false);
			}

		}else{
			if($_REQUEST['Itemid'] == AGENT){
				//$this->setRedirect(JUri::root().'agent.html#sp-component', Text::_('COM_REGISTRATION_ITEM_SAVED_SUCCESSFULLY2'), false);
				//$this->setRedirect(JUri::root().'cam-on-ban-da-dang-ky.html?type=agent'.$params_link, '', false);
				$landingpage_name = Factory::getApplication()->input->get('landingpage_name', '');
				if($landingpage_name == 'workshop2h'){
					$this->setRedirect('http://ws.b-alpha.vn/con-1-buoc-nua', '', false);
				}else{
					$this->setRedirect('https://tuyendung.b-alpha.vn/trang-cam-on', '', false);
				}

			}else{
				if($_REQUEST['Itemid'] == FOUR_ZERO_INSURACNE || $_REQUEST['Itemid'] == TECH_INSURACNE || $_REQUEST['Itemid'] == FOUNDER_STORY){
					//$this->setRedirect(JUri::root().'cam-on-ban-da-dang-ky.html', Text::_('COM_REGISTRATION_ITEM_SAVED_SUCCESSFULLY2'), false);
					if($_REQUEST['Itemid'] == TECH_INSURACNE){
						//$this->setRedirect(JUri::root().'cam-on-ban-da-dang-ky.html?type=total&lpage=bhcn'.$params_link, '', false);
						$landingpage_name = Factory::getApplication()->input->get('landingpage_name', '');
						if($landingpage_name == 'workshop2h'){
							$this->setRedirect('http://ws.b-alpha.vn/con-1-buoc-nua', '', false);
						}else{
							$this->setRedirect('https://tuyendung.b-alpha.vn/trang-cam-on', '', false);
						}
					}elseif($_REQUEST['Itemid'] == FOUNDER_STORY){
						//$this->setRedirect(JUri::root().'cam-on-ban-da-dang-ky.html?type=total&lpage=ccslbca'.$params_link, '', false);
						$landingpage_name = Factory::getApplication()->input->get('landingpage_name', '');
						if($landingpage_name == 'workshop2h'){
							$this->setRedirect('http://ws.b-alpha.vn/con-1-buoc-nua', '', false);
						}else{
							$this->setRedirect('https://tuyendung.b-alpha.vn/trang-cam-on', '', false);
						}
					}else{
						//$this->setRedirect(JUri::root().'cam-on-ban-da-dang-ky.html?type=total'.$params_link, '', false);
						$landingpage_name = Factory::getApplication()->input->get('landingpage_name', '');
						if($landingpage_name == 'workshop2h'){
							$this->setRedirect('http://ws.b-alpha.vn/con-1-buoc-nua', '', false);
						}else{
							$this->setRedirect('https://tuyendung.b-alpha.vn/trang-cam-on', '', false);
						}

					}

				}else{
					$this->setRedirect(Route::_($url, false));
				}

			}

		}


		// Flush the data from the session.
		$app->setUserState('com_registration.edit.registration.data', null);
	}

	/**
	 * Method to abort current operation
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	public function cancel($key = NULL)
	{
		$app = Factory::getApplication();

		// Get the current edit id.
		$editId = (int) $app->getUserState('com_registration.edit.registration.id');

		// Get the model.
		$model = $this->getModel('RegistrationForm', 'RegistrationModel');

		// Check in the item
		if ($editId)
		{
			$model->checkin($editId);
		}

		$menu = Factory::getApplication()->getMenu();
		$item = $menu->getActive();
		$url  = (empty($item->link) ? 'index.php?option=com_registration&view=registrations' : $item->link);
		$this->setRedirect(Route::_($url, false));
	}

	/**
	 * Method to remove data
	 *
	 * @return void
	 *
	 * @throws Exception
     *
     * @since 1.6
	 */
	public function remove()
    {
        $app   = Factory::getApplication();
        $model = $this->getModel('RegistrationForm', 'RegistrationModel');
        $pk    = $app->input->getInt('id');

        // Attempt to save the data
        try
        {
            $return = $model->delete($pk);

            // Check in the profile
            $model->checkin($return);

            // Clear the profile id from the session.
            $app->setUserState('com_registration.edit.registration.id', null);

            $menu = $app->getMenu();
            $item = $menu->getActive();
            $url = (empty($item->link) ? 'index.php?option=com_registration&view=registrations' : $item->link);

            // Redirect to the list screen
            $this->setMessage(Text::_('COM_REGISTRATION_ITEM_DELETED_SUCCESSFULLY'));
            $this->setRedirect(Route::_($url, false));

            // Flush the data from the session.
            $app->setUserState('com_registration.edit.registration.data', null);
        }
        catch (Exception $e)
        {
            $errorType = ($e->getCode() == '404') ? 'error' : 'warning';
            $this->setMessage($e->getMessage(), $errorType);
            $this->setRedirect('index.php?option=com_registration&view=registrations');
        }
	}


	public function isEnableUrl()
	{
		if($_POST['url']){
			$url = $_POST['url'];
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('count(*)');
			$query->from($db->quoteName('#__whitelist'));
			$query->where($db->quoteName('url_page') . " = " . $db->quote($url));
			$query->where($db->quoteName('state') . " IN (1)");
			$db->setQuery($query);
			$result = $db->loadResult();
			if ($result) {
			echo "1";
			}else {
				echo "0";
			}
		}
		else echo "0";

	}



	public function saveLandingPage(){
		$app   = Factory::getApplication();
		$model = $this->getModel('RegistrationForm', 'RegistrationModel');
		$data = array();
		$data['name'] = $_REQUEST['name'];
		$data['email'] = $_REQUEST['email'];
		$data['phone'] = $_REQUEST['phone'];

		$utm_term = '';
		$utm_content = '';
		$variant_url = '';
		$variant_content = '';

		$utm_source = '';
		$utm_medium = '';
		$utm_campaign = '';

		if($_REQUEST['utm_source'] != ''){
			$utm_source = '&utm_source='.$_REQUEST['utm_source'];
		}

		if($_REQUEST['utm_medium'] != ''){
			$utm_medium = '&utm_medium='.$_REQUEST['utm_medium'];
		}

		if($_REQUEST['utm_campaign'] != ''){
			$utm_campaign = '&utm_campaign='.$_REQUEST['utm_campaign'];
		}

		if($_REQUEST['utm_term'] != ''){
			$utm_term = '&utm_term='.$_REQUEST['utm_term'];
		}

		if($_REQUEST['utm_content'] != ''){
			$utm_content = '&utm_content='.$_REQUEST['utm_content'];
		}

		if($_REQUEST['variant_url'] != ''){
			$variant_url = '&variant_url='.$_REQUEST['variant_url'];
		}

		if($_REQUEST['variant_content'] != ''){
			$variant_content = '&variant_content='.$_REQUEST['variant_content'];
		}

		$data['utm_source'] = $utm_source.$utm_medium.$utm_campaign.$utm_term.$utm_content.$variant_url.$variant_content;
		$data['state'] = 1;
		$data['status'] = 'new';

		$data['utm_sourceonly'] = $_REQUEST['utm_source'];
		$data['utm_mediumonly'] = $_REQUEST['utm_medium'];
		$data['utm_compainonly'] = $_REQUEST['utm_campaign'];

		$strLink = $_REQUEST['link'];
		$arrayLink = explode("?",$strLink);
		$link_from_landingpage = '';
		if($arrayLink[0] != ''){
			$link_from_landingpage = $arrayLink[0];
		}
		$data['from_landingpage'] = $link_from_landingpage;

		if($_REQUEST['link'] != ''){
			$projectID = $this->getProjectIDFromLinkLandingpage($_REQUEST['link']);
			if($projectID > 0){
				$data['landingpage_project_id'] = $projectID;
				if($arrayLink[1] != ''){
					parse_str($arrayLink[1],$arrParams);
					$data['utm_source'] = $arrParams['utm_source'];
					$data['aff_sid'] = $arrParams['aff_sid'];
				}

			}
		}

		if($_REQUEST['test'] == 1){
			if($data['name'] != '' && $data['email'] != '' && $data['phone'] != '' && $this->isPhone($data['phone'])){
				$myFile = JPATH_SITE.DS."ladipage.txt";
				$data['time'] = date("d-m-Y H:i:s");
				JFile::write($myFile, json_encode($data));
				echo "Test file ok";
			}
		}else{
			if($data['name'] != '' && $data['email'] != '' && $data['phone'] != '' && $this->isPhone($data['phone'])){

				$id_registration = $model->save($data);
				echo $id_registration;
			}
		}
		die;
	}

	public function isPhone($phone) {
		return preg_match('/^0[0-9]{9}+$/', $phone);
	}

	public function getProjectIDFromLinkLandingpage($link){
		if($link != ''){
			if (strpos($link, '.insumall.vn') !== false) {
				return 30; // Insurmall Project
			}else{
				if (strpos($link, 'https://insurance.bcavietnam.com/at') !== false) {
					return 32; // AT Project
				}else{
					if (strpos($link, 'https://insurance.bcavietnam.com/tp') !== false) {
						return 28; // Toan quoc 2
					}else{
						if (strpos($link, 'https://insurance.bcavietnam.com/nvo') !== false) {
							return 33; // Toan quoc 3
						}else{
							if (strpos($link, 'https://insurance.bcavietnam.com/cc') !== false) {
								return 34; // Toan quoc 4
							}else{
								if (strpos($link, 'https://www.xuhuongkinhdoanh.xyz/kdol') !== false) {
									return 35; // Du An KDOL
								}else{
									if (strpos($link, 'https://www.xuhuongkinhdoanh.xyz/kd-online-tp') !== false) {
										return 36; // Du An KDOL 2
									}else{
										return 0;
									}
								}
							}
						}
					}
				}
			}
		}else{
			return 0;
		}
	}


	public function getUserDataBiznet($userid){
		$data['userid'] = $userid;
		$param = $data;
		if($_SERVER['HTTP_HOST'] == 'localhost'){
				$url_biznet = 'http://localhost/biznetweb';
		}else{
			$url_biznet = 'https://biznet.com.vn';
		}

		// URL có chứa hai thông tin name và diachi
		$url = $url_biznet.'/index.php?option=com_registration&task=registrationform.landingpagePersonalUserData';
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

	public function testNoti(){
		$app   = Factory::getApplication();
		$model = $this->getModel('RegistrationForm', 'RegistrationModel');
		$listDevice[] = '5f36b9f6-3db3-44f9-b4a2-7106fa701439';
		//$listDevice[] = '7b6150a8-48c0-454a-836b-eecd70ee6aac'; // anh Dung
		//$listDevice = $this->getUserDevices(582);
		if(count($listDevice) > 0){
			$title_notification = 'Mua Data tự động';
			$content_notification = 'Bạn vừa mua 1 Liên hệ (Data) tự động';
			$page_app = '';
			$tag_key = '';
			$tag_value = '';
			$segments = array();
			$testNoti = $model->sendMessageOnesignalNotification($title_notification,$content_notification,$tag_key,$tag_value,$segments,$page_app,$listDevice);
			print_r($testNoti);
		}
		die();
	}


	public function testModel(){
		$model = $this->getModel('RegistrationForm', 'RegistrationModel');
		//$a = $model->getSaleIDCustomer('0816131959');
		$a = $model->upgradeAgentLevel();
		print_r($a);
		die;
		//$a = $model->countPhoneRegistration('0816131950');
		//$a = $model->updateDuplicateFirstBCA(38396);

		//$result = $model->remarketingHandle('0826992929', 100);
		//$model->sendMailWarningMoney(582);
		//$model->callApiAccessTradeReject('0a8c547b-e098-4f57-8df0-d1a9d75f0b00',38395);
		//echo "<pre>";
		//$aa = $model->getLuckySaleLevel2(100000);
		// $bb =  $model->checkExistPhoneRegistration('0948322279');
		// echo count($bb);
		//$bb =  $model->checkExistAccessTrade('0948322271');
		//$aa = $model->updateMoney(581,-200000);
		//var_dump($aa);
		//print_r($aa);
		//echo "</pre>";
		//die;

	}

}
