<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Registration
 * @author     nganly <lythanhngan167@gmail.com>
 * @copyright  2020 nganly
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;
if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
jimport('joomla.application.component.modelform');
jimport('joomla.event.dispatcher');
jimport('joomla.filesystem.file');
//require_once JPATH_SITE . DS.'components'.DS.'com_registration'.DS.'helpers'.DS.'GetResponseAPI3.php';
require_once JPATH_SITE . DS.'includes'.DS.'defines.php';

use \Joomla\CMS\Factory;
use \Joomla\Utilities\ArrayHelper;
use \Joomla\CMS\Language\Text;
use \Joomla\CMS\Table\Table;

/**
 * Registration model.
 *
 * @since  1.6
 */
class RegistrationModelRegistrationForm extends \Joomla\CMS\MVC\Model\FormModel
{
    private $item = null;





    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     *
     * @return void
     *
     * @since  1.6
     *
     * @throws Exception
     */
    protected function populateState()
    {
        $app = Factory::getApplication('com_registration');

        // Load state from the request userState on edit or from the passed variable on default
        if (Factory::getApplication()->input->get('layout') == 'edit')
        {
                $id = Factory::getApplication()->getUserState('com_registration.edit.registration.id');
        }
        else
        {
                $id = Factory::getApplication()->input->get('id');
                Factory::getApplication()->setUserState('com_registration.edit.registration.id', $id);
        }

        $this->setState('registration.id', $id);

        // Load the parameters.
        $params       = $app->getParams();
        $params_array = $params->toArray();

        if (isset($params_array['item_id']))
        {
                $this->setState('registration.id', $params_array['item_id']);
        }

        $this->setState('params', $params);
    }

    public function updateIDBiznet($id, $biznet_id)
    {
      $register = new stdClass();
      $register->id = $id;
      $register->biznet_id = $biznet_id;
      // Insert the object into the user profile table.
      $result = JFactory::getDbo()->updateObject('#__registration', $register, 'id');
      if ($result == true) {
        return true;
      }
    }

    /**
     * Method to get an ojbect.
     *
     * @param   integer $id The id of the object to get.
     *
     * @return Object|boolean Object on success, false on failure.
     *
     * @throws Exception
     */
    public function getItem($id = null)
    {
        if ($this->item === null)
        {
            $this->item = false;

            if (empty($id))
            {
                    $id = $this->getState('registration.id');
            }

            // Get a level row instance.
            $table = $this->getTable();

            if ($table !== false && $table->load($id))
            {
                $user = Factory::getUser();
                $id   = $table->id;



				if ($id)
				{
					$canEdit = $user->authorise('core.edit', 'com_registration.registration.' . $id) || $user->authorise('core.create', 'com_registration.registration.' . $id);
				}
				else
				{
					$canEdit = $user->authorise('core.edit', 'com_registration') || $user->authorise('core.create', 'com_registration');
				}

                if (!$canEdit && $user->authorise('core.edit.own', 'com_registration.registration.' . $id))
                {
                        $canEdit = $user->id == $table->created_by;
                }

                if (!$canEdit)
                {
                        throw new Exception(Text::_('JERROR_ALERTNOAUTHOR'), 403);
                }

                // Check published state.
                if ($published = $this->getState('filter.published'))
                {
                        if (isset($table->state) && $table->state != $published)
                        {
                                return $this->item;
                        }
                }

                // Convert the JTable to a clean JObject.
                $properties = $table->getProperties(1);
                $this->item = ArrayHelper::toObject($properties, 'JObject');



            }
        }

        return $this->item;
    }

    /**
     * Method to get the table
     *
     * @param   string $type   Name of the JTable class
     * @param   string $prefix Optional prefix for the table class name
     * @param   array  $config Optional configuration array for JTable object
     *
     * @return  JTable|boolean JTable if found, boolean false on failure
     */
    public function getTable($type = 'Registration', $prefix = 'RegistrationTable', $config = array())
    {
        $this->addTablePath(JPATH_ADMINISTRATOR . '/components/com_registration/tables');

        return Table::getInstance($type, $prefix, $config);
    }

    /**
     * Get an item by alias
     *
     * @param   string $alias Alias string
     *
     * @return int Element id
     */
    public function getItemIdByAlias($alias)
    {
        $table      = $this->getTable();
        $properties = $table->getProperties();

        if (!in_array('alias', $properties))
        {
                return null;
        }

        $table->load(array('alias' => $alias));

            return $table->id;

    }

    /**
     * Method to check in an item.
     *
     * @param   integer $id The id of the row to check out.
     *
     * @return  boolean True on success, false on failure.
     *
     * @since    1.6
     */
    public function checkin($id = null)
    {
        // Get the id.
        $id = (!empty($id)) ? $id : (int) $this->getState('registration.id');

        if ($id)
        {
            // Initialise the table
            $table = $this->getTable();

            // Attempt to check the row in.
            if (method_exists($table, 'checkin'))
            {
                if (!$table->checkin($id))
                {
                    return false;
                }
            }
        }

        return true;

    }

    /**
     * Method to check out an item for editing.
     *
     * @param   integer $id The id of the row to check out.
     *
     * @return  boolean True on success, false on failure.
     *
     * @since    1.6
     */
    public function checkout($id = null)
    {
        // Get the user id.
        $id = (!empty($id)) ? $id : (int) $this->getState('registration.id');

        if ($id)
        {
            // Initialise the table
            $table = $this->getTable();

            // Get the current user object.
            $user = Factory::getUser();

            // Attempt to check the row out.
            if (method_exists($table, 'checkout'))
            {
                if (!$table->checkout($user->get('id'), $id))
                {
                    return false;
                }
            }
        }

        return true;

    }

    /**
     * Method to get the profile form.
     *
     * The base form is loaded from XML
     *
     * @param   array   $data     An optional array of data for the form to interogate.
     * @param   boolean $loadData True if the form is to load its own data (default case), false if not.
     *
     * @return    JForm    A JForm object on success, false on failure
     *
     * @since    1.6
     */
    public function getForm($data = array(), $loadData = true)
    {
        // Get the form.
        $form = $this->loadForm('com_registration.registration', 'registrationform', array(
                        'control'   => 'jform',
                        'load_data' => $loadData
                )
        );

        if (empty($form))
        {
                return false;
        }

        return $form;
    }

