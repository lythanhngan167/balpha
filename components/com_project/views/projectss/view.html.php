<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Project
 * @author     tung hoang <tungvacc@gmail.com>
 * @copyright  2017 tung hoang
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View class for a list of Project.
 *
 * @since  1.6
 */
class ProjectViewProjectss extends JViewLegacy
{
    protected $items;

    protected $pagination;

    protected $state;

    protected $params;

    /**
     * Display the view
     *
     * @param string $tpl Template name
     *
     * @return void
     *
     * @throws Exception
     */
    public function display($tpl = null)
    {
      // $model = $this->getModel('Projectss', 'ProjectModel');
      // $countContactToday = $model->getCountCustomerDataToday(2351, $_GET['projectid'], 151);
      // print_r($countContactToday);
      // die;

        if ($_GET['ajax'] == 1) {
            if ($_GET['type'] == 'createOrder') {
                $user = JFactory::getUser();
                $model = $this->getModel('Projectss', 'ProjectModel');
                $countContact = $model->getCountByCat($_GET['projectid'], $_GET['catid']);
                if ($_GET['quantity'] > $countContact) {
                    echo "-2"; // no data to buy
                    exit();
                }


                $db = JFactory::getDbo();
                if ($user->id > 0) {

                    $projectInfo = $this->getProjectByID($_GET['projectid']);

                    $projectInfo['price'] = $_GET['catid'] != DATA_RETURN ? $projectInfo['price'] : 0;
                    $totalPrice = $projectInfo['price'] * $_GET['quantity'];


                    if ($this->getMoney($user->id) < $totalPrice) {
                        echo '-4'; // charge to buy
                        exit();
                    }

                    $level = $user->level;

                    if($_GET['catid'] > 0 && $level > 0){
                      $max_pick_cat = $_GET['max_pick_'.$_GET['catid']];
                      $maxPickLevel = $this->getMaxPickByCat($_GET['catid'], $level);

                      $model = $this->getModel('Projectss', 'ProjectModel');
                      $countContactToday = $model->getCountCustomerDataToday($user->id, $_GET['projectid'], $_GET['catid']);
                      if($countContactToday >= $maxPickLevel){
                        echo '-6'; // over to buy
                        exit();
                      }
                    }

                    if ($_GET['quantity'] > 0) {
                      try{
                        $db->transactionStart();
                        $randomCustomer = $this->getRandomCustomer($_GET['projectid'], $_GET['catid'], $_GET['quantity']);

                        if (count($randomCustomer) != $_GET['quantity']) {
                            echo '-5'; // no data to buy
                            exit();
                        }

                        $checkAssign = true;
                        foreach ($randomCustomer as $customer) {
                            $checkReadyAssign = $this->checkAssignForSale($customer->id);
                            if($checkReadyAssign == 0){
                              $checkAssign = false;
                            }
                        }

                        if ($checkAssign == false) {
                            echo '-5'; // no data to buy
                            exit();
                        }

                        $arrCustomer = array();
                        foreach ($randomCustomer as $customer) {
                            $arrCustomer[] = $customer->id;
                            $this->asignCustomerToSale($user->id, $customer->id);
                        }
                        if (count($arrCustomer) > 0) {
                            $data = array();
                            $data['created_by'] = $user->id;
                            $data['category_id'] = $_GET['catid'];
                            $data['price'] = $data['category_id'] != DATA_RETURN ? $projectInfo['price'] : 0;
                            $data['quantity'] = $_GET['quantity'];
                            $data['project_id'] = $_GET['projectid'];
                            $list_customer = implode(",", $arrCustomer);
                            $data['list_customer'] = $list_customer;
                            $order_id = $model->createOrder($data);

                            if ($order_id) {
                                // Add history
                                $obj = new stdClass();
                                $obj->state = 1;
                                $obj->created_by = $user->id;
                                $obj->title = 'Mua dữ liệu #' . $order_id;
                                $obj->amount = 0 - $totalPrice;
                                $obj->created_date = date('Y-m-d H:i:s');
                                $obj->type_transaction = 'buydata';
                                $obj->status = 'completed';
                                $obj->reference_id = $order_id;

                                if($user->id > 0){
                                  $userSaleHistory   = $this->getUserByID($user->id);
                                  $obj->current_money = $userSaleHistory->money - $totalPrice;
                                  $obj->current_money_before_operation = $userSaleHistory->money;
                                }

                                $db = JFactory::getDbo();
                                $db->insertObject('#__transaction_history', $obj, 'id');

                                // Descrease money
                                $sql = "UPDATE #__users set money = money - " . $totalPrice . ' WHERE id = ' . $user->id;
                                $db->setQuery($sql)->execute();

                                $strtime_buydate =  strtotime($user->buydate);
                                $buydate = date("Y-m-d H:i:s");

                                $quantityBuy = $_GET['quantity'];
                                if(date("Y-m-d",$strtime_buydate) != date('Y-m-d')){
                                  $sql = "UPDATE #__users set buytoday = " . $quantityBuy . " , buydate = '".$buydate."' WHERE id = " . $user->id;
                                  $db->setQuery($sql)->execute();
                                }else{
                                  $sql = "UPDATE #__users set buytoday = buytoday + " . $quantityBuy . " , buydate = '".$buydate."' WHERE id = " . $user->id;
                                  $db->setQuery($sql)->execute();
                                }
                                $model = $this->getModel('Projectss', 'ProjectModel');
                                $model->updateBuyAll($user->id, $quantityBuy);
                                echo '1';

                            } else {
                                echo '0';
                            }

                        }
                        $db->transactionCommit();
                      }catch (Exception $e){
                        // catch any database errors.
                        $db->transactionRollback();
                        JErrorPage::render($e);
                      }

                    }

                } else {
                    echo "-1";
                }
                exit();
            }
            if ($_GET['type'] == 'checkPickToday') {
                $user = JFactory::getUser();
                $model = $this->getModel('Projectss', 'ProjectModel');
                if ($user->id > 0) {
                    // kiem tra dang cham soc
                    $caring = $model->getCaringCustomer($user->id, $_GET['projectid']);
                    $config = JFactory::getConfig();
                    $app = JFactory::getApplication();
                    $cparams = $app->getParams('com_crm_config');
                    $maxpick = $cparams->get('maxpick');
                    $maxCaring = $maxpick;
                    //$config->get('maxforsale');

                    $projectInfo = $this->getProjectByID($_GET['projectid']);
                    $projectInfo['price'] =  $_GET['catid'] != DATA_RETURN ? $projectInfo['price'] : 0;
                    $totalPrice = $projectInfo['price'] * $_GET['quantity'];

                    if ($this->getMoney($user->id) < $totalPrice) {
                        echo '-4';
                        exit();
                    }

                    if ($caring >= $maxCaring) {
                        echo "-3";
                        exit();
                    }

                    $level = $user->level;
                    $maxPick = $this->getMaxPickByCat($_GET['catid'], $level);
                    $countContactToday = $model->getCountCustomerDataToday($user->id, $_GET['projectid'], $_GET['catid']);

                    if($countContactToday >= $maxPick){
                      echo '-6';
                      exit();
                    }else{
                      $restCustomer = $maxPick - $countContactToday;
                      if ($_GET['quantity'] <= $restCustomer) {
                          echo "-2";
                      } else {
                          echo $restCustomer; // rest to buy
                      }
                      exit();
                    }

                } else {
                    echo "-1"; // no login
                    exit();
                }
                exit();
            }

            if ($_GET['type'] == 'setAutoBuy') {
              $user = JFactory::getUser();
              $model = $this->getModel('Projectss', 'ProjectModel');
              $on_off = 0;
              if ($user->id > 0) {
                $on_off = $_REQUEST['on_off'];
                if(isset($on_off)){
                  $isOk = $model->setAutoBuy($user->id,$on_off);
                  if($isOk){
                    echo "-2"; // ok
                  }else{
                    echo "-3"; // co loi
                  }
                }

              }else{
                  echo "-1"; // chua dang nhap
              }
              exit();
           }

        } else {
            $app = JFactory::getApplication();

            $this->state = $this->get('State');
            $this->items = $this->get('Items');
            $this->pagination = $this->get('Pagination');
            $this->params = $app->getParams('com_project');
            $this->filterForm = $this->get('FilterForm');
            $this->activeFilters = $this->get('ActiveFilters');

            $model = &$this->getModel('Projectss', 'ProjectModel');
            $user = JFactory::getUser();
            $totalCaring = $model->getTotalCaring($user->id);
            $this->totalCaring = $totalCaring;
            // Check for errors.
            if (count($errors = $this->get('Errors'))) {
                throw new Exception(implode("\n", $errors));
            }

            $this->_prepareDocument();
            parent::display($tpl);
        }
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
        $app = JFactory::getApplication();
        $menus = $app->getMenu();
        $title = null;

        // Because the application sets a default page title,
        // we need to get it from the menu item itself
        $menu = $menus->getActive();

        if ($menu) {
            $this->params->def('page_heading', $this->params->get('page_title', $menu->title));
        } else {
            $this->params->def('page_heading', JText::_('COM_PROJECT_DEFAULT_PAGE_TITLE'));
        }

        $title = $this->params->get('page_title', '');

        if (empty($title)) {
            $title = $app->get('sitename');
        } elseif ($app->get('sitename_pagetitles', 0) == 1) {
            $title = JText::sprintf('JPAGETITLE', $app->get('sitename'), $title);
        } elseif ($app->get('sitename_pagetitles', 0) == 2) {
            $title = JText::sprintf('JPAGETITLE', $title, $app->get('sitename'));
        }

        $this->document->setTitle($title);

        if ($this->params->get('menu-meta_description')) {
            $this->document->setDescription($this->params->get('menu-meta_description'));
        }

        if ($this->params->get('menu-meta_keywords')) {
            $this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
        }

        if ($this->params->get('robots')) {
            $this->document->setMetadata('robots', $this->params->get('robots'));
        }
    }

