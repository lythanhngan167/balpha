  <?php
  /**
   * @package API plugins
   * @copyright Copyright (C) 2009 2014 Techjoomla, Tekdi Technologies Pvt. Ltd. All rights reserved.
   * @license GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
   * @link http://www.techjoomla.com
   */

  defined('_JEXEC') or die('Restricted access');

  jimport('joomla.plugin.plugin');
  /**
   * @OA\Info(title="BCA API", version="0.1")
   */

  /**
   * @OA\Schema(
   *     schema="ErrorModel",
   *     required={"err_msg", "err_code"},
   *     @OA\Property(
   *         property="err_code",
   *         type="integer",
   *         format="int32"
   *     ),
   *     @OA\Property(
   *         property="err_msg",
   *         type="string"
   *     )
   * )
   */

  /**
   * @OA\SecurityScheme(
   *   securityScheme="bearerAuth",
   *   type="http",
   *   in="header",
   *   scheme="bearer",
   *   name="Authorization"
   * )
   */
  class plgAPIUsers extends ApiPlugin
  {
    public function __construct(&$subject, $config = array())
    {
      parent::__construct($subject, $config = array());

      ApiResource::addIncludePath(dirname(__FILE__) . '/users');
      ApiResource::addIncludePath(dirname(__FILE__) . '/contents');
      ApiResource::addIncludePath(dirname(__FILE__) . '/projects');
      ApiResource::addIncludePath(dirname(__FILE__) . '/buycustomers');
      ApiResource::addIncludePath(dirname(__FILE__) . '/customers');
      ApiResource::addIncludePath(dirname(__FILE__) . '/reports');
      ApiResource::addIncludePath(dirname(__FILE__) . '/orders');
      ApiResource::addIncludePath(dirname(__FILE__) . '/status');
      ApiResource::addIncludePath(dirname(__FILE__) . '/address');
      ApiResource::addIncludePath(dirname(__FILE__) . '/notes');
      ApiResource::addIncludePath(dirname(__FILE__) . '/history');
      ApiResource::addIncludePath(dirname(__FILE__) . '/revenue');
      ApiResource::addIncludePath(dirname(__FILE__) . '/statictis');
      ApiResource::addIncludePath(dirname(__FILE__) . '/home');
      ApiResource::addIncludePath(dirname(__FILE__) . '/config');
      ApiResource::addIncludePath(dirname(__FILE__) . '/notify');
      ApiResource::addIncludePath(dirname(__FILE__) . '/cdocument');
      ApiResource::addIncludePath(dirname(__FILE__) . '/documents');
      ApiResource::addIncludePath(dirname(__FILE__) . '/requests');
      ApiResource::addIncludePath(dirname(__FILE__) . '/received');
      ApiResource::addIncludePath(dirname(__FILE__) . '/addrequest');
      ApiResource::addIncludePath(dirname(__FILE__) . '/shop');
      ApiResource::addIncludePath(dirname(__FILE__) . '/test');
      ApiResource::addIncludePath(dirname(__FILE__) . '/money');
      ApiResource::addIncludePath(dirname(__FILE__) . '/k2category');
      ApiResource::addIncludePath(dirname(__FILE__) . '/favoriteservice');
      ApiResource::addIncludePath(dirname(__FILE__) . '/requestpackage');
      ApiResource::addIncludePath(dirname(__FILE__) . '/autobuy');
      ApiResource::addIncludePath(dirname(__FILE__) . '/getautobuy');
      ApiResource::addIncludePath(dirname(__FILE__) . '/registrations');
      ApiResource::addIncludePath(dirname(__FILE__) . '/requestpackagelist');
      ApiResource::addIncludePath(dirname(__FILE__) . '/userseennotification');
      ApiResource::addIncludePath(dirname(__FILE__) . '/usernotification');
      ApiResource::addIncludePath(dirname(__FILE__) . '/erp');

      // Set the login resource to be public
      $this->setResourceAccess('login', 'public', 'POST');
      $this->setResourceAccess('forgotpassword', 'public', 'POST');
      $this->setResourceAccess('resetpassword', 'public', 'POST');
      $this->setResourceAccess('renewtoken', 'public', 'POST');
      $this->setResourceAccess('contents', 'public', 'GET');
      $this->setResourceAccess('home', 'public', 'GET');
      $this->setResourceAccess('config', 'public', 'GET');
      $this->setResourceAccess('extra', 'public', 'GET');
      $this->setResourceAccess('documents', 'public', 'POST');
      $this->setResourceAccess('notify', 'public', 'GET');
      $this->setResourceAccess('notifyguest', 'public', 'POST');
      $this->setResourceAccess('notifydetail', 'public', 'GET');
      $this->setResourceAccess('cdocument', 'public', 'GET');
      $this->setResourceAccess('cdocument', 'public', 'POST');
      $this->setResourceAccess('contents', 'public', 'POST');
      $this->setResourceAccess('register', 'public', 'POST');
      $this->setResourceAccess('confirmregister', 'public', 'POST');
      $this->setResourceAccess('confirmphoneuser', 'public', 'POST');
      $this->setResourceAccess('shopcategories', 'public', 'GET');
      $this->setResourceAccess('shopcampaign', 'public', 'GET');
      $this->setResourceAccess('shopcampaign', 'public', 'POST');
      $this->setResourceAccess('shopproducts', 'public', 'POST');
      $this->setResourceAccess('shopproductdetail', 'public', 'GET');
      $this->setResourceAccess('shopcountry', 'public', 'GET');
      $this->setResourceAccess('shopzone', 'public', 'GET');
      $this->setResourceAccess('shopcheckout', 'public', 'GET');
      $this->setResourceAccess('shopviewed', 'public', 'GET');
      $this->setResourceAccess('shoporderstatus', 'public', 'GET');
      $this->setResourceAccess('shopreport', 'public', 'GET');
      $this->setResourceAccess('test', 'public', 'GET');
      $this->setResourceAccess('bookform', 'public', 'GET');
      $this->setResourceAccess('shopshipghtk', 'public', 'GET');
      $this->setResourceAccess('stock', 'public', 'POST');
      $this->setResourceAccess('synccart', 'public', 'POST');
      $this->setResourceAccess('checkusersocial', 'public', 'POST');
      $this->setResourceAccess('social', 'public', 'POST');
      $this->setResourceAccess('validatesocialcode', 'public', 'POST');
      $this->setResourceAccess('resendregistersocial', 'public', 'POST');
      $this->setResourceAccess('sendforgottoken', 'public', 'POST');
      $this->setResourceAccess('resetpasswordphone', 'public', 'POST');
      $this->setResourceAccess('checkname', 'public', 'POST');
      $this->setResourceAccess('k2category', 'public', 'POST');
      $this->setResourceAccess('favoriteservice', 'public', 'POST');
      $this->setResourceAccess('k2details', 'public', 'POST');
      $this->setResourceAccess('updatephoneuser', 'public', 'POST');
      $this->setResourceAccess('requestpackagelist', 'protected', 'POST');
      $this->setResourceAccess('usernotification', 'protected', 'POST');
      $this->setResourceAccess('userseennotification', 'protected', 'POST');
      $this->setResourceAccess('findagency', 'protected', 'GET');
      $this->setResourceAccess('changeagency', 'protected', 'POST');
      //$this->setResourceAccess('autobuy', 'public', 'POST');
      //$this->setResourceAccess('shopcoupon', 'public', 'POST');
      //ERP[start]
      $this->setResourceAccess('registeragency', 'protected', 'POST');
      $this->setResourceAccess('blockupdate', 'protected', 'POST');
      $this->setResourceAccess('updateleveltree', 'protected', 'POST');
      $this->setResourceAccess('updateinviteid', 'protected', 'POST');
      $this->setResourceAccess('updatephonebyidbiznet', 'protected', 'POST');
      $this->setResourceAccess('updateidbiznetbyphone', 'protected', 'POST');
      //ERP[end]
    }
  }