    /**
     * Method to get the data that should be injected in the form.
     *
     * @return    mixed    The data for the form.
     *
     * @since    1.6
     */
    protected function loadFormData()
    {
        $data = Factory::getApplication()->getUserState('com_registration.edit.registration.data', array());

        if (empty($data))
        {
            $data = $this->getItem();
        }

		// Support for multiple or not foreign key field: province
		$array = array();

		foreach ((array) $data->province as $value)
		{
			if (!is_array($value))
			{
				$array[] = $value;
			}
		}
		if(!empty($array)){

		$data->province = $array;
		}
		// Support for multiple or not foreign key field: status
		$array = array();

		foreach ((array) $data->status as $value)
		{
			if (!is_array($value))
			{
				$array[] = $value;
			}
		}
		if(!empty($array)){

		$data->status = $array;
		}

        return $data;
    }

    /**
     * Method to save the form data.
     *
     * @param   array $data The form data
     *
     * @return bool
     *
     * @throws Exception
     * @since 1.6
     */


     public function saveCustomer($data)
     {

       $customer = new stdClass();
       $customer->name = $data['name'];
       $customer->email = $data['email'];
       $customer->phone = $data['phone'];
       $customer->province = $data['province'];
       $customer->from_landingpage = $data['from_landingpage'];

       JFactory::getDbo()->insertObject('#__registration', $customer);
     }