    /**
     * Check if state is set
     *
     * @param mixed $state State
     *
     * @return bool
     */
    public function getState($state)
    {
        return isset($this->state->{$state}) ? $this->state->{$state} : false;
    }

    public function getCountByCat($project_id, $cat_id)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('count(*) as number');
        $query->from($db->quoteName('#__customers'));
        $query->where($db->quoteName('project_id') . " = " . $project_id, 'AND');
        $query->where($db->quoteName('category_id') . " = " . $cat_id, "AND");
        $query->where('(`sale_id` = 0 OR `payback` = 1 )', "AND");
        $query->where($db->quoteName('state') . " = 1");
        $db->setQuery($query);
        $result = $db->loadResult();
        return $result;
    }

    public function getProjectByID($project_id)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('is_recruitment,price');
        $query->from($db->quoteName('#__projects'));
        $query->where($db->quoteName('id') . " = " . $project_id);
        $query->where($db->quoteName('state') . " = 1");
        $db->setQuery($query);
        $result = $db->loadAssoc();
        return $result;
    }

    public function getPriceByCat($cat_id)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('note');
        $query->from($db->quoteName('#__categories'));
        $query->where($db->quoteName('id') . " = " . $cat_id);
        $db->setQuery($query);
        $result = $db->loadResult();
        return $result;
    }

    public function getMaxPickByCat($cat_id, $level)
    {

        if ($level == 1) {
            $levelid = 167;
        }
        if ($level == 2) {
            $levelid = 168;
        }
        if ($level == 3) {
            $levelid = 169;
        }
        if ($level == 4) {
            $levelid = 170;
        }
        if ($level == 5) {
            $levelid = 171;
        }

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('maxpick');
        $query->from($db->quoteName('#__maxpick_level'));
        $query->where($db->quoteName('level') . " = " . $levelid);
        $query->where($db->quoteName('category_customer') . " = " . $cat_id);
        $db->setQuery($query);

        $result = $db->loadResult();
        if ($result <= 0) {
            $config = JFactory::getConfig();
            $app = JFactory::getApplication();
            $cparams = $app->getParams('com_maxpick_level');
            $maxpickdefault = $cparams->get('maxpickdefault');
            return $maxpickdefault;
        } else {
            return $result;
        }

    }

    public function getListCustomers($pid)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select('*');
        $query->from($db->quoteName('#__customers'));
        $query->where($db->quoteName('project_id') . " = " . $db->quote($pid));
        $db->setQuery($query);
        $result = $db->loadObjectList();
        return $result;
    }

    public function getListCategories()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select('*');
        $query->from($db->quoteName('#__categories'));
        $query->where($db->quoteName('extension') . " =  'com_customer'");
        $query->where($db->quoteName('published') . " =  1");
        //$query->andWhere('id = ' . DATA_NEW);
        $query->order('rgt ASC');
        $db->setQuery($query);
        $result = $db->loadObjectList();
        return $result;
    }

    public function updateOrder($data)
    {
        // Insert the object into the user profile table.
        $order = new stdClass();

        $order->id = $data['id'];
        $order->list_customer = $data['list_customer'];

        // Must be a valid primary key value.


        // Update their details in the users table using id as the primary key.
        $result = JFactory::getDbo()->updateObject('#__orders', $order, 'id');
        if ($result) {
            return 1;
        } else {
            return 0;
        }
    }

    public function getMoney($uid)
    {

        return JFactory::getDbo()->setQuery('SELECT money FROM #__users WHERE id = ' . $uid)->loadResult();
    }

    public function getRandomCustomer($projectid, $catid, $quantity)
    {
        $model = $this->getModel('Projectss', 'ProjectModel');
        $randomCustomer = $model->getRandomCustomer($projectid, $catid, $quantity);
        return $randomCustomer;
    }

    public function checkReadytoHoldCustomerForSale($customerid)
    {
        $model = $this->getModel('Projectss', 'ProjectModel');
        $ready = $model->checkReadytoHoldCustomerForSale($customerid);
        return $ready;
    }

    public function holdCustomerForSale($saleid, $customerid)
    {
        $model = $this->getModel('Projectss', 'ProjectModel');
        $holdStatus = $model->holdCustomerForSale($saleid, $customerid);
        return $holdStatus;
    }

    public function unHoldCustomerForSale($saleid, $customerid)
    {
        $model = $this->getModel('Projectss', 'ProjectModel');
        $unHoldStatus = $model->unHoldCustomerForSale($saleid, $customerid);
        return $unHoldStatus;
    }


    public function checkReadytoUnHoldCustomerForSale($saleid,$customerid)
    {
        $model = $this->getModel('Projectss', 'ProjectModel');
        $ready = $model->checkReadytoUnHoldCustomerForSale($saleid,$customerid);
        return $ready;
    }


    public function asignCustomerToSale($saleid, $customerid)
    {
        $model = $this->getModel('Projectss', 'ProjectModel');
        $asignCustomer = $model->asignCustomerToSale($saleid, $customerid);
        return $asignCustomer;
    }

    public function getCaringCustomer($userid, $projectid)
    {
        $model = $this->getModel('Projectss', 'ProjectModel');
        $caring = $model->getCaringCustomer($userid, $projectid);
        return $caring;
    }

    public function checkAssignForSale($customerid)
    {
        $model = $this->getModel('Projectss', 'ProjectModel');
        $check = $model->checkAssignForSale($customerid);
        return $check;
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


}