    public function save($data)
    {
        $id    = (!empty($data['id'])) ? $data['id'] : (int) $this->getState('registration.id');
        $state = (!empty($data['state'])) ? 1 : 0;
        $user  = Factory::getUser();
        if ($id)
        {
            // Check the user can edit this item
            $authorised = $user->authorise('core.edit', 'com_registration.registration.' . $id) || $authorised = $user->authorise('core.edit.own', 'com_registration.registration.' . $id);
        }
        else
        {
            // Check the user can create new items in this section
            $authorised = $user->authorise('core.create', 'com_registration');
        }

        if ($authorised !== true)
        {
            throw new Exception(Text::_('JERROR_ALERTNOAUTHOR'), 403);
        }

        $table = $this->getTable();
        $send_mail_to_sale = 0;
        $mail_sale = '';
        $paramsEmail = array();
        $customer_exist = $this->checkExistPhone($data['phone']);
        if($customer_exist->sale_id > 0){
          $userSale = Factory::getUser($customer_exist->sale_id);
        }
        $data['againt_registration'] = 0;
        if($data['phone'] != ''){
          //test here
          // $luckySale = $this->getLuckySale(100000);
          // echo "<pre>";
          // print_r($luckySale);
          // echo "</pre>";
          // die;
          $customer_exist = $this->checkExistPhone($data['phone']);
          if($customer_exist->id > 0){
   				     $data['is_exist'] = 1;
               $create_date_int = strtotime($customer_exist->create_date);
               $create_date_str = date('d-m-Y',$create_date_int);
               $today_date_str = date('d-m-Y',time());
               if($create_date_str == $today_date_str){
                 $send_mail_to_sale = 0;
                 $data['againt_registration'] = 0;
               }else{
                 $send_mail_to_sale = 1;
                 $data['againt_registration'] = 1;
                 $paramsEmail = array(
                     'subject' => 'B-Alpha - Khách hàng Đăng ký lại',
                     'name_sale' => $userSale->name,
                     'name_customer' => $customer_exist->name,
                     'phone_customer' => $customer_exist->phone,
                 );
               }
     			 }else{
     				 $data['is_exist'] = 0;
             $data['status'] = 'converted';

     			 }
        }

        if($data['utm_source'] != ''){
          parse_str($data['utm_source'],$arrUTM);
          $data['utm_sourceonly'] = $arrUTM['utm_source'];
          $data['utm_mediumonly'] = $arrUTM['utm_medium'];
          $data['utm_compainonly'] = $arrUTM['utm_campaign'];
        }

        if($_REQUEST['Itemid'] == TECH_INSURACNE){
          $data['from_landingpage'] = 'bao-hiem-cong-nghe';
        }
        if($_REQUEST['Itemid'] == FOUNDER_STORY){
          $data['from_landingpage'] = 'cau-chuyen-nha-sang-lap-bca';
        }
        if($_REQUEST['Itemid'] == FOUR_ZERO_INSURACNE){
          $data['from_landingpage'] = 'bao-hiem-40';
        }
        // if($_REQUEST['Itemid'] == AGENT){
        //   $data['from_landingpage'] = 'bao-hiem-40';
        // }

        $tranferBiznet = TRANFER_BIZNET;
        //$bizappco = 1;
        if($bizappco == 1) { // test luu file
          $tranferBiznet = 0;
          $data['from_website'] = 'bcavietnam.com';
          $data['test'] = 1;
          //unset($data['status']);
          $data['status'] = 'new';
          $param = $data;

          if($_SERVER['HTTP_HOST'] == 'localhost'){
              $url_biznet = 'http://localhost/biznetweb';
          }else{
            $url_biznet = 'https://biznet.com.vn';
          }

          // URL có chứa hai thông tin name và diachi
          $url = $url_biznet.'/index.php?option=com_registration&task=registrationform.saveCustomer';
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
          echo $result;
          die();
        }

        if ($table->save($data) === true)
        {

            $params_project_id = 0;
            if($data['created_by'] > 0){
              $params_project_id = 22; // Data Landingpage Member

            }else{
              if($_REQUEST['Itemid'] == AGENT){
                $params_project_id = 22; // Data Landingpage Member
              }
              else{
                $params_project_id = 21; // data Landingpage Vietnam
                if($data['landingpage_project_id'] > 0){
                  $params_project_id = $data['landingpage_project_id'];
                }
              }
            }
            if($params_project_id > 0){
              $this->updateProjectID($table->id, $params_project_id);
            }

            if($params_project_id != AT_PROJECT && $data['is_exist'] == 1){ // All Project
              //$countPhoneTpProject = $this->countPhoneRegistrationTpProject($data['phone']);
              $countPhoneProject = $this->countPhoneRegistrationProject($data['phone'],$params_project_id);
              if($countPhoneProject == 1){ // All Project trung lan dau
                $this->updateDuplicateFirstBCA($table->id);
              }
            }

            if($params_project_id == AT_PROJECT){ // AT Project
              $projectInfo = $this->getProjectByID($params_project_id); //Landingpage Data
              $price_data = $projectInfo['price'];

              if($data['aff_sid'] != ''){
                $tracking_id = $data['aff_sid'];
              }else{
                $tracking_id = '';
              }

              if($data['is_exist'] == 0){
                $cat_data = 'khach_hang_moi';
              }else{
                if($customer_exist->project_id == AT_PROJECT){ // AT Project
                  $cat_data = 'khach_hang_da_ton_tai_AT';
                }else{
                  $cat_data = 'khach_hang_da_ton_tai_BCA'; // 50% money
                }


              }
              $uuid_key = Factory::gen_uuid();
              $countPhone = 0;
              if($cat_data == 'khach_hang_da_ton_tai_BCA'){
                $countPhone = $this->countPhoneRegistration($data['phone']);
                if($countPhone == 1){ // AT Project trung lan dau 50% gia tien
                  $this->updateDuplicateFirstBCA($table->id);
                  //$customerSale = $this->getSaleIDCustomer($data['phone']);
                  // if($customerSale->sale_id > 0){
                  //   $moneyRemarketing = $price_data/2;
                  //   $updateMoney = $this->updateMoney($customerSale->sale_id, $moneyRemarketing);
                  //   if($updateMoney){
                  //     $this->saveHistoryRemarketingATProject($customerSale->sale_id, $moneyRemarketing, $data['phone']);
                  //   }
                  // }
                }
              }

              if ($_SERVER['HTTP_HOST'] != "localhost") {
                $resultAT = $this->callApiAccessTrade($uuid_key, $table->id, $tracking_id, $cat_data, $price_data);
                if($cat_data == 'khach_hang_da_ton_tai_AT' && $resultAT == 1){
                  $resultATReject = $this->callApiAccessTradeReject($uuid_key,$table->id);
                }
                if($cat_data == 'khach_hang_da_ton_tai_BCA'){
                  if($countPhone >= 2){ // Huy AT Project neu  trung lan 2 tro di
                    $resultATReject2 = $this->callApiAccessTradeReject($uuid_key,$table->id);
                  }
                }
              }

            }

            if($tranferBiznet == 1){
              $data['from_website'] = 'bcavietnam.com';
              //unset($data['status']);
              $data['status'] = 'new';
              $data['token'] = 'd3b0741683075e7565cf0e91208f1aa6';
              $param = $data;
              if($_SERVER['HTTP_HOST'] == 'localhost'){
                  $url_biznet = 'http://localhost/biznetweb';
              }else{
                $url_biznet = 'https://biznet.com.vn';
              }
              // URL có chứa hai thông tin name và diachi
              $url = $url_biznet.'/index.php?option=com_registration&task=registrationform.saveCustomer';
              // Khởi tạo CURL
              $ch = curl_init($url);
              // Thiết lập có return
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
              // Thiết lập sử dụng POST
              curl_setopt($ch, CURLOPT_POST, count($param));
              // Thiết lập các dữ liệu gửi đi
              curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
              $biznet_id = curl_exec($ch);
              $this->updateIDBiznet($table->id,$biznet_id);
              curl_close($ch);


            }

            if($data['is_exist'] == 0){
              JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_project/models', 'ProjectModel');
              $modelProject = JModelLegacy::getInstance('Projectss', 'ProjectModel', array('ignore_request' => true));
              $params = array();
              $paramsGetRespone = array();
              $params['name'] = $data['name'];
              $paramsGetRespone['name'] = $data['name'];

              $params['phone']	= $data['phone'];
              $paramsGetRespone['mobile_phone']	= $data['phone'];

              $params['email']	= $data['email'];
              $paramsGetRespone['email']	= $data['email'];

              //Data Center List
              $paramsGetRespone['campaign']['campaignId'] = "WtVsd";

              $params['place'] = '';
              $params['province'] = $data['province'];

              if($data['created_by'] > 0){
                $params['project_id'] = 22; // Data Landingpage Member
                $params['from_landingpage'] = $data['created_by'];

              }else{
                if($_REQUEST['Itemid'] == AGENT){
                  $params['project_id'] = 22; // Data Landingpage Member
                }
                else{
                  $params['project_id'] = 21; // data Landingpage Vietnam
                  if($data['landingpage_project_id'] > 0){
                    $params['project_id'] = $data['landingpage_project_id'];
                  }
                }
              }

              $params['category_id'] = 151;

              //get and set autobuy data
              // $myFile = JPATH_SITE.DS."random_data.txt";
              // $currentIndex = JFile::read($myFile);
              //
              // if($currentIndex == 1){
              //   $randomIndex = 1;
              //   $contentIndex = 2;
              // 	JFile::write($myFile, $contentIndex);
              // }elseif($currentIndex == 2){
              //   $randomIndex = 2;
              //   $contentIndex = 1;
              // 	JFile::write($myFile, $contentIndex);
              // }

              $randomIndex = 2;

              $params['sale_id'] = 0;
              if($randomIndex == 1){ // cho vao data center
                $params['sale_id'] = 0;
              }elseif($randomIndex == 2){ // gán tự động
                $price_data = 0;
                // Landingpage Toan Quoc, Insumall, AT, Toan Quoc 2,3,4, Du An KDOL, KDOL 2
                if($params['project_id'] == 21 || $params['project_id'] == 28
                || $params['project_id'] == 30 || $params['project_id'] == AT_PROJECT
                || $params['project_id'] == 33 || $params['project_id'] == 34
                || $params['project_id'] == 35 || $params['project_id'] == 36){

                  $projectInfo = $this->getProjectByID($params['project_id']); //Project Info
                  $price_data = $projectInfo['price'];
                  $params['price_data'] = $price_data;
                  $luckySaleId = 0;
                  if($params['project_id'] == AT_PROJECT){ // AT Project , Level 2
                    // $luckySaleLevel2 = $this->getLuckySaleLevel2($price_data);
                    // $luckySaleId = $luckySaleLevel2->id;
                    $luckySaleId = 0;
                  }else{
                    if($params['project_id'] == 35 || $params['project_id'] == 36){ // Du An KDOL, KDOL 2 , Level 3
                      $luckySaleLevel3 = $this->getLuckySaleLevel3($price_data);
                      $luckySaleId = $luckySaleLevel3->id;
                    }else{
                      // Landingpage Toan Quoc, Insumall, Level 1, Toan Quoc 2,3,4
                      $luckySale1 = $this->getLuckySale1($price_data);
                      if($luckySale1->id > 0){
                        $luckySaleId = $luckySale1->id;
                      }else{
                        $luckySale2 = $this->getLuckySale2($price_data);
                        $luckySaleId = $luckySale2->id;
                      }
                    }

                  }


                  if($luckySaleId > 0){
                    // $buyToday = $modelProject->getCountCustomerDataToday($luckySaleId,$params['project_id'], $params['category_id']);
                    //
                    // $level = $luckySale->level;
                    // $maxPick = $modelProject->getMaxPickByCat($params['category_id'],$level);

                    //if($buyToday < $maxPick){
                      // $moneySale = $this->getMoney($luckySaleId);
                      // if($moneySale >= $price_data){
                      //   $params['sale_id'] =  $luckySaleId;
                      // }else{
                      //   $params['sale_id'] = 0;
                      // }
                    //}
                    $moneySale = $this->getMoney($luckySaleId);
                    if($moneySale >= $price_data){
                      $params['sale_id'] =  $luckySaleId;
                    }else{
                      $params['sale_id'] = 0;
                    }
                  }
                }
              }
              $params['regis_id'] = $table->id;
              $doneCustomer = $this->createCustomer($params);

              if($doneCustomer){
                //update buyall field
                if($params['sale_id'] > 0){
                  $modelProject->updateBuyAll($params['sale_id'], 1);
                  $this->upgradeAgentLevel($params['sale_id']);

                  if ($_SERVER['HTTP_HOST'] != "localhost") {
                    $listDevice = $this->getUserDevices($params['sale_id']);
                    $listDevice[] = '5f36b9f6-3db3-44f9-b4a2-7106fa701439'; // nganly
                    $listDevice[] = '6a4311ef-c702-4fdc-ac37-cdc9d73b7359'; // anh Dung

                    //$listDevice = $this->getUserDevices(582);
                    if(count($listDevice) > 0){
                      $title_notification = 'Mua Data tự động';
                			$content_notification = 'Bạn vừa mua 1 Liên hệ (Data) tự động';
                			$page_app = '';
                			$tag_key = '';
                			$tag_value = '';
                      $segments = array();
                      $this->sendMessageOnesignalNotification($title_notification,$content_notification,$tag_key,$tag_value,$segments,$page_app,$listDevice);
                    }
                  }

                }
                // $getRespone = new GetResponse('wpzitbc71t96gsv9ng6k2uu0g2iux9md','https://api.getresponse.com/v3');
                // $getRespone->addContact($paramsGetRespone);
              }


            }else{
              if($send_mail_to_sale == 1 && $userSale->email != ''){
                if($data['created_by'] > 0){
                  $project_id_send_email = 22; // Data Landingpage Member
                }else{
                  if($_REQUEST['Itemid'] == AGENT){
                    $project_id_send_email = 22; // Data Landingpage Member
                  }
                  else{
                    $project_id_send_email = 21; // data Landingpage Vietnam
                    if($data['landingpage_project_id'] > 0){
                      $project_id_send_email = $data['landingpage_project_id'];
                    }
                  }
                }
                if($project_id_send_email > 0){
                  $projectInfo = $this->getProjectByID($project_id_send_email); //Landingpage Data
                  $paramsEmail['project_name'] = $projectInfo['title'];
                }

                $this->_sendMail('againt_registration', $userSale->email, $paramsEmail);

              }
            }
            return $table->id;
        }
        else
        {
            return false;
        }

    }

    public function upgradeAgentLevel($user_id)
  	{
  			$user   = $this->getUserByID($user_id);
        $config = new JConfig();
        $buyall = 0;
        $to_level = 0;
        $current_level = 0;
    		if($config->numberDataLevel1 != ''){
    			$arrFromTo1 = explode("-",$config->numberDataLevel1);
    		}
    		if($config->numberDataLevel2 != ''){
    			$arrFromTo2 = explode("-",$config->numberDataLevel2);
    		}
    		if($config->numberDataLevel3 != ''){
    			$arrFromTo3 = explode("-",$config->numberDataLevel3);
    		}
        $buyall = (int)$user->buyall;
    		if($buyall >= (int)$arrFromTo1[0] && $buyall <= (int)$arrFromTo1[1]){
    			$to_level = 1;
    		}
    		if($buyall >= (int)$arrFromTo2[0] && $buyall <= (int)$arrFromTo2[1]){
    			$to_level = 2;
    		}
    		if($buyall >= (int)$arrFromTo3[0] && $buyall <= (int)$arrFromTo3[1]){
    			$to_level = 3;
    		}

        $current_level = $user->level;
        //if(($to_level > 0) && ($current_level < $to_level) && ($current_level == 1 || $current_level == 2)){
  			if(($to_level > 0) && ($current_level != $to_level) && ($current_level == 1 || $current_level == 2 || $current_level == 3)){ // loai tru level 4,5
          $object = new stdClass();
  				$object->id = $user_id;
  				$object->level = $to_level;
  				$object->upgraded_date = date("Y-m-d H:i:s");
  				$result = JFactory::getDbo()->updateObject('#__users', $object, 'id');
          if($result){
    				$log 				= new stdClass();
    				$log->type			= LEVEL_UPDATE;
    				$log->created_by 	= (int)$user_id;
    				$log->modified_by 	= (int)$user_id;
    				$log->status		= 1;
    				$log->user_id		= (int)$user_id;
    				$log->old_level	= $current_level;
    				$log->new_level	= $to_level;
    				$log->state			= 1;
    				$log->created_date	= date('Y-m-d H:i:s');
    				$result2 = JFactory::getDbo()->insertObject('#__userlogs', $log);
    			}
  			}

  	}

    /**
     * Method to delete data
     *
     * @param   int $pk Item primary key
     *
     * @return  int  The id of the deleted item
     *
     * @throws Exception
     *
     * @since 1.6
     */
    public function delete($pk)
    {
        $user = Factory::getUser();


            if (empty($pk))
            {
                    $pk = (int) $this->getState('registration.id');
            }

            if ($pk == 0 || $this->getItem($pk) == null)
            {
                    throw new Exception(Text::_('COM_REGISTRATION_ITEM_DOESNT_EXIST'), 404);
            }

            if ($user->authorise('core.delete', 'com_registration.registration.' . $id) !== true)
            {
                    throw new Exception(Text::_('JERROR_ALERTNOAUTHOR'), 403);
            }

            $table = $this->getTable();

            if ($table->delete($pk) !== true)
            {
                    throw new Exception(Text::_('JERROR_FAILED'), 501);
            }

            return $pk;

    }

    /**
     * Check if data can be saved
     *
     * @return bool
     */
    public function getCanSave()
    {
        $table = $this->getTable();

        return $table !== false;
    }


    public function checkExistPhone($phone)
  	{
  		$db = JFactory::getDbo();
  		$query = $db->getQuery(true);
  		$query->select('*');
  		$query->from($db->quoteName('#__customers'));
  		$query->where($db->quoteName('phone') . " = '" .$phone."'");
  		$query->where($db->quoteName('state') . " = 1");
  		$query->order('id DESC');
  		$query->setLimit(1);
  		$db->setQuery($query);
  		$result = $db->loadObject();
      return $result;

  	}



    public function createCustomer($params){

  		$db = JFactory::getDbo();
  		$query = "SELECT * FROM #__customers WHERE phone = '".$params['phone']."' AND state = 1";
  		$db->setQuery($query);
  		$phone = $db->loadObject();
  		$existPhone = 0;
  		if($phone->id > 0){
  			$existPhone = 1;
  		}

  		if($existPhone == 0 ){

  			$queryInsert = $db->getQuery(true);
  			$time_created = date("Y-m-d H:i:s");

  			$customer = new stdClass();
        if($params['sale_id'] > 0){
          $customer->sale_id = $params['sale_id'];
          $customer->buy_date = $time_created;
        }else{
          $customer->sale_id = 0;
        }
  			$customer->name = $params['name'];
  			$customer->phone = $params['phone'];
  			$customer->email = $params['email'];
  			$customer->place = $params['place'];
        $customer->province = $params['province'];
  			$customer->project_id = $params['project_id'];
  			$customer->category_id = $params['category_id'];
  			$customer->reference_id = $params['reference_id'];
  			$customer->reference_type = $params['reference_type'];
        $customer->from_landingpage = $params['from_landingpage'];

  			$customer->status_id = 1;
  			$customer->modified_date = $time_created;
  			$customer->create_date = $time_created;
        $customer->state = 1;
        $customer->regis_id = $params['regis_id'];

  			// $columns = array('name', 'phone', 'email', 'place','province', 'sale_id', 'project_id', 'category_id', 'status_id', 'modified_date', 'create_date','state');
  			// $values = array($db->quote($customer->name), $db->quote($customer->phone), $db->quote($customer->email), $db->quote($customer->place), $db->quote($customer->province), $db->quote($customer->sale_id), $db->quote($customer->project_id), $db->quote($customer->category_id), $db->quote($customer->status_id), $db->quote($customer->modified_date), $db->quote($customer->create_date),1);
  			// $queryInsert
  			// 	->insert($db->quoteName('#__customers'))
  			// 	->columns($db->quoteName($columns))
  			// 	->values(implode(',', $values));
  			// $db->setQuery($queryInsert);
        // $db->execute();
        // $idCustomer = JFactory::getDbo()->insertid();

        $result = JFactory::getDbo()->insertObject('#__customers', $customer);
        if ($result) {
            $idCustomer = JFactory::getDbo()->insertid();
        }

        if($params['sale_id'] > 0){

          $data = array();
          $quantity = 1;
          $data['created_by'] = $params['sale_id'];
          $data['category_id'] = $params['category_id'];
          $data['price'] = $params['price_data'];
          $data['quantity'] = $quantity;
          $data['project_id'] = $params['project_id'];
          $arrCustomer = array();
          $arrCustomer[] = $idCustomer;
          $list_customer = implode(",", $arrCustomer);
          $data['list_customer'] = $list_customer;

          JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_project/models', 'ProjectModel');
          $modelProject = JModelLegacy::getInstance('Projectss', 'ProjectModel', array('ignore_request' => true));
          $order_id = $modelProject->createOrder($data);
          // echo "<pre>";
          // print_r($data);
          // print_r($arrCustomer);
          // echo $order_id;
          // echo "</pre>";
          // die;
          if ($order_id) {
              // Add history

              $totalPrice = $params['price_data'];
              $obj = new stdClass();
              $obj->state = 1;
              $obj->created_by = $params['sale_id'];
              $obj->title = 'Mua Data tự động #' . $order_id;
              $obj->amount = 0 - $totalPrice;
              $obj->created_date = date('Y-m-d H:i:s');
              $obj->type_transaction = 'buydata';
              $obj->status = 'completed';
              $obj->reference_id = $order_id;

              if($params['sale_id'] > 0){
                $userSaleHistory   = $this->getUserByID($params['sale_id']);
                $obj->current_money = $userSaleHistory->money - $totalPrice;
                $obj->current_money_before_operation = $userSaleHistory->money;
              }

              $db = JFactory::getDbo();
              $db->insertObject('#__transaction_history', $obj, 'id');

              // Descrease money
              $sql = "UPDATE #__users set money = money - " . $totalPrice . ' WHERE id = ' . $params['sale_id'];
              $db->setQuery($sql)->execute();
              //$modelProject->updateBuyToday($params['sale_id'], $quantity);
              $userSale = JFactory::getUser($params['sale_id']);
              $strtime_buydate =  strtotime($userSale->buydate);
              $buydate = date("Y-m-d H:i:s");
              $quantity = 1;
              if(date("Y-m-d",$strtime_buydate) != date('Y-m-d')){
                $sql = "UPDATE #__users set buytoday = " . $quantity . " , buydate = '".$buydate."' WHERE id = " . $params['sale_id'];
                $db->setQuery($sql)->execute();
              }else{
                $sql = "UPDATE #__users set buytoday = buytoday + " . $quantity . " , buydate = '".$buydate."' WHERE id = " . $params['sale_id'];
                $db->setQuery($sql)->execute();
              }

              return 1;
          }else{
            return 0;
          }

        }

  		}else{
  			return 0;
  		}
  	}


    private function _sendMail($type, $recipient, $params)
    {
        $mailer = JFactory::getMailer();
        $config = JFactory::getConfig();
        $sender = array(
            $config->get('mailfrom'),
            $config->get('fromname')
        );
        $mailer->setSender($sender);
        $mailer->addRecipient($recipient);
        $mailer->isHtml(true);

        $body = $this->_getTemplate($type, $params);
        $mailer->setSubject($params['subject']);
        $mailer->setBody($body);
        try{
            $mailer->Send();
            return true;
        } catch (Exception $e){
            return false;
        }

    }

    private function _getTemplate($type, $params)
    {
        $message = '';
        switch ($type) {
            case 'againt_registration':
                $message = "<p>Chào " . $params['name_sale'] . ",</p>";
                $message .= "<p>Bạn vừa có một Khách hàng Đăng ký lại.</p>";
                $message .= "<p>Thông tin Khách hàng vừa Đăng ký lại:</p>
                <p>Tên: <b>{$params['name_customer']}</b></p>
                <p>Số điện thoại: <b><a href=\"tel:{$params['phone_customer']}\">{$params['phone_customer']}</a></b></p>";
                $message .= "<p>Dự án: {$params['project_name']}</p>";
                $message .= "<p>Cảm ơn!</p>";
                $message .= "<p><b>B-Alpha</b></p>";
                break;
            case 'low_money':
              $message = '<p>Chào '.$params['name'].'</p>';
              $message .= '<p>Cảnh báo: Tài khoản của bạn còn dưới <b style="color:red">400.000</b> Bizxu</p>';
              $message .= "<p><b>B-Alpha</b></p>";
              break;
        }
        return $message;
    }

    public function getLuckySale1($price_data)	{
  				$db = JFactory::getDbo();
  				$query = $db->getQuery(true);
  				$query->select('us.id,us.name,us.username,us.id_biznet,us.level,us.money,us.autobuy,us.buyall,us.buytoday,us.buydate,us.registerDate')
  					->from('#__users AS us')
  					->join('LEFT', '#__user_usergroup_map AS ug ON ug.user_id = us.id')
  					->where("ug.group_id = 3")
  					->where("us.level  IN (1)")
  					->where("us.block = 0")
  					->where("us.autobuy = 1")
  					->where("us.money >= ".(int)$price_data)
  					->where("us.buyall < 2")
  					->order("us.buyall ASC")
  					->order("us.id ASC")
  					->setLimit(1);
  				$db->setQuery($query);
  				//echo $query->__toString();
  				$result = $db->loadObject();
  				return $result;
  	}

    public function getLuckySale2($price_data)
  	{
      $db = JFactory::getDbo();
      $query = $db->getQuery(true);
      $query->select('us.*')
        ->from('#__users AS us')
        ->join('LEFT', '#__user_usergroup_map AS ug ON ug.user_id = us.id')
        ->where("ug.group_id = 3")
        ->where("us.level  IN (1)")
        ->where("us.block = 0")
        ->where("us.autobuy = 1")
        ->where("us.buyall >= 2")
        ->where("us.money >= ".(int)$price_data)
        ->where("DATE_FORMAT(us.buydate,'%Y-%m-%d') <= '".date("Y-m-d")."'")
        ->order("us.buydate ASC")
        ->setLimit(1);
      $db->setQuery($query);
      $result = $db->loadObject();
      return $result;

  	}

    public function getLuckySaleLevel2($price_data)
  	{
      $db = JFactory::getDbo();
      $query = $db->getQuery(true);
      $query->select('us.*')
        ->from('#__users AS us')
        ->join('LEFT', '#__user_usergroup_map AS ug ON ug.user_id = us.id')
        ->where("ug.group_id = 3")
        ->where("us.level  IN (2)")
        ->where("us.block = 0")
        ->where("us.autobuy = 1")
        ->where("us.money >= ".(int)$price_data)
        ->where("DATE_FORMAT(us.buydate,'%Y-%m-%d') <= '".date("Y-m-d")."'")
        ->order("us.buydate ASC")
        ->order("us.id ASC")
        ->setLimit(1);
      $db->setQuery($query);
      //echo $query->__toString();
      $result = $db->loadObject();
      return $result;
  	}

    public function getLuckySaleLevel3($price_data)
  	{
      $db = JFactory::getDbo();
      $query = $db->getQuery(true);
      $query->select('us.*')
        ->from('#__users AS us')
        ->join('LEFT', '#__user_usergroup_map AS ug ON ug.user_id = us.id')
        ->where("ug.group_id = 3")
        ->where("us.level  IN (3)")
        ->where("us.block = 0")
        ->where("us.autobuy = 1")
        ->where("us.money >= ".(int)$price_data)
        ->where("DATE_FORMAT(us.buydate,'%Y-%m-%d') <= '".date("Y-m-d")."'")
        ->order("us.buydate ASC")
        ->order("us.id ASC")
        ->setLimit(1);
      $db->setQuery($query);
      //echo $query->__toString();
      $result = $db->loadObject();
      return $result;
  	}

    public function getProjectByID($project_id)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('is_recruitment,price,title');
        $query->from($db->quoteName('#__projects'));
        $query->where($db->quoteName('id') . " = " . $project_id);
        $query->where($db->quoteName('state') . " = 1");
        $db->setQuery($query);
        $result = $db->loadAssoc();
        return $result;
    }

    public function getMoney($uid)
    {
        return JFactory::getDbo()->setQuery('SELECT money FROM #__users WHERE id = ' . $uid)->loadResult();
    }

    public function getUserDevices($user_id){
  		$db = JFactory::getDbo();
  		$query = $db->getQuery(true);
  		$query->select('ud.device_id');
  		$query->from('`#__user_devices` AS ud');
  		$query->where('ud.`user_id` = '.$user_id);
  		$db->setQuery($query);
  		$results = $db->loadObjectList();
      $arrayDevicesID = array();
      if(count($results) > 0){
        foreach ($results as $key => $row) {
          $arrayDevicesID[] = $row->device_id;
        }
      }
  		return $arrayDevicesID;
  	}


    public function sendMessageOnesignalNotification($title,$content,$tag_key,$tag_value,$segments,$page_app,$arrayDevicesID){
  			$content = array(
  				"vi" => $content,
  				"en" => $content
  				);
  			$headings = array(
  				"vi" => $title,
  				"en" => $title
  				);
  			// $daTags = array(
        // array("key" => $tag_key, "relation" => "=", "value" => $tag_value),
        // );
  			// $filters = array(
  			//     array("field" => "tag", "key" => $tag_key, "relation" => "=", "value" => $tag_value),
  			// );
  			$app_url = 'ebiznet://bizappco/CustomerPage';

  			$rest_api_key = REST_API_KEY;
  			$fields = array(
  				'app_id' => APP_ID,
  				'include_player_ids' =>  $arrayDevicesID,
  				//'included_segments' => $segments,
  				'data' => array("foo" => "bar"),
  				//'filters' => array(array("field" => "tag", "key" => "user_type", "relation" => "equal", "value" => "factory")),
  				//"tags" => $daTags,
  				//"filters" => $filters,
  				'app_url' => $app_url,
  				'contents' => $content,
  				'headings' => $headings
  			);

  			$fields = json_encode($fields);

  			$ch = curl_init();
  			curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
  			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8','Authorization: Basic ' . $rest_api_key));
  			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
  			curl_setopt($ch, CURLOPT_HEADER, FALSE);
  			curl_setopt($ch, CURLOPT_POST, TRUE);
  			curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
  			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

  			$response = curl_exec($ch);
  			curl_close($ch);
  			return $response;
  	}

    public function callApiAccessTrade($uuid_key, $registrationId, $tracking_id, $cat_data, $price = 100000)
  	{
      // $registrationId = 38383;
  		// $tracking_id = '9872394-ngan';
  		// $cat_data = 'khach_hang_da_ton_tai';
  		// $price = 100000;
  		$config = new JConfig();

  		if($cat_data == 'khach_hang_da_ton_tai_BCA'){
  			$category_name = "Khách hàng đã tồn tại BCA";
  			$category_id = 'khach_hang_da_ton_tai_BCA';

  		}elseif($cat_data == 'khach_hang_da_ton_tai_AT'){
        $category_name = "Khách hàng đã tồn tại AT";
  			$category_id = 'khach_hang_da_ton_tai_AT';

  		}else{
        $category_name = "Khách hàng mới";
  			$category_id = "khach_hang_moi";

      }

  		$data = array(
  			"conversion_id" => $uuid_key,
  			"conversion_result_id" => '30',
  			"tracking_id" => $tracking_id,
  			"transaction_id" => $uuid_key,
  			"transaction_time" => date("Y-m-d H:i:s") ,
  			"transaction_value" => 0,
  			"extra" => array(
  				"cus_id" => $registrationId
  			),
  			//"is_cpql" => 1,
  			"items" => array (
  				array(
  				"id" => $uuid_key,
  				"sku" => $uuid_key,
  				"name" => "Khach hang ".$registrationId,
  				"price" => $price,
  				"quantity" => 1,
  				"category" => $category_name,
  				"category_id" => $category_id
  				),
  			)
  		);

  		$token = $config->accesstradeKey;
  		$data_string = json_encode($data);
  		$ch = curl_init($config->urlPostAccessTrade);
  		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
  		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
  		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  			'Content-Type: application/json',
  			'Authorization: Token '.$token.''
  		));
  		$result = curl_exec($ch);
  		$json_result = json_decode($result);
  		$is_success = 0;
  		if($json_result->success === true){
  			$is_success = 1;
  			if($registrationId > 0){
  				$db = JFactory::getDbo();
  				$sql = 'UPDATE #__registration set transaction_id = '.$db->quote($uuid_key).', transaction_success = 1, accesstrade_catid = '.$db->quote($category_id).' WHERE id = '.$db->quote($registrationId);
  				$result2 = $db->setQuery($sql)->execute();

  			}
  		}else{
  			$is_success = 0;
  			if($registrationId > 0){
  				$db = JFactory::getDbo();
  				$sql = 'UPDATE #__registration set transaction_id = '.$db->quote($uuid_key).', transaction_success = 0, accesstrade_catid = '.$db->quote($category_id).' WHERE id = '.$db->quote($registrationId);
  				$result3 = $db->setQuery($sql)->execute();
  			}
  		}
  		return $is_success;
  	}


    public function callApiAccessTradeReject($uuid_key,$registrationId)
  	{
  		$config = new JConfig();
      $transaction_cancel_reason = "Hủy vì trùng với Khách hàng cũ của AccessTrade";
  		$data = array(
  			"transaction_id" => $uuid_key,
        "status" => 2,
        "rejected_reason" => $transaction_cancel_reason,
  			"items" => array (
  				array(
    				"id" => $uuid_key,
    				"status" => 2,
            "extra" => array(
      				"rejected_reason" => $transaction_cancel_reason
      			),
  				),
  			)
  		);
  		$token = $config->accesstradeKey;
  		$data_string = json_encode($data);
  		$ch = curl_init($config->urlPostAccessTrade);
  		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
  		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
  		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  			'Content-Type: application/json',
  			'Authorization: Token '.$token.''
  		));
  		$result = curl_exec($ch);
  		$json_result = json_decode($result);
      $is_success = 0;
      $cancel_date = date("Y-m-d H:i:s");
  		if($json_result->success === true){
  			$is_success = 1;
  			if($registrationId > 0){
  				$db = JFactory::getDbo();
  				$sql = 'UPDATE #__registration set transaction_cancel_reason = '.$db->quote($transaction_cancel_reason).', transaction_status = 2 , transaction_cancel_date = '.$db->quote($cancel_date).'  WHERE id = '.$db->quote($registrationId);
  				$result2 = $db->setQuery($sql)->execute();
  			}
  		}else{
  			$is_success = 0;
  		}
  		return $is_success;
  	}

    public function updateProjectID($registration_id, $project_id)
  	{
  			if($registration_id > 0){
  				$object = new stdClass();
  				$object->id = $registration_id;
  				$object->project_id = $project_id;
  				$result = JFactory::getDbo()->updateObject('#__registration', $object, 'id');
  			}
    }

    public function sendMailWarningMoney($user_id) {
      $user = JFactory::getUser($user_id);
      $paramsEmail = array();
      if($user->id <= 0) {
        return -1;
      }
      if($user->money < 400000) {
        $paramsEmail['name'] = $user->name | $user->username;
        $paramsEmail['subject'] = "Cảnh báo";
        $this->_sendMail('low_money', $user->email, $paramsEmail);
      }

    }

    public function remarketingHandle($phone, $subMoney) {

      $personalRemarketingTime   = PERSONAL_PROJECT_TIME;
      $personalRemarketingMonth  = PERSONAL_PROJECT_MONTH;

      $db = JFactory::getDbo();
      $query = $db->getQuery(true);
      $query->select(array('c.id','r.phone', 'c.project_id', 'c.remarketing_date', 'c.remarketing_time', 'r.created_date', 'c.created_by', '(SELECT COUNT(phone) FROM `#__registration` WHERE phone='.$db->quote($phone).') as time'));
      $query->from('#__registration AS r');
      $query->join('LEFT', '#__customers AS c ON r.phone = c.phone');
      $query->where($db->quoteName('r.phone'). ' = '. $db->quote($phone));
      $query->order('r.id DESC');
      // echo($query->__toString());
      // die();
      $db->setQuery($query, 0, 1);
      $customer = $db->loadObject();

      if(isset($customer) && (int)$customer->project_id === 32) {
        return -1;
      }

      //Xu ly remarketing cho du an ca nhan
      if(isset($customer) && (int)$customer->project_id === 22 && (int)$customer->time > 1) {
        if($customer->remarketing_date === '0000-00-00 00:00:00') {
          return $this->_subMoney($customer, $subMoney, 'newMonth');
        } else {
          $monthDiff = $this->_monthDiff($customer->created_date, $customer->remarketing_date);
          if($monthDiff >= $personalRemarketingMonth) {
            return $this->_subMoney($customer, $subMoney, 'newMonth');
          }

          if($monthDiff < $personalRemarketingMonth) {
            if((int)$customer->remarketing_time < $personalRemarketingTime) {
              return $this->_subMoney($customer, $subMoney, 'oldMonth');
            }
          }

        }
      }

      return null;
    }

    private function _monthDiff($createdDate, $remarketingDate) {
      $createdDate = strtotime($createdDate);
      $remarketingDate = strtotime($remarketingDate);
      $year1 = date('Y', $createdDate);
      $year2 = date('Y', $remarketingDate);

      $month1 = date('m', $createdDate);
      $month2 = date('m', $remarketingDate);

      $diff = (abs($year2 - $year1) * 12) + abs($month2 - $month1);
      return $diff;
    }

    private function _subMoney($customer, $subMoney, $type) {
      switch($type) {
        case 'newMonth':
          //Cap nhat lai Customer
          $updateCustomer = new stdClass();
          $updateCustomer->id = $customer->id;
          $updateCustomer->remarketing_time = 1;
          $updateCustomer->remarketing_date = $customer->created_date;
          $resultUpdateCustomer = JFactory::getDbo()->updateObject('#__customers', $updateCustomer, 'id');
        break;
        case 'oldMonth':
          //Cap nhat lai Customer
          $updateCustomer = new stdClass();
          $updateCustomer->id = $customer->id;
          $updateCustomer->remarketing_time = (int)$customer->remarketing_time + 1;
          $updateCustomer->remarketing_date = $customer->created_date;
          $resultUpdateCustomer = JFactory::getDbo()->updateObject('#__customers', $updateCustomer, 'id');
        break;
      }

      //tru tien
      $user = JFactory::getUser((int)$customer->created_by);
      $newMoney = $user->money - ($subMoney/2);
      $updateUser = new stdClass();
      $updateUser->id = $customer->created_by;
      $updateUser->money = $newMoney;
      $resultUpdateUser = JFactory::getDbo()->updateObject('#__users', $updateUser, 'id');

      $updateHistory = $this->_saveTransactionHistory($customer, $subMoney/2);
      if($resultUpdateUser && $updateHistory === 1 && $resultUpdateCustomer && $resultUpdateCustomer) {
        return 1;
      } else {
        return -1;
      }
    }

    private function _saveTransactionHistory($customer, $subMoney) {
      $obj = new stdClass();
      $obj->state = 1;
      $obj->created_by = $customer->created_by;
      $obj->title = 'Remarketing #' . $customer->phone;
      $obj->amount = 0-$subMoney;
      $obj->created_date = date('Y-m-d H:i:s');
      $obj->type_transaction = 'remarketing';
      $obj->status = 'completed';
      $db = JFactory::getDbo();
      $result = $db->insertObject('#__transaction_history', $obj, 'id');
      if($result) {
        return 1;
      } else {
        return -1;
      }
    }

    public function checkExistAccessTrade($phone)
  	{
      $db = JFactory::getDbo();
  		$query = $db->getQuery(true);
  		$query->select('*');
  		$query->from($db->quoteName('#__customers'));
  		$query->where($db->quoteName('phone') . " = '" .$phone."'");
      $query->where($db->quoteName('project_id') . " = ".AT_PROJECT);
  		$query->where($db->quoteName('state') . " = 1");
  		$query->order('id DESC');
  		$query->setLimit(1);
  		$db->setQuery($query);
  		$result = $db->loadObject();
      return $result;
  	}

    public function updateMoney($user_id, $money){
      $db = JFactory::getDbo();
      $sql = "UPDATE #__users set money = money + " . $money . ' WHERE id = ' . $user_id;
      $result = $db->setQuery($sql)->execute();
      return $result;
    }

    public function saveHistoryRemarketingATProject($user_id, $sub_money, $phone) {
      $obj = new stdClass();
      $obj->state = 1;
      $obj->created_by = $user_id;
      $obj->title = 'Remarketing số Điện thoại: ' . $phone;
      $obj->amount = 0-$sub_money;
      $obj->created_date = date('Y-m-d H:i:s');
      $obj->type_transaction = 'remarketingat';
      $obj->status = 'completed';
      $db = JFactory::getDbo();
      $result = $db->insertObject('#__transaction_history', $obj, 'id');
      if($result) {
        return 1;
      } else {
        return 0;
      }
    }

    public function countPhoneRegistration($phone)
  	{
      $db = JFactory::getDbo();
  		$query = $db->getQuery(true);
  		$query->select('count(*)');
  		$query->from($db->quoteName('#__registration'));
  		$query->where($db->quoteName('phone') . " = '" .$phone."'");
      $query->where($db->quoteName('project_id') . " = ".AT_PROJECT);
  		$query->where($db->quoteName('state') . " = 1");
  		$db->setQuery($query);
  		$result = $db->loadResult();
      return $result;
  	}

    public function updateDuplicateFirstBCA($regis_id)
  	{
      if($regis_id > 0){
        $object = new stdClass();
  			$object->id = $regis_id;
  			$object->duplicate_first_bca = 1;
        $object->duplicate_first_date = date("Y-m-d H:i:s");
  			$result = JFactory::getDbo()->updateObject('#__registration', $object, 'id');
      }
  	}

    public function getSaleIDCustomer($phone)
  	{
      $db = JFactory::getDbo();
  		$query = $db->getQuery(true);
  		$query->select('*');
  		$query->from($db->quoteName('#__customers'));
  		$query->where($db->quoteName('phone') . " = '" .$phone."'");
  		$query->where($db->quoteName('state') . " = 1");
  		$db->setQuery($query);
  		$result = $db->loadObject();
      return $result;
  	}

    public function getUserByID($user_id)
  	{
      $db = JFactory::getDbo();
  		$query = $db->getQuery(true);
  		$query->select('*');
  		$query->from($db->quoteName('#__users'));
  		$query->where($db->quoteName('id') . " = '" .$user_id."'");
  		$db->setQuery($query);
  		$result = $db->loadObject();
      return $result;
  	}

    public function countPhoneRegistrationTpProject($phone)
  	{
      $db = JFactory::getDbo();
  		$query = $db->getQuery(true);
  		$query->select('count(*)');
  		$query->from($db->quoteName('#__registration'));
  		$query->where($db->quoteName('phone') . " = '" .$phone."'");
      $query->where($db->quoteName('project_id') . " = 28"); // TP Project
  		$query->where($db->quoteName('state') . " = 1");
  		$db->setQuery($query);
  		$result = $db->loadResult();
      return $result;
  	}

    public function countPhoneRegistrationProject($phone,$project_id)
  	{
      $db = JFactory::getDbo();
  		$query = $db->getQuery(true);
  		$query->select('count(*)');
  		$query->from($db->quoteName('#__registration'));
  		$query->where($db->quoteName('phone') . " = '" .$phone."'");
      $query->where($db->quoteName('project_id') . " = ".$project_id); // TP Project
  		$query->where($db->quoteName('state') . " = 1");
  		$db->setQuery($query);
  		$result = $db->loadResult();
      return $result;
  	}

    public function getUserLandingpage($userid){
  		$data['userid'] = $userid;
  		$param = $data;
  		if($_SERVER['HTTP_HOST'] == 'localhost'){
  				$url_biznet = 'http://localhost/biznetweb';
  		}else{
  			$url_biznet = 'https://biznet.com.vn';
  		}

  		// URL có chứa hai thông tin name và diachi
  		$url = $url_biznet.'/index.php?option=com_registration&task=registrationform.checkUserLandingpage';
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
